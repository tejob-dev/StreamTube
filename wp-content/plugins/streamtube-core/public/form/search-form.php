<?php
/**
 *
 * The simple search form template
 * 
 */
if( ! defined('ABSPATH' ) ){
    exit;
}
?>
<div class="mb-4">
	<form method="get">
		<div class="input-group">
			<?php printf(
				'<input type="search" name="search_query" class="form-control" value="%s" placeholder="%s">',
				isset( $_REQUEST['search_query'] ) ? esc_attr( wp_unslash( $_REQUEST['search_query'] ) ) : '',
				esc_attr( 'Search ...', 'streamtube-core' )
			)?>
			<button class="btn btn-secondary">
				<span class="btn__icon icon-search"></span>
			</button>
		</div>
	</form>
</div>