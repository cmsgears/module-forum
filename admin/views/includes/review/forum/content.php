<div class="row">
	<div class="box box-crud colf colf15x7">
		<div class="box-header">
			<div class="box-header-title">Summary</div>
		</div>
		<div class="box-content-wysiwyg">
			<div class="box-content">
				<?= $form->field( $content, 'summary' )->textarea( [ 'class' => 'content-editor' ] )->label( false ) ?>
			</div>
		</div>
	</div>
	<div class="colf colf15"></div>
	<div class="box box-crud colf colf15x7">
		<div class="box-header">
			<div class="box-header-title">Details</div>
		</div>
		<div class="box-content-wysiwyg">
			<div class="box-content">
				<?= $form->field( $content, 'content' )->textarea( [ 'class' => 'content-editor' ] )->label( false ) ?>
			</div>
		</div>
	</div>
</div>
