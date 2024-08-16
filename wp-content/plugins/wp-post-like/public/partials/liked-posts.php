<?php
if( ! defined( 'ABSPATH' ) ){
    exit;
}

if( ! class_exists( 'Streamtube_Core_Widget_Posts' ) ){
    return;
}

extract( $args );

$not_found_text = esc_html__( 'You have not liked any posts yet.', 'wp-post-like' );

if( is_array( $post_type ) && count( $post_type ) == 1 ){
    $not_found_text = sprintf(
        esc_html__( 'You have not liked any %s yet.', 'wp-post-like' ),
        get_post_type_object( $post_type[0] )->label
    );
}

$args = array_merge( $args, array(
    'title'                     =>  $heading,
    'post_type'                 =>  $post_type,
    'post_status'               =>  array( 'publish', 'private', 'unlist', 'inherit' ),
    'current_logged_in_like'    =>  true,
    'paged'                     =>  get_query_var( 'page' ),
    'not_found_text'            =>  $not_found_text
) );

$args = apply_filters(
    'wp-post-like/liked_posts_args',
    $args
);

the_widget( 'Streamtube_Core_Widget_Posts', $args, array(
    'before_widget' => '<div class="widget widget-featured %1$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<div class="widget-title-wrap"><h2 class="widget-title d-flex align-items-center">',
    'after_title'   => '</h2></div>'
) );