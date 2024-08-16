<?php
/**
 * Define the comment functionality
 *
 *
 * @link       https://themeforest.net/user/phpface
 * @since      1.0.0
 *
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 */

/**
 *
 * @since      1.0.0
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */

if( ! defined('ABSPATH' ) ){
    exit;
}

class Streamtube_Core_Comment {

	/**
	 *
	 * Get comment content
	 * 
	 * @param  int  $comment_id
	 * @param  boolean $email_filter
	 * @return WP_Error|Object
	 *
	 * @since 1.0.8
	 * 
	 */
	protected function get_comment( $comment_id = 0, $email_filter = false ){

		$comment = get_comment( $comment_id );

		if( ! $comment ){
			return new WP_Error(
				'comment_not_found',
				esc_html__( 'Comment was not found', 'streamtube-core' )
			);
		}

		if( $email_filter ){
			unset( $comment->comment_author_email );

			/**
			 *
			 * Filter comment object before returning
			 * 
			 * @var object $comment
			 *
			 * @since 1.0.8
			 * 
			 */
			$comment = apply_filters( 'streamtube/core/comment/get_comment', $comment );			
		}

		$comment->comment_content_filtered = force_balance_tags( wpautop( wp_trim_words( $comment->comment_content, 20 ) ) );

		$comment->comment_content_autop = wpautop( $comment->comment_content );

		return $comment;
	}

	/**
	 *
	 * Post comment on POST request
	 *
	 * 
	 * @return WP_Error|array
	 *
	 * @since  1.0.0
	 * 
	 */
	private function post_comment(){

		$comment_output = $comments_number = '';

		$comment = wp_handle_comment_submission( wp_unslash( $_POST ) );

		if( is_wp_error( $comment ) ){
			return $comment;
		}

		if( ! function_exists( 'streamtube_comment_callback' ) ){
			return new WP_Error(
				'no_comment_template',
				esc_html__( 'Comment template was not found', 'streamtube-core' )
			);
		}

		ob_start();

		$GLOBALS['comment'] = $comment;

		streamtube_comment_callback( 
			$comment, streamtube_comment_list_args(), 
			streamtube_get_comment_depth($comment)+1 
		);

		$comment_output = ob_get_clean() . '</li>';

		$comments_number = get_comments_number_text( false, false, false, $comment->comment_post_ID );

		return compact( 'comment', 'comment_output', 'comments_number' );
	}

	/**
	 *
	 * Check if current user is comment author
	 * 
	 * @param  int|object  $comment
	 * @return boolean
	 * 
	 */
	public function is_comment_author( $comment ){

		if( is_int( $comment ) ){
			$comment = $this->get_comment( $comment );
		}

		if( ! $comment || ! is_user_logged_in() ){
			return false;
		}

		return $comment->user_id == get_current_user_id() ? true : false;
	}

	/**
	 *
	 * Check if current user can moderate comments
	 * 
	 * @param  integer $comment_id
	 * @return true|false
	 *
	 * @since  1.0.0
	 * 
	 */
	public function can_moderate_comments( $comment = null ){

		/**
		 *
		 * Filter moderate_comments_cap
		 * 
		 * @param $cap moderate_comments is default
		 *
		 * @since  1.0.0
		 * 
		 */
		$cap = apply_filters( 'moderate_comments_cap', 'moderate_comments' );

		if( ! $comment ){
			return current_user_can( $cap ); 
		}

		if( is_int( $comment ) ){
			$comment = $this->get_comment( $comment );
		}		

		$post = get_post( $comment->comment_post_ID );

		if( $post && current_user_can( 'edit_post', $comment->comment_post_ID ) ){
			return true;
		}

		return current_user_can( $cap, $comment );
	}

	/**
	 *
	 * Check if current user can edit given comment
	 * 
	 * @param  integer $comment_id
	 * @return boolean
	 * 
	 */
	public function can_edit_comment( $comment = null ){

		if( is_int( $comment ) ){
			$comment = $this->get_comment( $comment );
		}

		if( $this->can_moderate_comments( $comment ) ){
			return true;
		}

		if( $this->is_comment_author( $comment ) ){
			$can = get_option( 'comment_edit' ) ? true : false;

			/**
			 *
			 * Filter $can
			 * 
			 */
			return apply_filters( 'streamtube/core/comment/can_edit', $can, $comment );
		}

		return false;
	}

	/**
	 *
	 * Check if current user can delete given comment
	 * 
	 * @param  integer $comment_id
	 * @return boolean
	 * 
	 */
	public function can_delete_comment( $comment = null ){

		if( is_int( $comment ) ){
			$comment = $this->get_comment( $comment );
		}

		if( $this->can_moderate_comments( $comment ) ){
			return true;
		}

		if( $this->is_comment_author( $comment ) ){
			$can = get_option( 'comment_delete' ) ? true : false;

			/**
			 *
			 * Filter $can
			 * 
			 */
			return apply_filters( 'streamtube/core/comment/can_delete', $can, $comment );
		}

		return false;
	}

	/**
	 *
	 * Check if current user can report comment
	 * 
	 * @return boolean
	 */
	public function can_report_comment( $comment = null ){

		if( ! get_option( 'comment_report' ) ){
			return false;
		}

		if( is_int( $comment ) ){
			$comment = $this->get_comment( $comment );
		}

		$user_id = is_user_logged_in();

		if( ! $user_id ){
			return false;
		}

		// Always return true if current user can moderate comments
		if( $this->can_moderate_comments( $comment ) ){
			return true;
		}

		$can_report = apply_filters( 'streamtube/core/comment/can_report', $user_id, $comment );

		if( "" != $cap = get_option( 'comment_report_role', 'read' ) ){
			if( ! current_user_can( $cap ) ){
				$can_report = false;
			}
		}

		return $can_report;
	}

	/**
	 *
	 * Do approve and unapprove given comment
	 * 
	 * @param  integer $comment_id
	 * @return WP_Error|array
	 *
	 * @since  1.0.0
	 * 
	 */
	private function moderate_comment( $comment_id = 0 ){

		$status = wp_get_comment_status( $comment_id ); // unapproved

		if( $status != 'approved' ){
			return wp_set_comment_status( $comment_id, 'approve' );
		}
		else{
			return wp_set_comment_status( $comment_id, 'hold' );	
		}
	}

	/**
	 *
	 * Report comment
	 * 
	 * @param  int|WP_Comment $comment
	 * 
	 */
	public function report_comment( $comment = null, $report_content = '' ){

		if( is_int( $comment ) ){
			$comment = $this->get_comment( $comment );
		}

		if( ! is_object( $comment ) || ! $this->can_report_comment( $comment ) ){
			return new WP_Error(
				'no_permission',
				esc_html__( 'Sorry, you do not have permission to report this comment', 'streamtube-core' )
			);
		}

		if( empty( $report_content ) ){
			return new WP_Error(
				'empty_report_content',
				esc_html__( 'Report content is required', 'streamtube-core' )
			);			
		}

		$content = array(
			'content'	=>	$report_content,
			'user_id'	=>	get_current_user_id(),
			'date'		=>	current_time( 'timestamp' )
		);

		update_comment_meta( $comment->comment_ID, 'report_content', $content );

		do_action( 'streamtube/core/comment/reported', $comment, $content );

		return $content;
	}

	/**
	 *
	 * Notify Author after reported sent
	 * 
	 */
	public function report_comment_notify( $comment, $content ){

		if( ! get_option( 'comment_report_notify', 'on' ) ){
			return false;
		}

		$_post 	= get_post( $comment->comment_post_ID );

		$author	= get_userdata( $_post->post_author );

		if( ! $author || $_post->post_author == $content['user_id'] ){
			return;
		}

		$email['to'] 		= $author->user_email;
		$email['subject']	= sprintf(
			'%s: %s',
			get_bloginfo( 'name' ),
			esc_html__( 'Report Comment', 'streamtube-core' )
		);

		$email['message']	= sprintf(
			esc_html__( 'Hello %s', 'streamtube-core' ),
			$author->display_name
		) . "\r\n\r\n";

		$email['message']	.= sprintf(
			esc_html__( '%s have reported a comment', 'streamtube-core' ),
			get_userdata( $content['user_id'] )->display_name
		) . "\r\n\r\n";

		$email['message']	.= esc_html__( 'Report Content', 'streamtube-core' ) . "\r\n\r\n";		

		$email['message']	.= '<hr/>' . "\r\n\r\n";

		$email['message']	.= $content['content'] . "\r\n\r\n";

		$email['message']	.= esc_html__( 'Comment Content', 'streamtube-core' ) . "\r\n\r\n";		

		$email['message']	.= '<hr/>' . "\r\n\r\n";

		$email['message']	.= $comment->comment_content . "\r\n\r\n";

		$email['message']	.= sprintf(
			esc_html__( 'Reference URL: %s', 'streamtube-core' ),
			add_query_arg( 
				array( 
					'comment_id' => $comment->comment_ID 
				), 
				get_permalink( $comment->comment_post_ID ) 
			) . '#comment-' . $comment->comment_ID
		) . "\r\n\r\n";

		$headers = array();
		$headers[] = 'Content-Type: text/html; charset=UTF-8';
		$headers[] = sprintf(
			'From: %s <%s>',
			get_option( 'blogname' ),
			get_option( 'new_admin_email' )
		);

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
		$email = apply_filters( 'streamtube/core/comment/report/notify_email', $email, $comment, $content );

		extract( $email );

		return wp_mail( $to, $subject, wpautop( $message ), $headers );	
	}

	/**
	 *
	 * Remove comment report
	 * 
	 * @param  int|WP_Comment $comment
	 * @return delete_comment_meta()
	 * 
	 */
	public function remove_comment_report( $comment = null ){

		if( is_int( $comment ) ){
			$comment = $this->get_comment( $comment );
		}

		if( ! is_object( $comment ) || ! $this->can_moderate_comments( $comment ) ){
			return new WP_Error(
				'no_permission',
				esc_html__( 'Sorry, you do not have permission to remove report for this comment', 'streamtube-core' )
			);
		}

		return delete_comment_meta( $comment->comment_ID, 'report_content' );
	}

	/**
	 *
	 * Check if given comment has been reported
	 * 
	 * @param  int|WP_Comment  $comment
	 * @return array|false
	 * 
	 */
	public function comment_reported( $comment = null ){

		if( is_int( $comment ) ){
			$comment = $this->get_comment( $comment );
		}

		$report_content = get_comment_meta( $comment->comment_ID, 'report_content', true );

		if( $report_content ){
			return $report_content;
		}

		return false;
	}

	public function bulk_action( $comment_id = 0, $action = '' ){

		$errors = new WP_Error();

		if( ! $comment_id || ! $action || ! $this->can_moderate_comments( (int)$comment_id ) ){
			$errors->add(
				'no_permission',
				esc_html__( 'Sorry, you are not allowed to moderate this comment.', 'streamtube-core' )
			);
		}

		/**
		 *
		 * Filter the errors
		 * 
		 * @var WP_Error
		 *
		 * @since  1.0.0
		 * 
		 */
		$errors = apply_filters( 'streamtube/core/comment/bulk_action', $errors, $comment_id, $action );

		if( $errors->get_error_code() ){
			return $errors;
		}		

		switch ( $action ) {
			case 'approve':
				return wp_set_comment_status( $comment_id, 'approve' );

			break;

			case 'unapprove':
				return wp_set_comment_status( $comment_id, 'hold' );
			break;

			case 'spam':
				return wp_spam_comment( $comment_id);
			break;

			case 'trash':
				return wp_trash_comment( $comment_id );
			break;	

			case 'delete':
				return wp_delete_comment( $comment_id, true );
			break;	
		}
	}

	/**
	 *
	 * AJAX load comment
	 * 
	 * @since 1.0.0
	 */
	public function ajax_get_comment(){

		check_ajax_referer( '_wpnonce' );

		$comment_id = isset( $_GET['comment_id'] ) ? (int)$_GET['comment_id'] : 0;

		if( ! $comment_id && isset( $_POST['data'] ) ){
			$http_data = json_decode( wp_unslash( $_POST['data'] ), true );

			if( array_key_exists( 'comment_id', $http_data ) ){
				$comment_id = (int)$http_data['comment_id'];
			}
		}

		if( ! $comment_id ){
			wp_send_json_error( new WP_Error(
				'comment_id_not_found',
				esc_html__( 'Comment ID was not found', 'streamtube-core' )
			) );
		}

		$comment = $this->get_comment( $comment_id, true );

		$comment->comment_editor = $this->can_moderate_comments( $comment ) ? 'editor' : 'textarea';

		$comment->comment_editor = apply_filters( 'streamtube/comment/editor', $comment->comment_editor, $comment );

		if( is_wp_error( $comment ) ){
			wp_send_json_error( $comment );
		}

		wp_send_json_success( $comment );
	}	

	/**
	 * AJAX Get comment to report
	 */
	public function ajax_get_comment_to_report(){
		add_filter( 'streamtube/comment/editor', '__return_false', 1, 2 );
		return $this->ajax_get_comment();
	}

	/**
	 * 
	 *
	 * Do AJAX post comment
	 * 
	 * @since 1.0.0
	 * 
	 */
	public function ajax_post_comment(){

		check_ajax_referer( '_wpnonce' );

		$comment = $this->post_comment();

		if( is_wp_error( $comment ) ){

			$message = join( '<br/>', $comment->get_error_messages() );

			if( $comment->get_error_code() == 'comment_on_password_protected' ){
				$message = esc_html__( 'Cannot comment on password protected', 'streamtube-core' );
			}

			wp_send_json_error( array(
				'code'		=>	$comment->get_error_code(),
				'message'	=>	$message ? $message : esc_html__( 'Undefined Error', 'streamtube-core' )
			) );
		}

		wp_send_json_success( array_merge( $comment, array(
			'message'	=>	esc_html__( 'Comment posted.', 'streamtube-core' )
		) ) );
	}

	/**
	 *
	 * AJAX edit comment
	 * 
	 * @return JSON
	 *
	 * @since 1.0.8
	 * 
	 */
	public function ajax_edit_comment(){
		check_ajax_referer( '_wpnonce' );

		$errors = new WP_Error();

		$comment_id = isset( $_POST['comment_ID'] ) ? (int)$_POST['comment_ID'] : 0;

		if( ! $comment_id || ! $this->can_edit_comment( $comment_id ) ){
			$errors->add(
				'no_permission',
				esc_html__( 'Sorry, you do not have permission edit comment', 'streamtube-core' )
			);
		}

		/**
		 * Fiter the errors
		 *
		 * @since  1.0.0
		 * 
		 */
		$errors = apply_filters( 'streamtube/core/comment/edit_comment', $errors );

		if( $errors->get_error_code() ){
			wp_send_json_error( $errors );
		}
		
		$comment = wp_update_comment( $_POST, true );

		if( is_wp_error( $comment ) ){
			wp_send_json_error( $comment );
		}

		if( wp_validate_boolean( $comment ) === false ){
			wp_send_json_error( new WP_Error(
				'undefined_error',
				esc_html__( 'Error: cannot update comment, it seems you have not modified comment content yet', 'streamtube-core' )
			) );
		}

		update_comment_meta( $_POST['comment_ID'], 'last_edited', current_time( 'timestamp' ) );

		$comment = $this->get_comment( $comment_id, true );

		wp_send_json_success( array(
			'message' 	=>	esc_html__( 'Comment updated.', 'streamtube-core' ),
			'comment'	=>	$comment
		));		
	}

	/**
	 * AJAX report comment
	 */
	public function ajax_report_comment(){
		check_ajax_referer( '_wpnonce' );

		$errors = new WP_Error();

		$comment_id 	= isset( $_POST['comment_ID'] ) ? (int)$_POST['comment_ID'] : 0;

		$report_content = isset( $_POST['comment_content'] ) ? $_POST['comment_content'] : '';

		/**
		 * Fiter the errors
		 *
		 * @since  1.0.0
		 * 
		 */
		$errors = apply_filters( 'streamtube/core/comment/report_comment', $errors );

		if( $errors->get_error_code() ){
			wp_send_json_error( $errors );
		}

		$results = $this->report_comment( $comment_id, $report_content );

		if( is_wp_error( $results ) ){
			wp_send_json_error( $results );
		}

		wp_send_json_success( array(
			'message'	=>	esc_html__( 'You have reported this comment successfully', 'streamtube-core' )
		) );
	}

	/**
	 * AJAX remove comment report
	 */
	public function ajax_remove_comment_report(){
		check_ajax_referer( '_wpnonce' );

		$errors = new WP_Error();

		$comment_id = isset( $_GET['comment_id'] ) ? (int)$_GET['comment_id'] : 0;

		if( ! $comment_id && isset( $_POST['data'] ) ){
			$http_data = json_decode( wp_unslash( $_POST['data'] ), true );

			if( array_key_exists( 'comment_id', $http_data ) ){
				$comment_id = (int)$http_data['comment_id'];
			}
		}

		if( ! $comment_id ){
			wp_send_json_error( new WP_Error(
				'comment_id_not_found',
				esc_html__( 'Comment ID was not found', 'streamtube-core' )
			) );
		}

		/**
		 * Fiter the errors
		 *
		 * @since  1.0.0
		 * 
		 */
		$errors = apply_filters( 'streamtube/core/comment/remove_report_comment', $errors );

		if( $errors->get_error_code() ){
			wp_send_json_error( $errors );
		}

		$results = $this->remove_comment_report( $comment_id );

		if( is_wp_error( $results ) ){
			wp_send_json_error( $results );
		}

		$comment = $this->get_comment( $comment_id );
		$message = esc_html__( 'You have removed report for this comment successfully', 'streamtube-core' );

		wp_send_json_success( compact( 'comment', 'message' ) );
	}

	/**
	 * 
	 *
	 * Do AJAX moderate comment
	 * 
	 * @since 1.0.0
	 * 
	 */
	public function ajax_moderate_comment(){

		check_ajax_referer( '_wpnonce' );

		$errors = new WP_Error();

		$data = json_decode( wp_unslash( $_POST['data'] ), true );

		if( ! $data || ( is_array( $data ) && ! isset( $data['comment_id'] ) ) ){
			$errors->add(
				'data_not_found',
				esc_html__( 'Data was not found.', 'streamtube-core' )
			);
		}

		if( ! $this->can_moderate_comments( (int)$data['comment_id'] ) ){
			$errors->add( 
				'no_permission', 
				esc_html__( 'Sorry, you are not allowed to moderate this comment.', 'streamtube-core' ) 
			);
		}		

		/**
		 * Fiter the errors
		 *
		 * @since  1.0.0
		 * 
		 */
		$errors = apply_filters( 'streamtube/core/comment/approve', $errors, $data );

		if( $errors->get_error_code() ){
			wp_send_json_error( $errors );
		}

		$results = $this->moderate_comment( $data['comment_id'] );

		if( is_wp_error( $results ) ){
			wp_send_json_error( $results );
		}

		$comment_approved = get_comment( $data['comment_id'] )->comment_approved;

		if( $comment_approved == 1 ){
			$status = esc_html__( 'Unapprove', 'streamtube-core' );
		}
		else{
			$status = esc_html__( 'Approve', 'streamtube-core' );
		}

		wp_send_json_success( compact( 'status', 'comment_approved' ) );
	}

	/**
	 * 
	 *
	 * Do AJAX trash comment
	 * 
	 * @since 1.0.0
	 * 
	 */
	public function ajax_trash_comment(){

		check_ajax_referer( '_wpnonce' );

		$errors = new WP_Error();

		$data = json_decode( wp_unslash( $_POST['data'] ), true );

		if( ! $data || ( is_array( $data ) && ! isset( $data['comment_id'] ) ) ){
			$errors->add(
				'data_not_found',
				esc_html__( 'Data was not found.', 'streamtube-core' )
			);
		}

		if( ! $this->can_delete_comment( (int)$data['comment_id'] ) ){
			$errors->add( 
				'no_permission', 
				esc_html__( 'Sorry, you are not allowed to trash this comment.', 'streamtube-core' ) 
			);
		}

		/**
		 * Fiter the errors
		 *
		 * @since  1.0.0
		 * 
		 */
		$errors = apply_filters( 'streamtube/core/comment/trash', $errors, $data );

		if( $errors->get_error_code() ){
			wp_send_json_error( $errors );
		}

		$results = wp_delete_comment( $data['comment_id'], true );

		if( is_wp_error( $results ) ){
			wp_send_json_error( $results );
		}

		wp_send_json_success( array(
			'message'		=>	sprintf(
				esc_html__( 'Comment #%s has been trashed successfully.', 'streamtube-core' ),
				'<strong>'. $data['comment_id'] .'</strong>'
			),
			'comment_id'	=>	$data['comment_id']
		) );
	}

	/**
	 * 
	 *
	 * Do AJAX spam comment
	 * 
	 * @since 1.0.0
	 * 
	 */
	public function ajax_spam_comment(){

		check_ajax_referer( '_wpnonce' );

		$errors = new WP_Error();

		$data = json_decode( wp_unslash( $_POST['data'] ), true );

		if( ! $data || ( is_array( $data ) && ! isset( $data['comment_id'] ) ) ){
			$errors->add(
				'data_not_found',
				esc_html__( 'Data was not found.', 'streamtube-core' )
			);
		}

		if( ! $this->can_moderate_comments( (int)$data['comment_id'] ) ){
			$errors->add( 
				'no_permission', 
				esc_html__( 'Sorry, you are not allowed to spam this comment.', 'streamtube-core' ) 
			);
		}

		/**
		 * Fiter the errors
		 *
		 * @since  1.0.0
		 * 
		 */
		$errors = apply_filters( 'streamtube/core/comment/spam', $errors, $data );

		if( $errors->get_error_code() ){
			wp_send_json_error( $errors );
		}

		$results = wp_spam_comment( $data['comment_id'] );

		if( ! $results ){
			wp_send_json_error( new WP_Error(
				'undefined_error',
				esc_html__( 'Error is undefined, cannot trash this comment', 'streamtube-core' )
			) );
		}

		wp_send_json_success( array(
			'message'		=>	sprintf(
				esc_html__( 'Comment #%s has been spammed successfully.', 'streamtube-core' ),
				'<strong>'. $data['comment_id'] .'</strong>'
			),
			'comment_id'	=>	$data['comment_id']
		) );
	}

	/**
	 * 
	 *
	 * AJAX load more comments
	 * 
	 * @since 1.0.0
	 * 
	 */
	public function ajax_load_more_comments(){

		check_ajax_referer( '_wpnonce' );

		$output = '';

		if( ! isset( $_POST['data'] ) || ! isset( $_POST['action'] ) ){
			wp_send_json_error( array(
				'code'		=>	'no_data',
				'message'	=>	esc_html__( 'Invalid Request, no request data.', 'streamtube-core' )
			) );
		}

		//$data = json_decode( wp_unslash( $_POST['data'] ), true );

		$data = wp_parse_args( json_decode( wp_unslash( $_POST['data'] ), true ), array(
			'post_id'	=>	'',
			'paged'		=>	1,
			'order'		=>	''
		));

		if( $_POST['action'] == 'load_comments' ){
			$data['paged'] = 0;
		}

		$data['paged'] = (int)$data['paged']+1;

		if( ! $data['post_id'] || ! get_post_status( $data['post_id'] ) ){
			wp_send_json_error( array(
				'code'		=>	'post_id_not_found',
				'message'	=>	esc_html__( 'Post ID was not found', 'streamtube-core' )
			) );			
		}

		// turn on buffering
		ob_start();

		$args = array(
			'post_id'	=>	$data['post_id'],
			'paged'		=>	$data['paged']
		);

		if( $data['order'] ){
			$args['order'] = $data['order'];
		}

		streamtube_core_list_comments( $args );

		$output = ob_get_clean();

		wp_send_json_success( array(
			'message'	=>	'OK',
			'data'		=>	json_encode( $data ),
			'output'	=>	trim($output)
		) );
	}

	/**
	 *
	 * AJAX reload comments
	 *
	 * @since 1.0.0
	 * 
	 */
	public function ajax_load_comments(){

		return $this->ajax_load_more_comments();
	}

	/**
	 *
	 * Filter reported comment content
	 * 
	 * @param  integer $comment_ID
	 * @return string
	 * 
	 */
	public function filter_reported_comment_content( $comment_text, $comment, $args ){

		if( ! get_option( 'comment_report' )  ){
			return $comment_text;
		}

		$maybe_report = $this->comment_reported( $comment );

		if( $maybe_report && ! is_admin() ){

			if( $this->can_moderate_comments( $comment ) ){

				$report = sprintf(
					'<p class="text-muted fst-italic">%s (%s)</p>',
					esc_html__( 'This comment is currently being reviewed', 'streamtube-core' ),
					'<a class="text-body" data-bs-toggle="collapse" href="#view-comment-report-'.esc_attr($comment->comment_ID).'">'. esc_html__( 'view report', 'streamtube-core' ) .'</a>'
				);

				$report .= '<div class="alert border bg-light collapse" id="view-comment-report-'.esc_attr($comment->comment_ID).'">';

				$report .= sprintf(
					'<p class="mb-0 report-content">%s</p>',
					$maybe_report['content']
				);

				if( $maybe_report['user_id'] ){
					$_user = get_userdata( $maybe_report['user_id'] );

					if( $_user ){
						$report .= sprintf(
							'<p class="mb-0 report-user">'. esc_html__( 'Reported by %s' ) .'</p>',
							'<a class="text-body" href="'. esc_url( get_author_posts_url( $_user->ID ) ) .'">'. $_user->display_name .'</a>'
						);
					}
				}

				if( $maybe_report['date'] ){
					$report .= sprintf(
						'<p class="mb-0 report-date">'. esc_html__( '%s ago', 'streamtube-core' ) .'</p>',
						human_time_diff( $maybe_report['date'], current_time( 'timestamp' ) )
					);
				}			

				$report .= '</div>';

				$comment_text = $report . $comment_text;
			}
			else{
				$comment_text = sprintf(
					'<p class="text-muted fst-italic">%s</p>',
					esc_html__( 'This comment is currently being reviewed', 'streamtube-core' )
				);
			}
		}

		return $comment_text;
	}

	/**
	 *
	 * Filter the comment form args
	 * 
	 * @param  array $args
	 * @return array $args
	 *
	 * @since  1.0.0
	 * 
	 */
	public function filter_comment_form_args( $args ){

		// add form-ajax class
		$args['class_form']		.=	' form-ajax';

		// Add action and nonce fields
		$args['comment_field']	.=	'<input type="hidden" name="action" value="post_comment">';

		return $args;
	}

	/**
	 *
	 * Filter comment classes
	 * 
	 */
	public function filter_comment_classes( $classes, $css_class, $comment_id, $comment, $post ){

		if( $this->comment_reported( $comment ) ){
			$classes[] = 'has-reported';
		}

		return $classes;
	}

	/**
	 *
	 * Load AJAX comments template
	 * 
	 * @param  string $file
	 * @return string
	 *
	 * @since  1.0.0
	 * 
	 */
	public function load_ajax_comments_template( $file ){
		
		if( strpos( $file, 'comments.php' ) !== false ){
			return streamtube_core_get_template( 'comment/comments-ajax.php' );	
		}
		
		return $file;
	}

	/**
	 *
	 * Get comments count
	 * 
	 * @param  string $status
	 * @return int
	 *
	 * @since 1.1.5
	 * 
	 */
	public function get_comments_count( $status = '' ){

		$comments_args = array(
			'status'		=>	$status,
			'type'			=>	array( 'comment' ),
			'count'			=>	true
		);

		if( ! Streamtube_Core_Permission::moderate_posts() ){
			$comments_args['post_author'] = get_current_user_id();
		}

		return get_comments( $comments_args );
	}

	/**
	 *
	 * Get comments count badge
	 * 
	 * @return int
	 *
	 * @since 1.1.5
	 * 
	 */
	public function get_pending_comments_badge(){

		$badge = '';

		$count = $this->get_comments_count( 'hold' );

		if( $count ){
            $badge = sprintf(
                '<span class="badge bg-danger">%s</span>',
                number_format_i18n( $count )
            );
		}

        /**
         *
         * @since 1.1.5
         * 
         */
        return apply_filters( 'streamtube/core/pending_comments_badge', $badge, $count );
	}

	/**
	 *
	 * Load comment buttons
	 * 
	 * @since 1.0.0
	 * 
	 */
	public function load_control_buttons( $comment, $args, $depth ){

		$is_reported = $this->comment_reported( $comment );

		?>
		<div class="moderate-comment">

			<?php if( $this->can_report_comment( $comment ) && ! $is_reported && ! user_can( $comment->user_id, 'administrator' ) ): ?>

				<?php streamtube_core_load_template( 'comment/button-report.php', false ); ?>

			<?php endif;?>

			<?php if( $this->can_moderate_comments( $comment ) && $is_reported ): ?>

				<?php streamtube_core_load_template( 'comment/button-remove-report.php', false ); ?>

			<?php endif;?>

			<?php if( $this->can_edit_comment( $comment ) ): ?>

				<?php streamtube_core_load_template( 'comment/button-edit.php', false ); ?>

			<?php endif;?>

			<?php if( $this->can_delete_comment( $comment ) ): ?>
				<?php streamtube_core_load_template( 'comment/button-delete.php', false ); ?>
			<?php endif;?>
		</div>
		<?php
	}

}