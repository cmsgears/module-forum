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
 * AttributeController provides actions specific to Topic attributes.
 *
 * @since 1.0.0
 */
class AttributeController extends \cmsgears\core\admin\controllers\base\AttributeController {

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
		$this->title	= 'Topic Attribute';
		$this->apixBase	= 'forum/topic/attribute';

		// Services
		$this->modelService		= Yii::$app->factory->get( 'topicMetaService' );
		$this->parentService	= Yii::$app->factory->get( 'topicService' );

		// Sidebar
		$this->sidebar = [ 'parent' => 'sidebar-forum', 'child' => 'topic' ];

		// Return Url
		$this->returnUrl = Url::previous( 'topic-attributes' );
		$this->returnUrl = isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/forum/topic/attribute/all' ], true );

		// All Url
		$allUrl = Url::previous( 'topics' );
		$allUrl = isset( $allUrl ) ? $allUrl : Url::toRoute( [ '/forum/topic/all' ], true );

		// Breadcrumbs
		$this->breadcrumbs	= [
			'base' => [
				[ 'label' => 'Home', 'url' => Url::toRoute( '/dashboard' ) ],
				[ 'label' => 'Topics', 'url' =>  $allUrl ]
			],
			'all' => [ [ 'label' => 'Topic Attributes' ] ],
			'create' => [ [ 'label' => 'Topic Attributes', 'url' => $this->returnUrl ], [ 'label' => 'Create' ] ],
			'update' => [ [ 'label' => 'Topic Attributes', 'url' => $this->returnUrl ], [ 'label' => 'Update' ] ],
			'delete' => [ [ 'label' => 'Topic Attributes', 'url' => $this->returnUrl ], [ 'label' => 'Delete' ] ]
		];
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// AttributeController -------------------

	public function actionAll( $pid ) {

		Url::remember( Yii::$app->request->getUrl(), 'topic-attributes' );

		return parent::actionAll( $pid );
	}

}
