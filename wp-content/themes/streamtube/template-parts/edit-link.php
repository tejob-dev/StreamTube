<?php
/**
 *
 * The edit post link template file
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

printf(
    '<a href="%s" class="edit-post-link badge bg-danger text-white text-decoration-none">%s</a>',
    esc_url( streamtube_get_edit_post_link( get_the_ID() ) ),
    esc_html__( 'Edit', 'streamtube' )
);