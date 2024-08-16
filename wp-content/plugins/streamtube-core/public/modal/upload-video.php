<?php
if( ! defined('ABSPATH' ) ){
    exit;
}

wp_enqueue_editor();
wp_enqueue_script( 'bootstrap-tagsinput' );
wp_enqueue_style( 'bootstrap-tagsinput' );

?>
<div class="modal fade" id="modal-upload" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal-upload-label" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
		<div class="modal-content step-wrap bg-white">
			
			<div class="modal-header bg-light">
				<h5 class="modal-title" id="modal-upload-label">
					<?php esc_html_e( 'Upload Video', 'streamtube-core' ); ?>
				</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php esc_attr_e( 'Close', 'streamtube-core' ); ?>"></button>
			</div>

			<div class="modal-body">

				<form id="form-submit-video" class="form-ajax form-steps upload-video-form" autocomplete="off">

					<?php streamtube_core_the_upload_form(); ?>

					<input type="hidden" name="action" value="upload_video">
					<input type="hidden" name="quick_update" value="1">
				</form>

			</div>

			<div class="modal-footer bg-light gap-3 d-none">

				<button id="form-submit-video-button" form="form-submit-video" type="submit" class="btn btn-danger px-4 text-white btn-next">
					<?php esc_html_e( 'Save Changes', 'streamtube-core' ); ?>
				</button>

			</div>
		</div>
	</div>
</div>