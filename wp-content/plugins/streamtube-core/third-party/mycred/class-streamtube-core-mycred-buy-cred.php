<?php
/**
 * Define the myCred functionality
 *
 *
 * @link       https://themeforest.net/user/phpface
 * @since      1.1
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

class Streamtube_Core_myCRED_Buy_CRED extends Streamtube_Core_myCRED_Base{ 

    /**
     *
     * Holds settings
     * 
     * @var array
     *
     * @since 1.1
     * 
     */
    protected $settings;

    /**
     *
     * Class contructor
     * 
     * @param array $settings
     *
     * @since 1.1
     * 
     */
    public function __construct( $settings = array() ){
        $this->settings = $settings;
    }    

    /**
     *
     * Check if addon activated
     * 
     * @return boolean
     *
     * @since 1.1
     * 
     */
    public function is_activated(){
        return defined( 'MYCRED_PURCHASE' ) ? true : false;
    }
}