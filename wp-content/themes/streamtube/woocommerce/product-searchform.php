<?php
/**
 * The template for displaying product search form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/product-searchform.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<form role="search" method="get" class="woocommerce-product-search search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <div class="input-group-wrap position-relative w-100">
    	<input type="search" id="woocommerce-product-search-field-<?php echo isset( $index ) ? absint( $index ) : 0; ?>" class="search-field form-control" placeholder="<?php echo esc_attr__( 'Search products&hellip;', 'streamtube' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
    	<button class="btn border-0 shadow-none btn-main text-secondary" type="submit" value="<?php echo esc_attr_x( 'Search', 'submit button', 'streamtube' ); ?>">
            <span class="icon-search"></span>
        </button>
    	<input type="hidden" name="post_type" value="product" />
    </div>
</form>
