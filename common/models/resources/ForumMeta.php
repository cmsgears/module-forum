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
use cmsgears\forum\common\models\entities\Forum;

/**
 * @inheritdoc
 */
class ForumMeta extends \cmsgears\core\common\models\base\Meta {

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

	// ForumMeta -----------------------------

	/**
	 * Return corresponding forum.
	 *
	 * @return \cmsgears\forum\common\models\entities\Forum
	 */
	public function getParent() {

		return $this->hasOne( Forum::class, [ 'id' => 'modelId' ] );
	}

	/**
	 * Check whether meta belongs to given forum.
	 *
	 * @param Forum $forum
	 * @return boolean
	 */
	public function belongsToForum( $forum ) {

		return $this->modelId == $forum->id;
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return ForumTables::getTableName( ForumTables::TABLE_FORUM_META );
	}

	// CMG parent classes --------------------

	// ForumMeta -----------------------------

	// Read - Query -----------

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
