<thead>
	<tr>
		<th scope="col" class="col-id">
			<input class="form-check-input mt-0" type="checkbox" name="row_id" value="">
		</th>		
		<th scope="col" class="col-author">
			<?php esc_html_e( 'Author', 'streamtube-core' ); ?>
		</th>
		<th scope="col" class="col-comment">
			<?php esc_html_e( 'Comment', 'streamtube-core' ); ?>
		</th>

		<?php if( ! streamtube_core_is_edit_post_screen() ):?>

			<th scope="col" class="col-response-to">
				<?php esc_html_e( 'In response to', 'streamtube-core' ); ?>
			</th>

		<?php endif;?>

		<th scope="col" class="col-date">
			<?php printf(
				'<a class="text-body text-decoration-none" href="%s">%s</a>',
				esc_url( add_query_arg( array(
					'order'	=>	$args['order'] == 'ASC' ? 'DESC' : 'ASC'
				) ) ),
				esc_html__( 'Submitted on', 'streamtube-core' )
			);?>
		</th>
	</tr>
</thead>