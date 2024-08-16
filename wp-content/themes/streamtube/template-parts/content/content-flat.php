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

$args = wp_parse_args( $args, array(
    'thumbnail_size'        => 'streamtube-image-medium',
    'post_categories'       =>  'yes',
    'author_avatar'         => '',
    'avatar_size'           => 'sm',
    'avatar_name'           =>  '',
    'show_author_name'      =>  '',
    'show_post_date'        =>  '',
    'show_post_comment'     =>  '',
    'show_post_view'        =>  '',
    'post_excerpt_length'   =>  0,
    'hide_thumbnail'        => '',
    'overlay'               =>  ''
) );

if( $args['thumbnail_size'] == 'size-560-315' ){
    $args['thumbnail_size'] = 'streamtube-image-medium';
}

if( in_array( $args['show_post_date'], array( 'on', 'yes', '1' ) ) ){
    $args['show_post_date'] = 'diff';
}

$thumbnail_url = has_post_thumbnail() ? wp_get_attachment_image_url( get_post_thumbnail_id(), $args['thumbnail_size'] ) : '';

?>
<article <?php post_class(); ?>>

    <?php 
    if( ! empty( $thumbnail_url ) ){
        printf(
            '<div class="bg-cover bg-thumbnail post-thumbnail" style="background-image: url(%s);">',
            esc_url( $thumbnail_url )
        );

            do_action( 'streamtube/flat_post/thumbnail/content' );

        echo '</div>';
    }?>

    <div class="post-body position-relative h-100 w-100">

        <a class="post-permalink" title="<?php echo esc_attr( wp_strip_all_tags(get_the_title()) )?>" href="<?php echo esc_url( get_permalink() )?>">
            <div class="bg-overlay"></div>

            <?php if( get_post_type() == 'video' || get_post_format() == 'video' ){
                get_template_part( 'template-parts/play-icon' );   
            }?>
        </a>

        <?php if( get_post_type() == 'video' || get_post_format() == 'video' ):?>

            <?php get_template_part( 'template-parts/video-length' ); ?>

        <?php endif;?>
    
        <?php do_action( 'streamtube/flat_post/item', $args );?>

        <div class="post-bottom w-100">

            <?php 
            if( $args['post_categories'] ){

                $tax = 'category';

                if( get_post_type() == 'video' ){
                    $tax = 'categories';
                }

                if( has_term( null, $tax, get_the_ID() ) ){
                    ?>
                    <div class="post-category post-tags mb-3">
                        <?php the_terms( get_the_ID(), $tax, null, '<span class="mx-1"></span>' ); ?>
                    </div>
                    <?php
                }
                    
            }?>             

            <div class="d-block w-100">
                <?php 
                if( $args['author_avatar'] ){
                    ?>
                    <div class="me-3 d-inline-block float-start">
                        <?php get_template_part( 'template-parts/post-author-avatar', null, $args ); ?>
                    </div>
                    <?php
                }?>

                <div class="post-meta overflow-hidden">

                    <?php do_action( 'streamtube/post/meta/title/before' );?>

                    <?php
                    the_title(
                        '<h2 class="post-meta__title post-title"><a title="'. esc_attr( wp_strip_all_tags(get_the_title()) ) .'" href="'. esc_url( get_permalink() ) .'">', 
                        '</a></h2>'
                    );
                    ?>

                    <?php do_action( 'streamtube/post/meta/title/after' );?>

                    <div class="post-meta__items">

                        <?php do_action( 'streamtube/post/meta/item/before' );?>

                        <?php if( $args['show_author_name'] ){
                            get_template_part( 'template-parts/post-author' );
                        }?>

                        <?php if( $args['show_post_view'] ){
                            get_template_part( 'template-parts/post-views' );
                        }?>                        

                        <?php if( $args['show_post_date'] ){
                           get_template_part( 'template-parts/post-date', $args['show_post_date'] ); 
                        }?>

                        <?php if( $args['show_post_comment'] && get_comments_number() > 0 ){
                           get_template_part( 'template-parts/post-comment' ); 
                        }?>

                        <?php do_action( 'streamtube/post/meta/item' );?> 

                        <?php do_action( 'streamtube/post/meta/item/after' );?>

                    </div>

                </div>

            </div>

        </div>

    </div>

</article>