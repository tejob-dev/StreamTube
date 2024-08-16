<?php
/**
 * BuddyPress - Sent Membership Invitations
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 * @version 8.0.0
 */
?>

<?php if ( bp_has_members_invitations() ) : ?>

	<h2 class="bp-screen-reader-text">
		<?php
		/* translators: accessibility text */
		esc_html_e( 'Invitations', 'buddypress' );
		?>
	</h2>

	<div id="pag-top" class="pagination no-ajax">
		<div class="pag-count text-muted m-0 mb-3" id="invitations-count-top">
			<?php bp_members_invitations_pagination_count(); ?>
		</div>

		<div class="pagination-links text-muted" id="invitations-pag-top">
			<?php bp_members_invitations_pagination_links(); ?>
		</div>
	</div>

	<?php bp_get_template_part( 'members/single/invitations/invitations-loop' ); ?>

	<div id="pag-bottom" class="pagination no-ajax">
		<div class="pag-count text-muted" id="invitations-count-bottom">
			<?php bp_members_invitations_pagination_count(); ?>
		</div>

		<div class="pagination-links text-muted" id="invitations-pag-bottom">
			<?php bp_members_invitations_pagination_links(); ?>
		</div>
	</div>

	<div class="clearfix"></div>

<?php else : ?>

	<div class="alert alert-warning p-2 px-3">
		<p class="m-0"><?php esc_html_e( 'There are no invitations to display.', 'buddypress' ); ?></p>
	</div>

<?php endif;
