<?php
if( ! defined('ABSPATH' ) ){
    exit;
}
?>
<div class="modal fade" id="deletePostModal" tabindex="-1" aria-labelledby="deletePostModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<form class="form-ajax">
				<div class="modal-header bg-light">
					<h5 class="modal-title" id="deletePostModalLabel">
						<?php esc_html_e( 'Confirm Trash','streamtube-core' ); ?>
					</h5>
					<?php printf(
						'<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="%s"></button>',
						esc_attr__( 'Close','streamtube-core' )
					);?>
				</div>
				<div class="modal-body bg-white">
					<p>
						<?php esc_html_e( 'Are you sure you want to trash this post?', 'streamtube-core' ); ?>
					</p>

					<div class="post-list-wrap d-flex gap-3 mb-2 bg-light p-3 d-none"></div>

					<p>
						<?php printf(
							esc_html__( 'After you press %s, this post will be marked for deletion and removed shortly.', 'streamtube-core' ),
							'<strong class="text-danger">'. esc_html__( 'TRASH', 'streamtube-core' ) .'</strong>'
						); ?>
					</p>

				</div>
				<div class="modal-footer bg-light d-flex justify-content-between gap-3">
					<?php printf(
						'<button type="button" class="btn btn-sm px-3 btn-secondary" data-bs-dismiss="modal">%s</button>',
						esc_html__( 'Cancel','streamtube-core' )
					);?>
					
					<?php printf(
						'<button type="submit" class="btn btn-sm px-3 btn-danger">%s</button>',
						esc_html__( 'Trash', 'streamtube-core' )
					);?>

				</div>
				<input type="hidden" name="action" value="trash_post">
				<input type="hidden" name="post_id" value="">		
			</form>
		</div>
	</div>
</div>