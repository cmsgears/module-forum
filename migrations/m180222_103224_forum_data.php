<?php
// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\User;
use cmsgears\core\common\models\entities\Locale;
use cmsgears\core\common\models\entities\Role;
use cmsgears\core\common\models\entities\Permission;

use cmsgears\core\common\utilities\DateUtil;

class m180222_103224_forum_data extends \yii\db\Migration {

    // Public Variables

    // Private Variables

    private $prefix;

    // Entities

    private $locale;

    private $master;

    // Config

    private $siteName;
    private $siteTitle;

    private $siteMaster;

    private $primaryDomain;

    public function init() {

        // Table prefix
        $this->prefix		= Yii::$app->migration->cmgPrefix;

        // Site config
        $this->siteName		= Yii::$app->migration->getSiteName();
        $this->siteTitle	= Yii::$app->migration->getSiteTitle();
        $this->siteMaster	= Yii::$app->migration->getSiteMaster();

        $this->primaryDomain	= Yii::$app->migration->getPrimaryDomain();
        
        // Init Locale
        $this->locale = Locale::findByCode( 'en_US' );
    }
    
    public function up() {
        
        // Create default users
        $this->insertDefaultUsers();
        
        // Create roles and permissions
        $this->insertRolePermission();
    }
    
    private function insertDefaultUsers() {

        $primaryDomain	= $this->primaryDomain;

        // Default password for all test users is test#123
        // Forum Manager i.e. demoforummanager must change password on first login and remove other users if required.

        $columns = [ 'localeId', 'status', 'email', 'username', 'passwordHash', 'firstName', 'lastName', 'registeredAt', 'lastLoginAt', 'authKey' ];

        $users	= [];

        if( Yii::$app->migration->isTestAccounts() ) {

                $users[]	= [ $this->locale->id, User::STATUS_ACTIVE, "demoforummanager@$primaryDomain", 'demoforummanager','$2y$13$Ut5b2RskRpGA9Q0nKSO6Xe65eaBHdx/q8InO8Ln6Lt3HzOK4ECz8W','demo','user', DateUtil::getDateTime(), DateUtil::getDateTime(), 'SQ1LLCWEPva4IKuQklILLGDpmUTGzq8E' ];                
        }

        $this->batchInsert( $this->prefix . 'core_user', $columns, $users );

        $this->master	= User::findByUsername( $this->siteMaster );
    }
    
    private function insertRolePermission() {
        
        // Roles

        $columns = [ 'createdBy', 'modifiedBy', 'name', 'slug', 'adminUrl', 'homeUrl', 'type', 'icon', 'description', 'createdAt', 'modifiedAt' ];

        $roles = [
                [ $this->master->id, $this->master->id, 'Forum Manager', 'forum-manager', 'dashboard', NULL, CoreGlobal::TYPE_SYSTEM, NULL, 'The role Forum Manager is limited to manage forums from admin.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
        ];

        $this->batchInsert( $this->prefix . 'core_role', $columns, $roles );

        $forumManagerRole   = Role::findBySlugType( 'forum-manager', CoreGlobal::TYPE_SYSTEM );

        // Permissions

        $columns = [ 'createdBy', 'modifiedBy', 'name', 'slug', 'type', 'icon', 'description', 'createdAt', 'modifiedAt' ];

        $permissions = [
                [ $this->master->id, $this->master->id, 'Forum', 'forum', CoreGlobal::TYPE_SYSTEM, NULL, 'The permission forum is to manage forum module from admin.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
        ];

        $this->batchInsert( $this->prefix . 'core_permission', $columns, $permissions );

        $adminPerm			= Permission::findBySlugType( 'admin', CoreGlobal::TYPE_SYSTEM );        
        $forumPerm                      = Permission::findBySlugType( 'forum', CoreGlobal::TYPE_SYSTEM );

        // RBAC Mapping

        $columns = [ 'roleId', 'permissionId' ];

        $mappings = [
                [ $forumManagerRole->id, $adminPerm->id ], [ $forumManagerRole->id, $forumPerm->id ]
        ];

        $this->batchInsert( $this->prefix . 'core_role_permission', $columns, $mappings );
    }
}
