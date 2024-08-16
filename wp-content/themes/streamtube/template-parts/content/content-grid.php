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

/**
 * Template part for displaying archive posts
 */

if( ! $args ){
    $args = array();
}

$featured_image_2 = '';

$title_classes = array( 'post-meta__title', 'post-title' );

$args = wp_parse_args( $args, array(
    'thumbnail_size'        => 'streamtube-image-medium',
    'show_post_date'        => '',
    'show_post_comment'     => '',
    'show_post_like'        => 'on',
    'show_post_view'        => '',
    'show_author_name'      => '',
    'author_avatar'         => '',
    'avatar_size'           => 'sm',
    'post_excerpt_length'   => 0,
    'avatar_name'           => '',
    'hide_thumbnail'        => '',
    'overlay'               => '',
    'title_size'            => ''
) );

if( $args['thumbnail_size'] == 'size-560-315' ){
    $args['thumbnail_size'] = 'streamtube-image-medium';
}

if( in_array( $args['show_post_date'], array( 'on', 'yes', '1' ) ) ){
    $args['show_post_date'] = 'diff';
}

if( $args['title_size'] ){
    $title_classes[] ='post-title-' .sanitize_html_class( $args['title_size'] );
}

if( function_exists( 'streamtube_core' ) && method_exists( streamtube_core()->get()->post, 'get_thumbnail_image_url_2' ) ){
    $featured_image_2 = streamtube_core()->get()->post->get_thumbnail_image_url_2();
}

$article_attrs = sprintf(
    'data-layout="grid" data-embed-url="%s" %s',
    esc_url( get_post_embed_url() ),
    $featured_image_2 ? 'data-thumbnail-image-2="'. esc_url( $featured_image_2 ) .'"' : ''
);

?>
<article <?php post_class(); ?> <?php echo wp_kses_post( $article_attrs ); ?>>

    <div class="post-body position-relative">
        
        <?php if( ! $args['hide_thumbnail'] ): ?>
            <div class="post-main position-relative rounded overflow-hidden">

                <a class="post-permalink" title="<?php echo esc_attr( wp_strip_all_tags( get_the_title() ) )?>" href="<?php echo esc_url( get_permalink() )?>">
                    <?php get_template_part( 'template-parts/thumbnail', null, $args ); ?>
                </a>
                    
            </div>
        <?php endif;?>

        <div class="post-bottom mt-3 d-flex align-items-start">

            <?php 
            if( $args['author_avatar'] && get_post_type() != 'product' ){
                ?>
                <div class="me-2">
                    <?php get_template_part( 'template-parts/post-author-avatar', null, $args ); ?>
                </div>
                <?php
            }?>

            <div class="post-meta w-100">

                <?php do_action( 'streamtube/post/meta/title/before' );?>

                <?php
                the_title(
                    '<h2 class="'. esc_attr( join( ' ', array_unique( $title_classes ) ) ) .'"><a title="'. esc_attr( wp_strip_all_tags(get_the_title() )) .'" href="'. esc_url( get_permalink() ) .'">', 
                    '</a></h2>'
                );
                ?>  

                <?php do_action( 'streamtube/post/meta/title/after' );?>

                <?php
                if( function_exists( 'WC' ) && get_post_type() == 'product' ){
                    ?>
                    <div class="wc-meta d-flex">
                        <div class="wc-meta__left">
                        <?php
                        /**
                        * Hook: woocommerce_after_shop_loop_item_title.
                        *
                        * @hooked woocommerce_template_loop_rating - 5
                        * @hooked woocommerce_template_loop_price - 10
                        */
                        do_action( 'woocommerce_after_shop_loop_item_title' );
                        ?>
                        </div>

                        <div class="wc-meta__right ms-auto">
                            <?php woocommerce_template_loop_add_to_cart(); ?>
                        </div>
                    </div>
                    <?php
                }
                ?>                

                <?php if( get_post_type() != 'product' ): ?>
                    <div class="post-meta__items d-flex flex-column">

                        <?php do_action( 'streamtube/post/meta/item/before' );?>

                        <?php if( $args['show_author_name'] ){
                            get_template_part( 'template-parts/post-author' );
                        }?>  

                        <div class="d-flex gap-3">
                            <?php if( $args['show_post_view'] ){
                                get_template_part( 'template-parts/post-views' );
                            }?>
                            <?php if( $args['show_post_date'] ){
                               get_template_part( 'template-parts/post-date', $args['show_post_date'] ); 
                            }?>
                            <?php
                            if( $args['show_post_comment'] && get_comments_number() ){
                               get_template_part( 'template-parts/post-comment' ); 
                            }
                            ?>                        
                        </div>
                                        
                        <?php do_action( 'streamtube/post/meta/item' );?>

                        <?php do_action( 'streamtube/post/meta/item/after' );?>
                    </div>
                <?php endif;?>

            </div>

        </div>

        <?php if( absint( $args['post_excerpt_length'] ) > 0 ): ?>
            <div class="post-excerpt text-secondary">
                <?php echo wp_trim_words( get_the_excerpt(), absint( $args['post_excerpt_length'] ), '' );?>
            </div>
        <?php endif;?>        

    </div>

</article>