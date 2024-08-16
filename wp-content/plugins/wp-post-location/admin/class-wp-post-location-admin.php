<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://1.envato.market/mgXE4y
 * @since      1.0.0
 *
 * @package    WP_Post_Location
 * @subpackage WP_Post_Location/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WP_Post_Location
 * @subpackage WP_Post_Location/admin
 * @author     phpface <nttoanbrvt@gmail.com>
 */
class WP_Post_Location_Admin {

	/**
	 *
	 * Add Location metabox
	 *
	 * @since 1.0.0
	 * 
	 */
	public static function add_meta_boxes(){

		add_meta_box( 
			'wp-post-location-box', 
			esc_html__( 'Location', 'wp-post-location' ), 
			array( __CLASS__ , 'location_callback' ), 
			array( 'video', 'post' ), 
			'advanced', 
			'core'
		);

	}

	/**
	 *
	 * Location metabox callback
	 *
	 * @since 1.0.0
	 * 
	 */
	public static function location_callback( $post ){
		load_template( plugin_dir_path( __FILE__ ) . '/partials/edit-location.php', true );
	}

	/**
	 *
	 * Location metabox callback
	 *
	 * @since 1.0.0
	 * 
	 */
	public static function save_location( $post_id ){

        if( ! current_user_can( 'edit_post', $post_id ) ){
            return;
        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        if( ! isset( $_POST['wp_post_location'] ) ){
        	return;
        }

		return WP_Post_Location_Post::update_location();  
	}	

}
