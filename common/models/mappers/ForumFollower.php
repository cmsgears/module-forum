<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.forum/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\forum\common\models\mappers;

// CMG Imports
use cmsgears\forum\common\models\base\ForumTables;
use cmsgears\forum\common\models\entities\Forum;

/**
 * ForumFollower represents interest of user in forum.
 *
 * @inheritdoc
 */
class ForumFollower extends \cmsgears\core\common\models\base\Follower {

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

	// ForumFollower -------------------------

	/**
	 * Return corresponding group.
	 *
	 * @return \cmsgears\forum\common\models\entities\Forum
	 */
	public function getParent() {

		return $this->hasOne( Forum::class, [ 'id' => 'parentId' ] );
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

    /**
     * @inheritdoc
     */
	public static function tableName() {

		return ForumTables::getTableName( ForumTables::TABLE_FORUM_FOLLOWER );
	}

	// CMG parent classes --------------------

	// ForumFollower -------------------------

	// Read - Query -----------

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
