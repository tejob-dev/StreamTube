<?php
/**
 *
 * The WooThanks Buy Button template file
 * 
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
<div class="button-buy-wrap">

	<?php printf(
		'<button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modal-woothanks">%s%s</button>',
		'<span class="btn__icon icon-heart me-1"></span>',
		esc_html__( 'Thanks', 'streamtube-core' )
	);?>

</div>