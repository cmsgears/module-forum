<?php
namespace cmsgears\modules\forum\common\models\entities;

// CMG Imports
use cmsgears\modules\core\common\models\entities\NamedActiveRecord;

use cmsgears\modules\core\common\models\entities\CmgFile;
use cmsgears\modules\core\common\models\entities\User;

class Forum extends NamedActiveRecord {

	const COMMUNITY_FORUM	=  1;

	// Instance Methods --------------------------------------------

	// db columns

	public function getId() {

		return $this->forum_id;
	}

	public function getOwnerId() {

		return $this->forum_owner;
	}

	public function getOwner() {

		return $this->hasOne( User::className(), [ 'user_id' => 'forum_owner' ] );
	}

	public function setOwnerId( $user ) {

		$this->forum_owner = $user;	
	}

	public function getBannerId() {

		return $this->forum_banner;
	}

	public function getBanner() {

		return $this->hasOne( CmgFile::className(), [ 'file_id' => 'forum_banner' ] );
	}

	public function setBannerId( $bannerId ) {

		$this->forum_banner = $bannerId;
	}

	public function getName() {

		return $this->forum_name;
	}
	
	public function setName( $name ) {

		$this->forum_name = $name;	
	}

	public function getDesc() {

		return $this->forum_desc;
	}
	
	public function setDesc( $desc ) {

		$this->forum_desc = $desc;	
	}

	public function getSlug() {
		
		return $this->forum_slug;	
	}

	public function setSlug( $slug ) {
		
		$this->forum_slug = $slug;
	}

	public function getCreatedOn() {

		return $this->forum_created_on;
	}
	
	public function setCreatedOn( $date ) {

		$this->forum_created_on = $date;	
	}
	
	public function getUpdatedOn() {

		return $this->forum_updated_on;
	}
	
	public function setUpdatedOn( $date ) {

		$this->forum_updated_on = $date;	
	}
	
	public function getAllTopic() {

		return $this->hasMany( ForumTopic::className(), [ 'topic_forum_id' => 'forum_id' ] );
	}

	public function getCategories() {

    	return $this->hasMany( Option::className(), [ 'option_id' => 'category_id' ] )
					->viaTable( CMSTables::TABLE_POST_CATEGORY, [ 'forum_id' => 'forum_id' ] );
	}

	public function getCategoriesMap() {

    	return $this->hasMany( ForumCategory::className(), [ 'forum_id' => 'forum_id' ] );
	}

	public function getCategoriesIdList() {

    	$categories 		= $this->categoriesMap;
		$categoriesList		= array();

		foreach ( $categories as $category ) {

			array_push( $categoriesList, $category->category_id );
		}

		return $categoriesList;
	}

	public function getCategoriesIdNameMap() {

		$categories 	= $this->categories;
		$categoriesMap	= array();

		foreach ( $categories as $category ) {

			$categoriesMap[]	= [ 'id' => $category->getId(), 'name' => $category->getKey() ];
		}

		return $categoriesMap;
	}

	// yii\base\Model

	public function rules() {

        return [
			[ [ 'forum_name' ], 'required' ],
			[ 'forum_name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'forum_name', 'validateNameUpdate', 'on' => [ 'update' ] ],
            [ [ 'forum_desc', 'forum_banner' ], 'safe' ]
        ];
    }

	public function attributeLabels() {

		return [
			'forum_name' => 'Name',
			'forum_desc' => 'Description',
			'forum_slug' => 'Slug',
		];
	}

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord
	
	public static function tableName() {
		
		return ForumTables::TABLE_FORUM;
	}

	// Forum

	public static function findById( $id ) {

		return Forum::find()->where( 'forum_id=:id', [ ':id' => $id ] )->one();
	}

	public static function findByName( $name ) {

		return Forum::find()->where( 'forum_name=:name', [ ':name' => $name ] )->one();
	}
}

?>