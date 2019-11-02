<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\forum\admin\controllers\topic\tag;

// Yii Imports
use Yii;
use yii\helpers\Url;

// CMG Imports
use cmsgears\forum\common\config\ForumGlobal;

/**
 * FileController provides actions specific to tag files.
 *
 * @since 1.0.0
 */
class FileController extends \cmsgears\core\admin\controllers\base\FileController {

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
		$this->title	= 'Tag File';
		$this->apixBase	= 'forum/tag/file';

		// Services
		$this->parentService = Yii::$app->factory->get( 'tagService' );

		// Sidebar
		$this->sidebar = [ 'parent' => 'sidebar-forum', 'child' => 'topic-tag' ];

		// Return Url
		$this->returnUrl = Url::previous( 'topic-tag-files' );
		$this->returnUrl = isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/forum/topic/tag/file/all' ], true );

		// All Url
		$allUrl = Url::previous( 'topic-tags' );
		$allUrl = isset( $allUrl ) ? $allUrl : Url::toRoute( [ '/forum/topic/tag/all' ], true );

		// Breadcrumbs
		$this->breadcrumbs	= [
			'base' => [
				[ 'label' => 'Home', 'url' => Url::toRoute( '/dashboard' ) ],
				[ 'label' => 'Topic Tags', 'url' =>  $allUrl ]
			],
			'all' => [ [ 'label' => 'Tag Files' ] ],
			'create' => [ [ 'label' => 'Tag Files', 'url' => $this->returnUrl ], [ 'label' => 'Create' ] ],
			'update' => [ [ 'label' => 'Tag Files', 'url' => $this->returnUrl ], [ 'label' => 'Update' ] ],
			'delete' => [ [ 'label' => 'Tag Files', 'url' => $this->returnUrl ], [ 'label' => 'Delete' ] ]
		];
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// FileController ------------------------

	public function actionAll( $pid ) {

		Url::remember( Yii::$app->request->getUrl(), 'topic-tag-files' );

		return parent::actionAll( $pid );
	}

}
