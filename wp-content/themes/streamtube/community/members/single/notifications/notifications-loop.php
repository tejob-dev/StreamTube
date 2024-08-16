<?php
/**
 * BuddyPress - Members Notifications Loop
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 * @version 3.0.0
 */

?>
<form action="" method="post" id="notifications-bulk-management">
	<table class="notifications table table-hover">
		<thead>
			<tr>
				<th class="icon"></th>
				<th class="bulk-select-all"><input id="select-all-notifications" type="checkbox"><label class="bp-screen-reader-text" for="select-all-notifications"><?php
					/* translators: accessibility text */
					_e( 'Select all', 'buddypress' );
				?></label></th>
				<th class="user"><?php esc_html_e( 'User', 'streamtube' )?></th>
				<th class="title"><?php _e( 'Notification', 'buddypress' ); ?></th>
				<th class="date"><?php _e( 'Date Received', 'buddypress' ); ?></th>
				<th class="actions"><?php _e( 'Actions',    'buddypress' ); ?></th>
			</tr>
		</thead>

		<tbody>

			<?php while ( bp_the_notifications() ) : bp_the_notification(); ?>

				<tr <?php streamtube_bp_the_notification_classes(); ?>>
					<td></td>
					<td class="bulk-select-check"><label for="<?php bp_the_notification_id(); ?>"><input id="<?php bp_the_notification_id(); ?>" type="checkbox" name="notifications[]" value="<?php bp_the_notification_id(); ?>" class="notification-check"><span class="bp-screen-reader-text"><?php
						/* translators: accessibility text */
						_e( 'Select this notification', 'buddypress' );
					?></span></label></td>
					<td class="notification-user" data-title="<?php esc_attr_e( 'User', 'streamtube' )?>"><?php streamtube_bp_the_notification_avatar( 64 ); ?></td>
					<td class="notification-description" data-title="<?php esc_attr_e( 'Notification', 'buddypress' )?>"><?php bp_the_notification_description();  ?></td>
					<td class="notification-since" data-title="<?php esc_attr_e( 'Date Received', 'buddypress' )?>"><?php bp_the_notification_time_since();   ?></td>
					<td class="notification-actions" data-title="<?php esc_attr_e( 'Actions', 'buddypress' )?>">

						<div class="dropdown">
							<button class="btn p-1 shadow-none" type="button" data-bs-toggle="dropdown" aria-expanded="false">
								<span class="btn__icon icon-ellipsis-vert"></span>
							</button>

							<ul class="dropdown-menu">

								<?php if ( bp_is_current_action( 'read' ) ) : ?>
									<li><a href="<?php echo bp_get_the_notification_mark_unread_url( bp_displayed_user_id() );?>" class="dropdown-item text-info fw-normal">
										<span class="btn__icon icon-eye-off"></span>
										<?php _e( 'Unread', 'streamtube' ); ?>
									</a></li>								
								<?php else: ?>
									<li><a href="<?php echo bp_get_the_notification_mark_read_url( bp_displayed_user_id() );?>" class="dropdown-item text-body fw-normal">
										<span class="btn__icon icon-eye"></span>
										<?php _e( 'Read', 'streamtube' ); ?>
									</a></li>		
								<?php endif;?>

								<li><a href="<?php echo bp_get_the_notification_delete_url( bp_displayed_user_id() );?>" class="dropdown-item text-danger fw-normal confirm">
									<span class="btn__icon icon-trash"></span>
									<?php _e( 'Delete', 'streamtube' ); ?>
								</a></li>									
							</ul>

						</div>						
					</td>
				</tr>

			<?php endwhile; ?>

		</tbody>
	</table>

	<div class="notifications-options-nav">
		<?php bp_notifications_bulk_management_dropdown(); ?>
	</div><!-- .notifications-options-nav -->

	<?php wp_nonce_field( 'notifications_bulk_nonce', 'notifications_bulk_nonce' ); ?>
</form>