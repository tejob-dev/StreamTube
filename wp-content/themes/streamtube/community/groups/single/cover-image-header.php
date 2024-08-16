<?php
/**
 * BuddyPress - Groups Cover Image Header.
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 * @version 12.0.0
 */

/**
 * Fires before the display of a group's header.
 *
 * @since 1.2.0
 */
do_action( 'bp_before_group_header' ); ?>

<div id="cover-image-container">
	<a class="rounded" id="header-cover-image" href="<?php bp_group_url(); ?>"></a>

	<div id="item-header-cover-image">
		<?php if ( ! bp_disable_group_avatar_uploads() ) : ?>
			<div id="item-header-avatar">
				<a href="<?php bp_group_url(); ?>">

					<?php bp_group_avatar(); ?>

				</a>
			</div><!-- #item-header-avatar -->
		<?php endif; ?>

		<div id="item-header-content">

			<div id="item-buttons">
				<?php

				/**
				 * Fires in the group header actions section.
				 *
				 * @since 1.2.6
				 */
				do_action( 'bp_group_header_actions' ); ?>	
			</div><!-- #item-buttons -->

			<?php

			/**
			 * Fires before the display of the group's header meta.
			 *
			 * @since 1.2.0
			 */
			do_action( 'bp_before_group_header_meta' ); ?>

			<div id="item-meta" class="m-0">

				<?php

				/**
				 * Fires after the group header actions section.
				 *
				 * @since 1.2.0
				 */
				do_action( 'bp_group_header_meta' ); ?>

				<div class="mb-2">
					<span class="highlight badge bg-info p-1"><?php bp_group_type(); ?></span>
				</div>

				<div class="text-secondary">
					<div class="time-since small" data-livestamp="<?php bp_core_iso8601_date( bp_get_group_last_active( 0, array( 'relative' => false ) ) ); ?>">
						<?php
						/* translators: %s: last activity timestamp (e.g. "Active 1 hour ago") */
						printf( __( 'Active %s', 'buddypress' ), bp_get_group_last_active() );
						?>
					</div>					
					<?php bp_group_description(); ?>
				</div>

				<?php bp_group_type_list(); ?>
			</div>
		</div><!-- #item-header-content -->

	</div><!-- #item-header-cover-image -->

</div><!-- #cover-image-container -->

<?php

/**
 * Fires after the display of a group's header.
 *
 * @since 1.2.0
 */
do_action( 'bp_after_group_header' ); ?>


