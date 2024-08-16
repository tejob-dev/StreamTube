<?php
/**
 *
 * Add Agreement field to default WP Registration form
 * 
 * @since 2.1.6
 */
if( ! defined('ABSPATH' ) ){
    exit;
}

global $streamtube;

?>
<div class="registration-fields registration-agreement">
    <?php
    /**
     *
     * Fires before agreement field
     *
     * @param array $data
     *
     * @since 2.1.6
     * 
     */
    do_action( 'streamtube/core/form/registration/agreement/before' );
    ?>   
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="agreement" name="agreement">
        <label class="form-check-label" for="agreement">
            <?php esc_html_e( 'Terms and conditions', 'streamtube-core' )?>
            <?php printf(
                '<a target="_blank" class="text-white" href="%s"><span class="icon-link-ext"></span></a>',
                esc_url( get_permalink( $streamtube->get()->user->get_registration_settings()->agreement ) )
            )?>
        </label>
    </div>
    <?php
    /**
     *
     * Fires after agreement field
     *
     * @param array $data
     *
     * @since 2.1.6
     * 
     */
    do_action( 'streamtube/core/form/registration/agreement/after' );
    ?> 
</div>