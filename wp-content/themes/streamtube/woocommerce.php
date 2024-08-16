<?php
/**
 *
 * The template for displaying home
 *
 * @link       https://1.envato.market/mgXE4y
 * @since      1.0.0
 *
 * @package    WordPress
 * @subpackage StreamTube
 * @author     phpface <nttoanbrvt@gmail.com>
 */
if( ! defined( 'ABSPATH' ) ){
    exit;
}

get_header();

if( have_posts() ){
    the_post();
}

?>

<header class="woocommerce-products-header page-header bg-white py-4 px-2 border-bottom">
    <?php printf(
        '<div class="%s">',
        esc_attr( join( ' ', streamtube_woo_get_container_classes() ) )
    )?>

        <?php streamtube_breadcrumbs(); ?>

        <?php if( ! is_product() ) : ?>

            <h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
            <?php
            /**
             * Hook: woocommerce_archive_description.
             *
             * @hooked woocommerce_taxonomy_archive_description - 10
             * @hooked woocommerce_product_archive_description - 10
             */
            do_action( 'woocommerce_archive_description' );
            ?>

        <?php else:?>

            <?php
            /**
             *
             * Fires after title
             * 
             */
            do_action( 'streamtube/woocommerce/product_single_title/before' );
            ?>            

            <?php woocommerce_template_single_title(); ?>

            <?php
            /**
             *
             * Fires after title
             * 
             */
            do_action( 'streamtube/woocommerce/product_single_title/after' );
            ?>

        <?php endif;?>
   
    </div>
</header>

    <div class="page-main pt-4">

        <?php printf(
            '<div class="%s">',
            esc_attr( join( ' ', streamtube_woo_get_container_classes() ) )
        )?>
        
            <?php 
            rewind_posts();
            woocommerce_content(); 
            ?>

        </div>

    </div>

<?php 
get_footer();