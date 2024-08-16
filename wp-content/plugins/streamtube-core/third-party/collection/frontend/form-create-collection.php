<?php
/**
 * The Create Collection form template file
 *
 * @link       https://1.envato.market/mgXE4y
 * @since      1.0.0
 *
 * @package    WordPress
 * @subpackage StreamTube
 * @author     phpface <nttoanbrvt@gmail.com>
 */
if( ! defined( 'ABSPATH' ) ){
    exit;
}

global $post, $streamtube;

?>
<?php printf(
	'<form class="form-ajax %s mt-3" id="create-collection-form">',
	$args['collapse'] ? 'collapse' : ''
);?>
	
	<?php
	/**
	 *
	 * Fires before the form
	 * 
	 */
	do_action( 'streamtube/core/collection/add_form/name_before' );
	?>

	<?php
		streamtube_core_the_field_control( array(
		    'label'         =>  esc_html__( 'Name', 'streamtube-core' ),
		    'type'          =>  'text',
		    'name'          =>  'name',
		    'autocomplete'	=>	false
		) );
	?>

	<?php
		streamtube_core_the_field_control( array(
		    'label'         =>  esc_html__( 'Description', 'streamtube-core' ),
		    'type'          =>  'textarea',
		    'name'          =>  'description'
		) );
	?>	

	<?php
		streamtube_core_the_field_control( array(
		    'label'         =>  esc_html__( 'Privacy', 'streamtube-core' ),
		    'type'          =>  'select',
		    'name'          =>  'status',
		    'options'		=>	$streamtube->get()->collection->_get_statuses()
		) );
	?>

	<?php if( Streamtube_Core_Permission::can_manage_term( Streamtube_Core_Collection::TAX_COLLECTION ) ): ?>
		<?php
			streamtube_core_the_field_control( array(
			    'label'         =>  esc_html__( 'Searchable', 'streamtube-core' ),
			    'type'          =>  'checkbox',
			    'name'          =>  'searchable'
			) );
		?>	
	<?php endif;?>

	<?php
	/**
	 *
	 * Fires after the submit button
	 * 
	 */
	do_action( 'streamtube/core/collection/add_form/extra_field' );
	?>		

	<?php printf(
		'<input type="hidden" name="post_id" value="%s">',
		$post ? $post->ID : '0'
	);?>

	<input type="hidden" name="term_id" value="0">

	<input type="hidden" name="action" value="create_collection">

	<div class="d-flex gap-3">

		<?php if( $args['collapse'] ):?>
			<button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="collapse" href="#create-collection-form">
		<?php else:?>
			<button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">
		<?php endif;?>
			<span class="btn__icon icon-ccw"></span>
			<span class="btn__text"><?php esc_html_e( 'Cancel', 'streamtube-core' );;?></span>
		</button>	

		<button type="submit" class="btn btn-sm btn-primary">
			<span class="btn__icon icon-plus"></span>
			<span class="btn__text"><?php esc_html_e( 'Save', 'streamtube-core' );;?></span>
		</button>

		<?php
		/**
		 *
		 * Fires after the submit button
		 * 
		 */
		do_action( 'streamtube/core/collection/add_form/submit_after' );
		?>		

	</div>
</form>