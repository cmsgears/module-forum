<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . ' | Add Reply';
?>
<section class="wrap-content container clearfix">
	<div class="cud-box">
		<h2>Add Reply</h2>
		<?php $form = ActiveForm::begin( ['id' => 'frm-reply-create', 'options' => ['class' => 'frm-split' ] ] );?>

    	<?= $form->field( $model, 'reply_content' )->textarea() ?>

		<div class="box-filler"></div>

		<?=Html::a( "Cancel", [ '/cmgforum/forum/view-topic?tid=' . $topicId ], ['class' => 'btn' ] );?>
		<input type="submit" value="Create" />

		<?php ActiveForm::end(); ?>
	</div>
</section>

<script type="text/javascript">
	initSidebar( "sidebar-forum", -1 );
</script>