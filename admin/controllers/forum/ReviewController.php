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

use cmsgears\core\common\models\resources\ModelComment;

/**
 * ReviewController provides actions specific to forum reviews.
 *
 * @since 1.0.0
 */
class ReviewController extends \cmsgears\core\admin\controllers\base\CommentController {

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
		$this->parentType	= ForumGlobal::TYPE_FORUM;
		$this->commentType	= ModelComment::TYPE_REVIEW;
		$this->title		= 'Review';
		$this->apixBase		= 'forum/forum/review';
		$this->parentUrl	= '/forum/forum/update?id=';
		$this->parentCol	= 'Forum';
		$this->urlKey		= 'forum-reviews';

		// Services
		$this->parentService = Yii::$app->factory->get( 'forumService' );

		// Sidebar
		$this->sidebar = [ 'parent' => 'sidebar-forum', 'child' => 'forum-reviews' ];

		// Return Url
		$this->returnUrl = Url::previous( $this->urlKey );
		$this->returnUrl = isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/forum/forum/comment/all' ], true );

		// All Url
		$allUrl = Url::previous( 'forums' );
		$allUrl = isset( $allUrl ) ? $allUrl : Url::toRoute( [ '/forum/forum/all' ], true );

		// Breadcrumbs
		$this->breadcrumbs	= [
			'base' => [
				[ 'label' => 'Home', 'url' => Url::toRoute( '/dashboard' ) ],
				[ 'label' => 'Forums', 'url' =>  $allUrl ]
			],
			'all' => [ [ 'label' => 'Forum Reviews' ] ],
			'create' => [ [ 'label' => 'Forum Reviews', 'url' => $this->returnUrl ], [ 'label' => 'Add' ] ],
			'update' => [ [ 'label' => 'Forum Reviews', 'url' => $this->returnUrl ], [ 'label' => 'Update' ] ],
			'delete' => [ [ 'label' => 'Forum Reviews', 'url' => $this->returnUrl ], [ 'label' => 'Delete' ] ]
		];
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ReviewController ----------------------

}
