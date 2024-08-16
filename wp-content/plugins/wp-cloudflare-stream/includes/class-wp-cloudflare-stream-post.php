<?php
/**
 *
 * @link       https://1.envato.market/mgXE4y
 * @since      1.0.0
 *
 * @package    WP_Cloudflare_Stream
 * @subpackage WP_Cloudflare_Stream/includes
 */

/**
 *
 * @package    WP_Cloudflare_Stream
 * @subpackage WP_Cloudflare_Stream/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */

if( ! defined('ABSPATH' ) ){
    exit;
}

class WP_Cloudflare_Stream_Post {

    /**
     *
     * Holds settings
     * 
     * @var array
     *
     * @since 1.0.0
     * 
     */
    public $settings = array();

    /**
     *
     * Holds Cloudflare Stream API
     * 
     * @var object
     *
     * @since 2.0.0
     * 
     */
    public $cloudflare_api;

    /**
     *
     * Class contructor
     * 
     * @param array $settings
     *
     * @since 1.0.0
     * 
     */
    public function __construct(){

        $this->settings = WP_Cloudflare_Stream_Settings::get_settings();

        $this->cloudflare_api = new WP_Cloudflare_Stream_API( array(
            'account_id'    => $this->settings['account_id'],
            'api_token'     => $this->settings['api_token'],
            'subdomain'     => $this->settings['subdomain']
        ) );
    }

    /**
     *
     * Check if enabled
     * 
     * @return boolean
     *
     * @since 1.0.0
     * 
     */
    public function is_enabled(){
        return $this->settings['enable'] ? true : false;
    }

    /**
     *
     * Check if Auto Upload
     * 
     * @return boolean
     *
     * @since 1.0.0
     * 
     */
    public function is_auto_upload(){
        if( array_key_exists( 'upload_type', $this->settings ) ){
            if( $this->settings['upload_type'] == 'auto' ){
                return true;
            }
        }

        return false;
    }

    /**
     *
     * Get stream UID
     * 
     * @param  int $attachment_id
     * @return string
     *
     * @since 1.0.0
     * 
     */
    public function get_stream_uid( $attachment_id ){
        return get_post_meta( 
            $attachment_id, 
            WP_Cloudflare_Stream_Settings::POST_CLOUDFLARE_UID, 
            true 
        );
    }

    /**
     *
     * Check if given attachment is live stream
     * 
     * @param  int  $attachment_id|$post_id
     * @return boolean
     *
     * @since 1.0.0
     * 
     */
    public function is_live_stream( $post_id = 0 ){
        return get_post_meta( $post_id, 'live_status', true );
    }

    /**
     *
     * Parse stream array
     * 
     * @param  array $stream
     * @return array
     *
     * @since 1.0.0
     * 
     */
    private function _parse_stream( $stream = array(), $attachment_id = 0 ){

        if( ! $stream || is_wp_error( $stream ) ){
            return false;
        }

        $status         = false;// error, ready, connected, disconnected or off
        $is_live        = false;
        $can_live       = false;
        $videoUID       = '';

        if( array_key_exists( 'recording' , $stream ) ){
            $can_live = true;
            if( $stream['recording']['mode'] == 'automatic' ){

                $poll = false;

                if( wp_doing_ajax() && $_REQUEST['action'] == 'load_video_source' ){

                    $sign_token = array();

                    if( $this->is_sign_token_enabled() ){
                        $sign_token = $this->get_sign_token();
                    }

                    $poll = $this->cloudflare_api->poll_live_status( $stream['uid'], $sign_token );
                }else{
                    $poll = array(
                        'status'    =>  $this->is_live_stream( $attachment_id ),
                        'videoUID'  =>  ''
                    );
                }

                if( is_wp_error( $poll ) ){
                    $status = 'off';
                }else{
                    $status = is_array( $poll ) && array_key_exists( 'status', $poll ) ? $poll['status'] : 'disconnected';

                    if( in_array( $status , array( 'ready', 'initializing' )) ){
                        $videoUID = $poll['videoUID'];
                    }
                }                
            }else{
                $status = 'off';
            }

            if( $status == 'connected' ){
                $is_live = true;
            }

            // Change ingest domain
            if( $this->settings['live_stream_domain']  ){
                $_default_rts = array(
                    'rtmps', 'rtmpsPlayback', 'srt', 'srtPlayback'
                );

                for ( $i=0; $i < count( $_default_rts ); $i++) { 
                    $stream[ $_default_rts[$i] ]['url'] = str_replace( 
                        'live.cloudflare.com', 
                        $this->settings['live_stream_domain'], 
                        $stream[ $_default_rts[$i] ]['url']
                    );                
                }
            }
        }
        
        elseif( is_array( $stream ) && array_key_exists( 'status' , $stream ) && is_array( $stream['status'] ) ){
            $status = $stream['status']['state'];    
        }
            
        return compact( 'status', 'is_live', 'can_live', 'stream', 'videoUID' ); 
    }    

    /**
     *
     * Get stream
     * 
     * @param  int $attachment_id
     * @return string
     *
     * @since 1.0.0
     * 
     */
    public function get_stream( $attachment_id ){

        $stream = get_post_meta( 
            $attachment_id, 
            WP_Cloudflare_Stream_Settings::POST_CLOUDFLARE, 
            true 
        );

        return $this->_parse_stream( $stream, $attachment_id );
    }

    /**
     *
     * Check if video ready to stream
     * 
     * @param  int $attachment_id
     * @return WP_Error|true
     *
     * @since 1.0.0
     * 
     */
    public function is_ready_to_stream( $attachment_id, $uid = '' ){

        $status = true;

        if( ! $uid ){
            $uid = $this->get_stream_uid( $attachment_id );
        }

        if( ! $uid ){
           return new WP_Error(
                'uid_not_found',
                esc_html__( 'Video UID is not found', 'wp-cloudflare-stream' )
            );
        }

        if( wp_doing_ajax() && $_REQUEST['action'] == 'load_video_source' ){
            if( $this->is_live_stream( $attachment_id ) ){
                $stream = $this->cloudflare_api->get_live_stream( $uid );
            }else{
                $stream = $this->cloudflare_api->get_video( $uid );
            }

            if( ! is_wp_error( $stream ) && is_array( $stream ) ){

                update_post_meta( 
                    $attachment_id, 
                    WP_Cloudflare_Stream_Settings::POST_CLOUDFLARE, 
                    $stream 
                );

                if( ! WP_Cloudflare_Stream_Settings::get_setting( 'webhook' ) ){
                    $data = compact( 'uid' );

                    /**
                     *
                     * Fires once webhook updated
                     *
                     * @param object $attachment_id
                     * @param array $data
                     *
                     * @since 2.1
                     * 
                     */
                    do_action( 'wp_cloudflare_stream_post_webhook_updated', $attachment_id, $data, $stream );
                }

                $stream = $this->_parse_stream( $stream, $attachment_id );

                if( $this->settings['auto_thumbnail'] && in_array( $stream['status'] , array( 'ready', 'initializing' )) ){
                    $this->generate_thumbnail_image( $attachment_id, $this->get_thumbnail_url( array(
                        'uid'   =>  $stream['videoUID']
                    ) ) );
                }
            }
        }else{
            $stream = $this->get_stream( $attachment_id );
        }

        if( is_wp_error( $stream ) ){
            return $stream;
        }

        $status = $stream['status'];

        switch ( $status ) {

            case 'closed':
                // Live stream closed
                $status = new WP_Error(
                    $status,
                    esc_html__( 'Stream is closed', 'wp-cloudflare-stream' ),
                    array(
                        'spinner'   =>  false
                    )
                );
            break;

            case 'disconnected':
                $status = new WP_Error(
                    $status,
                    esc_html__( 'Stream has not started yet', 'wp-cloudflare-stream' )
                );
            break;

            case 'initializing':
                $status = new WP_Error(
                    $status,
                    esc_html__( 'Stream is initializing', 'wp-cloudflare-stream' ),
                    array(
                        'spinner'   =>  'spinner-grow text-success'
                    )                    
                );
            break; 

            case 'error':
                $status = new WP_Error(
                    $stream['stream']['status']['errReasonCode'],
                    $stream['stream']['status']['errReasonText'],
                    array(
                        'spinner'   =>  false
                    )                    
                );
            break;

            case 'downloading':
                $status = new WP_Error(
                    $status,
                    esc_html__( 'Video is processing', 'wp-cloudflare-stream' ),
                    array(
                        'spinner'   =>  'spinner-grow text-success'
                    )
                );
            break;    
        }

        if( is_wp_error( $status ) ){

            $status->add_data( array(
                'handler'   =>  'cloudflare'
            ) );

            return $status;
        }

        return $stream;
    }

    /**
     *
     * Check if given stream is ready to play
     * 
     */
    private function is_ready_to_play( $stream = array() ){
        if( ! $stream || ! is_array( $stream ) ){
            return false;
        }

        if( ! is_array( $stream['status'] ) ){
            return false;
        }

        if( $stream['status']['state'] == 'ready' && (int)$stream['status']['pctComplete'] == 100 ){
            return true;
        }

        return false;
    }    

    /**
     *
     * Generate stream key
     * 
     * @return WP_Error|array
     */
    public function generate_stream_key(){
        $response = $this->cloudflare_api->generate_stream_key();

        if( is_wp_error( $response ) ){
            return $response;   
        }

        return update_option( 'wp_cloudflare_stream_key', $response );
    }    

    /**
     *
     * Get sign token
     * 
     * @return array
     */
    public function get_sign_token(){

        $sign_token = (array)get_option( 'wp_cloudflare_stream_key' );

        if( ! $sign_token ){
            $sign_token = array();
        }

        $sign_token = wp_parse_args( $sign_token, array(
            'id'    =>  '',
            'pem'   =>  '',
            'exp'   =>  3600*12
        ) );

        return apply_filters( 'wp_cloudflare_stream/sign_token', $sign_token );
    }

    /**
     *
     * Check if require sign token
     * 
     * @return boolean
     * 
     */
    public function is_sign_token_enabled(){
        return wp_validate_boolean( $this->settings['signed_url'] );
    }

    /**
     *
     * Get HLS url
     * 
     * @param  string $uid
     * @return string
     * 
     */
    public function get_playback_url( $uid, $hls = true ){

        $sign_token = array();

        if( $this->is_sign_token_enabled() ){
            $sign_token = $this->get_sign_token();
        }

        $url = $this->cloudflare_api->get_playback_url( $uid, $sign_token, $hls );

        return apply_filters( 'wp_cloudflare_stream/playback_url', $url, $uid, $sign_token, $hls );
    }

    /**
     *
     * Get preview url
     * 
     * @param  [type] $uid
     * 
     */
    public function get_preview_url( $uid ){
        $sign_token = array();

        if( $this->is_sign_token_enabled() ){
            $sign_token = $this->get_sign_token();
        }

        $url = $this->cloudflare_api->get_preview_url( $uid, $sign_token );

        return apply_filters( 'wp_cloudflare_stream/preview_url', $url, $uid, $sign_token );        
    }

    /**
     *
     * Get thumbnail image url
     * 
     * @param  array $args
     * 
     */
    public function get_thumbnail_url( $args = array() ){
        $sign_token = array();

        if( $this->is_sign_token_enabled() ){
            $sign_token = $this->get_sign_token();
        }

        $url = $this->cloudflare_api->get_thumbnail_url( $args, $sign_token );

        return apply_filters( 'wp_cloudflare_stream/thumbnail_url', $url, $args, $sign_token );                      
    }

    /**
     *
     * Get cloudflare iframe
     * 
     */
    public function get_iframe( $uid ){

        $sign_token = array();

        if( $this->is_sign_token_enabled() ){
            $sign_token = $this->get_sign_token();
        }

        $iframe = $this->cloudflare_api->get_iframe( $uid, $sign_token );

        /**
         *
         * Filter iframe
         *
         * @param string $iframe
         * @param string $uid
         * @param array sign_token
         * 
         */
        return apply_filters( 'wp_cloudflare_stream/iframe', $iframe, $uid, $sign_token );
    }

    /**
     *
     * Get WP Post ID (attachment_id) from Cloudflare UID
     * 
     * @param  string $uid
     * @return false|int
     *
     * @since 2.1
     * 
     */
    public function get_attachment_id_from_uid( $uid = '' ){

        if( ! $uid ){
            return false;
        }

        if( false !== ( $results = wp_cache_get( "uid_attachment_id_{$uid}" ) ) ){
            return (int)$results;
        }

        global $wpdb;

        $results = $wpdb->get_var( 
            $wpdb->prepare( 
                "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = %s AND meta_value = %s", 
                WP_Cloudflare_Stream_Settings::POST_CLOUDFLARE_UID,
                $uid
            )
        );

        if( $results ){

            wp_cache_set( "uid_attachment_id_{$uid}", (int)$results );

            return (int)$results;
        }

        return false;
    }

    /**
     *
     * Get Webhook URL
     * 
     * @return string
     *
     * @since 1.0.0
     * 
     */
    public function get_webhook_url(){
        return add_query_arg( array(
            'webhook'   =>  'cloudflare',
            'key'       =>  $this->settings['webhook_key'],
            'live'      =>  'off'
        ), home_url('/') );
    }

    /**
     *
     * Get Live Stream Webhook URL
     * 
     * @return string
     *
     * @since 1.0.0
     * 
     */
    public function get_live_webhook_url(){
        return add_query_arg( array(
            'live'   =>  'on'
        ), $this->get_webhook_url() );
    }

    /**
     *
     * Get default allowed_origins
     * 
     * @return array
     *
     * @since 1.0.0
     * 
     */
    public function get_default_allowed_origins(){
        $parsed_url = parse_url(home_url());

        return array( '*.' . $parsed_url['host'] );
    }

    /**
     *
     * Get allowed origins
     * 
     * @return array
     *
     * @since 1.0.0
     * 
     */
    public function get_allowed_origins(){

        $default = $this->get_default_allowed_origins();

        $allowed_origins = trim( $this->settings['allowed_origins'] );

        if( ! $allowed_origins ){
            return $default;
        }

        if( is_string( $allowed_origins ) ){
            $allowed_origins =  array_map( 'trim', explode( ",", $allowed_origins ) );
        }

        if( is_array( $allowed_origins ) ){
            $allowed_origins = array_merge( $allowed_origins, $default );
        }

        return array_unique( $allowed_origins );
    }

    /**
     *
     * Get the Download URL
     * 
     * @param  integer $attachment_id
     * @return false|string
     * 
     */
    public function get_downloadable_url( $attachment_id = 0 ){

        $uid = $this->get_stream_uid( $attachment_id );

        if( ! $uid || $this->is_live_stream( $attachment_id ) ){
            return false;
        }

        $sign_token = array();

        if( $this->is_sign_token_enabled() ){
            $sign_token = $this->get_sign_token();
        }        

        $url = $this->cloudflare_api->get_download_url( 
            $uid, 
            basename( get_attached_file( $attachment_id ) ),
            $sign_token
        );

        /**
         *
         * Filter the URL
         * 
         */
        return apply_filters( 'wp_cloudflare_stream_post_downloadable_url', $url, $attachment_id, $uid, $sign_token );        
    }

    /**
     *
     * Get Watermark UID
     * 
     * @return array
     *
     * @since 1.0.0
     * 
     */
    public function get_watermark_uid(){
        $watermark = WP_Cloudflare_Stream_Settings::get_setting( 'watermark' );

        if( is_array( $watermark ) && array_key_exists( 'uid' , $watermark ) ){
            return $watermark['uid'];
        }

        return false;
    }    

    /**
     *
     * Get recorded videos of given uid
     * 
     * @param  integer $live_uid
     * @return array|false
     *
     * @since 1.0.0
     * 
     */
    public function get_recorded_videos( $live_uid = 0 ){

        if( false !== $response = get_transient( "recorded_{$live_uid}" ) ){
            return $response;
        }

        $response = $this->cloudflare_api->get_recorded_videos( $live_uid );

        if( is_array( $response ) ){
            set_transient( "recorded_{$live_uid}", $response, 60 );
        }

        return $response;
    }

    /**
     *
     * Enable MP4 download
     * 
     * @param  int $attachment_id
     * 
     */
    public function enable_mp4_download( $attachment_id ){
        $response = $this->cloudflare_api->enable_download( $this->get_stream_uid( $attachment_id ) );

        if( ! is_wp_error( $response ) ){
            return update_post_meta( $attachment_id, 'cf_download_url', $response['default']['url'] );
        }

        return $response;
    }

    /**
     *
     * Disable MP4 download
     * 
     * @param  int $attachment_id
     * 
     */
    public function disable_mp4_download( $attachment_id ){
        $response = $this->cloudflare_api->disable_download( $this->get_stream_uid( $attachment_id ) );

        if( ! is_wp_error( $response ) ){
            return delete_post_meta( $attachment_id, 'cf_download_url' );
        }

        return $response;
    }

    /**
     *
     * Upload attachment
     * 
     * @param int $post_id attachment ID
     *
     * @return WP_Error|array
     *
     * @since 1.0.0
     * 
     */
    public function _add_attachment( $attachment_id ){

        if( $this->get_stream_uid( $attachment_id ) ){
            return new WP_Error(
                'already_synced',
                esc_html__( 'This file is already synced', 'wp-cloudflare-stream' )
            );            
        }

        // Check max size

        $_metadata = get_post_meta( $attachment_id, '_wp_attachment_metadata', true );

        if( is_array( $_metadata ) && array_key_exists( 'filesize', $_metadata ) ){

            $_filesize  = (int)$_metadata['filesize'];
            $_max       = (int)streamtube_core_get_max_upload_size();

            if( $_filesize && $_max && apply_filters( 'check_max_size_remote_source', true ) === true ){
                if( $_filesize > $_max ){
                    return new WP_Error(
                        'exceed_max_file_size',
                        esc_html__( 'The file size has exceeded the maximum allowed limit.', 'wp-cloudflare-stream' )
                    );                     
                }
            }
        }

        $request_args = array(
            'url'                   =>  wp_get_attachment_url( $attachment_id ),
            'name'                  =>  get_the_title( $attachment_id ) ,
            'allowedOrigins'        =>  $this->get_allowed_origins(),
            'requireSignedURLs'     =>  wp_validate_boolean( $this->settings['signed_url'] ),
            'creator'               =>  get_post( $attachment_id )->post_author
        );

        if( $this->settings['watermark_enable'] && false !== ( $watermark_uid = $this->get_watermark_uid()) ){
            $request_args['watermark']['uid'] = $watermark_uid;
        }

        $response = $this->cloudflare_api->fetch_video( $request_args );

        if( ! is_wp_error( $response ) ){
            update_post_meta( 
                $attachment_id, 
                WP_Cloudflare_Stream_Settings::POST_CLOUDFLARE, 
                $response
            );            
            update_post_meta( 
                $attachment_id, 
                WP_Cloudflare_Stream_Settings::POST_CLOUDFLARE_UID, 
                $response['uid'] 
            );
        }  

        return $response;
    }

    /**
     *
     * Upload attachment wrapper
     * 
     * @param int $post_id attachment ID
     *
     * @return $this->_add_attachment();
     *
     * @since 1.0.0
     * 
     */
    public function add_attachment( $attachment_id ){

        if( ! $this->is_enabled() || ! $this->is_auto_upload() ){
            return $attachment_id;
        }

        if( wp_attachment_is( 'video', $attachment_id ) ){
            return $this->_add_attachment( $attachment_id );
        }        
                
        return $attachment_id;
    }

    /**
     *
     * Delete attachment
     * 
     * @param int $post_id attachment ID
     *
     * @since 1.0.0
     * 
     */
    public function _delete_attachment( $attachment_id ){

        $uid = $this->get_stream_uid( $attachment_id );

        if( ! $uid ){
            return $attachment_id;
        }

        if( $this->is_live_stream( $attachment_id ) ){

            $this->cloudflare_api->delete_live_stream_playbacks( $uid );

            return $this->cloudflare_api->delete_live_stream( $uid );  
        }

        return $this->cloudflare_api->delete_video( $uid );  
    }

    /**
     *
     * Delete attachment wrapper
     * 
     * @param int $post_id attachment ID
     *
     * @since 1.0.0
     * 
     */
    public function delete_attachment( $attachment_id ){

        if( ! $this->is_enabled() ){
            return $attachment_id;
        }

        return $this->_delete_attachment( $attachment_id );
    }

    /**
     *
     * Update attachment
     * 
     * @param int $post_id attachment ID
     *
     * @since 1.0.0
     * 
     */
    public function _update_attachment( $attachment_id ){

        $response = array();

        $stream = get_post_meta( 
            $attachment_id, 
            WP_Cloudflare_Stream_Settings::POST_CLOUDFLARE, 
            true 
        );

        if( ! $stream ){
            return $attachment_id;
        }

        $data = array(
            'uid'                   =>  $stream['uid'],
            'name'                  =>  get_the_title( $attachment_id ),
            'creator'               =>  get_post( $attachment_id )->post_author
        );

        if( $this->is_live_stream( $attachment_id ) ){

            $preferLowLatency           = wp_validate_boolean( $this->settings['live_ll_hls'] );
            $deleteRecordingAfterDays   = absint( $this->settings['live_delete_recorded_period'] );

            $data =  array_merge( $data, compact(
                'deleteRecordingAfterDays', 'preferLowLatency'
            ) );

            $data = array_merge( $data, $stream['recording'] );

            $response = $this->cloudflare_api->update_live_stream( $data );
        }else{
            $data = array_merge( $data, array(
                'allowedOrigins'            =>  $this->get_allowed_origins(),
                'requireSignedURLs'         =>  wp_validate_boolean( $this->settings['signed_url'] )
            ) );

            $response = $this->cloudflare_api->update_video( $stream['uid'], $data );            
        }

        if( is_array( $response ) ){
            update_post_meta( $attachment_id, WP_Cloudflare_Stream_Settings::POST_CLOUDFLARE, $response );

            if( wp_validate_boolean( $this->settings['enable_mp4_download'] ) ){
                $this->enable_mp4_download( $attachment_id );
            }else{
                $this->disable_mp4_download( $attachment_id );
            } 

            /**
             *
             * Fires after updating stream content
             *
             * @param int $attachment_id
             * @param string $uid
             * @param array $response
             * 
             */
            do_action( 'wp_cloudflare_stream/updated_stream', $attachment_id, $stream['uid'], $response );
        }

        return $response;
    }

    /**
     *
     * Update attachment wrapper
     * 
     * @param int $post_id attachment ID
     *
     * @since 1.0.0
     * 
     */
    public function update_attachment( $attachment_id ){

        if( ! $this->is_enabled() ){
            return $attachment_id;
        }

        return $this->_update_attachment( $attachment_id );
    }

    /**
     *
     * Auto Fetch video
     * 
     * @param  int $post_id video post type ID
     *
     * @since 1.0.0
     * 
     */
    public function _fetch_external_video( $post_id, $source = '' ){

        set_time_limit(0);

        if( empty( $source ) || ! wp_http_validate_url( $source ) ){
            return false;
        }

        $headers = wp_get_http_headers( $source );

        if( ! $headers ){
            return false;
        }

        $filetype = explode( '/', $headers['content-type'] );

        if( $filetype[0] != 'video' || ! in_array( strtolower( $filetype[1] ) , wp_get_video_extensions() ) ){
            return false;
        }

        $post_title = get_the_title( $post_id );

        $_wp_attachment_metadata = array(
            'filesize'  =>  $headers['content-length'] ? (int)$headers['content-length'] : 0,
            'mime_type' =>  $headers['content-type']
        );        

        $attachment_id = wp_insert_attachment( array(
            'post_title'        =>  $post_title,
            'post_mime_type'    =>  join( '/', $filetype ),
            'post_status'       =>  'inherit',
            'meta_input'        =>  compact( '_wp_attachment_metadata' )
        ), $source, $post_id, true, true );

        if( is_wp_error( $attachment_id ) ){
            return $attachment_id;
        }

        return update_post_meta( $post_id, 'video_url', $attachment_id );
    }

    /**
     *
     * Fetch external video on adding videos from backend
     * 
     * @param   $post_id
     * @return _fetch_external_video()
     *
     * @since 2.1
     * 
     */
    public function fetch_external_video( $post_id ){

        if( ! $this->is_enabled() ){
            return $post_id;
        }        

        if( ! isset( $_POST ) || ! isset( $_POST['video_url'] ) ){
            return $post_id;
        }

        return $this->_fetch_external_video( $post_id, $_POST['video_url'] );
    }     

    /**
     *
     * Fetch external video on embedding videos from frontend
     * 
     * @param   $post_id
     * @return _fetch_external_video()
     *
     * @since 2.1
     * 
     */
    public function fetch_external_video_embed( $post, $source ){
        return $this->_fetch_external_video( $post->ID, $source );
    } 

    /**
     *
     * Create an empty attachment
     * 
     * @param  array  $post_args
     * @param  array  $stream
     * @return int|WP_Error The post ID on success. The value 0 or WP_Error on failure.
     *
     * @since 1.0.0
     * 
     */
    public function create_attachment( $post_args = array(), $stream = array() ){

        $stream = wp_parse_args( $stream, array(
            'live_status'   =>  ''
        ) );

        $meta = array(
            WP_Cloudflare_Stream_Settings::POST_CLOUDFLARE      =>  $stream,
            WP_Cloudflare_Stream_Settings::POST_CLOUDFLARE_UID  =>  $stream['uid'],
            '_wp_attached_file'                                 =>  $stream['uid'],
        );

        if( $stream['live_status'] ){
            $meta['live_status'] = $stream['live_status'];
        }

        return wp_insert_post( array_merge( $post_args, array(
            'post_type'         =>  'attachment',
            'post_mime_type'    =>  'video/mp4',
            'post_status'       =>  'inherit',
            'meta_input'        =>  $meta
        )), true );
    }

    /**
     *
     * Start live stream
     * 
     * @param  array $args
     * @return WP_Error or WP_Post
     *
     * @since 2.3
     * 
     */
    public function start_live_stream( $args = array() ){

        $thumbnail_file = null;

        $errors = new WP_Error();

        $args = wp_parse_args( $args, array(
            'name'                      =>  '',
            'description'               =>  '',
            'video_id'                  =>  0,
            'preferLowLatency'          =>  wp_validate_boolean( $this->settings['live_ll_hls'] ),
            'timeoutSeconds'            =>  absint( $this->settings['live_timeout'] ),
            'deleteRecordingAfterDays'  =>  absint( $this->settings['live_delete_recorded_period'] ),
            'allowedOrigins'            =>  $this->get_allowed_origins(),
            'requireSignedURLs'         =>  wp_validate_boolean( $this->settings['signed_url'] )
        ) );

        $args['creator'] = get_current_user_id();

        extract( $args );

        if( ! $this->settings['live_stream_enable'] ){
            $errors->add(
                'live_stream_disabled',
                esc_html__( 'Live Stream is disabled', 'wp-cloudflare-stream' )
            );
        }

        if( ! WP_Cloudflare_Stream_Permission::can_live_stream() ){
            $errors->add(
                'no_permission',
                esc_html__( 'You do not have permission to start live stream', 'wp-cloudflare-stream' )
            );
        }

        if( ! $this->settings['live_stream_multiple'] && ! WP_Cloudflare_Stream_Permission::can_manage() ){
            /**
             *
             * Check if this user opened an live stream before.
             * 
             */
            if( get_user_meta( $creator, '_last_live_uid', true ) ){
                $errors->add(
                    'not_allowed',
                    esc_html__( 'Not allowed, You cannot open mutiple live streams.', 'wp-cloudflare-stream' )
                );
            }
        }

        $name = trim( wp_strip_all_tags( $name ) );

        if( ! $name ){
            $errors->add(
                'empty_name',
                esc_html__( 'Live Stream Title is required.', 'wp-cloudflare-stream' )
            );          
        }

        // Thumbnail is required
        if( isset( $_FILES ) && array_key_exists( 'featured-image', $_FILES ) && $_FILES['featured-image'] ){

            $thumbnail_file = $_FILES[ 'featured-image' ];

            if( $thumbnail_file['error'] == 0 ){
                $type = array_key_exists( 'type' , $thumbnail_file ) ? $thumbnail_file['type'] : '';

                if ( 0 !== strpos( $type, 'image/' ) ) {
                    $errors->add( 
                        'file_not_accepted', 
                        esc_html__( 'Featured Image is required or File format is not accepted.', 'wp-cloudflare-stream' )
                    );
                }

                $max_thumbnail_size = (int)$this->settings['live_stream_thumbnail_size'] * 1024 * 1024;

                $max_thumbnail_size = min( $max_thumbnail_size, wp_max_upload_size() );

                if( $thumbnail_file['size'] > $max_thumbnail_size ){
                    $errors->add( 
                        'thumbnail_size_not_allowed',
                        sprintf(
                            esc_html__( 'Thumbnail Image size has to be smaller than %s', 'wp-cloudflare-stream' ),
                            size_format( $max_thumbnail_size )
                        )
                    );                    
                }

                /**
                 * @since 1.0.0
                 */
                $errors = apply_filters( 'wp_cloudflare_stream/start_live_stream/thumbnail', $errors, $thumbnail_file, $args );
            }
        }

        if( $video_id ){

            if( ( get_post_type( $video_id ) != 'video' ) || ! current_user_can( 'edit_post', $video_id ) ){
                $errors->add( 
                    'incorrect_video_id',
                    esc_html__( 'Incorrect Video ID', 'wp-cloudflare-stream' )
                );
            }
        }

        /**
         *
         * Filter the errors
         * 
         * @var WP_Error
         *
         * @since  1.0.0
         * 
         */
        $errors = apply_filters( 'wp_cloudflare_stream/start_live_stream/errors', $errors , $args );

        if( $errors->get_error_code() ){
            return $errors;
        }

        $live_stream = $this->cloudflare_api->create_live_stream( $args );

        if( is_wp_error( $live_stream ) ){
            return $live_stream;
        } 

        $post_args = array(
            'post_title'        =>  $name,
            'post_content'      =>  $description,
                'meta_input'    =>  array(
                'live_status'     =>  'disconnected'
            )
        );

        $attachment_id = $this->create_attachment( $post_args, array_merge( $live_stream, array(
            'live_status'   =>  'disconnected'
        ) ) );

        if( is_wp_error( $attachment_id ) ){
            // Delete live stream because we can't add attachment
            $this->cloudflare_api->delete_live_stream( $live_stream['uid'] );

            return $attachment_id;
        }

        if( ! $video_id ){
            $video_id = wp_insert_post( array_merge( $post_args, array(
                'post_type'     =>  'video',
                'post_status'   =>  $this->settings['live_stream_status'],
                'meta_input'    =>  array(
                    'video_url'       =>  $attachment_id,
                    'live_status'     =>  'disconnected'
                )
            )), true );

            if( is_wp_error( $video_id ) ){

                // Delete attachment.
                wp_delete_attachment( $attachment_id );

                // return WP_Error
                return $video_id;
            }
        }else{
            update_post_meta( $video_id, 'video_url', $attachment_id );

            update_post_meta( $video_id, 'live_status', 'disconnected' );
        }

        wp_update_post( array(
            'ID'            =>  $attachment_id,
            'post_parent'   =>  $video_id       
        ) );

        if( $thumbnail_file ){
            // Upload thumbnail
            $thumbnail_id = media_handle_upload( 
                'featured-image', 
                $attachment_id, 
                array( '' ), 
                array( 'test_form' => false ) 
            );

            if( ! is_wp_error( $thumbnail_id ) ){
                set_post_thumbnail( $attachment_id, $thumbnail_id );

                set_post_thumbnail( $video_id, $thumbnail_id );
            }
        }

        update_user_meta( $creator, '_last_live_uid', $live_stream['uid'] );

        /**
         * @since 1.0.0
         */
        do_action( 'wp_cloudflare_stream/started_live_stream', $video_id, $attachment_id, $live_stream );        

        $data = compact( 'video_id', 'live_stream' );

        return array_merge( $data, array(
            'message'   =>  esc_html__( 'Live Stream has been created successfully.', 'wp-cloudflare-stream' )
        ) );
    }

    /**
     *
     * Create live output
     * 
     * @param  array  $args
     * 
     */
    public function process_live_output( $args = array() ){

        $add_new = false;

        $args = wp_parse_args( $args, array(
            'post_id'   =>  0,
            'service'   =>  '',
            'server'    =>  '',
            'streamkey' =>  '',
            'enabled'   =>  true
        ) );

        foreach ( $args as $key => $value) {
            if( is_string( $value ) ){
                $args[ $key ] = trim($value);
            }
        }

        extract( $args );

        if( get_post_type( $post_id ) == 'video' ){
            $post_id = get_post_meta( $post_id, 'video_url', true );
        }

        $service = WP_Cloudflare_Stream_Service::sanitize_service_name( $service );

        $errors = new WP_Error();

        if( ! current_user_can( 'edit_post', $post_id ) || ! WP_Cloudflare_Stream_Permission::can_live_stream() ){
            $errors->add(
                'no_permission',
                esc_html__( 'You do not have permission to add live output', 'wp-cloudflare-stream' )
            );
        }

        if( $server && ! $streamkey ){
            $errors->add(
                'stream_key_not_found',
                esc_html__( 'Stream Key is required', 'wp-cloudflare-stream' )
            );
        }

        $uid = $this->get_stream_uid( $post_id );

        if( ! $uid ){
            $errors->add(
                'live_uid_not_found',
                esc_html__( 'Live UID was not found', 'wp-cloudflare-stream' )
            );            
        }

        /**
         *
         * Filter errors
         */
        $errors = apply_filters( 'wp_cloudflare_stream/process_live_output/errors', $errors, $args );

        if( $errors->get_error_code() ){
            return $errors;
        }

        $output = get_post_meta( $post_id, "live_output_{$service}", true );

        if( ! $output || ! is_array( $output ) || ! array_key_exists( 'uid', $output ) ){
            $response = $this->cloudflare_api->add_live_output( $uid, array(
                'url'       =>  $server,
                'streamkey' =>  $streamkey
            ) );            

            $add_new = true;
        }
        else{
            $response = $this->cloudflare_api->delete_live_output( $uid, $output['uid'] );         
        }

        if( is_wp_error( $response ) ){
            return $response;
        }

        if( $add_new ){
            unset( $response['streamKey'] );

            $data = array_merge( $response, compact( 'service', 'streamkey' ) );            

            /**
             *
             * Fires after creating live output
             *
             * @param int $post_id attachment_id
             * @param array $data
             * 
             */
            do_action( 'wp_cloudflare_stream/added_live_output', $post_id, $data );

            update_post_meta( $post_id, "live_output_{$service}", $data );
        }else{

            $data = compact( 'service', 'streamkey', 'server' );

            /**
             *
             * Fires after creating live output
             *
             * @param int $post_id
             * @param array $data
             * 
             */
            do_action( 'wp_cloudflare_stream/deleted_live_output', $post_id, $data );   

            update_post_meta( $post_id, "live_output_{$service}", $data );
        }

        return compact( 'data', 'add_new' );
    }

    /**
     *
     * Disable live output
     * 
     * @param  array  $args
     * 
     */
    public function update_live_output( $args = array() ){

        $args = wp_parse_args( $args, array(
            'post_id'   =>  '',
            'service'   =>  '',
            'enabled'    =>  true
        ) );

        extract( $args );

        if( get_post_type( $post_id ) == 'video' ){
            $post_id = get_post_meta( $post_id, 'video_url', true );
        }        

        $errors = new WP_Error();

        if( ! current_user_can( 'edit_post', $post_id ) || ! WP_Cloudflare_Stream_Permission::can_live_stream() ){
            $errors->add(
                'no_permission',
                esc_html__( 'You do not have permission to update live output', 'wp-cloudflare-stream' )
            );
        }

        /**
         *
         * Filter errors
         */
        $errors = apply_filters( 'wp_cloudflare_stream/update_live_output/errors', $errors, $args );

        if( $errors->get_error_code() ){
            wp_send_json_error( $errors );
        }

        $live_uid = $this->get_stream_uid( $post_id );

        $output = get_post_meta( $post_id, "live_output_{$service}", true );

        if( ! $output || ! array_key_exists( 'uid', $output ) ){
            return new WP_Error(
                'output_uid_not_found',
                esc_html__( 'Output UID was not found', 'wp-cloudflare-stream' )
            );
        }else{
            $response = $this->cloudflare_api->update_live_output( $live_uid, $output['uid'], array(
                'enabled'   =>  $enabled
            ) );

            if( is_wp_error( $response ) ){
                return $response;
            }

            $streamkey = $response['streamKey'];

            unset( $response['streamKey'] );

            $data = array_merge( $response, compact( 'service', 'streamkey' ) );

            /**
             *
             * Fires after updating live output
             *
             * @param int $post_id
             * @param array $data
             * 
             */
            do_action( 'wp_cloudflare_stream/updated_live_output', $post_id, $data );

            if( $enabled ){
                /**
                 *
                 * Fires after enabling live output
                 *
                 * @param int $post_id
                 * @param array $data
                 * 
                 */
                do_action( 'wp_cloudflare_stream/enabled_live_output', $post_id, $data );                
            }else{
                /**
                 *
                 * Fires after disabling live output
                 *
                 * @param int $post_id
                 * @param array $data
                 * 
                 */
                do_action( 'wp_cloudflare_stream/disabled_live_output', $post_id, $data );                      
            }

            update_post_meta( $post_id, "live_output_{$service}", $data );

            return $response;

        }
    }

    public function disable_live_output( $args = array() ){
        return $this->update_live_output( array_merge( $args, array(
            'enabled'   =>  false
        ) ) );
    }

    public function enable_live_output( $args = array() ){
        return $this->update_live_output( array_merge( $args, array(
            'enabled'   =>  true
        ) ) );        
    }

    /**
     *
     * Poll outputs status
     * 
     * @param  integer $post_id
     * 
     */
    public function poll_outputs_status( $post_id = 0 ){

        if( get_post_type( $post_id ) == 'video' ){
            $post_id = get_post_meta( $post_id, 'video_url', true );
        }

        if( ! $post_id || ! $this->is_live_stream( $post_id ) || ! WP_Cloudflare_Stream_Permission::can_live_stream() ){
            return new WP_Error(
                'invalid_request',
                esc_html__( 'Invalid Request', 'wp-cloudflare-stream' )
            );
        }

        $uid = $this->get_stream_uid( $post_id );

        return $this->cloudflare_api->get_live_destinations( $uid );
    }

    /**
     *
     * AJAX start live stream
     * 
     */
    public function ajax_start_live_stream(){

        check_ajax_referer( '_wpnonce' );   

        global $streamtube;

        $response = $this->start_live_stream( $_POST );

        if( is_wp_error( $response ) ){
            wp_send_json_error( array(
                'message'   =>  $response->get_error_messages(),
                'errors'    =>  $response
            ) );
        }

        extract( $response );

        $post = get_post( $video_id );

        $form = $streamtube->get()->post->the_edit_post_form( $post );

        wp_send_json_success( compact( 'message', 'form', 'post' ));
    }

    /**
     *
     * Do Open|Close a given live stream
     * 
     * @param  int $post_id video_id
     * @return WP_Error or string new status
     *
     * @since 1.0.0
     * 
     */
    public function process_live_stream( $post_id ){

        $new_status = '';

        $attachment_id = get_post_meta( $post_id, 'video_url', true );

        $stream = $this->get_stream( $attachment_id );

        if( ! $stream['stream'] ){
            return new WP_Error(
                'stream_not_found',
                esc_html__( 'Stream was not found', 'wp-cloudflare-stream' )
            );            
        }

        // Default status
        $statuses = array( 'connected', 'disconnected', 'ready' );

        $new_status = isset( $_POST['live_status'] ) ? sanitize_text_field( $_POST['live_status'] ) : 'disconnected';

        if( in_array( $new_status , $statuses ) ){
            $new_status = 'close';
        }else{
            $new_status = 'disconnected';
        }

        $data = array(
            'uid' =>  $stream['stream']['uid'],
            'preferLowLatency'  =>  wp_validate_boolean( $this->settings['live_ll_hls'] ),
            'deleteRecordingAfterDays'  =>  absint( $this->settings['live_delete_recorded_period'] )
        );

        $data = array_merge( $data, $stream['stream']['recording'] );

        if( $new_status == 'close' ){
            $response = $this->cloudflare_api->close_live_stream( $data );
        }else{
            $response = $this->cloudflare_api->open_live_stream( $data );
        }

        if( ! is_wp_error( $response ) ){
            update_post_meta( $attachment_id,   'live_status', $new_status );
            update_post_meta( $post_id,         'live_status', $new_status );

            $stream = $this->cloudflare_api->get_live_stream( $stream['stream']['uid'] );

            if( ! is_wp_error( $stream ) ){
                update_post_meta( $attachment_id, WP_Cloudflare_Stream_Settings::POST_CLOUDFLARE, $stream );
            }
        }

        /**
         * @since 1.0.0
         */
        do_action( 'wp_cloudflare_stream/process_live_stream', $post_id, $new_status, $response );

        return compact( 'post_id', 'new_status', 'response' );
    }

    /**
     *
     * AJAX do Open|Close a live stream
     * 
     * @since 1.0.0
     */
    public function ajax_process_live_stream(){

        check_ajax_referer( '_wpnonce' );   

        $post_id = (int)$_POST['post_id'];

        $errors = new WP_Error();

        if( ! current_user_can( 'edit_post', $post_id ) || ! WP_Cloudflare_Stream_Permission::can_live_stream() ){
            $errors->add(
                'no_permission',
                esc_html__( 'You do not have permission to update live stream', 'wp-cloudflare-stream' )
            );
        }   

        /**
         *
         * Filter errors
         */
        $errors = apply_filters( 'wp_cloudflare_stream/process_live_stream/errors', $errors, $post_id );        
        
        if( $errors->get_error_code() ){
            wp_send_json_error( $errors );
        }

        $results = $this->process_live_stream( $post_id );

        if( is_wp_error( $results['response'] ) ){
            wp_send_json_error( $results['response'] );
        }

        if( $results['new_status'] == 'close' ){
            wp_send_json_success( array(
                'message'   =>  esc_html__( 'Live Stream has been closed successfully', 'wp-cloudflare-stream' )
            ) );
        }
        else{
            wp_send_json_success( array(
                'message'   =>  esc_html__( 'Live Stream has been opened successfully', 'wp-cloudflare-stream' )
            ) );            
        }
    }

    public function ajax_process_live_output(){

        check_ajax_referer( '_wpnonce' );

        $response = $this->process_live_output( $_POST );

        if( is_wp_error( $response ) ){
            wp_send_json_error( $response );
        }

        if( wp_validate_boolean( $response['add_new'] ) ){
            wp_send_json_success( array_merge( $response, array(
                'message'           =>  esc_html__( 'Live output has been added successfully', 'wp-cloudflare-stream' ),
                'button'            =>  esc_html__( 'Delete', 'wp-cloudflare-stream' ),
                'button2'           =>  esc_html__( 'Disable', 'wp-cloudflare-stream' ),
                'button2_action'    =>  'disable_live_output',
                'label'             =>  esc_html__( 'Added', 'wp-cloudflare-stream' )
            ) ) );            
        }

        wp_send_json_success( array_merge( $response, array(
            'message'           =>  esc_html__( 'Live output has been deleted successfully', 'wp-cloudflare-stream' ),
            'button'            =>  esc_html__( 'Add', 'wp-cloudflare-stream' ),
            'button2'           =>  esc_html__( 'Enable', 'wp-cloudflare-stream' ),
            'button2_action'    =>  'enable_live_output',
            'label'             =>  esc_html__( 'Not added', 'wp-cloudflare-stream' )
        ) ) );

    }

    /**
     *
     * AJAX disable live output
     * 
     */
    public function ajax_disable_live_output(){

        check_ajax_referer( '_wpnonce' );

        if( ! isset( $_POST['data'] ) ){
            exit;
        }

        $default    = array(
            'post_id'   =>  0,
            'service'   =>  ''
        );        

        if( is_string( $_POST['data'] ) ){
            $http_data = json_decode( wp_unslash( $_POST['data'] ), true );
        }else{
            $http_data = wp_parse_args( $_POST['data'], $default );    
        }

        $response = $this->disable_live_output( $http_data );

        if( is_wp_error( $response ) ){
            wp_send_json_error( $response );
        }

        wp_send_json_success( array_merge( $response, array(
            'message'   =>  esc_html__( 'Live output has been disabled successfully', 'wp-cloudflare-stream' ),
            'action'    =>  'enable_live_output',
            'button'    =>  esc_html__( 'Enable', 'wp-cloudflare-stream' )
        ) ) );
    }

    /**
     *
     * AJAX disable live output
     * 
     */
    public function ajax_enable_live_output(){

        check_ajax_referer( '_wpnonce' );

        if( ! isset( $_POST['data'] ) ){
            exit;
        }

        $default    = array(
            'post_id'   =>  0,
            'service'   =>  ''
        );

        if( is_string( $_POST['data'] ) ){
            $http_data = json_decode( wp_unslash( $_POST['data'] ), true );
        }else{
            $http_data = wp_parse_args( $_POST['data'], $default );    
        }

        $response = $this->enable_live_output( $http_data );

        if( is_wp_error( $response ) ){
            wp_send_json_error( $response );
        }

        wp_send_json_success( array_merge( $response, array(
            'message'   =>  esc_html__( 'Live output has been enabled successfully', 'wp-cloudflare-stream' ),
            'action'    =>  'disable_live_output',
            'button'    =>  esc_html__( 'Disable', 'wp-cloudflare-stream' )
        ) ) );
    }    

    public function ajax_poll_outputs_status(){

        check_ajax_referer( '_wpnonce' );

        $http_data = wp_parse_args( $_REQUEST, array(
            'post_id'   =>  0
        ) );

        extract( $http_data );

        $response = $this->poll_outputs_status( $post_id );

        if( is_wp_error( $response ) ){
            wp_send_json_error( $response );
        }

        wp_send_json_success( $response );
    }

    /**
     *
     * bulk update data
     * 
     */
    public function bulk_update_data(){

        $results = array();

        $posts_per_page = apply_filters( 'wp_cloudflare_stream/update_data/per_page', 1 );

        $attachments = get_posts( array(
            'post_type'         =>  'attachment',
            'posts_per_page'    =>  $posts_per_page,
            'meta_query'        =>  array(
                array(
                    'key'       =>  WP_Cloudflare_Stream_Settings::POST_CLOUDFLARE_UID,
                    'compare'   =>  'EXISTS'
                ),
                array(
                    'key'       =>  '_cloudflare_bulk_update',
                    'compare'   =>  'NOT EXISTS'
                ),
                array(
                    'key'       =>  'live_status',
                    'compare'   =>  'NOT EXISTS'
                )
            )
        ) );

        if( ! $attachments ){
            return $attachments;
        }

        foreach ( $attachments as $attachment ) {

            $result = $this->_update_attachment( $attachment->ID );

            if( is_array( $result ) ){

                $results[$attachment->ID] = array_merge( $result, array(
                    'message'           =>  sprintf(
                        esc_html__( '[%s] %s has been successfully updated.', 'wp-cloudflare-stream' ),
                        $attachment->ID,
                        $attachment->post_title
                    ),
                    'attachment_id'     =>  $attachment->ID,
                    'attachment_name'   =>  $attachment->post_title,
                    'parent_url'        =>  $attachment->post_parent ? get_permalink( $attachment->post_parent ) : '#',
                    'response'          =>  $result
                ) );

                /**
                 *
                 * Fires after repairing data
                 *
                 * @param object $attachment
                 * @param array $result
                 * 
                 */
                do_action( 'wp_cloudflare_stream/updated_data', $attachment, $result );

                update_post_meta( $attachment->ID, '_cloudflare_bulk_update', current_time( 'mysql' ) ); 
            }
        }

        return $results;
    }

    /**
     *
     * AJAX bulk update data
     * 
     */
    public function ajax_bulk_update_data(){

        check_ajax_referer( '_wpnonce' );

        if( ! current_user_can( 'administrator' ) ){
            wp_send_json_error( new WP_Error(
                'no_permission',
                esc_html__( 'Sorry, You do not have permission to do this action', 'wp-cloudflare-stream' )
            ) );
        }

        global $wpdb;
        
        $results = $this->bulk_update_data();

        if( $results ){
            wp_send_json_success( $results );
        }

        $wpdb->query(
            $wpdb->prepare(
                "DELETE FROM {$wpdb->prefix}postmeta WHERE `meta_key` = %s",
                '_cloudflare_bulk_update'
            )
        );

        wp_send_json_error( array(
            'message'   =>  esc_html__( 'Done! all videos have been successfully updated.', 'wp-cloudflare-stream' )
        ) );
    }

    /**
     *
     * Revoke tokens
     * 
     */
    public function revoke_tokens(){
        $keys = get_option( 'wp_cloudflare_stream_key' );

        if( is_array( $keys ) && array_key_exists( 'id', $keys ) ){
            $response = $this->cloudflare_api->delete_stream_key( $keys['id'] );

            if( is_wp_error( $response ) ){
                return $response;
            }

            delete_option( 'wp_cloudflare_stream_key' );
        }

        return $this->generate_stream_key();
    }

    /**
     *
     * AJAX revoke tokens
     * 
     */
    public function ajax_revoke_tokens(){
        check_ajax_referer( '_wpnonce' );

        $response = $this->revoke_tokens();

        if( is_wp_error( $response ) ){
            wp_send_json_error( $response );
        }

        wp_send_json_success( array(
            'message'   =>  esc_html__( 'Tokens have been successfully revoked.', 'wp-cloudflare-stream' )
        ) );
    }

    /**
     *
     * Filter attachment URL
     *
     * @param string $url
     * 
     * @param int $attachment_id
     *
     * @since 1.0.0
     * 
     */
    public function filter_wp_get_attachment_url( $url, $attachment_id ){

        if( get_post_type( get_post_parent( $attachment_id ) ) == 'ad_tag' ){
            return $url;
        }

        $maybe_remote_source = get_post_meta( $attachment_id, '_wp_attached_file', true );

        if( wp_http_validate_url( $maybe_remote_source ) ){
            $url = $maybe_remote_source;
        }   

        if( "" != $uid = $this->get_stream_uid( $attachment_id ) ){
            do_action( 'wp_cloudflare_stream/attachment_url_filtered', $uid, $url, $attachment_id );

            return $this->get_playback_url( $uid );
        }

        return $url;
    }

    /**
     *
     * Filter the player setup
     * 
     * @param  array $setup
     * @param  int $source
     * @return array $setup
     */
    public function filter_player_setup( $setup, $source ){

        $playerLoadSource = array();

        $maybe_uid = $this->get_stream_uid( $source );

        if( ! wp_attachment_is( 'video', $source ) || ! $maybe_uid ){
            return $setup;
        }

        $response = $this->is_ready_to_stream( $source );

        if( is_wp_error( $response ) ){
            $playerLoadSource = array(
                'message'   =>  $response->get_error_message()
            );
        }

        if( is_array( $response ) ){
            if( is_string( $response['status'] ) && $response['status'] === 'ready' ){
                return $setup;
            }

            if( wp_validate_boolean( $response['can_live'] ) ){
                $playerLoadSource = array(
                    'message'   =>  esc_html__( 'Waiting for stream', 'wp-cloudflare-stream' )
                );
            }
        }

        if( $playerLoadSource ){

            if( isset( $_REQUEST['uid'] ) ){
                $playerLoadSource['data'] = array(
                    'uid'   =>  wp_unslash( $_REQUEST['uid'] )
                );
            }

            $setup['plugins']['playerLoadSource'] = $playerLoadSource;
            // Reset sources
            $setup['sources'] = array();
        }

        return $setup;
    }

    /**
     *
     * Hooked into "streamtube/core/player/check_video_source" filter
     *
     * 
     * @param  string|WP_Error $source
     * @param  int $post_id
     */
    public function filter_player_load_source( $source, $post_id, $data = array() ){

        $attachment_id  = get_post_meta( $post_id, 'video_url', true );
        $response       = $this->is_ready_to_stream( $attachment_id );

        if( is_wp_error( $response ) ){

            switch ( $response->get_error_code() ) {
                case 'uid_not_found':
                    return $source;
                break;          
                
                default:
                    return $response;
                break;
            }
        }  

        if( is_array( $response ) ){

            $src = '';

            $data = wp_parse_args( $data, array(
                'uid'   =>  ''
            ) );

            if( wp_validate_boolean( $response['can_live'] ) ){

                $recorded = $this->get_recorded_videos( $response['stream']['uid'] );

                if( $recorded ){
                    update_post_meta( $attachment_id, '_recorded_videos', $recorded );
                }

                if( $data['uid'] && $recorded && in_array( $data['uid'], wp_list_pluck( $recorded , 'uid' ) ) ){
                    $src = $this->get_playback_url( $data['uid'] );
                }
                elseif( $response['videoUID'] ){
                    $src = $this->get_playback_url( $response['videoUID'] );
                }else{
                    // Retrieve recorded
                    if( $response['status'] == 'off' ){
                        if( is_array( $recorded ) && count( $recorded ) > 0 ){     

                            if( $this->is_ready_to_play( $recorded[0] ) ){
                                $src = $this->get_playback_url( $recorded[0]['uid'] );
                            }else{
                                return new WP_Error(
                                    'waiting_recorded',
                                    esc_html__( 'Live stream has ended, waiting for the latest recording.', 'wp-cloudflare-stream' )
                                );
                            }
                        }else{
                            return new WP_Error(
                                'no_recorded',
                                esc_html__( 'Live stream has ended, no recordings were found.', 'wp-cloudflare-stream' ),
                                array(
                                    'spinner'   =>  false
                                )
                            );
                        }
                    }
                }
            }else{
                if( $this->is_ready_to_play( $response['stream'] ) ){
                    $src = $this->get_playback_url( $response['stream']['uid'] );
                }else{
                    return new WP_Error(
                        'encoding',
                        esc_html__( 'Video is currently being encoded.', 'wp-cloudflare-stream' )
                    );
                }
            }

            if( $src ){ 
                return array(
                    'type'  =>  'application/x-mpegURL',
                    'src'   =>  $src
                ); 
            }
        }

        return $source;
    }

    /**
     *
     * Filter player's output
     * 
     */
    public function filter_player_output( $player, $setup, $source ){
        if( ! $this->is_enabled() || ! $this->settings['default_player'] ){
            return $player;
        }

        $maybe_uid = $this->get_stream_uid( $source );

        if( ! $maybe_uid ){
            return $player;
        }

        return $this->get_iframe( $maybe_uid );
    }

    /**
     *
     * Filter the recorded thumbnail image if requireSignedURLs is enabled
     * 
     */
    public function filter_recorded_signed_url_thumbnail( $thumbnail_url, $recorded ){
      
        if( wp_validate_boolean( $recorded['requireSignedURLs'] ) ){
            $thumbnail_url = $this->get_thumbnail_url( array(
                'uid'   =>  $recorded['uid']
            ) );
        }

        return $thumbnail_url;
    }

    /**
     *
     * Add "Live Stream" to header dropdown
     * 
     * @param array $types
     *
     * @since 1.0.0
     * 
     */
    public function add_header_upload_type_selection( $types ){

        if( $this->settings['live_stream_enable'] ){
            $types['live_stream'] = array(
                'text'  =>  esc_html__( 'Go Live', 'wp-cloudflare-stream' ),
                'icon'  =>  'icon-live',
                'cap'   =>  array( 'WP_Cloudflare_Stream_Permission', 'can_live_stream' )
            );
        }
        return $types;
    }

    /**
     *
     * Load modal live stream
     * 
     * @since 1.0.0
     */
    public function load_modal_live_stream(){
        if( $this->settings['live_stream_enable'] ){
            load_template( WP_CLOUDFLARE_STREAM_PATH_PUBLIC . '/partials/add-live-stream.php' );
        }
    }

    /**
     *
     * Show Live Stream nav menu
     * 
     * @param  array $items
     *
     * @since 1.0.0
     */
    public function add_post_nav_item( $items ){

        $post_id = streamtube_core()->get()->post->get_edit_post_id();

        if( ! $post_id || get_post_type( $post_id ) != 'video' ){
            return $items;
        }

        if( $this->is_live_stream( $post_id ) && WP_Cloudflare_Stream_Permission::can_live_stream() ){
            $items['livestream']   = array(
                'title'         =>  esc_html__( 'Live', 'wp-cloudflare-stream' ),
                'icon'          =>  'icon-live',
                'template'      =>  WP_CLOUDFLARE_STREAM_PATH_PUBLIC . '/partials/live-stream-settings.php',
                'priority'      =>  6
            );

            $items['simulcast']   = array(
                'title'         =>  esc_html__( 'Simulcast', 'wp-cloudflare-stream' ),
                'icon'          =>  'icon-wifi',
                'template'      =>  WP_CLOUDFLARE_STREAM_PATH_PUBLIC . '/partials/live-stream-outputs.php',
                'priority'      =>  7
            );  
        }

        return $items;
    }

    /**
     *
     * The live badge
     *
     * Output the live badge
     * 
     * @since 1.0.0
     */
    public function the_live_badge( $args = array() ){

        $args = wp_parse_args( $args, array(
            'text'      =>  esc_html__( 'Live', 'wp-cloudflare-stream' ),
            'status'    =>  ''
        ) );

        /**
         * @since 1.0.0
         */
        $args = apply_filters( 'wp_cloudflare_stream_live_badge', $args );

        load_template( WP_CLOUDFLARE_STREAM_PATH_PUBLIC . '/partials/live-badge.php', false, $args );
    }

    /**
     *
     * Add the Live badge
     *
     * @since 1.0.0
     * 
     */
    public function add_live_badge(){

        $live_status = get_post_meta( get_the_ID(), 'live_status', true );

        if( is_string( $live_status ) && in_array( $live_status, array( 'connected', 'disconnected' ) ) ){
            $this->the_live_badge( array(
                'status'    =>  $live_status
            ) );
        }
    }

    public function load_the_live_settings(){

        global $post;

        if( $this->is_live_stream( $post->ID ) && $post->post_author == get_current_user_id() && is_user_logged_in() ){
            load_template( WP_CLOUDFLARE_STREAM_PATH_PUBLIC . '/partials/live-stream-settings.php' );
        }
    }

    /**
     *
     * Filter Allow Formats
     * 
     * @param  array  $allow_formats
     *
     * @return array
     * 
     */
    public function filter_allow_formats( $allow_formats = array() ){

        $default = array( 'mp4', 'm4v', 'webm', 'ogv', 'flv' );

        $settings = WP_Cloudflare_Stream_Settings::get_settings();

        if( ! array_key_exists( 'allow_formats' , $settings) ){
            $_allow_formats = $default;
        }else{
            $_allow_formats = array_map( 'trim', explode(',', $settings['allow_formats'] ) );
        }

        if( is_array( $_allow_formats ) ){
            $allow_formats = array_merge( $allow_formats, $_allow_formats );    
        }

        return array_values( array_unique( $allow_formats ) );
    }

    /**
     *
     * Filter the Download URL
     * 
     * @param  string $url
     * @param  int $post_id
     * @return string
     * 
     */
    public function filter_download_file_url( $url, $post_id ){

        if( ! $this->is_enabled() ){
            return $url;
        }

        if( $this->settings['signed_url'] ){
            return false;
        }        

        $attachment_id = get_post_meta( $post_id, 'video_url', true );

        if( wp_attachment_is( 'video', $attachment_id ) && get_option( $attachment_id, 'cf_download_url', true ) ){
            $maybe_url = $this->get_downloadable_url( $attachment_id );

            if( wp_http_validate_url( $maybe_url ) ){
                $url = $maybe_url;
            }
        }

        return $url;
    }

    /**
     *
     * Install Webhook
     * 
     * @return cloudflare_api->subscribe_webhook()
     *
     * @since 1.0.0
     * 
     */
    public function install_webhook(){
        $response = $this->cloudflare_api->subscribe_webhook( $this->get_webhook_url() );

        if( is_wp_error( $response ) ){
            WP_Cloudflare_Stream_Settings::delete_setting( 'webhook' );
        }else{
            WP_Cloudflare_Stream_Settings::update_setting( 'webhook', $response );
        }

        return $response;
    }

    /**
     *
     * AJAX Install Webhook
     * 
     * @return install_webhook()
     *
     * @since 1.0.0
     * 
     */
    public function ajax_install_webhook(){
        $response = $this->install_webhook();

        if( is_wp_error( $response ) ){
            wp_send_json_error( $response );
        }

        wp_send_json_success( array_merge( $response, array(
            'message'   =>  esc_html__( 'Installed', 'wp-cloudflare-stream' )
        ) ) );
    }

    /**
     *
     * Subscribe Webhook
     * 
     * @return cloudflare_api->subscribe_webhook()
     *
     * @since 1.0.0
     * 
     */
    public function add_watermark(){
        return $this->cloudflare_api->upload_watermark( array(
            'url'       =>  $this->settings['watermark_url'],
            'name'      =>  $this->settings['watermark_name'],
            'opacity'   =>  (float)$this->settings['watermark_opacity'],
            'padding'   =>  (float)$this->settings['watermark_padding'],
            'scale'     =>  (float)$this->settings['watermark_scale'],
            'position'  =>  $this->settings['watermark_position']
        ) );
    }

    /**
     *
     * Generate thumbnail image
     * 
     * @param  int $post_id
     * @param  string $thumbnail_url
     * @return WP_Error|int
     *
     * @since 2.1
     * 
     */
    public function generate_thumbnail_image( $attachment_id, $thumbnail_url = '' ){

        if( has_post_thumbnail( $attachment_id ) ){
            return new WP_Error(
                'thumbnail_exists',
                esc_html__( 'Thumbnail Image is already existed', 'wp-cloudflare-stream' )
            );
        }

        $thumbnail_id = media_sideload_image( $thumbnail_url, $attachment_id, null, 'id' );

        if( ! is_wp_error(  $thumbnail_id ) ){

            $attachment = get_post( $attachment_id );

            set_post_thumbnail( $attachment_id, $thumbnail_id );

            wp_update_post( array(
                'ID'            =>  $thumbnail_id,
                'post_parent'   =>  $attachment_id,
                'post_author'   =>  $attachment->post_author
            ) );

            if( $attachment->post_parent && ! has_post_thumbnail( $attachment->post_parent ) ){
                set_post_thumbnail( $attachment->post_parent, $thumbnail_id );
            }
        }

        return $thumbnail_id;
    }

    /**
     *
     * Generate gif thumbnail image
     * 
     * @param  int $post_id
     * @param  string $thumbnail_url
     * @return WP_Error|int
     *
     * @since 2.1
     * 
     */
    public function generate_gif_thumbnail_image( $attachment_id, $thumbnail_url = '' ){

        if( get_post_meta( $attachment_id, '_thumbnail_url_2', true ) ){
            return;
        }

        $thumbnail_id = media_sideload_image( $thumbnail_url, $attachment_id, null, 'id' );

        if( ! is_wp_error(  $thumbnail_id ) ){

            $thumbnail_url = wp_get_attachment_image_url( $thumbnail_id, 'full' );

            update_post_meta( $attachment_id, '_thumbnail_url_2', $thumbnail_url );

            $attachment = get_post( $attachment_id );

            if( $attachment->post_parent ){
                update_post_meta( $attachment->post_parent, '_thumbnail_url_2', $thumbnail_url );
            }

            wp_update_post( array(
                'ID'            =>  $thumbnail_id,
                'post_parent'   =>  $attachment_id,
                'post_author'   =>  $attachment->post_author
            ) );

        }

        return $thumbnail_id;
    }

    /**
     *
     * Update thumbnail images
     * 
     * @param  WP_Post $post
     * @param  array $data
     * @since 1.0.0
     */
    public function auto_import_thumbnail_images( $attachment_id, $data = array(), $stream = array() ){

        if( ! is_array( $stream ) || ! array_key_exists( 'readyToStream' , $stream ) ){
            return;
        }

        if( $this->settings['auto_thumbnail'] ){
            $this->generate_thumbnail_image( 
                $attachment_id, 
                $this->get_thumbnail_url( array(
                    'uid'   =>  $data['uid'],
                    'ext'   =>  'jpg'
                ) )
            );
        }

        if( $this->settings['auto_gif_thumbnail'] ){
            $this->generate_gif_thumbnail_image( 
                $attachment_id, 
                $this->get_thumbnail_url( array(
                    'uid'   =>  $data['uid'],
                    'ext'   =>  'gif'
                ) )
            );
        }            
    }

    public function _auto_publish_parent_post( $attachment, $data = array(), $stream = array() ){
     
        wp_update_post( array(
            'ID'            =>  $attachment->post_parent,
            'post_status'   =>  'publish'
        ) );

        if( $this->settings['author_notify_publish'] ){
            streamtube_core_notify_author_after_video_publish( $attachment->post_parent, array(
                'subject'   =>  trim( $this->settings['author_notify_publish_subject'] ),
                'content'   =>  trim( $this->settings['author_notify_publish_content'] )
            ) );                
        }

        /**
         *
         * @since 1.0.0
         * 
         */
        do_action( 'wp_cloudflare_stream_post_auto_publish', $attachment, $data );
    }    

    /**
     *
     * Auto update parent post
     * 
     * @param  attachment_id
     * @param  array $data
     * @return wp_update_post()
     *
     * @since 1.0.0
     * 
     */
    public function auto_publish_parent_post( $attachment_id, $data = array(), $stream = array() ){ 

        if( ! is_array( $stream ) || ! array_key_exists( 'readyToStream' , $stream ) ){
            return;
        }

        $attachment = get_post( $attachment_id );

        if( ! $this->settings['auto_publish'] || ! $attachment->post_parent ){
            return;
        }

        return $this->_auto_publish_parent_post( $attachment, $data = array(), $stream );
    }

    /**
     *
     * Enable mp4 download
     * 
     * @param  attachment_id
     * @param  array $data
     *
     * @since 1.0.0
     * 
     */
    public function auto_enable_mp4_download( $attachment_id, $data = array(), $stream = array() ){ 

        if( ! is_array( $stream ) || ! array_key_exists( 'readyToStream' , $stream ) ){
            return;
        }

        if( wp_validate_boolean( $this->settings['enable_mp4_download'] ) ){
            return $this->enable_mp4_download( $attachment_id );
        }else{
            return $this->disable_mp4_download( $attachment_id );
        }    
    }    

    /**
     *
     * Auto delete original file
     * 
     * @param  WP_Post $post
     *
     * @since 1.0.0
     * 
     */
    public function _auto_delete_original_file( $attachment_id, $data = array(), $stream = array() ){

        $attachment = get_post( $attachment_id );

        if( get_post_type( $attachment->post_parent ) != 'ad_tag' ){
            $uploadpath = wp_get_upload_dir();
            return wp_delete_file_from_directory( get_attached_file( $attachment->ID ), $uploadpath['basedir'] );            
        }
    }    

    /**
     *
     * Auto delete original file
     * 
     * @param  attachment_id
     * @param  array $data
     *
     * @since 1.0.0
     * 
     */
    public function auto_delete_original_file( $attachment_id, $data = array(), $stream = array() ){

        if( ! is_array( $stream ) || ! array_key_exists( 'readyToStream' , $stream ) ){
            return;
        }       

        if( ! $this->settings['delete_original_file'] ){
            return;
        }

        return $this->_auto_delete_original_file( $attachment_id, $data, $stream = array() );
    }

    /**
     *
     * Send notify to author after video encoding failed
     * 
     * @param  int $attachment_id
     * @param  array  $data
     * @return streamtube_core_notify_author_after_video_encoding_failed();
     *
     * @since 1.0.0
     * 
     */
    public function _notify_author_after_encoding_failed( $attachment_id, $data = array(), $stream = array() ){
        streamtube_core_notify_author_after_video_encoding_failed( $post, array(
            'subject'           =>  trim( $this->settings['author_notify_fail_subject'] ),
            'content'           =>  trim( $this->settings['author_notify_fail_content'] ),
            'error_code'        =>  $data['status']['errReasonCode'],
            'error_message'     =>  $data['status']['errReasonText']
        ) );
    }

    /**
     *
     * Send notify to author after video encoding failed
     * 
     * @param  int $attachment_id
     * @param  array  $data
     * @return $this->_notify_author_after_encoding_failed()
     *
     * @since 1.0.0
     * 
     */
    public function notify_author_after_encoding_failed( $attachment_id, $data = array(), $stream = array() ){

        if( ! $this->settings['author_notify_fail'] ){
            return;
        }

        if( $data['status']['state'] == 'error' ){
            return $this->_notify_author_after_encoding_failed( $attachment_id, $data );
        }
    }

    /**
     *
     * Sync uploads to WP
     * 
     * @param  array $data
     * 
     */
    public function _sync_uploads_to_wp( $data ){

        $data = wp_parse_args( $data, array(
            'live_status'   =>  false
        ) );

        $post_args = array();

        $stream = $this->cloudflare_api->get_video( $data['uid'] );

        if( is_wp_error( $stream ) ){
            return;
        }

        $post_args['post_title'] = $stream['meta']['name'];

        $attachment_id = $this->create_attachment( $post_args, $data );

        if( is_wp_error( $attachment_id ) ){
            return $attachment_id;
        }

        $post_args = apply_filters( 'wp_cloudflare_stream_post_upload_synce_post_args', array(
            'post_title'    =>  preg_replace( '/\.[^.]+$/', '', $post_args['post_title'] ),
            'post_type'     =>  'video',
            'post_status'   =>  $this->settings['syn_post_status'],
            'post_author'   =>  absint( $this->settings['syn_post_author'] ),
            'meta_input'    =>  array(
                'video_url'       =>  $attachment_id
            )
        ) );

        $video_id = wp_insert_post( $post_args, true );

        if( is_wp_error( $video_id ) ){
            return $video_id;
        }

        wp_update_post( array(
            'ID'            =>  $attachment_id,
            'post_parent'   =>  $video_id
        ) );

        /**
         *
         * Fires once webhook updated
         *
         * @param object $attachment_id
         * @param array $data
         *
         * @since 2.1
         * 
         */
        do_action( 'wp_cloudflare_stream_post_webhook_updated', $attachment_id, $data, $stream );

        /**
         *
         * Fires after upload synced
         *
         * @param int $video_id (video post ID)
         * @param int $attachment_id
         * @param array $stream
         * 
         */
        do_action( 'wp_cloudflare_stream_post_upload_synced', $video_id, $attachment_id, $stream );        

        return compact( 'video_id', 'attachment_id', 'stream' );
    }

    /**
     *
     * Auto sync uploads to WP
     * 
     * @param  array $data
     * 
     */
    public function sync_uploads_to_wp( $data ){
        if( $this->settings['auto_sync'] ){
            return $this->_sync_uploads_to_wp( $data );
        }
    }

    /**
     *
     * Parse data sent by Cloudflare Webhook
     * 
     * @since 1.0.0
     */
    public function _webhook_callback( $data ){
        $data = json_decode( $data, true );

        if( ! is_array( $data ) ){
            return;
        }

        $attachment_id = $this->get_attachment_id_from_uid( $data['uid'] );

        if( ! $attachment_id ){

            /**
             *
             * Fires once a webhook is received without any existing attachment
             *
             * @param array $data webhook response
             * @param object $instance
             * 
             */
            return do_action( 'wp_cloudflare_stream_post_webhook_no_attachment', $data, array( &$this ) );
        }

        $stream = $this->cloudflare_api->get_video( $data['uid'] );

        if( ! is_wp_error( $stream ) && is_array( $stream ) ){
            update_post_meta( $attachment_id, WP_Cloudflare_Stream_Settings::POST_CLOUDFLARE, $stream );
        }

        /**
         *
         * Fires once webhook updated
         *
         * @param object $attachment_id
         * @param array $data
         *
         * @since 2.1
         * 
         */
        do_action( 'wp_cloudflare_stream_post_webhook_updated', $attachment_id, $data, $stream );
    }

    /**
     *
     * Live callback webhook
     * 
     * @param  array $data
     * @since 1.0.0
     */
    public function _webhook_live_callback( $data ){

        $data = json_decode( $data, true );

        if( ! is_array( $data ) ){
            return;
        }

        $attachment_id = $this->get_attachment_id_from_uid( $data['data']['input_id'] );

        if( ! $attachment_id ){
            return;
        }

        $status = explode( '.' , $data['data']['event_type'] );

        update_post_meta( $attachment_id, 'live_status', $status[1] );

        if( "" != $post_parent = get_post( $attachment_id )->post_parent ){
            update_post_meta( $post_parent, 'live_status', $status[1] );
        }

        $stream = $this->cloudflare_api->get_live_stream( $data['data']['input_id'] );

        if( ! is_wp_error( $stream ) ){
            update_post_meta( $attachment_id, WP_Cloudflare_Stream_Settings::POST_CLOUDFLARE, $stream );
        }

        /**
         *
         * Fires once webhook updated
         *
         * @param object $post ($attachment_id)
         * @param array $data
         *
         * @since 2.1
         * 
         */
        do_action( 'wp_cloudflare_stream_post_webhook_live_updated', $attachment_id, $data, $stream );
    }

    /**
     *
     * Catch data sent by Cloudflare Webhook
     * 
     * @since 1.0.0
     */
    public function webhook_callback(){
        $http = wp_parse_args( $_GET, array(
            'webhook'   =>  '',
            'key'       =>  '',
            'live'      =>  'off'
        ) );

        if( $http['webhook'] != 'cloudflare' ){
            return;
        }

        if( $http['key'] != $this->settings['webhook_key'] ){
            return;
        }

        if( ! in_array( $http['live'] , array( 'on', 'off' ) ) ){
            return;
        }

        $data = file_get_contents("php://input");

        if( $http['live'] == 'off' ){
            $this->_webhook_callback( $data );
        }
        else{
            $this->_webhook_live_callback( $data );
        }

        wp_send_json_success( array(
            'message'   =>  'Webhook'
        ) );
    }

    /**
     * 
     *
     * Rest API generate thumbnail image
     * 
     * @param  int $thumbnail_id
     * @param  int $attachment_id
     * @return int
     */
    public function rest_generate_thumbnail_image( $thumbnail_id = 0, $attachment_id = 0 ){    

        if( ! $this->is_enabled() ){
            return $thumbnail_id;
        }

        if( ! $thumbnail_id || is_wp_error( $thumbnail_id ) ){

            $uid = $this->get_stream_uid( $attachment_id );

            if( ! $uid ){
                $thumbnail_id = new WP_Error(
                    'cloudflare_uid_not_found',
                    esc_html__( 'Cloudflare Stream UID was not found', 'wp-cloudflare-stream' )
                );
            }
            else{

                $_cloudflare_image_url = $this->get_thumbnail_url( compact( 'uid' ) );

                $thumbnail_id = $this->generate_thumbnail_image( $attachment_id, $_cloudflare_image_url );
            }
        }

        return $thumbnail_id;
    }
}