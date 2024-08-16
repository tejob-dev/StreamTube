<?php
/**
 * Define the Youtube API functionality
 *
 *
 * @link       https://themeforest.net/user/phpface
 * @since      2.0
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

class StreamTube_Core_Youtube_API_Videos extends StreamTube_Core_Youtube_API_Search{

    /**
     *
     * The part
     * 
     * @var string
     *
     * @since 2.0
     * 
     */
    protected $part           =   'snippet,contentDetails,statistics';

    /**
     *
     * The api endpoint
     * 
     * @var string
     *
     * @since 2.0
     * 
     */
    public $api_endpoint    =   '/videos';

    /**
     *
     * Get item id
     * 
     * @param  string $item
     * @return string
     *
     * @since 2.0
     * 
     */
    public function get_item_id( $item ){
        if( ! array_key_exists( 'id', $item ) ){
            return false;
        }

        return $item['id'];
    }    

    /**
     *
     * Get content details
     * 
     * @param  array $item
     * @return false|array
     *
     * @since 2.0
     * 
     */
    public function get_item_content_details( $item ){
        if( array_key_exists( 'contentDetails', $item ) ){
            return $item['contentDetails'];
        }

        return false;
    }

    /**
     *
     * Get statistics
     * 
     * @param  array $item
     * @return false|array
     *
     * @since 2.0
     * 
     */
    public function get_item_statistics( $item ){
        if( array_key_exists( 'statistics', $item ) ){
            return $item['statistics'];
        }

        return false;
    }

    /**
     *
     * Get tags
     * 
     * @param  array $item
     * @return false|array
     *
     * @since 2.0
     * 
     */
    public function get_item_tags( $item ){
        if( array_key_exists( 'tags', $item['snippet'] ) ){
            return $item['snippet']['tags'];
        }

        return false;
    }
}