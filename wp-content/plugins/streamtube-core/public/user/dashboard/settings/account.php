<?php
/**
 *
 * Account Privacy template file
 * 
 */
if( ! defined('ABSPATH' ) ){
    exit;
}

global $streamtube;

$userPrivacy 	= $streamtube->get()->user_privacy;

$is_deactivated = $userPrivacy->is_deactivated();
$settings 		= $userPrivacy->get_settings();
?>

<div class="widget mb-0">

	<div class="widget-title-wrap d-flex">
	    <h2 class="widget-title no-after">

	    	<?php 
	    	if( $is_deactivated ){
	    		esc_html_e( 'Reactivate Account', 'streamtube-core' );
	    	}else{

	    		if( $settings->deactivation_period ){
	    			esc_html_e( 'Deactivate Account', 'streamtube-core' );
	    		}else{
	    			esc_html_e( 'Delete Account', 'streamtube-core' );
	    		}
	    	}
	    	?>

	    </h2>
	</div>

	<div class="widget-content post-content">

		<?php if( ! $is_deactivated ) : ?>

			<?php 
			if( "" != $terms = $settings->deactivation_terms ):
				printf(
					'<div class="terms-content post-content mb-4">%s</div>',
					streamtube_core_get_elementor_builder_content( $terms )
				);
			endif; 
			?>
		<?php else:?>
			<?php 
			if( "" != $terms = $settings->reactivation_terms ):
				printf(
					'<div class="terms-content post-content mb-4">%s</div>',
					streamtube_core_get_elementor_builder_content( $terms )
				);
			endif; 
			?>
		<?php endif;?>

		<form method="post" class="form-ajax">

			<?php
			/**
			 *
			 * Fires before form
			 * 
			 */
			do_action( 'streamtube/core/user/dashboard/settings/account/form/before', $is_deactivated );
			?>		

			<?php if( $is_deactivated ): ?>

				<?php if( $settings->reactivation == 'manual' ): ?>

					<?php printf(
						'<button type="submit" class="btn btn-%s text-white">%s</button>',
						'success',
						esc_html__( 'Reactivate My Account', 'streamtube-core' )
					);?>

					<?php printf(
						'<input type="hidden" name="action" value="reactivate_account">'
					);?>

					<?php wp_nonce_field( 'reactivate_account', 'reactivate_account' );?>

				<?php endif;?>

			<?php else: ?>

				<?php if( $settings->deactivation_period ): ?>
					<?php printf(
						'<a class="btn btn-%s text-white" data-bs-toggle="modal" data-bs-target="#modal-delete-account">%s</a>',
						'danger',
						esc_html__( 'Deactivate My Account', 'streamtube-core' )	
					);?>
				<?php else:?>
					<?php printf(
						'<a class="btn btn-%s text-white" data-bs-toggle="modal" data-bs-target="#modal-delete-account">%s</a>',
						'danger',
						esc_html__( 'Delete My Account', 'streamtube-core' )	
					);?>
				<?php endif;?>

				<?php printf(
					'<input type="hidden" name="action" value="deactivate_account">'
				);?>

				<?php wp_nonce_field( 'deactivate_account', 'deactivate_account' );?>

			<?php endif;?>

			<?php
			/**
			 *
			 * Fires after form
			 * 
			 */
			do_action( 'streamtube/core/user/dashboard/settings/account/form/after', $is_deactivated );
			?>	
			<div class="modal fade" id="modal-delete-account" tabindex="-1">
				<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">

								<?php printf(
									esc_html__( 'Confirm Account %s', 'streamtube-core' ),
									$settings->deactivation_period ? 'Deactivation' : 'Deletion'
								);?>
								
							</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body bg-white">

							<?php
							/**
							 *
							 * @param is_deactivated
							 * 
							 */
							do_action( 'streamtube/core/user/dashboard/settings/account/confirm/before', $is_deactivated );
							?>		

							<?php
							streamtube_core_the_field_control( array(
								'type'			=>	'password',
								'label'			=>	esc_html__( 'Enter your password', 'streamtube-core' ),
								'name'			=>	'password',
								'value'			=>	''
							) );
							?>							

							<p>
								<?php printf(
									esc_html__( 'After you press %s, your account will be %s.', 'streamtube-core' ),
									'<strong class="fw-bold text-danger text-uppercase">'. ($settings->deactivation_period ? esc_html__( 'deactivate', 'streamtube-core' ) : esc_html__( 'delete', 'streamtube-core' )) .'</strong>',
									$settings->deactivation_period ? esc_html__( 'immediately deactivated', 'streamtube-core' ) : esc_html__( 'permanently deleted', 'streamtube-core' )
								);?>
							</p>

						</div>

						<?php
						/**
						 *
						 * @param is_deactivated
						 * 
						 */
						do_action( 'streamtube/core/user/dashboard/settings/account/confirm/after', $is_deactivated );
						?>						

						<div class="modal-footer bg-light d-flex justify-content-between gap-3">
							<button type="button" class="btn btn-sm px-3 btn-secondary" data-bs-dismiss="modal">
								<?php esc_html_e( 'Cancel', 'streamtube-core' );?>
							</button>
							<button type="submit" class="btn btn-sm px-3 btn-danger text-capitalize">
								<?php 
								if( $settings->deactivation_period ){
									esc_html_e( 'deactivate', 'streamtube-core' );	
								}else{
									esc_html_e( 'delete', 'streamtube-core' );
								}
								?>
							</button>
						</div>

						<?php
						/**
						 *
						 * @param is_deactivated
						 * 
						 */
						do_action( 'streamtube/core/user/dashboard/settings/account/confirm/submit/after', $is_deactivated );
						?>						
					</div>
				</div>
			</div>				
		</form>		
	</div>

</div>