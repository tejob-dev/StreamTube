<?php

if( ! defined('ABSPATH' ) ){
    exit;
}


global $post;

$featured_image_2 = streamtube_core()->get()->post->get_thumbnail_image_url_2( $post->ID );
?>

<div class="metabox-wrap">
    <div class="field-group" style="margin-bottom: 0">

        <?php printf(
            '<div class="placeholder-image %s w-100">',
            ! $featured_image_2 ? 'no-image' : ''
        );?>

            <button type="button" class="button button-secondary button-delete">
                <span class="dashicons dashicons-no"></span>
            </button>

            <?php 
                if( $featured_image_2 ){
                     printf(
                        '<img src="%s" class="featured-image-2 image-src">',
                        $featured_image_2
                    );
                }
            ?>                
            
        </div>

        <?php printf(
            '<input type="text" name="thumbnail_image_url_2" id="thumbnail_image_url_2" class="regular-text input-field" value="%s">',
            esc_attr( $featured_image_2 )
        );?>

        <p class="description">
            <?php esc_html_e( 'Show this image when hovering over on featured image.', 'streamtube-core' );?>
        </p>

        <button id="button-upload-image" type="button" class="button button-primary button-upload hide-if-no-js w-100" data-media-type="image" data-media-source="url">
            <?php esc_html_e( 'Upload', 'streamtube-core' );?>
        </button>                

        <button id="button-generate-webp-image" type="button" class="button button-secondary hide-if-no-js w-100">
            <?php esc_html_e( 'Auto Generate Image', 'streamtube-core' );?>
            <span class="spinner"></span>
        </button>

    </div> 
</div>