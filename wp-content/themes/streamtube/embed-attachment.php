<?php
/**
 *
 * The template for displaying embed attachment
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

        if( wp_attachment_is( 'video', get_the_ID() ) ){
            get_template_part( 'template-parts/player', null, array(
                'source'    =>  get_the_ID()
            ) );
        }
        else{
            get_template_part( 'embed', 'content' );
        }

        
    }
get_footer( 'embed' );