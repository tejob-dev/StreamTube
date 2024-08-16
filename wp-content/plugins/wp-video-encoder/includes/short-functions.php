<?php

function wpve_get_encode_log_file_content( $attachment_id ){
    $encoder = new WP_Video_Encoder_Encoder( get_attached_file( $attachment_id ) );

    return $encoder->get_log_file_content();
}

/**
 *
 * Check if the attachment is in the queue
 * 
 * @param  int $attachment_id
 * @return null|array
 *
 * @since  1.0.0
 * 
 */
function wpve_is_attachment_queue( $attachment_id, $status = '' ){
	return wp_video_encoder()->get()->queue->get_queue_item( $attachment_id, $status );
}
/**
 *
 * Check if the attachment is waiting for encoding
 * 
 * @param  int $attachment_id
 * @return null|array
 *
 * @since  1.0.0
 * 
 */
function wpve_is_attachment_waiting( $attachment_id ){
	return wpve_is_attachment_queue( $attachment_id, 'waiting' );
}

/**
 *
 * Check if the attachment is encoding
 * 
 * @param  int $attachment_id
 * @return null|array
 *
 * @since  1.0.0
 * 
 */
function wpve_is_attachment_encoding( $attachment_id ){
	return wpve_is_attachment_queue( $attachment_id, 'encoding' );
}

/**
 *
 * Check if the attachment is encoded
 * 
 * @param  int $attachment_id
 * @return null|array
 *
 * @since  1.0.0
 * 
 */
function wpve_is_attachment_encoded( $attachment_id ){
	return wpve_is_attachment_queue( $attachment_id, 'encoded' );
}

/**
 *
 * Insert given attachment to the queue
 * 
 * @param  int $attachment_id
 * @return null|array
 *
 * @since  1.0.0
 *
 *	
 * 
 */
function wpve_insert_queue_item( $attachment_id ){

	$queue = wpve_is_attachment_queue( $attachment_id );

	if( ! $queue ){
		return wp_video_encoder()->get()->queue->insert_queue_item( $attachment_id );
	}

}