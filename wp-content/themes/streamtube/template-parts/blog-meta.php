<?php
/**
 *
 * The blog post meta
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
?>
<div class="post-meta__items">

    <div class="post-author me-3">
        <div class="user-avatar user-avatar-md d-inline-block me-2">
            <?php printf(
                '<a href="%s">%s</a>',
                esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
                get_avatar( get_the_author_meta( 'ID' ), null, null, null, array(
                    'class' =>  'img-thumbnail'
                ) )
            );?>
        </div>
        <?php printf(
            '<a href="%s">%s</a>',
            esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
            get_the_author()
        );?>
    </div>

    <?php if( $args['post_date'] ){
       get_template_part( 'template-parts/post-date', $args['post_date'] ); 
    }?>

    <?php if( $args['post_views'] ){
       get_template_part( 'template-parts/post-views' ); 
    }?>

    <?php if( function_exists( 'wppl_button_like' ) ){
        wppl_button_like();
    }?>

    <?php
    do_action( 'streamtube/blog/single_meta' )
    ?>

    <?php if( $args['post_comment'] ): ?>
        <div class="float-end me-0">
            <?php get_template_part( 'template-parts/post-comment-box' );?>
        </div>
    <?php endif;?>
    <div class="clearfix"></div>
    
</div><!--.post-meta__items-->