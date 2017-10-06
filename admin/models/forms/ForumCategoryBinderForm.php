<?php
namespace cmsgears\modules\forum\admin\models\forms;

// Yii Imports
use \Yii;
use yii\base\Model;

class ForumCategoryBinderForm extends Model {

	public $forumId;
	public $bindedData;

	// yii\base\Model

	public function rules() {

        return [
            [ [ 'forumId', 'bindedData' ], 'required' ],
            [ 'forumId', 'compare', 'compareValue' => 0, 'operator' => '>' ]
        ];
    }
}

?>