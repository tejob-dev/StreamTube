<?php
/**
 * The template for displaying AJAX comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}

if( did_action( 'streamtube/core/widget/comments_template/loaded' ) ){
	return;
}

if( ! comments_open() && ! get_comments_number() ){
	return;
}
$comments_args = array(
	'post_id'	=>	get_the_ID()
);
if( isset( $_GET['comment_id'] ) && get_comment_type( $_GET['comment_id'] ) ){
	$comments_args['comment__in'] = array( $_GET['comment_id'] );
}

/**
 *
 * Filter comment list order
 * 
 */
$comment_list_order 	= apply_filters( 'streamtube/comments/list_order', get_option( 'comment_order' ) );

/**
 *
 * Filter comment form position
 * 
 */
$comment_form_position 	= apply_filters( 'streamtube/comments/comment_form_position', 'top' );

?>
<div class="comments-list-lg bg-white rounded shadow-sm mb-4">
	<div id="comments" class="comments-area comments-ajax d-flex flex-column">

		<?php 
		if( $comment_form_position == 'top' ){
			get_template_part( 'template-parts/comment/comment', 'form' );	
		}
		?>

		<?php if( get_comments_number() ): ?>

			<div class="widget-title-wrap comment-title d-flex align-items-center justify-content-between border-top p-4 py-3 m-0">
			    <h2 class="widget-title no-after m-0"><?php comments_number();?></h2>

			    <?php load_template( streamtube_core_get_template( 'comment/sortby.php' ) );?>
			</div>

		<?php endif;?>

		<?php if( comments_open() || get_comments_number() ): ?>

			<?php if( array_key_exists( 'comment__in' , $comments_args) ){
			printf(
				'<a class="btn border-bottom d-block w-100 rounded-0" href="%s">%s</a>',
				esc_url( get_permalink( get_the_ID() ) ) . '#comments-list',
				esc_html__( 'View all comments', 'streamtube-core' )
			);				
			}?>

			<?php
			printf(
				'<ul id="comments-list" class="comments-list comments-list-order-%s comment-form-position-%s list-unstyled py-4 m-0 flex-grow-1 position-relative">',
				esc_attr( $comment_list_order ),
				esc_attr( $comment_form_position )
			);
			?>
				<?php

				streamtube_core_list_comments( $comments_args );		

				if( comments_open() && ! get_comments_number() ){
					printf(
						'<li class="no-comments py-4"><p class="top-50 start-50 translate-middle position-absolute text-muted text-center">%s</p></li>',
						esc_html__( 'Be the first to comment', 'streamtube-core' )
					);
				}
				?>
			</ul>
		<?php endif;?>

		<?php 
		if( $comment_form_position == 'bottom' ){
			get_template_part( 'template-parts/comment/comment', 'form' );	
		}
		?>		

		<?php if ( ! comments_open() ) : ?>
			<p class="no-comments border-top p-4 mb-0"><?php _e( 'Comments are closed.', 'streamtube' ); ?></p>
		<?php endif; ?>
	</div>
</div>
<?php
/**
 * @since 2.1.7
 */
do_action( 'streamtube/comments_template/loaded' );