<?php
/**
 *
 * The template for displaying single blog post
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

get_header();

?>

    <div class="page-main pt-4">

        <div class="container">

            <?php if( have_posts() ): the_post();?>

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

                <?php get_template_part( 'template-parts/content/content', 'image' );?>

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

            <?php endif;?>

            <?php
            if( apply_filters( 'streamtube/single/image/sidebar/bottom', false ) === true ){
                get_sidebar( 'content-bottom' );
            }
            ?>

            <?php if( apply_filters( 'streamtube/single/image/comments_template', true ) === true ): ?>
                <?php comments_template(); ?>
            <?php endif;?>

        </div>

    </div>

<?php 

get_footer();