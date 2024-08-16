<?php
/**
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

global $streamtube;

echo '<div class="post-item d-flex mb-4">';

    get_template_part( 'template-parts/content/content', 'list', array(
        'show_post_like'        =>  false,
        'post_excerpt_length'   =>  0,
        'show_post_date'        =>  true,
        'show_post_comment'     =>  false,
        'show_author_name'      =>  true
    ) );

    echo '<div class="add-button ms-auto">';

        echo streamtube_core_collection_add_post_to( get_the_ID(), $args['term_id'] );

    echo '</div>';

echo '</div>';