<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\forum\admin\controllers\forum;

// Yii Imports
use Yii;
use yii\helpers\Url;

// CMG Imports
use cmsgears\forum\common\config\ForumGlobal;

/**
 * ModelFileController provides actions specific to forum files.
 *
 * @since 1.0.0
 */
class ModelFileController extends \cmsgears\core\admin\controllers\base\ModelFileController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Permission
		$this->crudPermission = ForumGlobal::PERM_FORUM_ADMIN;

		// Config
		$this->title	= 'Forum File';
		$this->apixBase	= 'forum/forum/model-file';

		// Services
		$this->parentService = Yii::$app->factory->get( 'forumService' );

		// Sidebar
		$this->sidebar = [ 'parent' => 'sidebar-forum', 'child' => 'forum' ];

		// Return Url
		$this->returnUrl = Url::previous( 'forum-files' );
		$this->returnUrl = isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/forum/forum/file/all' ], true );

		// All Url
		$allUrl = Url::previous( 'forums' );
		$allUrl = isset( $allUrl ) ? $allUrl : Url::toRoute( [ '/forum/forum/all' ], true );

		// Breadcrumbs
		$this->breadcrumbs	= [
			'base' => [
				[ 'label' => 'Home', 'url' => Url::toRoute( '/dashboard' ) ],
				[ 'label' => 'Forums', 'url' =>  $allUrl ]
			],
			'all' => [ [ 'label' => 'Forum Files' ] ],
			'create' => [ [ 'label' => 'Forum Files', 'url' => $this->returnUrl ], [ 'label' => 'Create' ] ],
			'update' => [ [ 'label' => 'Forum Files', 'url' => $this->returnUrl ], [ 'label' => 'Update' ] ],
			'delete' => [ [ 'label' => 'Forum Files', 'url' => $this->returnUrl ], [ 'label' => 'Delete' ] ]
		];
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ModelFileController -------------------

	public function actionAll( $pid ) {

		Url::remember( Yii::$app->request->getUrl(), 'forum-files' );

		return parent::actionAll( $pid );
	}

}
