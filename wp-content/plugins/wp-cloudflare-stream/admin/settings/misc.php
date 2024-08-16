<?php
/**
 * The Misc settings template file
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
				<label for="default_player">
					<?php esc_html_e( 'Cloudflare Player', 'wp-cloudflare-stream' );?>
				</label>
			</th>
			<td>
				<label>
					<?php printf(
						'<input name="wp_cloudflare_stream[default_player]" type="checkbox" id="default_player" %s class="regular-text">',
						checked( $settings['default_player'], 'on', false )
					)?>
					<?php esc_html_e( 'Enable Cloudflare Player instead of the default build-in player.', 'wp-cloudflare-stream' );?>
				</label>				
			</td>
		</tr>		

		<tr>
			<th scope="row">
				<label for="bulk_update">
					<?php esc_html_e( 'Bulk Update', 'wp-cloudflare-stream' );?>
				</label>
			</th>
			<td>
				<p>
				<?php
				printf(
					'<button type="button" class="button button-secondary" id="cloudflare-bulk-update-data" data-text1="%1$s" data-text2="%2$s">%1$s</button>',
					esc_html__( 'Bulk Update', 'wp-cloudflare-stream' ),
					esc_attr__( 'Updating ...', 'wp-cloudflare-stream' )
				);
				?>
				</p>			
				<p class="field-help">
					<?php esc_html_e( 'Bulk updating existing videos if any settings have been modified.', 'wp-cloudflare-stream' );?>
				</p>

				<ul id="updated-list" class="live-list d-none"></ul>
			</td>
		</tr>
	</tbody>
</table>
