<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://1.envato.market/mgXE4y
 * @since      1.0.0
 *
 * @package    WP_Post_Location
 * @subpackage WP_Post_Location/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    WP_Post_Location
 * @subpackage WP_Post_Location/public
 * @author     phpface <nttoanbrvt@gmail.com>
 */
class WP_Post_Location_Public {

	public static function defer_scripts( $tag, $handle, $src ){
		$defer = array(
			'google-map',
			'googlemap',
			'wp-post-location'
		);

		if( in_array( $handle , $defer ) ){
			$tag = str_replace( '<script ', '<script async ', $tag );
		}

		return $tag;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public static function enqueue_scripts() {

		$settings = WP_Post_Location_Customizer::get_settings();

		wp_register_style( 
			'leaflet', 
			WP_POST_LOCATION_URL_PUBLIC . '/assets/leaflet.css', 
			array(), 
			filemtime( WP_POST_LOCATION_PATH_PUBLIC . '/assets/leaflet.css' ),
			'all' 
		);

		wp_register_script( 
			'leaflet', 
			WP_POST_LOCATION_URL_PUBLIC . '/assets/leaflet.js', 
			array(), 
			filemtime( WP_POST_LOCATION_PATH_PUBLIC . '/assets/leaflet.js' ),
			false
		);

		wp_register_style( 
			'wp-post-location', 
			WP_POST_LOCATION_URL_PUBLIC . '/assets/style.css', 
			array(), 
			filemtime( WP_POST_LOCATION_PATH_PUBLIC . '/assets/style.css' ),
			'all' 
		);		

		$map_args = array(
			'key'		=>	$settings['googlemap_api'],
			'language'	=>	$settings['language'],
			'libraries'	=>	'places'
		);

		$map_js_url = add_query_arg( $map_args, '//maps.googleapis.com/maps/api/js' );

		wp_register_script( 
			'google-map', 
			$map_js_url 
		);

		wp_register_script( 
			'wp-post-location', 
			WP_POST_LOCATION_URL_PUBLIC . '/assets/'. sanitize_file_name( $settings['map_provider'] ) .'.js', 
			array(), 
			filemtime( WP_POST_LOCATION_PATH_PUBLIC . '/assets/'. sanitize_file_name( $settings['map_provider'] ) .'.js' ),
			true
		);

		wp_localize_script( 'wp-post-location', 'wp_post_location', array(
			'ajax_url'			=>	admin_url( 'admin-ajax.php' ),
			'_wpnonce'			=> 	wp_create_nonce('_wpnonce'),
			'google_map_api'	=>	$settings['googlemap_api'],
			'language'			=>	$settings['language'],
			'is_admin'			=>	is_admin() ? true : false,
			'i18n'				=>	array(
				'address'			=>	esc_html__( 'Address', 'wp-post-location' ),
				'latitude'			=>	esc_html__( 'Latitude', 'wp-post-location' ),
				'longitude'			=>	esc_html__( 'Longitude', 'wp-post-location' ),
				'by'				=>	esc_html__( 'By', 'wp-post-location' ),
				'geo_not_supported'	=>	esc_html__( 'Geolocation is not supported by this browser', 'wp-post-location' ),
				'search_location_empty'	=>	esc_html__( 'No addresses were found', 'wp-post-location' )
			)
		) );
	}

	/**
	 *
	 * Add GEO item to Edit Post nav
	 * 
	 * @param  array $items
	 * @return array
	 *
	 * @since 1.0.0
	 * 
	 */
	public static function edit_post_nav( $items ){

		$settings = WP_Post_Location_Customizer::get_settings();

		if( $settings['frontend_form'] ){
			$items['location'] 	= array(
				'title'			=>	esc_html__( 'Location', 'wp-post-location' ),
				'icon'			=>	'icon-location',
				'template'		=>	plugin_dir_path( __FILE__ ) . 'partials/edit-location.php',
				'priority'		=>	30
			);
		}

		return $items;
	} 
}
