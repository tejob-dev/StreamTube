<?php
/**
 * BuddyPress - Members Friends Requests
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 * @version 3.0.0
 */

/**
 * Fires before the display of member friend requests content.
 *
 * @since 1.2.0
 */
do_action( 'bp_before_member_friend_requests_content' ); ?>

<?php if ( bp_has_members( 'type=alphabetical&include=' . bp_get_friendship_requests() ) ) : ?>

	<h2 class="bp-screen-reader-text"><?php
		/* translators: accessibility text */
		_e( 'Friendship requests', 'buddypress' );
	?></h2>

	<div id="pag-top" class="pagination no-ajax">

		<div class="pag-count text-muted" id="member-dir-count-top">

			<?php bp_members_pagination_count(); ?>

		</div>

		<div class="pagination-links text-muted" id="member-dir-pag-top">

			<?php bp_members_pagination_links(); ?>

		</div>

	</div>

	<div class="clearfix"></div>

	<div class="bg-white shadow-sm p-4">

		<ul id="friend-list" class="item-list border-top-0">
			<?php while ( bp_members() ) : bp_the_member(); ?>

				<li id="friendship-<?php bp_friend_friendship_id(); ?>" class="border-top-0 border-bottom">

					<div class="d-flex align-items-center gap-4">

						<div class="item-avatar">
							<a href="<?php bp_member_link(); ?>"><?php bp_member_avatar(); ?></a>
						</div>

						<div class="item">
							<div class="item-title">
								<a class="text-body text-decoration-none fw-bold" href="<?php bp_member_link(); ?>"><?php bp_member_name(); ?></a>
							</div>
							<div class="item-meta text-muted"><span class="activity"><?php bp_member_last_active(); ?></span></div>

							<?php
							/**
							 * Fires inside the display of a member friend request item.
							 *
							 * @since 1.1.0
							 */
							do_action( 'bp_friend_requests_item' );
							?>
						</div>

						<?php if( bp_is_my_profile() ): ?>
							<div class="action">
								<a class="accept btn btn-sm btn-primary" href="<?php bp_friend_accept_request_link(); ?>"><?php _e( 'Accept', 'buddypress' ); ?></a> &nbsp;
								<a class="accept btn btn-sm btn-danger reject" href="<?php bp_friend_reject_request_link(); ?>"><?php _e( 'Reject', 'buddypress' ); ?></a>

								<?php

								/**
								 * Fires inside the member friend request actions markup.
								 *
								 * @since 1.1.0
								 */
								do_action( 'bp_friend_requests_item_action' ); ?>
							</div>
						<?php endif;?>

					</div>
				</li>

			<?php endwhile; ?>
		</ul>

	</div>

	<?php

	/**
	 * Fires and displays the member friend requests content.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_friend_requests_content' ); ?>

	<div id="pag-bottom" class="pagination no-ajax mb-2">

		<div class="pag-count text-muted" id="member-dir-count-bottom">

			<?php bp_members_pagination_count(); ?>

		</div>

		<div class="pagination-links text-muted" id="member-dir-pag-bottom">

			<?php bp_members_pagination_links(); ?>

		</div>

	</div>

<?php else: ?>

	<div class="alert alert-warning p-2 px-3">
		<p class="m-0">
			<?php if( bp_is_my_profile() ){
				_e( 'You have no pending friendship requests.', 'buddypress' );
			}else{
				printf(
					esc_html__( '%s has no pending friendship requests.', 'streamtube' ),
					'<strong>'. bp_get_displayed_user_fullname() .'</strong>'
				);
			}?>
		</p>
	</div>

<?php endif;?>

<?php

/**
 * Fires after the display of member friend requests content.
 *
 * @since 1.2.0
 */
do_action( 'bp_after_member_friend_requests_content' );
