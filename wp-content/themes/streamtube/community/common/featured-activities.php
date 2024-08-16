<?php
/**
 * The template for displaying featured activities
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

$widget_args = apply_filters( 'streamtube/core/bp/activity/featured_activities_args', array(
    'groupby_author'                =>  false,
    'current_logged_in_following'   =>  is_user_logged_in() ? true : false,
    'author__not_in'                =>  is_user_logged_in() ? array( get_current_user_id() ) : array(),
    'posts_per_page'                =>  20,
    'orderby'                       =>  'date',
    'order'                         =>  'DESC',
    'hide_empty_thumbnail'          =>  true,
    'thumbnail_ratio'               =>  '2x3',
    'rows'                          =>  1,
    'slide'                         =>  true,
    'slide_arrows'                  =>  true,
    'overlay'                       =>  true,
    'show_post_date'                =>  false,
    'col_xxl'                       =>  4,
    'col_xl'                        =>  4,
    'col_lg'                        =>  2,
    'col_md'                        =>  2,
    'col_sm'                        =>  2,
    'col'                           =>  2
) );

the_widget( 'Streamtube_Core_Widget_Posts', $widget_args, array(
    'before_widget' => '<div class="mb-0 widget posts-widget streamtube-widget">',
    'after_widget'  => '</div>'
) );
