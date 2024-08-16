<?php
/**
 * Define the Permissiont functionality
 *
 *
 * @link       https://themeforest.net/user/phpface
 * @since      2.2
 *
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 */

/**
 *
 * @since      2.2
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */

if( ! defined('ABSPATH' ) ){
    exit;
}

class StreamTube_Core_Woocommerce_Permission{
    /**
     *
     * Check if current user can edit product
     * 
     * @param  int $product_id
     * @return boolean
     * 
     */
    public static function can_edit_product( $product_id = 0 ){
        $retvar = current_user_can( 'edit_product', $product_id );

        /**
         *
         * Filter the retvar
         *
         * @param boolean $retvar
         * @param int $product_id
         * 
         */
        return apply_filters( 
            'streamtube/core/woocommerce/perm/can_edit_product', 
            $retvar, 
            $product_id 
        );
    }

    /**
     *
     * Check if current user can edit products
     * 
     * @return boolean
     * 
     */
    public static function can_edit_products(){
        $retvar = current_user_can( 'edit_products' );

        /**
         *
         * Filter the retvar
         *
         * @param boolean $retvar
         * @param int $product_id
         * 
         */
        return apply_filters( 
            'streamtube/core/woocommerce/perm/can_edit_products', 
            $retvar
        );
    }

    /**
     *
     * Check if current user can publish products
     * 
     * @return boolean
     * 
     */
    public static function can_publish_products(){
        $retvar = current_user_can( 'publish_products' );

        /**
         *
         * Filter the retvar
         *
         * @param boolean $retvar
         * @param int $product_id
         * 
         */
        return apply_filters( 
            'streamtube/core/woocommerce/perm/can_publish_products', 
            $retvar
        );
    }

    /**
     *
     * Check if current user is shop manager
     * 
     * @return boolean
     */
    public static function is_shop_manager(){
        return apply_filters(
            'streamtube/core/woocommerce/perm/is_shop_manager',
            current_user_can( 'shop_manager' )
        );
    }
}