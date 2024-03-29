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
use cmsgears\cms\common\config\CmsGlobal;
use cmsgears\forum\common\config\ForumGlobal;

use cmsgears\core\common\models\entities\Site;
use cmsgears\core\common\models\entities\User;
use cmsgears\core\common\models\entities\Role;
use cmsgears\core\common\models\entities\Permission;
use cmsgears\cms\common\models\entities\Page;

use cmsgears\core\common\utilities\DateUtil;

/**
 * The newsletter data migration inserts the base data required to run the application.
 *
 * @since 1.0.0
 */
class m180222_102221_forum_data extends \cmsgears\core\common\base\Migration {

	// Public Variables

	// Private Variables

	private $prefix;

	private $site;

	private $master;

	public function init() {

		// Table prefix
		$this->prefix = Yii::$app->migration->cmgPrefix;

		$this->site		= Site::findBySlug( CoreGlobal::SITE_MAIN );
		$this->master	= User::findByUsername( Yii::$app->migration->getSiteMaster() );

		Yii::$app->core->setSite( $this->site );
	}

    public function up() {

		// Create RBAC and Site Members
		$this->insertRolePermission();

		// Create forum permission groups and CRUD permissions
		$this->insertForumPermissions();

		// Create topic permission groups and CRUD permissions
		$this->insertTopicPermissions();

		// Init system pages
		$this->insertSystemPages();

		// Notifications
		$this->insertNotificationTemplates();
	}

	private function insertRolePermission() {

		// Roles

		$columns = [ 'createdBy', 'modifiedBy', 'name', 'slug', 'adminUrl', 'homeUrl', 'type', 'icon', 'description', 'createdAt', 'modifiedAt' ];

		$roles = [
			[ $this->master->id, $this->master->id, 'Forum Admin', ForumGlobal::ROLE_FORUM_ADMIN, 'dashboard', NULL, CoreGlobal::TYPE_SYSTEM, NULL, 'The role Forum Admin is limited to manage forum from admin.', DateUtil::getDateTime(), DateUtil::getDateTime() ]
		];

		$this->batchInsert( $this->prefix . 'core_role', $columns, $roles );

		$superAdminRole	= Role::findBySlugType( 'super-admin', CoreGlobal::TYPE_SYSTEM );
		$adminRole		= Role::findBySlugType( 'admin', CoreGlobal::TYPE_SYSTEM );
		$forumAdminRole	= Role::findBySlugType( ForumGlobal::ROLE_FORUM_ADMIN, CoreGlobal::TYPE_SYSTEM );

		// Permissions

		$columns = [ 'createdBy', 'modifiedBy', 'name', 'slug', 'type', 'icon', 'description', 'createdAt', 'modifiedAt' ];

		$permissions = [
			[ $this->master->id, $this->master->id, 'Admin Forum', ForumGlobal::PERM_FORUM_ADMIN, CoreGlobal::TYPE_SYSTEM, null, 'The permission admin forum is to manage forum from admin.', DateUtil::getDateTime(), DateUtil::getDateTime() ]
		];

		$this->batchInsert( $this->prefix . 'core_permission', $columns, $permissions );

		$adminPerm		= Permission::findBySlugType( 'admin', CoreGlobal::TYPE_SYSTEM );
		$userPerm		= Permission::findBySlugType( 'user', CoreGlobal::TYPE_SYSTEM );
		$forumAdminPerm	= Permission::findBySlugType( ForumGlobal::PERM_FORUM_ADMIN, CoreGlobal::TYPE_SYSTEM );

		// RBAC Mapping

		$columns = [ 'roleId', 'permissionId' ];

		$mappings = [
			[ $superAdminRole->id, $forumAdminPerm->id ],
			[ $adminRole->id, $forumAdminPerm->id ],
			[ $forumAdminRole->id, $adminPerm->id ], [ $forumAdminRole->id, $userPerm->id ], [ $forumAdminRole->id, $forumAdminPerm->id ]
		];

		$this->batchInsert( $this->prefix . 'core_role_permission', $columns, $mappings );
	}

	private function insertForumPermissions() {

		// Permissions
		$columns = [ 'createdBy', 'modifiedBy', 'name', 'slug', 'type', 'icon', 'group', 'description', 'createdAt', 'modifiedAt' ];

		$permissions = [
			// Permission Groups - Default - Website - Individual, Organization
			[ $this->master->id, $this->master->id, 'Manage Forums', ForumGlobal::PERM_FORUM_MANAGE, CoreGlobal::TYPE_SYSTEM, NULL, true, 'The permission manage newsletters allows user to manage newsletters from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Forum Author', ForumGlobal::PERM_FORUM_AUTHOR, CoreGlobal::TYPE_SYSTEM, NULL, true, 'The permission newsletter author allows user to perform crud operations of newsletter belonging to respective author from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],

			// Forum Permissions - Hard Coded - Website - Individual, Organization
			[ $this->master->id, $this->master->id, 'View Forums', ForumGlobal::PERM_FORUM_VIEW, CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission view forums allows users to view their forums from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Add Forum', ForumGlobal::PERM_FORUM_ADD, CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission add forum allows users to create forum from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Update Forum', ForumGlobal::PERM_FORUM_UPDATE, CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission update forum allows users to update forum from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Delete Forum', ForumGlobal::PERM_FORUM_DELETE, CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission delete forum allows users to delete forum from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Approve Forum', ForumGlobal::PERM_FORUM_APPROVE, CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission approve forum allows user to approve, freeze or block forum from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Print Forum', ForumGlobal::PERM_FORUM_PRINT, CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission print forum allows user to print forum from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Import Forums', ForumGlobal::PERM_FORUM_IMPORT, CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission import forums allows user to import forums from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Export Forums', ForumGlobal::PERM_FORUM_EXPORT, CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission export forums allows user to export forums from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ]
		];

		$this->batchInsert( $this->prefix . 'core_permission', $columns, $permissions );

		// Permission Groups
		$forumManagerPerm	= Permission::findBySlugType( ForumGlobal::PERM_FORUM_MANAGE, CoreGlobal::TYPE_SYSTEM );
		$forumAuthorPerm	= Permission::findBySlugType( ForumGlobal::PERM_FORUM_AUTHOR, CoreGlobal::TYPE_SYSTEM );

		// Permissions
		$vForumsPerm	= Permission::findBySlugType( ForumGlobal::PERM_FORUM_VIEW, CoreGlobal::TYPE_SYSTEM );
		$aForumPerm		= Permission::findBySlugType( ForumGlobal::PERM_FORUM_ADD, CoreGlobal::TYPE_SYSTEM );
		$uForumPerm		= Permission::findBySlugType( ForumGlobal::PERM_FORUM_UPDATE, CoreGlobal::TYPE_SYSTEM );
		$dForumPerm		= Permission::findBySlugType( ForumGlobal::PERM_FORUM_DELETE, CoreGlobal::TYPE_SYSTEM );
		$apForumPerm	= Permission::findBySlugType( ForumGlobal::PERM_FORUM_APPROVE, CoreGlobal::TYPE_SYSTEM );
		$pForumPerm		= Permission::findBySlugType( ForumGlobal::PERM_FORUM_PRINT, CoreGlobal::TYPE_SYSTEM );
		$iForumsPerm	= Permission::findBySlugType( ForumGlobal::PERM_FORUM_IMPORT, CoreGlobal::TYPE_SYSTEM );
		$eForumsPerm	= Permission::findBySlugType( ForumGlobal::PERM_FORUM_EXPORT, CoreGlobal::TYPE_SYSTEM );

		//Hierarchy

		$columns = [ 'parentId', 'childId', 'rootId', 'parentType', 'lValue', 'rValue' ];

		$hierarchy = [
			// Forum Manager - Organization, Approver
			[ null, null, $forumManagerPerm->id, CoreGlobal::TYPE_PERMISSION, 1, 18 ],
			[ $forumManagerPerm->id, $vForumsPerm->id, $forumManagerPerm->id, CoreGlobal::TYPE_PERMISSION, 2, 3 ],
			[ $forumManagerPerm->id, $aForumPerm->id, $forumManagerPerm->id, CoreGlobal::TYPE_PERMISSION, 4, 5 ],
			[ $forumManagerPerm->id, $uForumPerm->id, $forumManagerPerm->id, CoreGlobal::TYPE_PERMISSION, 6, 7 ],
			[ $forumManagerPerm->id, $dForumPerm->id, $forumManagerPerm->id, CoreGlobal::TYPE_PERMISSION, 8, 9 ],
			[ $forumManagerPerm->id, $apForumPerm->id, $forumManagerPerm->id, CoreGlobal::TYPE_PERMISSION, 10, 11 ],
			[ $forumManagerPerm->id, $pForumPerm->id, $forumManagerPerm->id, CoreGlobal::TYPE_PERMISSION, 12, 13 ],
			[ $forumManagerPerm->id, $iForumsPerm->id, $forumManagerPerm->id, CoreGlobal::TYPE_PERMISSION, 14, 15 ],
			[ $forumManagerPerm->id, $eForumsPerm->id, $forumManagerPerm->id, CoreGlobal::TYPE_PERMISSION, 16, 17 ],

			// Forum Author- Individual
			[ null, null, $forumAuthorPerm->id, CoreGlobal::TYPE_PERMISSION, 1, 16 ],
			[ $forumAuthorPerm->id, $vForumsPerm->id, $forumAuthorPerm->id, CoreGlobal::TYPE_PERMISSION, 2, 3 ],
			[ $forumAuthorPerm->id, $aForumPerm->id, $forumAuthorPerm->id, CoreGlobal::TYPE_PERMISSION, 4, 5 ],
			[ $forumAuthorPerm->id, $uForumPerm->id, $forumAuthorPerm->id, CoreGlobal::TYPE_PERMISSION, 6, 7 ],
			[ $forumAuthorPerm->id, $dForumPerm->id, $forumAuthorPerm->id, CoreGlobal::TYPE_PERMISSION, 8, 9 ],
			[ $forumAuthorPerm->id, $pForumPerm->id, $forumAuthorPerm->id, CoreGlobal::TYPE_PERMISSION, 10, 11 ],
			[ $forumAuthorPerm->id, $iForumsPerm->id, $forumAuthorPerm->id, CoreGlobal::TYPE_PERMISSION, 12, 13 ],
			[ $forumAuthorPerm->id, $eForumsPerm->id, $forumAuthorPerm->id, CoreGlobal::TYPE_PERMISSION, 14, 15 ]
		];

		$this->batchInsert( $this->prefix . 'core_model_hierarchy', $columns, $hierarchy );
	}

	private function insertTopicPermissions() {

		// Permissions
		$columns = [ 'createdBy', 'modifiedBy', 'name', 'slug', 'type', 'icon', 'group', 'description', 'createdAt', 'modifiedAt' ];

		$permissions = [
			// Permission Groups - Default - Website - Individual, Organization
			[ $this->master->id, $this->master->id, 'Manage Topics', ForumGlobal::PERM_TOPIC_MANAGE, CoreGlobal::TYPE_SYSTEM, NULL, true, 'The permission manage newsletters allows user to manage newsletters from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Topic Author', ForumGlobal::PERM_TOPIC_AUTHOR, CoreGlobal::TYPE_SYSTEM, NULL, true, 'The permission newsletter author allows user to perform crud operations of newsletter belonging to respective author from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],

			// Topic Permissions - Hard Coded - Website - Individual, Organization
			[ $this->master->id, $this->master->id, 'View Topics', ForumGlobal::PERM_TOPIC_VIEW, CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission view topics allows users to view their topics from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Add Topic', ForumGlobal::PERM_TOPIC_ADD, CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission add topic allows users to create topic from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Update Topic', ForumGlobal::PERM_TOPIC_UPDATE, CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission update topic allows users to update topic from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Delete Topic', ForumGlobal::PERM_TOPIC_DELETE, CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission delete topic allows users to delete topic from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Approve Topic', ForumGlobal::PERM_TOPIC_APPROVE, CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission approve topic allows user to approve, freeze or block topic from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Print Topic', ForumGlobal::PERM_TOPIC_PRINT, CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission print topic allows user to print topic from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Import Topics', ForumGlobal::PERM_TOPIC_IMPORT, CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission import topics allows user to import topics from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Export Topics', ForumGlobal::PERM_TOPIC_EXPORT, CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission export topics allows user to export topics from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ]
		];

		$this->batchInsert( $this->prefix . 'core_permission', $columns, $permissions );

		// Permission Groups
		$topicManagerPerm	= Permission::findBySlugType( ForumGlobal::PERM_TOPIC_MANAGE, CoreGlobal::TYPE_SYSTEM );
		$topicAuthorPerm	= Permission::findBySlugType( ForumGlobal::PERM_TOPIC_AUTHOR, CoreGlobal::TYPE_SYSTEM );

		// Permissions
		$vTopicsPerm	= Permission::findBySlugType( ForumGlobal::PERM_TOPIC_VIEW, CoreGlobal::TYPE_SYSTEM );
		$aTopicPerm		= Permission::findBySlugType( ForumGlobal::PERM_TOPIC_ADD, CoreGlobal::TYPE_SYSTEM );
		$uTopicPerm		= Permission::findBySlugType( ForumGlobal::PERM_TOPIC_UPDATE, CoreGlobal::TYPE_SYSTEM );
		$dTopicPerm		= Permission::findBySlugType( ForumGlobal::PERM_TOPIC_DELETE, CoreGlobal::TYPE_SYSTEM );
		$apTopicPerm	= Permission::findBySlugType( ForumGlobal::PERM_TOPIC_APPROVE, CoreGlobal::TYPE_SYSTEM );
		$pTopicPerm		= Permission::findBySlugType( ForumGlobal::PERM_TOPIC_PRINT, CoreGlobal::TYPE_SYSTEM );
		$iTopicsPerm	= Permission::findBySlugType( ForumGlobal::PERM_TOPIC_IMPORT, CoreGlobal::TYPE_SYSTEM );
		$eTopicsPerm	= Permission::findBySlugType( ForumGlobal::PERM_TOPIC_EXPORT, CoreGlobal::TYPE_SYSTEM );

		//Hierarchy

		$columns = [ 'parentId', 'childId', 'rootId', 'parentType', 'lValue', 'rValue' ];

		$hierarchy = [
			// Topic Manager - Organization, Approver
			[ null, null, $topicManagerPerm->id, CoreGlobal::TYPE_PERMISSION, 1, 18 ],
			[ $topicManagerPerm->id, $vTopicsPerm->id, $topicManagerPerm->id, CoreGlobal::TYPE_PERMISSION, 2, 3 ],
			[ $topicManagerPerm->id, $aTopicPerm->id, $topicManagerPerm->id, CoreGlobal::TYPE_PERMISSION, 4, 5 ],
			[ $topicManagerPerm->id, $uTopicPerm->id, $topicManagerPerm->id, CoreGlobal::TYPE_PERMISSION, 6, 7 ],
			[ $topicManagerPerm->id, $dTopicPerm->id, $topicManagerPerm->id, CoreGlobal::TYPE_PERMISSION, 8, 9 ],
			[ $topicManagerPerm->id, $apTopicPerm->id, $topicManagerPerm->id, CoreGlobal::TYPE_PERMISSION, 10, 11 ],
			[ $topicManagerPerm->id, $pTopicPerm->id, $topicManagerPerm->id, CoreGlobal::TYPE_PERMISSION, 12, 13 ],
			[ $topicManagerPerm->id, $iTopicsPerm->id, $topicManagerPerm->id, CoreGlobal::TYPE_PERMISSION, 14, 15 ],
			[ $topicManagerPerm->id, $eTopicsPerm->id, $topicManagerPerm->id, CoreGlobal::TYPE_PERMISSION, 16, 17 ],

			// Topic Author- Individual
			[ null, null, $topicAuthorPerm->id, CoreGlobal::TYPE_PERMISSION, 1, 16 ],
			[ $topicAuthorPerm->id, $vTopicsPerm->id, $topicAuthorPerm->id, CoreGlobal::TYPE_PERMISSION, 2, 3 ],
			[ $topicAuthorPerm->id, $aTopicPerm->id, $topicAuthorPerm->id, CoreGlobal::TYPE_PERMISSION, 4, 5 ],
			[ $topicAuthorPerm->id, $uTopicPerm->id, $topicAuthorPerm->id, CoreGlobal::TYPE_PERMISSION, 6, 7 ],
			[ $topicAuthorPerm->id, $dTopicPerm->id, $topicAuthorPerm->id, CoreGlobal::TYPE_PERMISSION, 8, 9 ],
			[ $topicAuthorPerm->id, $pTopicPerm->id, $topicAuthorPerm->id, CoreGlobal::TYPE_PERMISSION, 10, 11 ],
			[ $topicAuthorPerm->id, $iTopicsPerm->id, $topicAuthorPerm->id, CoreGlobal::TYPE_PERMISSION, 12, 13 ],
			[ $topicAuthorPerm->id, $eTopicsPerm->id, $topicAuthorPerm->id, CoreGlobal::TYPE_PERMISSION, 14, 15 ]
		];

		$this->batchInsert( $this->prefix . 'core_model_hierarchy', $columns, $hierarchy );
	}

	private function insertSystemPages() {

		$columns = [ 'siteId', 'createdBy', 'modifiedBy', 'name', 'slug', 'type', 'icon', 'title', 'status', 'visibility', 'order', 'featured', 'comments', 'createdAt', 'modifiedAt' ];

		$pages	= [
			// Topic Pages
			[ $this->site->id, $this->master->id, $this->master->id, 'Forum', ForumGlobal::PAGE_FORUM, CmsGlobal::TYPE_PAGE, null, null, Page::STATUS_ACTIVE, Page::VISIBILITY_PUBLIC, 0, false, false, DateUtil::getDateTime(), DateUtil::getDateTime() ],
			// Search Pages
			[ $this->site->id, $this->master->id, $this->master->id, 'Search Topics', ForumGlobal::PAGE_SEARCH_TOPICS, CmsGlobal::TYPE_PAGE, null, null, Page::STATUS_ACTIVE, Page::VISIBILITY_PUBLIC, 0, false, false, DateUtil::getDateTime(), DateUtil::getDateTime() ]
		];

		$this->batchInsert( $this->prefix . 'cms_page', $columns, $pages );

		$summary = "Lorem ipsum is a pseudo-Latin text used in web design, typography, layout, and printing in place of English to emphasise design elements over content. It\'s also called placeholder (or filler) text. It\'s a convenient tool for mock-ups. It helps to outline the visual elements of a document or presentation, eg typography, font, or layout. Lorem ipsum is mostly a part of a Latin text by the classical author and philosopher Cicero.";
		$content = "Lorem ipsum is a pseudo-Latin text used in web design, typography, layout, and printing in place of English to emphasise design elements over content. It\'s also called placeholder (or filler) text. It\'s a convenient tool for mock-ups. It helps to outline the visual elements of a document or presentation, eg typography, font, or layout. Lorem ipsum is mostly a part of a Latin text by the classical author and philosopher Cicero.";

		$columns = [ 'parentId', 'parentType', 'seoName', 'seoDescription', 'seoKeywords', 'seoRobot', 'summary', 'content', 'publishedAt' ];

		$pages	= [
			// Topic Pages
			[ Page::findBySlugType( ForumGlobal::PAGE_FORUM, CmsGlobal::TYPE_PAGE )->id, CmsGlobal::TYPE_PAGE, null, null, null, null, $summary, $content, DateUtil::getDateTime() ],
			// Search Pages
			[ Page::findBySlugType( ForumGlobal::PAGE_SEARCH_TOPICS, CmsGlobal::TYPE_PAGE )->id, CmsGlobal::TYPE_PAGE, null, null, null, null, $summary, $content, DateUtil::getDateTime() ]
		];

		$this->batchInsert( $this->prefix . 'cms_model_content', $columns, $pages );
	}

	private function insertNotificationTemplates() {

		$columns = [ 'createdBy', 'modifiedBy', 'name', 'slug', 'icon', 'type', 'description', 'active', 'renderer', 'fileRender', 'layout', 'layoutGroup', 'viewPath', 'createdAt', 'modifiedAt', 'message', 'content', 'data' ];

		$templates = [
			// Forum
			[ $this->master->id, $this->master->id, 'New Forum', ForumGlobal::TPL_NOTIFY_FORUM_NEW, null, 'notification', 'Trigger Notification and Email to Admin, when new Forum is created by site users.', true, 'twig', 0, null, false, null, DateUtil::getDateTime(), DateUtil::getDateTime(), 'New Forum - <b>{{model.displayName}}</b>', 'New Forum - <b>{{model.displayName}}</b> has been submitted for registration. {% if config.link %}Please follow this <a href="{{config.link}}">link</a>.{% endif %}{% if config.adminLink %}Please follow this <a href="{{config.adminLink}}">link</a>.{% endif %}', '{"config":{"admin":"1","user":"0","direct":"0","adminEmail":"1","userEmail":"0","directEmail":"0"}}' ],
			// Topic
			[ $this->master->id, $this->master->id, 'New Topic', ForumGlobal::TPL_NOTIFY_TOPIC_NEW, null, 'notification', 'Trigger Notification and Email to Admin, when new Topic is created by site users.', true, 'twig', 0, null, false, null, DateUtil::getDateTime(), DateUtil::getDateTime(), 'New Topic - <b>{{model.displayName}}</b>', 'New Topic - <b>{{model.displayName}}</b> has been submitted for registration. {% if config.link %}Please follow this <a href="{{config.link}}">link</a>.{% endif %}{% if config.adminLink %}Please follow this <a href="{{config.adminLink}}">link</a>.{% endif %}', '{"config":{"admin":"1","user":"0","direct":"0","adminEmail":"1","userEmail":"0","directEmail":"0"}}' ]
		];

		$this->batchInsert( $this->prefix . 'core_template', $columns, $templates );
	}

    public function down() {

        echo "m180222_102221_forum_data will be deleted with m160621_014408_core.\n";

        return true;
    }

}
