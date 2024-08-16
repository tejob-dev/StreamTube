<div class="position-absolute top-50 start-50 translate-middle spinner-wrap">
	<div class="d-flex align-items-center">
		<?php get_template_part( 'template-parts/spinner', null, array(
			'type'	=>	'secondary'
		) );?>
		<span class="loading-text ms-2"><?php esc_html_e( 'Loading reports ...', 'streamtube-core' ); ?></span>
	</div>
</div>