<?php
/**
 *
 * The WooThanks Buy Form modal template file
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

<div class="modal fade" id="modal-woothanks" tabindex="-1" aria-labelledby="modal-woothanks-label" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-woothanks-label">
					<?php esc_html_e( 'Bonus', 'streamtube-core' );?>
				</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<?php
				if( function_exists( 'woothanks' ) ){
					echo woothanks()->buy_form();
				}
				?>
			</div>
		</div>
	</div>
</div>