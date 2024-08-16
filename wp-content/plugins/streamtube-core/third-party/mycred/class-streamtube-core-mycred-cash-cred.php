<?php
/**
 * Define the cashRed functionality
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

class Streamtube_Core_myCRED_Cash_Cred extends Streamtube_Core_myCRED_Base{
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
     * Holds the page slug in dashboard
     *
     * @var string
     *
     * @since 1.1
     * 
     */
    const ENDPOINT = 'withdrawal';

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
        return defined( 'MYCRED_CASHCRED' ) ? true : false;
    }

    /**
     *
     * Fox Withdrawal 404 error on Dashboard page
     * 
     * @since 1.1
     */
    public function fix_withdrawal_404(){

        if( ! $this->is_activated() ){
            return;
        }

        if( isset( $_POST['cashcred_pay_method'] ) && isset( $_POST['withdraw_on_dashboard'] ) ){

            $User_Dashboard = new Streamtube_Core_User_Dashboard();

            $url = $User_Dashboard->get_endpoint( get_current_user_id(), self::ENDPOINT );

            $_SERVER['REQUEST_URI'] = str_replace( home_url('/'), '/', $url );
        }
    }
}