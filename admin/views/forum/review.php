<?php
// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\widgets\ActiveForm;
use cmsgears\core\common\widgets\Editor;
use cmsgears\widgets\popup\Popup;

Editor::widget();

// Config -------------------------

$coreProperties = $this->context->getCoreProperties();
$siteProperties	= $this->context->getSiteProperties();
$title			= $this->context->title;
$this->title	= "Review {$title} | " . $coreProperties->getSiteTitle();
$returnUrl		= $this->context->returnUrl;
$apixBase		= $this->context->apixBase;
$modelContent	= $model->modelContent;

$reviewIncludes = Yii::getAlias( '@cmsgears' ) . '/module-forum/admin/views/includes/review';

// Services -----------------------

// Approval -----------------------

$modelClass	= $modelService->getModelClass();

// Basic --------------------------

// Attributes ---------------------

$metas = $model->getMetasByType( CoreGlobal::META_TYPE_USER );

// Files --------------------------

$gallery		= $modelContent->gallery;
$galleryFiles	= isset( $gallery ) ? $gallery->modelFiles : [];

// Settings -----------------------

?>
<div class="box-crud-wrap">
	<div class="box-crud-wrap-main margin margin-small">
		<div class="filler-height filler-height-medium"></div>
		<?php include "$reviewIncludes/forum/approval.php"; ?>
		<div class="filler-height filler-height-medium"></div>
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-forum', 'options' => [ 'class' => 'form' ] ] ); ?>
		<?php include "$reviewIncludes/forum/basic.php"; ?>
		<?php if( count( $metas ) > 0 ) { ?>
			<div class="filler-height filler-height-medium"></div>
			<?php include "$reviewIncludes/forum/attributes.php"; ?>
		<?php } ?>
		<div class="filler-height filler-height-medium"></div>
		<?php include "$reviewIncludes/forum/files.php"; ?>
		<div class="filler-height filler-height-medium"></div>
		<?php include "$reviewIncludes/forum/settings.php"; ?>
		<div class="filler-height filler-height-medium"></div>
		<?php include "$reviewIncludes/forum/content.php"; ?>
		<div class="filler-height filler-height-medium"></div>
		<?php include "$reviewIncludes/forum/seo.php"; ?>
		<div class="filler-height filler-height-medium"></div>
		<?php ActiveForm::end(); ?>
	</div>
</div>
<?= Popup::widget([
	'templateDir' => Yii::getAlias( '@themes/admin/views/templates/popup/lightbox' ), 'template' => 'slider'
])?>
