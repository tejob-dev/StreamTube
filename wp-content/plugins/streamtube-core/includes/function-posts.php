<?php
if( ! defined('ABSPATH' ) ){
    exit;
}

/**
 *
 * Get text tracks
 * 
 * @param  int $post_id
 * @return array|false
 */
function streamtube_core_get_text_tracks( $post_id ){
    return streamtube_core()->get()->post->get_text_tracks( $post_id );
}

/**
 *
 * Check if current is edit post screen
 * 
 * @return true|false
 *
 * @since  1.0.0
 * 
 */
function streamtube_core_is_edit_post_screen(){
	return streamtube_core()->get()->post->is_edit_post_screen();
}

/**
 *
 * Get edit post URL
 * 
 * @param  string $endpoint
 * @return string
 *
 * @since  1.0.0
 * 
 */
function streamtube_core_get_edit_post_url( $post_id = 0, $endpoint = '' ){
    return streamtube_core()->get()->post->get_edit_post_url( $post_id, $endpoint );
}

