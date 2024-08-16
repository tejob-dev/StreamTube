<?php
/**
 * BuddyPress - Activity Stream (Single Item)
 *
 * This template is used by activity-loop.php and AJAX functions to show
 * each activity.
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 * @since 3.0.0
 * @version 12.0.0
 */

/**
 * Fires before the display of an activity entry.
 *
 * @since 1.2.0
 */
do_action( 'bp_before_activity_entry' ); ?>

<li class="<?php bp_activity_css_class(); ?>" id="activity-<?php bp_activity_id(); ?>">

	<div class="activity-header bg-white d-flex align-items-center gap-4 p-4 m-0">

		<div class="activity-avatar">
			<a href="<?php bp_activity_user_link(); ?>">
				<?php
				streamtube_core_get_user_avatar( array(
				    'link'          =>  false,
				    'wrap_size'     =>  'lg',
				    'user_id'       =>  bp_get_activity_user_id()
				) );
				?>
			</a>
		</div>

		<div class="activity-header-meta">
			<?php bp_activity_action(); ?>

			<?php

			/**
			 * Fires after the display of an activity entry meta.
			 *
			 * @since 1.2.0
			 */
			do_action( 'streamtube_bp_activity_entry_meta' );
			?>
		</div>

	</div>

	<div class="activity-content border-top">

		<?php if ( bp_activity_has_content() ) : ?>

			<div class="activity-inner p-4 m-0 border-0">

				<?php if( bp_activity_type_part() == 'new-video' ): ?>
					<?php printf(
						'<h4 class="activity-title h3"><a class="text-body fw-bold text-decoration-none" href="%s">%s</a></h4>',
						esc_url( get_permalink( bp_get_activity_secondary_item_id() ) ),
						get_post( bp_get_activity_secondary_item_id() )->post_title
					)?>
				<?php endif;?>				

				<?php bp_get_template_part( 'activity/type-parts/content',  bp_activity_type_part() ); ?>

			</div>

		<?php endif; ?>

		<?php

		/**
		 * Fires after the display of an activity entry content.
		 *
		 * @since 1.2.0
		 */
		do_action( 'bp_activity_entry_content' ); ?>

		<?php if ( is_user_logged_in() ) : ?>

			<div class="activity-meta m-0 border-top border-bottom bg-white d-flex justify-content-between flex-row-reverse">

				<?php if ( bp_get_activity_type() == 'activity_comment' ) : ?>

					<a href="<?php bp_activity_thread_permalink(); ?>" class="view bp-secondary-action btn btn-white border-0 btn-sm"><?php _e( 'View Conversation', 'buddypress' ); ?></a>

				<?php endif; ?>

				<?php if ( bp_activity_can_comment() ) : ?>

					<a href="<?php bp_activity_comment_link(); ?>" class="icon-custom acomment-reply bp-primary-action button btn btn-white border-0 btn-sm" id="acomment-comment-<?php bp_activity_id(); ?>">
						<?php esc_html_e( 'Comments', 'buddypress' )?>
					</a>

				<?php endif; ?>

				<?php if ( bp_activity_can_favorite() ) : ?>

					<?php if ( ! bp_get_activity_is_favorite() ) : ?>

						<a href="<?php bp_activity_favorite_link(); ?>" class="icon-custom btn btn-white border-0 btn-sm button fav bp-secondary-action">
							<?php _e( 'Favorite', 'buddypress' ); ?>
						</a>

					<?php else : ?>

						<a href="<?php bp_activity_unfavorite_link(); ?>" class="icon-custom btn btn-white border-0 btn-sm button unfav bp-secondary-action">
							<?php _e( 'Remove Favorite', 'buddypress' ); ?>
						</a>

					<?php endif; ?>

				<?php endif; ?>

				<?php if ( bp_activity_user_can_delete() ) bp_activity_delete_link(); ?>

				<?php

				/**
				 * Fires at the end of the activity entry meta data area.
				 *
				 * @since 1.2.0
				 */
				do_action( 'bp_activity_entry_meta' ); ?>
			</div>

		<?php endif; ?>

	</div>

	<?php

	/**
	 * Fires before the display of the activity entry comments.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_before_activity_entry_comments' ); ?>

	<?php if ( ( bp_activity_get_comment_count() || bp_activity_can_comment() ) || bp_is_single_activity() ) : ?>

		<div class="activity-comments">

			<?php bp_activity_comments(); ?>

			<?php if ( is_user_logged_in() && bp_activity_can_comment() ) : ?>

				<form action="<?php bp_activity_comment_form_action(); ?>" method="post" id="ac-form-<?php bp_activity_id(); ?>" class="ac-form"<?php bp_activity_comment_form_nojs_display(); ?>>
					<div class="ac-reply-avatar"><?php bp_loggedin_user_avatar( 'width=' . BP_AVATAR_THUMB_WIDTH . '&height=' . BP_AVATAR_THUMB_HEIGHT ); ?></div>
					<div class="ac-reply-content">
						<div class="ac-textarea">
							<label for="ac-input-<?php bp_activity_id(); ?>" class="bp-screen-reader-text"><?php
								/* translators: accessibility text */
								_e( 'Comment', 'buddypress' );
							?></label>
							<textarea id="ac-input-<?php bp_activity_id(); ?>" class="ac-input bp-suggestions" name="ac_input_<?php bp_activity_id(); ?>"></textarea>
						</div>
						<input type="submit" name="ac_form_submit" value="<?php esc_attr_e( 'Post', 'buddypress' ); ?>" /> &nbsp; <a href="<?php bp_activity_comment_cancel_url(); ?>" class="ac-reply-cancel"><?php esc_html_e( 'Cancel', 'buddypress' ); ?></a>
						<input type="hidden" name="comment_form_id" value="<?php bp_activity_id(); ?>" />
					</div>

					<?php

					/**
					 * Fires after the activity entry comment form.
					 *
					 * @since 1.5.0
					 */
					do_action( 'bp_activity_entry_comments' ); ?>

					<?php wp_nonce_field( 'new_activity_comment', '_wpnonce_new_activity_comment_' . bp_get_activity_id() ); ?>

				</form>

			<?php endif; ?>

		</div>

	<?php endif; ?>

	<?php

	/**
	 * Fires after the display of the activity entry comments.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_after_activity_entry_comments' ); ?>

</li>

<?php

/**
 * Fires after the display of an activity entry.
 *
 * @since 1.2.0
 */
do_action( 'bp_after_activity_entry' );
