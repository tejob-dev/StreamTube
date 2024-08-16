<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The Live Stream content template file
 *
 *
 * @link       https://1.envato.market/mgXE4y
 * @since      1.0.0
 *
 * @package    wp_cloudflare_stream
 * @subpackage wp_cloudflare_stream/admin/settings
 */

if ( is_singular() ) {
	$post_id = get_the_ID();
} else {
	$post_id = streamtube_core()->get()->post->get_edit_post_id();
}

$stream = wp_cloudflare_stream()->get()->post->get_stream( get_post_meta( $post_id, 'video_url', true ) );
$settings = WP_Cloudflare_Stream_Settings::get_settings();
?>

<div class="alert alert-info p-2 px-3 d-flex align-items-center">
	<?php if ( get_post_meta( $post_id, 'live_status', true ) == 'close' ) : ?>
		<?php esc_html_e( 'The live stream has concluded, and you cannot send a stream unless you reopen it.', 'wp-cloudflare-stream' ); ?>
	<?php else : ?>
		<?php esc_html_e( 'The live stream is now open, awaiting the stream.', 'wp-cloudflare-stream' ); ?>
	<?php endif; ?>

	<?php if ( is_singular() ) : ?>
		<button id="btn-collapse-live-settings" class="btn btn-danger shadow-none ms-auto collapsed"
			data-bs-toggle="collapse" data-bs-target="#collapse-live-settings" aria-expanded="false">
			<?php esc_html_e( 'Live Credentials', 'wp-cloudflare-stream' ); ?>
			<span class="btn__icon icon-angle-down"></span>
		</button>
	<?php endif; ?>
</div>

<?php printf(
	'<div class="%s" id="collapse-live-settings">',
	is_singular() ? 'collapse' : 'no-collapse'
); ?>

<div class="row">

	<?php if ( is_author() ) : ?>
		<div class="col-12 col-lg-6">
			<div class="live-stream__player shadow-sm mb-4">
				<?php get_template_part( 'template-parts/player', true, array(
					'post_id' => $post_id,
					'ratio' => '16x9',
					'source' => get_post_meta( $post_id, 'video_url', true )
				) ); ?>
			</div>
		</div>
	<?php endif; ?>

	<?php printf( '<div class="col-12 col-lg-%s">', is_author() ? '6' : '12' ); ?>
	<?php if ( apply_filters( 'wp_cloudflare_stream/display_playback_url', $settings['live_enable_hls_url'] ) && is_author() ) : ?>

		<div class="widget p-4 mb-4 bg-white shadow-sm">
			<?php
			printf(
				'<h6 class="mb-4">%s</h6>',
				esc_html__( 'Playbacks', 'wp-cloudflare-stream' )
			);
			?>

			<div class="widget-content">
				<?php
				streamtube_core_the_field_control( array(
					'label' => esc_html__( 'HLS Playback URL', 'wp-cloudflare-stream' ),
					'type' => 'text',
					'name' => 'hls_url',
					'readonly' => true,
					'value' => wp_cloudflare_stream()->get()->post->get_playback_url( $stream['stream']['uid'] )
				) );
				?>

				<?php
				streamtube_core_the_field_control( array(
					'label' => esc_html__( 'Dash Playback URL', 'wp-cloudflare-stream' ),
					'type' => 'text',
					'name' => 'hls_url',
					'readonly' => true,
					'value' => wp_cloudflare_stream()->get()->post->get_playback_url( $stream['stream']['uid'], array(), false )
				) );
				?>
			</div>

		</div>

	<?php endif; ?>

	<div class="widget p-4 mb-4 bg-white shadow-sm">
		<?php
		printf(
			'<h6 class="mb-4">%s</h6>',
			esc_html__( 'RMTP Credentials', 'wp-cloudflare-stream' )
		);
		?>

		<div class="widget-content">
			<?php
			streamtube_core_the_field_control( array(
				'label' => esc_html__( 'RMTP URL', 'wp-cloudflare-stream' ),
				'type' => 'text',
				'name' => 'rmtp_url',
				'readonly' => true,
				'value' => $stream['stream']['rtmps']['url']
			) );

			streamtube_core_the_field_control( array(
				'label' => esc_html__( 'RMTP Stream Key', 'wp-cloudflare-stream' ),
				'type' => 'password',
				'name' => 'rmtp_streamKey',
				'readonly' => true,
				'value' => $stream['stream']['rtmps']['streamKey']
			) );
			?>
		</div>
	</div>

	<div class="widget p-4 mb-4 bg-white shadow-sm">
		<?php
		printf(
			'<h6 class="mb-4">%s</h6>',
			esc_html__( 'SRT Credentials', 'wp-cloudflare-stream' )
		);
		?>

		<div class="widget-content">
			<?php
			streamtube_core_the_field_control( array(
				'label' => esc_html__( 'SRT URL', 'wp-cloudflare-stream' ),
				'type' => 'text',
				'name' => 'srt_url',
				'readonly' => true,
				'value' => $stream['stream']['srt']['url']
			) );

			streamtube_core_the_field_control( array(
				'label' => esc_html__( 'SRT Stream ID', 'wp-cloudflare-stream' ),
				'type' => 'password',
				'name' => 'srt_Stream_ID',
				'readonly' => true,
				'value' => $stream['stream']['srt']['streamId']
			) );

			streamtube_core_the_field_control( array(
				'label' => esc_html__( 'SRT Passphrase', 'wp-cloudflare-stream' ),
				'type' => 'password',
				'name' => 'srt_Passphrase',
				'readonly' => true,
				'value' => $stream['stream']['srt']['passphrase']
			) );
			?>
		</div>
	</div>

</div>
</div>

<div class="widget p-4 mb-4 bg-white shadow-sm">
	<form method="post" class="form-ajax">

		<div class="d-flex gap-3">
			<?php printf(
				'<button type="submit" class="btn btn-%s">%s</button>',
				$stream['status'] != 'off' ? 'danger' : 'primary',
				$stream['status'] != 'off' ? esc_html__( 'Close', 'wp-cloudflare-stream' ) : esc_html__( 'Open', 'wp-cloudflare-stream' )
			); ?>

			<?php printf(
				'<a href="%s" class="btn btn-secondary">%s</a>',
				esc_url( get_permalink( $post_id ) ),
				esc_html__( 'View', 'wp-cloudflare-stream' )
			); ?>
		</div>

		<?php printf(
			'<input type="hidden" name="post_id" value="%s">',
			$post_id
		); ?>

		<?php printf(
			'<input type="hidden" name="live_status" value="%s">',
			esc_attr( $stream['status'] )
		); ?>

		<?php printf(
			'<input type="hidden" name="action" value="%s">',
			'process_live_stream'
		); ?>
	</form>
</div>

</div>