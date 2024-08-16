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

$template_options = streamtube_get_page_template_options();

?>
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

            <?php if( ! $template_options['disable_title'] ):?>

                <div class="post-meta">
                    <?php 
                     the_title(
                        '<h1 class="post-meta__title post-title post-title-xxl py-2">', '</h1>'
                    );    
                    ?>
                </div>

            <?php endif;?>

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