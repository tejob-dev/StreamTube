<?php
/**
 * @since 2.1.7
 */
do_action( 'streamtube/core/form/embed_video/before' );
?>
<div id="embed-video-wrapper" class="upload-form__group">
	<?php
	/**
	 * @since 2.1.7
	 */
	do_action( 'streamtube/core/form/embed_video/container/before' );
	?>
	<?php streamtube_core_the_field_control( array(
		'label'			=>	esc_html__( 'Add From Source', 'streamtube-core' ),
		'name'			=>	'source',
		'type'			=>	'textarea',
		'required'		=>	true
	) );
	?>
	<?php
	/**
	 * @since 2.1.7
	 */
	do_action( 'streamtube/core/form/embed_video/container/after' );
	?>	
</div>
<?php
/**
 * @since 2.1.7
 */
do_action( 'streamtube/core/form/embed_video/after' );
?>