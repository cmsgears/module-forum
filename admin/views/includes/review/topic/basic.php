<?php
// CMG Imports
use cmsgears\icons\widgets\IconChooser;
?>
<div class="box box-crud">
	<div class="box-header">
		<div class="box-header-title">Basic Details</div>
	</div>
	<div class="box-content-wrap frm-split-40-60">
		<div class="box-content">
			<div class="row">
				<div class="col col3">
					<?= $form->field( $model, 'name' )->textInput( [ 'readonly' => 'true' ] ) ?>
				</div>
				<div class="col col3">
					<?= $form->field( $model, 'slug' )->textInput( [ 'readonly' => 'true' ] ) ?>
				</div>
				<div class="col col3">
					<?= $form->field( $model, 'title' )->textInput( [ 'readonly' => 'true' ] ) ?>
				</div>
			</div>
			<div class="row">
				<div class="col col3">
					<?= $form->field( $content, 'templateId' )->dropDownList( $templatesMap, [ 'class' => 'cmt-select', 'disabled' => true ] ) ?>
				</div>
				<div class="col col3">
					<?= $form->field( $model, 'status' )->dropDownList( $statusMap, [ 'class' => 'cmt-select', 'disabled' => true ] ) ?>
				</div>
				<div class="col col3">
					<?= $form->field( $model, 'visibility' )->dropDownList( $visibilityMap, [ 'class' => 'cmt-select', 'disabled' => true ] ) ?>
				</div>
			</div>
			<div class="row">
				<div class="col col2">
					<?= Yii::$app->formDesigner->getIconCheckbox( $form, $model, 'pinned', [ 'disabled' => true ], 'cmti cmti-checkbox' ) ?>
				</div>
				<div class="col col2">
					<?= Yii::$app->formDesigner->getIconCheckbox( $form, $model, 'featured', [ 'disabled' => true ], 'cmti cmti-checkbox' ) ?>
				</div>
			</div>
			<div class="row">
				<div class="col col2">
					<?= IconChooser::widget( [ 'model' => $model, 'options' => [ 'class' => 'icon-picker-wrap', 'disabled' => true ] ] ) ?>
				</div>
				<div class="col col2">
					<?= $form->field( $model, 'description' )->textarea( [ 'readonly' => 'true' ] ) ?>
				</div>
			</div>
			<div class="row">
				<div class="col col2">
					<?= $form->field( $model, 'order' )->textInput( [ 'readonly' => 'true' ] ) ?>
				</div>
			</div>
		</div>
	</div>
</div>
