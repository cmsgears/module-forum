<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\forum\common\services\resources;

// CMG Imports
use cmsgears\forum\common\models\base\ForumTables;

use cmsgears\forum\common\services\interfaces\resources\ITopicReplyService;

use cmsgears\core\common\services\base\MetaService;

use cmsgears\core\common\services\traits\resources\DataTrait;

/**
 * TopicReplyService provide service methods of topic reply.
 *
 * @since 1.0.0
 */
class TopicReplyService extends MetaService implements ITopicReplyService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\forum\common\models\resources\TopicReply';

	public static $modelTable	= ForumTables::TABLE_TOPIC_REPLY;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	use DataTrait;

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// TopicReplyService ---------------------

	// Data Provider ------

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// TopicReplyService ---------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------

}
