<?php
/**
 *
 * WP Easy Review plugin compatibility file
 * 
 */
if( ! defined( 'ABSPATH' ) ){
	exit;
}

/**
 *
 * Add review support for video post
 * 
 * @param  array $array
 * @return array $array
 *
 *
 * @since 1.0.0
 * 
 */
function streamtube_easy_review_video_support( $array ){

	if( is_string( $array['screen'] ) ){
		$array['screen'] .= ',video';
	}

	if( is_array( $array['screen'] ) ){
		$array['screen'][] = 'video';
	}

	return $array;

}
add_filter( 'wp-easy-review_metaboxes_pre', 'streamtube_easy_review_video_support', 9999, 1 );

/**
 *
 * Show the review box
 *
 * @since 1.0.0
 * 
 */
function streamtube_show_easy_review_box(){
    echo do_shortcode( '[wp_easy_review]' );
}

add_action( 'streamtube/single/content/after', 'streamtube_show_easy_review_box'  );

// Remove default review box, we use our hook
remove_action( 'the_content' , array( $GLOBALS['wp_easy_review'] , 'the_content' ), 10 , 1 );

/**
 *
 * Show post total review score
 * 
 * @since 1.0.0
 */
function streamtube_post_total_score(){
    $score = $GLOBALS['wp_easy_review']->get_total_score( get_the_ID() );

    if( $score ){
        printf(
            '<div class="total-score"><span>%s</span></div>',
            apply_filters( 'score_format', $score )
        );
    }
}