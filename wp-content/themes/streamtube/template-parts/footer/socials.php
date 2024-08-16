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

$socials = array();

$_socials = streamtube_option_socials();

for ( $i=0; $i < count( $_socials ); $i++) { 
	if( "" != $url = get_option( 'social_' . sanitize_key( $_socials[$i] ) ) ){
		$socials[ $_socials[$i] ] = $url;
	}
}

if( ! $socials ){
	return;
}

$content_width = get_option( 'footer_content_width', 'container' );

?>
<div class="footer-socials py-4 text-center">
	<div class="<?php echo esc_attr( join( ' ', streamtube_get_container_classes( $content_width ) ) ); ?>">

		<h3 class="widget-title no-after mb-4">
			<?php esc_html_e( 'Connect with us', 'streamtube' );?>
		</h3>

		<ul class="social-list list-unstyled mb-0">
			<?php
			foreach ( $socials as $social => $url ):

				$classes = array();

				$classes[] = 'social__' . $social;

				/**
				 *
				 * Filter social item class
				 * 
				 * @param array $classes
				 * @param string $social
				 * @param array $socials
				 *
				 * @since 1.1
				 * 
				 */
				$classes = apply_filters( 'streamtube/footer/social/classes', $classes, $social, $socials );

				printf(
					'<li class="%1$s"><a target="_blank" href="%2$s"><span class="icon-%3$s icon-%3$s-circled"></span></a>',
					esc_attr( join( ' ', $classes ) ),
					esc_url( $url ),
					$social
				);
		
			endforeach;
			?>
		
		</ul>
	</div>
</div>