<?php
/**
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
 * Get edit post link
 * 
 * @param  integer $post_id
 * @return string
 *
 * @since 1.0.0
 * 
 */
function streamtube_get_edit_post_link( $post_id = 0 ){

	if( ! $post_id ){
		$post_id = get_the_ID();
	}

	if( ! function_exists( 'streamtube_core' ) ){
		return get_edit_post_link( $post_id );
	}

	return streamtube_core()->get()->post->get_edit_post_url( $post_id );
}

/**
 *
 * Convert default post content to JS read more/read less
 * 
 * @param  string $content
 * @return HTML
 *
 * @since  1.0.0
 * 
 */
function streamtube_content_to_readmorejs( $content = '' ){

	if( empty( $content ) || get_post_type() != 'video' || ! is_main_query() ){
		return $content;
	}

	if( ! get_option( 'read_more_less', 'on' ) ){
		return $content;
	}

	$new_content = '';

	$new_content = sprintf(
		'<div class="js-read">%s</div>',
		$content
	);

	$new_content .= '<button class="btn btn-block shadow-none border-0 d-block w-100 js-read-toggler d-none">';
		$new_content .= '<span class="btn__icon icon-angle-double-down text-secondary"></span>';
	$new_content .= '</button>';

	return sprintf(
		'<div class="js-read-wrap">%s</div>',
		$new_content
	);
}
add_filter( 'the_content', 'streamtube_content_to_readmorejs', 9999, 1 );

/**
 *
 * Get custom single template
 * 
 * @param  string $template
 * @return string
 * 
 */
function streamtube_get_custom_single_template( $template = null ){

	global $post;

	if( ! in_array( $post->post_type , array( 'video', 'post' )) ){
		return $template;
	}

	$global_single_template	= get_option( "single_{$post->post_type}" );
	$default_template       = get_page_template_slug( $post->ID );

    if( ! empty( $default_template ) ){
    	$template = locate_template( $default_template );
    }else{
    	if( ! empty( $global_single_template ) ){
    		$template = locate_template( $global_single_template );
    	}
    }

    if( get_option( "enforce_single_{$post->post_type}" ) && ! empty( $global_single_template ) ){
    	$template = locate_template( $global_single_template );
    }

    return $template;
}

add_filter( 'single_template', 'streamtube_get_custom_single_template', 10, 1 );