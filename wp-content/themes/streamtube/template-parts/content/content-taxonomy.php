<?php
/**
 *
 * The content taxonomy template file
 *
 * @link       https://1.envato.market/mgXE4y
 * @since      2.2.1
 *
 * @package    WordPress
 * @subpackage StreamTube
 * @author     phpface <nttoanbrvt@gmail.com>
 */
if( ! defined( 'ABSPATH' ) ){
    exit;
}

global $streamtube, $term, $term_grid_settings;

if( is_null( $term ) ){
    $term = $args;
}

if( ! $term_grid_settings ){
    $term_grid_settings = array(
        'thumbnail_size'    =>  'streamtube-image-medium',
        'thumbnail_ratio'   =>  get_option( 'thumbnail_ratio', '16x9' )
    );
}

$thumbnail_url = streamtube_core()->get()->taxonomy->get_thumbnail_url( $term, $term_grid_settings['thumbnail_size'] );

if( is_array( $thumbnail_url ) ){
    if( isset( $thumbnail_url[0] ) && wp_attachment_is( 'image', $thumbnail_url[0] ) ){
        $thumbnail_url = wp_get_attachment_image_url( $thumbnail_url[0], $term_grid_settings['thumbnail_size'] );
    }
    else{
        $thumbnail_url = false;
    }
}

$count = sprintf( 
    _n( '%s post', '%s posts', $term->count, 'streamtube' ), 
    number_format_i18n( $term->count ) 
);

if( in_array( $term->taxonomy, array( 'video_tag', 'categories', 'video_collection' ) ) ){
    $count = sprintf( 
        _n( '%s video', '%s videos', $term->count, 'streamtube' ), 
        number_format_i18n( $term->count ) 
    );    
}

if( in_array( $term->taxonomy, array( 'product_cat' ) ) ){
    $count = sprintf( 
        _n( '%s product', '%s products', $term->count, 'streamtube' ), 
        number_format_i18n( $term->count ) 
    );    
}

$count      = apply_filters( 'streamtube/core/term_grid/count', $count, $term );

$show_count = array_key_exists( 'count', $term_grid_settings ) && $term_grid_settings['count'] ? true : false;

?>
<article class="post-term rounded overflow-hidden bg-dark">

    <div class="post-body position-relative">

        <div class="post-main position-relative rounded overflow-hidden">

            <?php printf(
                '<a class="post-permalink" href="%s">',
                esc_url( get_term_link( $term->term_id, $term->taxonomy ) )
            );?>

                <?php printf(
                    '<div class="post-thumbnail ratio ratio-%s">',
                    esc_attr( $term_grid_settings['thumbnail_ratio'] )
                );?>

                    <?php 
                    if( $thumbnail_url ){
                        printf(
                            '<img class="img-fluid wp-post-image" src="%s">',
                            esc_url( $thumbnail_url )
                        );
                    }
                    ?>                          

                    <div class="term-box top-50 start-50 translate-middle position-absolute h-auto w-auto rounded text-center">
                        <?php
                        printf(
                            '<h2 class="term-title %s">%s</h2>',
                            ! $show_count ? 'mb-0' : '',
                            $term->name_formatted ? $term->name_formatted : $term->name
                        );
                        ?>

                        <?php if( $show_count ): ?>

                            <?php printf(
                                '<span class="post-count text-white mt-4 badge bg-danger fw-bold h3">%s</span>',
                                $count
                            );?>

                        <?php endif;?>
                    </div>
            	</div>
            </a>

        </div><!--.post-main-->

    </div><!--.post-body-->

</article>	