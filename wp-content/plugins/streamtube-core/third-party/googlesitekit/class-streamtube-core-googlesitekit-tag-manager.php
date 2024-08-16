<?php
/**
 * Define the Google Analytics Report functionality
 *
 *
 * @link       https://themeforest.net/user/phpface
 * @since      1.0.0
 *
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 */

/**
 * Define the analytics functionality
 *
 * @since      1.0.8
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */

if( ! defined('ABSPATH' ) ){
    exit;
}

class Streamtube_Core_GoogleSiteKit_Tag_Manager extends Streamtube_Core_GoogleSiteKit{

    /**
     *
     * Holds the module slug
     * 
     * @var string
     *
     * @since 1.0.8
     * 
     */
    protected $module = 'tagmanager';

    /**
     *
     * Enqueue embed scripts
     * 
     */
    public function enqueue_embed_scripts(){
        if( "" != $container_id = $this->get_container_id() ){
            wp_enqueue_script( 'tag-manager', add_query_arg( array(
                'id'    =>  $container_id
            ), '//www.googletagmanager.com/gtm.js' ) );
        }
    }

    /**
     *
     * Check if Google Tag manager module activated
     * 
     * @return true|false
     *
     * @since 1.0.8
     * 
     */
    public function is_connected(){
        return $this->is_module_active();
    }

    /**
     *
     * Get tag manager settings
     * 
     * @return array
     *
     * @since 1.0.8
     */
    public function get_settings(){
        return get_option( 'googlesitekit_tagmanager_settings' );
    }

    /**
     *
     * Get tag manager container ID
     * 
     * @return string
     *
     * @since 1.0.8
     * 
     */
    public function get_container_id(){
        $settings = $this->get_settings();

        if( is_array( $settings ) && array_key_exists( 'containerID', $settings ) ){
            return $settings['containerID'];
        }

        return false;
    }

    public function get_measurement_id(){
        $settings = $this->get_settings();

        if( is_array( $settings ) && array_key_exists( 'measurementID', $settings ) ){
            return $settings['measurementID'];
        }

        return false;
    }

    /**
     * @since 1.3
     */
    public function player_tracker( $setup, $source ){

        if( $this->is_connected() ){

            $post_id = 0;

            if( get_post_status( $setup['mediaid'] ) ){
                $post_id = $setup['mediaid'];
            }

            $setup['plugins']['playerTracker'] = array(
                'url'   =>  $post_id ? get_permalink( $post_id ) : '',
                'title' =>  $post_id ? get_the_title( $post_id ) : ''
            );
        }

        return $setup;
    }
}