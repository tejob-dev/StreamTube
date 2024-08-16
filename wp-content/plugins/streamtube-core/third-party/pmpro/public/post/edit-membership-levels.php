<?php
if( ! defined('ABSPATH' ) ){
    exit;
}
?>
<div class="widget widget-require-membership shadow-sm rounded bg-white border">
	<div class="widget-title-wrap d-flex m-0 p-3 bg-light">
		<h2 class="widget-title no-after m-0">
			<?php esc_html_e( 'Require Membership', 'streamtube-core' );?>
		</h2>
	</div>

	<div class="widget-content p-3">
		<?php pmpro_page_meta(); ?>
	</div>	

</div>