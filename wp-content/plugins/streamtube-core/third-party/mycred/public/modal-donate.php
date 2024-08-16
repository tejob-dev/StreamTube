<?php
if( ! defined('ABSPATH' ) ){
    exit;
}
?>
<div class="modal fade" id="modal-donate" tabindex="-1" aria-labelledby="modal-donate-label" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header bg-light">
				<h5 class="modal-title" id="modal-donate-label">
					<?php echo $args['modal_title']; ?>
				</h5>
				<?php printf(
					'<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="%s"></button>',
					esc_attr__( 'Close','streamtube-core' )
				);?>
			</div>
			<div class="modal-body bg-white">
				<?php
				
				if( is_user_logged_in() ){
					load_template( untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/form-donate.php', true );
				}else{
					?>
					<div class="need-login text-muted text-center p-4">
						<?php printf(
							esc_html__( 'Please %s to continue', 'streamtube-core' ),
							sprintf(
								'<a class="fw-bold text-secondary" href="%s">%s</a>',
								esc_url( wp_login_url() ),
								esc_html__( 'login', 'streamtube-core' )
							)
						);?>
					</div>
					<?php						
				}

				?>
			</div>
		</div>
	</div>
</div>