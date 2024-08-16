<?php
/**
 * Product Loop Start
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/loop-start.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     7.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$columns = wc_get_loop_prop( 'columns' );

printf(
    '<div class="clearfix"></div><div class="products row row-cols-1 row-cols-sm-2 row-cols-md-%1$s row-cols-lg-%1$s row-cols-xl-%1$s">',
    esc_attr( $columns )
);


