<?php
/**
 *
 * The template for displaying 404 error
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
    <div class="error-404-wrap position-relative">
        <div class="p-5 text-center">
            <header class="page-header">
                <h3 class="text-uppercase"><?php esc_html_e( 'Oops, page not found', 'streamtube' ); ?></h3>
                <h1 class="error-title">
                    <?php esc_html_e( '404', 'streamtube' ); ?>
                </h1>
            </header><!-- .page-header -->
            <p class="text-muted">
                <?php esc_html_e( 'The page you are looking for does not exist. It might have been moved or deleted.', 'streamtube' ); ?>
            </p>

            <?php printf(
                '<a class="btn btn-info text-white px-4 mt-3" href="%s">%s</a>',
                esc_url( home_url('/') ),
                esc_html__( 'back to home', 'streamtube' )
            );?>

        </div>
    </div>
<?php 
get_footer();