<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\resources\ModelStats;
use cmsgears\forum\common\models\base\ForumTables;

/**
 * The forum stats migration insert the default row count for all the tables available in
 * forum module. A scheduled console job can be executed to update these stats.
 *
 * @since 1.0.0
 */
class m180222_102486_forum_stats extends \cmsgears\core\common\base\Migration {

	// Public Variables

	public $options;

	// Private Variables

	private $prefix;

	public function init() {

		// Table prefix
		$this->prefix = Yii::$app->migration->cmgPrefix;

		// Get the values via config
		$this->options = Yii::$app->migration->getTableOptions();

		// Default collation
		if( $this->db->driverName === 'mysql' ) {

			$this->options = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}
	}

	public function up() {

		// Table Stats
		$this->insertTables();
	}

	private function insertTables() {

		$columns 	= [ 'parentId', 'parentType', 'name', 'type', 'count' ];

		$tableData	= [
			[ 1, CoreGlobal::TYPE_SITE, $this->prefix . 'forum', 'rows', 0 ],
			[ 1, CoreGlobal::TYPE_SITE, $this->prefix . 'forum_meta', 'rows', 0 ],
			[ 1, CoreGlobal::TYPE_SITE, $this->prefix . 'forum_follower', 'rows', 0 ],
			[ 1, CoreGlobal::TYPE_SITE, $this->prefix . 'forum_topic', 'rows', 0 ],
			[ 1, CoreGlobal::TYPE_SITE, $this->prefix . 'forum_topic_meta', 'rows', 0 ],
			[ 1, CoreGlobal::TYPE_SITE, $this->prefix . 'forum_topic_follower', 'rows', 0 ],
			[ 1, CoreGlobal::TYPE_SITE, $this->prefix . 'forum_topic_reply', 'rows', 0 ]
		];

		$this->batchInsert( $this->prefix . 'core_model_stats', $columns, $tableData );
	}

	public function down() {

		ModelStats::deleteByTable( ForumTables::getTableName( ForumTables::TABLE_FORUM ) );
		ModelStats::deleteByTable( ForumTables::getTableName( ForumTables::TABLE_FORUM_META ) );
		ModelStats::deleteByTable( ForumTables::getTableName( ForumTables::TABLE_FORUM_FOLLOWER ) );
		ModelStats::deleteByTable( ForumTables::getTableName( ForumTables::TABLE_TOPIC ) );
		ModelStats::deleteByTable( ForumTables::getTableName( ForumTables::TABLE_TOPIC_META ) );
		ModelStats::deleteByTable( ForumTables::getTableName( ForumTables::TABLE_TOPIC_FOLLOWER ) );
		ModelStats::deleteByTable( ForumTables::getTableName( ForumTables::TABLE_TOPIC_REPLY ) );
	}

}
