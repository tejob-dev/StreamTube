<?php
$is_logged_in = is_user_logged_in();
?>
<form class="form-ajax join-us position-relative">

	<?php
	/**
	 * Fires before form
	 *
	 * @since 1.0.9
	 */
	do_action( 'streamtube/core/form/join_us/before' );
	?>

	<?php streamtube_core_the_field_control( array(
		'label'			=>	sprintf(
			esc_html__( 'Reference %s', 'streamtube-core' ),
			ucwords( get_post_type() )
		),
		'name'			=>	'post_title',
		'value'			=>	get_the_title(),
		'type'			=>	'text',
		'data'			=>	array(
			'disabled'	=>	'disabled'
		)
	) );
	?>	

	<?php streamtube_core_the_field_control( array(
		'label'			=>	esc_html__( 'Your name', 'streamtube-core' ),
		'name'			=>	'name',
		'value'			=>	$is_logged_in ? wp_get_current_user()->display_name : '',
		'type'			=>	'text',
		'data'			=>	array(
			'disabled'	=>	$is_logged_in ? 'disabled' : '',
			'readonly'	=>	$is_logged_in ? 'readonly' : ''
		)
	) );
	?>	

	<?php streamtube_core_the_field_control( array(
		'label'			=>	esc_html__( 'Content', 'streamtube-core' ),
		'name'			=>	'content',
		'type'			=>	'textarea',
		'data'			=>	array(
			'rows'	=>	100
		)
	) );
	?>

	<?php
	/**
	 * Fires after form
	 *
	 * @since 1.0.9
	 */
	do_action( 'streamtube/core/form/join_us/after' );
	?>

	<input type="hidden" name="action" value="join_us">
	<input type="hidden" name="post_id" value="<?php the_ID(); ?>">

	<div class="form-submit d-flex">
		<button type="submit" class="btn btn-danger px-4 btn-next ms-auto">
			<?php esc_html_e( 'Send', 'streamtube-core' ); ?>
		</button>
	</div>

	<?php
	/**
	 * Fires after submit button
	 *
	 * @since 1.0.9
	 */
	do_action( 'streamtube/core/form/join_us/submit_after' );
	?>
</form>