<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://themeforest.net/user/phpface
 * @since      1.0.0
 *
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/admin
 */

/**
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/admin
 * @author     phpface <nttoanbrvt@gmail.com>
 */

if( ! defined('ABSPATH' ) ){
	exit;
}

class Streamtube_Core_Admin_User{

    /**
     *
     * Filter user table
     * 
     * @param  array $columns
     * @return array new $columns
     *
     * @since 1.0
     * 
     */
    public function user_table( $columns ){
        return array_merge( $columns, array(
            'video_count'       =>  esc_html__( 'Videos', 'streamtube-core' ),
            'verify'            =>  esc_html__( 'Verify', 'streamtube-core' ),
            'deactivate'        =>  esc_html__( 'Deactivate', 'streamtube-core' )
        ) );
    }

    /**
     *
     * Filter user table
     * 
     * @param  string $output
     * @param string $column_name
     * @param innt $user_id
     *
     * @since 1.0
     * 
     */
    public function user_table_columns( $output, $column_name, $user_id ){

        global $streamtube;

        switch ( $column_name ) {
            case 'video_count':
                $output = number_format_i18n( count_user_posts( $user_id, 'video', true ) );
            break;

            case 'verify':
                $output = $streamtube->get()->user->get_verify_button( $user_id );
            break;

            case 'deactivate':
                $output = $streamtube->get()->user_privacy->get_action_button( $user_id );
            break;
        }

        return $output;
    }

}