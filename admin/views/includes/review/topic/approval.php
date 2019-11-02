<?php
// Yii Imports
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<?php if( $model->isNew() || $model->isSubmitted() || $model->isReSubmit() ) { ?>
	<h5>Review <?= $title ?></h5> <hr />
	<?php $form = ActiveForm::begin( [ 'id' => 'frm-approval' ] ); ?>
	<?= $form->field( $model, 'name' )->hiddenInput()->label( false ) ?>
	<div class="align align-center">
		<?php if( $model->isNew() ) { ?>
			<input type="radio" name="status" value="<?= $modelClass::STATUS_ACCEPTED ?>" checked>Accept &nbsp;&nbsp;
		<?php } else if( $model->isSubmitted() ) { ?>
			<input type="radio" name="status" value="<?= $modelClass::STATUS_SUBMITTED ?>" checked>Approve &nbsp;&nbsp;
		<?php } else { ?>
			<input type="radio" name="status" value="<?= $modelClass::STATUS_ACTIVE ?>" checked>Approve &nbsp;&nbsp;
		<?php } ?>
		<input type="radio" name="status" value="<?= $modelClass::STATUS_REJECTED ?>">Reject
	</div>
	<div class="filler-height"></div>
	<div class="content-80">
		<textarea name="message" placeholder="Add cause of rejection ..."></textarea>
	</div>
	<div class="clear filler-height"></div>
	<div class="align align-center">
		<?= Html::a( 'Back', $returnUrl, [ 'class' => 'btn btn-medium' ] ); ?>
		<input class="frm-element-medium" type="submit" value="Submit" />
	</div>
	<?php ActiveForm::end(); ?>
<?php } else if( $model->isApprovable() ) { ?>
	<h5>Review <?= $title ?></h5> <hr />
	<?php $form = ActiveForm::begin( [ 'id' => 'frm-approval' ] ); ?>
	<?= $form->field( $model, 'name' )->hiddenInput()->label( false ) ?>
	<div class="align align-center">
		<input type="radio" name="status" value="<?= $modelClass::STATUS_ACTIVE ?>" checked>Approve
	</div>
	<div class="clear filler-height"></div>
	<div class="align align-center">
		<?= Html::a( 'Back', $returnUrl, [ 'class' => 'btn btn-medium' ] ); ?>
		<input class="frm-element-medium" type="submit" value="Submit" />
	</div>
	<?php ActiveForm::end(); ?>
<?php } else if( $model->isActive() ) { ?>
	<?php $form = ActiveForm::begin( [ 'id' => 'frm-approval' ] ); ?>
	<?= $form->field( $model, 'name' )->hiddenInput()->label( false ) ?>
	<div class="align align-center">
		<input type="radio" name="status" value="<?= $modelClass::STATUS_FROJEN ?>" checked>Freeze &nbsp;&nbsp;
		<input type="radio" name="status" value="<?= $modelClass::STATUS_BLOCKED ?>">Block
	</div>
	<div class="filler-height"></div>
	<div class="content-80">
		<textarea name="message" placeholder="Add cause ..."></textarea>
	</div>
	<div class="clear filler-height"></div>
	<div class="align align-center">
		<?= Html::a( 'Back', $returnUrl, [ 'class' => 'btn btn-medium' ] ); ?>
		<input class="frm-element-medium" type="submit" value="Submit" />
	</div>
	<?php ActiveForm::end(); ?>
<?php } else { ?>
	<div class="align align-center">
		<?= Html::a( 'Back', $returnUrl, [ 'class' => 'btn btn-medium' ] ); ?>
	</div>
<?php } ?>
