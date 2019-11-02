<?php
// Yii Imports
use yii\helpers\Html;

// CMG Imports
use cmsgears\forum\common\config\ForumGlobal;

$core	= Yii::$app->core;
$user	= Yii::$app->user->getIdentity();
?>

<?php if( $core->hasModule( 'forum' ) && $user->isPermitted( ForumGlobal::PERM_FORUM_ADMIN ) ) { ?>
	<div id="sidebar-forum" class="collapsible-tab has-children <?= $parent === 'sidebar-forum' ? 'active' : null ?>">
		<span class="marker"></span>
		<div class="tab-header">
			<div class="tab-icon"><span class="cmti cmti-comment"></span></div>
			<div class="tab-title">Forum</div>
		</div>
		<div class="tab-content clear <?= $parent === 'sidebar-forum' ? 'expanded visible' : null ?>">
			<ul>
				<li class='<?= $child === 'topic' ? 'active' : null ?>'><?= Html::a( "Topics", ['/forum/topic/all'] ) ?></li>
				<li class='<?= $child === 'topic-category' ? 'active' : null ?>'><?= Html::a( "Topic Categories", ['/forum/topic/category/all'] ) ?></li>
				<li class='<?= $child === 'topic-tag' ? 'active' : null ?>'><?= Html::a( "Topic Tags", ['/forum/topic/tag/all'] ) ?></li>
				<li class='<?= $child === 'topic-template' ? 'active' : null ?>'><?= Html::a( "Topic Templates", ['/forum/topic/template/all'] ) ?></li>
			</ul>
		</div>
	</div>
<?php } ?>
