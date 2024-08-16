<?php
/**
 * Review Comments Template
 *
 * Closing li is left out on purpose!.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/review.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$user_url = get_comment_author_url();

if( get_user_by( 'ID', $comment->user_id ) !== false ){
	$user_url = get_author_posts_url( $comment->user_id );
}
?>
<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">

	<div id="comment-<?php comment_ID(); ?>" class="comment_container comment-wrap comment-body">

		<?php
		/**
		 * The woocommerce_review_before hook
		 *
		 * @hooked woocommerce_review_display_gravatar - 10
		 */
		//do_action( 'woocommerce_review_before', $comment );
		?>

	    <div class="comment-avatar me-4">
	        <a title="<?php echo esc_attr( $comment->comment_author )?>" href="<?php echo esc_url( $user_url )?>">
	            <div class="user-avatar user-avatar-lg">
	            	<?php echo get_avatar( $comment, apply_filters( 'woocommerce_review_gravatar_size', '60' ), null, null, array(
	            		'class'	=>	'img-thumbnail avatar'
	            	) );?>
	            </div>
	        </a>
	    </div>		

	    <div class="comment-content">
			<div class="comment-text">

				<?php
				/**
				 * The woocommerce_review_before_comment_meta hook.
				 *
				 * @hooked woocommerce_review_display_rating - 10
				 */
				do_action( 'woocommerce_review_before_comment_meta', $comment );

				/**
				 * The woocommerce_review_meta hook.
				 *
				 * @hooked woocommerce_review_display_meta - 10
				 */
				do_action( 'woocommerce_review_meta', $comment );

				do_action( 'woocommerce_review_before_comment_text', $comment );

				/**
				 * The woocommerce_review_comment_text hook
				 *
				 * @hooked woocommerce_review_display_comment_text - 10
				 */
				do_action( 'woocommerce_review_comment_text', $comment );

				do_action( 'woocommerce_review_after_comment_text', $comment );
				?>

			</div>
		</div>
	</div>
