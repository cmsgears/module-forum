<div class="box box-crud">
	<div class="box-header">
		<div class="box-header-title">Attributes</div>
	</div>
	<div class="box-content-wrap">
		<div class="box-content">
			<?php foreach( $metas as $meta ) { ?>
				<div class="row">
					<div class="col col12x2">
						<?= $form->field( $meta, 'name' )->textInput( [ 'readonly' => 'true' ] )->label( false ) ?>
					</div>
					<div class="col col12x10">
						<?= $form->field( $meta, 'value' )->textarea( [ 'readonly' => 'true' ] )->label( false ) ?>
					</div>
				</div>
				<div class="filler-height filler-height-small"></div><hr/>
			<?php } ?>
		</div>
	</div>
</div>
