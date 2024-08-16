<?php
/**
 * Define the PMPro functionality
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

class StreamTube_Core_PMPro_Admin{

    /**
     *
     * The "Require Membership" metabox
     * 
     */
    public function add_meta_boxes(){

        if( defined( 'PMPRO_CPT_BASENAME' ) ){
            $options = get_option( 'pmprocpt_options' );

            if( is_array( $options ) && array_key_exists( 'cpt_selections' , $options ) ){
                if( in_array( 'video' , $options['cpt_selections']) ){
                    return;
                }
            }
        }

        if( ! function_exists( 'pmpro_page_meta' ) ){
            return;
        }

        add_meta_box( 
            esc_html__( 'Require Membership', 'streamtube-core' ), 
            esc_html__( 'Require Membership', 'streamtube-core' ), 
            'pmpro_page_meta', 
            'video', 
            'side', 
            'high', 
            null 
        );
    } 
}