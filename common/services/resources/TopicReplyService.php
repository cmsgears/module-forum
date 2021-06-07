<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\forum\common\services\resources;

// Yii Imports
use Yii;
use yii\data\Sort;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\forum\common\config\ForumGlobal;

use cmsgears\forum\common\models\resources\TopicReply;

use cmsgears\forum\common\services\interfaces\resources\ITopicReplyService;

use cmsgears\core\common\services\traits\resources\DataTrait;
use cmsgears\core\common\services\traits\mappers\FileTrait;

use cmsgears\core\common\utilities\DateUtil;

/**
 * TopicReplyService provide service methods of topic reply.
 *
 * @since 1.0.0
 */
class TopicReplyService extends \cmsgears\core\common\services\base\ResourceService implements ITopicReplyService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass = '\cmsgears\forum\common\models\resources\TopicReply';

	public static $parentType = ForumGlobal::TYPE_TOPIC_REPLY;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $fileService;
	protected $modelFileService;

	// Private ----------------

	// Traits ------------------------------------------------------

	use DataTrait;
	use FileTrait;

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->fileService		= Yii::$app->factory->get( 'fileService' );
		$this->modelFileService	= Yii::$app->factory->get( 'modelFileService' );
	}

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// TopicReplyService ---------------------

	// Data Provider ------

	public function getPage( $config = [] ) {


		$searchParam	= $config[ 'search-param' ] ?? 'keywords';
		$searchColParam	= $config[ 'search-col-param' ] ?? 'search';

		$defaultSort = isset( $config[ 'defaultSort' ] ) ? $config[ 'defaultSort' ] : [ 'id' => SORT_DESC ];

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$forumTable = Yii::$app->factory->get( 'forumService' )->getModelTable();
		$topicTable = Yii::$app->factory->get( 'topicService' )->getModelTable();

		// Sorting ----------

		$sort = new Sort([
			'attributes' => [
				'id' => [
					'asc' => [ "$modelTable.id" => SORT_ASC ],
					'desc' => [ "$modelTable.id" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Id'
				],
				'forum' => [
					'asc' => [ "$forumTable.name" => SORT_ASC ],
					'desc' => [ "$forumTable.name" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Forum'
				],
				'topic' => [
					'asc' => [ "$forumTable.topic" => SORT_ASC ],
					'desc' => [ "$forumTable.topic" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Topic'
				],
	            'user' => [
					'asc' => [ "user.name" => SORT_ASC ],
					'desc' => [ "user.name" => SORT_DESC ],
					'default' => SORT_DESC,
	                'label' => 'User'
	            ],
				'title' => [
					'asc' => [ "$modelTable.title" => SORT_ASC ],
					'desc' => [ "$modelTable.title" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Title'
				],
				'name' => [
					'asc' => [ "$modelTable.name" => SORT_ASC ],
					'desc' => [ "$modelTable.name" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Name'
				],
				'email' => [
					'asc' => [ "$modelTable.email" => SORT_ASC ],
					'desc' => [ "$modelTable.email" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Email'
				],
				'status' => [
					'asc' => [ "$modelTable.status" => SORT_ASC ],
					'desc' => [ "$modelTable.status" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Status'
				],
				'score' => [
					'asc' => [ "$modelTable.score" => SORT_ASC ],
					'desc' => [ "$modelTable.score" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Score'
				],
				'pinned' => [
					'asc' => [ "$modelTable.pinned" => SORT_ASC ],
					'desc' => [ "$modelTable.pinned" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Pinned'
				],
				'featured' => [
					'asc' => [ "$modelTable.featured" => SORT_ASC ],
					'desc' => [ "$modelTable.featured" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Featured'
				],
				'popular' => [
					'asc' => [ "$modelTable.popular" => SORT_ASC ],
					'desc' => [ "$modelTable.popular" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Popular'
				],
				'anonymous' => [
					'asc' => [ "$modelTable.anonymous" => SORT_ASC ],
					'desc' => [ "$modelTable.anonymous" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Anonymous'
				],
	            'cdate' => [
	                'asc' => [ "$modelTable.createdAt" => SORT_ASC ],
	                'desc' => [ "$modelTable.createdAt" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Created At'
	            ],
	            'udate' => [
	                'asc' => [ "$modelTable.modifiedAt" => SORT_ASC ],
	                'desc' => [ "$modelTable.modifiedAt" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Updated At'
	            ],
	            'adate' => [
	                'asc' => [ "$modelTable.approvedAt" => SORT_ASC ],
	                'desc' => [ "$modelTable.approvedAt" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Approved At'
	            ]
			],
			'defaultOrder' => [
				'id' => SORT_DESC
			]
		]);

		if( !isset( $config[ 'sort' ] ) ) {

			$config[ 'sort' ] = $sort;
		}

		// Query ------------

		if( !isset( $config[ 'query' ] ) ) {

			$config[ 'hasOne' ] = true;
		}

		// Filters ----------

		// Params
		$status	= Yii::$app->request->getQueryParam( 'status' );
		$filter	= Yii::$app->request->getQueryParam( 'model' );

		// Filter - Status
		if( isset( $status ) && isset( $modelClass::$urlRevStatusMap[ $status ] ) ) {

			$config[ 'conditions' ][ "$modelTable.status" ]	= $modelClass::$urlRevStatusMap[ $status ];
		}

		// Filter - Model
		if( isset( $filter ) ) {

			switch( $filter ) {

				case 'pinned': {

					$config[ 'conditions' ][ "$modelTable.pinned" ] = true;

					break;
				}
				case 'featured': {

					$config[ 'conditions' ][ "$modelTable.featured" ] = true;

					break;
				}
				case 'popular': {

					$config[ 'conditions' ][ "$modelTable.popular" ] = true;

					break;
				}
				case 'anonymous': {

					$config[ 'conditions' ][ "$modelTable.anonymous" ] = true;

					break;
				}
			}
		}

		// Searching --------

		$searchCol	= Yii::$app->request->getQueryParam( 'search' );

		if( isset( $searchCol ) ) {

			$search = [
				'user' => "user.name",
				'title' => "$modelTable.title",
				'name' => "$modelTable.name",
				'email' =>  "$modelTable.email",
				'content' => "$modelTable.content"
			];

			$config[ 'search-col' ] = $search[ $searchCol ];
		}

		// Reporting --------

		$config[ 'report-col' ]	= [
			'user' => "user.name",
			'title' => "$modelTable.title",
			'name' => "$modelTable.name",
			'email' => "$modelTable.email",
			'content' => "$modelTable.content",
			'status' => "$modelTable.status",
			'featured' => "$modelTable.featured"
		];

		// Result -----------

		return parent::findPage( $config );
	}

	public function getPageByTopicId( $topicId, $config = [] ) {

		$topLevel = isset( $config[ 'topLevel' ] ) ? $config[ 'topLevel' ] : true;

		if( $topLevel ) {

			$config[ 'conditions' ][ 'baseId' ] = null;
		}

		$config[ 'conditions' ][ 'topicId' ] = $topicId;

		return $this->getPage( $config );
	}

	public function getPageByBaseId( $baseId, $config = [] ) {

		$config[ 'conditions' ][ 'baseId' ] = $baseId;

		return $this->getPage( $config );
	}

	public function getPageForApproved( $topicId, $config = [] ) {

		$modelTable	= $this->getModelTable();

		$config[ 'conditions' ][ "$modelTable.status" ]	= TopicReply::STATUS_APPROVED;

		return $this->getPageByTopicId( $topicId, $config );
	}

	// Read ---------------

	// Read - Models ---

	/**
	 * It returns immediate child replies for given base id.
	 */
	public function getByBaseId( $baseId, $config = [] ) {

		$modelClass	= self::$modelClass;

		return $modelClass::queryByBaseId( $baseId, $config )->all();
	}

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function create( $model, $config = [] ) {

		$avatar = isset( $config[ 'avatar' ] ) ? $config[ 'avatar' ] : null;
		$banner = isset( $config[ 'banner' ] ) ? $config[ 'banner' ] : null;
		$video	= isset( $config[ 'video' ] ) ? $config[ 'video' ] : null;

		// Save Files
		$this->fileService->saveFiles( $model, [ 'avatarId' => $avatar, 'bannerId' => $banner, 'videoId' => $video ] );

		$model->agent	= Yii::$app->request->userAgent;
		$model->ip		= Yii::$app->request->userIP;

		// Default New
		$model->status = $model->status ?? TopicReply::STATUS_NEW;

		return parent::create( $model, $config );
	}

	// Update -------------

	public function update( $model, $config = [] ) {

		$admin = isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : false;

		$avatar = isset( $config[ 'avatar' ] ) ? $config[ 'avatar' ] : null;
		$banner = isset( $config[ 'banner' ] ) ? $config[ 'banner' ] : null;
		$video	= isset( $config[ 'video' ] ) ? $config[ 'video' ] : null;

		$attributes = isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [
			'avatarId', 'bannerId', 'videoId', 'name', 'email',
			'avatarUrl', 'websiteUrl', 'content',
			'field1', 'field2', 'field3', 'field4', 'field5'
		];

		if( $admin ) {

			$attributes	= ArrayHelper::merge( $attributes, [
				'status', 'order', 'pinned', 'featured', 'popular', 'anonymous', 'score'
			]);
		}

		// Save Files
		$this->fileService->saveFiles( $model, [ 'avatarId' => $avatar, 'bannerId' => $banner, 'videoId' => $video ] );

		return parent::update( $model, [
			'attributes' => $attributes
		]);
	}

	// States -----

	public function updateStatus( $model, $status ) {

		$model->status = $status;

		$model->update();

		return $model;
	}

	public function approve( $model ) {

		$model->approvedAt = DateUtil::getDateTime();

		return $this->updateStatus( $model, TopicReply::STATUS_APPROVED );
	}

	public function block( $model ) {

		return $this->updateStatus( $model, TopicReply::STATUS_BLOCKED );
	}

	public function markSpam( $model ) {

		return $this->updateStatus( $model, TopicReply::STATUS_SPAM );
	}

	public function markTrash( $model ) {

		return $this->updateStatus( $model, TopicReply::STATUS_TRASH );
	}

	// Requests ---

	public function spamRequest( $model, $parent, $config = [] ) {

		$parentType		= $config[ 'parentType' ];
		$notify			= isset( $config[ 'notify' ] ) ? $config[ 'notify' ] : true;
		$commentType	= isset( $config[ 'commentType' ] ) ? $config[ 'commentType' ] : ModelComment::TYPE_COMMENT;
		$adminLink		= isset( $config[ 'adminLink' ] ) ? $config[ 'adminLink' ] : null;

		if( $notify ) {

			$this->notifyAdmin( $model, [
				'template' => CoreGlobal::TPL_COMMENT_REQUEST_SPAM,
				'adminLink' => $adminLink,
				'data' => [ 'parent' => $parent, 'parentType' => $parentType, 'commentType' => $commentType ]
			]);
		}
	}

	public function approveRequest( $model, $parent, $config = [] ) {

		$parentType		= $config[ 'parentType' ];
		$notify			= isset( $config[ 'notify' ] ) ? $config[ 'notify' ] : true;
		$commentType	= isset( $config[ 'commentType' ] ) ? $config[ 'commentType' ] : ModelComment::TYPE_COMMENT;
		$adminLink		= isset( $config[ 'adminLink' ] ) ? $config[ 'adminLink' ] : null;

		if( $notify ) {

			$this->notifyAdmin( $model, [
				'template' => CoreGlobal::TPL_COMMENT_REQUEST_APPROVE,
				'adminLink' => $adminLink,
				'data' => [ 'parent' => $parent, 'parentType' => $parentType, 'commentType' => $commentType ]
			]);
		}
	}

	public function deleteRequest( $model, $parent, $config = [] ) {

		$parentType		= $config[ 'parentType' ];
		$notify			= isset( $config[ 'notify' ] ) ? $config[ 'notify' ] : true;
		$commentType	= isset( $config[ 'commentType' ] ) ? $config[ 'commentType' ] : ModelComment::TYPE_COMMENT;
		$adminLink		= isset( $config[ 'adminLink' ] ) ? $config[ 'adminLink' ] : null;

		if( $notify ) {

			$this->notifyAdmin( $model, [
				'template' => CoreGlobal::TPL_COMMENT_REQUEST_DELETE,
				'adminLink' => $adminLink,
				'data' => [ 'parent' => $parent, 'parentType' => $parentType, 'commentType' => $commentType ]
			]);
		}
	}

	// Delete -------------

	public function delete( $model, $config = [] ) {

		// Delete Files
		$this->fileService->deleteMultiple( $model->files );

		// Delete model
		return parent::delete( $model, $config );
	}

	// Bulk ---------------

	protected function applyBulk( $model, $column, $action, $target, $config = [] ) {

		switch( $column ) {

			case 'status': {

				switch( $action ) {

					case 'approve': {

						$this->approve( $model );

						break;
					}
					case 'spam': {

						$this->markSpam( $model );

						break;
					}
					case 'trash': {

						$this->markTrash( $model );

						break;
					}
					case 'block': {

						$this->block( $model );

						break;
					}
				}

				break;
			}
			case 'model': {

				switch( $action ) {

					case 'pinned': {

						$model->pinned = true;

						$model->update();

						break;
					}
					case 'featured': {

						$model->featured = true;

						$model->update();

						break;
					}
					case 'popular': {

						$model->popular = true;

						$model->update();

						break;
					}
					case 'delete': {

						$this->delete( $model );

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

	// TopicReplyService ---------------------

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
