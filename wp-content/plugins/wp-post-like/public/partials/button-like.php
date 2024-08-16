<?php
if( ! defined( 'ABSPATH' ) ){
	exit;
}

global $wppl;

if( false == $options = wp_cache_get( 'post_like_settings' ) ){
	$options = WP_Post_Like_Customizer::get_options();
}

if( is_array( $options['post_types'] ) && ! in_array( get_post_type() , $options['post_types'] ) ){
	return;
}

$progress = false;

if( $options['progress_bar'] ){
	$progress = $wppl->get()->query->get_progress( get_the_ID() );
}

$options = array_merge( $options, $args );

?>

<div class="wppl-button-wrap button-group position-relative">

	<?php if( $progress !== false ) : ?>
		<div class="like-progress progress bg-danger position-absolute w-100">
			<?php printf(
				'<div class="progress-bar bg-success" style="width: %1$s" role="progressbar" aria-valuenow="%2$s" aria-valuemin="0" aria-valuemax="100"></div>',
				$progress . '%',
				$progress
			);?>
		</div>			
	<?php endif;?>

	<div class="d-flex gap-4">
		<?php if( $options['button_like_enable'] ): ?>
			<form class="form-ajax form-post-like position-relative" method="post">
				
				<?php printf(
					'<button type="%s" value="like" class="wppl-like-button btn px-1 border-0 position-relative shadow-none %s" %s>',
					! is_user_logged_in() ? 'button' : 'submit',
					wppl_is_liked( get_the_ID() ) ? 'active' : '',
					! is_user_logged_in() ? 'data-bs-toggle="modal" data-bs-target="#modal-login"' : ''
				);?>
					
					<?php printf(
						'<span class="btn__icon"><span class="%s"></span></span>',
						esc_attr( trim( $options['button_like_icon'] ) )
					);?>
					
					<span class="btn__badge badge bg-secondary position-absolute">
						<?php echo number_format_i18n( wppl_get_count( get_the_ID() )->like );?>
					</span>
				</button>
			
				<input type="hidden" name="action" value="post_like">
				<input type="hidden" name="do_action" value="like">
				<input type="hidden" name="post_id" value="<?php the_ID(); ?>">
				<?php printf(
					'<input type="hidden" name="nonce" value="%s">',
					esc_attr( wp_create_nonce( 'wp_rest' ) )
				);?>
				<?php printf(
					'<input type="hidden" name="request_url" value="%s">',
					esc_url( WPPL()->get()->rest_api->get_rest_url() )
				);?>

			</form>
		<?php endif;?>

		<?php if( $options['button_dislike_enable'] ): ?>
			<form class="form-ajax form-post-like position-relative" method="post">
				<?php printf(
					'<button type="%s" value="dislike" class="wppl-dislike-button btn px-1 border-0 position-relative shadow-none %s" %s>',
					! is_user_logged_in() ? 'button' : 'submit',
					wppl_is_disliked( get_the_ID() ) ? 'active' : '',
					! is_user_logged_in() ? 'data-bs-toggle="modal" data-bs-target="#modal-login"' : ''
				);?>
					<?php printf(
						'<span class="btn__icon"><span class="%s"></span></span>',
						esc_attr( trim( $options['button_dislike_icon'] ) )
					);?>
					<span class="btn__badge badge bg-secondary position-absolute">
						<?php echo number_format_i18n( wppl_get_count( get_the_ID() )->dislike );?>
					</span>
				</button>			
				<input type="hidden" name="action" value="post_like">
				<input type="hidden" name="do_action" value="dislike">
				<input type="hidden" name="post_id" value="<?php the_ID(); ?>">
				<?php printf(
					'<input type="hidden" name="nonce" value="%s">',
					esc_attr( wp_create_nonce( 'wp_rest' ) )
				);?>
				<?php printf(
					'<input type="hidden" name="request_url" value="%s">',
					esc_url( WPPL()->get()->rest_api->get_rest_url() )
				);?>
			</form>
		<?php endif;?>

	</div>
</div>