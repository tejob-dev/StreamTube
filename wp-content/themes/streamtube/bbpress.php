<?php
/**
 *
 * The template for displaying bbpress pages
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

$has_sidebar = is_active_sidebar( 'bbpress' );

get_header();
?>

    <?php get_template_part( 'bbpress/forum', 'header' )?>

    <?php get_template_part( 'bbpress/forum-tabs' );?>

    <div class="page-main">

        <?php printf(
            '<div class="%s">',
            ! $has_sidebar ? 'container' : 'container-fluid'
        );?>

            <div class="row">

                <?php printf(
                    '<div class="col-xl-%1$s col-lg-%1$s col-md-12 col-12">',
                    $has_sidebar ? '9' : '12'
                );?>

        			<?php if( have_posts() ):?>

        				<?php while( have_posts() ): the_post();?>

        					<?php the_content();?>

        				<?php endwhile;?>

        			<?php endif;?>

                </div>

            <?php if( $has_sidebar ): ?>
                <div class="col-xl-3 col-lg-3 col-md-12 col-12">
                    <?php get_sidebar( 'bbpress' );?>
                </div>
            <?php endif;?>

            </div><!--.row-->

        </div><!--.container-->

    </div><!--.page-main-->

<?php 
get_footer();