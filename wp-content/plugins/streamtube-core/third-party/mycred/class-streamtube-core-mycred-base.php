<?php
/**
 * Define the myCred functionality
 *
 *
 * @link       https://themeforest.net/user/phpface
 * @since      1.0.0
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

class Streamtube_Core_myCRED_Base {

    /**
     *
     * Check if plugin is activated
     * 
     * @return boolean
     *
     * @since 1.1
     * 
     */
    public function is_activated(){
        return class_exists( 'myCRED_Core' );
    }

    /**
     *
     * Alias of is_activated
     * 
     * @return boolean
     *
     * @since 1.1
     * 
     */
    public function is_enabled(){
        return $this->is_activated();
    }

    /**
     *
     * Get public template directory
     * 
     * @return string 
     *
     * @since 1.1
     * 
     */
    public function get_template_dir(){
        return trailingslashit( plugin_dir_path( __FILE__ ) ) . 'public/';
    }

    /**
     *
     * load template file
     *
     * @since 1.1
     * 
     */
    public function load_template( $file, $require_once = true, $args = array() ){
        return load_template( $this->get_template_dir() . $file, $require_once, $args );
    }

    /**
     *
     * Include file in WP environment
     * 
     * @param  string $file
     *
     * @since 1.0.9
     * 
     */
    protected function include_file( $file ){
        require_once trailingslashit( plugin_dir_path( __FILE__ ) ) . $file;
    }
}