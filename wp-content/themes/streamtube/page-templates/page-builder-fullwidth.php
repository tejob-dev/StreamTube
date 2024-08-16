<?php
/**
 * Template Name: Page Builder - FullWidth
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
$content_width = streamtube_get_site_content_width();

get_header();?>

	<div class="page-main p-0">

		<div class="<?php echo esc_attr( join( ' ', streamtube_get_container_classes( $content_width ) ) ); ?> p-0">

			<?php if( have_posts() ): the_post();

				the_content();

			endif;?>

		</div>

	</div>

<?php 
get_footer();