<?php
/**
 * BuddyPress - Groups Activity
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 * @version 3.0.0
 */


/**
 *
 * Filter single group sidebar
 * 
 */
$sidebar = apply_filters( 'streamtube/bp/group/single/sidebar', 'buddypress' );

?>

<div class="row">

    <?php printf(
        '<div class="col-xl-%1$s col-lg-%1$s col-md-12 col-12">',
        $sidebar ? '8' : '12'
    );?>

		<div class="item-list-tabs no-ajax bg-white shadow-sm" id="subnav" aria-label="<?php esc_attr_e( 'Group secondary navigation', 'buddypress' ); ?>" role="navigation">
			<ul>
				<?php if ( bp_activity_is_feed_enable( 'group' ) ) : ?>
					<li class="feed">
						<a href="<?php bp_group_activity_feed_link(); ?>" class="bp-tooltip" data-bp-tooltip="<?php esc_attr_e( 'RSS Feed', 'buddypress' ); ?>" aria-label="<?php esc_attr_e( 'RSS Feed', 'buddypress' ); ?>">
							<?php _e( 'RSS', 'buddypress' ); ?>
						</a>
					</li>
				<?php endif; ?>

				<?php

				/**
				 * Fires inside the syndication options list, after the RSS option.
				 *
				 * @since 1.2.0
				 */
				do_action( 'bp_group_activity_syndication_options' ); ?>

				<li id="activity-filter-select" class="last">
					<label for="activity-filter-by"><?php _e( 'Show:', 'buddypress' ); ?></label>
					<select id="activity-filter-by">
						<option value="-1"><?php _e( '&mdash; Everything &mdash;', 'buddypress' ); ?></option>

						<?php bp_activity_show_filters( 'group' ); ?>

						<?php

						/**
						 * Fires inside the select input for group activity filter options.
						 *
						 * @since 1.2.0
						 */
						do_action( 'bp_group_activity_filter_options' ); ?>
					</select>
				</li>
			</ul>
		</div><!-- .item-list-tabs -->

		<?php

		/**
		 * Fires before the display of the group activity post form.
		 *
		 * @since 1.2.0
		 */
		do_action( 'bp_before_group_activity_post_form' ); ?>

		<?php if ( is_user_logged_in() && bp_group_is_member() ) : ?>

			<?php bp_get_template_part( 'activity/post-form' ); ?>

		<?php endif; ?>

		<?php

		/**
		 * Fires after the display of the group activity post form.
		 *
		 * @since 1.2.0
		 */
		do_action( 'bp_after_group_activity_post_form' ); ?>
		<?php

		/**
		 * Fires before the display of the group activities list.
		 *
		 * @since 1.2.0
		 */
		do_action( 'bp_before_group_activity_content' ); ?>

		<div class="activity single-group" aria-live="polite" aria-atomic="true" aria-relevant="all">

			<?php bp_get_template_part( 'activity/activity-loop' ); ?>

		</div><!-- .activity.single-group -->

		<?php

		/**
		 * Fires after the display of the group activities list.
		 *
		 * @since 1.2.0
		 */
		do_action( 'bp_after_group_activity_content' );
	?>
	</div>

    <?php if( $sidebar ): ?>
        <div class="col-xl-4 col-lg-4 col-md-12 col-12">
            <?php get_sidebar( $sidebar );?>
        </div>
    <?php endif;?>

</div>	
