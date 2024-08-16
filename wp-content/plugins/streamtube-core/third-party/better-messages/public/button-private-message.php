<?php
/**
 *
 * The Button Message template file
 * 
 */
if( ! defined('ABSPATH' ) ){
    exit;
}

if( ! is_object( $args ) ){
    return;
}

?>
<div class="button-private-message">
    <?php
    /**
     *
     * Fires before button
     *
     * @since 1.1.5
     * 
     */
    do_action( 'streamtube/core/better_messages/button_private_message/before' );
    ?>
    <?php printf(
        '<button type="button" id="%s-%s" class="%s" data-bs-toggle="modal" data-bs-target="#%s" data-recipient-id="%s">',
        esc_attr( $args->button_id ),
        esc_attr( $args->recipient_id, ),
        esc_attr( join( ' ', $args->button_classes ) ),
        esc_attr( $args->modal_id ),
        esc_attr( $args->recipient_id )
    )?>
        <?php printf(
            '<span class="btn__icon position-absolute top-50 start-50 translate-middle %s" data-bs-toggle="tooltip" data-bs-title="%s"></span>',
            $args->button_icon,
            $args->button_text
        );?>
    </button>
    <?php
    /**
     *
     * Fires after button
     *
     * @since 1.1.5
     * 
     */
    do_action( 'streamtube/core/better_messages/button_private_message/after' );
    ?>
</div>