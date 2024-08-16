<?php
/**
 * The template for displaying video archive
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
    
$args = streamtube_get_search_template_settings();

get_header();
?>

    <?php get_template_part( 'template-parts/page', 'header' )?>

    <div class="page-main pt-4">

        <div class="container">

            <div class="row">

                <?php printf(
                    '<div class="col-xl-%1$s col-lg-%1$s col-md-12 col-12">',
                    $has_sidebar ? '8' : '12'
                );?>

                    <?php if( have_posts() ):?>
                        <div class="post-list">

                            <?php while( have_posts() ): the_post();?>

                                <div class="post-item shadow-sm bg-white mb-4">
                                    <?php get_template_part( 
                                        'template-parts/content/content', 
                                        get_post_format(),
                                        $args
                                    )?>
                                </div>

                            <?php endwhile;?>

                        </div>

                        <?php streamtube_posts_pagination( array(
                            'el_class'  =>  'mb-4'    
                        ) );?>

                    <?php else:?>

                        <?php get_template_part( 'template-parts/content/content', 'none' )?>

                    <?php endif;?>

                </div>

                <?php if( $has_sidebar ): ?>
                    <div class="col-xl-4 col-lg-4 col-md-12 col-12">
                        <?php get_sidebar();?>
                    </div>
                <?php endif;?>
            </div>

        </div>

    </div>

<?php 
get_footer();