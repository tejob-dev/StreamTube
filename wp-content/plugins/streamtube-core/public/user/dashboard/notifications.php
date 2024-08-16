<?php
if( ! defined('ABSPATH' ) ){
    exit;
}

define( 'STREAMTUBE_CORE_IS_DASHBOARD_NOTIFICATIONS', true );

streamtube_core()->get()->buddypress->setup_bp_environments( 'notifications' );

add_filter( 'bp_current_action', function( $action ){
	switch ( $GLOBALS['wp_query']->query['dashboard']  ){

		case 'notifications/read':
			$action = 'read';
		break;

		default:
			$action = 'unread';
		break;
	}

	return $action;
} );

buddypress()->notifications->late_includes();

do_action( 'bp_actions' );

bp_enqueue_scripts();

?>
<div id="buddypress" class="buddypress-wrap bp-dir-hori-nav alignwide">
	<?php

	/**
	 *
	 * Fires before page header
	 * 
	 */
	do_action( 'streamtube/core/user/dashboard/page_header/before' );
	?>

	<div class="page-head mb-3">
		<h1 class="page-title h4">
			<?php esc_html_e( 'Notifications', 'streamtube-core' ); ?>
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

	<div class="page-content bp-wrap">

		<?php bp_get_template_part( 'members/single/notifications' );?>

		<div class="clearfix"></div>
		
	</div>
	<?php

	/**
	 *
	 * Fires after page content
	 * 
	 */
	do_action( 'streamtube/core/user/dashboard/page_content/after' );

	?>
</div>
