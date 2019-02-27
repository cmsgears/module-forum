<?php
// CMG Imports
use cmsgears\widgets\popup\Popup;

use cmsgears\widgets\grid\DataGrid;

$coreProperties = $this->context->getCoreProperties();
$this->title	= 'Forum Categories | ' . $coreProperties->getSiteTitle();

// Templates
$moduleTemplates	= '@cmsgears/module-cms/admin/views/templates';
?>

<?= DataGrid::widget([
	'dataProvider' => $dataProvider, 'add' => true, 'addUrl' => 'create', 'data' => [ ],
	'title' => 'Categories', 'options' => [ 'class' => 'grid-data grid-data-admin' ],
	'searchColumns' => [ 'name' => 'Name', 'desc' => 'Description' ],
	'sortColumns' => [
		'name' => 'Name', 'slug' => 'Slug', 'featured' => 'Featured', 'order' => 'Order'
	],
	'filters' => [ 'status' => [ 'featured' => 'Featured' ], 'parent' => [ 'top' => 'Top Level' ] ],
	'reportColumns' => [
		'name' => [ 'title' => 'Name', 'type' => 'text' ],
		'slug' => [ 'title' => 'Slug', 'type' => 'text' ],
		'desc' => [ 'title' => 'Description', 'type' => 'text' ],
		'featured' => [ 'title' => 'Featured', 'type' => 'flag' ]
	],
	'bulkPopup' => 'popup-grid-bulk', 'bulkActions' => [
		'status' => [ 'featured' => 'Featured', 'regular' => 'Regular' ],
		'model' => [ 'delete' => 'Delete' ]
	],
	'header' => false, 'footer' => true,
	'grid' => true, 'columns' => [ 'root' => 'colf colf15', 'factor' => [ null , 'x3', null, 'x5', null, 'x2', null, null ] ],
	'gridColumns' => [
		'bulk' => 'Action',
		'name' => 'Name',
		'icon' => [ 'title' => 'Icon', 'generate' => function( $model ) { return "<i class=\"$model->icon\"></i>"; } ],
		'description' => 'Description',
		'featured'	=> [ 'title' => 'Featured', 'generate' => function( $model ) { return $model->getFeaturedStr(); } ],
		'parent'	=> [ 'title' => 'Parent', 'generate' => function( $model ) { return $model->getParentName(); } ],
		'template'	=> [ 'title' => 'Template', 'generate' => function( $model ) { return $model->modelContent->getTemplateName(); } ],
		'actions'	=> 'Actions'
	],
	'gridCards' => [ 'root' => 'col col12', 'factor' => 'x3' ],
	'templateDir' => '@themes/admin/views/templates/widget/grid',
	//'dataView' => "$moduleTemplates/grid/data/category",
	//'cardView' => "$moduleTemplates/grid/cards/category",
	//'actionView' => "$moduleTemplates/grid/actions/category"
]) ?>

<?= Popup::widget([
	'title' => 'Apply Bulk Action', 'size' => 'medium',
	'templateDir' => Yii::getAlias( '@themes/admin/views/templates/widget/popup/grid' ), 'template' => 'bulk',
	'data' => [ 'model' => 'Category', 'app' => 'main', 'controller' => 'crud', 'action' => 'bulk', 'url' => "cms/category/bulk" ]
]) ?>

<?= Popup::widget([
	'title' => 'Delete Category', 'size' => 'medium',
	'templateDir' => Yii::getAlias( '@themes/admin/views/templates/widget/popup/grid' ), 'template' => 'delete',
	'data' => [ 'model' => 'Category', 'app' => 'main', 'controller' => 'crud', 'action' => 'delete', 'url' => "cms/category/delete?id=" ]
]) ?>
