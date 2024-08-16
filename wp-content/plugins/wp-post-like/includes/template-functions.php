<?php

if( ! defined( 'ABSPATH' ) ){
	exit;
}

/**
 *
 * Check if current user liked given post
 * 
 * @return array|false
 *
 * @since 1.0.0
 * 
 */
function wppl_is_liked( $post_id = 0 ){

	return WPPL()->get()->query->is_liked( $post_id, get_current_user_id() );

}

/**
 *
 * Check if current user disliked given post
 * 
 * @return array|false
 *
 * @since 1.0.0
 * 
 */
function wppl_is_disliked( $post_id = 0 ){

    return WPPL()->get()->query->is_disliked( $post_id, get_current_user_id() );

}

/**
 *
 * Get post like count
 * 
 * @return int
 *
 * @since 1.0.0
 * 
 */
function wppl_get_count( $post_id = 0 ){
    $count = array(
        'like'      => (int)get_post_meta( $post_id, '_like_count', true ),
        'dislike'   => (int)get_post_meta( $post_id, '_dislike_count', true )
    );

    return (object)$count;
}

/**
 *
 * The like button template
 * 
 * @since 1.0.2
 * 
 */
function wppl_button_like( $args = array() ){
	load_template( WP_POST_PUBLIC_PATH . '/partials/button-like.php', false, $args );
}