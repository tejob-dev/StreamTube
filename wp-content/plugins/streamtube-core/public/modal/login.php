<?php
/**
 * The login form modal template file
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
<div class="modal fade" id="modal-login" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">
					<?php esc_html_e( 'Log In', 'streamtube' ); ?>
				</h5>
				<?php printf(
					'<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="%s"></button>',
					esc_attr__( 'Close', 'streamtube' )
				);?>
			</div>
			<div class="modal-body">
				<?php printf(
					'<div class="login-form-wrap p-2">%s</div>',
					streamtube_core_the_login_form()
				);?>
			</div>
		</div>
	</div>
</div>