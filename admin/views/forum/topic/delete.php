<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . ' | Delete Topic';
?>
<section class="wrap-content container clearfix">
	<div class="cud-box">
		<h2>Delete Topic</h2>
		<?php $form = ActiveForm::begin( ['id' => 'frm-topic-delete', 'options' => ['class' => 'frm-split' ] ] );?>

    	<?= $form->field( $model, 'topic_name' )->textInput( [ 'disabled'=>'true' ] ) ?>
    	<?= $form->field( $model, 'topic_desc' )->textarea( [ 'disabled'=>'true' ] ) ?>

		<div class="box-filler"></div>

		<?=Html::a( "Cancel", [ '/cmgforum/forum/view?fid='. $model->getForumId() ], ['class' => 'btn' ] );?>
		<input type="submit" value="Delete" />

		<?php ActiveForm::end(); ?>
	</div>
</section>

<script type="text/javascript">
	initSidebar( "sidebar-forum", -1 );
</script>