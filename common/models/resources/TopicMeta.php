<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\forum\common\models\resources;

// CMG Imports
use cmsgears\forum\common\models\base\ForumTables;
use cmsgears\forum\common\models\entities\Topic;

/**
 * @inheritdoc
 */
class TopicMeta extends \cmsgears\core\common\models\base\Meta {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Model ---------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// TopicMeta -----------------------------

	/**
	 * Return corresponding forum branch.
	 *
	 * @return \cmsgears\forum\common\models\entities\Topic
	 */
	public function getParent() {

		return $this->hasOne( Topic::class, [ 'id' => 'modelId' ] );
	}

	/**
	 * Check whether meta belongs to given topic.
	 *
	 * @param Topic $topic
	 * @return boolean
	 */
	public function belongsToTopic( $topic ) {

		return $this->modelId == $topic->id;
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return ForumTables::getTableName( ForumTables::TABLE_TOPIC_META );
	}

	// CMG parent classes --------------------

	// TopicMeta -----------------------------

	// Read - Query -----------

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
