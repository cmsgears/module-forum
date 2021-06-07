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
use yii\filters\VerbFilter;
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
class TopicController extends \cmsgears\core\admin\controllers\base\Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	public $title;

	public $tagWidgetSlug;

	public $parentType;

	public $metaService;

	// Protected --------------

	protected $templateType;
	protected $prettyReview;

	protected $templateService;
	protected $modelContentService;

	protected $forumService;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

        parent::init();

		// Views
        $this->setViewPath( '@cmsgears/module-forum/admin/views/topic' );

		// Permission
		$this->crudPermission = ForumGlobal::PERM_FORUM_ADMIN;

		// Config
		$this->parentType	= ForumGlobal::TYPE_TOPIC;
		$this->templateType	= ForumGlobal::TYPE_TOPIC;
		$this->title		= 'Topic';
		$this->baseUrl		= 'topic';
		$this->apixBase		= 'forum/topic';
		$this->prettyReview	= false;

		// Services
		$this->modelService		= Yii::$app->factory->get( 'topicService' );
		$this->metaService		= Yii::$app->factory->get( 'topicMetaService' );
		$this->templateService	= Yii::$app->factory->get( 'templateService' );

		$this->modelContentService = Yii::$app->factory->get( 'modelContentService' );

		$this->forumService = Yii::$app->factory->get( 'forumService' );

		// Sidebar
		$this->sidebar = [ 'parent' => 'sidebar-forum', 'child' => 'topic' ];

		// Return Url
		$this->returnUrl = Url::previous( 'forum-topics' );
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
			'attributes' => [ [ 'label' => 'Topics', 'url' => $this->returnUrl ], [ 'label' => 'Attributes' ] ],
			'config' => [ [ 'label' => 'Topics', 'url' => $this->returnUrl ], [ 'label' => 'Config' ] ],
			'settings' => [ [ 'label' => 'Topics', 'url' => $this->returnUrl ], [ 'label' => 'Settings' ] ]
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
					'delete' => [ 'permission' => $this->crudPermission ],
					'pdf' => [ 'permission' => $this->crudPermission ],
					'import' => [ 'permission' => $this->crudPermission ],
					'export' => [ 'permission' => $this->crudPermission ],
					'gallery' => [ 'permission' => $this->crudPermission ],
					'data' => [ 'permission' => $this->crudPermission ],
					'attributes' => [ 'permission' => $this->crudPermission ],
					'config' => [ 'permission' => $this->crudPermission ],
					'settings' => [ 'permission' => $this->crudPermission ],
					'review' => [ 'permission' => $this->crudPermission ]
				]
			],
			'verbs' => [
				'class' => VerbFilter::class,
				'actions' => [
					'index' => [ 'get', 'post' ],
					'all' => [ 'get' ],
					'create' => [ 'get', 'post' ],
					'update' => [ 'get', 'post' ],
					'delete' => [ 'get', 'post' ],
					'pdf' => [ 'get' ],
					'import' => [ 'post' ],
					'export' => [ 'get' ],
					'gallery' => [ 'get' ],
					'data' => [ 'get', 'post' ],
					'attributes' => [ 'get', 'post' ],
					'config' => [ 'get', 'post' ],
					'settings' => [ 'get', 'post' ],
					'review' => [ 'get', 'post' ]
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

	public function actions() {

		return [
			'gallery' => [ 'class' => 'cmsgears\cms\common\actions\gallery\Manage' ],
			'data' => [ 'class' => 'cmsgears\cms\common\actions\data\data\Form' ],
			'attributes' => [ 'class' => 'cmsgears\cms\common\actions\data\attribute\Form' ],
			'config' => [ 'class' => 'cmsgears\cms\common\actions\data\config\Form' ],
			'settings' => [ 'class' => 'cmsgears\cms\common\actions\data\setting\Form' ]
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// TopicController -----------------------

	public function actionIndex() {

		return $this->redirect( 'all' );
	}

	public function actionAll( $fid = null ) {

		Url::remember( Yii::$app->request->getUrl(), 'forum-topics' );

		$modelClass = $this->modelService->getModelClass();

		$forum = null;

		$dataProvider = $this->modelService->getPage();

		if( isset( $fid ) ) {

			$dataProvider = $this->modelService->getPageByForumId( $fid ) ;

			$forum = $this->forumService->getById( $fid );
		}

		return $this->render( 'all', [
			'forum' => $forum,
			'dataProvider' => $dataProvider,
			'visibilityMap' => $modelClass::$visibilityMap,
			'statusMap' => $modelClass::$statusMap,
			'filterStatusMap' => $modelClass::$filterStatusMap
		]);
	}

	public function actionCreate( $fid = null ) {

		$modelClass = $this->modelService->getModelClass();

		$model	= new $modelClass;
		$forum	= null;

		if( isset( $fid ) ) {

			$forum = $this->forumService->getById( $fid );

			$model->forumId = isset( $forum ) ? $forum->id : null;
		}

		$content = $this->modelContentService->getModelObject();

		$avatar		= File::loadFile( null, 'Avatar' );
		$banner		= File::loadFile( null, 'Banner' );
		$mbanner	= File::loadFile( null, 'MobileBanner' );
		$video		= File::loadFile( null, 'Video' );
		$mvideo		= File::loadFile( null, 'MobileVideo' );

		if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $content->load( Yii::$app->request->post(), $content->getClassName() ) &&
			$model->validate() && $content->validate() ) {

			$this->model = $this->modelService->add( $model, [
				'admin' => true, 'content' => $content,
				'publish' => $model->isActive(), 'avatar' => $avatar,
				'banner' => $banner, 'mbanner' => $mbanner,
				'video' => $video, 'mvideo' => $mvideo
			]);

			return $this->redirect( 'all' );
		}

		$templatesMap = $this->templateService->getIdNameMapByType( $this->templateType, [ 'default' => true ] );

		return $this->render( 'create', [
			'forum' => $forum,
			'model' => $model,
			'content' => $content,
			'avatar' => $avatar,
			'banner' => $banner,
			'mbanner' => $mbanner,
			'video' => $video,
			'mvideo' => $mvideo,
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

			$content	= $model->modelContent;
			$template	= $content->template;

			$avatar		= File::loadFile( $model->avatar, 'Avatar' );
			$banner		= File::loadFile( $content->banner, 'Banner' );
			$mbanner	= File::loadFile( $content->mobileBanner, 'MobileBanner' );
			$video		= File::loadFile( $content->video, 'Video' );
			$mvideo		= File::loadFile( $content->mobileVideo, 'MobileVideo' );

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $content->load( Yii::$app->request->post(), $content->getClassName() ) &&
				$model->validate() && $content->validate() ) {

				// Update organization
				$this->model = $this->modelService->update( $model, [
					'admin' => true, 'content' => $content,
					'publish' => $model->isActive(), 'oldTemplate' => $template,
					'avatar' => $avatar, 'banner' => $banner, 'mbanner' => $mbanner,
					'video' => $video, 'mvideo' => $mvideo
				]);

				return $this->redirect( $this->returnUrl );
			}

			$templatesMap = $this->templateService->getIdNameMapByType( $this->templateType, [ 'default' => true ] );

			$tagTemplate	= $this->templateService->getGlobalBySlugType( CoreGlobal::TEMPLATE_TAG, $this->templateType );
			$tagTemplateId	= isset( $tagTemplate ) ? $tagTemplate->id : null;

			return $this->render( 'update', [
				'forum' => $model->forum,
				'model' => $model,
				'content' => $content,
				'avatar' => $avatar,
				'banner' => $banner,
				'mbanner' => $mbanner,
				'video' => $video,
				'mvideo' => $mvideo,
				'visibilityMap' => $modelClass::$visibilityMap,
				'statusMap' => $modelClass::$statusMap,
				'templatesMap' => $templatesMap,
				'tagTemplateId' => $tagTemplateId
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
				'forum' => $model->forum,
				'model' => $model,
				'content' => $content,
				'avatar' => $model->avatar,
				'banner' => $content->banner,
				'mbanner' => $content->mobileBanner,
				'video' => $content->video,
				'mvideo' => $content->mobileVideo,
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
					case $modelClass::STATUS_APPROVED: {

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
					'forum' => $model->forum,
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
					'forum' => $model->forum,
					'model' => $model,
					'content' => $content,
					'avatar' => $model->avatar,
					'banner' => $content->banner,
					'mbanner' => $content->mobileBanner,
					'video' => $content->video,
					'mvideo' => $content->mobileVideo,
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
