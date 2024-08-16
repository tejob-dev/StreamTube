<?php
/**
 *
 * The template for displaying page fullwidth image
 *
 * Template Name: Page Image Fullwidth
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

$args               = streamtube_get_blog_template_settings();

$has_sidebar        = is_active_sidebar( 'sidebar-1' );
    
$template_options   = streamtube_get_page_template_options();

get_header();
?>

    <?php if( have_posts() ): the_post();?>

        <?php get_template_part( 'template-parts/large', 'image', $args );?>

        <div class="page-main py-4">

            <div class="container">

                <div class="row">

                    <?php printf(
                        '<div class="col-xl-%1$s col-lg-%1$s col-md-12 col-12">',
                        $has_sidebar && ! $template_options['disable_primary_sidebar'] ? '8' : '12'
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

                            <?php 
                            if( ! $template_options['disable_comment_box'] ){
                                comments_template();    
                            }
                            ?>

                        </div>
                    </div>

                    <?php if( $has_sidebar && ! $template_options['disable_primary_sidebar'] ): ?>
                        <div class="col-xl-4 col-lg-4 col-md-12 col-12">
                            <?php get_sidebar();?>
                        </div>
                    <?php endif;?>

                </div>

            </div>

        </div>

    <?php endif;?>

<?php 
get_footer();