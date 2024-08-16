<?php
/**
 * Define the Real_Cookie_Banner functionality
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

class StreamTube_Core_Real_Cookie_Banner{

    /**
     *
     * Get providers
     * 
     * @return array
     */
    public static function get_providers(){
        return array(
            'video/youtube'
        );
    }

    /**
     *
     * Check if plugin activated
     * 
     * @return boolean
     */
    public static function is_active(){
        return defined( 'RCB_PATH' ) ? true : false;
    }

    /**
     *
     * Filter player output
     * Require access original URL to view content if YouTube source found
     * 
     */
    public static function filter_player_output( $player, $setup ){

        if( is_embed() && in_array( $setup['sources'][0]['type'], self::get_providers() ) ){

            $RestrictContent = new Streamtube_Core_Restrict_Content();

            $message = esc_html__( 'Playback has been disabled by the video owner due to GDPR and ePrivacy Directive compliant', 'streamtube-core' );

            if( get_post_status( $setup['mediaid'] ) ){
                $message .=  '<br/>' . sprintf(
                    '<a href="%s">%s</a>',
                    esc_url( add_query_arg(
                        array(
                            'from'      =>  'embed_privacy_gdpr',
                            'referer'   =>  isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : false
                        ),
                        wp_get_shortlink( $setup['mediaid'] )
                    ) ),
                    sprintf(
                        esc_html__( 'Watch on %s', 'streamtube-core' ),
                        get_bloginfo( 'name' )
                    )
                );
            }

            $errors = new WP_Error(
                'require-consent',
                $message
            );

            /**
             *
             * Filter $errors
             * 
             * @var WP_Error
             */
            $errors = apply_filters( 'streamtube/core/rcb/embed/errors', $errors, $player, $setup );

            $player = $RestrictContent->get_notice_message( $errors, $setup );
        }

        return $player;
    }

}