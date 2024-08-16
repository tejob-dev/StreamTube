<?php
/**
 * The paid icon template file
 *
 * @link       https://1.envato.market/mgXE4y
 * @since      2.2
 *
 * @package    WordPress
 * @subpackage StreamTube
 * @author     phpface <nttoanbrvt@gmail.com>
 */
if( ! defined( 'ABSPATH' ) ){
    exit;
}
?>
<div class="video-paid badge">
	<?php
	if( $args['paid_icon'] ){
		printf(
			'<span class="icon %s"></span>',
			sanitize_html_class( $args['paid_icon'] )
		);
	}
	?>

	<?php
	if( $args['paid_label'] ){
		printf(
			'<span class="text">%s</span>',
			esc_html( $args['paid_label'] )
		);
	}
	?>

</div>