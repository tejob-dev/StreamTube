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

    </div><!--.site-main-->

<div id="site-footer" class="site-footer mt-auto">

    <?php if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'footer' ) ) : ?>

        <?php
        /**
         *
         * Fires before footer content
         * 
         */
        do_action( 'streamtube/footer/content/before' );
        ?>  

        <?php get_template_part( 'template-parts/footer/widgets' ); ?>

        <?php get_template_part( 'template-parts/footer/logo' ); ?>

        <?php get_template_part( 'template-parts/footer/socials' ); ?>

        <?php get_template_part( 'template-parts/footer/copyright' ); ?>

        <?php
        /**
         *
         * Fires after footer content
         * 
         */
        do_action( 'streamtube/footer/content/after' );
        ?>         

    <?php endif; ?>

</div><!--.site-footer-->