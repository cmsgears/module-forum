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

use cmsgears\core\common\base\Migration;

use cmsgears\core\common\models\entities\Site;
use cmsgears\core\common\models\entities\User;
use cmsgears\core\common\models\entities\Role;
use cmsgears\core\common\models\entities\Permission;

use cmsgears\core\common\utilities\DateUtil;

/**
 * The newsletter data migration inserts the base data required to run the application.
 *
 * @since 1.0.0
 */
class m180222_102221_forum_data extends Migration {

	// Public Variables

	// Private Variables

	private $prefix;

	private $site;

	private $master;

	public function init() {

		// Table prefix
		$this->prefix	= Yii::$app->migration->cmgPrefix;

		$this->site		= Site::findBySlug( CoreGlobal::SITE_MAIN );
		$this->master	= User::findByUsername( Yii::$app->migration->getSiteMaster() );

		Yii::$app->core->setSite( $this->site );
	}

    public function up() {

		// Create RBAC and Site Members
		$this->insertRolePermission();

		// Create newsletter permission groups and CRUD permissions
		$this->insertTopicPermissions();
    }

	private function insertRolePermission() {

		// Roles

		$columns = [ 'createdBy', 'modifiedBy', 'name', 'slug', 'adminUrl', 'homeUrl', 'type', 'icon', 'description', 'createdAt', 'modifiedAt' ];

		$roles = [
			[ $this->master->id, $this->master->id, 'Forum Admin', 'forum-admin', 'dashboard', NULL, CoreGlobal::TYPE_SYSTEM, NULL, 'The role Forum Admin is limited to manage forum from admin.', DateUtil::getDateTime(), DateUtil::getDateTime() ]
		];

		$this->batchInsert( $this->prefix . 'core_role', $columns, $roles );

		$superAdminRole		= Role::findBySlugType( 'super-admin', CoreGlobal::TYPE_SYSTEM );
		$adminRole			= Role::findBySlugType( 'admin', CoreGlobal::TYPE_SYSTEM );
		$forumAdminRole		= Role::findBySlugType( 'forum-admin', CoreGlobal::TYPE_SYSTEM );

		// Permissions

		$columns = [ 'createdBy', 'modifiedBy', 'name', 'slug', 'type', 'icon', 'description', 'createdAt', 'modifiedAt' ];

		$permissions = [
			[ $this->master->id, $this->master->id, 'Admin Forum', 'admin-forum', CoreGlobal::TYPE_SYSTEM, null, 'The permission admin forum is to manage forum from admin.', DateUtil::getDateTime(), DateUtil::getDateTime() ]
		];

		$this->batchInsert( $this->prefix . 'core_permission', $columns, $permissions );

		$adminPerm		= Permission::findBySlugType( 'admin', CoreGlobal::TYPE_SYSTEM );
		$userPerm		= Permission::findBySlugType( 'user', CoreGlobal::TYPE_SYSTEM );
		$forumAdminPerm	= Permission::findBySlugType( 'admin-forum', CoreGlobal::TYPE_SYSTEM );

		// RBAC Mapping

		$columns = [ 'roleId', 'permissionId' ];

		$mappings = [
			[ $superAdminRole->id, $forumAdminPerm->id ],
			[ $adminRole->id, $forumAdminPerm->id ],
			[ $forumAdminRole->id, $adminPerm->id ], [ $forumAdminRole->id, $userPerm->id ], [ $forumAdminRole->id, $forumAdminPerm->id ]
		];

		$this->batchInsert( $this->prefix . 'core_role_permission', $columns, $mappings );
	}

	private function insertTopicPermissions() {

		// Permissions
		$columns = [ 'createdBy', 'modifiedBy', 'name', 'slug', 'type', 'icon', 'group', 'description', 'createdAt', 'modifiedAt' ];

		$permissions = [
			// Permission Groups - Default - Website - Individual, Organization
			[ $this->master->id, $this->master->id, 'Manage Topics', 'manage-topics', CoreGlobal::TYPE_SYSTEM, NULL, true, 'The permission manage newsletters allows user to manage newsletters from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Topic Author', 'topic-author', CoreGlobal::TYPE_SYSTEM, NULL, true, 'The permission newsletter author allows user to perform crud operations of newsletter belonging to respective author from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],

			// Newsletter Permissions - Hard Coded - Website - Individual, Organization
			[ $this->master->id, $this->master->id, 'View Topics', 'view-topics', CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission view topics allows users to view their topics from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Add Topic', 'add-topic', CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission add topic allows users to create topic from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Update Topic', 'update-topic', CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission update topic allows users to update topic from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Delete Topic', 'delete-topic', CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission delete topic allows users to delete topic from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Approve Topic', 'approve-topic', CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission approve topic allows user to approve, freeze or block topic from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Print Topic', 'print-topic', CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission print topic allows user to print topic from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Import Topics', 'import-topics', CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission import topics allows user to import topics from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Export Topics', 'export-topics', CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission export topics allows user to export topics from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ]
		];

		$this->batchInsert( $this->prefix . 'core_permission', $columns, $permissions );

		// Permission Groups
		$topicManagerPerm	= Permission::findBySlugType( 'manage-topics', CoreGlobal::TYPE_SYSTEM );
		$topicAuthorPerm	= Permission::findBySlugType( 'topic-author', CoreGlobal::TYPE_SYSTEM );

		// Permissions
		$vTopicsPerm	= Permission::findBySlugType( 'view-topics', CoreGlobal::TYPE_SYSTEM );
		$aTopicPerm		= Permission::findBySlugType( 'add-topic', CoreGlobal::TYPE_SYSTEM );
		$uTopicPerm		= Permission::findBySlugType( 'update-topic', CoreGlobal::TYPE_SYSTEM );
		$dTopicPerm		= Permission::findBySlugType( 'delete-topic', CoreGlobal::TYPE_SYSTEM );
		$apTopicPerm	= Permission::findBySlugType( 'approve-topic', CoreGlobal::TYPE_SYSTEM );
		$pTopicPerm		= Permission::findBySlugType( 'print-topic', CoreGlobal::TYPE_SYSTEM );
		$iTopicsPerm	= Permission::findBySlugType( 'import-topics', CoreGlobal::TYPE_SYSTEM );
		$eTopicsPerm	= Permission::findBySlugType( 'export-topics', CoreGlobal::TYPE_SYSTEM );

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

    public function down() {

        echo "m180222_102221_forum_data will be deleted with m160621_014408_core.\n";

        return true;
    }

}
