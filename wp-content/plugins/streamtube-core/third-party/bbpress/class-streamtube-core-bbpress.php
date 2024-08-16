<?php
/**
 * Define the bbPress functionality
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

class StreamTube_Core_bbPress{

    /**
     *
     * Check if plugin is activated
     * 
     * @return boolean
     *
     * @since 1.1.9
     * 
     */
    public function is_activated(){
        return class_exists( 'bbpress' );
    }    

    /**
     * 
     * Add Forum thumbnail
     */
    public function add_forum_thumbnail(){
        add_post_type_support( 'forum', 'thumbnail' );
    }

    /**
     *
     * Redirect to bbPress search if topic post type found in the search page
     * 
     * @since 1.1.9
     */
    public function redirect_search_page(){

        if( isset( $_GET['post_type'] ) && in_array( $_GET['post_type'], array( 'topic', 'reply' ) ) ){

            if( isset( $_GET['search'] ) && bbp_get_search_terms() ){
                wp_redirect( bbp_get_search_results_url() );
                exit;
            }
        }

    }
}