<?php
if( ! defined('ABSPATH' ) ){
    exit;
}
?>
<div class="modal fade" id="modal-live_stream" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal-upload-label" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
		<div class="modal-content step-wrap bg-white">
			<div class="modal-header bg-light">
				<h5 class="modal-title" id="modal-embed-label">
					<?php esc_html_e( 'Go Live', 'wp-cloudflare-stream' ); ?>
				</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php esc_attr_e( 'Close', 'wp-cloudflare-stream' ); ?>"></button>
			</div>

			<div class="modal-body">

				<form id="form-live-stream" class="form-ajax form-live-stream">

					<?php
					/**
					 * @since 2.1.7
					 */
					do_action( 'streamtube/core/form/live_stream/before' );
					?>		

					<div class="upload-form__group">			

						<div class="row">

							<div class="col-12 col-lg-4">

						        <div class="thumbnail-group mb-4">

						            <div class="post-thumbnail ratio ratio-16x9 position-relative bg-dark mb-2 shadow rounded">
						            </div>

						            <label class="text-center w-100 mt-3">
						                <a class="btn btn-secondary btn-sm">
						                	<span class="icon-file-image"></span>
						                    <?php esc_html_e( 'Upload Image', 'wp-cloudflare-stream' ); ?>
						                </a>
						                <input type="file" name="featured-image" accept=".jpg,.jpeg,.png,.gif,.bmp,.tiff" class="d-none">
						            </label>
						        </div>
							</div>

							<div class="col-12 col-lg-8">
								<?php streamtube_core_the_field_control( array(
									'label'			=>	esc_html__( 'Title', 'wp-cloudflare-stream' ),
									'name'			=>	'name',
									'type'			=>	'text',
									'required'		=>	true,
									'description'	=>	esc_html__( 'Add a title that describes your stream', 'wp-cloudflare-stream' )
								) );
								?>

								<?php streamtube_core_the_field_control( array(
									'label'			=>	esc_html__( 'Description', 'wp-cloudflare-stream' ),
									'name'			=>	'description',
									'type'			=>	'textarea',
									'required'		=>	false,
									'description'	=>	esc_html__( 'Tell viewers more about your stream', 'wp-cloudflare-stream' )
								) );
								?>

							</div>
						</div>

					</div>

					<input type="hidden" name="action" value="live_stream">
					<input type="hidden" name="quick_update" value="1">

					<?php
					/**
					 * @since 2.1.7
					 */
					do_action( 'streamtube/core/form/live_stream/after' );
					?>					
				</form>

			</div>

			<div class="modal-footer bg-light gap-3">

				<div class="form-submit d-flex">

					<button form="form-live-stream" type="submit" class="btn btn-danger px-4 text-white btn-next">
						<span class="icon-plus"></span>
						<?php esc_html_e( 'Start', 'wp-cloudflare-stream' ); ?>
					</button>

				</div>

			</div>			

		</div>
	</div>
</div>