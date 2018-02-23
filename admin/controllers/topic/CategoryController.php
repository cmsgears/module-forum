<?php
namespace cmsgears\forum\admin\controllers\topic;

// Yii Imports
use Yii;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\forum\common\config\ForumGlobal;
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\resources\File;
use cmsgears\cms\common\models\resources\ModelContent;

class CategoryController extends \cmsgears\core\admin\controllers\base\CategoryController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------
    
    protected $templateType;

	protected $templateService;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();
        
        // Views
		$this->viewPath			= '@cmsgears/module-forum/admin/views/category/';

		// Permission
		$this->crudPermission	= ForumGlobal::PERM_FORUM;

		$this->type				= ForumGlobal::TYPE_FORUM;
		$this->templateType		= ForumGlobal::TYPE_FORUM;
        
        // Services
		$this->templateService		= Yii::$app->factory->get( 'templateService' );

		// Sidebar
		$this->sidebar			= [ 'parent' => 'sidebar-forum', 'child' => 'forum-category' ];

		// Return Url
		$this->returnUrl		= Url::previous( 'categories' );
		$this->returnUrl		= isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/forum/topic/category/all' ], true );

		// Breadcrumbs
		$this->breadcrumbs	= [
			'base' => [ [ 'label' => 'Forum Topics', 'url' =>  [ '/forum/topic/all' ] ] ],
			'all' => [ [ 'label' => 'Categories' ] ],
			'create' => [ [ 'label' => 'Categories', 'url' => $this->returnUrl ], [ 'label' => 'Add' ] ],
			'update' => [ [ 'label' => 'Categories', 'url' => $this->returnUrl ], [ 'label' => 'Update' ] ],
			'delete' => [ [ 'label' => 'Categories', 'url' => $this->returnUrl ], [ 'label' => 'Delete' ] ]
		];
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// CategoryController --------------------
    
    public function actionAll() {
        
        Url::remember( Yii::$app->request->getUrl(), 'categories' );

		$modelTable		= $this->modelService->getModelTable();
		$dataProvider	= $this->modelService->getPageWithContent( [ 'conditions' => [ "$modelTable.type" => $this->type ] ] );

		return $this->render( 'all', [
			 'dataProvider' => $dataProvider
		]);
	}

	public function actionCreate() {

		$modelClass		= $this->modelService->getModelClass();
		$model			= new $modelClass;
		$model->siteId	= Yii::$app->core->siteId;
		$model->type	= $this->type;

		$content		= new ModelContent();
		$banner			= File::loadFile( null, 'Banner' );
		$video			= File::loadFile( null, 'Video' );

		if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $content->load( Yii::$app->request->post(), $content->getClassName() ) &&
			$model->validate() && $content->validate() ) {

			$this->modelService->create( $model, [ 'content' => $content, 'banner' => $banner, 'video' => $video ] );

			return $this->redirect( "update?id=$model->id" );
		}
		
		$templatesMap	= $this->templateService->getIdNameMapByType( $this->templateType, [ 'default' => true ] );

		return $this->render( 'create', [
			'model' => $model,
			'content' => $content,
			'banner' => $banner,
			'video' => $video,
			//'categoryMap' => $categoryMap,
			'templatesMap' => $templatesMap
		]);
	}

	public function actionUpdate( $id ) {

		// Find Model
		$model	= $this->modelService->getById( $id );

		// Update/Render if exist
		if( isset( $model ) ) {

			$content	= $model->modelContent;
			$banner		= File::loadFile( $content->banner, 'Banner' );
			$video		= File::loadFile( $content->video, 'Video' );

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $content->load( Yii::$app->request->post(), $content->getClassName() ) &&
				$model->validate() && $content->validate() ) {

				$this->modelService->update( $model, [ 'content' => $content, 'banner' => $banner, 'video' => $video ] );

				return $this->refresh();
			}

			$templatesMap	= $this->templateService->getIdNameMapByType( $this->templateType, [ 'default' => true ] );

			return $this->render( 'update', [
				'model' => $model,
				'content' => $content,
				'banner' => $banner,
				'video' => $video,
				//'categoryMap' => $categoryMap,
				'templatesMap' => $templatesMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionDelete( $id ) {

		// Find Model
		$model	= $this->modelService->getById( $id );

		// Delete/Render if exist
		if( isset( $model ) ) {

			$content	= $model->modelContent;

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) ) {

				$this->modelService->delete( $model, [ 'content' => $content ] );

				return $this->redirect( $this->returnUrl );
			}
			
			$templatesMap	= $this->templateService->getIdNameMapByType( $this->templateType, [ 'default' => true ] );

			return $this->render( 'delete', [
				'model' => $model,
				'content' => $content,
				'banner' => $content->banner,
				'video' => $content->video,
				'templatesMap' => $templatesMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}
