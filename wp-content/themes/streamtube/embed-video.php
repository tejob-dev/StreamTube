<?php
/**
 *
 * The template for displaying embed video
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

get_header( 'embed' );
    if( have_posts() ){
        the_post();

        get_template_part( 'template-parts/player' );
    }
get_footer( 'embed' );