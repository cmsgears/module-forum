<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\forum\common\services\interfaces\entities;

// CMG Imports
use cmsgears\core\common\services\interfaces\base\ISimilar;
use cmsgears\core\common\services\interfaces\mappers\ICategory;
use cmsgears\core\common\services\interfaces\resources\IMeta;
use cmsgears\cms\common\services\interfaces\base\IContentService;

/**
 * ITopicService declares methods specific to topic model.
 *
 * @since 1.0.0
 */
interface ITopicService extends IContentService, IMeta {

	// Data Provider ------

	public function getPageByForumId( $forumId, $config = [] );

	// Read ---------------

	// Read - Models ---

	public function getByForumId( $forumId );

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	public function getEmail( $model );

	// Create -------------

	// Update -------------

	// Delete -------------

	public function deleteByForumId( $forumId );

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}
