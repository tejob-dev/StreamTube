<?php
/**
 * The General settings template file
 *
 *
 * @link       https://1.envato.market/mgXE4y
 * @since      1.0.0
 *
 * @package    Wp_Cloudflare_Stream
 * @subpackage Wp_Cloudflare_Stream/admin/settings
 */

$webhook = WP_Cloudflare_Stream_Settings::get_setting( 'webhook' );

?>
<table class="form-table">
	
	<tbody>

		<tr>
			<th scope="row">
				<label for="upload_type">
					<?php esc_html_e( 'Upload Type', 'wp-cloudflare-stream' );?>
				</label>
			</th>
			<td>
				<select name="wp_cloudflare_stream[upload_type]" class="regular-text">
					
					<?php foreach ( WP_Cloudflare_Stream_Settings::get_upload_types() as $key => $value ): ?>

						<?php printf(
							'<option value="%s" %s>%s</option>',
							esc_attr( $key ),
							selected( $settings['upload_type'], $key, false ),
							esc_html( $value )
						)?>			

					<?php endforeach ?>

				</select>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="allow_formats">
					<?php esc_html_e( 'Allow Formats', 'wp-cloudflare-stream' );?>
				</label>
			</th>
			<td>
				<?php printf(
					'<input name="wp_cloudflare_stream[allow_formats]" type="text" id="allow_formats" value="%s" class="regular-text">',
					esc_attr( $settings['allow_formats'] )
				)?>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="enable_mp4_download">
					<?php esc_html_e( 'Enable MP4 Download', 'wp-cloudflare-stream' );?>
				</label>
			</th>
			<td>
				<label for="enable_mp4_download">
					<?php printf(
						'<input name="wp_cloudflare_stream[enable_mp4_download]" type="checkbox" id="enable_mp4_download" %s>',
						checked( $settings['enable_mp4_download'], 'on', false )
					);?>
					<?php esc_html_e( 'Allow downloading MP4 file', 'wp-cloudflare-stream' );?>
				</label>
			</td>
		</tr>		

		<tr>
			<th scope="row">
				<label for="delete_original_file">
					<?php esc_html_e( 'Delete Original File', 'wp-cloudflare-stream' );?>
				</label>
			</th>
			<td>
				<label for="delete_original_file">
					<?php printf(
						'<input name="wp_cloudflare_stream[delete_original_file]" type="checkbox" id="delete_original_file" %s>',
						checked( $settings['delete_original_file'], 'on', false )
					);?>
					<?php esc_html_e( 'Auto Delete the original file after processing completed', 'wp-cloudflare-stream' );?>
				</label>

				<p class="description" style="color: red">
					<?php esc_html_e( 'Warning: Original files will be permanently deleted from the WordPress media library.', 'wp-cloudflare-stream' );?>
				</p>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="auto_thumbnail">
					<?php esc_html_e( 'Auto Import Thumbnail', 'wp-cloudflare-stream' );?>
				</label>
			</th>
			<td>
				<label for="auto_thumbnail">
					<?php printf(
						'<input name="wp_cloudflare_stream[auto_thumbnail]" type="checkbox" id="auto_thumbnail" %s>',
						checked( $settings['auto_thumbnail'], 'on', false )
					);?>
					<?php esc_html_e( 'Auto Import Thumbnail Image', 'wp-cloudflare-stream' );?>
				</label>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="auto_gif_thumbnail">
					<?php esc_html_e( 'Auto Import Gif Thumbnail', 'wp-cloudflare-stream' );?>
				</label>
			</th>
			<td>
				<label for="auto_gif_thumbnail">
					<?php printf(
						'<input name="wp_cloudflare_stream[auto_gif_thumbnail]" type="checkbox" id="auto_gif_thumbnail" %s>',
						checked( $settings['auto_gif_thumbnail'], 'on', false )
					);?>
					<?php esc_html_e( 'Auto Import Gif Thumbnail Image', 'wp-cloudflare-stream' );?>
				</label>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="auto_publish">
					<?php esc_html_e( 'Auto Publish', 'wp-cloudflare-stream' );?>
				</label>
			</th>
			<td>
				<label for="auto_publish">
					<?php printf(
						'<input name="wp_cloudflare_stream[auto_publish]" type="checkbox" id="auto_publish" %s>',
						checked( $settings['auto_publish'], 'on', false )
					);?>
					<?php esc_html_e( 'Auto publish Parent Post after file processing completed', 'wp-cloudflare-stream' );?>
				</label>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="webhook">
					<?php esc_html_e( 'Webhook', 'wp-cloudflare-stream' );?>
				</label>
			</th>
			<td>
				<?php printf(
					'<button id="cloudflare-install-upload-webhook" type="button" class="button button-%s">%s</button>',
					! $webhook ? 'secondary' : 'primary',
					! $webhook ? esc_html__( 'Install Webhook', 'wp-cloudflare-stream' ) : esc_html__( 'Installed', 'wp-cloudflare-stream' )
				);?>
			</td>
		</tr>

	</tbody>

</table>