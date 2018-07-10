<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\forum\common\components;

// Yii Imports
use Yii;
use yii\base\Component;

/**
 * Forum component register the services provided by Forum Module.
 *
 * @since 1.0.0
 */
class Forum extends Component {

	// Global -----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	/**
	 * Initialize the services.
	 */
	public function init() {

		parent::init();

		// Register components and objects
		$this->registerComponents();
	}

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG parent classes --------------------

	// Forum ---------------------------------

	// Properties ----------------

	// Components and Objects ----

	/**
	 * Register the services.
	 */
	public function registerComponents() {

		// Init system services
		//$this->initSystemServices();

		// Register services
		$this->registerResourceServices();
		$this->registerEntityServices();

		// Init services
		$this->initResourceServices();
		$this->initEntityServices();
	}

	/**
	 * Registers resource services.
	 */
	public function registerResourceServices() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'cmsgears\forum\common\services\interfaces\resources\ITopicMetaService', 'cmsgears\forum\common\services\resources\TopicMetaService' );
		$factory->set( 'cmsgears\forum\common\services\interfaces\resources\ITopicReplyService', 'cmsgears\forum\common\services\resources\TopicReplyService' );
	}

	/**
	 * Registers entity services.
	 */
	public function registerEntityServices() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'cmsgears\forum\common\services\interfaces\entities\ITopicService', 'cmsgears\forum\common\services\entities\TopicService' );
	}

	/**
	 * Initialize resource services.
	 */
	public function initResourceServices() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'topicMetaService', 'cmsgears\forum\common\services\resources\TopicMetaService' );
		$factory->set( 'topicReplyService', 'cmsgears\forum\common\services\resources\TopicReplyService' );
	}

	/**
	 * Initialize entity services.
	 */
	public function initEntityServices() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'topicService', 'cmsgears\forum\common\services\entities\TopicService' );
	}

}
