<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\forum\common\models\resources;

// Yii Imports
use Yii;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\forum\common\config\ForumGlobal;

use cmsgears\core\common\models\interfaces\base\IAuthor;
use cmsgears\core\common\models\interfaces\base\IFeatured;
use cmsgears\core\common\models\interfaces\base\IOwner;
use cmsgears\core\common\models\interfaces\resources\IContent;
use cmsgears\core\common\models\interfaces\resources\IData;
use cmsgears\core\common\models\interfaces\resources\IGridCache;
use cmsgears\core\common\models\interfaces\resources\IVisual;
use cmsgears\core\common\models\interfaces\mappers\IFile;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\entities\User;
use cmsgears\forum\common\models\base\ForumTables;
use cmsgears\forum\common\models\entities\Forum;
use cmsgears\forum\common\models\entities\Topic;

use cmsgears\core\common\models\traits\base\AuthorTrait;
use cmsgears\core\common\models\traits\base\FeaturedTrait;
use cmsgears\core\common\models\traits\base\OwnerTrait;
use cmsgears\core\common\models\traits\resources\ContentTrait;
use cmsgears\core\common\models\traits\resources\DataTrait;
use cmsgears\core\common\models\traits\resources\GridCacheTrait;
use cmsgears\core\common\models\traits\resources\VisualTrait;
use cmsgears\core\common\models\traits\mappers\FileTrait;

use cmsgears\core\common\behaviors\AuthorBehavior;

/**
 * The topic reply model stores the replies of topics.
 *
 * @property integer $id
 * @property integer $forumId
 * @property integer $topicId
 * @property integer $userId
 * @property integer $baseId
 * @property integer $avatarId
 * @property integer $bannerId
 * @property integer $videoId
 * @property integer $createdBy
 * @property integer $modifiedBy
 * @property string $title
 * @property string $name
 * @property string $email
 * @property string $mobile
 * @property string $phone
 * @property string $avatarUrl
 * @property string $websiteUrl
 * @property string $ip
 * @property integer $ipNum
 * @property string $agent
 * @property integer $status
 * @property integer $score
 * @property boolean $pinned
 * @property boolean $featured
 * @property boolean $popular
 * @property boolean $anonymous
 * @property string $field1
 * @property string $field2
 * @property string $field3
 * @property string $field4
 * @property string $field5
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 * @property datetime $approvedAt
 * @property string $content
 * @property string $data
 * @property string $gridCache
 * @property boolean $gridCacheValid
 * @property datetime $gridCachedAt
 *
 * @since 1.0.0
 */
class TopicReply extends \cmsgears\core\common\models\base\Resource implements IAuthor,
	IContent, IData, IFeatured, IFile, IGridCache, IOwner, IVisual {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	const STATUS_NEW		=  500;
	const STATUS_SPAM		=  600;
	const STATUS_BLOCKED	=  700;
	const STATUS_APPROVED	=  800;
	const STATUS_TRASH		=  900;

	// Public -----------------

	public static $statusMap = [
		self::STATUS_NEW => 'New',
		self::STATUS_SPAM => 'Spam',
		self::STATUS_BLOCKED => 'Blocked',
		self::STATUS_APPROVED => 'Approved',
		self::STATUS_TRASH => 'Trash'
	];

	// Used for external docs
	public static $revStatusMap = [
		'New' => self::STATUS_NEW,
		'Spam' => self::STATUS_SPAM,
		'Blocked' => self::STATUS_BLOCKED,
		'Approved' => self::STATUS_APPROVED,
		'Trash' => self::STATUS_TRASH
	];

	// Used for url params
	public static $urlRevStatusMap = [
		'new' => self::STATUS_NEW,
		'spam' => self::STATUS_SPAM,
		'blocked' => self::STATUS_BLOCKED,
		'approved' => self::STATUS_APPROVED,
		'trash' => self::STATUS_TRASH
	];

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $modelType = ForumGlobal::TYPE_TOPIC_REPLY;

	// Private ----------------

	// Traits ------------------------------------------------------

	use AuthorTrait;
	use ContentTrait;
	use DataTrait;
	use FeaturedTrait;
	use FileTrait;
	use GridCacheTrait;
	use OwnerTrait;
	use VisualTrait;

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	/**
	 * @inheritdoc
	 */
	public function behaviors() {

		return [
			'authorBehavior' => [
				'class' => AuthorBehavior::class
			],
			'timestampBehavior' => [
				'class' => TimestampBehavior::class,
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

		// Model Rules
		$rules = [
			// Required, Safe
			[ [ 'forumId', 'topicId', 'content' ], 'required' ],
			[ [ 'id', 'content' ], 'safe' ],
			// Email
			[ 'email', 'email' ],
			// Text Limit
			[ [ 'phone', 'mobile' ], 'string', 'min' => 1, 'max' => Yii::$app->core->smallText ],
			[ 'ip', 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			[ [ 'field1', 'field2', 'field3', 'field4', 'field5' ], 'string', 'min' => 1, 'max' => Yii::$app->core->largeText ],
			[ [ 'name', 'email', 'agent' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xxLargeText ],
			[ [ 'title', 'avatarUrl', 'websiteUrl' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xxxLargeText ],
			// Other
			[ [ 'avatarUrl', 'websiteUrl' ], 'url' ],
			[ [ 'pinned', 'featured', 'popular', 'anonymous', 'gridCacheValid' ], 'boolean' ],
			[ [ 'ipNum', 'status', 'rating', 'order' ], 'number', 'integerOnly' => true, 'min' => 0 ],
			[ [ 'forumId', 'topicId', 'userId', 'baseId', 'avatarId', 'bannerId', 'videoId', 'createdBy', 'modifiedBy' ], 'number', 'integerOnly' => true, 'min' => 1 ],
			[ [ 'createdAt', 'modifiedAt', 'approvedAt', 'gridCachedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
		];

		// Trim Text
		if( Yii::$app->core->trimFieldValue ) {

			$trim[] = [ [ 'title', 'name', 'email', 'phone', 'mobile', 'avatarUrl', 'websiteUrl' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

			return ArrayHelper::merge( $trim, $rules );
		}

		return $rules;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {

		return [
			'forumId' => Yii::$app->forumMessage->getMessage( ForumGlobal::FIELD_FORUM ),
			'topicId' => Yii::$app->forumMessage->getMessage( ForumGlobal::FIELD_TOPIC ),
			'userId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_USER ),
			'baseId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
			'avatarId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_AVATAR ),
			'bannerId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_BANNER ),
			'videoId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_VIDEO ),
			'title' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TITLE ),
			'name' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'email' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_EMAIL ),
			'phone' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PHONE ),
			'mobile' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_MOBILE ),
			'avatarUrl' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_AVATAR_URL ),
			'websiteUrl' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_WEBSITE ),
			'ip' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_IP ),
			'ipNum' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_IP_NUM ),
			'agent' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_AGENT_BROWSER ),
			'status' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_STATUS ),
			'score' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_RATING ),
			'pinned' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PINNED ),
			'featured' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_FEATURED ),
			'popular' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_POPULAR ),
			'anonymous' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ANONYMOUS ),
			'content' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_MESSAGE ),
			'data' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DATA ),
			'gridCache' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_GRID_CACHE )
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// TopicReply ----------------------------

	/**
	 * Returns the corresponding user.
	 *
	 * @return User
	 */
	public function getUser() {

		$userTable = CoreTables::TABLE_USER;

		return $this->hasOne( User::class, [ 'id' => 'userId' ] )->from( "$userTable as user" );
	}

	public function getForum() {

		return $this->hasOne( Forum::class, [ 'id' => 'forumId' ] );
	}

	public function getTopic() {

		return $this->hasOne( Topic::class, [ 'id' => 'topicId' ] );
	}

	/**
	 * Return the immediate parent reply.
	 *
	 * @return TopicReply
	 */
	public function getBase() {

		return $this->hasOne( TopicReply::class, [ 'id' => 'baseId' ] );
	}

	/**
	 * Return all the immediate child replies.
	 *
	 * @return TopicReply
	 */
	public function getChildren() {

		return $this->hasMany( TopicReply::class, [ 'baseId' => 'id' ] );
	}

	/**
	 * Returns string representation of status.
	 *
	 * @return string
	 */
	public function getStatusStr() {

		return self::$statusMap[ $this->status ];
	}

	public function isNew() {

		return $this->status == self::STATUS_NEW;
	}

	public function isSpam() {

		return $this->status == self::STATUS_SPAM;
	}

	public function isBlocked() {

		return $this->status == self::STATUS_BLOCKED;
	}

	public function isApproved() {

		return $this->status == self::STATUS_APPROVED;
	}

	public function isTrash() {

		return $this->status == self::STATUS_TRASH;
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return ForumTables::getTableName( ForumTables::TABLE_TOPIC_REPLY );
	}

	// CMG parent classes --------------------

	// TopicReply ----------------------------

	// Read - Query -----------

	/**
	 * @inheritdoc
	 */
	public static function queryWithHasOne( $config = [] ) {

		$relations = isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'forum', 'topic', 'user' ];

		$config[ 'relations' ] = $relations;

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the child replies.
	 *
	 * @param integer $topicId
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query child comments.
	 */
	public static function queryByBaseId( $topicId, $config = [] ) {

		$status	= isset( $config[ 'status' ] ) ? $config[ 'status' ] : self::STATUS_APPROVED;

		return self::find()->where( [ 'topicId' => $topicId, 'status' => $status ] );
	}

	/**
	 * Return query to find the replies by email.
	 *
	 * @param integer $topicId
	 * @param string $email
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query by email.
	 */
	public static function queryByTopicIdEmail( $topicId, $email, $config = [] ) {

		return self::find()->where( [ 'topicId' => $topicId, 'email' => $email ] );
	}

	/**
	 * Return query to find top level approved comments.
	 *
	 * @param integer $topicId
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query by email.
	 */
	public static function queryL0Approved( $topicId, $config = [] ) {

		$status	= isset( $config[ 'status' ] ) ? $config[ 'status' ] : self::STATUS_APPROVED;

		return self::find()->where( [ 'topicId' => $topicId, 'status' => $status, 'baseId' => null ] );
	}

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
