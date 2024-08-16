<?php
/**
 * Define the BunnyCDN API functionality
 *
 *
 * @link       https://themeforest.net/user/phpface
 * @since      2.1
 *
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 */

/**
 *
 * @since      2.1
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */

if( ! defined('ABSPATH' ) ){
    exit;
}

class Streamtube_Core_BunnyCDN_API{
    
    /**
     *
     * Holds the Access Key
     * 
     * @var string
     *
     * @since 2.1
     * 
     */
    public $AccessKey       = '';

    /**
     *
     * Holds the Library Id
     * 
     * @var int
     *
     * @since 2.1
     * 
     */
    public $libraryId       =   0;

    /**
     *
     * Holds the CDN Hostname
     * 
     * @var string
     *
     * @since 2.1
     * 
     */
    public $cdn_hostname    =   '';    

    /**
     *
     * Holds the base URL
     *
     * @since 2.1
     * 
     * 
     */
    const API_BASE_URL      = 'http://video.bunnycdn.com';

    /**
     *
     * Holds the exec output
     * 
     * @var string
     *
     * @since 2.1
     * 
     */
    public $exec_output     = '';

    /**
     *
     * Holds the exec code
     * 
     * @var string
     *
     * @since 2.1
     * 
     */
    public $exec_code       = '';    

    /**
     *
     * Class contructor
     * 
     * @since 2.1
     */
    public function __construct( $args = array() ){

        $args = wp_parse_args( $args, array(
            'AccessKey'     =>  '',
            'libraryId'     =>  0,
            'cdn_hostname'  =>  ''
        ) );

        $this->AccessKey        =   $args['AccessKey'];

        $this->libraryId        =   $args['libraryId'];

        $this->cdn_hostname     =   $args['cdn_hostname'];        
    }

    /**
     *
     * Format video details field value
     * 
     * @param  string $field
     * @param string $value
     *
     * @since 2.1.2
     * 
     */
    public function get_format_video_details_field_value( $field = '', $value = '' ){
        switch ( $field ) {
            case 'storageSize':
                return sprintf(
                    '%s (%s)',
                    $value,
                    size_format( $value )
                );
            break;

            case 'encodeProgress':
                return $value . '%';
            break;  

            case 'dateUploaded':
                return sprintf(
                    '%s (%s)',
                    $value,
                    sprintf(
                        esc_html__( '%s ago', 'streamtube-core' ),
                        human_time_diff( current_time( 'timestamp' ), strtotime( $value ) )
                    )
                );
            break; 

            case 'status':

                $statuses = $this->get_webhook_video_statuses();

                if( array_key_exists( $value, $statuses ) ){
                    return sprintf(
                        '(%s) %s',
                        $value,
                        $statuses[ (string)$value ][1]
                    );
                }
            break;

            default:
                if( is_string( $value ) || is_int( $value ) ){
                    return $value;
                }else{
                    return json_encode( $value );    
                }
                
            break;
        }
    }
    
    /**
     *
     * Generate readable text for video field name
     * 
     * @param  string $field
     * @return string
     */
    public function get_video_details_field_name( $field = '' ){
        switch ( $field ) {
            case 'videoLibraryId':
                return esc_html__( 'Library ID', 'streamtube-core' );
            break;

            case 'guid':
                return esc_html__( 'ID', 'streamtube-core' );
            break;

            case 'title':
                return esc_html__( 'Title', 'streamtube-core' );
            break;

            case 'dateUploaded':
                return esc_html__( 'Date Uploaded', 'streamtube-core' );
            break;

            case 'collectionId':
                return esc_html__( 'Collection ID', 'streamtube-core' );
            break;

            case 'thumbnailFileName':
                return esc_html__( 'Thumbnail File Name', 'streamtube-core' );
            break;

            case 'isPublic':
                return esc_html__( 'Is Public', 'streamtube-core' );
            break;

            case 'availableResolutions':
                return esc_html__( 'Available Resolutions', 'streamtube-core' );
            break;

            case 'thumbnailCount':
                return esc_html__( 'Thumbnail Count', 'streamtube-core' );
            break;

            case 'encodeProgress':
                return esc_html__( 'Encode Progress', 'streamtube-core' );
            break;

            case 'storageSize':
                return esc_html__( 'Storage Size', 'streamtube-core' );
            break;

            case 'hasMP4Fallback':
                return esc_html__( 'Has MP4 Fallback', 'streamtube-core' );
            break;

            case 'averageWatchTime':
                return esc_html__( 'Average Watch Time', 'streamtube-core' );
            break;

            case 'totalWatchTime':
                return esc_html__( 'Total Watch Time', 'streamtube-core' );
            break;

            default:
                return ucwords( $field );
            break;            

        }
    }

    /**
     *
     * Convert collection fields to readable text
     * 
     * @param  string $field
     *
     * @since 2.1.2
     * 
     */
    public function get_collection_field_name( $field = '' ){
        switch ( $field ) {
            case 'videoLibraryId':
                return esc_html__( 'Library ID', 'streamtube-core' );
            break;

            case 'guid':
                return esc_html__( 'Collection ID', 'streamtube-core' );
            break;

            case 'name':
                return esc_html__( 'Collection Name', 'streamtube-core' );
            break;

            case 'videoCount':
                return esc_html__( 'Video Count', 'streamtube-core' );
            break;

            case 'totalSize':
                return esc_html__( 'Total Size', 'streamtube-core' );
            break;

            case 'previewVideoIds':
                return esc_html__( 'Preview Video Ids', 'streamtube-core' );
            break;

            default:
                return $field;
            break; 
        }
    }

    /**
     *
     * Format collection field value
     * 
     * @param  string $field
     * @param string $value
     *
     * @since 2.1.2
     * 
     */
    public function get_format_collect_field_value( $field, $value ){
        switch ( $field ) {
            case 'totalSize':
                return size_format( $value );
            break;

            case 'videoCount':
                return number_format_i18n( absint( $value ) );
            break;
            
            default:
                return $value;
            break;
        }
    }

    /**
     *
     * Get webhook video statuses
     * 
     * @return array
     *
     * @link https://docs.bunny.net/docs/stream-webhook
     *
     * @since 2.1
     * 
     */
    public function get_webhook_video_statuses(){
        $statuses = array(
            '-1'    =>  array(
                'uploading',
                esc_html__( 'The video is waiting for uploading', 'streamtube-core' )
            ),
            '0'     =>  array(
                'queued',
                esc_html__( 'The video has been queued for encoding', 'streamtube-core' )
            ),
            '1'     =>  array(
                'processing',
                esc_html__( 'The video has begun processing', 'streamtube-core' )
            ),
            '2'     =>  array(
                'encoding',
                esc_html__( 'The video is encoding', 'streamtube-core' )
            ),
            '3'     =>  array(
                'finished',
                esc_html__( 'The Video encoding has finished', 'streamtube-core' )
            ),
            '4'     =>  array(
                'resolution_finished',
                esc_html__( 'The encoder has finished processing one of the resolutions and is now playable', 'streamtube-core' )
            ),
            '5'     =>  array(
                'failed',
                esc_html__( 'The video encoding failed', 'streamtube-core' )
            ),
            '6'     =>  array(
                'presigned_upload_started',
                esc_html__( 'A pre-signed upload has been initiated.', 'streamtube-core' )
            ),
            '7'     =>  array(
                'presigned_upload_finished',
                esc_html__( 'A pre-signed upload has been completed.', 'streamtube-core' )
            ),
            '8'     =>  array(
                'presigned_upload_failed',
                esc_html__( 'A pre-signed upload has failed.', 'streamtube-core' )
            ),
            '9'     =>  array(
                'captions_generated',
                esc_html__( 'Automatic captions were generated.', 'streamtube-core' )
            ),
            '10'     =>  array(
                'title_or_description_generated',
                esc_html__( 'Automatic generation of title or description has been completed.', 'streamtube-core' )
            )            
        );

        /**
         * @since 2.1
         */
        return apply_filters( 'streamtube/core/bunnycdn/webhook/statuses', $statuses );
    }

    /**
     *
     * Call API
     *
     * @return WP_Error|array
     *
     * @since 2.1
     * 
     */
    protected function call_api( $url, $args = array() ){

        $args = array_merge( $args, array(
            'headers'   =>  array(
                'Accept'        =>  'application/json',
                'AccessKey'     =>  $this->AccessKey,
                'Content-Type'  =>  'application/*+json'
            )
        ) );

        $response = wp_remote_request( $url, $args );

        if( is_wp_error( $response ) ){
            return $response;
        }

        if( wp_remote_retrieve_response_code( $response ) != 200 ){
            return new WP_Error(
                wp_remote_retrieve_response_code( $response ),
                wp_remote_retrieve_response_message( $response )
            );
        }

        return json_decode( wp_remote_retrieve_body( $response ), true );
    }

    /**
     *
     * Get login file path
     * 
     * @param  string $video_file 
     * @return string
     *
     * @since 2.1
     * 
     */
    public function get_log_file( $video_file ){        
        $file_path      = trailingslashit( plugin_dir_path( $video_file ) );
        $file_log_name  = sanitize_file_name( basename( $video_file ) ) . '.log';

        return $file_path . $file_log_name;
    }

    /**
     *
     * Create an empty log file
     * 
     * @param  string $file 
     *
     * @since 2.1
     * 
     */
    public function create_empty_log_file( $video_file ){

        $log_file = $this->get_log_file( $video_file );

        if( file_exists( $log_file ) ){
            return $log_file;
        }

        $fopen = fopen( $log_file , 'w' );

        if( $fopen ){
            fwrite( $fopen, '' );
        }

        fclose( $fopen );

        return $log_file;
    }

    /**
     *
     * Delete video log file.
     * 
     * @param  string $video_file 
     * @return string
     *
     * @since 2.1
     * 
     */
    public function delete_log_file( $video_file ){
        return @unlink( $this->get_log_file( $video_file ) );
    }

    /**
     *
     * Read video log file.
     * 
     * @param  string $video_file 
     * @return string
     *
     * @since 2.1
     * 
     */
    public function read_log_file( $video_file ){
        $log_file = $this->get_log_file( $video_file );

        if( file_exists( $log_file ) && $log_content = @file_get_contents( $log_file ) ){

            $log_content = trim( $log_content );

            if( is_numeric( $log_content ) ){

                $log_content = $this->read_task_log_content( $log_content );
            }

            return $log_content;
        }

        return false;
    }

    /**
     *
     * Read task log
     * 
     * @param  id $task_id
     * @return string
     *
     * @since 2.1
     * 
     */
    public function read_task_log_content( $task_id ){
        $tsp_path = get_option( 'system_tsp_path', '/usr/bin/tsp' );

        return Streamtube_Core_Task_Spooler::get_task_log( $tsp_path, $task_id );
    }    

    /**
     *
     * Write video log file.
     * 
     * @param  string $video_file 
     * @return string
     *
     * @since 2.1
     * 
     */
    public function write_log_file( $video_file, $log_content = '', $marker = 'Log' ){

        if( wp_http_validate_url( $video_file ) ){
            return false;
        }

        if( ! $log_content ){
            $log_content = $this->read_log_file( $video_file );
        }

        if( ! $log_content ){
            return false;
        }

        if( ! function_exists( 'insert_with_markers' ) ){
            require_once( ABSPATH . 'wp-admin/includes/misc.php' );
        }

        add_filter( 'insert_with_markers_inline_instructions', function( $instructions, $marker ){
            return array();
        }, 10, 2 );

        return insert_with_markers( $this->get_log_file( $video_file ), $marker, $log_content );
    } 

    /**
     *
     * Create Video
     * 
     * @param  string $title
     * @param  string $collectionId
     * @return call_api()
     *
     * @since 2.1
     * 
     */
    public function create_video( $title = '', $collectionId = '' ){

        return $this->call_api( self::API_BASE_URL . "/library/{$this->libraryId}/videos", array(
            'method'    =>  'POST',
            'body'      =>  json_encode( compact( 'title', 'collectionId' ) )
        ) );
    }

    /**
     *
     * Get Video
     * 
     * @param  string $videoId
     * @return call_api()
     *
     * @since 2.1
     * 
     */
    public function get_video( $videoId = '' ){
        return $this->call_api( self::API_BASE_URL . "/library/{$this->libraryId}/videos/{$videoId}", array(
            'method'    =>  'GET'
        ) );
    }

    /**
     *
     * Update Video
     * 
     * @param  array $args{
     *         @param string $videoId
     *         @param string $title
     *         @param string $collectionId
     *         @param array $chapters
     *         @param array $moments
     * }
     * @return call_api()
     *
     * @since 2.1
     * 
     */
    public function update_video( $args = array() ){

        $args = wp_parse_args( $args, array(
            'videoId'           =>  '',
            'title'             =>  '',
            'collectionId'      =>  '',
            'chapters'          =>  array(),
            'moments'           =>  array()
        ) );

        extract( $args );

        return $this->call_api( self::API_BASE_URL . "/library/{$this->libraryId}/videos/{$videoId}", array(
            'method'    =>  'POST',
            'body'      =>  json_encode( compact( 'title', 'collectionId', 'chapters', 'moments' ) )
        ) );
    }

    /**
     *
     * Delete Video
     * 
     * @param  string $videoId
     * @return call_api()
     *
     * @since 2.1
     * 
     */
    public function delete_video( $videoId = '' ){
        return $this->call_api( self::API_BASE_URL . "/library/{$this->libraryId}/videos/{$videoId}", array(
            'method'    =>  'DELETE'
        ) );
    }    

    /**
     *
     * Upload Video
     * 
     * @param  string $videoId
     * @param string $file file path
     *
     * @since 2.1
     * 
     */
    public function php_curl_upload_video( $videoId = '', $file = '' ){

        $stream = fopen( $file, "r");

        if( ! $stream ){
            $this->write_log_file( $file, esc_html__( 'Cannot read file', 'streamtube-core' ) );
            return new WP_Error(
                'cannot_read_file',
                esc_html__( 'Cannot read file', 'streamtube-core' )
            );
        }

        if( ! function_exists( 'curl_init' ) ){
            $this->write_log_file( $file, esc_html__( 'curl_init function is disabled', 'streamtube-core' ) );
        }        

        $curl = curl_init();

        curl_setopt_array( 
            $curl,
            array(
                CURLOPT_CUSTOMREQUEST       => 'PUT',
                CURLOPT_URL                 => self::API_BASE_URL . "/library/{$this->libraryId}/videos/{$videoId}",
                CURLOPT_RETURNTRANSFER      => 1,
                CURLOPT_TIMEOUT             => 60000,
                CURLOPT_FOLLOWLOCATION      => 0,
                CURLOPT_FAILONERROR         => 0,
                CURLOPT_SSL_VERIFYPEER      => 1,
                CURLOPT_INFILE              => $stream,
                CURLOPT_INFILESIZE          => filesize( $file ),
                CURLOPT_UPLOAD              => 1,
                CURLOPT_HTTPHEADER          => array(
                    'AccessKey: ' . $this->AccessKey
                )
            ) 
        );

        $response   = curl_exec( $curl );
        $http_code  = curl_getinfo( $curl, CURLINFO_HTTP_CODE );

        // Cleanup
        curl_close($curl);
        fclose($stream);

        if( $http_code != 200 ){
            $this->write_log_file( $file, sprintf(
                esc_html__( '%s: Error, cannot upload file', 'streamtube-core' ),
                $http_code
            ) );
            return new WP_Error( $http_code, esc_html__( 'Error, cannot upload file', 'streamtube-core' ) );
        }

        $this->write_log_file( $file, json_encode( $response ) );

        return $response;
    }

    /**
     *
     * Generate shell CURL upload command
     * 
     * @param  string $videoId
     * @param  string $file
     * @param  string $curl_app_path
     * 
     * @return @since 2.1
     */
    public function get_command_curl_upload_video( $videoId = '', $file = '', $curl_path = '' ){
        $file_type      = wp_check_filetype( $file );
        $file_size      = filesize( $file );

        if( $curl_path ){
            $curl_path = untrailingslashit( $curl_path );
        }

        $cmd = "{$curl_path} -i -X PUT " . self::API_BASE_URL . "/library/{$this->libraryId}/videos/{$videoId}";
        $cmd .= " --data-binary @{$file}";
        $cmd .= " --header 'Access: application/json'";
        $cmd .= " --header 'AccessKey: {$this->AccessKey}'";
        $cmd .= " --header 'Transfer-Encoding: chunked'";
        $cmd .= " --header 'Content-Type: {$file_type['type']}'";
        $cmd .= " --header 'Content-Length: {$file_size}'";

        return $cmd;
    }

    /**
     *
     * Upload file using Shell CURL
     * 
     * @param  string $videoId
     * @param  string $file
     * @param  string $curl_path
     * 
     * @return @since 2.1
     */
    public function shell_curl_upload_video( $videoId = '', $file = '', $curl_path = '', $tsp = false, $tsp_path = '' ){

        if( ! function_exists( 'exec' ) ){
            $this->write_log_file( $file, esc_html__( 'exec function is disabled', 'streamtube-core' ) );
            return new WP_Error(
                'exec_disabled',
                esc_html__( 'exec function is disabled', 'streamtube-core' )
            );
        }

        $file_log       = $this->get_log_file( $file );

        if( file_exists( $file_log ) ){
            @unlink( $file_log );
        }

        $cmd = $this->get_command_curl_upload_video( $videoId, $file, $curl_path );

        if( ! $tsp ){

            $cmd .= " >/dev/null 2>&1 2> {$file_log} & echo $!";
            exec( $cmd, $this->exec_output, $this->exec_code );
        }
        else{

            if( $tsp_path ){
                $tsp_path = untrailingslashit( $tsp_path );
            }

            $cmd .= " > {$file_log}";
            exec( "{$tsp_path} {$cmd}", $this->exec_output, $this->exec_code );
        }

        return array(
            'output'    =>  $this->exec_output,
            'code'      =>  $this->exec_code
        );
    }

    /**
     *
     * Fetch external video file
     * 
     * @param  string $videoId
     * @param  string $url
     * @return call_api()
     *
     * @since 2.1
     * 
     */
    public function fetch_video( $videoId, $url ){
        return $this->call_api( self::API_BASE_URL . "/library/{$this->libraryId}/videos/{$videoId}/fetch", array(
            'method'    =>  'POST',
            'body'      =>  json_encode( compact( 'url' ) )
        ) );
    }

    /**
     *
     * Reencode video
     * 
     * @param  string $videoId
     * @return call_api()
     *
     * @since 2.1
     * 
     */
    public function reencode_video( $videoId ){
        return $this->call_api( self::API_BASE_URL . "/library/{$this->libraryId}/videos/{$videoId}/reencode", array(
            'method'    =>  'POST'
        ) );        
    }

    /**
     *
     * Get video HLS playlist URL
     * 
     * @param  string $videoId
     * @return string|false
     *
     * @since 2.1
     * 
     */
    public function get_video_hls_url( $videoId ){
        if( ! $videoId ){
            return false;
        }

        return sprintf( 'https://%s/%s/playlist.m3u8', untrailingslashit( $this->cdn_hostname ), $videoId );
    }

    /**
     *
     * Get direct file URL
     * 
     * @param  string $videoId
     * 
     */
    public function get_direct_file_url( $videoId ){
        return sprintf( 'https://%s/%s/original', untrailingslashit( $this->cdn_hostname ), $videoId );
    }

    /**
     *
     * Get video thumbnail URL
     * 
     * @param  string $videoId
     * @return string|false
     *
     * @since 2.1
     * 
     */
    public function get_video_thumbnail_url( $videoId ){
        if( ! $videoId ){
            return false;
        }

        return sprintf( 'https://%s/%s/thumbnail.jpg', untrailingslashit( $this->cdn_hostname ), $videoId );
    }

    /**
     *
     * Get video preview webp URL
     * 
     * @param  string $videoId
     * @return string|false
     *
     * @since 2.1
     * 
     */
    public function get_video_preview_webp_url( $videoId ){
        if( ! $videoId ){
            return false;
        }

        return sprintf( 'https://%s/%s/preview.webp', untrailingslashit( $this->cdn_hostname ), $videoId );
    }

    /**
     *
     * Get direct bunny player
     * 
     */
    public function get_direct_player( $libraryId, $videoId ){
        return sprintf(
            'https://iframe.mediadelivery.net/embed/%s/%s',
            $libraryId,
            $videoId
        );
    }

    /**
     *
     * Create collection
     * 
     * @param  string $name
     * @return call_api()
     *
     * @since 2.1
     * 
     */
    public function create_collection( $name = '' ){
        return $this->call_api( self::API_BASE_URL . "/library/{$this->libraryId}/collections", array(
            'method'    =>  'POST',
            'body'      =>  json_encode( compact( 'name' ) )
        ) );
    }

    /**
     *
     * Update collection
     *
     * @param string  $collectionId
     * @param  string $name
     * @return call_api()
     *
     * @since 2.1
     * 
     */
    public function update_collection( $collectionId, $name = '' ){
        return $this->call_api( self::API_BASE_URL . "/library/{$this->libraryId}/collections/{$collectionId}", array(
            'method'    =>  'POST',
            'body'      =>  json_encode( compact( 'name' ) )
        ) );
    }    

    /**
     *
     * Delete collection
     * 
     * @param  string $collectionId
     * @return call_api()
     *
     * @since 2.1
     * 
     */
    public function delete_collection( $collectionId ){
        return $this->call_api( self::API_BASE_URL . "/library/{$this->libraryId}/collections/{$collectionId}", array(
            'method'    =>  'DELETE'
        ) );
    }

    /**
     *
     * Get collection
     * 
     * @param  string $collectionId
     * @return call_api()
     *
     * @since 2.1
     * 
     */
    public function get_collection( $collectionId ){
        return $this->call_api( self::API_BASE_URL . "/library/{$this->libraryId}/collections/{$collectionId}", array(
            'method'    =>  'GET'
        ) );
    } 
}