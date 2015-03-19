<?php
namespace cmsgears\modules\forum\admin;

// Yii Imports
use \Yii;

class Module extends \yii\base\Module {

    public $controllerNamespace = 'cmsgears\modules\forum\admin\controllers';

    public function init() {

        parent::init();

        $this->setViewPath( '@cmsgears/modules/forum/admin/views' );
    }
}

?>