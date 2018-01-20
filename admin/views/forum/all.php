<?php
use \Yii;
use yii\helpers\Html; 
use yii\widgets\LinkPager;

use cmsgears\modules\core\common\utilities\CodeGenUtil;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . ' | All Forums';

// Searching
$searchTerms	= Yii::$app->request->getQueryParam( "search" );

// Sorting
$sortOrder		= Yii::$app->request->getQueryParam( "sort" );

if( !isset( $sortOrder ) ) {

	$sortOrder	= '';
}
?>
<div class="content-header clearfix">
	<div class="header-actions"> 
		<?= Html::a( "Add Forum", ["/cmgforum/forum/create"], ['class'=>'btn'] )  ?>				
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
					<th><input type='checkbox' /></th>
					<th>Banner</th>
					<th>Name
						<span class='box-icon-sort'>
							<span sort-order='name' class="icon-sort <?php if( strcmp( $sortOrder, 'name') == 0 ) echo 'icon-up-active'; else echo 'icon-up';?>"></span>
							<span sort-order='-name' class="icon-sort <?php if( strcmp( $sortOrder, '-name') == 0 ) echo 'icon-down-active'; else echo 'icon-down';?>"></span>
						</span>
					</th>
					<th>Slug</th>
					<th>Description</th>
					<th>Owner</th>
					<th>Created On
						<span class='box-icon-sort'>
							<span sort-order='cdate' class="icon-sort <?php if( strcmp( $sortOrder, 'cdate') == 0 ) echo 'icon-up-active'; else echo 'icon-up';?>"></span>
							<span sort-order='-cdate' class="icon-sort <?php if( strcmp( $sortOrder, '-cdate') == 0 ) echo 'icon-down-active'; else echo 'icon-down';?>"></span>
						</span>
					</th>
					<th>Updated On
						<span class='box-icon-sort'>
							<span sort-order='udate' class="icon-sort <?php if( strcmp( $sortOrder, 'udate') == 0 ) echo 'icon-up-active'; else echo 'icon-up';?>"></span>
							<span sort-order='-udate' class="icon-sort <?php if( strcmp( $sortOrder, '-udate') == 0 ) echo 'icon-down-active'; else echo 'icon-down';?>"></span>
						</span>
					</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php
					
					$uploadUrl	= Yii::$app->fileManager->uploadUrl;

					foreach( $page as $forum ) {

						$id 	= $forum->getId();
						$user	= $forum->owner;
				?>
					<tr>
						<td><input type='checkbox' /></td>
						<td> 
							<?php
								$banner = $forum->banner;

								if( isset( $banner ) ) { 
							?> 
								<img class="avatar" src="<?=$uploadUrl?><?= $banner->getThumb() ?>">
							<?php 
								} else { 
							?>
								<img class="avatar" src="<?=Yii::getAlias('@web')?>/assets/images/avatar.png">
							<?php } ?>
						</td>
						<td><?= $forum->getName() ?></td>
						<td><?= $forum->getSlug() ?></td>
						<td><?= $forum->getDesc() ?></td>
						<td><?= $user->getName() ?></td>
						<td><?= $forum->getCreatedOn() ?></td>
						<td><?= $forum->getUpdatedOn() ?></td>
						<td>
							<span class="wrap-icon-action" title="View Forum"><?= Html::a( "", ["/cmgforum/forum/view?fid=$id"], ['class'=>'icon-action icon-action-view'] )  ?></span>
							<span class="wrap-icon-action" title="Update Forum"><?= Html::a( "", ["/cmgforum/forum/update?id=$id"], ['class'=>'icon-action icon-action-edit'] )  ?></span>
							<span class="wrap-icon-action" title="Delete Forum"><?= Html::a( "", ["/cmgforum/forum/delete?id=$id"], ['class'=>'icon-action icon-action-delete'] )  ?></span>
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
	initSidebar( "sidebar-forum", 2 );
</script>