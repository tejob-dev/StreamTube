<tr class="table-warning">
	<td colspan="5">
		<div class="p-2 text-muted">
			<?php
			if( isset( $args['search'] ) && ! empty( $args['search'] ) ){

				esc_html_e( 'Nothing matched your search terms.', 'streamtube-core' );
			}else{
				esc_html_e( 'No comments were found.', 'streamtube-core' );
			}
			?>
		</div>
	</td>
</tr>