<?php
/**
 * Template Name: Page Builder - Wide
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

	<div class="page-main">

		<div class="container container-wide">

			<?php if( have_posts() ): the_post();

				the_content();

			endif;?>

		</div>

	</div>

<?php 
get_footer();