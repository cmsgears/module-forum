<div class="box box-crud">
	<div class="box-header">
		<div class="box-header-title">Page SEO</div>
	</div>
	<div class="box-content">
		<div class="box-content">
			<div class="row">
				<div class="col col2">
					<?= $form->field( $content, 'seoName' )->textInput( [ 'readOnly' => true ] ) ?>
				</div>
				<div class="col col2">
					<?= $form->field( $content, 'seoRobot' )->textInput( [ 'readOnly' => true ] ) ?>
				</div>
			</div>
			<div class="row">
				<div class="col col2">
					<?= $form->field( $content, 'seoKeywords' )->textarea( [ 'readOnly' => true ] ) ?>
				</div>
				<div class="col col2">
					<?= $form->field( $content, 'seoDescription' )->textarea( [ 'readOnly' => true ] ) ?>
				</div>
			</div>
		</div>
	</div>
</div>
