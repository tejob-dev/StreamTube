<?php
if( ! defined('ABSPATH' ) ){
    exit;
}
wp_enqueue_style( 'cropperjs' );
wp_enqueue_script( 'cropperjs' );

$user_id 	   	= get_current_user_id();
$field 			= 'profile';

$image_url 		= streamtube_core()->get()->user->get_custom_profile_image_url( $user_id );
?>
<div class="widget mb-0">

	<div class="widget-content">
		<form class="form form-profile form-user-photo form-ajax">

			<?php
			/**
			 *
			 * Fires before form
			 * 
			 */
			do_action( 'streamtube/core/user/dashboard/settings/profile_photo/form/before' );
			?>

			<?php
			$opts = array(
				'viewMode'				=>	3,
				'dragMode'				=>	'move',
				'cropBoxMovable'		=>	false,
				'aspectRatio'			=>	'26/5',
				'minCropBoxWidth'		=>	1300,
				'minCropBoxHeight'		=>	250
			);
			?>
			<div class="d-flex flex-column">
				<?php printf(
					'<div class="cropper-wrap border w-100 overflow-hidden" style="width: %spx; height: %spx">',
					esc_attr( $opts['minCropBoxWidth'] ),
					esc_attr( $opts['minCropBoxHeight'] )
				);?>

					<?php if( $image_url ) :
						printf(
							'<button type="button" class="btn-spinner btn btn-danger ajax-elm btn-sm p-1 position-absolute top-0 end-0" data-action="%s" data-params="%s">',
							'delete_user_photo',
							esc_attr( $field )
						);
						?>
							<span class="btn__icon icon-minus"></span>
						</button>
					<?php endif; ?>

					<?php printf(
						'<img data-option="%s" class="cropper-img" src="%s">',
						esc_attr( json_encode($opts) ),
						$image_url ? $image_url : ''
					);?>
				</div>

				<div class="form-submit mx-auto mt-4">

					<div class="d-md-flex d-block gap-3 align-items-start">
						<label class="btn btn-info text-white mb-2 d-block">
							<input type="file" name="file" class="cropper-input d-none" accept=".jpg,.jpeg,.png,.gif,.bmp,.tiff">
							<span class="icon-picture"></span>
							<span class="button-label">
								<?php esc_html_e( 'Browse image', 'streamtube-core' );?>
							</span>
						</label>

						<button type="submit" class="btn btn-primary ms-auto mb-2 d-block">
							<span class="icon-floppy"></span>
							<span class="button-label">
								<?php esc_html_e( 'Save Changes', 'streamtube-core' ); ?>
							</span>
						</button>
					</div>

					<input type="hidden" name="image_data">

					<input type="hidden" name="action" value="update_user_photo">

					<?php printf(
						'<input type="hidden" name="field" value="%s">',
						esc_attr( $field )
					);?>

					<?php printf(
						'<input type="hidden" name="request_url" value="%s">',
						streamtube_core()->get()->rest_api['user']->get_rest_url( '/upload-photo' )
					);?>					

				</div>
			</div>

			<?php
			/**
			 *
			 * Fires after submit field
			 * 
			 */
			do_action( 'streamtube/core/user/dashboard/settings/profile_photo/form/after' );
			?>

		</form>
	</div>
</div>	    