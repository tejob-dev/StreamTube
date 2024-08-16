<?php
/**
 *
 * The template for displaying bbpress primary sidebar
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

if( ! is_active_sidebar( 'bbpress' ) ){
	return;
}

$is_sticky = apply_filters( 'streamtube/sidebar/primary/sticky', get_option( 'sidebar_sticky' ), 'bbpress' );
?>
<?php printf(
    '<div id="sidebar-primary" class="sidebar sidebar-primary sidebar-bbpress %s">',
    $is_sticky ? 'sticky-top' : 'no-sticky-top'
)?>
	<?php dynamic_sidebar( 'bbpress' )?>
</div>