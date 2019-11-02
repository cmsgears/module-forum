<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\forum\admin\controllers\apix\topic;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\forum\common\config\ForumGlobal;

/**
 * AttributeController provides actions specific to topic attributes.
 *
 * @since 1.0.0
 */
class AttributeController extends \cmsgears\core\admin\controllers\apix\AttributeController {

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

		// Services
		$this->modelService = Yii::$app->factory->get( 'topicMetaService' );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// AttributeController -------------------

}
