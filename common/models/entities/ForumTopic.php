<?php
namespace cmsgears\modules\forum\common\models\entities;

// Yii Imports
use yii\db\ActiveRecord;

// CMG Imports
use cmsgears\modules\core\common\config\CoreGlobal;

use cmsgears\modules\core\common\models\entities\User;

use cmsgears\modules\core\common\utilities\MessageUtil;

class ForumTopic extends ActiveRecord {

	// Instance Methods --------------------------------------------

	// db columns

	public function getId() {

		return $this->topic_id;
	}

	public function getForumId() {

		return $this->topic_forum;
	}

	public function getForum() {

		return $this->hasOne( Forum::className(), [ 'user_id' => 'topic_forum' ] );
	}
	
	public function setForumId( $forumId ) {

		$this->topic_forum = $forumId;
	}

	public function getOwnerId() {

		return $this->topic_owner;
	}

	public function getOwner() {

		return $this->hasOne( User::className(), [ 'user_id' => 'topic_owner' ] );
	}

	public function setOwnerId( $userId ) {

		$this->topic_owner = $userId;
	}

	public function getName() {

		return $this->topic_name;
	}
	
	public function setName( $name ) {

		$this->topic_name = $name;	
	}

	public function getDesc() {

		return $this->topic_desc;
	}
	
	public function setDesc( $desc ) {

		$this->topic_desc = $desc;	
	}
	
	public function getCreatedOn() {

		return $this->topic_created_on;
	}
	
	public function setCreatedOn( $creatdate ) {

		$this->topic_created_on = $creatdate;	
	}
	
	public function getUpdatedOn() {

		return $this->topic_updated_on;
	}
	
	public function setUpdatedOn( $updatedate ) {

		$this->topic_updated_on = $updatedate;	
	}

	public function getAllPost() {

		return $this->hasMany( ForumPost::className(), [ 'post_topic' => 'topic_id' ] );
	}

	// yii\base\Model

	public function rules() {

        return [
            [ [ 'topic_name' ], 'required' ],
            [ 'topic_name', 'alphanumspace' ],
            [ 'topic_name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'topic_name', 'validateNameUpdate', 'on' => [ 'update' ] ],
            [ [ 'topic_desc', 'topic_forum'  ], 'safe' ]
        ];
    }

	public function attributeLabels() {

		return [
			'topic_name' => 'Name',
			'topic_desc' => 'Description'
		];
	}

    public function validateNameCreate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

			$entity = self::findByNameForumId( $this->getName(), $this->getForumId() );

            if( $entity ) {

				$this->addError( $attribute, MessageUtil::getMessage( CoreGlobal::ERROR_EXIST ) );
            }
        }
    }

    public function validateNameUpdate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

			$existingEntity = self::findByNameForumId( $this->getName(), $this->getForumId() );

			if( isset( $existingEntity ) && $existingEntity->getId() != $this->getId() 
				&& strcmp( $existingEntity->getName(), $this->getName() ) == 0 && $existingEntity->getForumId() == $this->getForumId() ) {

				$this->addError( $attribute, MessageUtil::getMessage( CoreGlobal::ERROR_EXIST ) );
			}
        }
    }

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord
	
	public static function tableName() {
		
		return ForumTables::TABLE_FORUM_TOPIC;
	}

	// ForumTopic

	public static function getQuery() {

		return self::find()->joinWith( 'owner' );
	}

	public static function findById( $id ) {

		return ForumTopic::find()->where( 'topic_id=:id', [ ':id' => $id ] )->one();
	}

	public static function findByName( $name ) {

		return ForumTopic::find()->where( 'topic_name=:name', [ ':name' => $name ] )->one();
	}

	public static function findByForumId( $id ) {

		return self::find()->where( 'topic_forum=:id', [ ':id' => $id ] )->all();
	}

	public static function findByNameForumId( $name, $id ) {

		return self::find()->where( 'topic_forum=:id', [ ':id' => $id ] )->andWhere( 'topic_name=:name', [ ':name' => $name ] )->one();
	}
}

?>