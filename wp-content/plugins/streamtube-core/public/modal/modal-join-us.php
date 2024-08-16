<?php
if( ! defined('ABSPATH' ) ){
    exit;
}
?>
<div class="modal fade" id="modal-join-us" tabindex="-1">
	<div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><?php esc_html_e( 'Join Us', 'streamtube-core' ); ?></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body position-relative">
				<?php 
				if( get_transient( 'request_join_us_' . get_current_user_id() ) === false ){
					streamtube_core_load_template( 'form/join-us.php', false );	
				}
				else{
					?>
					<div class="request-sent text-center p-4">
						<span class="icon-ok h4 text-success"></span>
						<h6 class="text-muted">
							<?php
							esc_html_e( 'You have already sent request.', 'streamtube-core' );
							?>
						</h6>
					</div>
					<?php
				}?>
			</div>
		</div>
	</div>
</div>