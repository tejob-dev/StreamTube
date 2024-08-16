<?php
/**
 *
 * The template for displaying Featured sidebar
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
<div id="sidebar-primary" class="sidebar sidebar-featured border-bottom p-4 pb-0 mb-4">
	<?php dynamic_sidebar( 'featured' )?>
</div>