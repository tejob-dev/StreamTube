<?php
$bunnycdn = streamtube_core()->get()->bunnycdn;

?>
<table class="form-table">
	
	<tbody>
	
		<?php foreach ( $args['video_data'] as $key => $value ): ?>

			<?php if( ! empty( $value ) ): ?>
			<tr>
				<th scope="row">
					<?php printf(
						'<label for="%s">%s</label>',
						sanitize_key( $key ),
						$bunnycdn->bunnyAPI->get_video_details_field_name( $key )
					);?>
				</th>
				<td>
					<?php printf(
						'<input readonly name="video_data[%s]" type="text" id="%s" value="%s" class="regular-text w-100">',
						$key,
						sanitize_key( $key ),
						esc_attr( $bunnycdn->bunnyAPI->get_format_video_details_field_value( $key, $value ) )
					);?>
				</td>
			</tr>
			<?php endif; ?>
		<?php endforeach ?>

	</tbody>

</table>

<button type="button" id="refresh-bunny-data" class="button button-primary btn btn-primary">
	<?php esc_html_e( 'Refresh Data', 'streamtube-core' );?>
</button>

<script type="text/javascript">
	jQuery( document ).on( 'click', 'button#refresh-bunny-data', function(e){

		e.preventDefault();
		var button = jQuery(this);
		button.attr( 'disabled', 'disabled' );

		jQuery.post( '<?php echo admin_url( 'admin-ajax.php' )?>', {
			action: 'refresh_bunny_data',
			_wpnonce : '<?php echo wp_create_nonce( '_wpnonce' )?>',
			attachment_id : '<?php echo $args['post_id']; ?>'
		}, function( response ){
			if( response.success ){
				button.closest( 'form' ).trigger( 'submit' );	
			}else{
				alert( response.data );
			}
			
		} );

	} );
</script>

<?php
wp_nonce_field( 'bunnycdn_nonce', 'bunnycdn_nonce' );
