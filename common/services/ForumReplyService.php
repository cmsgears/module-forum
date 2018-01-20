<?php
namespace cmsgears\modules\forum\common\services;

// Yii Imports
use \Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\modules\forum\common\models\entities\ForumReply;

use cmsgears\modules\core\common\services\Service;

class ForumReplyService extends Service {
	
	// Read

	public static function findById( $id ) {

		return ForumReply::findOne( $id );
	}
}

?>