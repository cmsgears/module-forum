<?php
namespace cmsgears\forum\admin\controllers;

// Yii Imports
use Yii;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\forum\common\models\entities\Topic;

class TopicController extends \cmsgears\core\admin\controllers\base\CrudController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------
    
    public function init() {
        
        parent::init();
        
        // Permission
		$this->crudPermission	= CoreGlobal::PERM_ADMIN;

		// Services
		$this->modelService		= Yii::$app->factory->get( 'topicService' );

		// Sidebar
		$this->sidebar			= [ 'parent' => 'sidebar-forum', 'child' => 'topic' ];

		// Return Url
		$this->returnUrl		= Url::previous( 'topics' );
		$this->returnUrl		= isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/forum/topic/all' ], true );

		// Breadcrumbs
		$this->breadcrumbs		= [
			'all' => [ [ 'label' => 'Forum Topics' ] ],
			'create' => [ [ 'label' => 'Forum Topics', 'url' => $this->returnUrl ], [ 'label' => 'Add' ] ],
			'update' => [ [ 'label' => 'Forum Topics', 'url' => $this->returnUrl ], [ 'label' => 'Update' ] ],
			'delete' => [ [ 'label' => 'Forum Topics', 'url' => $this->returnUrl ], [ 'label' => 'Delete' ] ],
			'items' => [ [ 'label' => 'Forum Topics', 'url' => $this->returnUrl ], [ 'label' => 'Items' ] ]
		];
    }

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// TopicController -----------------------
    
    public function actionAll() {
        
        $dataProvider   = $this->modelService->getPage();

		return $this->render( 'all', [
			 'dataProvider' => $dataProvider
		]);
    }
    
    public function actionCreate() {

		$modelClass	= $this->modelService->getModelClass();
		$model		= new $modelClass;
        
        $model->siteId		= Yii::$app->core->siteId;

		if( isset( $this->scenario ) ) {

			call_user_func_array( [ $model, 'setScenario' ], [ $this->scenario ] );
		}

		if( $model->load( Yii::$app->request->post(), $model->getClassName() )	&& $model->validate() ) {

			$this->modelService->create( $model );

			$model->refresh();
			
			$this->model = $model;
			
			return $this->redirect( 'all' );
		}
        
        $statusMap      = Topic::$statusMap;

		return $this->render( 'create', [
			'model' => $model,
            'statusMap' => $statusMap
		]);
	}
    
    public function actionUpdate( $id ) {

		// Find Model
		$model	= $this->modelService->getById( $id );

		// Update if exist
		if( isset( $model ) ) {

			if( isset( $this->scenario ) ) {

				call_user_func_array( [ $model, 'setScenario' ], [ $this->scenario ] );
			}

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

				$this->modelService->update( $model );

				$model->refresh();
			
				$this->model = $model;

				return $this->redirect( 'all' );
			}
            
            $statusMap      = Topic::$statusMap;

			// Render view
			return $this->render( 'update', [
				'model' => $model,
                'statusMap' => $statusMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
?>