<?php
if( ! defined('ABSPATH' ) ){
    exit;
}

/**
 *
 * Notify author after video publish
 *
 * @param  int|WP_Post $post
 * @param array $email_content
 * @return wp_mail()
 *
 * @since  2.1
 * 
 */
function streamtube_core_notify_author_after_video_publish( $post, $email_content = array() ){

	if( is_int( $post ) ){
		$post = get_post( $post );
	}

	if( $post->post_type == 'attachment' && get_post_type( $post->post_parent ) == 'video' ){
		$post = get_post( $post->post_parent );
	}

	$email_content = wp_parse_args( $email_content, array(
		'subject'	=>	'',
		'content'	=>	''
	) );

	$userdata = get_userdata( $post->post_author );

	$to = sprintf(
		'%s <%s>',
		$userdata->display_name,
		$userdata->user_email
	);

	$subject = sprintf(
		esc_html__( 'Your %s is now on %s', 'streamtube-core' ),
		$post->post_type,
		get_bloginfo( 'name' )
	);

	if( $email_content['subject'] ){
		$subject = $email_content['subject'];

		$subject = str_replace( '{user_display_name}', $userdata->display_name, 	$subject );
		$subject = str_replace( '{website_name}', get_bloginfo( 'name' ), 			$subject );
		$subject = str_replace( '{website_url}', untrailingslashit( home_url() ), 	$subject );
		$subject = str_replace( '{post_name}', $post->post_title, 					$subject );
		$subject = str_replace( '{post_url}', get_permalink( $post->ID ), 			$subject );
	}

	$message = sprintf(
		esc_html__( 'Your %s %s is now ready to watch on %s', 'streamtube-core'  ),
		$post->post_type,
		$post->post_title,
		get_bloginfo( 'name' )
	) . "\r\n\r\n";

	$message .= get_permalink( $post->ID ) . "\r\n\r\n";

	if( $email_content['content'] ){
		$message = $email_content['content'];

		$message = str_replace( '{user_display_name}', $userdata->display_name, 	$message );
		$message = str_replace( '{website_name}', get_bloginfo( 'name' ), 			$message );
		$message = str_replace( '{website_url}', untrailingslashit( home_url() ), 	$message );
		$message = str_replace( '{post_name}', $post->post_title, 					$message );
		$message = str_replace( '{post_url}', get_permalink( $post->ID ), 			$message );
	}

	$headers = array();
	$headers[] = 'Content-Type: text/html; charset=UTF-8';
	$headers[] = sprintf(
		'From: %s <%s>',
		get_option( 'blogname' ),
		get_option( 'new_admin_email' )
	);

	$email = compact( 'to', 'subject', 'message', 'headers' );

	/**
	 *
	 * filter the email before sending
	 * 
	 * @param array $email
	 * @param  int $post video post type
	 *
	 * @since  1.0.0
	 * 
	 */
	$email = apply_filters( 'streamtube_core_notify_author_after_video_publish', $email, $post );

	extract( $email );

	return wp_mail( $to, $subject, wpautop( $message ), $headers );	
}

/**
 *
 * Notify author after video encoding failed
 *
 * @param  int|WP_Post $post
 * @param array $email_content
 * @return wp_mail()
 *
 * @since  2.1
 * 
 */
function streamtube_core_notify_author_after_video_encoding_failed( $post, $email_content = array() ){

	if( is_int( $post ) ){
		$post = get_post( $post );
	}

	if( $post->post_type == 'attachment' && get_post_type( $post->post_parent ) == 'video' ){
		$post = get_post( $post->post_parent );
	}

	$email_content = wp_parse_args( $email_content, array(
		'subject'			=>	'',
		'content'			=>	'',
		'error_code'		=>	'',
		'error_message'		=>	''
	) );

	$userdata = get_userdata( $post->post_author );

	$to = sprintf(
		'%s <%s>',
		$userdata->display_name,
		$userdata->user_email
	);

	$subject = sprintf(
		esc_html__( 'Your %s encoding failed on %s', 'streamtube-core' ),
		$post->post_type,
		get_bloginfo( 'name' )
	);

	if( $email_content['subject'] ){
		$subject = $email_content['subject'];

		$subject = str_replace( '{user_display_name}', $userdata->display_name, 	$subject );
		$subject = str_replace( '{website_name}', get_bloginfo( 'name' ), 			$subject );
		$subject = str_replace( '{website_url}', untrailingslashit( home_url() ), 	$subject );
		$subject = str_replace( '{post_name}', $post->post_title, 					$subject );
		$subject = str_replace( '{post_url}', get_permalink( $post->ID ), 			$subject );
	}

	$message = sprintf(
		esc_html__( 'Your %s %s encoding failed on %s', 'streamtube-core'  ),
		$post->post_type,
		$post->post_title,
		get_bloginfo( 'name' )
	) . "\r\n\r\n";

	$message .= get_permalink( $post->ID ) . "\r\n\r\n";

	if( $email_content['content'] ){
		$message = $email_content['content'];

		$message = str_replace( '{user_display_name}', $userdata->display_name, 	$message );
		$message = str_replace( '{website_name}', get_bloginfo( 'name' ), 			$message );
		$message = str_replace( '{website_url}', untrailingslashit( home_url() ), 	$message );
		$message = str_replace( '{post_name}', $post->post_title, 					$message );
		$message = str_replace( '{post_url}', get_permalink( $post->ID ), 			$message );

		if( $email_content['error_code'] ){
			$message = str_replace( '{error_code}', $email_content['error_code'], 	$message );
		}

		if( $email_content['error_message'] ){
			$message = str_replace( '{error_message}', $email_content['error_message'], 	$message );
		}		
	}

	$headers = array();
	$headers[] = 'Content-Type: text/html; charset=UTF-8';
	$headers[] = sprintf(
		'From: %s <%s>',
		get_option( 'blogname' ),
		get_option( 'new_admin_email' )
	);

	$email = compact( 'to', 'subject', 'message', 'headers' );

	/**
	 *
	 * filter the email before sending
	 * 
	 * @param array $email
	 * @param  int $post video post type
	 *
	 * @since  1.0.0
	 * 
	 */
	$email = apply_filters( 'streamtube_core_notify_author_after_video_encoding_failed', $email, $post );

	extract( $email );

	return wp_mail( $to, $subject, wpautop( $message ), $headers );	
}

/**
 *
 * Notify author on post approve event
 *
 * @param  int $post_id
 * @param string $personal_message
 * @return wp_mail()
 *
 * @since  1.0.0
 * 
 */
function streamtube_core_notify_author_on_post_approve( $post_id = 0, $personal_message = '' ){

	if( ! $post_id || get_post_status( $post_id ) != 'publish' ){
		return false;
	}

	$post 				= get_post( $post_id );
	$author_data 		= get_userdata( $post->post_author );

	$email 				= array();

	$email['to']		= $author_data->user_email;

	$email['subject']	= sprintf(
		esc_html__( 'Your %s has been approved', 'streamtube-core' ),
		$post->post_type
	);

	$email['message']	= sprintf(
		esc_html__( 'Congratulations! Your %s %s has been approved', 'streamtube-core' ),
		$post->post_type,	
		$post->post_title
	) . "\r\n";

	if( $personal_message ){
		$email['message']	= $personal_message;
	}

	$email['message']	.= get_permalink( $post_id ) . "\r\n";

	$email['headers'] 	= array( 'Content-Type: text/plain; charset="' . get_option( 'blog_charset' ) );

	/**
	 *
	 * Filter the email before send
	 *
	 * @param  array $email
	 * @param  int $post_id
	 * @param  string $message
	 *
	 * @since  1.0.0
	 * 
	 */
	$email = apply_filters(  'streamtube_core_notify_author_on_post_approve_email' , $email , $post_id, $personal_message );

	extract( $email );

	wp_mail( $to, $subject, $message, $headers );
}

/**
 *
 * Notify author on post reject event
 *
 * @param  int $post_id
 * @param string $personal_message
 * @return wp_mail()
 *
 * @since  1.0.0
 * 
 */
function streamtube_core_notify_author_on_post_reject( $post_id = 0, $personal_message = '' ){

	if( ! $post_id || get_post_status( $post_id ) != 'reject' ){
		return false;
	}

	$post 				= get_post( $post_id );
	$author_data 		= get_userdata( $post->post_author );

	$email 				= array();

	$email['to']		= $author_data->user_email;

	$email['subject']	= sprintf(
		esc_html__( 'Your %s has been rejected', 'streamtube-core' ),
		$post->post_type
	);

	$email['message']	= sprintf(
		esc_html__( 'Sorry! Your %s %s has been rejected', 'streamtube-core' ),
		$post->post_type,	
		$post->post_title
	) . "\r\n";

	if( $personal_message ){
		$email['message']	= $personal_message;
	}

	$email['message']	.= get_permalink( $post_id ) . "\r\n";

	$email['headers'] 	= array( 'Content-Type: text/plain; charset="' . get_option( 'blog_charset' ) );

	/**
	 *
	 * Filter the email before send
	 *
	 * @param  array $email
	 * @param  int $post_id
	 * @param  string $message
	 *
	 * @since  1.0.0
	 * 
	 */
	$email = apply_filters(  'streamtube_core_notify_author_on_post_reject_email' , $email , $post_id, $personal_message );

	extract( $email );

	wp_mail( $to, $subject, $message, $headers );
}

/**
 *
 * Notify admin on video report event
 *
 * @param  int $post_id
 * @param int $category_id
 * @return wp_mail()
 *
 * @since  2.2.1
 * 
 */
function streamtube_core_notify_admin_on_report( $post_id = 0, $category = 0, $description = '' ){

	$title 				= get_the_title( $post_id );

	$email 				= array();
	$email['to']		= get_bloginfo( 'admin_email' );	

	$email['subject']	= sprintf(
		esc_html__( '%s: Report #%s - %s', 'streamtube-core' ),
		get_bloginfo( 'name' ),
		$post_id,
		$title
	);

	$email['message']	= sprintf(
		esc_html__( '%s reported video #%s %s', 'streamtube-core' ),
		'<strong>'. wp_get_current_user()->display_name .'</strong>',
		$post_id,
		$title
	) . "\r\n";

	if( $category ){
		$email['message']	.= sprintf(
			esc_html__( 'Report Category: %s', 'streamtube-core' ),
			get_term_by( 'term_id', $category, Streamtube_Core_Taxonomy::TAX_REPORT )->name
		) . "\r\n";
	}

	$email['message']	.= sprintf(
		esc_html__( 'Video URL: %s', 'streamtube-core' ),
		get_permalink( $post_id )
	) . "\r\n";	

	$email['message']	.= sprintf(
		esc_html__( 'Reported From: %s', 'streamtube-core' ),
		get_author_posts_url( get_current_user_id() )
	) . "\r\n";

	if( $description ){
		$email['message']	.= sprintf(
			esc_html__( 'Report description: %s', 'streamtube-core' ),
			$description
		) . "\r\n";	
	}

	$email['headers'] 	= array( 'Content-Type: text/plain; charset="' . get_option( 'blog_charset' ) );

	/**
	 *
	 * @since 2.2.1
	 * 
	 */
	$email = apply_filters(  'streamtube_core_notify_admin_on_report' , $email , $category, $description );	

	extract( $email );

	wp_mail( $to, $subject, $message, $headers );

}