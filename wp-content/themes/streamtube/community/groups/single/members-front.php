<?php
/**
 * BuddyPress - Groups Members
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 * @version 3.0.0
 */

?>
<div class="item-list-tabs bg-white shadow-sm" id="subnav"
	aria-label="<?php esc_attr_e( 'Group secondary navigation', 'buddypress' ); ?>" role="navigation">
	<ul class="p-1">
		<li class="groups-members-search ms-2" role="search">
			<?php streamtube_bp_directory_members_search_form(); ?>
		</li>

		<?php bp_groups_members_filter(); ?>
		<?php

		/**
		 * Fires at the end of the group members search unordered list.
		 *
		 * Part of bp_groups_members_template_part().
		 *
		 * @since 1.5.0
		 */
		do_action( 'bp_members_directory_member_sub_types' ); ?>

	</ul>
</div>

<h2 class="bp-screen-reader-text">
	<?php
	/* translators: accessibility text */
	_e( 'Members', 'buddypress' );
	?>
</h2>

<div id="members-group-list" class="group_members dir-list">

	<?php bp_get_template_part( 'groups/single/members' ); ?>

</div>