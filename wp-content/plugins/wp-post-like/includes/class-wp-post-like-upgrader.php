<?php

/**
 * Upgrader
 *
 * @link       https://1.envato.market/mgXE4y
 * @since      1.2
 *
 * @package    WP_Post_Like
 * @subpackage WP_Post_Like/includes
 */

/**
 *
 * @since      1.0.2
 * @package    WP_Post_Like
 * @subpackage WP_Post_Like/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */
if( ! defined( 'ABSPATH' ) ){
	exit;
}

class WP_Post_Like_Upgrader {

    public static function upgrader(){

        $db_version = WP_Post_Like_Query::get_db_version();

        if( ! $db_version || version_compare( $db_version , '1.1', '<' ) ){
            WP_Post_Like_Query::_upgrade_db();

            $query = new WP_Post_Like_Query();

            $query->_update_posts_count();
        }
    }

}