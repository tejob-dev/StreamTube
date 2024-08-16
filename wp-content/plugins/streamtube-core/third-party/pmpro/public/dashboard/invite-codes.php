<?php
if( ! defined('ABSPATH' ) ){
    exit;
}

$user_id = get_current_user_id();

//make sure they have codes
$codes = pmproio_getInviteCodes( $user_id );

?>

<div class="page-head mb-3 d-flex gap-3 align-items-center">
	<h6 class="page-title h6">
		<?php esc_html_e( 'Invite Codes', 'streamtube-core' ); ?>
	</h6>
</div>

<div id="pmproio_codes" class="pmpro_box clear">

	<?php if( $codes ): ?>

	<div class="mb-4">
		<?php if( count( $codes ) == 1 ) { ?>
			<p class="text-secondary"><?php esc_html_e('Give this code to your invited member to use at checkout', 'streamtube-core'); ?></p>
		<?php } else { ?>
			<p class="text-secondary"><?php esc_html_e('Give these codes to your invited members to use at checkout', 'streamtube-core'); ?></p>
		<?php } ?>

		<?php echo pmproio_displayInviteCodes(); ?>
	</div>

	<div class="mb-4">

		<h6 class="page-title h6"><?php esc_html_e('Used Invite Codes', 'streamtube-core' ); ?></h6>
		
		<p class="text-secondary"><?php echo pmproio_displayInviteCodes( $user_id, false, true); ?></p>

	</div>

	<?php else:?>

		<p class="text-secondary">
			<?php esc_html_e( 'No Invite Codes were found', 'streamtube-core' ); ?>
		</p>

	<?php endif;?>
</div>