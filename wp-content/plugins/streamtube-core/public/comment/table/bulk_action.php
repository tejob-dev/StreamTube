<?php
$_bulk_actions = array(
	''			=>	esc_html__( 'Bulk actions', 'streamtube-core' ),
	'unapprove'	=>	esc_html__( 'Unapprove', 'streamtube-core' ),
	'approve'	=>	esc_html__( 'Approve', 'streamtube-core' ),
	'spam'		=>	esc_html__( 'Mark as spam', 'streamtube-core' ),
	'trash'		=>	esc_html__( 'Move to trash', 'streamtube-core' ),
	'delete'	=>	esc_html__( 'Delete permanently', 'streamtube-core' ),
);

if( ! array_key_exists( 'position', $args ) ){
	$args['position'] = 'top';
}

?>
<div class="bulk-action">
	<div class="row g-3">

		<div class="col-auto">

			<select name="bulk_action_<?php echo $args['position']; ?>" class="form-select form-select-sm">
				<?php foreach( $_bulk_actions as $option => $text ):?>

					<?php printf(
						'<option value="%s">%s</option>',
						esc_attr( $option ),
						esc_html( $text )
					);?>

				<?php endforeach;?>

			</select>

		</div>

		<div class="col-auto">

			<button type="submit" name="submit" value="bulk_action" class="btn btn-secondary btn-sm px-3">
				<?php esc_html_e( 'Apply', 'streamtube-core' ); ?>
			</button>

		</div>
	</div>
</div>