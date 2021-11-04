<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\forum\admin\controllers\topic;

// Yii Imports
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\forum\common\config\ForumGlobal;

use cmsgears\core\common\models\resources\File;

use cmsgears\core\common\behaviors\ActivityBehavior;

/**
 * ReplyController provides actions specific to topic reply model.
 *
 * @since 1.0.0
 */
class ReplyController extends \cmsgears\core\admin\controllers\base\Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	protected $topicService;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Views
        $this->setViewPath( '@cmsgears/module-forum/admin/views/topic/reply' );

		// Permission
		$this->crudPermission = ForumGlobal::PERM_FORUM_ADMIN;

		// Config
		$this->apixBase = 'forum/topic/reply';

        // Services
        $this->modelService	= Yii::$app->factory->get( 'topicReplyService' );
		$this->topicService	= Yii::$app->factory->get( 'topicService' );

        // Sidebar
        $this->sidebar = [ 'parent' => 'sidebar-forum', 'child' => 'topic' ];

		// Return Url
		$this->returnUrl = Url::previous( 'topic-replies' );
		$this->returnUrl = isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/forum/topic/reply/all' ], true );

		// Topic Url
		$topicUrl = Url::previous( 'topics' );
		$topicUrl = isset( $topicUrl ) ? $topicUrl : Url::toRoute( [ '/forum/topic/all' ], true );

        // Breadcrumbs
		$this->breadcrumbs = [
			'base' => [ [ 'label' => 'Topics', 'url' =>  $topicUrl ] ],
			'all' => [ [ 'label' => 'Replies' ] ],
			'create' => [ [ 'label' => 'Replies', 'url' => $this->returnUrl ], [ 'label' => 'Add' ] ],
			'update' => [ [ 'label' => 'Replies', 'url' => $this->returnUrl ], [ 'label' => 'Update' ] ],
			'delete' => [ [ 'label' => 'Replies', 'url' => $this->returnUrl ], [ 'label' => 'Delete' ] ]
		];
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	public function behaviors() {

		return [
			'rbac' => [
				'class' => Yii::$app->core->getRbacFilterClass(),
				'actions' => [
					'index' => [ 'permission' => $this->crudPermission ],
					'all' => [ 'permission' => $this->crudPermission ],
					'create' => [ 'permission' => $this->crudPermission ],
					'update' => [ 'permission' => $this->crudPermission ],
					'delete' => [ 'permission' => $this->crudPermission ]
				]
			],
			'verbs' => [
				'class' => VerbFilter::class,
				'actions' => [
					'index' => [ 'get', 'post' ],
					'all'  => [ 'get' ],
					'create'  => [ 'get', 'post' ],
					'update'  => [ 'get', 'post' ],
					'delete'  => [ 'get', 'post' ]
				]
			],
			'activity' => [
				'class' => ActivityBehavior::class,
				'admin' => true,
				'create' => [ 'create' ],
				'update' => [ 'update' ],
				'delete' => [ 'delete' ]
			]
		];
	}

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// AccountController ---------------------

	public function actionAll( $tid ) {

		Url::remember( Yii::$app->request->getUrl(), 'topic-replies' );

		$modelClass = $this->modelService->getModelClass();

		$topic = $this->topicService->getById( $tid );

		$dataProvider = $this->modelService->getPageByTopicId( $tid ) ;

		return $this->render( 'all', [
			'dataProvider' => $dataProvider,
			'topic' => $topic,
			'statusMap' => $modelClass::$statusMap
		]);
	}

	public function actionCreate( $tid ) {

		$modelClass = $this->modelService->getModelClass();

		$model	= new $modelClass;
		$topic	= $this->topicService->getById( $tid );

		$model->forumId	= $topic->forumId;
		$model->topicId	= $topic->id;

		$avatar	= File::loadFile( null, 'Avatar' );
		$banner	= File::loadFile( null, 'Banner' );
		$video	= File::loadFile( null, 'Video' );

		if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

			$this->model = $this->modelService->create( $model, [
				'admin' => true, 'avatar' => $avatar, 'banner' => $banner, 'video' => $video
			]);

			return $this->redirect( $this->returnUrl );
		}

		return $this->render( 'create', [
			'topic' => $topic,
			'model' => $model,
			'avatar' => $avatar,
			'banner' => $banner,
			'video' => $video,
			'statusMap' => $modelClass::$statusMap
		]);
	}

	public function actionUpdate( $id ) {

		$model = $this->modelService->getById( $id );

		if( isset( $model ) ) {

			$modelClass = $this->modelService->getModelClass();

			$avatar	= File::loadFile( $model->avatar, 'Avatar' );
			$banner	= File::loadFile( $model->banner, 'Banner' );
			$video	= File::loadFile( $model->video, 'Video' );

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

				$this->model = $this->modelService->update( $model, [ 'admin' => true ] );

				return $this->redirect( $this->returnUrl );
			}

			return $this->render( 'update', [
				'model' => $model,
				'avatar' => $avatar,
				'banner' => $banner,
				'video' => $video,
				'statusMap' => $modelClass::$statusMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionDelete( $id ) {

		// Find Model
		$model = $this->modelService->getById( $id );

		// Delete/Render if exist
		if( isset( $model ) ) {

			$modelClass = $this->modelService->getModelClass();

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) ) {

				try {

					$this->model = $model;

					$this->modelService->delete( $model, [ 'admin' => true ] );

					return $this->redirect( $this->returnUrl );
				}
				catch( Exception $e ) {

					throw new HttpException( 409, Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_DEPENDENCY )  );
				}
			}

			return $this->render( 'delete', [
				'model' => $model,
				'statusMap' => $modelClass::$statusMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
