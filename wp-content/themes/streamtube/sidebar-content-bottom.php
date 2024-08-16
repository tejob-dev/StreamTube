<?php
/**
 *
 * The template for displaying content bottom sidebar
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

if( ! is_active_sidebar( 'content-bottom' ) ){
	return;
}
?>

<div id="content-bottom" class="sidebar sidebar-content-bottom">
	<?php dynamic_sidebar( 'content-bottom' )?>
</div>