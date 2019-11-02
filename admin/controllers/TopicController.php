<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\forum\admin\controllers;

// Yii Imports
use Yii;
use yii\helpers\Url;
use yii\base\Exception;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\forum\common\config\ForumGlobal;

use cmsgears\core\common\models\resources\File;

use cmsgears\core\common\behaviors\ActivityBehavior;

/**
 * TopicController provides actions specific to topic model.
 *
 * @since 1.0.0
 */
class TopicController extends \cmsgears\core\admin\controllers\base\CrudController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	public $title;

	public $metaService;

	// Protected --------------

	protected $type;
	protected $templateType;
	protected $prettyReview;

	protected $templateService;

	protected $modelContentService;
	protected $modelCategoryService;

	// Private------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// View Path
		$this->setViewPath( '@cmsgears/module-forum/admin/views/topic' );

		// Permission
		$this->crudPermission = ForumGlobal::PERM_FORUM_ADMIN;

		// Config
		$this->type			= CoreGlobal::TYPE_DEFAULT;
		$this->templateType	= ForumGlobal::TYPE_TOPIC;
		$this->title		= 'Topic';
		$this->baseUrl		= 'topic';
		$this->apixBase		= 'forum/topic';
		$this->prettyReview	= false;

		// Services
		$this->modelService		= Yii::$app->factory->get( 'topicService' );
		$this->metaService		= Yii::$app->factory->get( 'topicMetaService' );
		$this->templateService	= Yii::$app->factory->get( 'templateService' );

		$this->modelContentService	= Yii::$app->factory->get( 'modelContentService' );
		$this->modelCategoryService	= Yii::$app->factory->get( 'modelCategoryService' );

		// Sidebar
		$this->sidebar = [ 'parent' => 'sidebar-forum', 'child' => 'topic' ];

		// Return Url
		$this->returnUrl = Url::previous( 'topics' );
		$this->returnUrl = isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/forum/topic/all' ], true );

		// Breadcrumbs
		$this->breadcrumbs = [
			'base' => [
				[ 'label' => 'Home', 'url' => Url::toRoute( '/dashboard' ) ]
			],
			'all' => [ [ 'label' => 'Topics' ] ],
			'create' => [ [ 'label' => 'Topics', 'url' => $this->returnUrl ], [ 'label' => 'Add' ] ],
			'update' => [ [ 'label' => 'Topics', 'url' => $this->returnUrl ], [ 'label' => 'Update' ] ],
			'delete' => [ [ 'label' => 'Topics', 'url' => $this->returnUrl ], [ 'label' => 'Delete' ] ],
			'review' => [ [ 'label' => 'Topics', 'url' => $this->returnUrl ], [ 'label' => 'Review' ] ],
			'gallery' => [ [ 'label' => 'Topics', 'url' => $this->returnUrl ], [ 'label' => 'Gallery' ] ],
			'data' => [ [ 'label' => 'Topics', 'url' => $this->returnUrl ], [ 'label' => 'Data' ] ],
			'config' => [ [ 'label' => 'Topics', 'url' => $this->returnUrl ], [ 'label' => 'Config' ] ],
			'settings' => [ [ 'label' => 'Topics', 'url' => $this->returnUrl ], [ 'label' => 'Settings' ] ]
		];
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	public function behaviors() {

		$behaviors = parent::behaviors();

		$behaviors[ 'rbac' ][ 'actions' ][ 'review' ] = [ 'permission' => $this->crudPermission ];
		$behaviors[ 'rbac' ][ 'actions' ][ 'gallery' ] = [ 'permission' => $this->crudPermission ];

		$behaviors[ 'verbs' ][ 'actions' ][ 'review' ] = [ 'get', 'post' ];
		$behaviors[ 'verbs' ][ 'actions' ][ 'gallery' ] = [ 'get', 'post' ];

		$behaviors[ 'activity' ] = [
			'class' => ActivityBehavior::class,
			'admin' => true,
			'create' => [ 'create' ],
			'update' => [ 'update' ],
			'delete' => [ 'delete' ]
		];

		return $behaviors;
	}

	// yii\base\Controller ----

	public function actions() {

		return [
			'gallery' => [ 'class' => 'cmsgears\cms\common\actions\regular\gallery\Browse' ]
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// TopicController -----------------------

	public function actionAll( $config = [] ) {

		Url::remember( Yii::$app->request->getUrl(), 'topics' );

		$modelClass = $this->modelService->getModelClass();

		$dataProvider = $this->modelService->getPageByType( $this->type );

		return $this->render( 'all', [
			'dataProvider' => $dataProvider,
			'visibilityMap' => $modelClass::$visibilityMap,
			'statusMap' => $modelClass::$statusMap
		]);
	}

	public function actionCreate( $config = [] ) {

		$modelClass = $this->modelService->getModelClass();

		$model = new $modelClass;

		$model->siteId	= Yii::$app->core->siteId;
		$model->type	= $this->type;

		$content = $this->modelContentService->getModelObject();

		$avatar	 = File::loadFile( null, 'Avatar' );
		$banner	 = File::loadFile( null, 'Banner' );
		$video	 = File::loadFile( null, 'Video' );

		if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $content->load( Yii::$app->request->post(), $content->getClassName() ) &&
			$model->validate() && $content->validate() ) {

			$this->model = $this->modelService->add( $model, [
				'admin' => true, 'content' => $content,
				'avatar' => $avatar, 'banner' => $banner, 'video' => $video
			]);

			return $this->redirect( 'all' );
		}

		$templatesMap = $this->templateService->getIdNameMapByType( $this->templateType, [ 'default' => true ] );

		return $this->render( 'create', [
			'model' => $model,
			'content' => $content,
			'avatar' => $avatar,
			'banner' => $banner,
			'video' => $video,
			'visibilityMap' => $modelClass::$visibilityMap,
			'statusMap' => $modelClass::$statusMap,
			'templatesMap' => $templatesMap
		]);
	}

	public function actionUpdate( $id, $config = [] ) {

		$modelClass = $this->modelService->getModelClass();

		$model = $this->modelService->getById( $id );

		// Update/Render if exist
		if( isset( $model ) ) {

			$content = $model->modelContent;

			$avatar	 = File::loadFile( $model->avatar, 'Avatar' );
			$banner	 = File::loadFile( $content->banner, 'Banner' );
			$video	 = File::loadFile( $content->video, 'Video' );

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $content->load( Yii::$app->request->post(), $content->getClassName() ) &&
				$model->validate() && $content->validate() ) {

				// Update Topic
				$this->model = $this->modelService->update( $model, [
					'admin' => true, 'content' => $content,
					'avatar' => $avatar, 'banner' => $banner, 'video' => $video
				]);

				return $this->redirect( $this->returnUrl );
			}

			$templatesMap = $this->templateService->getIdNameMapByType( $this->templateType, [ 'default' => true ] );

			return $this->render( 'update', [
				'model' => $model,
				'content' => $content,
				'avatar' => $avatar,
				'banner' => $banner,
				'video' => $video,
				'visibilityMap' => $modelClass::$visibilityMap,
				'statusMap' => $modelClass::$statusMap,
				'templatesMap' => $templatesMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionDelete( $id, $config = [] ) {

		$modelClass = $this->modelService->getModelClass();

		$model = $this->modelService->getById( $id );

		// Delete/Render if exist
		if( isset( $model ) ) {

			$content = $model->modelContent;

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

			$templatesMap = $this->templateService->getIdNameMapByType( $this->templateType, [ 'default' => true ] );

			return $this->render( 'delete', [
				'model' => $model,
				'content' => $content,
				'avatar' => $model->avatar,
				'banner' => $content->banner,
				'video' => $content->video,
				'visibilityMap' => $modelClass::$visibilityMap,
				'statusMap' => $modelClass::$statusMap,
				'templatesMap' => $templatesMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionReview( $id ) {

		$modelClass = $this->modelService->getModelClass();

		$model = $this->modelService->getById( $id );

		// Render if exist
		if( isset( $model ) ) {

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) ) {

				$status		= Yii::$app->request->post( 'status' );
				$email		= $this->modelService->getEmail( $model );
				$message	= Yii::$app->request->post( 'message' );

				switch( $status ) {

					case $modelClass::STATUS_ACCEPTED: {

						$this->modelService->accept( $model, [ 'notify' => false ] );

						Yii::$app->coreMailer->sendAcceptMail( $model, $email, $message );

						break;
					}
					case $modelClass::STATUS_SUBMITTED: {

						$this->modelService->approve( $model, [ 'notify' => false ] );

						Yii::$app->coreMailer->sendApproveMail( $model, $email, $message );

						break;
					}
					case $modelClass::STATUS_REJECTED: {

						$model->setRejectMessage( $message );
						$model->refresh();

						$this->modelService->reject( $model, [ 'notify' => false ] );

						Yii::$app->coreMailer->sendRejectMail( $model, $email, $message );

						break;
					}
					case $modelClass::STATUS_FROJEN: {

						$model->setRejectMessage( $message );
						$model->refresh();

						$this->modelService->freeze( $model, [ 'notify' => false ] );

						Yii::$app->coreMailer->sendFreezeMail( $model, $email, $message );

						break;
					}
					case $modelClass::STATUS_BLOCKED: {

						$model->setRejectMessage( $message );
						$model->refresh();

						$this->modelService->block( $model, [ 'notify' => false ] );

						Yii::$app->coreMailer->sendBlockMail( $model, $email, $message );

						break;
					}
					case $modelClass::STATUS_ACTIVE: {

						$this->modelService->activate( $model, [ 'notify' => false ] );

						$model->updateDataMeta( CoreGlobal::DATA_APPROVAL_REQUEST, false );

						Yii::$app->coreMailer->sendActivateMail( $model, $email );
					}
				}

				$this->redirect( $this->returnUrl );
			}

			$content	= $model->modelContent;
			$template	= $content->template;

			if( $this->prettyReview && isset( $template ) ) {

				return Yii::$app->templateManager->renderViewAdmin( $template, [
					'model' => $model,
					'content' => $content,
					'userReview' => false
				], [ 'layout' => false ] );
			}
			else {

				$templatesMap = $this->templateService->getIdNameMapByType( $this->templateType, [ 'default' => true ] );

				return $this->render( 'review', [
					'modelService' => $this->modelService,
					'metaService' => $this->metaService,
					'model' => $model,
					'content' => $content,
					'avatar' => $model->avatar,
					'banner' => $content->banner,
					'video' => $content->video,
					'visibilityMap' => $modelClass::$visibilityMap,
					'statusMap' => $modelClass::$statusMap,
					'templatesMap' => $templatesMap
				]);
			}
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
