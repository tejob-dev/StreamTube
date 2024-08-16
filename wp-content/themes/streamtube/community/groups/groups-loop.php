<?php
/**
 * BuddyPress - Groups Loop
 *
 * Querystring is set via AJAX in _inc/ajax.php - bp_legacy_theme_object_filter().
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 * @version 12.0.0
 */

?>

<?php

/**
 * Fires before the display of groups from the groups loop.
 *
 * @since 1.2.0
 */
do_action( 'bp_before_groups_loop' ); ?>

<?php if ( bp_get_current_group_directory_type() ) : ?>
	<p class="current-group-type"><?php bp_current_group_directory_type_message() ?></p>
<?php endif; ?>

<?php if ( bp_has_groups( bp_ajax_querystring( 'groups' ) ) ) : ?>

	<div id="pag-top" class="pagination">

		<div class="pag-count" id="group-dir-count-top">

			<?php bp_groups_pagination_count(); ?>

		</div>

		<div class="pagination-links" id="group-dir-pag-top">

			<?php bp_groups_pagination_links(); ?>

		</div>

	</div>

	<?php

	/**
	 * Fires before the listing of the groups list.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_before_directory_groups_list' ); ?>

	<div id="groups-list" class="post-grid members-grid item-list " aria-live="assertive" aria-relevant="all">

		<div <?php streamtube_bp_directory_members_classes() ?>>

			<?php while ( bp_groups() ) : bp_the_group(); ?>

				<div class="mb-4 group-item" id="group-item-<?php bp_group_id();?>">

					<div id="group-<?php bp_group_id();?>" <?php bp_group_class( explode( ' ' , 'h-100 member-loop group-loop shadow-sm bg-white rounded position-relative group-' . bp_get_group_id() ) ); ?>>

						<div class="profile-top">

							<div class="profile-header ratio ratio-21x9 h-auto bg-light">
								<div class="profile-header__photo rounded-top">

									<?php if( ! bp_disable_group_cover_image_uploads() ): ?>

										<a href="<?php bp_group_url(); ?>">
											<?php printf(
												'<div class="profile-photo" style="background-image: url(%s)"></div>>',
												esc_attr( bp_get_group_cover_url() )
											); ?>	
										</a>

									<?php endif; ?>
								
								</div>
									
								<?php if ( ! bp_disable_group_avatar_uploads() ) : ?>
									<div class="profile-header__avatar">
										<a href="<?php bp_group_url(); ?>">
											<div class="user-avatar is-off user-avatar-xl">
												<?php bp_group_avatar( 'type=thumb&width=96&height=96&class=img-thumbnail avatar' ); ?>
											</div>
										</a>
									</div>

								<?php endif; ?>

							</div>

							<div class="group-info author-info item">      

								<h3 class="group-name author-name mb-2">
									<?php printf(
										'<a class="text-body fw-bold text-decoration-none" href="%s">%s</a>',
										esc_url( bp_get_group_url() ),
										bp_get_group_name()
									);?>
								</h3>

								<div class="group-meta item-meta">
									<div class="group-activity activity text-muted small mb-2" data-livestamp="<?php bp_core_iso8601_date( bp_get_group_last_active( 0, array( 'relative' => false ) ) ); ?>">
										<?php
										/* translators: %s: last activity timestamp (e.g. "Active 1 hour ago") */
										printf( __( 'Active %s', 'buddypress' ), bp_get_group_last_active() );
										?>
									</div>

									<div class="group-meta item-meta small mb-2">
										<?php bp_group_type(); ?> / <?php bp_group_member_count(); ?>
									</div>
								</div>

								<?php

								/**
								 * Fires inside the listing of an individual group listing item.
								 *
								 * @since 1.1.0
								 */
								do_action( 'bp_directory_groups_item' ); 
								?>

								<div class="group-action">
									<?php
									/**
									 * Fires inside the action section of an individual group listing item.
									 *
									 * @since 1.1.0
									 */
									do_action( 'bp_directory_groups_actions' ); 
									?>
								</div>										
			
							</div>					

						</div>

					</div>

				</div>

			<?php endwhile; ?>

		</div>

	</div>	

	<?php

	/**
	 * Fires after the listing of the groups list.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_after_directory_groups_list' ); ?>

	<div id="pag-bottom" class="pagination">

		<div class="pag-count" id="group-dir-count-bottom">

			<?php bp_groups_pagination_count(); ?>

		</div>

		<div class="pagination-links" id="group-dir-pag-bottom">

			<?php bp_groups_pagination_links(); ?>

		</div>

	</div>

<?php else: ?>

	<div class="alert alert-warning p-2 px-3">
		<p class="m-0"><?php _e( 'There were no groups found.', 'buddypress' ); ?></p>
	</div>

<?php endif; ?>

<?php

/**
 * Fires after the display of groups from the groups loop.
 *
 * @since 1.2.0
 */
do_action( 'bp_after_groups_loop' );
