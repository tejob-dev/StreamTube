<?php
if( ! defined('ABSPATH' ) ){
    exit;
}

$colspan = 6;
if( current_user_can( 'edit_others_posts' ) ){
	$colspan = 7;
}
?>
<tr class="not-found empty-rows">
	<td colspan="<?php echo $colspan; ?>">
		<div class="p-2 text-muted">
			<?php
			if( isset( $args['s'] ) && ! empty( $args['s'] ) ){

				esc_html_e( 'Nothing matched your search terms.', 'streamtube-core' );
			}else{
				esc_html_e( 'No posts were found.', 'streamtube-core' );
			}
			?>
		</div>
	</td>
</tr>
<script type="text/javascript">
	var columns = jQuery( 'table.table-posts thead th' ).length;
	jQuery( 'table.table-posts .empty-rows td' ).attr( 'colspan', columns );
</script>