<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . ' | Delete Reply';
?>
<section class="wrap-content container clearfix">
	<div class="cud-box">
		<h2>Delete Reply</h2>
		<?php $form = ActiveForm::begin( ['id' => 'frm-reply-delete', 'options' => ['class' => 'frm-split' ] ] );?>

    	<?= $form->field( $model, 'reply_content' )->textarea( [ 'disabled'=>'true' ] ) ?>

		<div class="box-filler"></div>

		<?=Html::a( "Cancel", [ '/cmgforum/forum/view-topic?tid=' . $model->getTopicId() ], ['class' => 'btn' ] );?>
		<input type="submit" value="Delete" />

		<?php ActiveForm::end(); ?>
	</div>
</section>

<script type="text/javascript">
	initSidebar( "sidebar-forum", -1 );
</script>