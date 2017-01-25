		<?php if( Yii::$app->cmgCore->hasModule( 'cmgforum' ) && $user->isPermitted( 'forum' ) ) { ?>
			<div class="collapsible-tab has-children" id="sidebar-forum">
				<div class="collapsible-tab-header clearfix">
					<div class="colf colf4"><span class="icon-sidebar icon-blogpost"></span></div>
					<div class="colf colf4x3">Forums</div>
				</div>
				<div class="collapsible-tab-content clear">
					<ul>
						<li><?= Html::a( "Forum Matrix", ['/cmgforum/forum/matrix'] ) ?></li>
						<li><?= Html::a( "Forum Categories", ['/cmgforum/forum/categories'] ) ?></li>
						<li><?= Html::a( "Forums", ['/cmgforum/forum/all'] ) ?></li>
					</ul>
				</div>
			</div>
		<?php } ?>