<?php
/**
 * BuddyPress - Activity Stream Comment
 *
 * This template is used by bp_activity_comments() functions to show
 * each activity.
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 * @version 3.0.0
 */

/**
 * Fires before the display of an activity comment.
 *
 * @since 1.5.0
 */
do_action( 'bp_before_activity_comment' ); ?>

<li id="acomment-<?php bp_activity_comment_id(); ?>" class="activity-comment border-0 border-bottom p-4 m-0">

	<div class="d-flex align-items-start gap-4">
		<div class="acomment-avatar">
			<a href="<?php bp_activity_comment_user_link(); ?>">
				<?php
				streamtube_core_get_user_avatar( array(
				    'link'          =>  false,
				    'wrap_size'     =>  'lg',
				    'user_id'       =>  bp_get_activity_comment_user_id()
				) );
				?>				
			</a>
		</div>

		<div class="acomment-meta-wrap">

			<div class="acomment-meta text-muted">
				<?php
				/* translators: 1: user profile link, 2: user name, 3: activity permalink, 4: ISO8601 timestamp, 5: activity relative timestamp */
				printf( __( '<a class="author-name text-body" href="%1$s">%2$s</a> replied <a href="%3$s" class="text-muted activity-time-since"><span class="time-since text-muted" data-livestamp="%4$s">%5$s</span></a>', 'buddypress' ), bp_get_activity_comment_user_link(), bp_get_activity_comment_name(), bp_get_activity_comment_permalink(), bp_core_get_iso8601_date( bp_get_activity_comment_date_recorded() ), bp_get_activity_comment_date_recorded() );
				?>
			</div>

			<div class="acomment-content m-0"><?php bp_activity_comment_content(); ?></div>

			<div class="acomment-options m-0">

				<?php if ( is_user_logged_in() && bp_activity_can_comment_reply( bp_activity_current_comment() ) ) : ?>

					<a href="#acomment-<?php bp_activity_comment_id(); ?>" class="acomment-reply text-body fw-bold me-4 bp-primary-action" id="acomment-reply-<?php bp_activity_id(); ?>-from-<?php bp_activity_comment_id(); ?>">
						<span class="icon-reply bg-transparent text-muted"></span>
						<?php _e( 'Reply', 'buddypress' ); ?>		
					</a>

				<?php endif; ?>

				<?php if ( bp_activity_user_can_delete() ) : ?>

					<a href="<?php bp_activity_comment_delete_link(); ?>" class="delete acomment-delete text-body fw-bold confirm bp-secondary-action" rel="nofollow">
						<span class="icon-cancel-circled bg-transparent text-muted"></span>
						<?php _e( 'Delete', 'buddypress' ); ?>
					</a>

				<?php endif; ?>

				<?php

				/**
				 * Fires after the default comment action options display.
				 *
				 * @since 1.6.0
				 */
				do_action( 'bp_activity_comment_options' ); ?>

			</div>			

		</div>
	</div>

	<?php bp_activity_recurse_comments( bp_activity_current_comment() ); ?>
</li>

<?php

/**
 * Fires after the display of an activity comment.
 *
 * @since 1.5.0
 */
do_action( 'bp_after_activity_comment' );
