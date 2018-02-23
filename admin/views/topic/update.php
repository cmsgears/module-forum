<?php
// Yii Imports
use yii\widgets\ActiveForm;
use yii\helpers\Html;

// CMG Imports
use cmsgears\forum\common\config\ForumGlobal;

use cmsgears\core\common\widgets\Editor;

use cmsgears\widgets\category\CategoryAuto;
use cmsgears\widgets\tag\TagMapper;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= 'Update Topic | ' . $coreProperties->getSiteTitle();
$returnUrl		= $this->context->returnUrl;

$typeForum      = ForumGlobal::TYPE_FORUM;

Editor::widget( [ 'selector' => '.content-editor', 'loadAssets' => true, 'fonts' => 'site', 'config' => [ 'controls' => 'mini' ] ] );
?>
<div class="box-crud-wrap row">
	<div class="box-crud-wrap-main colf colf3x2">
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-block', 'options' => [ 'class' => 'form' ] ] ); ?>
		<div class="box box-crud">
			<div class="box-header">
				<div class="box-header-title">Basic Details</div>
			</div>
			<div class="box-content-wrap frm-split-40-60">
				<div class="box-content">
					<div class="row">
						<div class="col col2">
							<?= $form->field( $model, 'name' ) ?>
						</div>

						<div class="col col2">
							<?= $form->field( $model, 'title' ) ?>
						</div>
					</div>
					<div class="row">
						<div class="col col2">
							<?= $form->field( $model, 'description' )->textarea() ?>
						</div>
                        <div class="col col2">
							<?= $form->field( $model, 'status' )->dropDownList( $statusMap, [ 'class' => 'cmt-select' ] ) ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="box box-crud">
			<div class="box-header">
				<div class="box-header-title">Content</div>
			</div>
			<div class="box-content-wysiwyg">
				<div class="box-content">
					<?= $form->field( $model, 'content' )->textarea( [ 'class' => 'content-editor' ] )->label( false ) ?>
				</div>
			</div>
		</div>
		<div class="filler-height"> </div>

		<div class="filler-height filler-height-medium"></div>

		<div class="align align-right">
			<?= Html::a( 'Cancel', $returnUrl, [ 'class' => 'btn btn-medium' ] ); ?>
			<input class="element-medium" type="submit" value="Update" />
		</div>

		<div class="filler-height filler-height-medium"></div>
		<?php ActiveForm::end(); ?>
        
        <div class="row max-cols-100">
			<div class="box box-crud colf colf15x7">
				<div class="box-header">
					<div class="box-header-title">Categories</div>
				</div>
				<div class="box-content padding padding-small">
					<?= CategoryAuto::widget([
						'options' => [ 'class' => 'box-mapper-auto' ],
						'type' => $typeForum,
						'model' => $model, 'app' => 'category',
						'mapActionUrl' => "forum/topic/assign-category?slug=$model->slug&type=".$typeForum,
						'deleteActionUrl' => "forum/topic/remove-category?slug=$model->slug&type=".$typeForum
					]) ?>
				</div>
			</div>
			<div class="colf colf15"></div>
			<div class="box box-crud colf colf15x7">
				<div class="box-header">
					<div class="box-header-title">Tags</div>
				</div>
				<div class="box-content padding padding-small">
					<?= TagMapper::widget([
						'options' => [ 'id' => 'box-tag-mapper', 'class' => 'box-tag-mapper' ],
						'loadAssets' => true,
						'model' => $model, 'app' => 'category',
						'mapActionUrl' => "forum/topic/assign-tags?slug=$model->slug&type=".$typeForum,
						'deleteActionUrl' => "forum/topic/remove-tag?slug=$model->slug&type=".$typeForum
					])?>
				</div>
			</div>
		</div>
		<div class="filler-height filler-height-medium"></div>
	</div>
	<div class="box-crud-wrap-sidebar colf colf3">

	</div>
</div>
