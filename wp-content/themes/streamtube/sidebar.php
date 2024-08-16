<?php
/**
 *
 * The template for displaying primary sidebar
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

if( ! is_active_sidebar( 'sidebar-1' ) ){
	return;
}

$is_sticky = apply_filters( 'streamtube/sidebar/primary/sticky', get_option( 'sidebar_sticky' ) );

?>
<?php printf(
    '<div id="sidebar-primary" class="sidebar sidebar-primary %s">',
    $is_sticky ? 'sticky-top' : 'no-sticky-top'
)?>
	<?php dynamic_sidebar( 'sidebar-1' )?>
</div>