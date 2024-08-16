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

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <div class="post-body <?php echo is_singular() ? 'single-body' : 'part-body'; ?>">

        <div class="post-main">
            <figure class="wp-block-image p-4">
                <?php
                /**
                 * Filter the default image attachment size.
                 * 
                 * @param string $image_size Image size. Default 'full'.
                 */
                $image_size = apply_filters( 'streamtube/single/image/attachment_size', 'full' );

                echo wp_get_attachment_image( get_the_ID(), $image_size, false, array(
                    'class' =>  'mx-auto d-block'
                ) );
                ?>

                <?php if ( wp_get_attachment_caption() ) : ?>
                    <figcaption class="wp-caption-text"><?php echo wp_kses_post( wp_get_attachment_caption() ); ?></figcaption>
                <?php else:?>
                    <figcaption class="wp-caption-text"><?php echo wp_kses_post( get_the_title() ); ?></figcaption>
                <?php endif; ?>
            </figure><!-- .wp-block-image -->
        </div>

    </div>
</article>