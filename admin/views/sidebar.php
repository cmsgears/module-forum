<?php
// Yii Imports
use yii\helpers\Html;

// CMG Imports
use cmsgears\forum\common\config\ForumGlobal;

$core	= Yii::$app->core;
$user	= Yii::$app->core->getUser();

$siteRootUrl = Yii::$app->core->getSiteRootUrl();
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
				<li class="forum <?= $child === 'forum' ? 'active' : null ?>"><?= Html::a( "Forums", [ "$siteRootUrl/forum/forum/all" ] ) ?></li>
				<li class="topic <?= $child === 'topic' ? 'active' : null ?>"><?= Html::a( "Topics", [ "$siteRootUrl/forum/topic/all" ] ) ?></li>
				<li class="topic-category <?= $child == 'topic-category' ? 'active' : null ?>"><?= Html::a( "Topic Categories", [ "$siteRootUrl/forum/topic/category/all" ] ) ?></li>
				<li class="topic-tag <?= $child === 'topic-tag' ? 'active' : null ?>"><?= Html::a( "Topic Tags", [ "$siteRootUrl/forum/topic/tag/all" ] ) ?></li>
				<li class="forum-review <?= $child === 'forum-reviews' ? 'active' : null ?>"><?= Html::a( "Forum Reviews", [ "$siteRootUrl/forum/forum/review/all" ] ) ?></li>
				<li class="forum-template <?= $child === 'forum-template' ? 'active' : null ?>"><?= Html::a( "Forum Templates", [ "$siteRootUrl/forum/forum/template/all" ] ) ?></li>
				<li class="topic-template <?= $child === 'topic-template' ? 'active' : null ?>"><?= Html::a( "Topic Templates", [ "$siteRootUrl/forum/topic/template/all" ] ) ?></li>
			</ul>
		</div>
	</div>
<?php } ?>
