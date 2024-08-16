<?php
/**
 * Define Cloudflare Stream API 
 *
 * @since      1.0.0
 * @package    WP_Cloudflare_Stream
 * @subpackage WP_Cloudflare_Stream/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */

if( ! defined('ABSPATH' ) ){
    exit;
}

class WP_Cloudflare_Stream_API {

    /**
     *
     * Holds the account ID
     * 
     * @var string
     *
     * @since 1.0.0
     * 
     */
    protected $account_id                       =   '';

    /**
     *
     * Holds the API Token
     * 
     * @var string
     *
     * @since 1.0.0
     * 
     */
    protected $api_token                        =   '';

    /**
     *
     * Holds the Ingest Domain
     * 
     * @var string
     *
     * @since 1.0.0
     * 
     */
    protected $ingest_domain                    =   '';

    /**
     *
     * Holds the base URL
     *
     * @since 1.0.0
     * 
     */
    protected $api_url                          =   'https://api.cloudflare.com/client/v4/accounts/$ACCOUNT/stream';

    /**
     *
     * Holds the cloudflarestream URL
     *
     * @since 1.0.0
     * 
     */
    protected $cloudflarestream_url             =   'https://cloudflarestream.com';

    /**
     *
     * Holds the subdomain
     * 
     * @var string
     */
    protected $subdomain                        =   '';

    /**
     *
     * Class contructor
     * 
     * @since 1.0.0
     */
    public function __construct( $args = array() ){

        $args = wp_parse_args( $args, array(
            'account_id'    =>  '',
            'api_token'     =>  '',
            'ingest_domain' =>  '',
            'subdomain'     =>  ''
        ) );

        $this->account_id       =   $args['account_id'];

        $this->api_token        =   $args['api_token'];

        $this->api_url          =   str_replace( '$ACCOUNT' , $this->account_id, $this->api_url );

        $this->subdomain        =   $this->filter_subdomain( $args['subdomain'] );

    }

    /**
     * Encodes data using base64url encoding.
     *
     * @param string $data The data to be encoded.
     *
     * @return string The base64url encoded data.
     */
    protected function base64Url( $data ){
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }    

    /**
     *
     * Call API
     *
     * @return WP_Error|array
     *
     * @since 1.0.0
     * 
     */
    protected function call_api( $url, $args = array() ){

        $args = array_merge( $args, array(
            'headers'   =>  array(
                'Authorization'     =>  'Bearer ' . $this->api_token,
                'Content-Type'      =>  'application/json'
            )
        ) );

        $response = wp_remote_request( $url, $args );

        if( is_wp_error( $response ) ){
            return $response;
        }

        $body = json_decode( wp_remote_retrieve_body( $response ), true );

        if( is_array( $body ) && array_key_exists( 'success', $body ) && ! $body['success'] ){
            return new WP_Error(
                $body['errors'][0]['code'],
                $body['errors'][0]['message']
            );            
        }

        return is_array( $body ) && array_key_exists( 'result' , $body ) ? $body['result'] : $body;
    }

    /**
     *
     * Filter subdomain
     * 
     */
    private function filter_subdomain( $subdomain = '' ){
        $subdomain = str_replace( 'http://', '', $subdomain );
        $subdomain = str_replace( 'https://', '', $subdomain );

        return $subdomain;
    }

    /**
     *
     * Enable download
     * 
     * @param  $uid 
     */
    public function enable_download( $uid ){
        return $this->call_api( $this->api_url . "/{$uid}/downloads", array(
            'method'    =>  'POST'
        ) );
    }

    public function disable_download( $uid ){
        return $this->call_api( $this->api_url . "/{$uid}/downloads", array(
            'method'    =>  'DELETE'
        ) );
    }    

    /**
     *
     * Fetch Video
     * 
     * @param  string $url
     * @param  string $name
     * @return call_api()
     *
     * @since 1.0.0
     * 
     */
    public function fetch_video( $args = array() ){

        $args = wp_parse_args( $args, array(
            'url'                   =>  '',
            'name'                  =>  '',
            'allowedOrigins'        =>  array(),
            'requireSignedURLs'     =>  false,
            'creator'               =>  '',
            'watermark'             =>  array()
        ) );

        extract( $args );

        $meta = compact( 'name' );

        $body = compact( 'url', 'meta', 'allowedOrigins', 'requireSignedURLs' );

        if( $creator ){
            $body['creator'] = $creator;
        }

        if( $watermark ){
            $body['watermark'] = $watermark;
        }        

        return $this->call_api( $this->api_url . "/copy", array(
            'method'    =>  'POST',
            'body'      =>  json_encode( $body )
        ) );
    }

    /**
     *
     * Delete Video
     * 
     * @param  string $uid
     * @return call_api()
     *
     * @since 1.0.0
     * 
     */
    public function delete_video( $uid ){
        return $this->call_api( trailingslashit( $this->api_url ) . $uid, array(
            'method'    =>  'DELETE'
        ) );        
    }

    /**
     *
     * Update Video
     * 
     * @param  string $uid
     * @return call_api()
     *
     * @since 1.0.0
     * 
     */
    public function update_video( $uid, $data = array() ){

        $data = wp_parse_args( $data, array(
            'name'  =>  '',
            'meta'  =>  array()
        ) );

        if( $data['name'] ){
            $data['meta'] = array(
                'name'  =>  $data['name']
            );

            unset( $data['name'] );
        }

        return $this->call_api( trailingslashit( $this->api_url ) . $uid, array(
            'method'    =>  'POST',
            'body'      =>  json_encode( $data )
        ) );
    }

    /**
     *
     * Get Video
     * 
     * @param  string $uid
     * @return call_api()
     *
     * @since 1.0.0
     * 
     */
    public function get_video( $uid ){
        return $this->call_api( trailingslashit( $this->api_url ) . $uid, array(
            'method'    =>  'GET'
        ) );
    }

    /**
     *
     * Download video
     * 
     * @param  string $uid
     * @return WP_Error|array
     *
     * @since 1.0.1
     * 
     */
    public function get_download_video_url( $uid ){
        $response = $this->call_api( trailingslashit( $this->api_url ) . $uid . '/downloads', array(
            'method'    =>  'GET'
        ) );       

        if( is_wp_error( $response ) ){
            return $response;
        }

        if( is_array( $response ) && array_key_exists( 'default', $response ) ){
            return $response['default']['url'];
        }

        return false;
    }

    /**
     *
     * Start live stream
     * 
     * @return WP_Error|array
     *
     * @since 1.0.0
     * 
     */
    public function create_live_stream( $args = array() ){
        $args = wp_parse_args( $args, array(
            'uid'                       =>  '',
            'name'                      =>  '',
            'description'               =>  '',
            'creator'                   =>  0,
            'mode'                      =>  'automatic',
            'preferLowLatency'          =>  null,
            'timeoutSeconds'            =>  null,
            'deleteRecordingAfterDays'  =>  null,
            'allowedOrigins'            =>  null,
            'requireSignedURLs'         =>  null
        ) );

        extract( $args );

        $body = $meta = $recording = array();

        if( ! empty( $name ) ){
            $meta['name'] = $name;
        }

        if( ! empty( $description ) ){
            $meta['description'] = $description;
        }

        if( $creator ){
            $body['creator'] = $creator;
        }        

        if( $preferLowLatency === true ){
            $body['preferLowLatency'] = true;
        }

        if( is_int( $deleteRecordingAfterDays ) ){
            $body['deleteRecordingAfterDays'] = absint( $deleteRecordingAfterDays );
        }

        if( ! empty( $mode ) ){
            $recording['mode'] = $mode;
        }

        if( is_int( $timeoutSeconds ) ){
            $recording['timeoutSeconds'] = $timeoutSeconds;
        }        

        if( ! is_null( $requireSignedURLs ) ){
            $recording['requireSignedURLs'] = $requireSignedURLs;    
        }

        if( $allowedOrigins && is_array( $allowedOrigins ) ){
            $recording['allowedOrigins'] = $allowedOrigins;
        }

        if( $meta ){
            $body['meta'] = $meta;
        }

        if( $recording ){
            $body['recording'] = $recording;
        }

        $api_url = $this->api_url . "/live_inputs";

        if( $uid ){
            $api_url = trailingslashit( $api_url ) . $uid;
        }

        $response = $this->call_api( $api_url, array(
            'method'    =>  $uid ? 'PUT' : 'POST',
            'body'      =>  json_encode( $body )
        ) );

        if( is_wp_error( $response ) ){
            return $response;
        }

        return $response;
    }

    /**
     *
     * Update live stream
     * 
     * @param  array  $args
     * 
     */
    public function update_live_stream( $args = array() ){
        return $this->create_live_stream( $args );
    }

    /**
     *
     * Close live stream
     * 
     * @param  string $uid
     * @return WP_Error|array
     *
     * @since 1.0.0
     * 
     */
    public function close_live_stream( $data = array() ){

        $data = array_merge( $data, array(
            'mode'  =>  'off'
        ) );

        return $this->create_live_stream( $data );
    }

    /**
     *
     * Open live stream
     * 
     * @param  string $uid
     * @return WP_Error|array
     *
     * @since 1.0.0
     * 
     */
    public function open_live_stream( $data = array() ){

        $data = array_merge( $data, array(
            'mode'  =>  'automatic'
        ) );

        return $this->create_live_stream( $data );
    }

    /**
     *
     * Delete all playback of given live stream input
     * 
     * @param  string $uid
     * 
     */
    public function delete_live_stream_playbacks( $uid ){

        $recorded_videos = $this->get_recorded_videos( $uid );

        if( is_wp_error( $recorded_videos ) ){
            return $recorded_videos;
        }

        $recorded_video_uids = wp_list_pluck( $recorded_videos, 'uid' );

        if( is_array( $recorded_video_uids ) ){
            for ( $i=0; $i < count( $recorded_video_uids ); $i++) { 
                $this->delete_video( $recorded_video_uids[$i] );
            }
        }
    }

    /**
     *
     * Delete live stream
     * 
     * @param  string $uid
     * @return WP_Error|array
     *
     * @since 1.0.0
     * 
     */
    public function delete_live_stream( $uid ){
        return $this->call_api( $this->api_url . "/live_inputs/" . $uid, array(
            'method'    =>  'DELETE'
        ) );   
    }

    /**
     *
     * Get live stream
     * 
     * @param  string $uid
     * @return WP_Error|array
     *
     * @since 1.0.0
     * 
     */
    public function get_live_stream( $uid ){
        return $this->call_api( $this->api_url . "/live_inputs/" . $uid, array(
            'method'    =>  'GET'
        ) );
    }   

    /**
     *
     * Get recorded videos of given live stream ID
     *
     * @see https://developers.cloudflare.com/stream/stream-live/watch-live-stream/
     * 
     * @param  string $uid
     * @return WP_Error|array
     *
     * @since 1.0.0
     * 
     */
    public function get_recorded_videos( $uid  ){
        return $this->call_api( $this->api_url . "/live_inputs/" . $uid . '/videos', array(
            'method'    =>  'GET'
        ) );
    }

    /**
     *
     * Request live stream status
     *
     * @example https://videodelivery.net/34036a0695ab5237ce757ac53fd158a2/lifecycle
     * 
     * 
     * @param  string $uid
     * @return WP_Error|array
     *
     * @since 1.0.0
     * 
     */
    public function poll_live_status( $uid, $sign_token = array() ){

        $sign_token = wp_parse_args( $sign_token, array(
            'pem'   =>  '',
            'id'    =>  '',
            'exp'   =>  3600*12
        ) );

        $url = sprintf( 'https://videodelivery.net/%s/lifecycle', $uid );

        if( $this->subdomain ){
            $url = sprintf( 'https://%s/%s/lifecycle', $this->subdomain, $uid );
        }

        if( $sign_token['pem'] ){
            $url = str_replace( $uid, $this->get_sign_token(
                $uid,
                $sign_token
            ), $url );
        }

        $response = wp_remote_get( $url );

        if( is_wp_error( $response ) ){
            return $response;
        }

        $response = json_decode( wp_remote_retrieve_body( $response ), true );

        return $response;
    }    

    /**
     *
     * Add live output
     * 
     * @param string $uid  live UID
     * @param array  $data
     */
    public function add_live_output( $uid, $data = array() ){

        $data = wp_parse_args( $data, array(
            'url'       =>  '',
            'streamkey' =>  '',
            'enabled'   =>  true
        ) );

        return $this->call_api( trailingslashit( $this->api_url ) . 'live_inputs/' . $uid . '/outputs', array(
            'method'    =>  'POST',
            'body'      =>  json_encode( $data )
        ) );
    }

    public function update_live_output( $uid, $output_uid = '', $data = array() ){
        return $this->call_api( trailingslashit( $this->api_url ) . 'live_inputs/' . $uid . '/outputs/' . $output_uid, array(
            'method'    =>  'PUT',
            'body'      =>  json_encode( $data )
        ) );
    }

    /**
     *
     * Delete output
     * 
     * @param  string $output_uid
     * 
     */
    public function delete_live_output( $uid, $output_uid = '' ){
        return $this->call_api( trailingslashit( $this->api_url ) . 'live_inputs/' . $uid . '/outputs/' . $output_uid, array(
            'method'    =>  'DELETE'
        ) );
    }

    /**
     *
     * Live destinations
     * 
     * @param  string $uid live uid
     * @return WP_Error/array
     * 
     */
    public function get_live_destinations( $uid ){
        return $this->call_api( $this->api_url . "/live_inputs/" . $uid . '/destinations', array(
            'method'    =>  'GET'
        ) );
    }

    /**
     *
     * Get HLS playback URL
     * 
     * @param  string $uid
     *
     * @since 1.0.0
     * 
     */
    public function get_playback_url( $uid, $sign_token = array(), $hls = true ){

        $sign_token = wp_parse_args( $sign_token, array(
            'pem'   =>  '',
            'id'    =>  '',
            'exp'   =>  3600*12
        ) );

        $url = sprintf(
            '%s/%s/manifest/video.%s',
            untrailingslashit( $this->cloudflarestream_url ),
            $uid,
            $hls ? 'm3u8' : 'mpd'
        );

        if( $this->subdomain ){
            $url = sprintf(
                'https://%s/%s/manifest/video.%s',
                $this->subdomain,
                $uid,
                $hls ? 'm3u8' : 'mpd'
            );
        }

        if( $sign_token['pem'] ){
            $url = str_replace( $uid, $this->get_sign_token(
                $uid,
                $sign_token
            ), $url );
        }

        return $url;
    }

    /**
     *
     * Get preview url
     * 
     * @param  string $uid
     * @param  array  $sign_token
     * @return string
     * 
     */
    public function get_preview_url( $uid, $sign_token = array() ){

        $sign_token = wp_parse_args( $sign_token, array(
            'pem'   =>  '',
            'id'    =>  '',
            'exp'   =>  3600*12
        ) );
                
        $url = sprintf(
            'https://%s/%s/watch',
            $this->subdomain,
            $uid
        );

        if( $sign_token['pem'] ){
            $url = str_replace( $uid, $this->get_sign_token(
                $uid,
                $sign_token
            ), $url );
        }

        return $url;        
    }

    /**
     *
     * Get thumbnail image URL
     * 
     * @param  array  $args
     * @return string
     *
     * @since 1.0.0
     * 
     */
    public function get_thumbnail_url( $args = array(), $sign_token = array() ){

        $sign_token = wp_parse_args( $sign_token, array(
            'pem'   =>  '',
            'id'    =>  '',
            'exp'   =>  3600*12
        ) );        

        $args = wp_parse_args( $args, array(
            'uid'       =>  '',
            'time'      =>  '5s',
            'height'    =>  360,
            'ext'       =>  'jpg'
        ) );

        $url = sprintf(
            'https://%s/%s/thumbnails/thumbnail.%s?height=%s',
            $this->subdomain ? $this->subdomain : 'videodelivery.net',
            $args['uid'],
            $args['ext'],
            $args['height']
        );

        if( $args['ext'] == 'gif' ){
            $url = add_query_arg( array(
                'time'  =>  $args['time']
            ), $url );
        }

        if( $sign_token['pem'] ){
            $url = str_replace( $args['uid'], $this->get_sign_token(
                $args['uid'],
                $sign_token
            ), $url );
        }        

        return $url;
    }

    /**
     *
     * Get download URL
     * 
     * @param  string $uid
     * @param  string $name
     * @return string
     */
    public function get_download_url( $uid, $name = '', $sign_token = array() ){

        $sign_token = wp_parse_args( $sign_token, array(
            'pem'   =>  '',
            'id'    =>  '',
            'exp'   =>  3600*12
        ) );        

        if( ! $this->subdomain ){
            return false;
        }

        $url = sprintf(
            'https://%s/%s/downloads/default.mp4',
            $this->subdomain,
            $uid
        );

        if( $name ){
            $url = add_query_arg( array(
                'filename'  =>  $name
            ), $url );
        }

        if( $sign_token['pem'] ){
            $url = str_replace( $uid, $this->get_sign_token(
                $uid,
                $sign_token
            ), $url );
        }        

        return $url;
    }

    /**
     *
     * Get cloudflare iframe
     * 
     */
    public function get_iframe( $uid = '', $sign_token = array() ){

        $sign_token = wp_parse_args( $sign_token, array(
            'pem'   =>  '',
            'id'    =>  '',
            'exp'   =>  3600*12
        ) );        

        $base_url = untrailingslashit( $this->cloudflarestream_url );

        if( $this->subdomain ){
            $base_url = 'https://' . $this->subdomain;
        }

        $iframe = sprintf(
            '<iframe src="%s/%s/iframe" allow="accelerometer; gyroscope; autoplay; encrypted-media; picture-in-picture;" allowfullscreen="true"></iframe>',
            $base_url,
            $uid
        );

        if( $sign_token['pem'] ){
            $iframe = str_replace( $uid, $this->get_sign_token(
                $uid,
                $sign_token
            ), $iframe );
        }

        return $iframe;       
    }

    /**
     *
     * Generate stream key for creating Sign Token
     * 
     * @return array|WP_Error
     * 
     */
    public function generate_stream_key(){
        return $this->call_api( trailingslashit( $this->api_url ) . 'keys', array(
            'method'    =>  'POST'
        ) );
    }

    /**
     *
     * Delete stream key
     * 
     * @param  string $key_id
     * 
     */
    public function delete_stream_key( $key_id = '' ){
        return $this->call_api( trailingslashit( $this->api_url ) . 'keys/' . $key_id, array(
            'method'    =>  'DELETE'
        ) );
    }

    /**
     * Signs a URL token for stream reproduction.
     *
     */
    public function get_sign_token( $uid, $key = array(), $nbf = null ){

        $key = wp_parse_args( $key, array(
            'pem'   =>  '',
            'id'    =>  '',
            'exp'   =>  3600*12
        ) );

        $exp = $key['exp'];

        unset( $key['exp'] );

        $privateKey = base64_decode($key['pem']);

        $header     = ['alg' => 'RS256', 'kid' => $key['id']];
        $payload    = ['sub' => $uid, 'kid' => $key['id']];

        if ( $exp ) {
            $payload['exp'] = floor(microtime(true) * 1000) + $exp;
        }

        if ($nbf) {
            $payload['nbf'] = $nbf;
        }

        $encodedHeader  = $this->base64Url(json_encode($header));
        $encodedPayload = $this->base64Url(json_encode($payload));

        $signature = '';

        if ( ! openssl_sign("$encodedHeader.$encodedPayload", $signature, $privateKey, OPENSSL_ALGO_SHA256 ) ) {
            return new WP_Error(
                'failed_sign_token',
                esc_html__( 'Failed to sign the token.', 'wp-cloudflare-stream' )
            );
        }

        $encodedSignature = $this->base64Url($signature);

        return "$encodedHeader.$encodedPayload.$encodedSignature";
    }    

    /**
     *
     * Subscribe Webhook
     * 
     * @param  string $notificationUrl
     * @return call_api()
     *
     * @since 1.0.0
     * 
     */
    public function subscribe_webhook( $notificationUrl ){
        return $this->call_api( $this->api_url . "/webhook", array(
            'method'    =>  'PUT',
            'body'      =>  json_encode( compact( 'notificationUrl' ) )
        ) );
    }

    /**
     *
     * Upload watermark
     * 
     * @param  array  $args
     * @return call_api()
     *
     * @since 1.0.0
     * 
     */
    public function upload_watermark( $args = array() ){
        $args = wp_parse_args( $args, array(
            'url'   =>  '',
            'name'  =>  esc_html__( 'Watermark', 'wp-cloudflare-stream' ),
            'opacity'   =>  1.0,
            'padding'   =>  0.0,
            'scale'     =>  0.15,
            'position'  =>  'upperRight'
        ) );

        return $this->call_api( $this->api_url . "/watermarks", array(
            'method'    =>  'POST',
            'body'      =>  json_encode( $args )
        ) );        
    }
}