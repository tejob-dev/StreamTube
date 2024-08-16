<?php
if( ! defined('ABSPATH' ) ){
    exit;
}

if( ! is_singular( 'video' ) ){
	return;
}

$user_id = get_current_user_id();

if( $user_id ){
	$_cache = sprintf( 'report_%s_%s', $user_id, get_the_ID() );
}
?>
<div class="modal fade" id="modal-report" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal-report-label" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content bg-white">
			<div class="modal-header bg-light">
				<h5 class="modal-title" id="modal-video-share-label">
					<?php esc_html_e( 'Report Video', 'streamtube-core' ); ?>
				</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php esc_html_e( 'Close', 'streamtube-core' ); ?>"></button>
			</div>
			<div class="modal-body">

				<?php if( $user_id ): ?>

					<?php if( false == $was_sent = get_transient( $_cache ) ) : ?>

						<?php streamtube_core_load_template( 'form/report-video.php' ); ?>

					<?php else: ?>

						<div class="text-muted text-center p-4">
							<span class="icon-ok-circled text-success"></span>
							<?php printf(
								esc_html__( 'You sent report %s ago', 'streamtube-core' ),
								human_time_diff( $was_sent, current_time( 'timestamp' ) )
							)?>
						</div>

					<?php endif; ?>

				<?php else: ?>

					<div class="need-login text-muted text-center p-4">

						<?php printf(
							esc_html__( 'Please %s to report this video.', 'streamtube-core' ),
							'<strong><a class="fw-bold text-secondary" href="'. esc_url( wp_login_url( get_permalink() ) ) .'">'. esc_html__( 'login', 'streamtube-core' ) .'</a></strong>'
						);?>

					</div>

				<?php endif; ?>
			</div><!--.modal-body-->
		</div>
	</div>
</div>