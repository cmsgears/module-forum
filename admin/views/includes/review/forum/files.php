<?php
// CMG Imports
use cmsgears\files\widgets\AvatarUploader;
use cmsgears\files\widgets\ImageUploader;
use cmsgears\files\widgets\VideoUploader;

use cmsgears\core\common\utilities\CodeGenUtil;
?>
<div class="box box-crud">
	<div class="box-header">
		<div class="box-header-title">Files</div>
	</div>
	<div class="box-content">
		<div class="row padding padding-small-v">
			<div class="col col3">
				<label>Avatar</label>
				<?= AvatarUploader::widget( [ 'model' => $avatar, 'disabled' => 'true' ] ) ?>
			</div>
			<div class="col col3">
				<label>Banner</label>
				<?= ImageUploader::widget( [ 'model' => $banner, 'disabled' => 'true' ] ) ?>
			</div>
			<div class="col col3">
				<label>Video</label>
				<?= VideoUploader::widget( [ 'model' => $video, 'disabled' => 'true' ] ) ?>
			</div>
		</div>
	</div>
</div>
<?php if( count( $galleryFiles ) > 0 ) { ?>
<div class="filler-height filler-height-small"></div>
<div class="box box-crud">
	<div class="box-header">
		<div class="box-header-title">Gallery</div>
	</div>
	<div class="box-content">
		<div class="cmt-slider slider slider-basic margin margin-small-v">
			<?php
				foreach( $galleryFiles as $galleryFile ) {

					$file = $galleryFile->model;
			?>
				<div class="slide-data" thumb-url="<?= $file->getThumbUrl() ?>" image-url="<?= $file->getFileUrl() ?>">
					<div class="slide-title"><?= $file->title ?></div>
					<div class="slide-image bkg-image" style="background-image:url(<?= CodeGenUtil::getThumbUrl( $file ) ?>)"></div>
				</div>
			<?php } ?>
		</div>
	</div>
</div>
<?php } ?>
