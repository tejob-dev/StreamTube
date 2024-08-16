<?php
/**
 * Define the Youtube Importer functionality
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

class StreamTube_Core_Youtube_Importer_Options{

    public static function get_types(){
        return array(
            'video'     =>  esc_html__( 'Video', 'streamtube-core' )
        );
    }

    public static function get_video_types(){
        return array(
            'any'       =>  esc_html__( 'Any', 'streamtube-core' ),
            'episode'   =>  esc_html__( 'Only retrieve episodes of shows', 'streamtube-core' ),
            'movie'     =>  esc_html__( 'Only retrieve movies', 'streamtube-core' )
        );
    }

    public static function get_search_ins(){
        return array(
            'channel'          =>  esc_html__( 'Channel', 'streamtube-core' ),
            'playlist'         =>  esc_html__( 'Playlist', 'streamtube-core' ),
        );
    }      

    public static function get_orders(){
        return array(
            'date'          =>  esc_html__( 'Date', 'streamtube-core' ),
            'rating'        =>  esc_html__( 'Rating', 'streamtube-core' ),
            'relevance'     =>  esc_html__( 'Relevance', 'streamtube-core' ),
            'title'         =>  esc_html__( 'Title', 'streamtube-core' ),
            'viewCount'     =>  esc_html__( 'View Count', 'streamtube-core' )
        );
    }    

    public static function get_safe_search(){
        return array(
            'moderate'   =>  esc_html__( 'Moderate', 'streamtube-core' ),
            'none'       =>  esc_html__( 'None', 'streamtube-core' ),
            'strict'     =>  esc_html__( 'Exclude all restricted content', 'streamtube-core' )
        );        
    }

    public static function get_video_definition(){
        return array(
            'any'       =>  esc_html__( 'Any', 'streamtube-core' ),
            'high'      =>  esc_html__( 'Only retrieve HD videos', 'streamtube-core' ),
            'standard'  =>  esc_html__( 'Only retrieve videos in standard definition', 'streamtube-core' )
        );        
    }

    public static function get_video_dimension(){
        return array(
            'any'       =>  esc_html__( 'Any', 'streamtube-core' ),
            '2d'        =>  esc_html__( '2D', 'streamtube-core' ),
            '3d'        =>  esc_html__( '3D', 'streamtube-core' )
        );        
    }

    public static function get_video_duration(){
        return array(
            'any'       =>  esc_html__( 'Any', 'streamtube-core' ),
            'long'      =>  esc_html__( 'Only include videos longer than 20 minutes', 'streamtube-core' ),
            'medium'    =>  esc_html__( 'Only include videos that are between four and 20 minutes long', 'streamtube-core' ),
            'short'     =>  esc_html__( 'Only include videos that are less than four minutes long', 'streamtube-core' )
        );        
    }

    public static function get_event_type(){
        return array(
            ''             =>  esc_html__( 'None', 'streamtube-core' ),
            'completed'    =>  esc_html__( 'Only include completed broadcasts.', 'streamtube-core' ),
            'live'         =>  esc_html__( 'Only include active broadcasts.', 'streamtube-core' ),
            'upcoming'     =>  esc_html__( 'Only include upcoming broadcasts.', 'streamtube-core' )
        );
    }

    public static function get_video_license(){
        return array(
            'any'                 =>  esc_html__( 'Any', 'streamtube-core' ),
            'creativeCommon'      =>  esc_html__( 'Creative Commons', 'streamtube-core' ),
            'youtube'             =>  esc_html__( 'Standard YouTube License', 'streamtube-core' )
        );        
    } 

    public static function get_frequency_unit(){
        return array(
            'minutes'    =>  esc_html__( 'Minutes', 'streamtube-core' ),
            'hours'      =>  esc_html__( 'Hours', 'streamtube-core' ),
            'days'       =>  esc_html__( 'Days', 'streamtube-core' )
        );        
    }    

    /**
     *
     * Get post statuses
     * 
     * @return array
     *
     * @since 2.0
     * 
     */
    public static function get_post_statuses(){
        return get_post_statuses();
    }

    /**
     *
     * Get post statuses
     * 
     * @return array
     *
     * @since 2.0
     * 
     */
    public static function get_post_types(){
        return get_post_types();
    }    
}