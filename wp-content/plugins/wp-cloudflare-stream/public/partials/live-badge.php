<?php
if( ! defined('ABSPATH' ) ){
    exit;
}
?>
<span class="post-live d-block text-uppercase">
	<span class="badge badge-live bg-danger">
		<?php echo $args['text']; ?>
	</span>

	<?php if( defined( 'STREAMTUBE_CORE_IS_DASHBOARD_VIDEOS' ) && STREAMTUBE_CORE_IS_DASHBOARD_VIDEOS ){
		printf(
			'<span class="badge badge-live bg-%s text-capitalize">%s</span>',
			$args['status'] == 'connected' ? 'success' : 'secondary',
			$args['status']
		);
	}?>
</span>