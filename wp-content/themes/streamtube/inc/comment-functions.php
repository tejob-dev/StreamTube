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

function streamtube_format_comment_date( $date = null, $comment = null ){

	if( ! $date ){
		$date = get_comment_time('U');
	}

	$date = sprintf(
        esc_html__( '%s ago', 'streamtube' ),
        human_time_diff( $date, current_time('timestamp') )
    );

	/**
	 * Filter comment date
	 */
	return $date = apply_filters( 'streamtube_format_comment_date', $date, $comment );
}

/**
 *
 * Default comment list args
 * 
 * @return array
 *
 * @since  1.0.0
 * 
 */
function streamtube_comment_list_args(){
	$args = array(
		'avatar_size'		=>	96,
		'style'				=>	'ul',
		'short_ping'		=>	true,
		'max_depth'			=>	get_option( 'thread_comments_depth' ),
		'callback'			=>	'streamtube_comment_callback',
		'per_page'			=>	get_option( 'comments_per_page', 10 ),
		'reverse_top_level'	=>	false,
		'echo'				=>	true
	);

	return apply_filters( 'streamtube_comment_list_args', $args );
}

/**
 *
 * Get the comment depth number
 *
 * @since 1.0.0
 *
 */
function streamtube_get_comment_depth( $comment, $depth = 0 ) {

	if( $comment->comment_parent > 0 ){
		$depth++;
		$comment	=	get_comment( $comment->comment_parent );
		return (int)call_user_func( __FUNCTION__, $comment, $depth );
	}
	else{
		return (int)$depth;
	}
}

/**
 *
 * Comment template callback
 * 
 * @param  
 * 
 * @param  object 	$comment current comment
 * @param  array 	$args
 * @return int 		$depth current comment depth
 *
 * @since  1.0.0
 */
function streamtube_comment_callback( $comment, $args, $depth ){

	$user_url = get_comment_author_url( $comment->comment_ID );

	$is_read_more_js = false;

	/**
	 *
	 * Filter the word count number, 100 is default
	 * 
	 * @var int
	 *
	 * @since  1.0.0
	 * 
	 */
	$word_count = apply_filters( 'streamtube_comment_read_more_word_count', 50 );

	if( $word_count && str_word_count( $comment->comment_content ) > $word_count && function_exists( 'streamtube_core' ) ){
		$is_read_more_js = true;
	}

	?>
	<li <?php comment_class();?> id="comment-<?php echo esc_attr( $comment->comment_ID );?>">

		<div id="div-comment-<?php comment_ID(); ?>" class="comment-wrap comment-body">

			<?php if ( $args['avatar_size'] != 0 && $comment->comment_type == 'comment' ):?>
			    <div class="comment-avatar me-4">
			        <a title="<?php echo esc_attr( $comment->comment_author )?>" href="<?php echo esc_url( $user_url )?>">
			            <div class="user-avatar user-avatar-lg">
			            	<?php echo get_avatar( $comment, $args['avatar_size'], null, null, array(
			            		'class'	=>	'img-thumbnail avatar'
			            	) );?>
			            </div>
			        </a>
			    </div>
			<?php endif;?>

		    <div class="comment-content">

		    	<div class="comment-meta d-flex">
		    		<div class="comment-meta__left">
				        <?php
						do_action( 'streamtube/comment_list/comment/author/before', $comment, $args, $depth );
				        ?>		    	
				        <div class="comment-author">		
					        <h5 class="comment-author-name">
					        	<?php printf(
					        		'<a class="text-body text-decoration-none" title="%s" href="%s">%s</a>',
					        		esc_attr( $comment->comment_author ),
					        		esc_url( $user_url ),
					        		$comment->comment_author
					        	);?>			        	
					        </h5>
				        	<?php printf(
				        		'<span class="comment-date">%s</span>',
				        		streamtube_format_comment_date( null, $comment )
				        	);?>
				    	</div>
				        <?php
						do_action( 'streamtube/comment_list/comment/author/after', $comment, $args, $depth );				        
				        ?>
			    	</div>

			    	<div class="comment-meta__right ms-auto">
						<?php do_action( 'streamtube/comment_list/comment/meta/right', $comment, $args, $depth );?>			    		
			    	</div>
		    	</div>

		        <div class="comment-text mt-2">
		            <?php printf(
		            	'<div class="comment-text-%s position-relative">',
		            	$is_read_more_js ? 'js' : 'no-js'
		            );?>
		            	<?php comment_text();?>

				        <?php
				        if(  "" != $edit_date = get_comment_meta( $comment->comment_ID, 'last_edited', true ) ){
							printf(
				        		'<span class="comment-date last-modified">'. esc_html__( 'last modified %s', 'streamtube' ) .'</span>',
				        		'<strong>'. streamtube_format_comment_date( $edit_date, $comment ) .'</strong>'
				        	);
				        }
				        ?>		            	

		            	<?php if( $is_read_more_js ): ?>
			            	<div class="bg-overlay"></div>

							<?php
							printf(
								'<a href="#" class="fw-bold comment-show-more text-body w-100">%s <span class="icon-angle-down"></span></a>',
								esc_html__( 'show more', 'streamtube' )
							)
							?>

							<?php
							printf(
								'<a href="#" class="fw-bold comment-show-less text-body w-100">%s <span class="icon-angle-up"></span></a>',
								esc_html__( 'show less', 'streamtube' )
							)
							?>							
						<?php endif;?>
		            </div>
		        </div>

				<div class="comment-options d-flex align-items-center gap-2">

					<?php if( function_exists( 'streamtube_core' ) && get_comments( array( 'parent' => $comment->comment_ID, 'count' => true ) ) > 0 ): ?>
						<?php printf(
							'<a class="toggle-replies-link" href="#"><span class="icon-angle-down"></span> %s</a>',
							esc_html__( 'Replies', 'streamtube' )
						);?>
					<?php endif;?>

					<?php
					/**
					 *
					 * Fires in comment options
					 *
					 * @param object $comment
					 * @param array $args
					 * @param $depth
					 *
					 * @since 1.0.0
					 * 
					 */
					do_action( 'streamtube/comment_list/comment/option', $comment, $args, $depth );

					echo get_comment_reply_link( 
						array_merge( $args, 
							array(
								'depth'			=>	$depth, 
								'max_depth'		=>	$args['max_depth'], 
								'reply_text'	=>	'<span class="icon-reply me-1"></span>' . esc_html__( 'Reply', 'streamtube' ),
								'login_text'	=>	'<span class="icon-reply me-1"></span>' . esc_html__( 'Reply', 'streamtube' ),
								'before'		=>	'<span class="ms-auto text-secondary">',
								'after'			=>	'</span>'
							)
						), 
						$comment->comment_ID
					);
					?>
				</div>

		    </div>

		</div>
	<?php

}

/**
 *
 * Add current user avatar next to the comment textarea
 *
 * @since  1.0.0
 * 
 */
function streamtube_add_comment_form_avatar( $commenter, $user_identity ){
	printf(
		'<div class="comment-avatar me-4 d-none d-lg-block">
            <a href="%s">
                <div class="user-avatar user-avatar-lg">
                    %s
                </div>
            </a>
        </div>',
        get_author_posts_url( get_current_user_id() ),
        get_avatar( get_current_user_id(), 96, null, null, array(
        	'class'	=>	'img-thumbnail avatar'
        ) )
	);
}
add_action( 'comment_form_logged_in_after', 'streamtube_add_comment_form_avatar', 10, 2 );

/**
 *
 * Add </div> after the submit field.
 * 
 * @param  html $submit_field
 * @param  array $args
 * @return html
 *
 * @since  1.0.0
 * 
 */
function streamtube_add_comment_form_submit_close_tag( $submit_field, $args ){

	return $submit_field . '</div>';
}

/**
 *
 * Filter comment author URL
 * 
 * @param string          $url        The comment author's URL, or an empty string.
 * @param string|int      $comment_id The comment ID as a numeric string, or 0 if not found.
 * @param WP_Comment|null $comment    The comment object, or null if not found.
 */
function streamtube_filter_comment_author_url( $url, $id, $comment ){

	if( $id && get_user_by( 'ID', $comment->user_id ) !== false ){
		$url = get_author_posts_url( $comment->user_id );
	}
	return $url;
}
add_filter( 'get_comment_author_url', 'streamtube_filter_comment_author_url', 9, 3 );