<?php
namespace cmsgears\forum\common\components;

// Yii Imports
use yii\base\Component;

// CMG Imports
use cmsgears\forum\common\config\ForumGlobal;

class MessageSource extends Component {

	// Variables ---------------------------------------------------

	// Global -----------------

	// Public -----------------

	// Protected --------------

	protected $messageDb = [
		// Generic Fields
		ForumGlobal::FIELD_TOPIC => 'Topic'
	];

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG parent classes --------------------

	// MessageSource -------------------------

	public function getMessage( $messageKey, $params = [], $language = null ) {

		return $this->messageDb[ $messageKey ];
	}

}
