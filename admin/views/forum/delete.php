<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . ' | Delete Forum';
?>
<section class="wrap-content container clearfix">
	<div class="cud-box">
		<h2>Delete Forum</h2>
		<?php $form = ActiveForm::begin( ['id' => 'frm-forum-delete', 'options' => ['class' => 'frm-split' ] ] );?>

    	<?= $form->field( $model, 'forum_name' )->textInput( [ 'disabled'=>'true' ] ) ?>
    	<?= $form->field( $model, 'forum_desc' )->textarea( [ 'disabled'=>'true' ] ) ?>

    	<h4>Forum Banner</h4>
		<div id="file-banner" class="file-container" legend="Forum Banner" selector="banner" utype="image" btn-class="btn file-input-wrap" btn-text="Change Banner"></div>

		<h4>Assign Categories</h4>
		<?php 
			$postCategories	= $model->getCategoriesIdList();

			foreach ( $categories as $category ) { 

				if( in_array( $category['id'], $postCategories ) ) {
		?>		
					<span class="box-half"><input type="checkbox" name="Binder[bindedData][]" value="<?=$category['id']?>" checked /><?=$category['name']?></span>
		<?php 
				}
				else {
		?>
					<span class="box-half"><input type="checkbox" name="Binder[bindedData][]" value="<?=$category['id']?>" /><?=$category['name']?></span>
		<?php
				}
			}
		?>
		<div class="box-filler"></div>

		<?=Html::a( "Cancel", [ '/cmgforum/forum/all' ], ['class' => 'btn' ] );?>
		<input type="submit" value="Delete" />

		<?php ActiveForm::end(); ?>
	</div>
</section>

<script type="text/javascript">
	initSidebar( "sidebar-forum", -1 );
	initFileUploader();

	<?php if( isset( $banner ) ) { ?>
		jQuery("#file-banner .file-image").html( "<img src='<?php echo Yii::$app->fileManager->uploadUrl . $banner->getDisplayUrl(); ?>' />'" );
	<?php } ?>
	
	jQuery( ".file-input").attr( "disabled", "true" );
</script>