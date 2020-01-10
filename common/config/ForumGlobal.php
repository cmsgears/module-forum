<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\forum\common\config;

/**
 * ForumGlobal defines the global constants and variables available for forum and dependent modules.
 *
 * @since 1.0.0
 */
class ForumGlobal {

	// System Sites ---------------------------------------------------

	const SITE_FORUM = 'forum';

	// System Pages ---------------------------------------------------

	const PAGE_SEARCH_TOPICS = 'forum';

	// Grouping by type ------------------------------------------------

	const TYPE_TOPIC		= 'topic';
	const TYPE_TOPIC_REPLY	= 'topic-reply';

	// Templates -------------------------------------------------------

	const TEMPLATE_FORUM = 'forum';

	const TPL_NOTIFY_TOPIC_NEW = 'topic-new';

	// Config ----------------------------------------------------------

	// Roles -----------------------------------------------------------

	const ROLE_FORUM_ADMIN	= 'forum-admin';

	// Permissions -----------------------------------------------------

	// Forum
	const PERM_FORUM_ADMIN		= 'admin-forum';

	const PERM_TOPIC_MANAGE		= 'manage-topics';
	const PERM_TOPIC_AUTHOR		= 'topic-author';

	const PERM_TOPIC_VIEW		= 'view-topics';
	const PERM_TOPIC_ADD		= 'add-topic';
	const PERM_TOPIC_UPDATE		= 'update-topic';
	const PERM_TOPIC_DELETE		= 'delete-topic';
	const PERM_TOPIC_APPROVE	= 'approve-topic';
	const PERM_TOPIC_PRINT		= 'print-topic';
	const PERM_TOPIC_IMPORT		= 'import-topics';
	const PERM_TOPIC_EXPORT		= 'export-topics';

	// Model Attributes ------------------------------------------------

	// Default Maps ----------------------------------------------------

	// Messages --------------------------------------------------------

	// Errors ----------------------------------------------------------

	// Model Fields ----------------------------------------------------

	// Generic Fields
	const FIELD_TOPIC = 'topicField';

}
