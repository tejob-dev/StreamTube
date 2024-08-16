<?php
/**
 *
 * The template for displaying pages
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

$has_sidebar = is_active_sidebar( 'sidebar-1' );

get_header();

$template_options = streamtube_get_page_template_options();
?>

    <div class="page-main pt-4">

        <div class="container">
        
            <div class="row">

                <?php printf(
                    '<div class="col-xl-%1$s col-lg-%1$s col-md-12 col-12">',
                    $has_sidebar && ! $template_options['disable_primary_sidebar'] ? '8' : '12'
                );?>

                    <?php if( have_posts() ):?>

                        <?php while( have_posts() ): the_post();?>

                            <?php printf(
                                '<div class="%s mb-4">',
                                $template_options['remove_content_box'] ? 'no-shadow' : 'shadow-sm bg-white'
                            )?>
                                <?php get_template_part( 
                                    'template-parts/content/content', 
                                    'page'
                                )?>
                            </div>

                        <?php endwhile;?>

                    <?php else:?>

                        <?php get_template_part( 'template-parts/content/content', 'none' )?>

                    <?php endif;?>
                    
                    <?php 
                    if( ! $template_options['disable_comment_box'] ){
                        comments_template();    
                    }
                    ?>

                </div>

                <?php if( $has_sidebar && ! $template_options['disable_primary_sidebar'] ): ?>
                    <div class="col-xl-4 col-lg-4 col-md-12 col-12">
                        <?php get_sidebar();?>
                    </div>
                <?php endif;?>
            </div>

        </div>

    </div>

<?php 
get_footer();