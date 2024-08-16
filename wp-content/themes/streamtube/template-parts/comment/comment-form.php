<?php
/**
 *
 * @link       https://1.envato.market/mgXE4y
 * @since      1.0.0
 *
 * @package    WordPress
 * @subpackage StreamTube
 * @author     phpface <nttoanbrvt@gmail.com>
 */
if( ! defined( 'ABSPATH' ) ){
	exit;
}

// Get current commenter
$commenter = wp_get_current_commenter();

$args = array(
	'class_container'		=>	'comment-respond',
	'class_form'			=>	'comment-form',
	'title_reply_before'   	=> '<div class="widget-title-wrap d-flex m-0 p-4"><h3 id="reply-title" class="comment-reply-title widget-title d-block w-100 no-after m-0">',
	'title_reply_after'    	=> '</h3></div>',
	'logged_in_as'			=>	false,
	'label_submit'			=>	esc_html__( 'Post', 'streamtube' ),
	'class_submit'			=>	'btn btn-secondary btn-sm shadow-none px-5 ms-auto',
	'submit_button'			=>	'<button name="%1$s" type="submit" id="%2$s" class="%3$s" />%4$s</button>',
	'cancel_reply_link'		=>	'<span class="cancel-reply-text badge bg-danger">'. esc_html__( 'Cancel', 'streamtube' ) .'</span>',
	'must_log_in'			=>	sprintf(
		'<div class="must-log-in p-3 text-center"><p class="text-muted m-0">%s</p></div>',
		sprintf(
			esc_html__( 'You must be %s to post a comment.', 'streamtube' ),
			'<a class="fw-bold text-decoration-none" href="'. esc_url( wp_login_url( get_permalink() ) ) .'">'. esc_html__( 'logged in', 'streamtube' ) .'</a>'
		)
	),
	'comment_field'			=>	sprintf(
		'<div class="form-floating mb-3">
		    <textarea class="form-control shadow-none" id="comment_content" name="comment"></textarea>
		    <label for="comment">%s</label>
		</div>',
		esc_attr__( 'Your comment', 'streamtube' )
	),
	'fields'				=>	array(
		'author'			=>	sprintf(
			'<div class="row"><div class="col-12 col-lg-6"><div class="form-floating mb-3">
			    <input type="text" class="form-control shadow-none" id="author" name="author" value="%s">
			    <label for="author">%s</label>
			</div></div>',
			esc_attr( $commenter['comment_author'] ),
			esc_html__( 'Name', 'streamtube' )
		),
		'email'				=>	sprintf(
			'<div class="col-12 col-lg-6"><div class="form-floating mb-3">
			    <input type="email" class="form-control shadow-none" id="email" name="email" value="%s">
			    <label for="email">%s</label>
			</div></div></div>',
			esc_attr(  $commenter['comment_author_email'] ),
			esc_html__( 'Email address', 'streamtube' )
		),
		'url'				=>	sprintf(
			'<div class="form-floating mb-3">
			    <input type="url" class="form-control shadow-none" id="url" name="url" value="%s">
			    <label for="url">%s</label>
			</div>',
			esc_attr( $commenter['comment_author_url'] ),
			esc_html__( 'Website', 'streamtube' )

		),
	)
);

if( is_user_logged_in() ){
	$args['class_form'] .= ' logged-in';
	$args['comment_field'] = sprintf(
		'<div class="respond-area border">
			<textarea class="form-control w-100 border-0 shadow-none" id="comment" name="comment" placeholder="%s"></textarea>
		
		',
		esc_attr__( 'Leave a comment', 'streamtube' )
	);

	/**
	 *
	 * Add </div> after the submit field.
	 *
	 * @since  1.0.0
	 * 
	 */
	add_filter( 'comment_form_submit_field', 'streamtube_add_comment_form_submit_close_tag', 10, 9999 );
}

comment_form( apply_filters( 'streamtube/comment/form_args', $args ) );