<?php
namespace cmsgears\modules\forum\admin\services;

// Yii Imports
use \Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\modules\core\common\models\entities\CmgFile;
use cmsgears\modules\forum\common\models\entities\Forum;
use cmsgears\modules\forum\common\models\entities\ForumCategory;
use cmsgears\modules\forum\common\models\entities\ForumPost;
use cmsgears\modules\forum\common\models\entities\ForumTopic;

use cmsgears\modules\core\admin\services\FileService;

use cmsgears\modules\core\common\utilities\CodeGenUtil;
use cmsgears\modules\core\common\utilities\DateUtil;

class ForumService extends \cmsgears\modules\forum\common\services\ForumService {
	
	// Pagination

	public static function getPagination() {

	    $sort = new Sort([
	        'attributes' => [
	            'name' => [
	                'asc' => [ 'forum_name' => SORT_ASC ],
	                'desc' => ['forum_name' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'name',
	            ],
	            'cdate' => [
	                'asc' => [ 'forum_created_on' => SORT_ASC ],
	                'desc' => ['forum_created_on' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'cdate',
	            ],
	            'udate' => [
	                'asc' => [ 'forum_updated_on' => SORT_ASC ],
	                'desc' => ['forum_updated_on' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'udate',
	            ]
	        ],
	        'defaultOrder' => [
	        	'cdate' => SORT_DESC
	        ]
	    ]);

		return self::getPaginationDetails( new Forum(), [ 'sort' => $sort, 'search-col' => 'forum_name' ] );
	}

	// Create

	public static function create( $forum, $banner ) {
		
		$date 		= DateUtil::getMysqlDate();
		$user		= Yii::$app->user->getIdentity();
	
		$forum->setOwnerId( $user->getId() );
		$forum->setCreatedOn( $date );
		$forum->setSlug( CodeGenUtil::generateSlug( $forum->getName() ) );

		// Save Banner
		FileService::saveImage( $banner, $user, Yii::$app->fileManager );

		// New Banner
		$bannerId 	= $banner->getId();

		if( isset( $bannerId ) && intval( $bannerId ) > 0 ) {

			$forum->setBannerId( $banner->getId() );
		}

		$forum->save();

		return true;
	}

	// Update

	public static function update( $forum, $banner ) {

		$date 			= DateUtil::getMysqlDate();
		$user			= Yii::$app->user->getIdentity();	
		$forumToUpdate	= self::findById( $forum->getId() );

		$forumToUpdate->setName( $forum->getName() );
		$forumToUpdate->setDesc( $forum->getDesc() );
		$forumToUpdate->setUpdatedOn( $date );
		$forumToUpdate->setSlug( CodeGenUtil::generateSlug( $forum->getName() ) );

		// Save Banner
		FileService::saveImage( $banner, $user, Yii::$app->fileManager );

		// New Banner
		$bannerId 	= $banner->getId();

		if( isset( $bannerId ) && intval( $bannerId ) > 0 ) {

			$forumToUpdate->setBannerId( $banner->getId() );
		}

		$forumToUpdate->update();

		return true;
	}

	public static function bindCategories( $binder ) {

		$forumId		= $binder->forumId;
		$categories		= $binder->bindedData;

		// Clear all existing mappings
		ForumCategory::deleteByForumId( $forumId );

		if( isset( $categories ) && count( $categories ) > 0 ) {

			foreach ( $categories as $key => $value ) {

				if( isset( $value ) ) {

					$toSave		= new ForumCategory();

					$toSave->setForumId( $forumId );
					$toSave->setCategoryId( $value );

					$toSave->save();
				}
			}
		}

		return true;
	}

	// Delete

	public static function delete( $forum ) {

		$forum->delete();

		return true;
	}
}

?>