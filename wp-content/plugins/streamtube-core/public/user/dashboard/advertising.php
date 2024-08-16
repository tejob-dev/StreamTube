<?php
if( ! defined('ABSPATH' ) ){
    exit;
}

$can = Streamtube_Core_Permission::can_manage_vast_tag();

/**
 *
 * Fires before page header
 * 
 */
do_action( 'streamtube/core/user/dashboard/page_header/before' );

?>
<div class="page-head mb-3 d-flex gap-3 align-items-center">
	<h1 class="page-title h4">
		<?php esc_html_e( 'Advertising', 'streamtube-core' ); ?>
	</h1>
</div>

<?php
/**
 *
 * Fires after page header
 * 
 */
do_action( 'streamtube/core/user/dashboard/page_header/after' );

/**
 *
 * Fires before page content
 * 
 */
do_action( 'streamtube/core/user/dashboard/page_content/before' );
?>

<div class="page-content">
	<div class="widget bg-white p-4 shadow-sm">

		<?php if( ! $can ): ?>

			<p class="text-danger">
				<?php esc_html_e( 'You do not have permission to manage advertising', 'streamtube-core' );?>
			</p>

		<?php endif;?>

		<div class="widget-content">
			<form class="form form-advertising form-ajax" method="post">
				<?php
				streamtube_core_the_field_control( array(
					'label'			=>	esc_html__( 'VAST Tag URL', 'streamtube-core' ),
					'name'			=>	'vast_tag_url',
					'value'			=>	streamtube_core()->get()->user->get_vast_tag_url( get_queried_object_id() ),
					'description'	=>	esc_html__( 'Automatically display VAST based ads in your videos.', 'streamtube-core' ),
					'data'			=>	array(
						'disabled'	=>	! $can ? 'disabled' : false
					)
				) );
				?>

				<div class="d-flex">
					<?php printf(
						'<button type="submit" class="btn btn-primary ms-auto" %s>',
						! $can ? 'disabled' : ''
					)?>
						<span class="icon-floppy"></span>
						<span class="button-label">
							<?php esc_html_e( 'Save', 'streamtube-core' ); ?>
						</span>
					</button>
				</div>

				<input type="hidden" name="action" value="update_advertising">	
			</form>
		</div>
	</div>
</div>

<?php

/**
 *
 * Fires after page content
 * 
 */
do_action( 'streamtube/core/user/dashboard/page_content/after' );