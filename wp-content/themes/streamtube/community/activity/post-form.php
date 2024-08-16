<?php
/**
 * BuddyPress - Activity Post Form
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 * @version 12.0.0
 */

if ( bp_is_group() ) {
	/* translators: 1: group name. 2: member name. */
	$placeholder = sprintf( __( 'What\'s new in %1$s, %2$s?', 'buddypress' ), bp_get_group_name(), bp_get_user_firstname( bp_get_loggedin_user_fullname() ) );
} else {
	/* translators: %s: member name */
	$placeholder = sprintf( __( "What's new, %s?", 'buddypress' ), bp_get_user_firstname( bp_get_loggedin_user_fullname() ) );
}

?>

<form class="bg-white p-4 shadow-sm mb-4" action="<?php bp_activity_post_form_action(); ?>" method="post" id="whats-new-form" name="whats-new-form">

	<?php

	/**
	 * Fires before the activity post form.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_before_activity_post_form' ); ?>

	<div id="whats-new-avatar">
		<a href="<?php bp_loggedin_user_link(); ?>">
			<?php bp_loggedin_user_avatar( 'width=' . bp_core_avatar_thumb_width() . '&height=' . bp_core_avatar_thumb_height() ); ?>
		</a>
	</div>

	<div id="whats-new-content">
		<div id="whats-new-textarea">

			<?php printf(
				'<textarea placeholder="%s" class="bp-suggestions bg-light shadow-none" name="whats-new" id="whats-new" data-suggestions-group-id="%s">%s</textarea>',
				esc_attr( $placeholder ),
				bp_is_group() ? (int) bp_get_current_group_id() : '',
				isset( $_GET['r'] ) ? esc_textarea( $_GET['r'] ) : ''
			);?>

		</div>

		<div id="whats-new-options">

			<?php if ( bp_is_active( 'groups' ) && !bp_is_my_profile() && !bp_is_group() ) : ?>

				<div id="whats-new-post-in-box" class="d-flex gap-2 align-items-center mt-3">

					<label for="whats-new-post-in"><?php
						/* translators: accessibility text */
						_e( 'Post in', 'buddypress' );
					?></label>
					<select id="whats-new-post-in" name="whats-new-post-in" class="form-select form-control-sm bg-transparent border-0 border-bottom shadow-none mt-0 fw-bold">
						<option selected="selected" value="0"><?php _e( 'My Profile', 'buddypress' ); ?></option>

						<?php if ( bp_has_groups( 'user_id=' . bp_loggedin_user_id() . '&type=alphabetical&max=100&per_page=100&populate_extras=0&update_meta_cache=0' ) ) :
							while ( bp_groups() ) : bp_the_group(); ?>

								<option value="<?php bp_group_id(); ?>"><?php bp_group_name(); ?></option>

							<?php endwhile;
						endif; ?>

					</select>
				</div>

				<input type="hidden" id="whats-new-post-object" name="whats-new-post-object" value="groups" />

			<?php elseif ( bp_is_group_activity() ) : ?>

				<input type="hidden" id="whats-new-post-object" name="whats-new-post-object" value="groups" />
				<input type="hidden" id="whats-new-post-in" name="whats-new-post-in" value="<?php bp_group_id(); ?>" />

			<?php endif; ?>

			<?php

			/**
			 * Fires at the end of the activity post form markup.
			 *
			 * @since 1.2.0
			 */
			do_action( 'bp_activity_post_form_options' ); 
			?>

			<?php
			/**
			 *
			 * @since 1.2.0
			 */
			do_action( 'bp_activity_post_form_submit_wrap_before' );
			?>			

			<div class="post-form-submit-wrap d-flex mt-4">

				<?php
				/**
				 *
				 * @since 1.2.0
				 */
				do_action( 'bp_activity_post_form_submit_button_before' );
				?>

				<button class="btn btn-primary bg-primary text-white py-2 px-4 btn-sm w-100 rounded border" type="submit" name="aw-whats-new-submit" id="aw-whats-new-submit">
					<?php esc_attr_e( 'Post Update', 'buddypress' ); ?>					
				</button>							

				<?php
				/**
				 *
				 * @since 1.2.0
				 */
				do_action( 'bp_activity_post_form_submit_button_after' );
				?>
			</div>

			<?php
			/**
			 *
			 * @since 1.2.0
			 */
			do_action( 'bp_activity_post_form_submit_wrap_after' );
			?>

		</div><!-- #whats-new-options -->
	</div><!-- #whats-new-content -->

	<?php wp_nonce_field( 'post_update', '_wpnonce_post_update' ); ?>
	<?php

	/**
	 * Fires after the activity post form.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_after_activity_post_form' ); ?>

</form><!-- #whats-new-form -->
