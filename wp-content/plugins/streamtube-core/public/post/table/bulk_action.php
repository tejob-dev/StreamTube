<?php
if( ! defined('ABSPATH' ) ){
    exit;
}

global $post_type_screen;

if( ! array_key_exists( 'position', $args ) ){
	$args['position'] = 'top';
}

$_bulk_actions = array(
	'trash'		=>	esc_html__( 'Move to trash', 'streamtube-core' )
);

if( function_exists( 'wp_video_encoder' ) && current_user_can( 'edit_others_posts' ) && $post_type_screen == 'video' ){
	$_bulk_actions['encode'] = esc_html__( 'Bulk Encode', 'streamtube-core' );
}

if( isset( $_GET['post_status'] ) && $_GET['post_status'] == 'trash' ){
	$_bulk_actions = array(
		'restore'	=>	esc_html__( 'Restore', 'streamtube-core' ),
		'delete'	=>	esc_html__( 'Delete permanently', 'streamtube-core' )
	);
}

if( current_user_can( 'edit_others_posts' ) ){
	$_bulk_actions = array_merge( array(
		'approve'	=>	esc_html__( 'Approve', 'streamtube-core' ),
		'reject'	=>	esc_html__( 'Reject', 'streamtube-core' ),
		'pending'	=>	esc_html__( 'Pending Review', 'streamtube-core' )
	), $_bulk_actions );
}

$_bulk_actions = array_merge( array(
	''		=>	esc_html__( 'Bulk actions', 'streamtube-core' ),
), $_bulk_actions );
?>
<div class="bulk-action">
	<div class="input-group">
		<select name="bulk_action_<?php echo $args['position']; ?>" class="form-select">
			<?php foreach( $_bulk_actions as $option => $text ):?>

				<?php printf(
					'<option value="%s">%s</option>',
					esc_attr( $option ),
					esc_html( $text )
				);?>

			<?php endforeach;?>

		</select>

		<button type="submit" name="submit" value="bulk_action" class="btn btn-secondary btn-sm rounded-0">
			<?php esc_html_e( 'Apply', 'streamtube-core' ); ?>
		</button>
	</div>
</div>