<?php
/**
 * Template Name: Single V2
 *
 * Template Post Type: Post
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

$template_options   = streamtube_get_page_template_options();

$el_width           = apply_filters( 'streamtube_main_content_size', 8 );

$has_sidebar        = is_active_sidebar( 'sidebar-1' ) ? 'sidebar-1' : false;
/**
 *
 * Filter sidebar
 * 
 */
$has_sidebar        = apply_filters('streamtube/sidebar/primary', $has_sidebar );

if( $template_options['disable_primary_sidebar'] ){
    $has_sidebar = false;
}

$has_comments       = ! comments_open() && ! get_comments_number() ? false : true;

if( $template_options['disable_comment_box'] ){
    $has_comments = false;
}

$args = streamtube_get_blog_template_settings();

get_header();
?>

    <?php if( have_posts() ): the_post();?>

        <?php get_template_part( 'template-parts/large', 'image', $args );?>

        <div class="page-main py-4">

            <div class="container">

                <div class="row">

                    <?php printf(
                        '<div class="col-xxl-%1$s col-xl-%1$s col-lg-%1$s col-md-12 col-12 %2$s">',
                        $has_sidebar    ? $el_width : 9,
                        ! $has_sidebar  ? 'mx-auto' : ''
                    );?>

                        <div class="content-wrap">
                    
                            <?php
                            /**
                             *
                             * Fires before content wrapper
                             *
                             * @since  1.0.0
                             * 
                             */
                            do_action( 'streamtube/single/content/wrap/before' );
                            ?>
                            
                            <div class="shadow-sm bg-white mb-4">

                                <?php
                                /**
                                 *
                                 * Fires before main content
                                 *
                                 * @since  1.0.0
                                 * 
                                 */
                                do_action( 'streamtube/single/content/before' );
                                ?>

                                <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                                    <div class="post-body single-body p-4">

                                        <div class="post-content">

                                            <?php the_content();?>

                                            <div class="clearfix"></div>

                                            <?php wp_link_pages( array(
                                                'type'  =>  'list'
                                            ) );?>                
                                        </div>

                                        <?php if( has_tag() && is_singular() ):?>
                                            <div class="post-tags mt-4">
                                                <?php the_tags( '<span class="tagged me-2">'. esc_html__( 'Tagged:', 'streamtube' ) .'</span>', ' ' );?>
                                            </div>
                                        <?php endif;?>

                                        <?php get_template_part( 'template-parts/social', 'share' ); ?>        

                                    </div>

                                </div>

                                <?php
                                /**
                                 *
                                 * Fires after main content
                                 *
                                 * @since  1.0.0
                                 * 
                                 */
                                do_action( 'streamtube/single/content/after' );
                                ?>                            
                            </div>

                            <?php if( get_option( 'blog_author_box' ) ): ?>
                                <div class="shadow-sm bg-white mb-4">

                                    <?php get_template_part( 'template-parts/author', 'box', array(
                                        'author_avatar' =>  'on'
                                    ) );?>

                                </div>
                            <?php endif;?>

                            <?php
                            /**
                             *
                             * Fires before content wrapper
                             *
                             * @since  1.0.0
                             * 
                             */
                            do_action( 'streamtube/single/content/wrap/after' );
                            ?>                        

                            <?php
                            if( ! $template_options['disable_bottom_sidebar'] ){
                                get_sidebar( 'content-bottom' );    
                            }
                            ?>

                            <?php if( $has_comments ): ?>
                                <?php comments_template(); ?>
                            <?php endif;?>

                        </div>
                    </div>

                    <?php if( $has_sidebar ): ?>
                        <?php printf(
                            '<div class="col-xxl-%1$s col-xl-%1$s col-lg-%1$s col-md-12 col-12">',
                            12-(int)$el_width
                        )?>
                            <?php get_sidebar( $has_sidebar );?>
                        </div>
                    <?php endif;?>

                </div>

            </div>

        </div>

    <?php endif;?>

<?php 
get_footer();