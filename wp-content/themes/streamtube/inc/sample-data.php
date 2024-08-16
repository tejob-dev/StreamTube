<?php
/**
 *
 * One Click Demo Import plugin compatiblity file
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

/**
 *
 * Register demo data
 *
 * @since 1.0.0
 * 
 */
function streamtube_import_sample_data(){
    return StreamTube_Theme_License()->get_sample_content();
}
add_filter( 'ocdi/import_files', 'streamtube_import_sample_data' );

/**
 *
 * Set up front page, blog page and menu after import done.
 * 
 * @since 1.0.0
 * 
 */
function streamtube_after_import_setup( $import ){
    $main_menu = get_term_by( 'name', 'Primary', 'nav_menu' );
 
    set_theme_mod( 'nav_menu_locations', array(
            'primary' => $main_menu->term_id
        )
    );

    // Assign front page and posts page (blog page).
    $front_page = get_page_by_title( 'Home' );
    $blog_page  = get_page_by_title( 'Blog' );
 
    if( $front_page ){
        update_option( 'show_on_front',     'page' );
        update_option( 'page_on_front',     $front_page->ID );
        update_option( 'page_for_posts',    $blog_page->ID );
    }

}
add_action( 'ocdi/after_import', 'streamtube_after_import_setup', 999, 1 );