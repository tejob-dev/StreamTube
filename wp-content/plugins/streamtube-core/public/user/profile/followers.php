<?php
if( ! defined('ABSPATH' ) ){
    exit;
}
?>
<section class="section-profile profile-followers py-4 pb-0 m-0">

    <?php printf(
        '<div class="%s">',
        esc_attr( join( ' ', streamtube_core_get_user_profile_container_classes() )  )
    )?>
        <?php 

        /**
         *
         * Fires before widgets
         * 
         */
        do_action( 'streamtube/core/user/profile/followers/widgets/before' );

        if( is_active_sidebar( 'sidebar-profile-followers' ) ):

            ob_start();

                dynamic_sidebar( 'sidebar-profile-followers' );

            $output = ob_get_clean();

            echo $output;

        endif;

        if( empty( $output ) ):

            ?>
                <div class="not-found p-3 text-center text-muted fw-normal h6"><p>
                    <?php
                     if( streamtube_core_is_my_profile() ){
                        esc_html_e( 'You have no followers.', 'streamtube-core' );
                     }
                     else{
                        printf(
                            esc_html__( '%s has no followers.', 'streamtube-core' ),
                            '<strong>'. get_user_by( 'ID', get_queried_object_id() )->display_name .'</strong>'
                        );                    
                     }
                    ?>
                </p></div>
            <?php

        endif;?>

        <?php
        /**
         *
         * Fires after widgets
         * 
         */
        do_action( 'streamtube/core/user/profile/followers/widgets/after' );
        ?>

    </div>

</section>