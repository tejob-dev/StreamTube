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

class StreamTube_Core_Youtube_API_Search extends StreamTube_Core_Youtube_API{
    /**
     *
     * The api endpoint
     * 
     * @var string
     *
     * @since 2.0
     * 
     */
    public $api_endpoint    =   '/search';

    /**
     *
     * The part
     * 
     * @var string
     *
     * @since 2.0
     * 
     */
    protected $part         =   'snippet';

    /**
     *
     * Set endpoint
     * 
     * @param string $endpoint
     */
    public function set_api_endpoint( $endpoint ){
        $this->api_endpoint = $endpoint;
    }

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

        if( array_key_exists( 'snippet', $item ) && array_key_exists( 'resourceId', $item['snippet'] ) ){
            return $item['snippet']['resourceId']['videoId'];
        }

        if( array_key_exists( 'id', $item ) && array_key_exists( 'videoId', $item['id'] ) ){
            return $item['id']['videoId'];
        }        

        return $item['id']['videoId'];
    }

    /**
     *
     * Get item url
     * 
     * @param  string $item
     * @return string
     *
     * @since 2.0
     * 
     */
    public function get_item_url( $item ){
        if( false != $item_id = $this->get_item_id( $item ) ){
            return 'https://www.youtube.com/watch?v=' . $item_id;
        }

        return false;
    }

    /**
     *
     * Get item title
     * 
     * @param  string $item
     * @return string
     *
     * @since 2.0
     * 
     */
    public function get_item_title( $item ){
        if( ! array_key_exists( 'title', $item['snippet'] ) ){
            return false;
        }

        return $item['snippet']['title'];
    }

    /**
     *
     * Get item description
     * 
     * @param  string $item
     * @return string
     *
     * @since 2.0
     * 
     */
    public function get_item_description( $item ){
        if( ! array_key_exists( 'description', $item['snippet'] ) ){
            return false;
        }

        return $item['snippet']['description'];
    }

    /**
     *
     * Get item channelId
     * 
     * @param  string $item
     * @return string
     *
     * @since 2.0
     * 
     */
    public function get_item_channel_id( $item ){
        if( ! array_key_exists( 'channelId', $item['snippet'] ) ){
            return false;
        }

        return $item['snippet']['channelId'];
    }

    /**
     *
     * Get item channelTitle
     * 
     * @param  string $item
     * @return string
     *
     * @since 2.0
     * 
     */
    public function get_item_channel_title( $item ){
        if( ! array_key_exists( 'channelTitle', $item['snippet'] ) ){
            return false;
        }

        return $item['snippet']['channelTitle'];
    }    

    /**
     *
     * Get item channel URL
     * 
     * @param  string $item
     * @return string
     *
     * @since 2.0
     * 
     */
    public function get_item_channel_url( $item ){
        return 'https://www.youtube.com/channel/' . $this->get_item_channel_id( $item );
    }    

    /**
     *
     * Get item publishedAt
     * 
     * @param  string $item
     * @return string
     *
     * @since 2.0
     * 
     */
    public function get_item_published_at( $item ){
        if( ! array_key_exists( 'publishedAt', $item['snippet'] ) ){
            return false;
        }

        return $item['snippet']['publishedAt'];
    }

    /**
     *
     * Get item thumbnail URL
     * 
     * @param  array $item
     * @return false|string
     *
     * @since 2.0
     * 
     */
    public function get_item_thumbnail_url( $item ){

        if( ! array_key_exists( 'thumbnails', $item['snippet'] ) ){
            return false;
        }

        $highest = end( $item['snippet']['thumbnails'] );

        return $highest['url'];
    }

    /**
     *
     * Get item IDs
     *
     * @param $response
     * 
     * @return array
     *
     * @since 2.0
     * 
     */
    public function get_item_ids( $response ){

        $item_ids = array();

        if( ! array_key_exists( 'items' , $response ) ){
            return false;
        }

        if( count( $response['items'] ) == 0 ){
            return false;
        }

        if( $this->api_endpoint == '/playlistItems' ){
            for ( $i=0; $i < count( $response['items'] ); $i++) {
                $item_ids[] = $response['items'][$i]['snippet']['resourceId']['videoId'];
            }
        }else{
            for ( $i=0; $i < count( $response['items'] ); $i++) {
                $item_ids[] = $response['items'][$i]['id']['videoId'];
            }            
        }
        
        return $item_ids;
    }

    /**
     *
     * Get next page token
     * 
     * @param  array $response
     * @return string|false
     *
     * @since 2.0
     * 
     */
    public function get_next_page_token( $response ){
        if( array_key_exists( 'nextPageToken' , $response ) ){
            return $response['nextPageToken'];
        }

        return '';
    }

    /**
     *
     * Get prev page token
     * 
     * @param  array $response
     * @return string|false
     *
     * @since 2.0
     * 
     */
    public function get_prev_page_token( $response ){
        if( array_key_exists( 'prevPageToken' , $response ) ){
            return $response['prevPageToken'];
        }

        return false;
    }

    /**
     *
     * Get total results
     * 
     * @param  array $response
     * @return int
     *
     * @since 2.0
     * 
     */
    public function get_total_results( $response ){
        return (int)$response['pageInfo']['totalResults'];
    }

    /**
     *
     * Get results per page
     * 
     * @param  array $response
     * @return int
     *
     * @since 2.0
     * 
     */
    public function get_results_per_page( $response ){
        return (int)$response['pageInfo']['resultsPerPage'];
    }
}