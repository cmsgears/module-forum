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
use yii\base\Exception;
use yii\db\Query;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\forum\common\config\ForumGlobal;

use cmsgears\forum\common\models\resources\ForumMeta;

use cmsgears\core\common\services\interfaces\resources\IFileService;
use cmsgears\forum\common\services\interfaces\entities\IForumService;
use cmsgears\forum\common\services\interfaces\resources\IForumMetaService;
use cmsgears\forum\common\services\interfaces\mappers\IForumFollowerService;

use cmsgears\core\common\services\traits\resources\MetaTrait;

/**
 * ForumService provide service methods of forum model.
 *
 * @since 1.0.0
 */
class ForumService extends \cmsgears\cms\common\services\base\ContentService implements IForumService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass = '\cmsgears\forum\common\models\entities\Forum';

	public static $parentType = ForumGlobal::TYPE_FORUM;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $fileService;
	protected $metaService;
	protected $followerService;

	// Private ----------------

	// Traits ------------------------------------------------------

	use MetaTrait;

	// Constructor and Initialisation ------------------------------

	public function __construct( IFileService $fileService, IForumMetaService $metaService, IForumFollowerService $followerService, $config = [] ) {

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

	// ForumService --------------------------

	// Data Provider ------

	public function getPublicPage( $config = [] ) {

		$config[ 'route' ] = isset( $config[ 'route' ] ) ? $config[ 'route' ] : 'forum';

		return parent::getPublicPage( $config );
	}

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	public function getEmail( $model ) {

		return isset( $model->userId ) ? $model->user->email : $model->creator->email;
	}

	public function getBlockStats( $config = [] ) {

		$limit = isset( $config[ 'limit' ] ) ? $config[ 'limit' ] : 5;

		$modelClass		= static::$modelClass;
		$modelTable		= $this->getModelTable();
		$fileTable		= Yii::$app->factory->get( 'fileService' )->getModelTable();
		$topicClass		= Yii::$app->factory->get( 'topicService' )->getModelClass();
		$topicTable		= Yii::$app->factory->get( 'topicService' )->getModelTable();

		$query = new Query();

		$query->select([
			"$modelTable.slug", "$modelTable.name", "$modelTable.title", "$fileTable.medium as avatarUrl",
			"count($topicTable.id) AS topics"
		]);

		$query->from( $modelTable );

		$query->join( 'LEFT JOIN', $fileTable, "$fileTable.id = $modelTable.avatarId" );
		$query->join( 'LEFT JOIN', $topicTable, "$topicTable.forumId = $modelTable.id" );

		$query->where( "$modelTable.status=:mstatus AND $topicTable.status=:tstatus", [
			':mstatus' => $modelClass::STATUS_ACTIVE,
			':tstatus' => $topicClass::STATUS_ACTIVE
		]);

		$query->groupBy( "$modelTable.id" );
		$query->limit( $limit );

		return $query->all();
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

		$content	= isset( $config[ 'content' ] ) ? $config[ 'content' ] : new ModelContent();
		$publish	= isset( $config[ 'publish' ] ) ? $config[ 'publish' ] : false;
		$banner		= isset( $config[ 'banner' ] ) ? $config[ 'banner' ] : null;
		$mbanner	= isset( $config[ 'mbanner' ] ) ? $config[ 'mbanner' ] : null;
		$video		= isset( $config[ 'video' ] ) ? $config[ 'video' ] : null;
		$mvideo		= isset( $config[ 'mvideo' ] ) ? $config[ 'mvideo' ] : null;
		$gallery	= isset( $config[ 'gallery' ] ) ? $config[ 'gallery' ] : null;

		$galleryService			= Yii::$app->factory->get( 'galleryService' );
		$modelContentService	= Yii::$app->factory->get( 'modelContentService' );

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

				$gallery->siteId	= $model->siteId;
				$gallery->type		= $parentType;
				$gallery->status	= $galleryClass::STATUS_ACTIVE;
				$gallery->name		= empty( $gallery->name ) ? $model->name : $gallery->name;

				$gallery = $galleryService->create( $gallery );
			}
			else {

				$gallery = $galleryService->createByParams([
					'siteId' => $model->siteId,
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

			// Default Settings
			$this->metaService->initByNameType( $model->id, CoreGlobal::META_RECEIVE_EMAIL, 'notification', ForumMeta::VALUE_TYPE_FLAG );
			$this->metaService->initByNameType( $model->id, CoreGlobal::META_RECEIVE_EMAIL, 'reminder', ForumMeta::VALUE_TYPE_FLAG );

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

		$content	= isset( $config[ 'content' ] ) ? $config[ 'content' ] : new ModelContent();
		$publish	= isset( $config[ 'publish' ] ) ? $config[ 'publish' ] : false;
		$banner		= isset( $config[ 'banner' ] ) ? $config[ 'banner' ] : null;
		$mbanner	= isset( $config[ 'mbanner' ] ) ? $config[ 'mbanner' ] : null;
		$video		= isset( $config[ 'video' ] ) ? $config[ 'video' ] : null;
		$mvideo		= isset( $config[ 'mvideo' ] ) ? $config[ 'mvideo' ] : null;
		$gallery	= isset( $config[ 'gallery' ] ) ? $config[ 'gallery' ] : null;
		$adminLink	= isset( $config[ 'adminLink' ] ) ? $config[ 'adminLink' ] : 'forum/forum/review';

		$galleryService			= Yii::$app->factory->get( 'galleryService' );
		$modelContentService	= Yii::$app->factory->get( 'modelContentService' );

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

				$gallery->siteId	= $model->siteId;
				$gallery->type		= $parentType;
				$gallery->status	= $galleryClass::STATUS_ACTIVE;
				$gallery->name		= empty( $gallery->name ) ? $model->name : $gallery->name;

				$gallery = $galleryService->create( $gallery );
			}
			else {

				$gallery = $galleryService->createByParams([
					'siteId' => $model->siteId,
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

			// Default Settings
			$this->metaService->initByNameType( $model->id, CoreGlobal::META_RECEIVE_EMAIL, 'notification', ForumMeta::VALUE_TYPE_FLAG );
			$this->metaService->initByNameType( $model->id, CoreGlobal::META_RECEIVE_EMAIL, 'reminder', ForumMeta::VALUE_TYPE_FLAG );

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

		$content	= isset( $config[ 'content' ] ) ? $config[ 'content' ] : null;
		$publish	= isset( $config[ 'publish' ] ) ? $config[ 'publish' ] : false;
		$avatar		= isset( $config[ 'avatar' ] ) ? $config[ 'avatar' ] : null;
		$banner		= isset( $config[ 'banner' ] ) ? $config[ 'banner' ] : null;
		$mbanner	= isset( $config[ 'mbanner' ] ) ? $config[ 'mbanner' ] : null;
		$video		= isset( $config[ 'video' ] ) ? $config[ 'video' ] : null;
		$mvideo		= isset( $config[ 'mvideo' ] ) ? $config[ 'mvideo' ] : null;
		$gallery	= isset( $config[ 'gallery' ] ) ? $config[ 'gallery' ] : null;

		$attributes	= isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [
			'avatarId', 'name', 'slug', 'icon', 'texture',
			'title', 'description', 'visibility', 'content'
		];

		if( $admin ) {

			$attributes	= ArrayHelper::merge( $attributes, [
				'status', 'order', 'pinned', 'featured', 'popular', 'reviews'
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

		// Model Checks
		$oldStatus = $model->getOldAttribute( 'status' );

		$model = parent::update( $model, [
			'attributes' => $attributes
		]);

		// Check status change and notify User
		if( isset( $model->userId ) && $oldStatus != $model->status ) {

			$config[ 'users' ] = [ $model->userId ];

			$config[ 'data' ][ 'message' ] = 'Forum status changed.';

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

				// Delete Comments
				Yii::$app->factory->get( 'modelCommentService' )->deleteByParent( $model->id, static::$parentType );

				// Delete Followers
				Yii::$app->factory->get( 'forumFollowerService' )->deleteByModelId( $model->id );

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

	// ForumService --------------------------

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
