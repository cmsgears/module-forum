<?php
namespace cmsgears\forum\common\services\entities;

// CMG Imports
use cmsgears\forum\common\config\ForumGlobal;

use cmsgears\forum\common\models\base\ForumTables;

use cmsgears\forum\common\services\interfaces\entities\ITopicService;

use cmsgears\core\common\services\traits\NameTrait;
use cmsgears\core\common\services\traits\SlugTrait;
use cmsgears\core\common\services\traits\ApprovalTrait;
use cmsgears\core\common\services\traits\DataTrait;

class TopicService extends \cmsgears\core\common\services\base\EntityService implements ITopicService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\forum\common\models\entities\Topic';

	public static $modelTable	= ForumTables::TABLE_TOPIC;

	public static $parentType	= ForumGlobal::TYPE_FORUM;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

    use ApprovalTrait;
	use DataTrait;
	use NameTrait;
	use SlugTrait;

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// CityService ---------------------------

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

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// CityService ---------------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------
    
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

	// Delete -------------

}
