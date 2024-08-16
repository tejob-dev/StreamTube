<?php
/**
 *
 * The template for displaying page boxed
 *
 * Template Name: Page Boxed
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

$template_options = streamtube_get_page_template_options();
?>

    <?php 
    if( ! $template_options['disable_title'] ){
        get_template_part( 'template-parts/page', 'header', $template_options );
    }
    ?>

    <div class="page-main">

        <div class="container">
        
            <?php if( have_posts() ):?>

                <?php while( have_posts() ): the_post();?>

                        <?php printf(
                            '<div class="%s mb-4">',
                            $template_options['remove_content_box'] ? 'no-shadow' : 'shadow-sm bg-white'
                        )?>

                        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                            <div class="post-body single-body">

                                <?php if( has_post_thumbnail() && ! $template_options['disable_thumbnail'] ) : ?>
                                    <div class="post-main">
                                        <a title="<?php echo esc_attr( get_the_title() )?>" href="<?php echo esc_url( get_permalink() )?>">
                                            <div class="post-thumbnail">
                                                <?php the_post_thumbnail( 'post-thumbnails', array(
                                                    'class' =>  'img-fluid'
                                                ) );?>
                                            </div>
                                        </a>
                                    </div>
                                <?php endif;?>

                                <?php printf(
                                    '<div class="post-bottom %s">',
                                    $template_options['disable_content_padding'] ? 'no-padding' : 'p-4'
                                );?>

                                    <div class="post-content">
                                        <?php the_content( esc_html__( 'Continue reading', 'streamtube' ) );?>
                                        <div class="clearfix"></div>

                                        <?php wp_link_pages( array(
                                            'type'  =>  'list'
                                        ) );?>                
                                    </div>

                                </div>
                            </div>

                        </article>

                        <?php 
                        if( ! $template_options['disable_comment_box'] ){
                            comments_template();    
                        }
                        ?>
                    </div>

                <?php endwhile;?>

            <?php endif;?>

        </div>

    </div>

<?php 
get_footer();