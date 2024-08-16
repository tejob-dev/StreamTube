<?php
/**
 *
 * The template for displaying buddypress primary sidebar
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

$sidebar = streamtube_bp_get_sidebar();

if( ! $sidebar ){
    return;
}

$is_sticky = apply_filters( 'streamtube/sidebar/primary/sticky', get_option( 'sidebar_sticky' ), 'buddypress' );
?>
<?php printf(
    '<div id="sidebar-primary" class="sidebar sidebar-primary sidebar-buddypress %s">',
    $is_sticky ? 'sticky-top' : 'no-sticky-top'
)?>
	<?php dynamic_sidebar( $sidebar )?>
</div>