<?php
/**
 *
 * The Dokan User Products template file
 * 
 */
if( ! defined('ABSPATH' ) ){
    exit;
}

$store_user   = dokan()->vendor->get( get_queried_object_id() );
$store_info   = $store_user->get_shop_info();
$map_location = $store_user->get_location();
$layout       = get_theme_mod( 'store_layout', 'left' );
$has_sidebar  = dokan_store_theme_sidebar_enabled() || is_active_sidebar( 'sidebar-store' );

if( $layout == 'full' ){
    $has_sidebar = false;
}

$heading = apply_filters( 'streamtube/core/user/profile/store', esc_html__( 'Store', 'streamtube-core' ));
?>
<section class="section-profile profile-store py-4 pb-0 m-0">

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
        </div>

        <div class="row">

            <?php printf(
                '<div class="col-xl-%1$s col-lg-%1$s col-md-12 col-12 col-primary">',
                $has_sidebar ? '8' : '12'
            );?>

                <?php
                /**
                 *
                 * @since 3.0.1
                 * 
                 */
                do_action( 'streamtube/core/user/profile/products/content' );
                ?>

            </div>

            <?php if( $has_sidebar ) :?>
                <div class="col-xl-4 col-lg-4 col-md-12 col-12 col-secondary">
                    <?php
                    dokan_get_template_part(
                        'store', 'sidebar', [
                            'store_user'   => $store_user,
                            'store_info'   => $store_info,
                            'map_location' => $map_location,
                        ]
                    );
                    ?>
                </div>
            <?php endif; ?>

        </div>        

    </div><!--.container-->

</section>