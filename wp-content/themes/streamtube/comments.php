<?php
/**
 *
 * The template for displaying comments
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

?>
<div class="comments-list-lg bg-white rounded shadow-sm border-top mb-4">
	<div id="comments" class="comments-area d-flex flex-column">

		<?php get_template_part( 'template-parts/comment/comment', 'form' );?>

		<?php if( get_comments_number() ): ?>

			<div class="widget-title-wrap comment-title d-flex align-items-center justify-content-between border-top p-4 py-3 m-0">
			    <h2 class="widget-title no-after m-0"><?php comments_number();?></h2>
			</div>

		<?php endif;?>

		<?php if( comments_open() || have_comments() ): ?>
			<?php printf(
				'<ul id="comments-list" class="comments-list list-unstyled %s my-0 flex-grow-1">',
				have_comments() ? 'py-4' : 'd-none'
			); ?>
				<?php wp_list_comments( streamtube_comment_list_args() );?>
			</ul>
		<?php endif;?>

		<?php
		streamtube_comments_navigation(
			array(
				'prev_text'          => sprintf(
					'<span class="nav-prev-text"><span class="icon-angle-double-left"></span> %s</span>',
					esc_html__( 'Older', 'streamtube' )
				),
				'next_text'          => sprintf(
					'<span class="nav-next-text">%s <span class="icon-angle-double-right"></span></span>',
					esc_html__( 'Newer', 'streamtube' )
				),
			)
		);
		?>

		<?php if ( ! comments_open() ) : ?>
			<p class="no-comments p-4 mb-0">
				<?php esc_html_e( 'Comments are closed.', 'streamtube' ); ?>
			</p>
		<?php endif; ?>
	</div>
</div>
<?php
/**
 * @since 2.1.7
 */
do_action( 'streamtube/comments_template/loaded' );