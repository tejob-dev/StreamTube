<?php
/**
 * BuddyPress - Groups Members
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 * @version 3.0.0
 */

?>

<?php if ( bp_group_has_members( bp_ajax_querystring( 'group_members' ) ) ) : ?>

	<?php

	/**
	 * Fires before the display of the group members content.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_before_group_members_content' ); ?>

	<div id="pag-top" class="pagination">

		<div class="pag-count" id="member-count-top">

			<?php bp_members_pagination_count(); ?>

		</div>

		<div class="pagination-links" id="member-pag-top">

			<?php bp_members_pagination_links(); ?>

		</div>

	</div>

	<?php

	/**
	 * Fires before the display of the group members list.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_before_group_members_list' ); ?>

	<div id="members-list" class="post-grid members-grid item-list " aria-live="assertive" aria-relevant="all">

		<div <?php streamtube_bp_directory_members_classes() ?>>

		<?php while ( bp_group_members() ) : bp_group_the_member(); ?>

			<div class="mb-4 user-item" id="user-item-<?php bp_member_user_id();?>">

				<div id="member-<?php bp_group_member_id();?>" class="h-100 member-loop shadow-sm bg-white rounded position-relative member-<?php bp_group_member_id();?>">

					<div class="profile-top">

						<div class="profile-header ratio ratio-21x9 h-auto">

				            <?php streamtube_core_get_user_photo( array(
				                'user_id'   =>  bp_get_group_member_id(),
				                'before'    =>  '<div class="profile-header__photo rounded-top">',
				                'after'     =>  '</div>'
				            ) );?>

				            <?php streamtube_core_get_user_avatar( array(
				                'user_id'       =>  bp_get_group_member_id(),
				                'wrap_size'     =>  'xl',
				                'before'        =>  '<div class="profile-header__avatar">',
				                'after'         =>  '</div>'
				            ) );
				            ?>					            

						</div>						

						<div class="author-info item">

				            <?php
				            /**
				             *
				             * Fires before user name
				             *
				             * @param  $args WP_User
				             *
				             * @since  1.0.0
				             * 
				             */
				            do_action( 'streamtube/core/user/card/name/before', bp_get_group_member_id() );
				            ?>            

				            <?php streamtube_core_get_user_name( array(
				                'user_id'   =>  bp_get_group_member_id(),
				                'before'    =>  '<h2 class="author-name">',
				                'after'     =>  '</h2>'
				            ) );?>

				            <div class="last-active item-meta my-3">
								<span class="activity text-muted small" data-livestamp="<?php bp_core_iso8601_date( bp_get_group_member_joined_since( array( 'relative' => false ) ) ); ?>">
									<?php bp_group_member_joined_since(); ?>
								</span>
							</div>

				            <?php
				            /**
				             *
				             * Fires after user name
				             *
				             * @param  $args WP_User
				             *
				             * @since  1.0.0
				             * 
				             */
				            do_action( 'streamtube/core/user/card/name/after', bp_get_group_member_id() );

				            ?>
						</div>

				        <?php
				        /**
				         *
				         * Fires before info
				         *
				         * @param  $args WP_User
				         *
				         * @since  1.0.0
				         * 
				         */
				        do_action( 'streamtube/core/user/card/info/before', bp_get_group_member_id() );

				        ?>        

				        <div class="member-info text-secondary d-flex gap-3 border-top">
				            <?php
				            /**
				             *
				             * Fires after video count
				             *
				             * @param  $args WP_User
				             *
				             * @since  1.0.0
				             * 
				             */
				            do_action( 'streamtube/core/user/card/info/item', bp_get_group_member_id() );
				            ?>
				        </div>

				        <?php
				        /**
				         *
				         * Fires after info
				         *
				         * @param  $args WP_User
				         *
				         * @since  1.0.0
				         * 
				         */
				        do_action( 'streamtube/core/user/card/info/after', bp_get_group_member_id() );
				        ?>

					</div>

					<?php

					/**
					 * Fires inside the listing of an individual group member listing item.
					 *
					 * @since 1.1.0
					 */
					do_action( 'bp_group_members_list_item' ); ?>
				</div>
			</div>

		<?php endwhile; ?>

		</div>

	</div>

	<?php

	/**
	 * Fires after the display of the group members list.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_after_group_members_list' ); ?>

	<div id="pag-bottom" class="pagination">

		<div class="pag-count" id="member-count-bottom">

			<?php bp_members_pagination_count(); ?>

		</div>

		<div class="pagination-links" id="member-pag-bottom">

			<?php bp_members_pagination_links(); ?>

		</div>

	</div>

	<?php

	/**
	 * Fires after the display of the group members content.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_after_group_members_content' ); ?>

<?php else: ?>

	<div id="message" class="info">
		<p><?php _e( 'No members were found.', 'buddypress' ); ?></p>
	</div>

<?php endif;
