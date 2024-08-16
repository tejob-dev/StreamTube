<?php
/**
 * BuddyPress - membership invitations
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 * @version 8.0.0
 */

?>

<div class="item-list-tabs no-ajax bg-white shadow-sm" id="subnav" aria-label="<?php esc_attr_e( 'Member secondary navigation', 'buddypress' ); ?>" role="navigation">
	<ul>
		<?php if( bp_is_my_profile() ){
			streamtube_bp_user_subnav( get_queried_object_id(), 'members_invitations' );
		}else{
			bp_get_options_nav();
		}?>
	</ul>
</div>

<div class="bg-white shadow-sm p-4 mb-4">
	<?php
	switch ( bp_current_action() ) :

		case 'send-invites' :
			bp_get_template_part( 'members/single/invitations/send-invites' );
			break;

		case 'list-invites' :
		default :
			bp_get_template_part( 'members/single/invitations/list-invites' );
			break;

	endswitch;

	?>
</div>

