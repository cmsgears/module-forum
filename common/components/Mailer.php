<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\forum\common\components;

/**
 * Mailer triggers the mails provided by Topic Module.
 *
 * @since 1.0.0
 */
class Mailer extends \cmsgears\core\common\base\Mailer {

	// Global -----------------

	const MAIL_TOPIC_CREATE		= 'topic/create';
	const MAIL_TOPIC_REGISTER	= 'topic/register';

	// Public -----------------

	public $htmlLayout	= '@cmsgears/module-core/common/mails/layouts/html';
	public $textLayout	= '@cmsgears/module-core/common/mails/layouts/text';
	public $viewPath	= '@cmsgears/module-topic/common/mails/views';

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG parent classes --------------------

	// Mailer --------------------------------

	public function sendCreateTopicMail( $topic ) {

		$fromEmail	= $this->mailProperties->getSenderEmail();
		$fromName	= $this->mailProperties->getSenderName();

		$email = $topic->creator->email;

		// Send Mail
		$this->getMailer()->compose( self::MAIL_TOPIC_CREATE, [ 'coreProperties' => $this->coreProperties, 'topic' => $topic ] )
			->setTo( $email )
			->setFrom( [ $fromEmail => $fromName ] )
			->setSubject( "Topic Registration | " . $this->coreProperties->getSiteName() )
			//->setTextBody( "text" )
			->send();
	}

	public function sendRegisterTopicMail( $topic, $admin ) {

		$fromEmail	= $this->mailProperties->getSenderEmail();
		$fromName	= $this->mailProperties->getSenderName();

		$email = $topic->creator->email;

		// Send Mail
		$this->getMailer()->compose( self::MAIL_TOPIC_REGISTER, [ 'coreProperties' => $this->coreProperties, 'topic' => $topic ] )
			->setTo( $email )
			->setFrom( [ $fromEmail => $fromName ] )
			->setSubject( "Topic Registration | " . $this->coreProperties->getSiteName() )
			//->setTextBody( "text" )
			->send();
	}

}
