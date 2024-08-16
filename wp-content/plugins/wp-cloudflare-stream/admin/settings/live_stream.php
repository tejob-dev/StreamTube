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

?>
<table class="form-table">
	
	<tbody>

		<tr>
			<th scope="row">
				<label for="live_stream_enable">
					<?php esc_html_e( 'Enable', 'wp-cloudflare-stream' );?>
				</label>
			</th>
			<td>
				<label>
					<?php printf(
						'<input name="wp_cloudflare_stream[live_stream_enable]" type="checkbox" id="live_stream_enable" %s class="regular-text">',
						checked( $settings['live_stream_enable'], 'on', false )
					)?>
					<?php esc_html_e( 'Enable Live Stream', 'wp-cloudflare-stream' );?>
				</label>				
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="live_ll_hls">
					<?php esc_html_e( 'Low Latency', 'wp-cloudflare-stream' );?>
				</label>
			</th>
			<td>
				<label>
					<?php printf(
						'<input name="wp_cloudflare_stream[live_ll_hls]" type="checkbox" id="live_ll_hls" %s class="regular-text">',
						checked( $settings['live_ll_hls'], 'on', false )
					)?>
					<?php esc_html_e( 'Enable Low Latency', 'wp-cloudflare-stream' );?>
				</label>				
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="live_timeout">
					<?php esc_html_e( 'Timeout', 'wp-cloudflare-stream' );?>
				</label>
			</th>
			<td>
				<label>
					<?php printf(
						'<input name="wp_cloudflare_stream[live_timeout]" type="number" id="live_timeout" class="regular-text" value="%s">',
						esc_attr( $settings['live_timeout'] )
					)?>
				</label>

				<p class="description">
					<?php esc_html_e( 'Specifies how long a live feed can be disconnected before it results in a new video being created, default is 15 minutes', 'wp-cloudflare-stream' );?>					
				</p>				
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="live_delete_recorded_period">
					<?php esc_html_e( 'Delete Recorded After Days', 'wp-cloudflare-stream' );?>
				</label>
			</th>
			<td>
				<label>
					<?php printf(
						'<input name="wp_cloudflare_stream[live_delete_recorded_period]" type="number" id="live_delete_recorded_period" class="regular-text" value="%s">',
						esc_attr( $settings['live_delete_recorded_period'] )
					)?>
				</label>

				<p class="description">
					<?php esc_html_e( 'Specifies a date and time for when the recording will be deleted.', 'wp-cloudflare-stream' );?>					
				</p>				
			</td>
		</tr>		

		<tr>
			<th scope="row">
				<label for="live_enable_hls_url">
					<?php esc_html_e( 'Display playback URLs', 'wp-cloudflare-stream' );?>
				</label>
			</th>
			<td>
				<label>
					<?php printf(
						'<input name="wp_cloudflare_stream[live_enable_hls_url]" type="checkbox" id="live_enable_hls_url" %s class="regular-text">',
						checked( $settings['live_enable_hls_url'], 'on', false )
					)?>
				</label>				
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="live_stream_domain">
					<?php esc_html_e( 'Custom Ingest Domain', 'wp-cloudflare-stream' );?>
				</label>
			</th>
			<td>
				<?php printf(
					'<input name="wp_cloudflare_stream[live_stream_domain]" type="text" id="live_stream_domain" value="%s" class="regular-text" placeholder="%s">',
					esc_attr( $settings['live_stream_domain'] ),
					esc_attr__( 'e.g: live.domain.com', 'wp-cloudflare-stream' )
				)?>

				<p class="description">
					<?php printf(
						esc_html__( 'Read %s', 'wp-cloudflare-stream' ),
						'<a target="_blank" href="https://developers.cloudflare.com/stream/stream-live/custom-domains/">'. esc_html__( 'Add custom ingest domains', 'wp-cloudflare-stream' ) .'</a>'
					)?>
				</p>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="live_stream_cap">
					<?php esc_html_e( 'Live Stream Capability', 'wp-cloudflare-stream' );?>
				</label>
			</th>
			<td>
				<?php printf(
					'<input name="wp_cloudflare_stream[live_stream_cap]" type="text" id="live_stream_cap" value="%s" class="regular-text">',
					esc_attr( $settings['live_stream_cap'] )
				)?>

				<p class="description">
					<?php esc_html_e( 'Admin and Editor can always start Live Stream without any restriction', 'wp-cloudflare-stream' );?>
				</p>
			</td>
		</tr>			
		<tr>
			<th scope="row">
				<label for="live_stream_status">
					<?php esc_html_e( 'Status', 'wp-cloudflare-stream' );?>
				</label>
			</th>
			<td>
				<select name="wp_cloudflare_stream[live_stream_status]" class="regular-text">
					
					<?php foreach ( WP_Cloudflare_Stream_Settings::get_live_stream_statuses() as $key => $value ): ?>

						<?php printf(
							'<option value="%s" %s>%s</option>',
							esc_attr( $key ),
							selected( $settings['live_stream_status'], $key, false ),
							esc_html( $value )
						)?>			

					<?php endforeach ?>

				</select>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="live_stream_multiple">
					<?php esc_html_e( 'Multiple Live Streams', 'wp-cloudflare-stream' );?>
				</label>
			</th>
			<td>
				<label>
					<?php printf(
						'<input name="wp_cloudflare_stream[live_stream_multiple]" type="checkbox" id="live_stream_multiple" %s class="regular-text">',
						checked( $settings['live_stream_multiple'], 'on', false )
					)?>
					<?php esc_html_e( 'Allow members to open Multiple Live Streams, this option does not apply to Admin or Editor', 'wp-cloudflare-stream' );?>
				</label>				
			</td>
		</tr>		

		<tr>
			<th scope="row">
				<label for="live_stream_webhook_url">
					<?php esc_html_e( 'Webhook URL', 'wp-cloudflare-stream' );?>
				</label>
			</th>
			<td>
				<?php printf(
					'<input type="url" id="live_stream_webhook_url" value="%s" class="regular-text">',
					esc_attr( $wp_cloudflare_stream->post->get_live_webhook_url() )
				)?>

				<p class="description">
					<?php printf(
						esc_html__( 'Do not share this URL publicly, read %s', 'wp-cloudflare-stream' ),
						'<a target="_blank" href="https://developers.cloudflare.com/stream/stream-live/webhooks/#subscribe-to-stream-live-webhooks">'. esc_html__( 'Subscribe to Stream Live Webhooks', 'wp-cloudflare-stream' ) .'</a>'
					);?>
				</p>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="live_stream_thumbnail_size">
					<?php esc_html_e( 'Thumbnail Image Size', 'wp-cloudflare-stream' );?>
				</label>
			</th>
			<td>
				<?php printf(
					'<input name="wp_cloudflare_stream[live_stream_thumbnail_size]" type="number" id="live_stream_thumbnail_size" value="%s" class="regular-text">',
					esc_attr( $settings['live_stream_thumbnail_size'] )
				)?>

				<p class="description">
					<?php printf(
						esc_html__( 'Maximum upload file size in MB, must be smaller than %sMB, 2MB is default.', 'wp-cloudflare-stream' ),
						wp_max_upload_size()/1024/1024
					);?>
				</p>
			</td>
		</tr>		
	</tbody>
</table>