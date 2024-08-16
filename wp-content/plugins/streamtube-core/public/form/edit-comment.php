<form class="form-ajax edit-comment position-relative d-none">

	<div class="wp-editor-wrap">
		<?php 
		streamtube_core_the_field_control( array(
			'name'			=>	'comment_content',
			'id'			=>	'_comment_content',
			'type'			=>	'textarea'
		) );
		?>
	</div>
	<input type="hidden" name="action" value="edit_comment">
	<input type="hidden" name="comment_ID" value="0">

	<div class="form-submit d-flex">

		<button type="submit" class="btn btn-danger px-4 btn-sm ms-auto">
			<span class="btn__icon icon-floppy"></span>
			<span class="btn__text"><?php esc_html_e( 'Save', 'streamtube-core' ); ?></span>
			
		</button>

	</div>
</form>