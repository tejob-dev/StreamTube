<?php
/**
 * The Sync settings template file
 *
 *
 * @link       https://1.envato.market/mgXE4y
 * @since      1.0.0
 *
 * @package    Wp_Cloudflare_Stream
 * @subpackage Wp_Cloudflare_Stream/admin/settings
 */

?>
<table class="form-table">
	
	<tbody>

		<tr>
			<th scope="row">
				<label for="auto-sync">
					<?php esc_html_e( 'Auto Sync', 'wp-cloudflare-stream' );?>
				</label>
			</th>
			<td>
				<label>
					<?php printf(
						'<input name="wp_cloudflare_stream[auto_sync]" type="checkbox" id="auto_sync" %s class="regular-text">',
						checked( $settings['auto_sync'], 'on', false )
					)?>
					<?php esc_html_e( 'Auto-sync uploads from Cloudflare Stream.', 'wp-cloudflare-stream' );?>
				</label>				
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="syn_post_author">
					<?php esc_html_e( 'Default Author ID', 'wp-cloudflare-stream' );?>
				</label>
			</th>
			<td>
				<?php printf(
					'<input type="number" name="wp_cloudflare_stream[syn_post_author]" id="syn_post_author" value="%s" class="regular-text" />',
					esc_attr( $settings['syn_post_author'] )
				);?>
				<p>
					<?php esc_html_e( 'Default owner ID for synchronized posts', 'wp-cloudflare-stream' );?>
				</p>
			</td>
		</tr>				

		<tr>
			<th scope="row">
				<label for="syn_post_status">
					<?php esc_html_e( 'Default Status', 'wp-cloudflare-stream' );?>
				</label>
			</th>
			<td>
				<select name="wp_cloudflare_stream[syn_post_status]" id="syn_post_status">
					<?php foreach ( get_post_statuses() as $key => $value ): ?>
						<?php printf(
							'<option value="%s" %s>%s</option>',
							esc_attr( $key ),
							selected( $key, $settings['syn_post_status'], false ),
							esc_html( $value )
						); ?>
					<?php endforeach ?>
				</select>
			</td>
		</tr>
	</tbody>
</table>
