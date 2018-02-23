<?php

use yii\db\Migration;

/**
 * Class m180222_103224_forum_data
 */
class m180222_103224_forum_data extends Migration
{
    // Public Variables

    // Private Variables

    private $prefix;

    // Entities

    private $site;
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
    }
}
