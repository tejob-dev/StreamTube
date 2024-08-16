<?php
/**
 *
 * The template for displaying footer
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
            <?php
            /**
             *
             * Fires before footer
             * 
             */
            do_action( 'streamtube/footer/before' );

            get_template_part( 'template-parts/footer/footer' );

            /**
             *
             * Fires after footer
             * 
             */
            do_action( 'streamtube/footer/after' );
            ?>  
        <?php wp_footer();?>

    </body>

</html>