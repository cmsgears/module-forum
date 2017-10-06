<?php
namespace cmsgears\modules\forum\admin\controllers\apix;

// Yii Imports
use \Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\modules\core\common\config\CoreGlobal;

use cmsgears\modules\forum\common\models\entities\ForumPermission;
use cmsgears\modules\forum\admin\models\forms\ForumCategoryBinderForm;

use cmsgears\modules\forum\admin\services\ForumService;

use cmsgears\modules\core\common\utilities\MessageUtil;
use cmsgears\modules\core\common\utilities\AjaxUtil;

class ForumController extends Controller {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );
	}

	// Instance Methods --------------------------------------------

	// yii\base\Component

    public function behaviors() {

        return [
            'rbac' => [
                'class' => Yii::$app->cmgCore->getRbacFilterClass(),
                'permissions' => [
	                'bindCategories'  => ForumPermission::PERM_FORUM
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
	                'bindCategories'  => ['post']
                ]
            ]
        ];
    }

	// PostController

	public function actionBindCategories() {

		$binder = new ForumCategoryBinderForm();

		if( $binder->load( Yii::$app->request->post(), "" ) ) {

			if( ForumService::bindCategories( $binder ) ) {

				// Trigger Ajax Success
				AjaxUtil::generateSuccess( MessageUtil::getMessage( CoreGlobal::MESSAGE_REQUEST ) );
			}
		}

		// Trigger Ajax Failure
        AjaxUtil::generateFailure( MessageUtil::getMessage( CoreGlobal::ERROR_REQUEST ) );
	}
}

?>