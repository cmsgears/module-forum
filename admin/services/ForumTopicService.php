<?php
namespace cmsgears\modules\forum\admin\services;

// Yii Imports
use \Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\modules\forum\common\models\entities\Forum;
use cmsgears\modules\forum\common\models\entities\ForumPost;
use cmsgears\modules\forum\common\models\entities\ForumTopic;

use cmsgears\modules\core\common\utilities\DateUtil;

class ForumTopicService extends \cmsgears\modules\forum\common\services\ForumTopicService {
	
	// Pagination

	public static function getPagination() {
		
		return self::getPaginationByForumId( null );
	}

	public static function getPaginationByForumId( $id ) {

	    $sort = new Sort([
	        'attributes' => [
	            'name' => [
	                'asc' => [ 'topic_name' => SORT_ASC ],
	                'desc' => [ 'topic_name' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'name',
	            ],
	            'owner' => [
	                'asc' => [ 'user_firstname' => SORT_ASC, 'user_lastname' => SORT_ASC ],
	                'desc' => [ 'user_firstname' => SORT_DESC, 'user_lastname' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'owner',
	            ],
	            'cdate' => [
	                'asc' => [ 'topic_created_on' => SORT_ASC ],
	                'desc' => ['topic_created_on' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'cdate',
	            ],
	            'udate' => [
	                'asc' => [ 'topic_updated_on' => SORT_ASC ],
	                'desc' => ['topic_updated_on' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'udate',
	            ]
	        ]
	    ]);
		
		if( isset( $id ) && $id > 0 ) {

			return self::getPaginationDetails( new ForumTopic(), [ 'query' => ForumTopic::getQuery(), 'conditions' => [ 'topic_forum' => $id ], 'sort' => $sort, 'search-col' => 'topic_name' ] );
		}
		
		return self::getPaginationDetails( new ForumTopic(), [ 'query' => ForumTopic::getQuery(), 'sort' => $sort, 'search-col' => 'topic_name' ] );
	}
	
	// Create

	public static function create( $topic, $forumId ) {

		$date 		= DateUtil::getMysqlDate();
		$user		= Yii::$app->user->getIdentity();
		
		$topic->setOwnerId( $user->getId() );
		$topic->setCreatedOn( $date );
		$topic->setForumId( $forumId );
		
		$topic->save();

		return true;
	}
	
	// Update

	public static function update( $topic ) {
		
		$topicToUpdate	= self::findById( $topic->getId() );
		
		$topicToUpdate->setName( $topic->getName() );
		$topicToUpdate->setDesc( $topic->getDesc() );

		$topicToUpdate->update();

		return true;
	}
	
	// Delete

	public static function delete( $topic ) {

		$topic->delete();

		return true;
	}
}

?>