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
<html <?php language_attributes(); ?> data-theme="<?php echo esc_attr( streamtube_get_theme_mode() ); ?>">
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <?php wp_head();?>
    </head>

    <body <?php body_class();?>>

        <?php wp_body_open(); ?>
        
        <?php get_template_part( 'template-parts/header/header' ); ?>