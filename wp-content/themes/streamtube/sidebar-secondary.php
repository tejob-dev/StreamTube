<?php
/**
 *
 * The template for displaying float sidebar
 *
 * @link       https://1.envato.market/mgXE4y
 * @since      1.0.0
 *
 * @package    WordPress
 * @subpackage StreamTube
 * @author     phpface <nttoanbrvt@gmail.com>
 */
if( ! defined( 'ABSPATH' ) ){
    exit;
}

$is_collapsed = false;

if( isset( $_COOKIE['is_float_collapsed'] ) ){
	$is_collapsed = ! wp_validate_boolean( $_COOKIE['is_float_collapsed'] );
}
else{
	$is_collapsed = get_option( 'sidebar_float_collapse' );
}

if( has_nav_menu( 'primary' ) || is_active_sidebar( 'secondary' ) ):?>

	<?php printf(
		'<div id="sidebar-secondary" class="sidebar sidebar-secondary %s border-end bg-white no-scroll d-flex flex-column">',
		$is_collapsed ? 'sidebar-collapse' : ''
	)?>

		<?php
		do_action( 'streamtube/sidebar/secondary/inner/before' );
		?>

		<button id="btn-menu-collap" class="btn-collapse btn btn-lg bg-white btn-white rounded-0 px-0 shadow-none">
			<span class="icon-left text-secondary"></span>
		</button>

		<?php

		do_action( 'streamtube/sidebar/secondary/inner/menu/before' );

		if( has_nav_menu( 'primary' ) ):
			echo '<div class="widget_main-menu">';
				wp_nav_menu( array(
					'theme_location'  	=> 'primary',
					'container'       	=> 'div',
					'container_class' 	=> 'main-nav float-nav',
					'container_id'   	=> 'main-nav',
					'menu_class'     	=> 'nav flex-column',
					'echo'				=> true,
					'fallback_cb'		=> 'WP_Bootstrap_Navwalker::fallback',
					'walker'        	=> new WP_Bootstrap_Navwalker(),
				) );
			echo '</div>';
		endif;

		do_action( 'streamtube/sidebar/secondary/inner/menu/after' );

		if( is_active_sidebar( 'secondary' ) ){
			echo '<div class="widget-group p-3 mt-3">';

				dynamic_sidebar( 'secondary' );

			echo '</div>';	
		}

		do_action( 'streamtube/sidebar/secondary/inner/after' );
	
		?>
	</div>

<?php endif;