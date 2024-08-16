<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

add_thickbox();

extract( $args );

if( ! $post_id || ! wp_attachment_is( 'video', $post_id ) || get_post_meta( $post_id, 'live_status', true ) ){
	return;
}

$_post = get_post( $post_id );

$post_parent = $_post ? $_post->post_parent : 0;

$queue_item = wp_video_encoder()->get()->queue->get_queue_item( $post_id );

if( ! $queue_item ){
	$queue_item['status'] = '';
}

echo '<div class="encode-status">';

	echo '<div class="encode-status__progress">';

		switch ( $queue_item['status'] ) {
			case 'waiting':
			case 'queue':
			case 'queuing':
				printf(
					'<span data-attachment-id="%1$s" data-parent-post="%2$s" class="badge bg-info badge-%3$s">%4$s</span>',
					$post_id,
					$post_parent,
					sanitize_html_class( $queue_item['status'] ),
					esc_html__( 'Waiting', 'wp-video-encoder' )
				);
			break;

			case 'encoding':

				printf(
					'<div data-attachment-id="%1$s" data-parent-post="%2$s" class="progress wpve-progress bg-dark"><div class="progress-bar progress-bar-striped progress-bar-animated bg-success px-2" style="width: %3$s">%3$s %4$s</div></div>',
					$post_id,
					$post_parent,
					$queue_item['percentage'] . '%',
					esc_html__( 'encoding', 'wp-video-encoder' )
				);
			break;

			case 'encoded':
				printf(
					'<span data-attachment-id="%1$s" data-parent-post="%2$s" class="badge bg-success badge-%3$s">%4$s</span>',
					$post_id,
					$post_parent,
					sanitize_html_class( $queue_item['status'] ),
					esc_html__( 'Encoded', 'wp-video-encoder' )
				);
			break;

			case 'fail':
			case 'failed':
				printf(
					'<span data-attachment-id="%1$s" data-parent-post="%2$s" class="badge bg-danger badge-%3$s">%4$s</span>',
					$post_id,
					$post_parent,			
					sanitize_html_class( $queue_item['status'] ),
					esc_html__( 'Failed', 'wp-video-encoder' )
				);
			break;

			default:
				if( current_user_can( 'edit_others_posts' ) ){
					printf(
						'<button type="button" data-attachment-id="%1$s" data-parent-post="%2$s" class="%3$s button-encode btn-sm btn-secondary">%4$s</button>',
						$post_id,
						$post_parent,
						is_admin() ? 'button' : 'btn',
						esc_html__( 'Encode', 'wp-video-encoder' )
					);
				}

			break;
		}

	echo '</div>';

	if( ! empty( $queue_item['status'] ) && is_admin() ){
		echo '<div class="encode-status__reencode">';
			printf(
				'<button style="margin-top: .5rem" type="button" data-attachment-id="%1$s" data-parent-post="%2$s" class="button-small button button-encode button-reencode btn btn-sm btn-secondary px-3">%3$s</button>',
				$post_id,
				$post_parent,
				esc_html__( 'Re-encode', 'wp-video-encoder' )
			);

			printf(
				'<a style="margin-top: .5rem; margin-left: .5rem" class="button button-small button-log thickbox" href="%s">%s</a>',
				esc_url( add_query_arg( array(
					'action'		=>	'view_encode_log',
					'attachment_id'	=>	$post_id,
					'TB_iframe'		=>	true,
					'width'			=>	800,
					'height'		=>	700
				), admin_url( 'admin-ajax.php' ) ) ),
				esc_html__( 'Log', 'wp-video-encoder' )
			);
		echo '</div>';
	}	

echo '</div>';