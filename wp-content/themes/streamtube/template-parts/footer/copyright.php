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

$default = sprintf(
	esc_html__( 'Copyright %s %s', 'streamtube' ),
	date( 'Y' ),
	get_bloginfo( 'name' )
);

$text = wp_kses_post(get_option( 'copyright_text', $default ));

/**
 *
 * Filter the text
 * 
 */
$text = apply_filters( 'streamtube/footer/copyright_text', $text );

if( empty( $text ) ){
	return;
}

$content_width = get_option( 'footer_content_width', 'container' );
?>
<div class="footer-text py-3 text-center">
	<div class="<?php echo esc_attr( join( ' ', streamtube_get_container_classes( $content_width ) ) ); ?>">
			
		<?php printf(
			'<div class="copyright-text">%s</div>',
			$text
		)?>

	</div>
</div>