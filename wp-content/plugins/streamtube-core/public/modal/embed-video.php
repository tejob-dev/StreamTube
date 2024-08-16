<?php
if( ! defined('ABSPATH' ) ){
    exit;
}
wp_enqueue_editor();
wp_enqueue_script( 'bootstrap-tagsinput' );
wp_enqueue_style( 'bootstrap-tagsinput' );
?>
<div class="modal fade" id="modal-embed" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal-upload-label" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
		<div class="modal-content step-wrap bg-white">
			<div class="modal-header bg-light">
				<h5 class="modal-title" id="modal-embed-label">
					<?php esc_html_e( 'Embed Video', 'streamtube-core' ); ?>
				</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php esc_attr_e( 'Close', 'streamtube-core' ); ?>"></button>
			</div>

			<div class="modal-body">

				<form id="form-embed-video" class="form-ajax form-regular upload-video-form" autocomplete="off">

					<?php streamtube_core_the_embed_form(); ?>

					<input type="hidden" name="action" value="import_embed">
					<input type="hidden" name="quick_update" value="1">

				</form>

			</div>

			<div class="modal-footer bg-light gap-3">

				<div class="form-submit d-flex">

					<button form="form-embed-video" type="submit" class="btn btn-danger px-4 text-white btn-next">
						<span class="icon-plus"></span>
						<?php esc_html_e( 'Import', 'streamtube-core' ); ?>
					</button>

				</div>

			</div>			

		</div>
	</div>
</div>