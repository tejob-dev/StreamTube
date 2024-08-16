<?php
if( ! defined('ABSPATH' ) ){
    exit;
}

global $wp_query;

$output = '';

$heading = apply_filters( 'streamtube/core/user/profile/videos', esc_html__( 'Videos', 'streamtube-core' ));

?>
<section class="section-profile profile-videos py-4 pb-0 m-0">
    <?php printf(
        '<div class="%s">',
        esc_attr( join( ' ', streamtube_core_get_user_profile_container_classes() )  )
    )?>

        <div class="widget-title-wrap d-flex">
            
            <?php if( $heading ): ?>

                <h2 class="widget-title no-after">
                    <?php echo $heading;?>
                </h2>

            <?php endif;?>

            <?php printf(
                '<div class="sortby %s">',
                ! is_rtl() ? 'ms-auto' : 'me-auto'
            );?>
                <?php get_template_part( 'template-parts/sortby' )?>
            </div>
        </div>

        <?php 

        /**
         *
         * Fires before widgets
         * 
         */
        do_action( 'streamtube/core/user/profile/videos/widgets/before' );

        if( is_active_sidebar( 'sidebar-profile-videos' ) ):

            ob_start();

                dynamic_sidebar( 'sidebar-profile-videos' );

            $output = ob_get_clean();

            echo $output;

        endif;

        if( empty( $output ) ):

            ?>
                <div class="not-found p-3 text-center text-muted fw-normal h6"><p>
                    <?php
                     if( streamtube_core_is_my_profile() ){
                        esc_html_e( 'You have not uploaded any videos yet.', 'streamtube-core' );
                     }
                     else{
                        printf(
                            esc_html__( '%s has not uploaded any videos yet.', 'streamtube-core' ),
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
        do_action( 'streamtube/core/user/profile/videos/widgets/after' );
        ?>
    </div>
</section>