<?php
$point_types = array_merge( array(
	''	=>	esc_html__( 'All', 'streamtube-core' )
), streamtube_core_get_mycred_public_point_types() );

?>
<div class="tablenav top mb-3">

	<div class="d-block d-xxl-flex align-items-start">

		<div class="d-block d-md-flex align-items-start gap-3">
			<select class="form-control mb-3" name="ctype">
				<?php foreach ( $point_types as $key => $value ): ?>
					
					<?php printf(
						'<option value="%s" %s>%s</option>',
						esc_attr( $key ),
						isset( $_REQUEST['ctype'] ) && $_REQUEST['ctype'] == $key ? 'selected' : '',
						esc_html( $value )
					);?>

				<?php endforeach ?>
			</select>
			<?php

				ob_start();
				$logs->filter_options();

				$filter = ob_get_clean();

				$filter = str_replace( 'alignleft actions', 'mycred-filter actions d-md-flex align-items-start gap-3', $filter );
				$filter = str_replace( 'button-secondary', 'button-secondary btn-primary mb-3', $filter );

				echo $filter;

			?>

		</div>

		<div class="ms-auto">
			<?php include( 'pagination.php' ) ?>
		</div>

	</div>

</div>