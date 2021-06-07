<?php
// CMG Imports
use cmsgears\widgets\popup\Popup;

use cmsgears\widgets\grid\DataGrid;

$coreProperties = $this->context->getCoreProperties();
$this->title	= 'Topic Replies | ' . $coreProperties->getSiteTitle();
$apixBase		= $this->context->apixBase;

// View Templates
$moduleTemplates	= '@cmsgears/module-bank/admin/views/templates';
$themeTemplates		= '@themes/admin/views/templates';
?>
<?= DataGrid::widget([
	'dataProvider' => $dataProvider, 'add' => true, 'addUrl' => "create?tid=$topic->id", 'data' => [],
	'title' => 'Topic Replies', 'options' => [ 'class' => 'grid-data grid-data-admin' ],
	'searchColumns' => [
		'user' => 'User', 'title' => 'Title', 'name' => 'Name',
		'email' => 'Email', 'content' => 'Content'
	],
	'sortColumns' => [
		'user' => 'User', 'title' => 'Title', 'name' => 'Name', 'email' => 'Email',
		'status' => 'Status', 'score' => 'Score',
		'pinned' => 'Pinned', 'featured' => 'Featured', 'popular' => 'Popular',
		'cdate' => 'Created At', 'udate' => 'Updated At', 'adate' => 'Approved At'
	],
	'filters' => [
		'status' => [
			'new' => 'New', 'spam' => 'Spam', 'blocked' => 'Blocked',
			'approved' => 'Approved', 'trash' => 'Trash'
		],
		'model' => [
			'pinned' => 'Pinned', 'featured' => 'Featured', 'popular' => 'Popular'
		]
	],
	'reportColumns' => [
		'user' => [ 'title' => 'User', 'type' => 'text' ],
		'name' => [ 'title' => 'Name', 'type' => 'text' ],
		'title' => [ 'title' => 'Title', 'type' => 'text' ],
		'email' => [ 'title' => 'Email', 'type' => 'text' ],
		'content' => [ 'title' => 'Content', 'type' => 'text' ],
		'status' => [ 'title' => 'Status', 'type' => 'select', 'options' => $statusMap ],
		'pinned' => [ 'title' => 'Pinned', 'type' => 'flag' ],
		'featured' => [ 'title' => 'Featured', 'type' => 'flag' ],
		'popular' => [ 'title' => 'Popular', 'type' => 'flag' ]
	],
	'bulkPopup' => 'popup-grid-bulk', 'bulkActions' => [
		'status' => [
			'approve' => 'Approve', 'spam' => 'Spam', 'trash' => 'Trash', 'block' => 'Block'
		],
		'model' => [
			'pinned' => 'Pinned', 'featured' => 'Featured', 'popular' => 'Popular',
			'delete' => 'Delete'
		]
	],
	'header' => false, 'footer' => true,
	'grid' => true, 'columns' => [ 'root' => 'colf colf15', 'factor' => [ null, 'x2', 'x2', 'x2', 'x2', null, null, null, null, 'x2' ] ],
	'gridColumns' => [
		'bulk' => 'Action',
		'title' => 'Title',
		'user' => [ 'title' => 'User', 'generate' => function( $model ) {
			return isset( $model->user ) ? $model->user->name : null;
		}],
		'name' => 'Name',
		'email' => 'Email',
		'status' => [ 'title' => 'Status', 'generate' => function( $model ) { return $model->getStatusStr(); } ],
		'pinned' => [ 'title' => 'Pinned', 'generate' => function( $model ) { return $model->getPinnedStr(); } ],
		'featured' => [ 'title' => 'Featured', 'generate' => function( $model ) { return $model->getFeaturedStr(); } ],
		'popular' => [ 'title' => 'Popular', 'generate' => function( $model ) { return $model->getPopularStr(); } ],
		'actions' => 'Actions'
	],
	'gridCards' => [ 'root' => 'col col12', 'factor' => 'x3' ],
	'templateDir' => "$themeTemplates/widget/grid",
	//'dataView' => "$moduleTemplates/grid/data/reply",
	//'cardView' => "$moduleTemplates/grid/cards/reply",
	//'actionView' => "$moduleTemplates/grid/actions/reply"
]) ?>

<?= Popup::widget([
	'title' => 'Apply Bulk Action', 'size' => 'medium',
	'templateDir' => Yii::getAlias( "$themeTemplates/widget/popup/grid" ), 'template' => 'bulk',
	'data' => [ 'model' => 'Topic Reply', 'app' => 'grid', 'controller' => 'crud', 'action' => 'bulk', 'url' => "$apixBase/bulk" ]
])?>

<?= Popup::widget([
	'title' => "Delete Topic Reply", 'size' => 'medium',
	'templateDir' => Yii::getAlias( "$themeTemplates/widget/popup/grid" ), 'template' => 'delete',
	'data' => [ 'model' => 'Topic Reply', 'app' => 'grid', 'controller' => 'crud', 'action' => 'delete', 'url' => "$apixBase/delete?id=" ]
])?>
