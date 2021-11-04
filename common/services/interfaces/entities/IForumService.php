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
use cmsgears\cms\common\services\interfaces\base\IContentService;
use cmsgears\core\common\services\interfaces\resources\IMeta;

/**
 * IForumService declares methods specific to forum model.
 *
 * @since 1.0.0
 */
interface IForumService extends IContentService, IMeta {

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	public function getEmail( $model );

	public function getBlockStats( $config = [] );

	// Create -------------

	// Update -------------

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}
