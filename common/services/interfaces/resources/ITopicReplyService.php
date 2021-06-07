<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\forum\common\services\interfaces\resources;

// CMG Imports
use cmsgears\core\common\services\interfaces\base\IResourceService;

/**
 * ITopicReplyService declares methods specific to topic reply.
 *
 * @since 1.0.0
 */
interface ITopicReplyService extends IResourceService {

	// Data Provider ------

	public function getPageByTopicId( $topicId, $config = [] );

	public function getPageByBaseId( $baseId, $config = [] );

	public function getPageForApproved( $topicId, $config = [] );

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// States -----

	public function updateStatus( $model, $status );

	public function approve( $model );

	public function block( $model );

	public function markSpam( $model );

	public function markTrash( $model );

	// Requests ---

	public function spamRequest( $model, $parent, $config = [] );

	public function approveRequest( $model, $parent, $config = [] );

	public function deleteRequest( $model, $parent, $config = [] );

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}
