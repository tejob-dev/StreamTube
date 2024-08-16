<?php
/**
 *
 * The social profiles template file
 *
 * @since 2.2.1
 * 
 */
if( ! defined('ABSPATH' ) ){
    exit;
}

$socials = streamtube_core()->get()->customizer->get_socials();

?>
<form class="form form-profile form-ajax" method="post">

    <div class="widget">
        <div class="widget-title-wrap d-flex">
            <h2 class="widget-title no-after"><?php esc_html_e( 'Social Profiles', 'streamtube-core' );?></h2>              
        </div>
        <div class="widget-content">

            <?php
            /**
             *
             * Fires before fields
             * 
             */
            do_action( 'streamtube/core/user/dashboard/settings/socials/before' );
            ?>

            <?php

            $user_socials = streamtube_core()->get()->user->get_social_profiles();

            foreach ( $socials as $social_id => $social_name ) {

                streamtube_core_the_field_control( array(
                    'label'         =>  sprintf(
                        esc_html__( '%s URL', 'streamtube-core' ),
                        $social_name
                    ),
                    'name'          =>  'socials['. $social_id .']',
                    'value'         =>   array_key_exists( $social_id , $user_socials ) ? $user_socials[ $social_id ] : ''
                ) );
            }
            ?>

            <?php
            /**
             *
             * Fires after fields
             * 
             */
            do_action( 'streamtube/core/user/dashboard/settings/socials/after' );
            ?>

        </div>

    </div>    

    <div class="d-flex">
        <button type="submit" class="btn btn-primary ms-auto">
            <span class="icon-floppy"></span>
            <span class="button-label">
                <?php esc_html_e( 'Save Changes', 'streamtube-core' ); ?>
            </span>
        </button>
    </div>

    <input type="hidden" name="action" value="update_social_profiles">

    <?php printf(
        '<input type="hidden" name="request_url" value="%s">',
        streamtube_core()->get()->rest_api['user']->get_rest_url( '/update-social-profiles' )
    );?>    
</form>