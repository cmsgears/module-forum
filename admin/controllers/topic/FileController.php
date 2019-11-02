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
use yii\helpers\Url;

// CMG Imports
use cmsgears\forum\common\config\ForumGlobal;

/**
 * FileController provides actions specific to Topic files.
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
		$this->title	= 'Topic File';
		$this->apixBase	= 'forum/topic/file';

		// Services
		$this->parentService = Yii::$app->factory->get( 'topicService' );

		// Sidebar
		$this->sidebar = [ 'parent' => 'sidebar-forum', 'child' => 'topic' ];

		// Return Url
		$this->returnUrl = Url::previous( 'topic-files' );
		$this->returnUrl = isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/forum/topic/file/all' ], true );

		// All Url
		$allUrl = Url::previous( 'topics' );
		$allUrl = isset( $allUrl ) ? $allUrl : Url::toRoute( [ '/forum/topic/all' ], true );

		// Breadcrumbs
		$this->breadcrumbs	= [
			'base' => [
				[ 'label' => 'Home', 'url' => Url::toRoute( '/dashboard' ) ],
				[ 'label' => 'Topics', 'url' =>  $allUrl ]
			],
			'all' => [ [ 'label' => 'Topic Files' ] ],
			'create' => [ [ 'label' => 'Topic Files', 'url' => $this->returnUrl ], [ 'label' => 'Create' ] ],
			'update' => [ [ 'label' => 'Topic Files', 'url' => $this->returnUrl ], [ 'label' => 'Update' ] ],
			'delete' => [ [ 'label' => 'Topic Files', 'url' => $this->returnUrl ], [ 'label' => 'Delete' ] ]
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

		Url::remember( Yii::$app->request->getUrl(), 'topic-files' );

		return parent::actionAll( $pid );
	}

}
