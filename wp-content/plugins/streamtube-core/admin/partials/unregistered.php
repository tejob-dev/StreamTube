<?php
/**
 *
 * @link       https://1.envato.market/mgXE4y
 * @since      1.3
 *
 * @package    WordPress
 * @subpackage StreamTube
 * @author     phpface <nttoanbrvt@gmail.com>
 */
if( ! defined( 'ABSPATH' ) ){
    exit;
}
?>
<div class="wrap">
	<h1><?php esc_html_e( 'Verify Purchase', 'streamtube-core' );?></h1>
	<div class="position-relative w-100" style="height: 80vh;">
		<div class="center-xy">
			<?php printf(
				esc_html__( '%s to unlock this feature.', 'streamtube-core' ),
				sprintf(
					'<a style="margin-bottom: 1rem" class="button button-primary d-block text-center" href="%s">%s</a>',
					esc_url( admin_url( 'themes.php?page=license-verification' ) ),
					esc_html__( 'Verify Purchase', 'streamtube-core' )
				)
			);?>
		</div>
	</div>
</div>