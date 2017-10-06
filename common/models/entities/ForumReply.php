<?php
namespace cmsgears\modules\forum\common\models\entities;

// Yii Imports
use yii\db\ActiveRecord;

// CMG Imports
use cmsgears\modules\core\common\models\entities\User;

use cmsgears\modules\core\common\utilities\MessageUtil;

class ForumReply extends ActiveRecord {

	// Instance Methods --------------------------------------------
	
	// db columns

	public function getId() {

		return $this->reply_id;
	}

	public function getForumId() {

		return $this->reply_forum;
	}

	public function getForum() {

		return $this->hasOne( Forum::className(), [ 'forum_id' => 'reply_forum' ] );
	}

	public function setForumId( $forumId ) {

		$this->reply_forum = $forumId;
	}
	
	public function getTopicId() {

		return $this->reply_topic;
	}

	public function getTopic() {

		return $this->hasOne( ForumTopic::className(), [ 'topic_id' => 'reply_topic' ] );
	}

	public function setTopicId( $topicId ) {

		$this->reply_topic = $topicId;
	}

	public function getOwnerId() {

		return $this->reply_owner;
	}

	public function getOwner() {

		return $this->hasOne( User::className(), [ 'user_id' => 'reply_owner' ] );
	}

	public function setOwnerId( $user ) {

		$this->reply_owner = $user;
	}

	public function getContent() {

		return $this->reply_content;
	}
	
	public function setContent( $content ) {

		$this->reply_content = $content;	
	}

	public function getCreatedOn() {

		return $this->reply_created_on;
	}
	
	public function setCreatedOn( $creatdate ) {

		$this->reply_created_on = $creatdate;
	}
	
	public function getUpdatedOn() {

		return $this->reply_updated_on;
	}
	
	public function setUpdatedOn( $updatedate ) {

		$this->reply_updated_on = $updatedate;	
	}

	// yii\base\Model

	public function rules() {

        return [
            [ [ 'reply_forum', 'reply_topic', 'reply_owner', 'reply_content' ], 'required' ]
        ];
    }

	public function attributeLabels() {

		return [
			'reply_content' => 'Content'
		];
	}

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord
	
	public static function tableName() {
		
		return ForumTables::TABLE_FORUM_REPLY;
	}

	// ForumPost

	public static function getQuery() {

		return self::find()->joinWith( 'owner' );
	}

	public static function findById( $id ) {

		return ForumPost::find()->where( 'reply_id=:id', [ ':id' => $id ] )->one();
	}

	public static function findByTopicId( $id ) {

		return ForumPost::find()->where( 'reply_topic=:id', [ ':id' => $id ] )->all();
	}
}

?>