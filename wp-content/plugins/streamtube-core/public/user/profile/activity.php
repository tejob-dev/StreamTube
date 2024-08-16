<?php
/**
 *
 * BuddyPress activity template
 * 
 */

if( ! defined('ABSPATH' ) ){
    exit;
}

streamtube_core()->get()->buddypress->setup_bp_environments( 'activity' );

bp_enqueue_scripts();

$has_sidebar = is_active_sidebar( 'buddypress' ) ? 'buddypress' : false;

/**
 *
 * Filter the "has_sidebar"
 * 
 */
$has_sidebar = apply_filters( 'streamtube/core/bp/user/profile/has_sidebar', $has_sidebar );

/**
 *
 * Filter heading
 *
 * @param $string $heading
 * 
 */
$heading = apply_filters( 'streamtube/core/user/profile/activity', esc_html__( 'Activity', 'streamtube-core' ));

?>
<section id="buddypress" class="buddypress-wrap section-profile profile-activity py-4 pb-0 m-0">
	<?php printf(
		'<div class="%s">',
		esc_attr( join( ' ', streamtube_core_get_user_profile_container_classes() )  )
	)?>

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
            	do_action( 'streamtube/core/user/profile/activity/widgets/before' );

            	bp_get_template_part( 'members/single/activity' );

            	/**
            	 *
            	 * Fires after widgets
            	 * 
            	 */
            	do_action( 'streamtube/core/user/profile/activity/widgets/after' );
            	?>

            </div>
            <?php if( $has_sidebar): ?>
                <div class="col-xl-4 col-lg-4 col-md-12 col-12">
                    <?php get_sidebar( $has_sidebar );?>
                </div>
            <?php endif;?>              
        </div>
</section>