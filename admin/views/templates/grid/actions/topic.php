<?php
use yii\helpers\Html;

$baseUrl = $widget->baseUrl;
?>
<span title="Files"><?= Html::a( "", [ "$baseUrl/file/all?pid=$model->id" ], [ 'class' => 'cmti cmti-file' ] ) ?></span>
<span title="Attributes"><?= Html::a( "", [ "$baseUrl/attribute/all?pid=$model->id" ], [ 'class' => 'cmti cmti-tag' ] ) ?></span>
<span title="Gallery"><?= Html::a( "", [ "$baseUrl/gallery?id=$model->id" ], [ 'class' => 'cmti cmti-image' ] ) ?></span>
<span title="Update"><?= Html::a( "", [ "update?id=$model->id" ], [ 'class' => 'cmti cmti-edit' ] )  ?></span>
<span title="Review"><?= Html::a( "", [ "review?id=$model->id" ], [ 'class' => 'cmti cmti-eye' ] )  ?></span>

<span class="action action-pop action-delete cmti cmti-bin" title="Delete" target="<?= $model->id ?>" popup="popup-grid-delete"></span>
