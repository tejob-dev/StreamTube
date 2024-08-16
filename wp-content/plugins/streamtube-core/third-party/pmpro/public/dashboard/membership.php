<?php
if( ! defined('ABSPATH' ) ){
    exit;
}

$page 		= StreamTube_Core_PMPro::PAGE_SLUG;

$mylevels 	= pmpro_getMembershipLevelsForUser();

/**
 *
 * Fires before page header
 * 
 */
do_action( 'streamtube/core/user/dashboard/page_header/before' );
?>
<div class="page-head mb-3 d-flex gap-3 align-items-center">
	<h1 class="page-title h4">
		<?php esc_html_e( 'Paid Membership', 'streamtube-core' ); ?>
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

<div class="page-content">
	<?php if ( empty( $mylevels ) ): ?>
		<?php
		/**
		 *
		 * Fires before Plans
		 *
		 * @since 2.2
		 * 
		 */
		do_action( 'streamtube/core/pmpro/dashboard/membership/plans/before' );

		$levels_output = '';

		if( 0 < $levels_page_id = get_option( 'pmpro_levels_page_id' ) ){

			echo streamtube_core_get_elementor_builder_content( $levels_page_id );
			
		}else{
			$levels_output = streamtube_core()->get()->pmpro->_shortcode_membership_levels(
				apply_filters( 'streamtube/core/shortcode/membership_levels_args', array() )
			);
		}

		/**
		 *
		 * Filter the output of levels
		 *
		 * @since 2.2
		 * 
		 */
		$levels_output = apply_filters( 'streamtube/core/pmpro/dashboard/membership/levels_output', $levels_output );

		if( $levels_output ){
			echo $levels_output;
		}

		/**
		 *
		 * Fires after Plans
		 *
		 * @since 2.2
		 * 
		 */
		do_action( 'streamtube/core/pmpro/dashboard/membership/plans/after' );
		?>
	<?php else: ?>

		<?php 
		streamtube_core()->get()->user_dashboard->the_menu( array(
			'user_id'		=>	get_current_user_id(),
			'base_url'		=>	streamtube_core_get_user_dashboard_url( get_current_user_id(), $page ),
			'menu_classes'	=>	'nav-tabs secondary-nav',
			'item_classes'	=>	'text-secondary d-flex align-items-center'
		), $page );?>

		<div class="bg-white p-4 border-start border-right border-bottom border-end">
			<?php streamtube_core()->get()->user_dashboard->the_main( $page );?>
		</div>	

	<?php endif; ?>
</div>

<?php

/**
 *
 * Fires after page content
 * 
 */
do_action( 'streamtube/core/user/dashboard/page_content/after' );