<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\forum\common\services\entities;

// CMG Imports
use cmsgears\forum\common\config\ForumGlobal;

use cmsgears\forum\common\models\base\ForumTables;

use cmsgears\forum\common\services\interfaces\entities\ITopicService;

use cmsgears\core\common\services\base\EntityService;

use cmsgears\core\common\services\traits\base\ApprovalTrait;
use cmsgears\core\common\services\traits\base\NameTypeTrait;
use cmsgears\core\common\services\traits\base\SlugTypeTrait;
use cmsgears\core\common\services\traits\resources\DataTrait;

/**
 * TopicService provide service methods of topic model.
 *
 * @since 1.0.0
 */
class TopicService extends EntityService implements ITopicService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\forum\common\models\entities\Topic';

	public static $modelTable	= ForumTables::TABLE_TOPIC;

	public static $typed		= true;

	public static $parentType	= ForumGlobal::TYPE_FORUM;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

    use ApprovalTrait;
	use DataTrait;
	use NameTypeTrait;
	use SlugTypeTrait;

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// TopicService --------------------------

	// Data Provider ------

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

    public function update( $model, $config = [] ) {

		return parent::update( $model, [
			'attributes' => [ 'name', 'description', 'status', 'title', 'content', 'data' ]
		]);
	}

	// Delete -------------

	// Bulk ---------------

    protected function applyBulk( $model, $column, $action, $target, $config = [] ) {

		switch( $column ) {

            case 'status': {

				switch( $action ) {

					case 'active': {

						$this->approve( $model );

						// TODO: Trigger activate email

						break;
					}
					case 'block': {

						$this->block( $model );

						// TODO: Trigger block email

						break;
					}
				}

				break;
			}
			case 'model': {

				switch( $action ) {

					case 'delete': {

						$this->delete( $model );

						Yii::$app->factory->get( 'activityService' )->deleteActivity( $model, self::$parentType );

						break;
					}
				}

				break;
			}
		}
	}

	// Notifications ------

	// Cache --------------

	// Additional ---------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// TopicService --------------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------

}
