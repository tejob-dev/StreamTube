<?php
/**
 * The template for displaying floating friend list
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

if( ! class_exists( 'StreamTube_Core_buddyPress_Widget_User_List' ) ){
    return;
}

$collapsed = $args['collapsed'] ? 'collapsed' : 'no-collapsed';

$instance = array(
    'source'    =>  'all',
    'type'      =>  'latest',
    'per_page'  =>  20,
    'user_id'   =>  false
);

/**
 *
 * Filter instance
 * 
 * @param array $instance
 */
$instance = apply_filters( 'streamtube/bp/friend_list_float/instance', $instance );

the_widget( 'StreamTube_Core_buddyPress_Widget_User_List', $instance,  array(
    'before_widget' =>  "<div class='{$collapsed} {$args['location']} widget widget-primary bp-user-list-float position-fixed bottom-0 mb-0 border-left bg-white %s'>",
    'after_widget'  =>  '</div>',
    'before_title'  =>  '<h2 class="widget-title no-after px-4 py-3 m-0 border-bottom d-block w-100 icon-title">',
    'after_title'   =>  '</h2>'
) );