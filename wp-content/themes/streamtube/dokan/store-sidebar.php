<?php
/**
 * The template for displaying Dokan sidebar
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

$is_sticky = apply_filters( 'streamtube/sidebar/dokan/sticky', get_option( 'sidebar_sticky' ) );

printf(
    '<div id="dokan-primary" class="sidebar sidebar-primary dokan-store-sidebar %s">',
    $is_sticky ? 'sticky-top' : 'no-sticky-top'
)?>

<?php  if ( function_exists( 'dokan_store_theme_sidebar_enabled' ) && ! dokan_store_theme_sidebar_enabled() ): ?>

    <div class="dokan-widget-area widget-collapse">
        <?php do_action( 'dokan_sidebar_store_before', $store_user->data, $store_info ); ?>
        <?php
            dokan_store_category_widget();

            if ( ! empty( $map_location ) ) {
                dokan_store_location_widget();
            }

            dokan_store_time_widget();
            dokan_store_contact_widget();
        ?>

        <?php do_action( 'dokan_sidebar_store_after', $store_user->data, $store_info ); ?>
    </div>

    <?php elseif( is_active_sidebar( 'sidebar-store' ) ): ?>
        <?php dynamic_sidebar( 'sidebar-store' ); ?>
    <?php endif; ?>

</div><!-- #secondary .widget-area -->


