<?php
/**
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 * @license https://www.cmsgears.org/license/
 * @package module
 * @subpackage forum
 */
namespace cmsgears\forum\common\models\entities;

// Yii Imports
use Yii;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\forum\common\config\ForumGlobal;

use cmsgears\forum\common\models\base\ForumTables;

use cmsgears\core\common\models\traits\CreateModifyTrait;
use cmsgears\core\common\models\traits\NameTrait;
use cmsgears\core\common\models\traits\SlugTrait;
use cmsgears\core\common\models\traits\resources\DataTrait;
use cmsgears\core\common\models\traits\interfaces\ApprovalTrait;
use cmsgears\core\common\models\traits\mappers\CategoryTrait;
use cmsgears\core\common\models\traits\mappers\TagTrait;

use cmsgears\core\common\behaviors\AuthorBehavior;

use cmsgears\core\common\models\interfaces\IApproval;

/**
 * Topic Entity
 *
 * @property long $id
 * @property long $siteId
 * @property long $createdBy
 * @property long $modifiedBy
 * @property short $status
 * @property string $name
 * @property string $slug
 * @property string $title
 * @property string $description
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 * @property string $content
 * @property string $data
 */

class Topic extends \cmsgears\core\common\models\base\Entity {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------
    
    public $modelType		= ForumGlobal::TYPE_FORUM;

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------
    
    use CreateModifyTrait;
    use ApprovalTrait;
	use DataTrait;
	use NameTrait;
	use SlugTrait;
    use CategoryTrait;
    use TagTrait;

	// Constructor and Initialisation ------------------------------
    
    public function init() {
        
        parent::init();
        
        static::$statusMap = [		
            IApproval::STATUS_ACTIVE => 'Active',
            IApproval::STATUS_BLOCKED => 'Blocked',
        ];
    }

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----
    
    /**
	 * @inheritdoc
	 */
	public function behaviors() {

		return [
			AuthorBehavior::className(),
			'sluggableBehavior' => [
				'class' => SluggableBehavior::className(),
				'attribute' => 'name',
				'slugAttribute' => 'slug',
				'immutable' => true,
				'ensureUnique' => true
			],
			'timestampBehavior' => [
				'class' => TimestampBehavior::className(),
				'createdAtAttribute' => 'createdAt',
				'updatedAtAttribute' => 'modifiedAt',
				'value' => new Expression('NOW()')
			]
		];
	}

	// yii\base\Model ---------
    
    /**
	 * @inheritdoc
	 */
	public function rules() {

		// model rules
		$rules = [
			// Required, Safe
			[ [ 'name' ], 'required' ],
			[ [ 'id', 'content', 'data' ], 'safe' ],			
			// Text Limit			
			[ [ 'name' ], 'string', 'min' => 1, 'max' => Yii::$app->core->largeText ],
			[ 'slug', 'string', 'min' => 1, 'max' => Yii::$app->core->xLargeText ],
            [ 'title', 'string', 'min' => 1, 'max' => Yii::$app->core->xxxLargeText ],
			[ [ 'description' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xxLargeText ],
			// Other
            [ [ 'id', 'status' ], 'number', 'integerOnly' => true ],
			[ [ 'createdBy', 'modifiedBy', 'siteId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
			[ [ 'createdAt', 'modifiedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
		];

		// trim if required
		if( Yii::$app->core->trimFieldValue ) {

			$trim[] = [ [ 'name', 'description', 'title', 'content' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

			return ArrayHelper::merge( $trim, $rules );
		}

		return $rules;
	}
    
    /**
	 * @inheritdoc
	 */
	public function attributeLabels() {

		return [						
			'name' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_NAME ),
            'title' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TITLE ),
			'slug' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_SLUG ),
			'description' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DESCRIPTION ),
            'content' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CONTENT ),
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// Topic ---------------------------------

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----
    
    public static function tableName() {

		return ForumTables::TABLE_TOPIC;
	}

	// CMG parent classes --------------------

	// Topic ---------------------------------

	// Read - Query -----------

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
