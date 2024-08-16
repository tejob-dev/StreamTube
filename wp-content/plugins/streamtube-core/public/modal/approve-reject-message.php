<?php
if( ! defined('ABSPATH' ) ){
    exit;
}
?>
<div class="modal fade" id="updatePostMessageModal" tabindex="-1" aria-labelledby="updatePostMessageModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<form class="form-ajax">
			<div class="modal-header">
				<h5 class="modal-title" id="updatePostMessageModalLabel">
					<?php esc_html_e( 'Private Message','streamtube-core' ); ?>
				</h5>
				<?php printf(
					'<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="%s"></button>',
					esc_attr__( 'Close','streamtube-core' )
				);?>
			</div>
			<div class="modal-body">
				<div class="form-floating my-3">
					<textarea style="height: 100px" class="form-control" id="message" name="message" rows="10" spellcheck="false"></textarea>
					<label for="message">
						<?php esc_html_e( 'Your message.', 'streamtube-core' ); ?>
					</label>
				</div>
			</div>
			<div class="modal-footer">
				<?php printf(
					'<button type="button" class="btn btn-sm px-3 btn-secondary" data-bs-dismiss="modal">%s</button>',
					esc_html__( 'Close','streamtube-core' )
				);?>
				
				<?php printf(
					'<button type="submit" class="btn btn-sm px-3 btn-primary">%s</button>',
					esc_html__( 'Update', 'streamtube-core' )
				);?>

				<input type="hidden" name="action" value="">
				<input type="hidden" name="post_id" value="">
			</div>
			</form>
		</div>
	</div>
</div>