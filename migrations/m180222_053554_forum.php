<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

// CMG Imports
use cmsgears\core\common\base\Migration;

use cmsgears\core\common\models\base\Meta;

/**
 * The forum migration inserts the database tables of forum module. It also insert the foreign
 * keys if FK flag of migration component is true.
 *
 * @since 1.0.0
 */
class m180222_053554_forum extends Migration {

	// Public Variables

	public $fk;
	public $options;

	// Private Variables

	private $prefix;

	public function init() {

		// Table prefix
		$this->prefix		= Yii::$app->migration->cmgPrefix;

		// Get the values via config
		$this->fk			= Yii::$app->migration->isFk();
		$this->options		= Yii::$app->migration->getTableOptions();

		// Default collation
		if( $this->db->driverName === 'mysql' ) {

			$this->options = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}
	}

    public function up() {

        // topic
		$this->upTopic();
		$this->upTopicMeta();
		$this->upTopicFollower();

        // Reply
        $this->upReply();

        if( $this->fk ) {

            $this->generateForeignKeys();
        }
    }

    private function upTopic() {

        $this->createTable( $this->prefix . 'forum_topic', [
			'id' => $this->bigPrimaryKey( 20 ),
			'siteId' => $this->bigInteger( 20 )->notNull(),
			'templateId' => $this->bigInteger( 20 ),
			'createdBy' => $this->bigInteger( 20 )->notNull(),
			'modifiedBy' => $this->bigInteger( 20 ),
			'name' => $this->string( Yii::$app->core->xLargeText )->notNull(),
			'slug' => $this->string( Yii::$app->core->xxLargeText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'icon' => $this->string( Yii::$app->core->largeText )->defaultValue( null ),
			'title' => $this->string( Yii::$app->core->xxxLargeText )->defaultValue( null ),
			'description' => $this->string( Yii::$app->core->xtraLargeText )->defaultValue( null ),
			'status' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime(),
			'content' => $this->mediumText(),
			'data' => $this->mediumText(),
			'gridCache' => $this->longText(),
			'gridCacheValid' => $this->boolean()->notNull()->defaultValue( false ),
			'gridCachedAt' => $this->dateTime()
        ], $this->options );

        // Indexes for forum_topic
        $this->createIndex( 'idx_' . $this->prefix . 'topic_site', $this->prefix . 'forum_topic', 'siteId' );
        $this->createIndex( 'idx_' . $this->prefix . 'topic_creator', $this->prefix . 'forum_topic', 'createdBy' );
        $this->createIndex( 'idx_' . $this->prefix . 'topic_modifier', $this->prefix . 'forum_topic', 'modifiedBy' );
    }

	private function upTopicMeta() {

		$this->createTable( $this->prefix . 'forum_topic_meta', [
			'id' => $this->bigPrimaryKey( 20 ),
			'modelId' => $this->bigInteger( 20 )->notNull(),
			'name' => $this->string( Yii::$app->core->xLargeText )->notNull(),
			'label' => $this->string( Yii::$app->core->xxLargeText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText ),
			'active' => $this->boolean()->defaultValue( false ),
			'valueType' => $this->string( Yii::$app->core->mediumText )->notNull()->defaultValue( Meta::VALUE_TYPE_TEXT ),
			'value' => $this->text(),
			'data' => $this->mediumText()
		], $this->options );

		// Index for column parent
		$this->createIndex( 'idx_' . $this->prefix . 'topic_meta_parent', $this->prefix . 'forum_topic_meta', 'modelId' );
	}

	private function upTopicFollower() {

        $this->createTable( $this->prefix . 'forum_topic_follower', [
			'id' => $this->bigPrimaryKey( 20 ),
			'userId' => $this->bigInteger( 20 )->notNull(),
			'modelId' => $this->bigInteger( 20 )->notNull(),
			'type' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'active' => $this->boolean()->notNull()->defaultValue( false ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime(),
			'data' => $this->mediumText()
        ], $this->options );

        // Index for columns user and model
		$this->createIndex( 'idx_' . $this->prefix . 'topic_follower_user', $this->prefix . 'forum_topic_follower', 'userId' );
		$this->createIndex( 'idx_' . $this->prefix . 'topic_follower_parent', $this->prefix . 'forum_topic_follower', 'modelId' );
	}

    private function upReply() {

        $this->createTable( $this->prefix . 'forum_topic_reply', [
			'id' => $this->bigPrimaryKey( 20 ),
			'topicId' => $this->bigInteger( 20 )->notNull(),
			'baseId' => $this->bigInteger( 20 ),
			'bannerId' => $this->bigInteger( 20 ),
			'videoId' => $this->bigInteger( 20 ),
			'createdBy' => $this->bigInteger( 20 ),
			'modifiedBy' => $this->bigInteger( 20 ),
			'ip' => $this->string( Yii::$app->core->mediumText )->defaultValue( null ),
			'ipNum' => $this->integer(11)->defaultValue( 0 ),
			'agent' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'status' => $this->smallInteger( 6 )->notNull()->defaultValue( 0 ),
			'score' => $this->smallInteger( 6 )->notNull()->defaultValue( 0 ),
			'fragment' => $this->smallInteger( 6 )->notNull()->defaultValue( 0 ),
			'pinned' => $this->boolean()->notNull()->defaultValue( false ),
			'featured' => $this->boolean()->notNull()->defaultValue( false ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime(),
			'approvedAt' => $this->dateTime(),
			'content' => $this->mediumText(),
			'data' => $this->mediumText(),
			'gridCache' => $this->longText(),
			'gridCacheValid' => $this->boolean()->notNull()->defaultValue( false ),
			'gridCachedAt' => $this->dateTime()
        ], $this->options );

        // Indexes for forum_reply
		$this->createIndex( 'idx_' . $this->prefix . 'topic_reply_parent', $this->prefix . 'forum_topic_reply', 'topicId' );
		$this->createIndex( 'idx_' . $this->prefix . 'topic_reply_base', $this->prefix . 'forum_topic_reply', 'baseId' );
		$this->createIndex( 'idx_' . $this->prefix . 'topic_reply_banner', $this->prefix . 'forum_topic_reply', 'bannerId' );
		$this->createIndex( 'idx_' . $this->prefix . 'topic_reply_video', $this->prefix . 'forum_topic_reply', 'videoId' );
        $this->createIndex( 'idx_' . $this->prefix . 'topic_reply_creator', $this->prefix . 'forum_topic_reply', 'createdBy' );
        $this->createIndex( 'idx_' . $this->prefix . 'topic_reply_modifier', $this->prefix . 'forum_topic_reply', 'modifiedBy' );
    }

    private function generateForeignKeys() {

		// Topic
		$this->addForeignKey( 'fk_' . $this->prefix . 'topic_creator', $this->prefix . 'forum_topic', 'createdBy', $this->prefix . 'core_user', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'topic_modifier', $this->prefix . 'forum_topic', 'modifiedBy', $this->prefix . 'core_user', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'topic_site', $this->prefix . 'forum_topic', 'siteId', $this->prefix . 'core_site', 'id', 'RESTRICT' );

		// Topic Meta
		$this->addForeignKey( 'fk_' . $this->prefix . 'topic_meta_parent', $this->prefix . 'forum_topic_meta', 'modelId', $this->prefix . 'forum_topic', 'id', 'CASCADE' );

		// Topic Follower
        $this->addForeignKey( 'fk_' . $this->prefix . 'topic_follower_user', $this->prefix . 'forum_topic_follower', 'userId', $this->prefix . 'core_user', 'id', 'CASCADE' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'topic_follower_parent', $this->prefix . 'forum_topic_follower', 'modelId', $this->prefix . 'forum_topic', 'id', 'CASCADE' );

        // Reply
		$this->addForeignKey( 'fk_' . $this->prefix . 'topic_reply_parent', $this->prefix . 'forum_topic_reply', 'topicId', $this->prefix . 'forum_topic', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'topic_reply_base', $this->prefix . 'forum_topic_reply', 'baseId', $this->prefix . 'forum_topic_reply', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'topic_reply_banner', $this->prefix . 'forum_topic_reply', 'bannerId', $this->prefix . 'core_file', 'id', 'SET NULL' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'topic_reply_video', $this->prefix . 'forum_topic_reply', 'videoId', $this->prefix . 'core_file', 'id', 'SET NULL' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'topic_reply_creator', $this->prefix . 'forum_topic_reply', 'createdBy', $this->prefix . 'core_user', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'topic_reply_modifier', $this->prefix . 'forum_topic_reply', 'modifiedBy', $this->prefix . 'core_user', 'id', 'RESTRICT' );
    }

    public function down() {

        if( $this->fk ) {

            $this->dropForeignKeys();
        }

        $this->dropTable( $this->prefix . 'forum_topic' );
		$this->dropTable( $this->prefix . 'forum_topic_meta' );
		$this->dropTable( $this->prefix . 'forum_topic_follower' );
        $this->dropTable( $this->prefix . 'forum_topic_reply' );
    }

    private function dropForeignKeys() {

        // Topic
        $this->dropForeignKey( 'fk_' . $this->prefix . 'topic_creator', $this->prefix . 'forum_topic' );
        $this->dropForeignKey( 'fk_' . $this->prefix . 'topic_modifier', $this->prefix . 'forum_topic' );
        $this->dropForeignKey( 'fk_' . $this->prefix . 'topic_site', $this->prefix . 'forum_topic' );

		// Topic Meta
		$this->dropForeignKey( 'fk_' . $this->prefix . 'topic_meta_parent', $this->prefix . 'forum_topic_meta' );

		// Topic Follower
        $this->dropForeignKey( 'fk_' . $this->prefix . 'topic_follower_user', $this->prefix . 'forum_topic_follower' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'topic_follower_parent', $this->prefix . 'forum_topic_follower' );

        // Reply
		$this->dropForeignKey( 'fk_' . $this->prefix . 'topic_reply_parent', $this->prefix . 'forum_topic_reply' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'topic_reply_base', $this->prefix . 'forum_topic_reply' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'topic_reply_banner', $this->prefix . 'forum_topic_reply' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'topic_reply_video', $this->prefix . 'forum_topic_reply' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'topic_reply_creator', $this->prefix . 'forum_topic_reply' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'topic_reply_modifier', $this->prefix . 'forum_topic_reply' );
    }

}
