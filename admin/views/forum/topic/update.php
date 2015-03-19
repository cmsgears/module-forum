<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . ' | Update Topic';
?>
<section class="wrap-content container clearfix">
	<div class="cud-box">
		<h2>Update Topic</h2>
		<?php $form = ActiveForm::begin( ['id' => 'frm-topic-update', 'options' => ['class' => 'frm-split' ] ] );?>

    	<?= $form->field( $model, 'topic_name' ) ?>
    	<?= $form->field( $model, 'topic_desc' )->textarea() ?>

		<div class="box-filler"></div>

		<?=Html::a( "Back", [ '/cmgforum/forum/view?fid='. $model->getForumId() ], ['class' => 'btn' ] );?>
		<input type="submit" value="Update" />

		<?php ActiveForm::end(); ?>
	</div>
</section>

<script type="text/javascript">
	initSidebar( "sidebar-forum", -1 );
</script>