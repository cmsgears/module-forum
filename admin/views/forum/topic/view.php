<?php
use \Yii;
use yii\helpers\Html; 
use yii\widgets\LinkPager;

use cmsgears\modules\core\common\utilities\CodeGenUtil;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . " | Forum Topics";
$fid			= $forum->getId();

// Searching
$searchTerms	= Yii::$app->request->getQueryParam("search");

// Sorting
$sortOrder		= Yii::$app->request->getQueryParam("sort");

if( !isset( $sortOrder ) ) {

	$sortOrder	= '';
}
?>
<div class="cud-box">
	<h2>Forum Topics</h2>
	<form action="#" class="frm-split">
		<label>Name</label>
		<label><?= $forum->getName() ?></label>
		<label>Description</label>
		<label><?= $forum->getDesc() ?></label>			
	</form>
</div>
<div class="content-header clearfix">
	<div class="header-actions"> 
		<?= Html::a( "Add Topic", ["/cmgforum/forum/create-topic?fid=$fid"], ['class'=>'btn'] )  ?>				
	</div>
	<div class="header-search">
		<input type="text" name="search" id="search-terms" value="<?php if( isset($searchTerms) ) echo $searchTerms;?>">
		<input type="submit" name="submit-search" value="Search" onclick="return searchTable();" />
	</div>
</div>
<div class="data-grid">
	<div class="grid-header">
		<?= LinkPager::widget( [ 'pagination' => $pages ] ); ?>
	</div>
	<div class="wrap-grid">
		<table>
			<thead>
				<tr>
					<th> <input type='checkbox' /> </th>
					<th>Topic Name
						<span class='box-icon-sort'>
							<span sort-order='name' class="icon-sort <?php if( strcmp( $sortOrder, 'name') == 0 ) echo 'icon-up-active'; else echo 'icon-up';?>"></span>
							<span sort-order='-name' class="icon-sort <?php if( strcmp( $sortOrder, '-name') == 0 ) echo 'icon-down-active'; else echo 'icon-down';?>"></span>
						</span>
					</th>				
					<th>Description</th>
					<th>Topic Owner
						<span class='box-icon-sort'>
							<span sort-order='owner' class="icon-sort <?php if( strcmp( $sortOrder, 'owner') == 0 ) echo 'icon-up-active'; else echo 'icon-up';?>"></span>
							<span sort-order='-owner' class="icon-sort <?php if( strcmp( $sortOrder, '-owner') == 0 ) echo 'icon-down-active'; else echo 'icon-down';?>"></span>
						</span>
					</th>
					<th>Created On
						<span class='box-icon-sort'>
							<span sort-order='cdate' class="icon-sort <?php if( strcmp( $sortOrder, 'cdate') == 0 ) echo 'icon-up-active'; else echo 'icon-up';?>"></span>
							<span sort-order='-cdate' class="icon-sort <?php if( strcmp( $sortOrder, '-cdate') == 0 ) echo 'icon-down-active'; else echo 'icon-down';?>"></span>
						</span>
					</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php

					foreach( $page as $topic ) {
						
						$id		= $topic->getId();
						$user  	= $topic->owner;
				?>
					<tr>
						<td> <input type='checkbox' /> </td>
						<td><?=$topic->getName()?></td>
						<td><?=$topic->getDesc()?></td>
						<td><?=$user->getName()?></td>
						<td><?=$topic->getCreatedOn()?></td>
						<td>
							<span class="wrap-icon-action"><?= Html::a( "", ["/cmgforum/forum/view-topic?tid=$id"], ['class'=>'icon-action icon-action-view'] )  ?></span>
							<span class="wrap-icon-action" title="Update Topic"><?= Html::a( "", ["/cmgforum/forum/update-topic?fid=$fid&id=$id"], ['class'=>'icon-action icon-action-update'] )  ?></span>
							<span class="wrap-icon-action" title="Delete Topic"><?= Html::a( "", ["/cmgforum/forum/delete-topic?fid=$fid&id=$id"], ['class'=>'icon-action icon-action-delete'] )  ?></span>
						</td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
	<div class="grid-footer">
		<div class="text"> <?=CodeGenUtil::getPaginationDetail( $pages, $page, $total ) ?> </div>
		<?= LinkPager::widget( [ 'pagination' => $pages ] ); ?>
	</div>
</div>
<script type="text/javascript">
	initSidebar( "sidebar-forum", -1 );
</script>