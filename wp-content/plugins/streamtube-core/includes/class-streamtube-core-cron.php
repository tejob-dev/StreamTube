<?php
/**
 * Define the Cron functionality
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

class Streamtube_Core_Cron{

    /**
     *
     * Define schedules
     * 
     * @return array
     *
     * @since 1.0.8
     * 
     */
    protected function get_schedules(){
        $schedules = array();

        $schedules['5_minutes'] = array(
            'interval' => 1*60*5,
            'display'  => esc_html__( '5 minutes', 'streamtube-core' )
        );

        $schedules['10_minutes'] = array(
            'interval' => 1*60*10,
            'display'  => esc_html__( '10 minutes', 'streamtube-core' )
        );

        $schedules['15_minutes'] = array(
            'interval' => 1*60*15,
            'display'  => esc_html__( '15 minutes', 'streamtube-core' )
        );

        $schedules['20_minutes'] = array(
            'interval' => 1*60*20,
            'display'  => esc_html__( '20 minutes', 'streamtube-core' )
        );

        $schedules['30_minutes'] = array(
            'interval' => 1*60*30,
            'display'  => esc_html__( '30 minutes', 'streamtube-core' )
        );

        $schedules['45_minutes'] = array(
            'interval' => 1*60*45,
            'display'  => esc_html__( '45 minutes', 'streamtube-core' )
        );

        return $schedules;

    }

    /**
     *
     * Add schedules
     * 
     * @param  array $schedules
     * @return array
     *
     * @since 1.0.8
     * 
     */
    public function add_schedules( $schedules ){
        return array_merge( $schedules, $this->get_schedules() );
    }

    /**
     *
     * Get all available hooks
     * 
     * @return array
     *
     * @since 1.0.8
     * 
     */
    public function get_hooks(){
        $hooks = array(
            'streamtube'                            =>  'hourly',
            'streamtube_check_inactivity_posts'     =>  'weekly',
            'streamtube_check_pageviews'            =>  'hourly',
            'streamtube_check_videoviews'           =>  'hourly'
        );

        /**
         *
         * Filter the hooks
         *
         * @param array $hooks
         *
         * @since 1.0.8
         * 
         */
        return apply_filters( 'streamtube/core/cron/hooks', $hooks );
    }

    /**
     *
     * Add cron job
     *
     * @since 1.0.8
     * 
     */
    public function add_hooks(){

        $hooks = $this->get_hooks();

        if( ! $hooks ){
            return;
        }

        foreach ( $hooks as $hook => $interval ) {

            if ( ! wp_next_scheduled( $hook ) ) {
                wp_schedule_event( time(), $interval, $hook );
            }
        }
    }

    /**
     *
     * Remove hooks
     * 
     * @since 1.0.8
     * 
     */
    public function remove_hooks(){
        $hooks = $this->get_hooks();

        if( ! $hooks ){
            return;
        }

        foreach ( $hooks as $hook => $interval ) {

             $timestamp = wp_next_scheduled( $hook );

             wp_unschedule_event( $timestamp, $hook );
        }
    }

}