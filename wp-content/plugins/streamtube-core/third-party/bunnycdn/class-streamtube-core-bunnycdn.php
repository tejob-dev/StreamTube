<?php
/**
 * Define the BunnyCDN functionality
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

class Streamtube_Core_BunnyCDN{

    /**
     *
     * Holds the settings
     * 
     * @var array
     *
     * @since 2.1
     * 
     */
    public $settings = array();

    /**
     *
     * Holds the Bunny API object
     * 
     * @var object
     *
     * @since 2.1
     * 
     */
    public $bunnyAPI;

    /**
     *
     * Holds the admin
     * 
     * @var object
     *
     * @since 2.1
     * 
     */
    public $admin;

    private $Post;

    public function __construct(){

        $this->load_dependencies();

        $this->settings = Streamtube_Core_BunnyCDN_Settings::get_settings();

        $this->admin = new Streamtube_Core_BunnyCDN_Admin(); 

        $this->bunnyAPI = new Streamtube_Core_BunnyCDN_API( array(
            'AccessKey'     =>  $this->settings['AccessKey'],
            'libraryId'     =>  $this->settings['libraryId'],
            'cdn_hostname'  =>  $this->settings['cdn_hostname']
        ) );

        $this->Post = new Streamtube_Core_Post();
    }

    /**
     *
     * Include file
     * 
     * @param  string $file
     *
     * @since 2.1
     * 
     */
    private function include_file( $file ){
        require_once plugin_dir_path( __FILE__ ) . $file;
    }    

    /**
     *
     * Load dependencies
     *
     * @since 2.1
     * 
     */
    private function load_dependencies(){
        if( ! function_exists( 'media_sideload_image' ) ){
            require_once(ABSPATH . 'wp-admin/includes/media.php');
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            require_once(ABSPATH . 'wp-admin/includes/image.php');
        }  
        $this->include_file( 'class-streamtube-core-bunnycdn-settings.php' );      
        $this->include_file( 'class-streamtube-core-bunnycdn-admin.php' );
        $this->include_file( 'class-streamtube-core-bunnycdn-api.php' );        
    }

    /**
     *
     * Settings Tabs
     * 
     * @return array
     *
     * @since 2.1
     * 
     */
    public function get_setting_tabs(){
        $tabs = array(
            'general'           =>  array(
                'heading'       =>  esc_html__( 'General', 'streamtube-core' ),
                'inform'        =>  true
            ),
            'notifications'     =>  array(
                'heading'       =>  esc_html__( 'Notifications', 'streamtube-core' ),
                'inform'        =>  true
            )
        );

        return apply_filters( 'streamtube/core/bunnycdn/admin/tabs', $tabs );
    }

    /**
     *
     * Get sync types
     * 
     * @return array
     *
     * @since 2.1
     * 
     */
    public function get_upload_types(){
        return array(
            'auto'        =>  esc_html__( 'Auto', 'streamtube-core' ),
            'manual'      =>  esc_html__( 'Manual', 'streamtube-core' )
        );
    }      

    /**
     *
     * Get sync types
     * 
     * @return array
     *
     * @since 2.1
     * 
     */
    public function get_sync_types(){
        return array(
            'fetching'      =>  esc_html__( 'Fetching', 'streamtube-core' ),
            'php_curl'      =>  esc_html__( 'PHP cURL', 'streamtube-core' ),
            'shell_curl'    =>  esc_html__( 'Shell cURL', 'streamtube-core' )
        );
    }  

    /**
     *
     * Get webhook URL
     * 
     * @return string
     *
     * @since 2.1
     * 
     */
    public function get_webhook_url(){
        return add_query_arg( array(
            'webhook'   =>  'bunnycdn',
            'key'       =>  $this->settings['webhook_key']
        ), home_url('/') );
    }

    public function is_enabled(){
        return $this->settings['enable'] && $this->settings['is_connected'] ? true : false;
    }

    /**
     *
     * Check if auto sync enabled
     * 
     * @return boolean
     *
     * @since 2.1
     * 
     */
    public function is_auto_sync(){
        return $this->is_enabled() && ( $this->settings['upload_type'] == 'auto' ) ? true : false;
    }

    /**
     *
     * Check if Bulk Sync supported
     * 
     * @return boolean
     *
     * @since 2.1
     * 
     */
    public function is_bulk_sync_supported(){
        return ( $this->settings['sync_type'] == 'php_curl' ) ? false : true;
    }

    /**
     *
     * Check if post is synced
     * 
     * @param  int  $post_id
     * @return true|false
     *
     * @since 2.1
     * 
     */
    public function is_synced( $post_id ){

        $has_data   = get_post_meta( $post_id,      '_bunnycdn', true );
        $is_encoded = (int)get_post_meta( $post_id, '_bunnycdn_status', true );

        return $has_data && $is_encoded == 3 ? true : false;
    }

    /**
     *
     * Get WP Post ID (attachment_id) from bunny guid
     * 
     * @param  string $videoId
     * @return false|int
     *
     * @since 2.1
     * 
     */
    public function get_post_id_from_videoId( $videoId ){
        global $wpdb;

        $results = $wpdb->get_var( 
            $wpdb->prepare( 
                "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = %s AND meta_value = %s", 
                '_bunnycdn_guid',
                $videoId 
            ) 
        );

        if( $results ){
            return (int)$results;
        }

        return false;
    }

    /**
     *
     * Get bunny videoId
     * 
     * @param  int $post_id
     * @return false|string
     *
     * @since 2.1
     * 
     */
    public function get_video_guid( $post_id ){

        $post_id = (int)$post_id;

        $videoId = get_post_meta( $post_id, '_bunnycdn_guid', true );

        if( $videoId ){
            return $videoId;
        }

        return false;
    }

    public function get_downloadable_url( $attachment_id ){

        $videoId    = $this->get_video_guid( $attachment_id );

        $url        = wp_get_attachment_url( $attachment_id );

        if( $videoId ){
            $url = add_query_arg( array(
                'download'  =>  'true',
                'name'      =>  sanitize_file_name( basename( get_attached_file( $attachment_id ) ) )
            ), $this->bunnyAPI->get_direct_file_url( $videoId ) );               
        }

        /**
         *
         * Filter the URL
         * 
         */
        return apply_filters( 'streamtube/core/bunnycdn/downloadable_url', $url, $attachment_id, $videoId );
    }

    /**
     *
     * Update user collection metadata
     * 
     * @param  int $user_id
     * @param  array $collection
     * @return update_user_meta()
     *
     * @since 2.1
     * 
     */
    private function _update_user_collection_metadata( $user_id, $collection ){
        return update_user_meta( $user_id, '_bunnycdn_collection', $collection );
    }

    /**
     *
     * Get user collection metadata
     * 
     * @param  int $user_id
     * @return get_user_meta()
     *
     * @since 2.1
     * 
     */
    private function _get_user_collection_metadata( $user_id ){
        return get_user_meta( $user_id, '_bunnycdn_collection', true );
    }    

    /**
     *
     * Create collection
     * 
     * @param  int $user_id
     * @return WP_Error|Array
     *
     * @since 2.1
     * 
     */
    public function create_collection( $user_id, $name = '' ){

        $user_id = (int)$user_id;

        if( ! $name ){
            $name = get_userdata( $user_id )->display_name;
        }

        $collection = $this->bunnyAPI->create_collection( $name );

        if( ! is_wp_error( $collection ) ){
            $this->_update_user_collection_metadata( $user_id, $collection );
        }

        return $collection;
    }

    /**
     *
     * Get collection
     * 
     * @param  int $user_id
     * @return false|array
     *
     * @since 2.1
     * 
     */
    public function get_collection( $user_id = 0 ){

        $user_id = (int)$user_id;

        $collection = $this->_get_user_collection_metadata( $user_id );

        if( is_array( $collection ) ){
            return $collection;
        }

        return false;
    }

    /**
     *
     * Get collection id
     * 
     * @param  int $user_id
     * @return false|string
     *
     * @since 2.1
     * 
     */
    public function get_collection_id( $user_id = 0 ){
        $collection = $this->get_collection( $user_id );

        if( is_array( $collection ) ){
            return $collection['guid'];
        }

        return false;
    }

    /**
     *
     * Request collectionId
     * 
     * @param  int $user_id [description]
     * @return string|WP_Error
     *
     * @since 2.1
     * 
     */
    public function request_collection_id( $user_id ){

        $collection  = $this->get_collection( $user_id );

        if( is_array( $collection ) ){

            // Verify if collection exists on bunny
            $collection = $this->bunnyAPI->get_collection( $collection['guid'] );

            if( is_wp_error( $collection ) ){

                if( (int)$collection->get_error_code() == 404 ){
                    // It seems the collection was deleted, try to create a new one
                    $collection = $this->create_collection( $user_id );

                    if( is_wp_error( $collection ) ){
                        // If still error, return WP_Error
                        return $collection;
                    }else{
                        return $collection['guid'];
                    }
                }

                // Return WP_Error
                return $collection;
            }else{

                $this->_update_user_collection_metadata( $user_id, $collection );

                return $collection['guid'];
            }
        }else{
            $collection = $this->create_collection( $user_id );

            if( is_wp_error( $collection ) ){
                // If still error, return
                return $collection;
            }else{
                return$collection['guid'];
            }
        }
    }

    /**
     *
     * Get bunny iframe
     * 
     */
    public function get_iframe( $videoId ){
        $allow = array(
            'accelerometer',
            'autoplay',
            'clipboard-write',
            'encrypted-media',
            'gyroscope',
            'picture-in-picture',
            'fullscreen'
        );

        $iframe = sprintf(
            '<iframe allow="%s" src="%s"></iframe>',
            esc_attr( join( ';', $allow ) ),
            $this->bunnyAPI->get_direct_player( $this->settings['libraryId'], $videoId )
        );

        /**
         *
         * Filter the player
         *
         * @param string $iframe
         * @param string $video UID
         * @param string $library UID
         * 
         */
        return apply_filters( 'streamtube/core/bunnycdn/bunny_player', $iframe, $videoId, $this->settings['libraryId'] );        
    }

    /**
     *
     * Delete attachment files
     * 
     * @param  int $post_id attachment_id
     *
     * @return wp_delete_file_from_directory();
     * 
     * @since 2.1
     */
    public function delete_attachment_file( $post_id ){
        $uploadpath = wp_get_upload_dir();
        return wp_delete_file_from_directory( get_attached_file( $post_id ), $uploadpath['basedir'] );
    }
    
    /**
     *
     * Create new Video after adding attachment
     * 
     * @param int $post_id
     *
     * @since 2.1
     * 
     */
    public function _add_attachment( $post_id ){

        $post           = get_post( $post_id );
        $user_id        = $post->post_author;
        $post_title     = $post->post_title;
        $attachment_url = wp_get_attachment_url( $post_id );
        $collectionId   = '';
        $collection     = false;
        $upload         = false;

        if( $this->settings['file_organize'] ){
            $collectionId = $this->request_collection_id( $user_id );

            if( is_wp_error( $collectionId ) ){

                $this->bunnyAPI->write_log_file(
                    get_attached_file( $post_id ),
                    $collectionId->get_error_code() . ' ' . $collectionId->get_error_message(),
                    $collectionId->get_error_code()
                );
                return $post_id;
            }
        }
        
        $create         = $this->bunnyAPI->create_video( get_the_title( $post_id ), $collectionId );

        if( ! is_wp_error( $create ) ){

            set_time_limit(0);

            update_post_meta( $post_id, '_bunnycdn', $create );
            update_post_meta( $post_id, '_bunnycdn_guid', $create['guid'] );
            update_post_meta( $post_id, '_bunnycdn_status', '-1' );// uploading

            /**
             *
             * Fires after Video created
             *
             * @param array $create
             * @param int $post_id (attachment_id)
             *
             * @since 2.1
             * 
             */
            do_action( 'streamtube/core/bunnycdn/video/created', $create, $post_id );

            $file = get_post_meta( $post_id, '_wp_attached_file', true );

            $this->bunnyAPI->delete_log_file( get_attached_file( $post_id ) );

            if( wp_http_validate_url( $file ) ){

                // Check allowed size
                $_metadata = get_post_meta( $post_id, '_wp_attachment_metadata', true );

                if( is_array( $_metadata ) && array_key_exists( 'filesize', $_metadata ) ){

                    $_filesize  = (int)$_metadata['filesize'];
                    $_max       = (int)streamtube_core_get_max_upload_size();

                    if( $_filesize && $_max && apply_filters( 'check_max_size_remote_source', true ) === true ){
                        if( $_filesize > $_max ){
                            
                            delete_post_meta( $post_id, '_bunnycdn' );
                            delete_post_meta( $post_id, '_bunnycdn_guid' );
                            delete_post_meta( $post_id, '_bunnycdn_status' );

                            do_action( 'streamtube/core/bunnycdn/video/before_delete', $post_id, $create['guid'] );

                            $this->bunnyAPI->delete_video( $create['guid'] );

                            return new WP_Error(
                                'exceed_max_file_size',
                                esc_html__( 'The file size has exceeded the maximum allowed limit.', 'streamtube-core' )
                            );                             
                        }
                    }
                }

                $upload = $this->bunnyAPI->fetch_video( $create['guid'], $file );
            }else{

                $file = get_attached_file( $post_id );

                $this->bunnyAPI->create_empty_log_file( $file );

                switch ( $this->settings['sync_type'] ) {
                    case 'shell_curl':
                        $upload = $this->bunnyAPI->shell_curl_upload_video( 
                            $create['guid'], 
                            $file,
                            $this->settings['curl_path'], 
                            wp_validate_boolean( $this->settings['tsp'] ),
                            $this->settings['tsp_path']
                        );
                    break;

                    case 'php_curl':
                        $upload = $this->bunnyAPI->php_curl_upload_video( $create['guid'], $file );
                    break;
                    
                    default:
                        $upload = $this->bunnyAPI->fetch_video( $create['guid'], $attachment_url );
                    break;
                }
            }           

            /**
             *
             * Fires after Video uploaded
             *
             * @param array $upload
             * @param array $create
             * @param int $post_id (attachment_id)
             *
             * @since 2.1
             * 
             */
            do_action( 'streamtube/core/bunnycdn/video/uploaded', $upload, $create, $post_id );
        }

        return $create;
    }

    /**
     *
     * Create new Video after adding attachment
     * 
     * @param int $post_id
     *
     * @since 2.1
     * 
     */
    public function add_attachment( $post_id ){

        if( ! $this->is_auto_sync() || get_post_meta( $post_id, 'live_status', true ) ){
            return $post_id;
        }

        if( wp_attachment_is( 'video', $post_id ) || wp_attachment_is( 'audio', $post_id )){
            return $this->_add_attachment( $post_id );
        }
        
        return $post_id;
    }    

    /**
     *
     * Update Video after updating attachment
     * 
     * @param  int $post_id
     * @return update_video()
     *
     * @since 2.1
     * 
     */
    public function _attachment_updated( $post_id ){

        $videoId = $this->get_video_guid( $post_id );

        if( ! $videoId ){
            return $post_id;
        }

        $title = get_the_title( $post_id );
        
        return $this->bunnyAPI->update_video( compact( 'videoId', 'title' ) );
    }

    /**
     *
     * Update Video after updating attachment
     * 
     * @param  int $post_id
     * @return update_video()
     *
     * @since 2.1
     * 
     */
    public function attachment_updated( $post_id ){
        if( ! $this->is_auto_sync() ){
            return $post_id;
        }

        return $this->_attachment_updated( $post_id );
    }    

    /**
     *
     * Delete video while deleting attachment
     * 
     * @param  int $post_id
     * @return delete_video()
     *
     * @since 2.1
     * 
     */
    public function _delete_attachment( $post_id ){

        $videoId = $this->get_video_guid( $post_id );

        if( ! $videoId ){
            return $post_id;
        }

        $this->bunnyAPI->delete_log_file( get_attached_file( $post_id ) );

        return $this->bunnyAPI->delete_video( $videoId );
    }

    /**
     *
     * Delete video while deleting attachment
     * 
     * @param  int $post_id
     * @return delete_video()
     *
     * @since 2.1
     * 
     */
    public function delete_attachment( $post_id ){

        if( ! $this->is_auto_sync() ){
            return $post_id;
        }

        return $this->_delete_attachment( $post_id );
    }    

    /**
     *
     * Auto Fetch video
     * 
     * @param  int $post_id video post type ID
     *
     * @since 2.1
     * 
     */
    public function _fetch_external_video( $post_id, $source = '' ){

        set_time_limit(0);

        if( ! $this->is_enabled() ){
            return false;
        }

        if( empty( $source ) || ! wp_http_validate_url( $source ) ){
            return false;
        }

        $headers = wp_get_http_headers( $source );

        if( ! $headers ){
            return false;
        }

        $filetype = explode( '/', $headers['content-type'] );

        if( ! in_array( $filetype[0] , array( 'video', 'audio' ) ) ){
            return false;
        }

        if( $filetype[0] == 'video' && ! in_array( strtolower( $filetype[1] ) , wp_get_video_extensions() ) ){
            return false;
        }

        if( $filetype[0] == 'audio' && ! in_array( strtolower( $filetype[1] ) , wp_get_audio_extensions() ) ){
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

        if( ! isset( $_POST ) || ! isset( $_POST['video_url'] ) ){
            return $post_id;
        }

        return $this->_fetch_external_video( $post_id, $_POST['video_url'] );
    }  

    /**
     *
     * Try to fetch external source on updating post hook
     * 
     */
    public function post_updated_fetch_external_video( $post_id ){

        if( "" != $video_url = get_post_meta( $post_id, 'video_url', true ) ){
            return $this->_fetch_external_video( $post_id, $video_url );
        }
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
     * Get bunny video data
     * 
     * @param  integer $attachment_id
     * @return WP_Error|array
     * 
     */
    public function get_video_data( $attachment_id = 0 ){

        $videoId = $this->get_video_guid( $attachment_id );

        if( $videoId ){
            return $this->bunnyAPI->get_video( $videoId );    
        }

        return false;
    }

    /**
     *
     * Update bunny video data
     * 
     * @param  integer $attachment_id
     * @return WP_Error|array
     * 
     */
    public function update_video_data( $attachment_id ){

        $response = $this->get_video_data( $attachment_id );

        if( is_array( $response ) ){
            update_post_meta( $attachment_id, '_bunnycdn',        $response );
            update_post_meta( $attachment_id, '_bunnycdn_guid',   $response['guid'] );
            update_post_meta( $attachment_id, '_bunnycdn_status', $response['status'] );
        }

        return $response;
    } 

    /**
     *
     * Get video status
     * 
     * @param  int $attachment_id
     * @return html
     *
     * @since 2.1
     * 
     */
    public function get_video_status( $attachment_id ){

        $status     = -1;
        $progress   = 0;
        $statuses   = $this->bunnyAPI->get_webhook_video_statuses();

        $video_data = $this->update_video_data( $attachment_id );

        if( is_wp_error( $video_data ) ){
            $message = $video_data->get_error_message();
        }else{

            $video_data = wp_parse_args( $video_data, array(
                'status'                =>  -1,
                'encodeProgress'        =>  0,
                'availableResolutions'  =>  ''
            ) );

            $status     = (int)$video_data['status'];
            $progress   = (int)$video_data['encodeProgress'];

            /**
             *
             * Fires once webhook updated
             * Fake Webhook
             *
             * @param attachment_id
             * @param array $data
             *
             * @since 2.1
             * 
             */
            do_action( 'streamtube/core/bunny/webhook/update', $attachment_id, array(
                'Status'    =>  $status,
                'VideoGuid' =>  $video_data['guid']
            ), $video_data );

            if( in_array( $status , array( 0, -1, 1, 2, 5, 6, 7, 8, 9, 10 ) ) ){
                $message = $statuses[ (string)$status ][1];
            }

            if( in_array( $status , array( 3, 4 ) ) && ( $progress < 100 || ! $video_data['availableResolutions'] ) ){
                $message = esc_html__( 'Encoding is nearly complete, please wait just a few more seconds.', 'streamtube-core' );
            }

            // playable
            if( $progress == 100 || $video_data['availableResolutions'] ){
                $message = '';
            }
        }

        if( ! empty( $message ) ){
            return new WP_Error( 'waiting', $message, array_merge( compact( 'status', 'progress' ), array(
                'handler'   =>  'bunny'
            ) ) );
        }

        return true;
    }

    /**
     *
     * Sync video
     * 
     * @param  int $post_id
     * @return _add_attachment()
     *
     * @since 2.1
     * 
     */
    public function sync_video( $post_id ){

        if( $this->is_synced( $post_id ) ){
            return new WP_Error(
                'synced',
                esc_html__( 'This video is already synced', 'streamtube-core' )
            );
        }

        return $this->_add_attachment( $post_id );
    }

    /**
     *
     * Retry sunc video
     * 
     * @param  int $post_id attachment_id
     * @return _add_attachment()
     *
     * @since 2.1
     * 
     */
    public function retry_sync_video( $post_id ){

        if( $this->is_synced( $post_id ) ){
            return new WP_Error(
                'synced',
                esc_html__( 'This video is already synced', 'streamtube-core' )
            );
        }         

        if( "" != $videoId = $this->get_video_guid( $post_id ) ){
            $this->bunnyAPI->delete_video( $videoId );
        }

        delete_post_meta( $post_id, '_bunnycdn' );
        delete_post_meta( $post_id, '_bunnycdn_guid' );
        delete_post_meta( $post_id, '_bunnycdn_status' );

        return $this->_add_attachment( $post_id );
    }

    /**
     *
     * AJAX get video player status
     * 
     * @since 2.1
     * 
     */
    public function ajax_get_video_status(){
        check_ajax_referer( '_wpnonce' );   

        if( ! isset( $_GET['attachment_id'] ) ){
            wp_send_json_error( new WP_Error(
                'attachment_not_found',
                esc_html__( 'Attachment ID was not found', 'streamtube-core' )
            ) );
        }

        $output = $this->get_transcoding_progress( $_GET['attachment_id'] );

        if( strpos( $output , '<iframe') !== false ){
            wp_send_json_success( $output );
        }

        wp_send_json_error( $output );
    }

    /**
     *
     * Refresh Bunny data
     * 
     */
    public function ajax_refresh_bunny_data(){

        check_ajax_referer( '_wpnonce' );

        if( ! isset( $_POST['attachment_id'] ) ){
            wp_send_json_error( new WP_Error(
                'attachment_not_found',
                esc_html__( 'Attachment ID was not found', 'streamtube-core' )
            ) );
        }

        if( ! current_user_can( 'edit_post', $_POST['attachment_id'] ) ){
            wp_send_json_error( new WP_Error(
                'no_permission',
                esc_html__( 'You do not have permission to process this action.', 'streamtube-core' )
            ) );
        }

        $response = $this->update_video_data( $_POST['attachment_id'] );

        if( is_wp_error( $response ) ){
            wp_send_json_error( $response );
        }

        wp_send_json_success( $response );
    }

    /**
     *
     * AJAX sync
     * 
     * @since 2.1
     */
    public function ajax_sync(){

        if( ! isset( $_POST ) || ! isset( $_POST['attachment_id'] ) ){
            wp_send_json_error( new WP_Error(
                'invalid_requested',
                esc_html__( 'Invalid Requested', 'streamtube-core' )
            ) );
        }    

        $results = $this->sync_video( $_POST['attachment_id'] );

        if( is_wp_error( $results ) ){
            wp_send_json_error( $results );
        }

        wp_send_json_success( array(
            'results'   =>  $results,
            'message'   =>  esc_html__( 'Syncing', 'streamtube-core' )
        ) );
    }

    /**
     *
     * AJAX retry sync
     * 
     * @since 2.1
     */
    public function ajax_retry_sync(){

        if( ! isset( $_POST ) || ! isset( $_POST['attachment_id'] ) ){
            exit;
        }

        $results = $this->retry_sync_video( $_POST['attachment_id'] );

        if( is_wp_error( $results ) ){
            wp_send_json_error( $results );
        }

        wp_send_json_success( array(
            'results'   =>  $results,
            'message'   =>  esc_html__( 'Syncing', 'streamtube-core' )
        ) );
    }  

    /**
     *
     * Bulk sync
     * 
     * @param  array $post_ids $attachment_ids
     *
     * @return array $queued array of queued post ids
     *
     * @since 2.1
     * 
     */
    public function bulk_media_sync( $post_ids = array() ){

        $sync_types = $this->get_sync_types();

        if( ! $post_ids ){
            return new WP_Error(
                'empty_posts',
                esc_html__( 'Empty Posts', 'streamtube-core' )
            );
        }

        if( $this->settings['sync_type'] == 'php_curl' ){
            return new WP_Error(
                'php_curl_not_supported',
                sprintf(
                    esc_html__( 'Bulk Sync does not support %s type', 'streamtube-core' ),
                    $sync_types[$this->settings['sync_type']]
                )
            );
        }

        $queued = array();

        foreach ( $post_ids as $post_id ) {
            $_queue = $this->_add_attachment( $post_id );

            if( ! is_wp_error( $_queue ) ){
                $queued[] = $post_id;
            }
        }

        return $queued;
    }

    /**
     *
     * Filter attachment URL
     * 
     * @param  string $url
     * @param  int $post_id
     * @return string
     *
     * @since 2.1
     * 
     */
    public function filter_wp_get_attachment_url( $url, $post_id ){

        if( ! $this->is_enabled() ){
            return $url;
        }        

        if( get_post_type( get_post_parent( $post_id ) ) == 'ad_tag' ){
            return $url;
        }

        $maybe_remote_source = get_post_meta( $post_id, '_wp_attached_file', true );

        if( wp_http_validate_url( $maybe_remote_source ) ){
            $url = $maybe_remote_source;
        }

        $videoId = $this->get_video_guid( $post_id );

        if( ! $videoId ){
            return $url;
        }

        if( wp_attachment_is( 'audio', $post_id ) ){
            return $this->bunnyAPI->get_direct_file_url( $videoId );
        }

        do_action( 'streamtube/core/bunnycdn/attachment_url_filtered', $videoId,  $url, $post_id );

        return $this->bunnyAPI->get_video_hls_url( $videoId );
    }

    /**
     *
     * Display the transcoding progress in case Bunny player is enabled
     * 
     * @param  string|int $attachment_id
     * @return string
     * 
     */
    public function get_transcoding_progress( $attachment_id ){

        $output = '';

        $response = $this->get_video_status( $attachment_id );

        error_log( $response->get_error_message() );

        if( is_wp_error( $response ) ){

            $error_data = $response->get_error_data();

            $status     = $error_data['status'];
            $progress   = $error_data['progress'];
            $message    = $response->get_error_message();
            $code       = $response->get_error_code();

            $spinner    = true;

            // The video has finished processing but failed.
            if( in_array( (int)$status, array( 5, -1 ) ) ){
                $spinner = false;
            }

            $args = compact( 'code', 'message', 'spinner', 'attachment_id', 'progress', 'status' );

            ob_start();

            /**
             *
             * Filter the output args
             * 
             * @since 2.1
             */
            $args = apply_filters( 'streamtube/core/bunnycdn/video_player_status', $args );

            load_template( 
                plugin_dir_path( __FILE__ ) . 'frontend/video-status.php', 
                true, 
                $args 
            );

            $output = ob_get_clean();
        }

        if( $output ){
            return $output;
        }

        return $this->get_iframe( $this->get_video_guid( $attachment_id ) );
    }

    /**
     *
     * Filter player setup
     *
     */
    public function filter_player_setup( $setup, $source ){

        if( ! $this->is_enabled() || ! $this->get_video_guid( $source ) ){
            return $setup;
        }

        $playerLoadSource = array();

        $video_data = wp_parse_args( (array)get_post_meta( $source, '_bunnycdn', true ), array(
            'encodeProgress'        =>  0,
            'availableResolutions'  =>  ''
        ) );     
        
        if( ! $video_data['availableResolutions'] ){
            $playerLoadSource = array(
                'message'   =>  esc_html__( 'Waiting ...', 'streamtube-core' )
            );
        }   

        if( $playerLoadSource ){
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
     * 
     */
    public function filter_player_load_source( $source, $post_id, $data = array() ){

        $attachment_id  = get_post_meta( $post_id, 'video_url', true );

        $videoId = $this->get_video_guid( $attachment_id );

        if( ! $this->is_enabled() || ! $videoId ){
            return $source;
        }

        $src = '';

        $status = $this->get_video_status( $attachment_id );

        if( is_wp_error( $status ) ){
            return $status;
        }else{
            $src = wp_get_attachment_url( $attachment_id );
        }

        if( $src ){ 
            return array(
                'type'  =>  'application/x-mpegURL',
                'src'   =>  $src
            ); 
        }

        return $source;
    }    

    /**
     *
     * Filter player output
     *
     * @since 2.1
     * 
     */
    public function filter_player_output( $player, $setup, $source ){

        if( ! $this->is_enabled() || ! $this->settings['bunny_player'] ){
            return $player;
        }

        if( "" == ($videoId = $this->get_video_guid( $source )) ){
            return $player;
        }

        $video_data = wp_parse_args( (array)get_post_meta( $source, '_bunnycdn', true ), array(
            'encodeProgress'        =>  0,
            'availableResolutions'  =>  ''
        ) );

        if( ! $video_data['availableResolutions']  ){
            return $this->get_transcoding_progress( $source );
        }

        return $this->get_iframe( $videoId );
    }

    public function filter_download_file_url( $url, $post_id ){

        if( ! $this->is_enabled() ){
            return $url;
        }

        $mediaid = $this->Post->get_source( $post_id );

        if( wp_attachment_is( 'video', $mediaid ) || wp_attachment_is( 'audio', $mediaid ) ){
            $url = $this->get_downloadable_url( $mediaid );
        }

        return $url;
    }

    /**
     *
     * Display the notice within thumbnail field on the Edit Post form
     * 
     */
    public function thumbnail_notice( $post ){
        if( ! has_post_thumbnail( $post ) ){

            $Post = new Streamtube_Core_Post();

            if( $this->get_video_guid( $Post->get_source( $post->ID ) ) ){
                load_template( untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/frontend/process-thumbnail.php' );    
            }
        }
    }

    /**
     *
     * Generate thumbnail image
     * 
     * @param  int $attachment_id
     * @param  string $videoId
     * @return WP_Error|int
     *
     * @since 2.1
     * 
     */
    public function generate_thumbnail_image( $attachment_id, $videoId = '' ){

        if( has_post_thumbnail( $attachment_id ) ){
            return new WP_Error(
                'thumbnail_exists',
                esc_html__( 'Thumbnail Image is already existed', 'streamtube-core' )
            );
        }

        if( ! $videoId ){
            $videoId = $this->get_video_guid( $attachment_id );    
        }

        if( ! $videoId ){
            return new WP_Error(
                'videoId_not_found',
                esc_html__( 'VideoId was not found', 'streamtube-core' )
            );
        }

        $thumbnail_url = $this->bunnyAPI->get_video_thumbnail_url( $videoId );

        $thumbnail_id = media_sideload_image( $thumbnail_url, $attachment_id, null, 'id' );

        if( ! is_wp_error(  $thumbnail_id ) ){

            set_post_thumbnail( $attachment_id, $thumbnail_id );

            wp_update_post( array(
                'ID'            =>  $thumbnail_id,
                'post_parent'   =>  $attachment_id,
                'post_author'   =>  get_post( $attachment_id )->post_author
            ) );

            $attachment = get_post( $attachment_id );

            if( $attachment->post_parent && ! has_post_thumbnail( $attachment->post_parent ) ){
                set_post_thumbnail( $attachment->post_parent, $thumbnail_id );
            }
        }

        return $thumbnail_id;
    }

    /**
     *
     * Generate webp image
     * 
     * @param  int $post_id
     * @param  string $videoId
     * @return WP_Error|int
     *
     * @since 2.1
     * 
     */
    public function generate_webp_image( $attachment_id, $videoId = '' ){

        if( $this->Post->get_thumbnail_image_url_2( $attachment_id ) != "" ){
            return new WP_Error(
                'webp_exists',
                esc_html__( 'WebP Image is already existed', 'streamtube-core' )
            );
        }

        if( ! $videoId ){
            $videoId = $this->get_video_guid( $attachment_id );    
        }

        if( ! $videoId ){
            return new WP_Error(
                'videoId_not_found',
                esc_html__( 'VideoId was not found', 'streamtube-core' )
            );
        }        

        $webp_url = $this->bunnyAPI->get_video_preview_webp_url( $videoId );

        $webp_id = media_sideload_image( $webp_url, $attachment_id, null, 'id' );

        if( ! is_wp_error(  $webp_id ) ){

            $this->Post->update_thumbnail_image_url_2( $attachment_id, $webp_id );

            $attachment = get_post( $attachment_id );

            if( $attachment->post_parent ){
                $this->Post->update_thumbnail_image_url_2( $attachment->post_parent, $webp_id );
            }

            wp_update_post( array(
                'ID'            =>  $webp_id,
                'post_parent'   =>  $attachment_id,
                'post_author'   =>  $attachment->post_author
            ) );
        }

        return $webp_id;
    }

    /**
     *
     * Generate thumbnail images
     * 
     * @param  int $post_id
     * @param  array $data webhook response
     *
     * @since 2.1
     * 
     */
    public function update_thumbnail_images( $attachment_id, $webhook_data, $video_data = array() ){

        if( in_array( $webhook_data['Status'] , array( 3, 4 )) ){

            if( $this->settings['auto_import_thumbnail'] ){
                $this->generate_thumbnail_image( $attachment_id, $webhook_data['VideoGuid'] );
            }

            if( $this->settings['animation_image'] ){
                $this->generate_webp_image( $attachment_id, $webhook_data['VideoGuid'] );
            }
        }
    }

    /**
     *
     * Filter webp image URL
     * 
     * @param  string $image_url
     * @param  int $post_id
     * @return $image_url
     *
     * @since 2.1.10
     * 
     */
    public function filter_thumbnail_image_2( $image_url, $image_id, $post_id ){

        if( ! $this->is_enabled() || ! empty( $image_url ) ){
            return $image_url;
        }

         if( ! apply_filters( 'streamtube/core/bunnycdn/load_webp', true ) ){
            return $image_url;
         }

         $attachment_id = get_post_meta( $post_id, 'video_url', true );

         if( ! wp_attachment_is( 'video', $attachment_id ) ){
            return $image_url;
         }

         return $this->bunnyAPI->get_video_preview_webp_url( $this->get_video_guid( $attachment_id ));
    }

    /**
     *
     * Delete orignial file
     * 
     * @param  int $post_id
     * @param  array $data webhook response
     *
     * @since 2.1
     * 
     */
    public function delete_original_file( $attachment_id, $webhook_data, $video_data = array() ){

        if( in_array( $webhook_data['Status'] , array( 3, 4 ) ) ){

            $attachment = get_post( $attachment_id );

            if( get_post_type( get_post_parent( $attachment ) ) != 'ad_tag' && wp_validate_boolean( $this->settings['delete_original'] ) ){
                $this->delete_attachment_file( $attachment_id );
            }
        }
    }

    public function ajax_read_log_content(){
        $attachment_id = isset( $_GET['attachment_id'] ) ? (int)$_GET['attachment_id'] : 0;

        if( ! $attachment_id || ! Streamtube_Core_Permission::moderate_cdn_sync() ){
            exit;
        }

        $log_content = $this->bunnyAPI->read_log_file( get_attached_file( $attachment_id ) );

        if( ! $log_content ){
            esc_html_e( 'No log content available', 'streamtube-core' );

        }else{
            printf(
                '<pre>%s</pre>',
                $log_content
            );
        }
        exit;
    }

    /**
     *
     * AJAX view log file content
     * 
     * @since 2.1
     */
    public function ajax_read_task_log_content(){

        $task_id = isset( $_GET['task_id'] ) ? (int)$_GET['task_id'] : -1;

        if( $task_id == -1 || ! Streamtube_Core_Permission::moderate_cdn_sync() ){
            exit;
        }        

        $log_content = $this->bunnyAPI->read_task_log_content( $task_id );

        if( $log_content ){
            printf(
                '<pre>%s</pre>',
                $log_content
            );
        }else{
            esc_html_e( 'No log content', 'streamtube-core' );
        }
        exit;
    }

    /**
     *
     * Try to update video data after updating post
     *
     * Hooked into "edit_post_video"
     *
     * @param int $post_id video post ID
     * 
     */
    public function refresh_bunny_data( $post_id ){

        if( ! $this->is_enabled() ){
            return;
        }

        $maybe_attachment_id = (int)get_post_meta( $post_id, 'video_url', true );

        if( $maybe_attachment_id ){
            return $this->update_video_data( $maybe_attachment_id );
        } 
    }

    /**
     *
     * Auto publish video after encoding successfully
     * 
     * @param  $post
     * @param  array $data webhook response
     *
     * @since 2.1
     * 
     */
    public function auto_publish_after_success_encoding( $attachment_id, $webhook_data, $video_data = array() ){

        $video_data = wp_parse_args( $video_data, array(
            'encodeProgress'        =>  '',
            'availableResolutions'  =>  ''
        ) );

        if( in_array( $webhook_data['Status'] , array( 3, 4 ) ) && ((int)$video_data['encodeProgress'] == 100 || $video_data['availableResolutions'] )){

            $attachment = get_post( $attachment_id );

            if( $attachment->post_parent ){
            
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

            }

            /**
             *
             * Fires after publishing video
             *
             * @param  int $post_id video id
             * @param  array $webhook_data webhook response
             *
             * @since 2.1
             * 
             */
            do_action( 'streamtube/core/bunnycdn/auto_publish', $attachment->post_parent, $webhook_data, $video_data );
        }
    }

    /**
     *
     * Auto send notify to author after encoding failed
     * 
     * @param  $attachment_id
     * @param  array $data webhook response
     *
     * @since 2.1
     * 
     */
    public function notify_author_after_encoding_failed( $attachment_id, $webhook_data, $video_data = array() ){
        if( $webhook_data['Status'] == 5 ){
            streamtube_core_notify_author_after_video_encoding_failed( $attachment_id, array(
                'subject'   =>  trim( $this->settings['author_notify_fail_subject'] ),
                'content'   =>  trim( $this->settings['author_notify_fail_content'] )
            ) );
        }
    }

    /**
     *
     * Update user collection if user updated
     *
     * @since 2.1.1
     */
    public function update_user_collection( $user_id, $old_user_data, $userdata ){

        if( ! $this->is_enabled() ){
            return $user_id;
        }

        $collectionId = $this->get_collection_id( $user_id );
        $display_name = get_userdata( $user_id )->display_name;

        if( ! $collectionId || ! $display_name ){
            return $user_id;
        }

        return $this->bunnyAPI->update_collection( $collectionId, $display_name );
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

        if( ! empty( $this->settings['allow_formats'] ) ){
            $_allow_formats = array_map( 'trim', explode(',', $this->settings['allow_formats'] ) );

            if( is_array( $_allow_formats ) ){
                $allow_formats = array_merge( $allow_formats, $_allow_formats );

                $allow_formats = array_values( array_unique( $allow_formats ) );
            }
        }

        return $allow_formats;
    }

    /**
     *
     * Filter the better messages meta
     * 
     */
    public function filter_better_messages_rest_message_meta( $meta, $message_id, $thread_id, $content ){

        if( ! array_key_exists( 'files', $meta ) ){
            return $meta;
        }

        if( is_array( $meta['files'] ) ){
            $files = $meta['files'];

            if( ! is_array( $files ) ){
                return $meta;
            }

            for ( $i = 0;  $i <  count( $files ); $i++)  { 

                if( wp_attachment_is( 'video', $files[$i]['id'] ) || wp_attachment_is( 'audio', $files[$i]['id'] ) ){

                    if( false != $videoId = $this->get_video_guid( $files[$i]['id'] ) ){
                        $files[$i]['url'] = $this->get_downloadable_url( $files[$i]['id'] );
                    }
                }

            }

            $meta['files'] = $files;
        }

        return $meta;        
    }

    /**
     *
     * Process webhook data
     * 
     * @since 2.1
     * 
     */
    public function _webhook_callback( $data ){
        $data       = wp_parse_args( json_decode( $data, true ), array(
            'VideoLibraryId'    =>  '',
            'VideoGuid'         =>  '',
            'Status'            =>  0
        ) );

        $statuses   = $this->bunnyAPI->get_webhook_video_statuses();

        $data['Status'] = (int)$data['Status'];

        $attachment_id = $this->get_post_id_from_videoId( $data['VideoGuid'] );

        if( $attachment_id ){

            $file = get_attached_file( $attachment_id );

            update_post_meta( $attachment_id, '_bunnycdn_status', $data['Status'] );
            
            // Get and update video data
            $video_data = $this->bunnyAPI->get_video( $data['VideoGuid'] );

            if( ! is_wp_error( $video_data ) ){
                update_post_meta( $attachment_id, '_bunnycdn', $video_data );
            }

            // Update log
            if( $this->settings['tsp'] ){
                $this->bunnyAPI->write_log_file( $file );
            }

            $this->bunnyAPI->write_log_file( 
                $file, 
                json_encode( $data ),  
                sprintf( 
                    esc_html__( 'Webhook Request Status %s', 'streamtube-core' ),
                    $statuses[ $data['Status'] ][0]
                )
            );               

            /**
             *
             * Fires once webhook updated
             *
             * @param $attachment_id
             * @param array $data
             * @param array $video_data
             *
             * @since 2.1
             * 
             */
            do_action( 'streamtube/core/bunny/webhook/update', $attachment_id, $data, $video_data );
        }
    }    

    /**
     *
     * Process webhook data
     * 
     * @since 2.1
     * 
     */
    public function webhook_callback(){
        $request = wp_parse_args( $_GET, array(
            'webhook'   =>  '',
            'key'       =>  ''
        ) );

        if( $request['webhook'] != 'bunnycdn' || $request['key'] != $this->settings['webhook_key'] ){
            return;
        }

        if( ! $this->is_enabled() ){
            wp_send_json_error( 'Not Enabled' );
        }

        $data = file_get_contents("php://input");

        if( $data ){
            $this->_webhook_callback( $data );
        }

        wp_send_json_success( 'Webhook' );

    }

    /**
     *
     * The Video table
     *
     * @since 2.1
     * 
     */
    public function admin_post_table( $columns ){

        if( ! $this->is_enabled() ){
            return $columns;
        }

        unset( $columns['date'] );

        $new_columns = array();

        if( Streamtube_Core_Permission::moderate_cdn_sync() && $this->is_enabled() ){
            $new_columns['bunnycdn_sync'] = esc_html__( 'Bunny Stream', 'streamtube-core' );
        }

        $new_columns['date'] = esc_html__( 'Date', 'streamtube-core' );

        return array_merge( $columns, $new_columns );
    }

    /**
     *
     * The Video table
     *
     * @since 2.1
     * 
     */
    public function admin_post_table_columns( $column, $post_id ){

        switch ( $column ) {

            case 'bunnycdn_sync':
                $attachment_id = get_post_meta( $post_id, 'video_url', true );

                if( wp_attachment_is( 'video', $attachment_id ) || wp_attachment_is( 'audio', $attachment_id ) ){
                    load_template( 
                        plugin_dir_path( __FILE__ ) . 'admin/sync-control.php', 
                        false, 
                        compact( 'attachment_id' )
                    );
                }
            break;
            
        }                    
    }    

    /**
     *
     * The media table
     * 
     * @since  2.1
     * 
     */
    public function admin_media_table( $columns ){

        if( ! $this->is_enabled() ){
            return $columns;
        }        

        unset( $columns['date'] );

        $new_columns = array();

        if( Streamtube_Core_Permission::moderate_cdn_sync() && $this->is_enabled() ){
            $new_columns['bunnycdn_sync'] = esc_html__( 'Bunny Stream', 'streamtube-core' );
        }       

        $new_columns['date'] = esc_html__( 'Date', 'streamtube-core' );

        return array_merge( $columns, $new_columns );
    }

    /**
     *
     * The media table
     * 
     * @since  2.1
     * 
     */
    public function admin_media_table_columns( $column, $post_id ){

        switch ( $column ) {

            case 'bunnycdn_sync':

                $attachment_id = $post_id;

                if( wp_attachment_is( 'video', $attachment_id ) || wp_attachment_is( 'audio', $attachment_id ) ){
                    load_template( 
                        plugin_dir_path( __FILE__ ) . 'admin/sync-control.php', 
                        false, 
                        compact( 'attachment_id' )
                    );
                }
            break;

        }
    }    

    /**
     *
     * Add Bulk actions
     * 
     * @return array
     *
     * @since 2.1
     * 
     */
    public function admin_bulk_actions( $bulk_actions ){

        if( ! $this->is_enabled() ){
            return $bulk_actions;
        }        

        $bulk_actions = array_merge( $bulk_actions, array(
            'bulk_bunnycdn_sync'                    =>  esc_html__( 'Bunny Stream Sync', 'streamtube-core' ),
            'bulk_bunnycdn_generate_image'          =>  esc_html__( 'Bunny Stream Generate Thumbnail Image', 'streamtube-core' ),
            'bulk_bunnycdn_generate_webp_image'     =>  esc_html__( 'Bunny Stream Generate WebP Image', 'streamtube-core' )
        ) );

        return $bulk_actions;
    }    

    /**
     *
     * Bulk actions handler
     * 
     * @param  string $redirect_url
     * @param  string $action
     * @param  int $post_ids
     *
     * @since 2.1
     * 
     */
    public function admin_handle_bulk_actions( $redirect_url, $action, $post_ids ){

        if( ! $this->settings['is_connected'] ){
            return $redirect_url;
        }

        $queued     = array();

        $_post_ids  = array();

        foreach ( $post_ids as $post_id ) {
            if( get_post_type( $post_id ) == 'video' ){
                $post_id = get_post_meta( $post_id, 'video_url', true );
            }

            $_post_ids[] = $post_id;
        }

        switch ( $action ) {
            case 'bulk_bunnycdn_sync':

                $is_bulk_sync_supported = $this->is_bulk_sync_supported();

                for ( $i = 0; $i < count( $_post_ids ); $i++ ) { 

                    if( $is_bulk_sync_supported ){

                        $result = $this->retry_sync_video( $_post_ids[$i] );

                        if( ! is_wp_error( $result ) ){
                            $queued[] = $_post_ids[$i];
                        }
                    }
                }

                if( count( $queued ) > 0 ){
                    $redirect_url   = add_query_arg( array(
                        $action     => count( $queued )
                    ), $redirect_url);    
                }

                if( ! $is_bulk_sync_supported ){
                    $redirect_url   = add_query_arg( array(
                        $action     => 'bulk_sync_not_supported',
                        'ref'       =>  'php_curl'
                    ), $redirect_url);
                }

            break;

            case 'bulk_bunnycdn_generate_image':
                for ( $i=0; $i < count( $_post_ids ); $i++) {
                    $result = $this->generate_thumbnail_image( $_post_ids[$i] );

                    if( ! is_wp_error( $result ) ){
                        $queued[] = $_post_ids[$i];
                    }                    
                }

                if( count( $queued ) > 0 ){
                    $redirect_url   = add_query_arg( array(
                        $action     => count( $queued )
                    ), $redirect_url);
                }                
            break;

            case 'bulk_bunnycdn_generate_webp_image':
                for ( $i=0; $i < count( $_post_ids ); $i++) {
                    $result = $this->generate_webp_image( $_post_ids[$i] );

                    if( ! is_wp_error( $result ) ){
                        $queued[] = $_post_ids[$i];
                    }                    
                }

                if( count( $queued ) > 0 ){
                    $redirect_url   = add_query_arg( array(
                        $action     => count( $queued )
                    ), $redirect_url);
                }
            break;            
        }

        return $redirect_url;
    }

    /**
     *
     * Show admin notice 
     * 
     * @since 2.1
     */
    public function admin_handle_bulk_admin_notices(){
        if( isset( $_REQUEST['bulk_bunnycdn_sync'] ) ){

            if( $_REQUEST['bulk_bunnycdn_sync'] == 'bulk_sync_not_supported' ){
                printf(
                    '<div class="notice notice-error"><p>%s</p></div>',
                    sprintf(
                        esc_html__( 'Bulk Sync is not supported since you have selected %s Sync Type from %s page', 'streamtube-core' ),
                        '<strong>'. esc_html__( 'PHP Curl', 'streamtube-core' ) .'</strong>',
                        '<strong><a href="'. esc_url( admin_url( 'options-general.php?page=sync-bunnycdn' ) ) .'">'. esc_html__( 'Settings', 'streamtube-core' ) .'</a></strong>',
                    )
                );
            }
            else{
                echo '<div class="notice notice-success"><p>';
                    $count = (int)$_REQUEST['bulk_bunnycdn_sync'];
                    printf( 
                        _n( 
                            '%s has been queued for syncing onto Bunny CDN', 
                            '%s have been queued for syncing onto Bunny CDN', 
                            $count, 
                            'streamtube-core' 
                        ), 
                        number_format_i18n( $count ) 
                    );
                echo '</p></div>';
            }   
        }
    }

    /**
     *
     * Filter user table
     * 
     * @param  array $columns
     * @return array new $columns
     *
     * @since 2.1
     * 
     */
    public function admin_user_table( $columns ){
        return array_merge( $columns, array(
            'bunnycdn_collection'   =>  esc_html__( 'Bunny Collection', 'streamtube-core' )
        ) );
    }

    /**
     *
     * Filter user table
     * 
     * @param string $output
     * @param string $column_name
     * @param innt $user_id
     *
     * @since 2.1
     * 
     */
    public function admin_user_table_columns( $output, $column_name, $user_id ){

        $output = '';

        switch ( $column_name ) {
            case 'bunnycdn_collection':
                $collection = $this->get_collection( $user_id );

                if( $collection ){
                    foreach ( $collection as $key => $value ) {
                        if( $key != 'previewVideoIds' ){
                            if( ! empty( $value ) ){
                                $output .= sprintf(
                                    '<p><strong>%s</strong>: %s</p>',
                                    $this->bunnyAPI->get_collection_field_name( $key ),
                                    $this->bunnyAPI->get_format_collect_field_value( $key, $value )
                                );
                            }
                        }
                    }
                }
            break;
        }

        return $output;
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
            $thumbnail_id = $this->generate_thumbnail_image( $attachment_id );    
        }

        return $thumbnail_id;
    }

}