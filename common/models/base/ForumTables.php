<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\forum\common\models\base;

// CMG Imports
use cmsgears\core\common\models\base\DbTables;

/**
 * It provide table name constants of db tables available in Forum Module.
 *
 * @since 1.0.0
 */
class ForumTables extends DbTables {

	// Entities -------------

    const TABLE_TOPIC = 'cmg_forum_topic';

	// Resources ------------

	const TABLE_TOPIC_META	= 'cmg_forum_topic_meta';
	const TABLE_TOPIC_REPLY	= 'cmg_forum_topic_reply';

	// Mappers --------------

	const TABLE_TOPIC_FOLLOWER = 'cmg_forum_topic_follower';

}
