<?php
namespace cmsgears\forum\admin\controllers\apix;

// Yii Imports
use Yii;
use yii\filters\VerbFilter;

// CMG Imports
use cmsgears\forum\common\config\ForumGlobal;

class TopicController extends \cmsgears\core\admin\controllers\base\Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------
	
	protected $activityService;
	
	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Permissions
		$this->crudPermission	= ForumGlobal::PERM_FORUM;

		// Services
		$this->modelService		= Yii::$app->factory->get( 'topicService' );
		$this->activityService	= Yii::$app->factory->get( 'activityService' );
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
                    'assign-category' => [ 'permission' => $this->crudPermission ],
					'remove-category' => [ 'permission' => $this->crudPermission ],
					'assign-tags' => [ 'permission' => $this->crudPermission ],
					'remove-tag' => [ 'permission' => $this->crudPermission ],
					'bulk' => [ 'permission' => $this->crudPermission ],
					'delete' => [ 'permission' => $this->crudPermission ]
				]
			],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
                    'assign-category' => [ 'post' ],
					'remove-category' => [ 'post' ],
					'assign-tags' => [ 'post' ],
					'remove-tag' => [ 'post' ],
					'bulk' => [ 'post' ],
					'delete' => [ 'post' ]
				]
			]
		];
	}

	// yii\base\Controller ----

	public function actions() {

		return [
            'assign-category' => [ 'class' => 'cmsgears\core\common\actions\category\Assign' ],
			'remove-category' => [ 'class' => 'cmsgears\core\common\actions\category\Remove' ],
			'assign-tags' => [ 'class' => 'cmsgears\core\common\actions\tag\Assign' ],
			'remove-tag' => [ 'class' => 'cmsgears\core\common\actions\tag\Remove' ],
			'bulk' => [ 'class' => 'cmsgears\core\common\actions\grid\Bulk' ],
			'delete' => [ 'class' => 'cmsgears\core\common\actions\grid\Delete' ]
		];
	}

	public function beforeAction( $action ) {

		$id	= Yii::$app->request->get( 'id' ) != null ? Yii::$app->request->get( 'id' ) : null;

		if( isset( $id ) ) {

			$model	= $this->modelService->getById( $id );
		
			$parentType = $this->modelService->getParentType();

			switch( $action->id ) {

				case 'delete': {

					if( isset( $model ) ) {

						$this->activityService->deleteActivity( $model, $parentType );
					}

					break;
				}
			}
		}
		return parent::beforeAction( $action);
	}
	
	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// PostController ------------------------

}
