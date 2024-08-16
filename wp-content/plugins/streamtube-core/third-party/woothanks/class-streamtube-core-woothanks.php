<?php
/**
 * Define the Woothanks functionality
 *
 *
 * @link       https://themeforest.net/user/phpface
 * @since      1.3
 *
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 */

/**
 *
 * @since      1.0.0
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */

if( ! defined('ABSPATH' ) ){
    exit;
}

class Streamtube_Core_WooThanks{

    private static function is_active(){
        return function_exists( 'woothanks' ) && function_exists( 'WC' );
    }

    /**
     *
     * The Buy button
     * 
     */
    public static function the_button_buy(){
        if( self::is_active() ){
            load_template( 
                untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/public/button-buy.php', 
                false,
                array()
            );
            do_action( 'streamtube/core/woothanks/button_buy_loaded' );
        }
    }

    /**
     *
     * The Buy Form modal
     * 
     */
    public static function the_modal_buyform(){
        if( self::is_active() && did_action( 'streamtube/core/woothanks/button_buy_loaded' ) ){
            load_template( 
                untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/public/modal-buy-form.php', 
                false,
                array()
            );
        }
    }
}