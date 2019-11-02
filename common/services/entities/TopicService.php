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
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\forum\common\config\ForumGlobal;

use cmsgears\core\common\services\interfaces\resources\IFileService;
use cmsgears\forum\common\services\interfaces\entities\ITopicService;
use cmsgears\forum\common\services\interfaces\resources\ITopicMetaService;

use cmsgears\core\common\services\traits\resources\MetaTrait;

/**
 * TopicService provide service methods of topic model.
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

	// Private ----------------

	// Traits ------------------------------------------------------

	use MetaTrait;

	// Constructor and Initialisation ------------------------------

	public function __construct( IFileService $fileService, ITopicMetaService $metaService, $config = [] ) {

		$this->fileService	= $fileService;
		$this->metaService	= $metaService;

		parent::__construct( $config );
	}

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// TopicService --------------------------

	// Data Provider ------

	public function getPublicPage( $config = [] ) {

		$config[ 'route' ] = isset( $config[ 'route' ] ) ? $config[ 'route' ] : 'topic';

		return parent::getPublicPage( $config );
	}

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function create( $model, $config = [] ) {

		$avatar = isset( $config[ 'avatar' ] ) ? $config[ 'avatar' ] : null;

		$modelClass = static::$modelClass;

		// Save Files
		$this->fileService->saveFiles( $model, [ 'avatarId' => $avatar ] );

		// Default Private
		$model->visibility = $model->visibility ?? $modelClass::VISIBILITY_PRIVATE;

		// Default New
		$model->status = $model->status ?? $modelClass::STATUS_NEW;

		// Create Topic
		return parent::create( $model, $config );
	}

	public function add( $model, $config = [] ) {

		$admin	= isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : false;
		$mail	= isset( $config[ 'mail' ] ) ? $config[ 'mail' ] : true;

		$modelClass = static::$modelClass;

		$content 	= isset( $config[ 'content' ] ) ? $config[ 'content' ] : new ModelContent();
		$banner 	= isset( $config[ 'banner' ] ) ? $config[ 'banner' ] : null;
		$video 		= isset( $config[ 'video' ] ) ? $config[ 'video' ] : null;
		$gallery	= isset( $config[ 'gallery' ] ) ? $config[ 'gallery' ] : null;

		$galleryService			= Yii::$app->factory->get( 'galleryService' );
		$modelContentService	= Yii::$app->factory->get( 'modelContentService' );

		$galleryClass = $galleryService->getModelClass();

		$registered	= false;

		$transaction = Yii::$app->db->beginTransaction();

		try {

			// Create Topic
			$model = $this->create( $model, $config );

			// Create Gallery
			if( isset( $gallery ) ) {

				$gallery->type		= static::$parentType;
				$gallery->status	= $galleryClass::STATUS_ACTIVE;
				$gallery->siteId	= Yii::$app->core->siteId;

				$gallery = $galleryService->create( $gallery );
			}
			else {

				$gallery = $galleryService->createByParams([
					'type' => static::$parentType, 'status' => $galleryClass::STATUS_ACTIVE,
					'name' => $model->name, 'title' => $model->name,
					'siteId' => Yii::$app->core->siteId
				]);
			}

			// Create and attach model content
			$modelContentService->create( $content, [
				'parent' => $model, 'parentType' => static::$parentType,
				'publish' => true,
				'banner' => $banner, 'video' => $video, 'gallery' => $gallery
			]);

			$transaction->commit();

			$registered	= true;
		}
		catch( Exception $e ) {

			$transaction->rollBack();

			return false;
		}

		if( $registered ) {

			// Email Topic Admin
			if( $mail ) {

				//Yii::$app->forumMailer->sendCreateTopicMail( $model );
			}
		}

		return $model;
	}

	public function register( $model, $config = [] ) {

		$notify	= isset( $config[ 'notify' ] ) ? $config[ 'notify' ] : true;
		$mail	= isset( $config[ 'mail' ] ) ? $config[ 'mail' ] : true;

		$modelClass = static::$modelClass;

		$content 	= isset( $config[ 'content' ] ) ? $config[ 'content' ] : new ModelContent();
		$banner 	= isset( $config[ 'banner' ] ) ? $config[ 'banner' ] : null;
		$video 		= isset( $config[ 'video' ] ) ? $config[ 'video' ] : null;
		$gallery	= isset( $config[ 'gallery' ] ) ? $config[ 'gallery' ] : null;
		$adminLink	= isset( $config[ 'adminLink' ] ) ? $config[ 'adminLink' ] : 'forum/topic/review';

		$galleryService			= Yii::$app->factory->get( 'galleryService' );
		$modelContentService	= Yii::$app->factory->get( 'modelContentService' );

		$galleryClass = $galleryService->getModelClass();

		$registered	= false;

		$transaction = Yii::$app->db->beginTransaction();

		try {

			// Create Topic
			$model = $this->create( $model, $config );

			// Create Gallery
			if( isset( $gallery ) ) {

				$gallery->type		= static::$parentType;
				$gallery->status	= $galleryClass::STATUS_ACTIVE;
				$gallery->siteId	= Yii::$app->core->siteId;
				$gallery->name		= empty( $gallery->name ) ? $model->name : $gallery->name;

				$gallery = $galleryService->create( $gallery );
			}
			else {

				$gallery = $galleryService->createByParams([
					'type' => static::$parentType, 'status' => $galleryClass::STATUS_ACTIVE,
					'name' => $model->name, 'title' => $model->displayName,
					'siteId' => Yii::$app->core->siteId
				]);
			}

			// Create and attach model content
			$modelContentService->create( $content, [
				'parent' => $model, 'parentType' => static::$parentType,
				'publish' => true,
				'banner' => $banner, 'video' => $video, 'gallery' => $gallery
			]);

			$transaction->commit();

			$registered	= true;
		}
		catch( Exception $e ) {

			$transaction->rollBack();

			return false;
		}

		if( $registered ) {

			// Notify Site Admin
			if( $notify ) {

				$this->notifyAdmin( $model, [
					'template' => ForumGlobal::TPL_NOTIFY_TOPIC_NEW,
					'adminLink' => $adminLink . "?id={$model->id}"
				]);
			}

			// Email Topic Admin
			if( $mail ) {

				//Yii::$app->orgMailer->sendRegisterTopicMail( $model, $user );
			}
		}

		return $model;
	}

	// Update -------------

	public function update( $model, $config = [] ) {

		$admin = isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : false;

		$content	= isset( $config[ 'content' ] ) ? $config[ 'content' ] : $model->modelContent;
		$avatar 	= isset( $config[ 'avatar' ] ) ? $config[ 'avatar' ] : null;
		$banner 	= isset( $config[ 'banner' ] ) ? $config[ 'banner' ] : null;
		$video 		= isset( $config[ 'video' ] ) ? $config[ 'video' ] : null;
		$gallery	= isset( $config[ 'gallery' ] ) ? $config[ 'gallery' ] : null;

		$attributes	= isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [
			'avatarId', 'name', 'slug', 'icon', 'title', 'description', 'content'
		];

		if( $admin ) {

			$attributes	= ArrayHelper::merge( $attributes, [
				'status', 'order', 'pinned', 'featured'
			]);
		}

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
				'publish' => true, 'banner' => $banner, 'video' => $video, 'gallery' => $gallery
			]);
		}

		return parent::update( $model, [
			'attributes' => $attributes
		]);
	}

	// Delete -------------

	public function delete( $model, $config = [] ) {

		$config[ 'hard' ] = $config[ 'hard' ] ?? !Yii::$app->core->isSoftDelete();

		if( $config[ 'hard' ] ) {

			$transaction = Yii::$app->db->beginTransaction();

			try {

				// Delete metas
				$this->metaService->deleteByModelId( $model->id );

				// Delete files
				$this->fileService->delete( $model->avatar );

				// Delete Model Content, Gallery, Banner, Video
				Yii::$app->factory->get( 'modelContentService' )->delete( $model->modelContent );

				// Delete Category Mappings
				Yii::$app->factory->get( 'modelCategoryService' )->deleteByParent( $model->id, static::$parentType );

				// Delete Tag Mappings
				Yii::$app->factory->get( 'modelTagService' )->deleteByParent( $model->id, static::$parentType );

				// Delete Option Mappings
				Yii::$app->factory->get( 'modelOptionService' )->deleteByParent( $model->id, static::$parentType );

				// Delete Replies
				//Yii::$app->factory->get( 'topicReplyService' )->deleteByTopicId( $model->id );

				// Delete Followers
				Yii::$app->factory->get( 'topicFollowerService' )->deleteByModelId( $model->id );

				$transaction->commit();
			}
			catch( Exception $e ) {

				$transaction->rollBack();

				throw new Exception( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_DEPENDENCY ) );
			}
		}

		// Delete model
		return parent::delete( $model, $config );
	}

	// Bulk ---------------

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
