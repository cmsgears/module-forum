<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\forum\common\components;

// CMG Imports
use cmsgears\forum\common\config\ForumGlobal;

/**
 * MessageSource stores and provide the messages and message templates available in
 * Forum Module.
 *
 * @since 1.0.0
 */
class MessageSource extends \cmsgears\core\common\base\MessageSource {

	// Variables ---------------------------------------------------

	// Global -----------------

	// Public -----------------

	// Protected --------------

	protected $messageDb = [
		// Generic Fields
		ForumGlobal::FIELD_FORUM => 'Forum',
		ForumGlobal::FIELD_TOPIC => 'Topic'
	];

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG parent classes --------------------

	// MessageSource -------------------------

}
