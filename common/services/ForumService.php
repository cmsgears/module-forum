<?php
namespace cmsgears\modules\forum\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\modules\forum\common\models\entities\Forum;

use cmsgears\modules\core\common\services\Service;

class ForumService extends Service {

	// Read

	public static function findById( $id ) {

		return Forum::findOne( $id );
	}

	public static function getIdNameMap() {

		$forumMap 		= array();
		$forums 		= Forum::find()->all();

		foreach ( $forums as $forum ) {

			$forumMap[] = [ "id" => $forum->getId(), "name" => $forum->getName() ];
		}

		return $forumMap;
	}
}

?>