<?php
namespace cmsgears\modules\forum\admin\services;

// Yii Imports
use \Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\modules\forum\common\models\entities\Forum;
use cmsgears\modules\forum\common\models\entities\ForumTopic;
use cmsgears\modules\forum\common\models\entities\ForumReply;

use cmsgears\modules\forum\admin\services\ForumService;
use cmsgears\modules\forum\admin\services\ForumTopicService;

use cmsgears\modules\core\common\utilities\DateUtil;

class ForumReplyService extends \cmsgears\modules\forum\common\services\ForumReplyService {

	// Pagination

	public static function getPagination( $topicId = null ) {

	    $sort = new Sort([
	        'attributes' => [
	            'content' => [
	                'asc' => [ 'reply_content' => SORT_ASC ],
	                'desc' => ['reply_content' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'content',
	            ],
	            'owner' => [
	                'asc' => [ 'user_firstname' => SORT_ASC, 'user_lastname' => SORT_ASC ],
	                'desc' => [ 'user_firstname' => SORT_DESC, 'user_lastname' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'owner',
	            ],
	            'cdate' => [
	                'asc' => [ 'reply_created_on' => SORT_ASC ],
	                'desc' => ['reply_created_on' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'cdate',
	            ],
	            'udate' => [
	                'asc' => [ 'reply_updated_on' => SORT_ASC ],
	                'desc' => ['reply_updated_on' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'udate',
	            ]
	        ],
	        'defaultOrder' => [
	        	'cdate' => SORT_DESC
	        ]
	    ]);
		
		if( isset( $topicId ) && $topicId > 0 ) {

			return self::getPaginationDetails( new ForumReply(), [ 'query' => ForumReply::getQuery(), 'conditions' => [ 'reply_topic' => $topicId ], 'sort' => $sort, 'search-col' => 'post_content' ] );
		}
		else {

			return self::getPaginationDetails( new ForumReply(), [ 'query' => ForumReply::getQuery(), 'sort' => $sort, 'search-col' => 'post_content' ] );
		}
	}

	public static function getPaginationByTopicId( $id ) {

		return self::getPagination( $id );
	}
	
	// Create	

	public static function create( $post, $topicId ) {

		$date 	= DateUtil::getMysqlDate();
		$user	= Yii::$app->user->getIdentity();
		$topic 	= ForumTopicService::findById( $topicId );

		$post->setOwnerId( $user->getId() );
		$post->setTopicId( $topicId );
		$post->setForumId( $topic->getForumId() );
		$post->setCreatedOn( $date );

		$post->save();

		return true;
	}

	// Update

	public static function update( $post ) {
		
		$postToUpdate	= self::findById( $post->getId() );
		
		$postToUpdate->setContent( $post->getContent() );

		$postToUpdate->update();

		return true;
	}

	// Delete

	public static function delete( $post ) {

		$post->delete();

		return true;
	}
}

?>