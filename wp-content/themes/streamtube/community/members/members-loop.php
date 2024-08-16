<?php
/**
 * BuddyPress - Members Loop
 *
 * Querystring is set via AJAX in _inc/ajax.php - bp_legacy_theme_object_filter()
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 * @version 3.0.0
 */

/**
 * Fires before the display of the members loop.
 *
 * @since 1.2.0
 */
do_action( 'bp_before_members_loop' ); 

?>

<?php if ( bp_get_current_member_type() ) : ?>
	<p class="current-member-type"><?php bp_current_member_type_message() ?></p>
<?php endif; ?>

<?php if ( bp_has_members( bp_ajax_querystring( 'members' ) ) ) : ?>

	<div id="pag-top" class="pagination">

		<div class="pag-count text-secondary my-2 mx-0" id="member-dir-count-top">

			<?php bp_members_pagination_count(); ?>

		</div>

		<div class="pagination-links" id="member-dir-pag-top">

			<?php bp_members_pagination_links(); ?>

		</div>

	</div>

	<?php

	/**
	 * Fires before the display of the members list.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_before_directory_members_list' ); ?>

	<div id="members-list" class="post-grid members-grid item-list" aria-live="assertive" aria-relevant="all">

		<div <?php streamtube_bp_directory_members_classes() ?>>

			<?php while ( bp_members() ) : bp_the_member(); ?>

				<div class="mb-4 user-item" id="user-item-<?php bp_member_user_id();?>">

					<div id="member-<?php bp_member_user_id();?>" <?php bp_member_class( explode( ' ' , 'h-100 member-loop shadow-sm bg-white rounded position-relative member-' . bp_get_member_user_id() ) ); ?>>

						<div class="profile-top d-flex flex-column h-100">

							<div class="profile-header ratio ratio-21x9 h-auto">

					            <?php
					            /**
					             *
					             * Fires before profile image
					             *
					             * @param  $args WP_User
					             *
					             * @since  1.0.0
					             * 
					             */
					            do_action( 'streamtube/core/user/card/profile_image/before', bp_get_member_user_id() );
					            ?>  

					            <?php streamtube_core_get_user_photo( array(
					                'user_id'   =>  bp_get_member_user_id(),
					                'before'    =>  '<div class="profile-header__photo rounded-top">',
					                'after'     =>  '</div>'
					            ) );?>

					            <?php
					            /**
					             *
					             * Fires after profile image
					             *
					             * @param  $args WP_User
					             *
					             * @since  1.0.0
					             * 
					             */
					            do_action( 'streamtube/core/user/card/profile_image/after', bp_get_member_user_id() );
					            ?>

					            <?php
					            /**
					             *
					             * Fires before avatar image
					             *
					             * @param  $args WP_User
					             *
					             * @since  1.0.0
					             * 
					             */
					            do_action( 'streamtube/core/user/card/avatar/before', bp_get_member_user_id() );
					            ?> 					            

					            <?php streamtube_core_get_user_avatar( array(
					                'user_id'       =>  bp_get_member_user_id(),
					                'wrap_size'     =>  'xl',
					                'before'        =>  '<div class="profile-header__avatar">',
					                'after'         =>  '</div>'
					            ) );
					            ?>

					            <?php
					            /**
					             *
					             * Fires after avatar image
					             *
					             * @param  $args WP_User
					             *
					             * @since  1.0.0
					             * 
					             */
					            do_action( 'streamtube/core/user/card/avatar/after', bp_get_member_user_id() );
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
					            do_action( 'streamtube/core/user/card/name/before', bp_get_member_user_id() );
					            ?>            

					            <?php streamtube_core_get_user_name( array(
					                'user_id'   =>  bp_get_member_user_id(),
					                'before'    =>  '<h2 class="author-name">',
					                'after'     =>  '</h2>'
					            ) );?>

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
					            do_action( 'streamtube/core/user/card/name/after', bp_get_member_user_id() );

								/**
								 * Fires inside the display of a directory member item.
								 *
								 * @since 1.1.0
								 */
								do_action( 'bp_directory_members_item' ); ?>

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
					        do_action( 'streamtube/core/user/card/info/before', bp_get_member_user_id() );

					        ?>        

					        <div class="member-info text-secondary border-top mt-auto nav nav-fill nav-justified">
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
					            do_action( 'streamtube/core/user/card/info/item', bp_get_member_user_id() );
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
					        do_action( 'streamtube/core/user/card/info/after', bp_get_member_user_id() );
					        ?>

						</div>

					</div>

				</div>

			<?php endwhile; ?>

		</div>

	</div>

	<?php

	/**
	 * Fires after the display of the members list.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_after_directory_members_list' ); ?>

	<?php bp_member_hidden_fields(); ?>

	<div id="pag-bottom" class="pagination">

		<div class="pag-count text-secondary my-2 mx-0" id="member-dir-count-bottom">

			<?php bp_members_pagination_count(); ?>

		</div>

		<div class="pagination-links" id="member-dir-pag-bottom">

			<?php bp_members_pagination_links(); ?>

		</div>

	</div>

<?php else: ?>

	<div class="alert alert-warning p-2 px-3">
		<p class="m-0"><?php _e( "Sorry, no members were found.", 'buddypress' ); ?></p>
	</div>

<?php endif; ?>

<?php

/**
 * Fires after the display of the members loop.
 *
 * @since 1.2.0
 */
do_action( 'bp_after_members_loop' );
