<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\forum\common\services\entities;

// Yii Imports
use Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\forum\common\config\ForumGlobal;

use cmsgears\forum\common\models\resources\TopicMeta;

use cmsgears\forum\common\services\interfaces\entities\ITopicService;
use cmsgears\forum\common\services\interfaces\resources\ITopicMetaService;
use cmsgears\forum\common\services\interfaces\mappers\ITopicFollowerService;

use cmsgears\core\common\services\interfaces\resources\IFileService;

use cmsgears\core\common\services\traits\base\SimilarTrait;
use cmsgears\core\common\services\traits\mappers\CategoryTrait;
use cmsgears\core\common\services\traits\resources\MetaTrait;

/**
 * TopicService provide service methods of branch model.
 *
 * @since 1.0.0
 */
class TopicService extends \cmsgears\cms\common\services\base\ContentService implements ITopicService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass = '\cmsgears\forum\common\models\entities\Topic';

	public static $parentType = ForumGlobal::TYPE_TOPIC;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $fileService;
	protected $metaService;
	protected $followerService;

	// Private ----------------

	// Traits ------------------------------------------------------

	use CategoryTrait;
	use MetaTrait;
	use SimilarTrait;

	// Constructor and Initialisation ------------------------------

	public function __construct( IFileService $fileService, ITopicMetaService $metaService, ITopicFollowerService $followerService, $config = [] ) {

		$this->fileService	= $fileService;
		$this->metaService	= $metaService;

		$this->followerService = $followerService;

		parent::__construct( $config );
	}

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// TopicService --------------------------

	// Data Provider ------

	public function getPage( $config = [] ) {

		$searchParam	= $config[ 'search-param' ] ?? 'keywords';
		$searchColParam	= $config[ 'search-col-param' ] ?? 'search';

		$defaultSort = isset( $config[ 'defaultSort' ] ) ? $config[ 'defaultSort' ] : [ 'id' => SORT_DESC ];

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$contentTable	= Yii::$app->factory->get( 'modelContentService' )->getModelTable();
		$templateTable	= Yii::$app->factory->get( 'templateService' )->getModelTable();

		$forumTable = Yii::$app->factory->get( 'forumService' )->getModelTable();

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
				'template' => [
					'asc' => [ "$templateTable.name" => SORT_ASC ],
					'desc' => [ "$templateTable.name" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Template',
				],
				'name' => [
					'asc' => [ "$modelTable.name" => SORT_ASC ],
					'desc' => [ "$modelTable.name" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Name'
				],
				'slug' => [
					'asc' => [ "$modelTable.slug" => SORT_ASC ],
					'desc' => [ "$modelTable.slug" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Slug'
				],
	            'type' => [
	                'asc' => [ "$modelTable.type" => SORT_ASC ],
	                'desc' => [ "$modelTable.type" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Type'
	            ],
	            'icon' => [
	                'asc' => [ "$modelTable.icon" => SORT_ASC ],
	                'desc' => [ "$modelTable.icon" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Icon'
	            ],
				'title' => [
					'asc' => [ "$modelTable.title" => SORT_ASC ],
					'desc' => [ "$modelTable.title" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Title'
				],
				'status' => [
					'asc' => [ "$modelTable.status" => SORT_ASC ],
					'desc' => [ "$modelTable.status" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Status'
				],
				'visibility' => [
					'asc' => [ "$modelTable.visibility" => SORT_ASC ],
					'desc' => [ "$modelTable.visibility" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Visibility'
				],
				'order' => [
					'asc' => [ "$modelTable.order" => SORT_ASC ],
					'desc' => [ "$modelTable.order" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Order'
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
				'pdate' => [
					'asc' => [ "$contentTable.publishedAt" => SORT_ASC ],
					'desc' => [ "$contentTable.publishedAt" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Published At'
				]
			],
			'defaultOrder' => $defaultSort
		]);

		if( !isset( $config[ 'sort' ] ) ) {

			$config[ 'sort' ] = $sort;
		}

		// Query ------------

		if( !isset( $config[ 'query' ] ) ) {

			$config[ 'query' ] = $modelClass::queryWithHasOne();
		}

		// Filters ----------

		// Searching --------

		// Reporting --------

		// Result -----------

		return parent::getPage( $config );
	}

	public function getPublicPage( $config = [] ) {

		$config[ 'route' ] = isset( $config[ 'route' ] ) ? $config[ 'route' ] : 'topic';

		return parent::getPublicPage( $config );
	}

	public function getPageByForumId( $forumId, $config = [] ) {

		$config[ 'conditions'][ 'forumId' ] = $forumId;

		return $this->getPage( $config );
	}

	// Read ---------------

	public function getByForumId( $forumId ) {

		$modelClass	= self::$modelClass;

		return $modelClass::findByBankId( $forumId );
	}

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	public function getEmail( $model ) {

		return isset( $model->userId ) ? $model->user->email : $model->creator->email;
	}

	// Create -------------

	public function create( $model, $config = [] ) {

		$modelClass = static::$modelClass;

		$avatar = isset( $config[ 'avatar' ] ) ? $config[ 'avatar' ] : null;

		// Save Files
		$this->fileService->saveFiles( $model, [ 'avatarId' => $avatar ] );

		return parent::create( $model, $config );
	}

	public function add( $model, $config = [] ) {

		$admin = isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : false;

		$modelClass = static::$modelClass;
		$parentType	= static::$parentType;

		$content		= isset( $config[ 'content' ] ) ? $config[ 'content' ] : new ModelContent();
		$publish		= isset( $config[ 'publish' ] ) ? $config[ 'publish' ] : false;
		$banner			= isset( $config[ 'banner' ] ) ? $config[ 'banner' ] : null;
		$mbanner		= isset( $config[ 'mbanner' ] ) ? $config[ 'mbanner' ] : null;
		$video			= isset( $config[ 'video' ] ) ? $config[ 'video' ] : null;
		$mvideo			= isset( $config[ 'mvideo' ] ) ? $config[ 'mvideo' ] : null;
		$gallery		= isset( $config[ 'gallery' ] ) ? $config[ 'gallery' ] : null;
		$mappingsType	= isset( $config[ 'mappingsType' ] ) ? $config[ 'mappingsType' ] : static::$parentType;

		$galleryService			= Yii::$app->factory->get( 'galleryService' );
		$modelContentService	= Yii::$app->factory->get( 'modelContentService' );
		$modelCategoryService	= Yii::$app->factory->get( 'modelCategoryService' );
		$modelTagService		= Yii::$app->factory->get( 'modelTagService' );

		$galleryClass = $galleryService->getModelClass();

		$transaction = Yii::$app->db->beginTransaction();

		try {

			// Copy Template
			$config[ 'template' ] = $content->template;

			$this->copyTemplate( $model, $config );

			// Create Model
			$model = $this->create( $model, $config );

			// Create Gallery
			if( isset( $gallery ) ) {

				$gallery->siteId	= Yii::$app->core->siteId;
				$gallery->type		= $parentType;
				$gallery->status	= $galleryClass::STATUS_ACTIVE;
				$gallery->name		= empty( $gallery->name ) ? $model->name : $gallery->name;

				$gallery = $galleryService->create( $gallery );
			}
			else {

				$gallery = $galleryService->createByParams([
					'siteId' => Yii::$app->core->siteId,
					'type' => $parentType, 'status' => $galleryClass::STATUS_ACTIVE,
					'name' => $model->name, 'title' => $model->title
				]);
			}

			// Create and attach model content
			$modelContentService->create( $content, [
				'parent' => $model, 'parentType' => $parentType,
				'publish' => $publish,
				'banner' => $banner, 'mbanner' => $mbanner,
				'video' => $video, 'mvideo' => $mvideo,
				'gallery' => $gallery
			]);

			// Bind categories
			$modelCategoryService->bindModels( $model->id, $mappingsType, [ 'binder' => 'CategoryBinder' ] );

			// Bind tags
			$modelTagService->bindTags( $model->id, $mappingsType, [ 'binder' => 'TagBinder' ] );

			// Default Settings
			$this->metaService->initByNameType( $model->id, CoreGlobal::META_RECEIVE_EMAIL, 'notification', TopicMeta::VALUE_TYPE_FLAG );
			$this->metaService->initByNameType( $model->id, CoreGlobal::META_RECEIVE_EMAIL, 'reminder', TopicMeta::VALUE_TYPE_FLAG );

			$transaction->commit();
		}
		catch( Exception $e ) {

			$transaction->rollBack();

			return false;
		}

		return $model;
	}

	public function register( $model, $config = [] ) {

		$notify	= isset( $config[ 'notify' ] ) ? $config[ 'notify' ] : true;
		$mail	= isset( $config[ 'mail' ] ) ? $config[ 'mail' ] : true;
		$user	= isset( $config[ 'user' ] ) ? $config[ 'user' ] : Yii::$app->core->getUser();

		$modelClass = static::$modelClass;
		$parentType	= static::$parentType;

		$content		= isset( $config[ 'content' ] ) ? $config[ 'content' ] : new ModelContent();
		$publish		= isset( $config[ 'publish' ] ) ? $config[ 'publish' ] : false;
		$banner			= isset( $config[ 'banner' ] ) ? $config[ 'banner' ] : null;
		$mbanner		= isset( $config[ 'mbanner' ] ) ? $config[ 'mbanner' ] : null;
		$video			= isset( $config[ 'video' ] ) ? $config[ 'video' ] : null;
		$mvideo			= isset( $config[ 'mvideo' ] ) ? $config[ 'mvideo' ] : null;
		$gallery		= isset( $config[ 'gallery' ] ) ? $config[ 'gallery' ] : null;
		$mappingsType	= isset( $config[ 'mappingsType' ] ) ? $config[ 'mappingsType' ] : static::$parentType;
		$adminLink		= isset( $config[ 'adminLink' ] ) ? $config[ 'adminLink' ] : 'forum/topic/review';

		$galleryService			= Yii::$app->factory->get( 'galleryService' );
		$modelContentService	= Yii::$app->factory->get( 'modelContentService' );
		$modelCategoryService	= Yii::$app->factory->get( 'modelCategoryService' );
		$modelTagService		= Yii::$app->factory->get( 'modelTagService' );

		$galleryClass = $galleryService->getModelClass();

		$registered	= false;

		$transaction = Yii::$app->db->beginTransaction();

		try {

			// Copy Template
			$config[ 'template' ] = $content->template;

			$this->copyTemplate( $model, $config );

			// Create Model
			$model = $this->create( $model, $config );

			// Create Gallery
			if( isset( $gallery ) ) {

				$gallery->siteId	= Yii::$app->core->siteId;
				$gallery->type		= $parentType;
				$gallery->status	= $galleryClass::STATUS_ACTIVE;
				$gallery->name		= empty( $gallery->name ) ? $model->name : $gallery->name;

				$gallery = $galleryService->create( $gallery );
			}
			else {

				$gallery = $galleryService->createByParams([
					'siteId' => Yii::$app->core->siteId,
					'type' => $parentType, 'status' => $galleryClass::STATUS_ACTIVE,
					'name' => $model->name, 'title' => $model->title
				]);
			}

			// Create and attach model content
			$modelContentService->create( $content, [
				'parent' => $model, 'parentType' => $parentType,
				'publish' => $publish,
				'banner' => $banner, 'mbanner' => $mbanner,
				'video' => $video, 'mvideo' => $mvideo,
				'gallery' => $gallery
			]);

			// Bind categories
			$modelCategoryService->bindModels( $model->id, $mappingsType, [ 'binder' => 'CategoryBinder' ] );

			// Bind tags
			$modelTagService->bindTags( $model->id, $mappingsType, [ 'binder' => 'TagBinder' ] );

			// Default Settings
			$this->metaService->initByNameType( $model->id, CoreGlobal::META_RECEIVE_EMAIL, 'notification', TopicMeta::VALUE_TYPE_FLAG );
			$this->metaService->initByNameType( $model->id, CoreGlobal::META_RECEIVE_EMAIL, 'reminder', TopicMeta::VALUE_TYPE_FLAG );

			$transaction->commit();

			$registered	= true;
		}
		catch( Exception $e ) {

			$transaction->rollBack();

			return false;
		}

		if( $registered ) {

			// Post Registration
		}

		return $model;
	}

	// Update -------------

	public function update( $model, $config = [] ) {

		$admin = isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : false;

		$content		= isset( $config[ 'content' ] ) ? $config[ 'content' ] : null;
		$publish		= isset( $config[ 'publish' ] ) ? $config[ 'publish' ] : false;
		$avatar			= isset( $config[ 'avatar' ] ) ? $config[ 'avatar' ] : null;
		$banner			= isset( $config[ 'banner' ] ) ? $config[ 'banner' ] : null;
		$mbanner		= isset( $config[ 'mbanner' ] ) ? $config[ 'mbanner' ] : null;
		$video			= isset( $config[ 'video' ] ) ? $config[ 'video' ] : null;
		$mvideo			= isset( $config[ 'mvideo' ] ) ? $config[ 'mvideo' ] : null;
		$gallery		= isset( $config[ 'gallery' ] ) ? $config[ 'gallery' ] : null;
		$mappingsType	= isset( $config[ 'mappingsType' ] ) ? $config[ 'mappingsType' ] : static::$parentType;

		$attributes	= isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [
			'name', 'slug', 'icon', 'texture',
			'title', 'description', 'visibility', 'content'
		];

		if( $admin ) {

			$attributes	= ArrayHelper::merge( $attributes, [
				'status', 'order', 'pinned', 'featured', 'popular', 'score'
			]);
		}

		// Copy Template
		if( isset( $content ) ) {

			$config[ 'template' ] = $content->template;

			if( $this->copyTemplate( $model, $config ) ) {

				$attributes[] = 'data';
			}
		}

		// Services
		$galleryService			= Yii::$app->factory->get( 'galleryService' );
		$modelContentService	= Yii::$app->factory->get( 'modelContentService' );
		$modelCategoryService	= Yii::$app->factory->get( 'modelCategoryService' );
		$modelTagService		= Yii::$app->factory->get( 'modelTagService' );

		// Save Files
		$this->fileService->saveFiles( $model, [ 'avatarId' => $avatar ] );

		// Create/Update gallery
		if( isset( $gallery ) ) {

			$gallery = $galleryService->createOrUpdate( $gallery );
		}

		// Update model content
		if( isset( $content ) ) {

			$modelContentService->update( $content, [
				'publish' => $publish,
				'banner' => $banner, 'mbanner' => $mbanner,
				'video' => $video, 'mvideo' => $mvideo,
				'gallery' => $gallery
			]);
		}

		// Bind categories
		$modelCategoryService->bindModels( $model->id, $mappingsType, [ 'binder' => 'CategoryBinder' ] );

		// Bind tags
		$modelTagService->bindTags( $model->id, $mappingsType, [ 'binder' => 'TagBinder' ] );

		// Model Checks
		$oldStatus = $model->getOldAttribute( 'status' );

		$model = parent::update( $model, [
			'attributes' => $attributes
		]);

		// Check status change and notify User
		if( isset( $model->userId ) && $oldStatus != $model->status ) {

			$config[ 'users' ] = [ $model->userId ];

			$config[ 'data' ][ 'message' ] = 'Topic status changed.';

			$this->checkStatusChange( $model, $oldStatus, $config );
		}

		return $model;
	}

	// Delete -------------

	public function delete( $model, $config = [] ) {

		$config[ 'hard' ] = $config[ 'hard' ] ?? !Yii::$app->core->isSoftDelete();

		if( $config[ 'hard' ] ) {

			$transaction = Yii::$app->db->beginTransaction();

			try {

				// Delete metas
				$this->metaService->deleteByModelId( $model->id );

				// Delete Files
				$this->fileService->deleteMultiple( [ $model->avatar ] );

				// Delete Model Content, Gallery, Banner, Video
				Yii::$app->factory->get( 'modelContentService' )->delete( $model->modelContent );

				// Delete Category Mappings
				Yii::$app->factory->get( 'modelCategoryService' )->deleteByParent( $model->id, static::$parentType );

				// Delete Tag Mappings
				Yii::$app->factory->get( 'modelTagService' )->deleteByParent( $model->id, static::$parentType );

				// Delete Followers
				Yii::$app->factory->get( 'topicFollowerService' )->deleteByModelId( $model->id );

				$transaction->commit();

				// Delete model
				return parent::delete( $model, $config );
			}
			catch( Exception $e ) {

				$transaction->rollBack();

				throw new Exception( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_DEPENDENCY )  );
			}
		}

		// Delete model
		return parent::delete( $model, $config );
	}

	public function deleteByForumId( $forumId ) {

		$topics = $this->getByForumId( $forumId );

		foreach( $topics as $topic ) {

			$this->delete( $topic );
		}
	}

	// Bulk ---------------

	protected function applyBulk( $model, $column, $action, $target, $config = [] ) {

		$config[ 'users' ] = isset( $config[ 'users' ] ) ? $config[ 'users' ] : ( isset( $model->userId ) ? [ $model->userId ] : [] );

		return parent::applyBulk( $model, $column, $action, $target, $config );
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
