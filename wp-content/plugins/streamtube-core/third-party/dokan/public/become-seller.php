<?php
/**
 *
 * The Dokan Become Seller template file
 * 
 */
if( ! defined('ABSPATH' ) ){
    exit;
}

/**
 *
 * Fires before page header
 * 
 */
do_action( 'streamtube/core/user/dashboard/page_header/before' );
?>

<div class="page-head mb-3 d-flex gap-3 align-items-center">
	<h1 class="page-title h4">
		<?php esc_html_e( 'Become seller', 'streamtube-core' );?>
	</h1>	
</div>

<?php
/**
 *
 * Fires after page header
 * 
 */
do_action( 'streamtube/core/user/dashboard/page_header/after' );

/**
 *
 * Fires before page content
 * 
 */
do_action( 'streamtube/core/user/dashboard/page_content/before' );
?>

<div class="page-content bg-white p-4 shadow-sm">
<?php

/**
 *
 * Become Seller content
 * 
 */
do_action( 'streamtube/core/dokan/become_seller_content' );

?>
</div>

<?php

/**
 *
 * Fires after page content
 * 
 */
do_action( 'streamtube/core/user/dashboard/page_content/after' );