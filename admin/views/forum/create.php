<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . ' | Add Forum';
?>
<section class="wrap-content container clearfix">
	<div class="cud-box">
		<h2>Add Forum</h2>
		<?php $form = ActiveForm::begin( ['id' => 'frm-forum-create', 'options' => ['class' => 'frm-split' ] ] );?>

    	<?= $form->field( $model, 'forum_name' ) ?>
    	<?= $form->field( $model, 'forum_desc' )->textarea() ?>

    	<h4>Forum Banner</h4>
		<div id="file-banner" class="file-container" legend="Forum Banner" selector="banner" utype="image" btn-class="btn file-input-wrap" btn-text="Choose Banner">
			<div class="file-fields">
				<input type="hidden" name="File[file_name]" class="file-name" value="<?php if( isset( $banner ) ) echo $banner->getName(); ?>" />
				<input type="hidden" name="File[file_extension]" class="file-extension" value="<?php if( isset( $banner ) ) echo $banner->getExtension(); ?>" />
				<input type="hidden" name="File[file_directory]" value="banner" value="<?php if( isset( $banner ) ) echo $banner->getDirectory(); ?>" />
				<input type="hidden" name="File[changed]" class="file-change" value="<?php if( isset( $banner ) ) echo $banner->changed; ?>" />
				<label>Banner Description</label> <input type="text" name="File[file_desc]" value="<?php if( isset( $banner ) ) echo $banner->getDesc(); ?>" />
				<label>Banner Alternate Text</label> <input type="text" name="File[file_alt_text]" value="<?php if( isset( $banner ) ) echo $banner->getAltText(); ?>" />
			</div>
		</div>

		<h4>Assign Categories</h4>
		<?php foreach ( $categories as $category ) { ?>
			<span class="box-half"><input type="checkbox" name="Binder[bindedData][]" value="<?=$category['id']?>" /><?=$category['name']?></span>
		<?php } ?>
		<div class="box-filler"></div>

		<?=Html::a( "Cancel", [ '/cmgforum/forum/all' ], ['class' => 'btn' ] );?>
		<input type="submit" value="Create" />

		<?php ActiveForm::end(); ?>
	</div>
</section>

<script type="text/javascript">
	initSidebar( "sidebar-forum", 3 );
	initFileUploader();

	<?php if( isset( $banner ) ) { ?>
		jQuery("#file-banner .file-image").html( "<img src='<?php echo Yii::$app->fileManager->uploadUrl . $banner->getDisplayUrl(); ?>' />'" );
	<?php } ?>
</script>