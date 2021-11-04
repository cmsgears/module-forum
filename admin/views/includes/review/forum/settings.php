<?php
// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

$notification	= $metaService->getNameMetaMapByType( $model->id, CoreGlobal::SETTINGS_NOTIFICATION );
$reminder		= $metaService->getNameMetaMapByType( $model->id, CoreGlobal::SETTINGS_REMINDER );
?>
<div class="box box-crud">
	<div class="box-header">
		<div class="box-header-title">Settings</div>
	</div>
	<div class="box-content-wrap frm-split-40-60">
		<div class="box-content">
			<div class="row">
				<div class="col col3 bold">Notifications</div>
				<div class="col col3x2">
					<label>Receive Mail</label>
					<span class="form">
						<span class="cmt-checkbox switch">
							<input id="notify_receive_email" class="switch-toggle switch-toggle-round" type="checkbox" name="value" disabled />
							<label for="notify_receive_email"></label>
							<input type="hidden" name="value" value="<?= isset( $notification[ 'receive_email' ] ) ? $notification[ 'receive_email' ]->value : false; ?>" />
						</span>
					</span>
				</div>
			</div>
			<div class="row">
				<div class="col col3 bold">Reminders</div>
				<div class="col col3x2">
					<label>Receive Mail</label>
					<span class="form">
						<span class="cmt-checkbox switch">
							<input id="notify_receive_email" class="switch-toggle switch-toggle-round" type="checkbox" name="value" disabled />
							<label for="notify_receive_email"></label>
							<input type="hidden" name="value" value="<?= isset( $reminder[ 'receive_email' ] ) ? $reminder[ 'receive_email' ]->value : false; ?>" />
						</span>
					</span>
				</div>
			</div>
		</div>
	</div>
</div>
