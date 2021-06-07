<?php
use yii\helpers\Html;

$baseUrl	= $widget->baseUrl;
$reviews	= isset( $widget->data[ 'reviews' ] ) ? $widget->data[ 'reviews' ] : true;
?>
<span title="Topics"><?= Html::a( "", [ "topic/all?fid=$model->id" ], [ 'class' => 'cmti cmti-hierarchy' ] ) ?></span>
<?php if( $reviews ) { ?>
	<span title="Reviews"><?= Html::a( "", [ "$baseUrl/review/all?pid=$model->id" ], [ 'class' => 'cmti cmti-comment' ] ) ?></span>
<?php } ?>
<span title="Files"><?= Html::a( "", [ "$baseUrl/model-file/all?pid=$model->id" ], [ 'class' => 'cmti cmti-file' ] ) ?></span>
<span title="Attributes"><?= Html::a( "", [ "$baseUrl/attribute/all?pid=$model->id" ], [ 'class' => 'cmti cmti-tag' ] ) ?></span>
<span title="Gallery"><?= Html::a( "", [ "$baseUrl/gallery?id=$model->id" ], [ 'class' => 'cmti cmti-image' ] ) ?></span>
<span title="Update"><?= Html::a( "", [ "update?id=$model->id" ], [ 'class' => 'cmti cmti-edit' ] ) ?></span>
<span title="Review"><?= Html::a( "", [ "review?id=$model->id" ], [ 'class' => 'cmti cmti-eye' ] ) ?></span>

<span class="action action-pop action-delete cmti cmti-bin" title="Delete" target="<?= $model->id ?>" popup="popup-grid-delete"></span>
