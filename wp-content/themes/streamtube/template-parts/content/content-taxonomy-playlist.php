<?php
/**
 *
 * The Term content playlist template file
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

$thumbnail_url = $streamtube->get()->taxonomy->get_thumbnail_url( $term, $term_grid_settings['thumbnail_size'] );

if( is_array( $thumbnail_url ) ){
    if( isset( $thumbnail_url[0] ) && wp_attachment_is( 'image', $thumbnail_url[0] ) ){
        $thumbnail_url = wp_get_attachment_image_url( $thumbnail_url[0], $term_grid_settings['thumbnail_size'] );
    }
    else{
        $thumbnail_url = false;
    }
}

if( $term_grid_settings['play_all'] && (int)$term->count > 0 ){
    $permalink = $streamtube->get()->collection->get_play_all_link( $term, $term->taxonomy );
}else{
    $permalink = get_term_link( $term->term_id, $term->taxonomy );
}

$show_count = array_key_exists( 'count', $term_grid_settings ) && $term_grid_settings['count'] ? true : false;

?>
<article class="post-term rounded overflow-hidden">

    <div class="post-body position-relative">

        <div class="post-main position-relative rounded overflow-hidden bg-dark">

            <?php printf(
                '<a class="post-permalink" href="%s">',
                esc_url( $permalink )
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

                    <?php if( array_key_exists( 'overlay', $term_grid_settings ) && $term_grid_settings['overlay'] ): ?>

                        <div class="bg-overlay"></div>

                    <?php endif;?>
                </div><!--.post-thumbnail-->

                <div class="term-box position-absolute">
                    <div class="position-absolute top-50 start-50 translate-middle text-center">
                         <?php printf(
                            '<h5 class="post-count text-white mb-2 fw-normal">%s</h5>',
                            number_format_i18n( $term->count )
                        );?>
                        <span class="icon__playlist icon-indent-right h5 d-block"></span>
                    </div>
                </div>

                <?php if( $term_grid_settings['play_all'] && (int)$term->count > 0 ): ?>
                    <?php get_template_part( 'template-parts/term', 'play-all-overlay' );?>
                <?php endif;?>
            </a>

        </div><!--.post-main-->

        <div class="post-bottom term-bottom mt-3 d-flex align-items-start">

            <div class="post-meta term-meta w-100">

                <?php 

                printf(
                    '<h2 class="post-title term-title"><a href="%s" title="%s">%s</a></h2>',
                    esc_url( get_term_link( $term->term_id, $term->taxonomy ) ),
                    $term->name_formatted ? esc_attr( $term->name_formatted ) : esc_attr( $term->name ),
                    $term->name_formatted ? esc_html( $term->name_formatted ) : esc_html( $term->name )
                );
                ?>

                <div class="post-meta__items playlist-meta__items d-flex gap-3">

                    <?php
                    if( $term_grid_settings['term_author'] ){
                        get_template_part( 'template-parts/term', 'author' );
                    }
                    ?>

                    <?php if( array_key_exists( 'term_status' , $term_grid_settings) && $term_grid_settings['term_status'] ): ?>
                        <div class="term-meta__status">
                            <?php
                            if( $term->status == 'private' ){
                                ?>
                                <span class="btn__icon icon-lock"></span>
                                <?php
                                esc_html_e( 'Private', 'streamtube' );
                            }else{
                                ?>
                                <span class="btn__icon icon-globe"></span>
                                <?php
                                esc_html_e( 'Public', 'streamtube' );
                            }
                            ?>
                        </div>
                    <?php endif;?>

                    <?php if( $show_count ): ?>

                        <div class="term-meta__index">
                            <?php printf(
                                '<span class="total">%s</span>',
                                sprintf( _n( '%s video', '%s videos', $term->count, 'streamtube' ), number_format_i18n( $term->count ) )
                            );?>
                        </div>

                    <?php endif;?>

                </div>

            </div>

        </div><!--.post-bottom-->

    </div><!--.post-body-->

</article>  