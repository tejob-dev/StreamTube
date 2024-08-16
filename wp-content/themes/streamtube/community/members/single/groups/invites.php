<?php
/**
 * BuddyPress - Members Single Group Invites
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 * @version 12.0.0
 */

/**
 * Fires before the display of member group invites content.
 *
 * @since 1.1.0
 */
do_action( 'bp_before_group_invites_content' ); ?>

<?php if ( bp_has_groups( 'type=invites&user_id=' . bp_displayed_user_id() ) ) : ?>

	<h2 class="bp-screen-reader-text"><?php
		/* translators: accessibility text */
		esc_html_e( 'Group invitations', 'buddypress' );
	?></h2>

	<div class="bg-white shadow-sm p-4 mb-4">

		<ul id="group-list" class="invites item-list border-top-0">

			<?php while ( bp_groups() ) : bp_the_group(); ?>

				<li class="vcard border-bottom member-1">

					<div class="d-flex align-items-start gap-4">

						<?php if ( ! bp_disable_group_avatar_uploads() ) : ?>
							<div class="item-avatar">
								<a href="<?php bp_group_url(); ?>"><?php bp_group_avatar( 'type=thumb&width=50&height=50' ); ?></a>
							</div>
						<?php endif; ?>

						<div class="item m-0">

							<h4 class="group-title mb-2">
								<?php printf(
									'<a href="%s" class="bp-group-home-link text-body fw-bold text-decoration-none %s-home-link">%s</a>',
									esc_url( bp_get_group_url() ),
									esc_attr( bp_get_group_slug() ),
									esc_html( bp_get_group_name() )
								); ?>
							</h4>

							<p class="group-member-count text-muted">
								<?php
								/* translators: %s: group members count */
								printf( _nx( '%d member', '%d members', bp_get_group_total_members( false ),'Group member count', 'buddypress' ), bp_get_group_total_members( false )  );
								?>
							</p>							

							<p class="desc">
								<?php bp_group_description_excerpt( false, 100 ); ?>
							</p>

							<?php

							/**
							 * Fires inside the display of a member group invite item.
							 *
							 * @since 1.1.0
							 */
							do_action( 'bp_group_invites_item' ); ?>

							<?php if( bp_is_my_profile() ): ?>

								<div class="action d-flex gap-4">
									<a data-group-id="<?php bp_group_id(); ?>" class="btn btn-sm btn-primary btn-group-action accept" href="<?php bp_group_accept_invite_link(); ?>"><?php _e( 'Accept', 'buddypress' ); ?></a>
									
									<a data-group-id="<?php bp_group_id(); ?>" class="btn btn-sm btn-danger btn-group-action reject" href="<?php bp_group_reject_invite_link(); ?>"><?php _e( 'Reject', 'buddypress' ); ?></a>

									<?php

									/**
									 * Fires inside the member group item action markup.
									 *
									 * @since 1.1.0
									 */
									do_action( 'bp_group_invites_item_action' ); ?>

								</div>

							<?php endif;?>

						</div>

					</div>
				</li>

			<?php endwhile; ?>
		</ul>

	</div>

<?php else: ?>

	<div class="alert alert-warning p-2 px-3">
		<p class="m-0">
			<?php if( bp_is_my_profile() ){
				esc_html_e( 'You have no outstanding group invites.', 'buddypress' );
			}else{
				printf(
					esc_html__( '%s have no outstanding group invites.', 'streamtube' ),
					'<strong>'. bp_get_displayed_user_fullname() .'</strong>'
				);
			}?>	
		</p>
	</div>

<?php endif;?>

<?php

/**
 * Fires after the display of member group invites content.
 *
 * @since 1.1.0
 */
do_action( 'bp_after_group_invites_content' );
