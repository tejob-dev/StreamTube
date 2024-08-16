<?php

if( ! defined('ABSPATH' ) ){
    exit;
}

streamtube_core()->get()->buddypress->setup_bp_environments( 'groups' );

add_filter( 'bp_current_action', function( $action ){
    $action = $GLOBALS['wp_query']->query['groups'];

    if( ! $action ){
        $action = 'my-groups';
    }

    return $action;
} );

buddypress()->groups->late_includes();

do_action( 'bp_actions' );

bp_enqueue_scripts();

/**
 *
 * Filter the "has_sidebar"
 * 
 */
$has_sidebar = apply_filters( 'streamtube/core/bp/user/profile/has_sidebar', false );

/**
 *
 * Filter heading
 *
 * @param $string $heading
 * 
 */
$heading = apply_filters( 'streamtube/core/user/profile/groups', esc_html__( 'Groups', 'streamtube-core' ));
?>
<section id="buddypress" class="buddypress-wrap section-profile profile-groups py-4 pb-0 m-0">

    <?php printf(
        '<div class="%s">',
        esc_attr( join( ' ', streamtube_core_get_user_profile_container_classes() )  )
    )?>

        <div id="template-notices" role="alert" aria-atomic="true">
            <?php

            /** This action is documented in bp-templates/bp-legacy/buddypress/activity/index.php */
            do_action( 'template_notices' ); ?>

        </div>

        <div class="row">

            <?php printf(
                '<div class="col-xl-%1$s col-lg-%1$s col-md-12 col-12">',
                $has_sidebar ? '8' : '12'
            );?>    

                <div class="widget-title-wrap">
                    
                    <?php if( $heading ): ?>

                        <h2 class="widget-title no-after">
                            <?php echo $heading;?>
                        </h2>

                    <?php endif;?>

                </div>

                <?php 

                /**
                 *
                 * Fires before widgets
                 * 
                 */
                do_action( 'streamtube/core/user/profile/groups/widgets/before' );

                bp_get_template_part( 'members/single/groups' );

                /**
                 *
                 * Fires after widgets
                 * 
                 */
                do_action( 'streamtube/core/user/profile/groups/widgets/after' );
                ?>

            </div>
            <?php if( $has_sidebar ): ?>
                <div class="col-xl-4 col-lg-4 col-md-12 col-12">
                    <?php get_sidebar( $has_sidebar );?>
                </div>
            <?php endif;?>              
        </div>
</section>