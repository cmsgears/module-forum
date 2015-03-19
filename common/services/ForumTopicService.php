<?php
namespace cmsgears\modules\forum\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\modules\forum\common\models\entities\ForumTopic;

use cmsgears\modules\core\common\services\Service;

class ForumTopicService extends Service {

	// Read

	public static function findById( $id ) {

		return ForumTopic::findOne( $id );
	}
}

?>