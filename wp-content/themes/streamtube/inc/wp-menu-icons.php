<?php
/**
 *
 * WP Menu Icons plugin compatiblity file
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

if( ! function_exists( 'streamtube_filter_wp_menu_icons_item_title' ) ){
	/**
	 *
	 * Filter the wp icon title
	 *
	 * 
	 * @param  string $new_title
	 * @param  int $menu_item_id
	 * @param  $wpmi
	 * @param  $title        [description
	 * @return string $title
	 *
	 * @since 1.0.0
	 * 
	 */
	function streamtube_filter_wp_menu_icons_item_title( $new_title, $menu_item_id, $wpmi, $title ){
		return $title;
	}

	add_filter( 'wp_menu_icons_item_title' , 'streamtube_filter_wp_menu_icons_item_title', 10, 4 );	
}

if( ! function_exists( 'streamtube_filter_wp_menu_item_classes' ) ){
	/**
	 *
	 * Add additional class if icon found
	 * 
	 */
	function streamtube_filter_wp_menu_item_classes( $classes , $item ){

		if( is_object( $item ) && property_exists( $item, 'wpmi' ) ){

			if( $item->wpmi->icon ){
				$classes[] = 'nav-item-icon';
			}
		}

		return $classes;
	}

	add_filter( 'nav_menu_css_class', 'streamtube_filter_wp_menu_item_classes', 10, 2 );

}

if( ! function_exists( 'streamtube_filter_wp_menu_item_title' ) ){
	/**
	 *
	 * Filter the menu title
	 *
	 * 
	 * @param  string $title
	 * @param  WP Post Object $item
	 * @param  array $args
	 * @param  int $depth
	 * @return string
	 *
	 *
	 * @since 1.0.0
	 * 
	 */
	function streamtube_filter_wp_menu_item_title( $title, $item, $args, $depth ){

		if( ! is_object( $item ) || ! property_exists( $item, 'wpmi' ) ){
			return $title;
		}

		$wpmi = wp_parse_args( (array)$item->wpmi, array(
			'icon'		=>	'',
			'position'	=>	''
		) );

		/**
		 * 
		 * @param array $wpmi
		 * @param string $title
		 * @param array $args
		 * 
		 */
		$wpmi = apply_filters( 'streamtube_filter_wp_menu_item_title_wpmi', $wpmi, $item, $args, $depth );

		$_title = $icon = '';

		if ( $wpmi['icon'] ) {	

			$icon = sprintf( 
				'<span class="menu-icon %s" data-bs-toggle="tooltip" data-bs-placement="%s" title="%s"></span>',
				esc_attr( $wpmi['icon'] ),
				! is_rtl() ? 'right' : 'left',
				esc_attr( $title )
			);

			/**
			 *
			 * Filter the icon output
			 *
			 * @param string $icon
			 * @param array $wpmi
			 * @param string $title
			 * @param array $args
			 * 
			 */
			$icon = apply_filters( 'streamtube_filter_wp_menu_item_title_icon_output', $icon, $wpmi, $item, $args, $depth );
		}

		$title = sprintf( 
			'<span class="menu-title menu-text">%s</span>',
			 $title
		);

		if ( $wpmi['position'] == 'after' ) {
			$_title = $title . $icon;
		}
		else{
			$_title = $icon . $title;
		}

		/**
		 *
		 * Filter the _title output
		 *
		 * @param string $icon
		 * @param array $wpmi
		 * @param string $title
		 * @param array $args
		 * 
		 */
		$_title = apply_filters( 'streamtube_filter_wp_menu_item_title', $_title, $wpmi, $item, $args, $depth );		

		return sprintf( '<span class="menu-icon-wrap">%s</span>', $_title );
	}

	add_filter( 'nav_menu_item_title' , 'streamtube_filter_wp_menu_item_title', 10, 4 );
}

