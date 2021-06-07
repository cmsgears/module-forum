<?php
// Yii Imports
use yii\helpers\Html;

// CMG Imports
use cmsgears\core\common\widgets\ActiveForm;

use cmsgears\core\common\widgets\Editor;
use cmsgears\files\widgets\AvatarUploader;
use cmsgears\files\widgets\ImageUploader;
use cmsgears\files\widgets\VideoUploader;

use cmsgears\icons\widgets\IconChooser;
use cmsgears\icons\widgets\TextureChooser;

use cmsgears\widgets\category\CategorySuggest;
use cmsgears\widgets\tag\TagMapper;
use cmsgears\widgets\elements\mappers\ElementSuggest;
use cmsgears\widgets\elements\mappers\BlockSuggest;
use cmsgears\widgets\elements\mappers\WidgetSuggest;

$coreProperties = $this->context->getCoreProperties();
$title			= $this->context->title;
$this->title 	= "Update {$title} | " . $coreProperties->getSiteTitle();
$parentType		= $this->context->parentType;
$returnUrl		= $this->context->returnUrl;
$apixBase		= $this->context->apixBase;
$tagWidgetSlug	= $this->context->tagWidgetSlug;

Editor::widget();

$forumName = isset( $model->forum ) ? $model->forum->name : null;
?>
<div class="box-crud-wrap row max-cols-100">
	<div class="box-crud-wrap-main colf colf3x2">
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-topic', 'options' => [ 'class' => 'form' ] ] ); ?>
		<div class="box box-crud">
			<div class="box-header">
				<div class="box-header-title">Basic Details</div>
			</div>
			<div class="box-content-wrap frm-split-40-60">
				<div class="box-content">
					<div class="row max-cols-100">
						<div class="col col3">
							<?= $form->field( $model, 'name' ) ?>
						</div>
						<div class="col col3">
							<?= $form->field( $model, 'slug' ) ?>
						</div>
						<div class="col col3">
							<?= $form->field( $model, 'title' ) ?>
						</div>
					</div>
					<div class="row max-cols-100">
						<div class="col col2">
							<?= $form->field( $content, 'templateId' )->dropDownList( $templatesMap, [ 'class' => 'cmt-select' ] ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'description' )->textarea() ?>
						</div>
					</div>
					<div class="row max-cols-100">
						<div class="col col2">
							<?= $form->field( $model, 'status' )->dropDownList( $statusMap, [ 'class' => 'cmt-select' ] ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'visibility' )->dropDownList( $visibilityMap, [ 'class' => 'cmt-select' ] ) ?>
						</div>
					</div>
					<div class="row max-cols-100">
						<div class="col col3">
							<?= Yii::$app->formDesigner->getIconCheckbox( $form, $model, 'pinned', null, 'cmti cmti-checkbox' ) ?>
						</div>
						<div class="col col3">
							<?= Yii::$app->formDesigner->getIconCheckbox( $form, $model, 'featured', null, 'cmti cmti-checkbox' ) ?>
						</div>
						<div class="col col3">
							<?= Yii::$app->formDesigner->getIconCheckbox( $form, $model, 'popular', null, 'cmti cmti-checkbox' ) ?>
						</div>
					</div>
					<div class="row max-cols-100">
						<div class="col col2">
							<?= IconChooser::widget( [ 'model' => $model, 'options' => [ 'class' => 'icon-picker-wrap' ] ] ) ?>
						</div>
						<div class="col col2">
							<?= TextureChooser::widget( [ 'model' => $model, 'options' => [ 'class' => 'icon-picker-wrap' ] ] ) ?>
						</div>
					</div>
					<div class="row max-cols-100">
						<div class="col col2">
							<?= $form->field( $model, 'order' ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $content, 'publishedAt' )->textInput( [ 'class' => 'datetimepicker' ] ) ?>
						</div>
					</div>
					<div class="row max-cols-100">
						<?php if( empty( $forum ) ) { ?>
							<div class="col col2">
								<?= Yii::$app->formDesigner->getAutoSuggest( $form, $model, 'forumId', [ 'placeholder' => 'Forum', 'icon' => 'cmti cmti-search', 'value' => $forumName, 'url' => 'forum/forum/auto-search' ] ) ?>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
		<div class="filler-height filler-height-medium"></div>
		<div class="box box-crud">
			<div class="box-header">
				<div class="box-header-title">Files</div>
			</div>
			<div class="box-content">
				<div class="box-content">
					<div class="row max-cols-50 padding padding-small-v">
						<div class="col col12x3">
							<label>Avatar</label>
							<?= AvatarUploader::widget([
								'model' => $avatar, 'clearAction' => true,
								'clearActionUrl' => "$apixBase/clear-avatar?slug=$model->slug&type=$model->type"
							])?>
						</div>
						<div class="col col12x3">
							<label>Banner</label>
							<?= ImageUploader::widget([
								'model' => $banner, 'clearAction' => true,
								'clearActionUrl' => "$apixBase/clear-banner?slug=$model->slug&type=$model->type"
							])?>
						</div>
						<div class="col col12x3">
							<label>Mobile Banner</label>
							<?= ImageUploader::widget([
								'model' => $mbanner, 'modelClass' => 'MobileBanner', 'clearAction' => true,
								'clearActionUrl' => "$apixBase/clear-mbanner?slug=$model->slug&type=$model->type"
							])?>
						</div>
						<div class="col col12x3">
							<label>Video</label>
							<?= VideoUploader::widget([
								'model' => $video, 'clearAction' => true,
								'clearActionUrl' => "$apixBase/clear-video?slug=$model->slug&type=$model->type"
							])?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="filler-height filler-height-medium"></div>
		<div class="box box-crud">
			<div class="box-header">
				<div class="box-header-title">Summary</div>
			</div>
			<div class="box-content-wysiwyg">
				<div class="box-content">
					<?= $form->field( $content, 'summary' )->textarea( [ 'class' => 'content-editor' ] )->label( false ) ?>
				</div>
			</div>
		</div>
		<div class="filler-height filler-height-medium"></div>
		<div class="box box-crud">
			<div class="box-header">
				<div class="box-header-title">Content</div>
			</div>
			<div class="box-content-wysiwyg">
				<div class="box-content">
					<?= $form->field( $content, 'content' )->textarea( [ 'class' => 'content-editor' ] )->label( false ) ?>
				</div>
			</div>
		</div>
		<div class="filler-height filler-height-medium"></div>
		<div class="box box-crud">
			<div class="box-header">
				<div class="box-header-title">Page SEO</div>
			</div>
			<div class="box-content">
				<div class="box-content">
					<div class="row max-cols-100">
						<div class="col col2">
							<?= $form->field( $content, 'seoName' ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $content, 'seoRobot' ) ?>
						</div>
					</div>
					<div class="row max-cols-100">
						<div class="col col2">
							<?= $form->field( $content, 'seoKeywords' )->textarea() ?>
						</div>
						<div class="col col2">
							<?= $form->field( $content, 'seoDescription' )->textarea() ?>
						</div>
					</div>
					<div class="row max-cols-100">
						<div class="col col1">
							<?= $form->field( $content, 'seoSchema' )->textarea() ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="filler-height filler-height-medium"></div>
		<div class="align align-right">
			<?= Html::a( 'View All', $returnUrl, [ 'class' => 'btn btn-medium' ] ); ?>
			<input class="frm-element-medium" type="submit" value="Update" />
		</div>
		<div class="filler-height filler-height-medium"></div>
		<?php ActiveForm::end(); ?>
		<div class="row max-cols-100">
			<div class="box box-crud colf colf15x7">
				<div class="box-header">
					<div class="box-header-title">Categories</div>
				</div>
				<div class="box-content padding padding-small">
					<?= CategorySuggest::widget([
						'model' => $model, 'type' => $parentType,
						'mapActionUrl' => "$apixBase/assign-category?slug=$model->slug&type=$model->type",
						'deleteActionUrl' => "$apixBase/remove-category?slug=$model->slug&type=$model->type"
					])?>
				</div>
			</div>
			<div class="colf colf15"></div>
			<div class="box box-crud colf colf15x7">
				<div class="box-header">
					<div class="box-header-title">Tags</div>
				</div>
				<div class="box-content padding padding-small">
					<?= TagMapper::widget([
						'model' => $model, 'widgetSlug' => $tagWidgetSlug, 'templateId' => $tagTemplateId,
						'mapActionUrl' => "$apixBase/assign-tags?slug=$model->slug&type=$model->type",
						'deleteActionUrl' => "$apixBase/remove-tag?slug=$model->slug&type=$model->type"
					])?>
				</div>
			</div>
		</div>
		<div class="filler-height filler-height-medium"></div>
		<div class="row max-cols-100">
			<div class="box box-crud colf colf15x7">
				<div class="box-header">
					<div class="box-header-title">Elements</div>
				</div>
				<div class="box-content padding padding-small">
					<?= ElementSuggest::widget([
						'model' => $model,
						'mapActionUrl' => "$apixBase/assign-element?slug=$model->slug&type=$model->type",
						'deleteActionUrl' => "$apixBase/remove-element?slug=$model->slug&type=$model->type"
					])?>
				</div>
			</div>
			<div class="colf colf15"> </div>
			<div class="box box-crud colf colf15x7">
				<div class="box-header">
					<div class="box-header-title">Blocks</div>
				</div>
				<div class="box-content padding padding-small">
					<?= BlockSuggest::widget([
						'model' => $model,
						'mapActionUrl' => "$apixBase/assign-block?slug=$model->slug&type=$model->type",
						'deleteActionUrl' => "$apixBase/remove-block?slug=$model->slug&type=$model->type"
					])?>
				</div>
			</div>
		</div>
		<div class="filler-height filler-height-medium"></div>
		<div class="row max-cols-100">
			<div class="box box-crud colf colf15x7">
				<div class="box-header">
					<div class="box-header-title">Widgets</div>
				</div>
				<div class="box-content padding padding-small">
					<?= WidgetSuggest::widget([
						'model' => $model,
						'mapActionUrl' => "$apixBase/assign-widget?slug=$model->slug&type=$model->type",
						'deleteActionUrl' => "$apixBase/remove-widget?slug=$model->slug&type=$model->type"
					])?>
				</div>
			</div>
		</div>
		<div class="filler-height filler-height-medium"></div>
	</div>
	<div class="box-crud-wrap-sidebar colf colf3"></div>
</div>
