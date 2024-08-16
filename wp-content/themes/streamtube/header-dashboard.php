<?php
/**
 *
 * The template for displaying header
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
<!doctype html>
<html <?php language_attributes(); ?> data-theme="<?php echo streamtube_get_theme_mode(); ?>">
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <?php wp_head();?>
    </head>

    <body <?php body_class( 'body-dashboard dashboard-' . sanitize_html_class( $wp_query->query_vars['dashboard'] ) );?>>
        <?php wp_body_open(); ?>

        <header id="site-header" class="site-header d-flex align-items-center shadow-sm border-bottom fixed-top py-4">
            <div class="<?php echo esc_attr( streamtube_get_container_header_classes() ); ?>">
                
                <div class="row align-items-center">
                    <div class="col-6">

                        <div class="d-flex align-items-center">

                            <div class="navbar-light d-xl-none">
                                <button id="toggle-nav" class="btn border-0 navbar-toggler shadow-none" type="button">
                                    <span class="btn__icon icon-menu"></span>
                                </button>
                            </div>

                            <div class="logo-lg">
                                <?php get_template_part( 'template-parts/logo' );?>
                            </div>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="header-user d-flex align-items-center">

                            <div class="ms-auto d-flex align-items-center gap-0 gap-sm-1 gap-lg-2">

                                <?php get_template_part( 'template-parts/header/profile-dropdown' );?>

                            </div>
                            
                        </div>
                    </div>

                </div>
            </div>   
        </header>

        <div id="site-main" class="site-main">  