<?php
namespace cmsgears\modules\forum\common\models\entities;

// Yii imports
use yii\db\ActiveRecord;

class ForumCategory extends ActiveRecord {

	// Instance Methods --------------------------------------------

	// db columns

	public function getForumId() {

		return $this->forum_id;
	}

	public function setForumId( $forumId ) {

		$this->forum_id = $forumId;
	}

	public function getCategoryId() {

		return $this->category_id;
	}

	public function setCategoryId( $categoryId ) {

		$this->category_id = $categoryId;
	}

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord

	public static function tableName() {

		return ForumTables::TABLE_FORUM_CATEGORY;
	}

	// ForumCategory

	public static function deleteByForumId( $forumId ) {

		self::deleteAll( 'forum_id=:id', [ ':id' => $forumId ] );
	}

	public static function deleteByCategoryId( $categoryId ) {

		self::deleteAll( 'category_id=:id', [ ':id' => $categoryId ] );
	}
}

?>