<?php
/**
 * The Template for displaying all single posts.
 *
 * @package dokan
 * @package dokan - 2014 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$store_user   = dokan()->vendor->get( get_query_var( 'author' ) );
$store_info   = $store_user->get_shop_info();
$map_location = $store_user->get_location();
$layout       = get_theme_mod( 'store_layout', 'left' );
$has_sidebar  = dokan_store_theme_sidebar_enabled() || is_active_sidebar( 'sidebar-store' );

if( $layout == 'full' ){
    $has_sidebar = false;
}

get_header( 'shop' );

?>

<?php printf( '<div class="page-main pt-4 dokan-single-store layout-%s">', esc_attr( $layout ) ); ?>

    <?php printf(
        '<div class="%s">',
        esc_attr( join( ' ', streamtube_dokan_get_store_container_classes() )  )
    )?>

        <?php dokan_get_template_part( 'store-header' ); ?>

        <?php do_action( 'dokan_store_profile_frame_after', $store_user->data, $store_info ); ?>        
    
        <div class="row">

            <?php printf(
                '<div class="col-xl-%1$s col-lg-%1$s col-md-12 col-12 col-primary">',
                $has_sidebar ? '8' : '12'
            );?>

                <?php do_action( 'woocommerce_before_main_content' ); ?>

                <div id="dokan-primary" class="dokan-store-wrap">

                    <div id="dokan-content" class="store-page-wrap">

                        <?php if ( have_posts() ) { ?>

                            <div class="seller-items">

                                <?php woocommerce_product_loop_start(); ?>

                                <?php
                                while ( have_posts() ) :
                                    the_post();
                                    ?>

                                    <?php wc_get_template_part( 'content', 'product' ); ?>

                                <?php endwhile; // end of the loop. ?>

                                <?php woocommerce_product_loop_end(); ?>

                            </div>

                            <?php dokan_content_nav( 'nav-below' ); ?>

                        <?php } else { ?>

                            <p class="dokan-info"><?php esc_html_e( 'No products were found of this vendor!', 'dokan-lite' ); ?></p>

                        <?php } ?>
                    </div>

                </div><!-- .dokan-store-wrap -->

                <?php do_action( 'woocommerce_after_main_content' ); ?>

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
    </div>
</div>

<?php get_footer( 'shop' ); ?>
