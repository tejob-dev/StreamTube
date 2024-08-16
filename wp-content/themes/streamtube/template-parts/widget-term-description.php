<?php
/**
 *
 * The template for displaying term description widget
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

if( "" != $description = get_queried_object()->description ): ?>
    <div class="widget widget-term-control">    
        <?php 
        printf(
            '<div class="post-content term-description">%s</div>',
            wp_kses_post( $description )
        );?>
    </div>
<?php endif;