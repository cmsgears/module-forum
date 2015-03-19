<?php
namespace cmsgears\modules\forum\frontend;

use \Yii;

class Module extends \yii\base\Module {

    public $controllerNamespace = 'cmsgears\modules\forum\frontend\controllers';

    public function init() {

        parent::init();

        $this->setViewPath( '@cmsgears/modules/forum/frontend/views' );
    }
}