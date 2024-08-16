<?php
/**
 *
 * Add Password fields to default WP Registration form
 * 
 * @since 2.1.6
 */
if( ! defined('ABSPATH' ) ){
    exit;
}

$data = wp_parse_args( $_REQUEST, array(
    'password1'     =>  '',
    'password2'     =>  ''
) );

?>
<div class="registration-fields registration-passwords">
    <?php

    /**
     *
     * Fires before password1 field
     *
     * @param array $data
     *
     * @since 2.1.6
     * 
     */
    do_action( 'streamtube/core/form/registration/password1/before', $data );
    ?>  
    <div class="user-pass-wrap">
        <label for="password1"><?php esc_html_e( 'Password', 'streamtube-core' ); ?></label>
        <div class="wp-pwd">
            <?php printf(
                '<input type="password" name="password1" id="password1" class="input" value="%s" />',
                esc_attr( $data['password1'] )
            )?>
            <button type="button" class="button button-secondary wp-hide-pw hide-if-no-js" data-toggle="0" style="display: inline-block;">
                <span class="dashicons dashicons-visibility" aria-hidden="true"></span>
            </button>                
        </div>
    </div>
    <?php
    /**
     *
     * Fires after password1 field
     *
     * @param array $data
     *
     * @since 2.1.6
     * 
     */
    do_action( 'streamtube/core/form/registration/password1/after', $data );
    ?>

    <div class="user-pass-wrap">
        <label for="password2"><?php esc_html_e( 'Confirm Password', 'streamtube-core' ); ?></label>
        <div class="wp-pwd">
            <?php printf(
                '<input type="password" name="password2" id="password2" class="input" value="%s" />',
                esc_attr( $data['password2'] )
            )?>
            <button type="button" class="button button-secondary wp-hide-pw hide-if-no-js" data-toggle="0" style="display: inline-block;">
                <span class="dashicons dashicons-visibility" aria-hidden="true"></span>
            </button>
        </div>
    </div>     
    <?php
    /**
     *
     * Fires after last_name field
     *
     * @param array $data
     *
     * @since 2.1.6
     * 
     */
    do_action( 'streamtube/core/form/registration/password2/after', $data );
    ?>  
</div>
<script type="text/javascript">
    jQuery( document ).on( 'click', '.wp-hide-pw', function(e){
        var button = jQuery(this);
        var password = button.prev();
        var icon = button.find( '.dashicons' );
        if( password.attr( 'type' ) == 'password' ){
            password.attr( 'type', 'text' );
            icon.addClass( 'dashicons-hidden' ).removeClass( 'dashicons-visibility' );
        }else{
            password.attr( 'type', 'password' );
            icon.removeClass( 'dashicons-hidden' ).addClass( 'dashicons-visibility' );
        }
        
    } );
</script>