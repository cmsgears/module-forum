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

/**
 * The newsletter index migration inserts the recommended indexes for better performance. It
 * also list down other possible index commented out. These indexes can be created using
 * project based migration script.
 *
 * @since 1.0.0
 */
class m180222_102301_forum_index extends Migration {

	// Public Variables

	// Private Variables

	private $prefix;

	public function init() {

		// Table prefix
		$this->prefix = Yii::$app->migration->cmgPrefix;
	}

	public function up() {

		$this->upPrimary();
	}

    private function upPrimary() {

        // Topic
        $this->createIndex( 'idx_' . $this->prefix . 'topic_name', $this->prefix . 'forum_topic', 'name' );
        $this->createIndex( 'idx_' . $this->prefix . 'topic_slug', $this->prefix . 'forum_topic', 'slug' );
		$this->createIndex( 'idx_' . $this->prefix . 'topic_type', $this->prefix . 'forum_topic', 'type' );
		//$this->createIndex( 'idx_' . $this->prefix . 'topic_icon', $this->prefix . 'forum_topic', 'icon' );

		// Topic Meta
		$this->createIndex( 'idx_' . $this->prefix . 'topic_meta_name', $this->prefix . 'forum_topic_meta', 'name' );
		$this->createIndex( 'idx_' . $this->prefix . 'topic_meta_type', $this->prefix . 'forum_topic_meta', 'type' );
		//$this->createIndex( 'idx_' . $this->prefix . 'topic_meta_label', $this->prefix . 'forum_topic_meta', 'label' );
		//$this->createIndex( 'idx_' . $this->prefix . 'topic_meta_vtype', $this->prefix . 'forum_topic_meta', 'valueType' );
		//$this->createIndex( 'idx_' . $this->prefix . 'topic_meta_mit', $this->prefix . 'forum_topic_meta', [ 'modelId', 'type' ] );
		//$this->createIndex( 'idx_' . $this->prefix . 'topic_meta_mitn', $this->prefix . 'forum_topic_meta', [ 'modelId', 'type', 'name' ] );
		//$this->execute( 'ALTER TABLE ' . $this->prefix . 'forum_topic_meta' . ' ADD FULLTEXT ' . 'idx_' . $this->prefix . 'topic_meta_search' . '(name ASC, value ASC)' );

		// Topic Reply
		//$this->createIndex( 'idx_' . $this->prefix . 'topic_reply_ip', $this->prefix . 'forum_topic_reply', 'ip' );
		//$this->createIndex( 'idx_' . $this->prefix . 'topic_reply_ipn', $this->prefix . 'forum_topic_reply', 'ipNum' );
		//$this->createIndex( 'idx_' . $this->prefix . 'topic_reply_agent', $this->prefix . 'forum_topic_reply', 'agent' );
		//$this->execute( 'ALTER TABLE ' . $this->prefix . 'forum_topic_reply' . ' ADD FULLTEXT ' . 'idx_' . $this->prefix . 'topic_reply_search' . '(agent ASC, content ASC)' );
    }

    public function down() {

        $this->downPrimary();
    }

    private function downPrimary() {

        // Topic
        $this->dropIndex( 'idx_' . $this->prefix . 'topic_name', $this->prefix . 'forum_topic' );
        $this->dropIndex( 'idx_' . $this->prefix . 'topic_slug', $this->prefix . 'forum_topic' );
		$this->dropIndex( 'idx_' . $this->prefix . 'topic_type', $this->prefix . 'forum_topic' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'topic_icon', $this->prefix . 'forum_topic' );

		// Topic Meta
		$this->dropIndex( 'idx_' . $this->prefix . 'topic_meta_name', $this->prefix . 'forum_topic_meta' );
		$this->dropIndex( 'idx_' . $this->prefix . 'topic_meta_type', $this->prefix . 'forum_topic_meta' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'topic_meta_label', $this->prefix . 'forum_topic_meta' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'topic_meta_vtype', $this->prefix . 'forum_topic_meta' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'topic_meta_mit', $this->prefix . 'forum_topic_meta' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'topic_meta_mitn', $this->prefix . 'forum_topic_meta' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'topic_meta_search', $this->prefix . 'forum_topic_meta' );

		// Topic Reply
		//$this->dropIndex( 'idx_' . $this->prefix . 'topic_reply_ip', $this->prefix . 'forum_topic_reply' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'topic_reply_ipn', $this->prefix . 'forum_topic_reply' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'topic_reply_agent', $this->prefix . 'forum_topic_reply' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'topic_reply_search', $this->prefix . 'forum_topic_reply' );
    }

}
