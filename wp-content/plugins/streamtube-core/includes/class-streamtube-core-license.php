<?php
/**
 * License
 *
 * @link       https://themeforest.net/user/phpface
 * @since      1.0.0
 *
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 */

/**
 *
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */

if( ! defined('ABSPATH' ) ){
    exit;
}

class Streamtube_Core_License{

    const ITEMID    = 33821786;

    const ITEMURL   = 'https://1.envato.market/qny3O5';

    public function __construct(){
        do_action( 'license_checker_loaded' );
    }

    /**
     * @return WP_Error|array
     */
    public function is_verified(){

        $check = get_option( 'envato_' . self::ITEMID );

        return (array)$check;

        if( ! $check || empty( $check ) || ! is_array( $check ) || ! array_key_exists( 'item', $check ) ){
            return new WP_Error(
                'not_verified',
                esc_html__( 'Not verified yet', 'streamtube-core' )
            );
        }

        if( (int)$check['item']['id'] == self::ITEMID ){
            return (array)$check;
        }

        return new WP_Error(
            'not_verified',
            esc_html__( 'Not verified yet', 'streamtube-core' )
        );
    }

    public function get_message(){

        return sprintf(
            esc_html__( '%s to unlock all premium features.', 'streamtube-core' ),
            sprintf(
                '<a class="text-white" href="%s">%s</a>',
                esc_url( admin_url( 'themes.php?page=license-verification' ) ),
                esc_html__( 'Verify Purchase', 'streamtube-core' )
            )
        );

    }

    public static function unregistered_template(){
        load_template( STREAMTUBE_CORE_ADMIN . '/partials/unregistered.php' );
    }    
}