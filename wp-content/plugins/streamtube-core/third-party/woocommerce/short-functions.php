<?php
/**
 * Short Functions
 *
 * @link       https://themeforest.net/user/phpface
 * @since      1.0.0
 *
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 */

if( ! defined('ABSPATH' ) ){
    exit;
}

/**
 *
 * Get the Edit Product link
 * 
 * @param  integer $product
 * 
 */
function streamtube_core_wc_edit_product_link( $product = 0 ){

	if( ! current_user_can( 'edit_products' ) || ! $product ){
		return;
	}	

	if( is_int( $product ) ){
		$product = wc_get_product( $product );
	}

	$link = printf(
		'<a class="edit-product-url btn bg-dark text-white btn-sm button button-secondary" href="%s">%s %s</a>',
		esc_url( get_edit_post_link( $product->get_id() ) ),
		'<span class="icon-pencil"></span>',
		esc_html__( 'Edit Product', 'streamtube-core' )
	);

	return apply_filters( 'streamtube/core/woocommerce/edit_link', $link, $product );
}

/**
 *
 * Get products
 * 
 * @return false|array
 * 
 */
function streamtube_core_wc_get_products(){
	
    if( ! function_exists( 'wc_get_products' ) ){
        return false;
    }

    $args = array(
        'type'      =>  array( 'simple', 'variable' ),
        'status'    =>  array( 'private', 'publish' ),
        'limit'     =>  -1
    );

    /**
     *
     * Filter query args
     * 
     */
    $args = apply_filters( 'streamtube/core/woocommerce/sell_content/query_product_args', $args );

    return wc_get_products( $args );	
}

/**
 *
 * Get all purchased products of given user
 * 
 * @param  integer $user_id
 * @return array
 * 
 */
function streamtube_core_wc_get_purchased_products( $user_id = 0 ) {

	global $wpdb;

	if( ! $user_id ){
		$user_id = get_current_user_id();
	}

	$db_query = apply_filters( 
		'streamtube/core/woocommerce/get_purchased_products_dbversion', 
		version_compare( WC()->version, '8.2', '>' ) 
	);

	if( $db_query ):

		$purchased_products_ids = $wpdb->get_col( 
			$wpdb->prepare(
				"
				SELECT      itemmeta.meta_value
				FROM        {$wpdb->prefix}woocommerce_order_itemmeta itemmeta
				INNER JOIN  {$wpdb->prefix}woocommerce_order_items items
				            ON itemmeta.order_item_id 							= items.order_item_id
				INNER JOIN  {$wpdb->prefix}posts orders 		ON orders.ID 	= items.order_id
				INNER JOIN  {$wpdb->prefix}postmeta ordermeta 	ON orders.ID 	= ordermeta.post_id

				WHERE       itemmeta.meta_key 			= '_product_id'
				            AND ordermeta.meta_key 		= '_customer_user'
				            AND ordermeta.meta_value 	= %d
				            AND orders.post_status 		= 'wc-completed'
				ORDER BY    orders.post_date DESC
				",
				$user_id
			)
		);

	else:

		$purchased_products_ids = $wpdb->get_col(
			$wpdb->prepare(
				"
				SELECT      itemmeta.meta_value
				FROM        {$wpdb->prefix}woocommerce_order_itemmeta itemmeta
				INNER JOIN  {$wpdb->prefix}woocommerce_order_items items
				            ON itemmeta.order_item_id = items.order_item_id
				INNER JOIN  {$wpdb->prefix}wc_orders orders
				            ON orders.id = items.order_id
				WHERE       itemmeta.meta_key = '_product_id'
				            AND orders.customer_id = %d
				            AND orders.status = 'wc-completed'
				ORDER BY    orders.date_created_gmt DESC
				",
				$user_id
			)
		);

	endif;

	return array_unique( $purchased_products_ids );
}

/**
 *
 * Get all purchased videos
 * 
 * @param  integer $user_id
 * @param  array   $args
 * @return false|WP_Query
 * 
 */
function streamtube_core_wc_get_purchased_videos( $user_id = 0, $search = '' ){

	$products = streamtube_core_wc_get_purchased_products( $user_id );

	if( ! $products ){
		return false;
	}

	return get_posts( array(
		'post_type'			=>	'video',
		'post_status'		=>	array( 'publish', 'unlist' ),
		'posts_per_page'	=>	-1,
		's'					=>	$search,
		'meta_query'		=>	array(
			'relation'	=>	'OR',
			array(
				'key'		=>	StreamTube_Core_Woocommerce_Sell_Content::META_FIELD_REF_PRODUCT,
				'value'		=>	$products,
				'compare'	=>	'IN'
			),
			array(
				'key'		=>	StreamTube_Core_Woocommerce_Sell_Content::META_FIELD_BUILTIN_PRODUCT,
				'value'		=>	$products,
				'compare'	=>	'IN'
			)
		),
		'fields'			=>	'ids'
	) );
}