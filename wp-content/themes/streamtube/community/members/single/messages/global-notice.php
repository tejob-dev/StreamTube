<?php
/**
 *
 * The global notice template file
 * 
 */

if( ! defined( 'ABSPATH' ) ){
	exit;
}

$notice = BP_Messages_Notice::get_active();

if ( empty( $notice ) ) {
    return false;
}

$closed_notices = bp_get_user_meta( bp_loggedin_user_id(), 'closed_notices', true );

if ( empty( $closed_notices ) ) {
    $closed_notices = array();
}

if ( is_array( $closed_notices ) ) {
    if ( ! in_array( $notice->id, $closed_notices, true ) && $notice->id ) {
        ?>
        <div id="message" class="alert alert-info p-4 info notice" rel="n-<?php echo esc_attr( $notice->id ); ?>">

            <div class="d-flex align-items-center">
                <h4><?php bp_message_notice_subject( $notice ); ?></h4>
                <a href="<?php bp_message_notice_dismiss_link(); ?>" id="close-notice" class="bp-tooltip btn text-danger ms-auto p-0 position-relative shadow-none" data-bp-tooltip="<?php esc_attr_e( 'Dismiss this notice', 'buddypress' ) ?>"><span class="bp-screen-reader-text"><?php _e( 'Dismiss this notice', 'buddypress' ) ?></span> <span aria-hidden="true">&Chi;</span></a>
            </div>

            <?php bp_message_notice_text( $notice ); ?>
            <?php wp_nonce_field( 'bp_messages_close_notice', 'close-notice-nonce' ); ?>
        </div>
        <?php
    }
}