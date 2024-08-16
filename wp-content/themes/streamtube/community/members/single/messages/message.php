<?php
/**
 * BuddyPress - Private Message Content.
 *
 * This template is used in /messages/single.php during the message loop to
 * display each message and when a new message is created via AJAX.
 *
 * @since 2.4.0
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 * @version 3.0.0
 */

?>

<div class="message-box mb-4 bg-white border <?php bp_the_thread_message_css_class(); ?>">

	<div class="message-metadata d-flex align-items-center gap-4">

		<?php

		/**
		 * Fires before the single message header is displayed.
		 *
		 * @since 1.1.0
		 */
		do_action( 'bp_before_message_meta' ); ?>

		<?php streamtube_bp_the_thread_message_sender_avatar(); ?>

		<div class="message-metadata__inner">

			<?php if ( bp_get_the_thread_message_sender_link() ) : ?>

				<strong>
					<a class="text-body" href="<?php bp_the_thread_message_sender_link(); ?>">
						<?php bp_the_thread_message_sender_name(); ?>	
					</a>
				</strong>

			<?php else : ?>

				<strong class="text-body"><?php bp_the_thread_message_sender_name(); ?></strong>

			<?php endif; ?>

			<div class="activity text-muted my-2 small">
				<?php bp_the_thread_message_time_since(); ?>
			</div>

		</div>

		<?php if ( bp_is_active( 'messages', 'star' ) ) : ?>
			<div class="message-star-actions">
				<?php bp_the_message_star_action_link(); ?>
			</div>
		<?php endif; ?>

		<?php

		/**
		 * Fires after the single message header is displayed.
		 *
		 * @since 1.1.0
		 */
		do_action( 'bp_after_message_meta' ); ?>

	</div><!-- .message-metadata -->

	<?php

	/**
	 * Fires before the message content for a private message.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_before_message_content' ); ?>

	<div class="message-content">

		<?php bp_the_thread_message_content(); ?>

	</div><!-- .message-content -->

	<?php

	/**
	 * Fires after the message content for a private message.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_after_message_content' ); ?>

	<div class="clear"></div>

</div><!-- .message-box -->
