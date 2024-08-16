<?php
/**
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

$logo = get_option( 'footer_logo' );

if( empty( $logo ) ){
	return;
}

$content_width = get_option( 'footer_content_width', 'container' );

?>
<div class="footer-logo py-4 text-center">
	<div class="<?php echo esc_attr( join( ' ', streamtube_get_container_classes( $content_width ) ) ); ?>">
		<?php printf(
			'<img src="%s" alt="%s">',
			esc_url( $logo ),
			esc_attr( get_bloginfo( 'name' ) )
		);?>
	</div>
</div>