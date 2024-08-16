<?php
/**
 * The custom logo template file
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

if( has_custom_logo() ){
    streamtube_the_custom_logo();    
}
else{
    printf(
        '<h1 class="site-title m-0"><a class="text-body text-decoration-none text-uppercase fw-bold h3" href="%s" title="%s">%s</a></h1>',
        esc_url( home_url( '/' ) ),
        esc_attr( get_bloginfo( 'description' ) ),
        esc_html( get_bloginfo( 'name' ) )
    );
}
