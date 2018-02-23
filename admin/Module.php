<?php
namespace cmsgears\forum\admin;

// Yii Imports
use \Yii;

class Module extends \cmsgears\core\common\base\Module {

    // Variables ---------------------------------------------------

    // Globals ----------------

    // Public -----------------

    public $controllerNamespace = 'cmsgears\forum\admin\controllers';

    // Protected --------------

    // Private ----------------

    // Traits ------------------------------------------------------

    // Constructor and Initialisation ------------------------------

    public function init() {

        parent::init();

        $this->setViewPath( '@cmsgears/module-forum/admin/views' );
    }

    // Instance methods --------------------------------------------

    // Yii interfaces ------------------------

    // Yii parent classes --------------------

    // CMG interfaces ------------------------

    // CMG parent classes --------------------

    // Module --------------------------------

    public function getSidebarHtml() {

        $path	= Yii::getAlias( '@cmsgears' ) . '/module-forum/admin/views/sidebar.php';

        return $path;
    }
}
