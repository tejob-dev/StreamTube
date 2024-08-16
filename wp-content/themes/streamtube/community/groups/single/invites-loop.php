<?php
/**
 * BuddyPress - Group Invites Loop
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 * @version 3.0.0
 */

?>
<div class="left-menu">

	<div id="invite-list" class="bg-white border-end">

		<ul class="list-group list-group-flush">
			<?php bp_new_group_invite_friend_list(); ?>
		</ul>

		<?php wp_nonce_field( 'groups_invite_uninvite_user', '_wpnonce_invite_uninvite_user' ); ?>

	</div>

</div><!-- .left-menu -->

<div class="main-column">

	<?php

	/**
	 * Fires before the display of the group send invites list.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_before_group_send_invites_list' ); ?>

	<?php if ( bp_group_has_invites( bp_ajax_querystring( 'invite' ) . '&per_page=10' ) ) : ?>

		<div id="pag-top" class="pagination">

			<div class="pag-count text-muted" id="group-invite-count-top">

				<?php bp_group_invite_pagination_count(); ?>

			</div>

			<div class="pagination-links text-muted" id="group-invite-pag-top">

				<?php bp_group_invite_pagination_links(); ?>

			</div>

		</div>

		<?php /* The ID 'friend-list' is important for AJAX support. */ ?>
		<ul id="friend-list" class="item-list border-top">

		<?php while ( bp_group_invites() ) : bp_group_the_invite(); ?>

			<li id="<?php bp_group_invite_item_id(); ?>" class="border-bottom">

				<div class="d-flex align-items-center gap-4">

					<div class="item-avatar">
						<?php bp_group_invite_user_avatar(); ?>
					</div>

					<div class="item m-0">

						<div class="item-title">
							<h3 class="text-body text-decoration-none fw-bold"><?php bp_group_invite_user_link(); ?></h3>

						</div>
						<div class="activity small text-muted"><?php bp_group_invite_user_last_active(); ?></div>
						<?php

						/**
						 * Fires inside the invite item listing.
						 *
						 * @since 1.1.0
						 */
						do_action( 'bp_group_send_invites_item' ); ?>

						<div class="action">
							<a class="button" href="<?php bp_group_invite_user_remove_invite_url(); ?>" id="<?php bp_group_invite_item_id(); ?>"><?php _e( 'Remove Invite', 'buddypress' ); ?></a>

							<?php

							/**
							 * Fires inside the action area for a send invites item.
							 *
							 * @since 1.1.0
							 */
							do_action( 'bp_group_send_invites_item_action' ); ?>
						</div>
					</div>
				</div>
			</li>

		<?php endwhile; ?>

		</ul><!-- #friend-list -->

		<div id="pag-bottom" class="pagination">

			<div class="pag-count text-muted" id="group-invite-count-bottom">

				<?php bp_group_invite_pagination_count(); ?>

			</div>

			<div class="pagination-links text-muted" id="group-invite-pag-bottom">

				<?php bp_group_invite_pagination_links(); ?>

			</div>

		</div>

	<?php else : ?>

		<div id="message" class="info">
			<p><?php _e( 'Select friends to invite.', 'buddypress' ); ?></p>
		</div>

	<?php endif; ?>

<?php

/**
 * Fires after the display of the group send invites list.
 *
 * @since 1.1.0
 */
do_action( 'bp_after_group_send_invites_list' ); ?>

</div><!-- .main-column -->
