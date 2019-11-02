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
 * TagController provides actions specific to Topic tags.
 *
 * @since 1.0.0
 */
class TagController extends \cmsgears\cms\admin\controllers\base\TagController {

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
		$this->title		= 'Topic';
		$this->type			= ForumGlobal::TYPE_TOPIC;
		$this->templateType	= ForumGlobal::TYPE_TOPIC;
		$this->apixBase		= 'forum/topic/tag';
		$this->parentPath	= '/forum/topic/tag';

		// Sidebar
		$this->sidebar = [ 'parent' => 'sidebar-forum', 'child' => 'topic-tag' ];

		// Return Url
		$this->returnUrl = Url::previous( 'topic-tags' );
		$this->returnUrl = isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/forum/topic/tag/all' ], true );

		// Breadcrumbs
		$this->breadcrumbs	= [
			'base' => [
				[ 'label' => 'Home', 'url' => Url::toRoute( '/dashboard' ) ]
			],
			'all' => [ [ 'label' => 'Topic Tags' ] ],
			'create' => [ [ 'label' => 'Topic Tags', 'url' => $this->returnUrl ], [ 'label' => 'Add' ] ],
			'update' => [ [ 'label' => 'Topic Tags', 'url' => $this->returnUrl ], [ 'label' => 'Update' ] ],
			'delete' => [ [ 'label' => 'Topic Tags', 'url' => $this->returnUrl ], [ 'label' => 'Delete' ] ],
			'gallery' => [ [ 'label' => 'Topic Tags', 'url' => $this->returnUrl ], [ 'label' => 'Gallery' ] ],
			'data' => [ [ 'label' => 'Topic Tags', 'url' => $this->returnUrl ], [ 'label' => 'Data' ] ],
			'config' => [ [ 'label' => 'Topic Tags', 'url' => $this->returnUrl ], [ 'label' => 'Config' ] ],
			'settings' => [ [ 'label' => 'Topic Tags', 'url' => $this->returnUrl ], [ 'label' => 'Settings' ] ]
		];
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// TagController -------------------------

	public function actionAll( $config = [] ) {

		Url::remember( Yii::$app->request->getUrl(), 'topic-tags' );

		return parent::actionAll( $config );
	}

}
