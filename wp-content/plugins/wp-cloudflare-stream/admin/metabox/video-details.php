<?php
if( ! defined('ABSPATH' ) ){
    exit;
}
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
				<?php printf(
					'<label for="name">%s</label>',
					esc_html__( 'Name', 'wp-cloudflare-stream' )
				);?>
			</th>
			<td>
				<?php printf(
					'<input readonly name="wp_cloudflare_stream[name]" type="text" id="name" value="%s" class="regular-text w-100">',
					esc_attr( $args['stream']['meta']['name'] )
				)?>
			</td>
		</tr>		
		<tr>
			<th scope="row">
				<?php printf(
					'<label for="uid">%s</label>',
					esc_html__( 'UID', 'wp-cloudflare-stream' )
				);?>
			</th>
			<td>
				<?php printf(
					'<input readonly name="wp_cloudflare_stream[uid]" type="text" id="uid" value="%s" class="regular-text w-100">',
					esc_attr( $args['stream']['uid'] )
				)?>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<?php printf(
					'<label for="hls_playback">%s</label>',
					esc_html__( 'HLS Playback URL', 'wp-cloudflare-stream' )
				);?>
			</th>
			<td>
				<?php printf(
					'<input readonly name="wp_cloudflare_stream[hls_url]" type="text" id="hls_playback" value="%s" class="regular-text w-100">',
					esc_attr( $args['stream']['hls_url'] )
				)?>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<?php printf(
					'<label for="dash_playback">%s</label>',
					esc_html__( 'Dash Playback URL', 'wp-cloudflare-stream' )
				);?>
			</th>
			<td>
				<?php printf(
					'<input readonly name="wp_cloudflare_stream[dash_url]" type="text" id="dash_playback" value="%s" class="regular-text w-100">',
					esc_attr( $args['stream']['dash_url'] )
				)?>
			</td>
		</tr>		

		<?php if( $args['can_live'] ): ?>

		<tr>
			<th scope="row">
				<?php printf(
					'<label for="rtmp_url">%s</label>',
					esc_html__( 'RTMP URL', 'wp-cloudflare-stream' )
				);?>
			</th>
			<td>
				<?php printf(
					'<input readonly type="text" id="rtmp_url" value="%s" class="regular-text w-100">',
					esc_attr( $args['stream']['rtmps']['url'] )
				)?>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<?php printf(
					'<label for="rtmp_streamKey">%s</label>',
					esc_html__( 'RTMP Stream Key', 'wp-cloudflare-stream' )
				);?>
			</th>
			<td>
				<?php printf(
					'<input readonly type="text" id="rtmp_streamKey" value="%s" class="regular-text w-100">',
					esc_attr( $args['stream']['rtmps']['streamKey'] )
				)?>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<?php printf(
					'<label for="srt_url">%s</label>',
					esc_html__( 'SRT URL', 'wp-cloudflare-stream' )
				);?>
			</th>
			<td>
				<?php printf(
					'<input readonly type="text" id="rtmp_url" value="%s" class="regular-text w-100">',
					esc_attr( $args['stream']['srt']['url'] )
				)?>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<?php printf(
					'<label for="rtmp_streamId">%s</label>',
					esc_html__( 'SRT Stream ID', 'wp-cloudflare-stream' )
				);?>
			</th>
			<td>
				<?php printf(
					'<input readonly type="text" id="rtmp_streamId" value="%s" class="regular-text w-100">',
					esc_attr( $args['stream']['srt']['streamId'] )
				)?>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<?php printf(
					'<label for="rtmp_passphrase">%s</label>',
					esc_html__( 'SRT Passphrase', 'wp-cloudflare-stream' )
				);?>
			</th>
			<td>
				<?php printf(
					'<input readonly type="text" id="passphrase" value="%s" class="regular-text w-100">',
					esc_attr( $args['stream']['srt']['passphrase'] )
				)?>
			</td>
		</tr>		

		<?php else: ?>

		<tr>
			<th scope="row">
				<?php printf(
					'<label readonly for="thumbnail">%s</label>',
					esc_html__( 'Thumbnail URL', 'wp-cloudflare-stream' )
				);?>
			</th>
			<td>
				<?php printf(
					'<input readonly name="wp_cloudflare_stream[thumbnail]" type="text" id="thumbnail" value="%s" class="regular-text w-100">',
					esc_attr( $args['stream']['thumbnail'] )
				)?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php printf(
					'<label for="readyToStream">%s</label>',
					esc_html__( 'Read To Stream', 'wp-cloudflare-stream' )
				);?>
			</th>
			<td>
				<?php printf(
					'<input readonly disabled name="wp_cloudflare_stream[readyToStream]" type="checkbox" id="readyToStream" %s>',
					checked( $args['stream']['readyToStream'], true, false )
				)?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php printf(
					'<label for="status">%s</label>',
					esc_html__( 'Status', 'wp-cloudflare-stream' )
				);?>
			</th>
			<td>
				<?php printf(
					'<input readonly name="wp_cloudflare_stream[status]" type="text" id="status" value="%s" class="regular-text w-100">',
					esc_attr( $args['stream']['status']['state'] )
				)?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php printf(
					'<label for="errorReasonCode">%s</label>',
					esc_html__( 'Error Reason Code', 'wp-cloudflare-stream' )
				);?>
			</th>
			<td>
				<?php printf(
					'<input readonly name="wp_cloudflare_stream[errorReasonCode]" type="text" id="errorReasonCode" value="%s" class="regular-text w-100">',
					esc_attr( $args['stream']['status']['errorReasonCode'] )
				)?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php printf(
					'<label for="Error Reason Text">%s</label>',
					esc_html__( 'Error Reason Text', 'wp-cloudflare-stream' )
				);?>
			</th>
			<td>
				<?php printf(
					'<input readonly name="wp_cloudflare_stream[errorReasonText]" type="text" id="errorReasonText" value="%s" class="regular-text w-100">',
					esc_attr( $args['stream']['status']['errorReasonText'] )
				)?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php printf(
					'<label for="uploaded">%s</label>',
					esc_html__( 'Uploaded', 'wp-cloudflare-stream' )
				);?>
			</th>
			<td>
				<?php printf(
					'<input readonly name="wp_cloudflare_stream[uploaded]" type="text" id="uploaded" value="%s" class="regular-text w-100">',
					esc_attr( $args['stream']['uploaded'] )
				)?>
			</td>
		</tr>		
		<tr>
			<th scope="row">
				<?php printf(
					'<label for="created">%s</label>',
					esc_html__( 'Created', 'wp-cloudflare-stream' )
				);?>
			</th>
			<td>
				<?php printf(
					'<input readonly name="wp_cloudflare_stream[created]" type="text" id="created" value="%s" class="regular-text w-100">',
					esc_attr( $args['stream']['created'] )
				)?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php printf(
					'<label for="modified">%s</label>',
					esc_html__( 'Modified', 'wp-cloudflare-stream' )
				);?>
			</th>
			<td>
				<?php printf(
					'<input readonly name="wp_cloudflare_stream[modified]" type="text" id="modified" value="%s" class="regular-text w-100">',
					esc_attr( $args['stream']['modified'] )
				)?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php printf(
					'<label for="size">%s</label>',
					esc_html__( 'Size', 'wp-cloudflare-stream' )
				);?>
			</th>
			<td>
				<?php printf(
					'<input readonly name="wp_cloudflare_stream[size]" type="text" id="size" value="%s (%s)" class="regular-text w-100">',
					esc_attr( $args['stream']['size'] ),
					esc_attr ( size_format( $args['stream']['size'] ) )
				)?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php printf(
					'<label for="preview">%s</label>',
					esc_html__( 'Preview', 'wp-cloudflare-stream' )
				);?>
			</th>
			<td>
				<?php printf(
					'<input readonly name="wp_cloudflare_stream[preview_url]" type="text" id="preview_url" value="%s" class="regular-text w-100">',
					esc_attr( $args['stream']['preview_url'] )
				)?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php printf(
					'<label for="allowedOrigins">%s</label>',
					esc_html__( 'Allowed Origins', 'wp-cloudflare-stream' )
				);?>
			</th>
			<td>
				<?php printf(
					'<input readonly disabled name="wp_cloudflare_stream[allowedOrigins]" type="text" id="allowedOrigins" value="%s" class="regular-text w-100">',
					$args['stream']['allowedOrigins'] ? join(', ', $args['stream']['allowedOrigins'] ) : ''
				)?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php printf(
					'<label for="requireSignedURLs">%s</label>',
					esc_html__( 'Require Signed URLs', 'wp-cloudflare-stream' )
				);?>
			</th>
			<td>
				<?php printf(
					'<input readonly disabled name="wp_cloudflare_stream[requireSignedURLs]" type="checkbox" id="requireSignedURLs" %s>',
					checked( $args['stream']['requireSignedURLs'], true, false )
				)?>
			</td>
		</tr>		
		<tr>
			<th scope="row">
				<?php printf(
					'<label for="duration">%s</label>',
					esc_html__( 'Duration', 'wp-cloudflare-stream' )
				);?>
			</th>
			<td>
				<?php printf(
					'<input readonly name="wp_cloudflare_stream[duration]" type="text" id="duration" value="%s" class="regular-text w-100">',
					$args['stream']['duration']
				)?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php printf(
					'<label for="width">%s</label>',
					esc_html__( 'Width', 'wp-cloudflare-stream' )
				);?>
			</th>
			<td>
				<?php printf(
					'<input readonly name="wp_cloudflare_stream[width]" type="text" id="width" value="%s" class="regular-text w-100">',
					$args['stream']['input']['width']
				)?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php printf(
					'<label for="height">%s</label>',
					esc_html__( 'Height', 'wp-cloudflare-stream' )
				);?>
			</th>
			<td>
				<?php printf(
					'<input readonly name="wp_cloudflare_stream[height]" type="text" id="height" value="%s" class="regular-text w-100">',
					$args['stream']['input']['height']
				)?>
			</td>
		</tr>
		<?php endif;?>
	</tbody>

</table>

<?php
wp_nonce_field( 'cloudflare_stream_nonce', 'cloudflare_stream_nonce' );