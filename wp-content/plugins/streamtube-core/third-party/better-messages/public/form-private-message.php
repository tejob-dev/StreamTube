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
<form class="form-ajax private-message position-relative">

    <?php

    if( ! streamtube_core()->get()->better_messages->get_bpm_settings('disableSubject') ){
        streamtube_core_the_field_control( array(
            'label'     =>  esc_html__( 'Subject', 'streamtube-core' ),
            'type'      =>  'text',
            'name'      =>  'subject',
            'value'     =>  ''
        ) );
    }
    ?>

    <?php
    streamtube_core_the_field_control( array(
        'label'     =>  esc_html__( 'Message', 'streamtube-core' ),
        'type'      =>  'textarea',
        'name'      =>  'message',
        'value'     =>  ''
    ) )
    ?>

    <input type="hidden" name="recipients[]" id="recipients" value="0">

    <?php printf(
        '<input type="hidden" name="nonce" value="%s">',
        esc_attr( wp_create_nonce( 'wp_rest' ) )
    );?>

    <?php printf(
        '<input type="hidden" name="request_url" value="%s">',
        esc_attr( rest_url( 'better-messages/v1/thread/new' ) )
    );?>    

    <input type="hidden" name="action" value="send_private_message">

    <div class="form-submit button-group">
		<button type="submit" class="btn btn-danger btn-sm">
            <?php printf(
                '<span class="btn__icon %s"></span>',
                esc_attr( $args->button_icon )
            );?>
            <span class="btn__text text-white">
                <?php esc_html_e( 'Send', 'streamtube-core' ); ?>
            </span>
        </button>
    </div>

    <div class="spinner-wrap d-block bg-white w-100 h-100 position-absolute top-0 left-0">

        <div class="position-absolute top-50 start-50 translate-middle">
            <?php get_template_part( 'template-parts/spinner', '', array(
                'type'  =>  'danger'
            ) ); ?>
        </div>
	
    </div>
</form>