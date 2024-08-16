<?php
/**
 * Define the Ad Tag post type functionality
 *
 *
 * @link       https://themeforest.net/user/phpface
 * @since      1.3
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

class Streamtube_Core_Advertising_Ad_Tag{

    /**
     *
     * Holds the option name
     *
     * @since 1.3
     * 
     */
    const OPTION_NAME           = 'ad_tag';

    /**
     *
     * Holds the nonce action and name
     *
     * @since 1.3
     * 
     */
    const NONCE                 = 'ad_tag_nonce';    

    /**
     *
     * Define Ad Tag post type id
     *
     * @since 1.3
     * 
     */
    const CPT_AD_TAG            =  'ad_tag';

    /**
     *
     * Holds the ad servers
     * 
     * @var array
     *
     * @since 1.3
     * 
     */
    public $ad_servers          =   array();

    /**
     *
     * Holds the ad types
     * 
     * @var array
     *
     * @since 1.3
     * 
     */
    public $ad_types            =   array();    

    /**
     *
     * Holds the ad media types
     * 
     * @var array
     *
     * @since 1.3
     * 
     */
    public $ad_media_types      =   array();

    public $ad_image_positions  =   array();

    /**
     *
     * Holds the ad video resolution
     * 
     * @var array
     *
     * @since 1.3
     * 
     */
    public $ad_video_res        =   array();

    public $tracking_events     =   array();

    public function __construct(){

        // Set ad_servers
        $this->ad_servers           =   array(
            'self_ad'       =>  esc_html__( 'Self Ad', 'streamtube-core' ),
            'vast'          =>  esc_html__( 'VAST-compliant Ad Server', 'streamtube-core' )
        );

        // Set ad_types
        $this->ad_types             =   array(
            'linear'        =>  esc_html__( 'Linear', 'streamtube-core' ),
            'nonlinear'     =>  esc_html__( 'NonLinear', 'streamtube-core' )
        );        

        // Set ad_media_types
        $this->ad_media_types       =   array(
            'video'         =>  esc_html__( 'Video', 'streamtube-core' ),
            'image'         =>  esc_html__( 'Image', 'streamtube-core' )
        );

        $this->ad_image_positions   =   array(
            'bottom'        =>  esc_html__( 'Bottom', 'streamtube-core' ),
            'center'        =>  esc_html__( 'Center', 'streamtube-core' )
        );

        // Set ad_video_res
        $this->ad_video_res         =   array(
            'res_360'       =>  esc_html__( 'Video - 360 Resolution', 'streamtube-core' ),
            'res_480'       =>  esc_html__( 'Video - 480 Resolution', 'streamtube-core' ),
            'res_720'       =>  esc_html__( 'Video - 720 (HD) Resolution', 'streamtube-core' )
        );

        $this->tracking_events      = array(
            'start',
            'firstQuartile',
            'midpoint',
            'thirdQuartile',
            'complete',
            'mute',
            'unmute',
            'rewind',
            'pause',
            'resume',
            'creativeView',
            'fullscreen',
            'acceptInvitationLinear',
            'closeLinear',
            'exitFullscreen',
            'skip'
        );
    }

    /**
     *
     * Register ad tag post type
     * 
     */
    public function post_type(){
        $labels = array(
            'name'                                  => esc_html__( 'Ad Tags', 'streamtube-core' ),
            'singular_name'                         => esc_html__( 'Ad Tag', 'streamtube-core' ) 
        );

        $args = array(
            'label'                                 => esc_html__( 'Ad Tag', 'streamtube-core' ),
            'labels'                                => $labels,
            'description'                           => '',
            'public'                                => true,
            'publicly_queryable'                    => true,
            'show_ui'                               => true,
            'show_in_rest'                          => false,
            'rest_base'                             => '',
            'rest_controller_class'                 => 'WP_REST_Posts_Controller',
            'has_archive'                           => false,
            'show_in_menu'                          => Streamtube_Core_Advertising_Admin::ADMIN_MENU_SLUG,
            'show_in_nav_menus'                     => false,
            'delete_with_user'                      => false,
            'exclude_from_search'                   => true,
            'capability_type'                       => 'post',
            'map_meta_cap'                          => true,
            'hierarchical'                          => false,
            'rewrite'                               => array( 
                'slug'          =>  self::CPT_AD_TAG, 
                'with_front'    =>  true 
            ),
            'query_var'                             => true,
            'supports'                              =>  array( 
                'title', 
                'thumbnail',
                'excerpt'
            ),
            'menu_icon'                             =>  'dashicons-welcome-widgets-menus'
        );

        register_post_type( self::CPT_AD_TAG, $args );
    }

    /**
     *
     * Get field name
     * 
     * @param  string $name
     * @return string
     *
     * @since 1.3
     * 
     */
    public function get_field( $name ){
        return sprintf( '%s[%s]', self::OPTION_NAME, $name );
    }    

    /**
    *
    * Convert seconds to video length
    * 
    * @param  int $seconds
    * @return string
    *
    * @since 1.0.0
    * 
    */    
    public function seconds_to_length( $seconds ){

        $seconds = absint( $seconds );

        return gmdate( "H:i:s", $seconds % 86400 );
    }

    /**
     *
     * @see add_meta_box()
     *
     * @since 1.3
     * 
     */
    public function add_meta_boxes(){
        add_meta_box( 
            self::CPT_AD_TAG, 
            esc_html__( 'Ad Content', 'streamtube-core' ), 
            array( $this , 'ad_content_box' ), 
            self::CPT_AD_TAG
        );
    }

    /**
     *
     * Ad Content metabox callback
     *
     * @since 1.3
     * 
     */
    public function ad_content_box( $post ){
        include_once plugin_dir_path( __FILE__ ) . 'admin/ad-tag/ad-content.php';
    }

    /**
     * Save metabox data
     *
     * @since 1.3
     * 
     */
    public function save_ad_content_box( $post_id ){

        if ( ! isset( $_POST[ self::NONCE ] ) || ! wp_verify_nonce( $_POST[ self::NONCE ], self::NONCE ) ){
            return;
        }

        if( ! isset( $_POST[ self::OPTION_NAME ] ) ){
            return;
        }        

        if( ! current_user_can( 'edit_post', $post_id ) ){
            return;
        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        if ( isset( $_POST['post_type'] ) && self::CPT_AD_TAG !== $_POST['post_type'] ) {
            return;
        }

        $data = wp_parse_args( $_POST[ self::OPTION_NAME ], $this->get_default() );

        foreach ( $data as $key => $value ) {

            if( $key == 'ad_adtag_url' ){
                $value = wp_unslash( $value );
            }

            elseif( is_string( $value ) ){
                $value = sanitize_text_field( wp_unslash( $value ) );
            }

            update_post_meta( $post_id, $key, $value );
        }
    }

    /**
     *
     * Get default options values
     * 
     * @return array
     *
     * @since 1.3
     * 
     */
    private function get_default(){
        return array(
            'ad_server'         =>  'self_ad',
            'ad_type'           =>  'linear',
            'ad_target_url'     =>  '',
            'ad_image_id'       =>  '',
            'ad_image_position' =>  'bottom',
            'ad_duration'       =>  '',
            'ad_res_360'        =>  '',
            'ad_res_480'        =>  '',
            'ad_res_720'        =>  '',
            'ad_skipoffset'     =>  '',
            'ad_adtag_url'      =>  ''
        );
    }

    /**
     *
     * Get Ad Tag options
     * 
     * @param  int $post_id
     * @return array
     *
     * @since 1.3
     * 
     */
    public function get_options( $post_id = 0 ){

        $options = array();

        if( ! $post_id ){
            $post_id = get_the_ID();
        }

        foreach ( $this->get_default() as $key => $value ) {

            $options[$key] = get_post_meta( $post_id, $key, true );

            if( empty( $options[$key] ) ){
                $options[$key] = $value;
            }
        }

        return $options;
    }

    /**
     *
     * Check input time offset format: 00:00:00
     * 
     * @return string
     *
     * @since 1.3
     * 
     */
    public function verify_time_offset( $time_offset = '' ){

        $default = '00:00:00';

        if( empty( $time_offset ) ){
            return $default;
        }

        $maybe_seconds = (int)$time_offset;

        if( $maybe_seconds > 0 ){
            return gmdate( "H:i:s", $maybe_seconds % 86400 );
        }

        $explore = explode( ':' , $time_offset );

        if( ! is_array( $explore ) || count( $explore ) != 3 ){
            return $default;
        }

        return gmdate( 'H:i:s', strtotime( $time_offset ) );
    }    

    /**
     *
     * Get Ad media metadata
     * 
     * @param  $attachment_id
     * @return false|array
     *
     * @since 1.3
     * 
     */
    public function get_ad_video_media_metadata( $attachment_id = 0 ){

        $metadata = array();

        if( ! $attachment_id ){
            return $metadata;
        }

        if( ! wp_attachment_is( 'video', $attachment_id ) ){
            return $metadata;
        }

        if( ! function_exists( 'wp_read_video_metadata' ) ){
            require_once( ABSPATH . 'wp-admin/includes/media.php' );
        }        

        $metadata = wp_read_video_metadata( get_attached_file( $attachment_id ) );

        if( is_array( $metadata ) && array_key_exists( 'length', $metadata ) ){
            $metadata['length_formatted'] = $this->seconds_to_length( $metadata['length'] );
        }

        return $metadata;
    }

    /**
     *
     * Get Ad Tag posts
     * 
     * @return get_posts()
     *
     * @since 1.3
     * 
     */
    public function get_ad_tags(){
        $query_args = array(
            'post_type'         =>  self::CPT_AD_TAG,
            'posts_per_page'    =>  -1,
            'post_status'       =>  'publish'
        );

        return get_posts( $query_args );
    }

    /**
     *
     * Get video files
     * 
     * @return array
     *
     * @since 1.3
     * 
     */
    private function get_ad_media_files(){

        $options = $this->get_options();

        $ad_video_files = array();

        $media_files = array( 'ad_res_360', 'ad_res_480', 'ad_res_720' );

        for ( $i=0; $i < count( $media_files ); $i++) {

            if( $options[$media_files[$i]] ){

                $metadata = $this->get_ad_video_media_metadata( $options[ $media_files[$i] ] );

                if( is_array( $metadata ) ){
                    if( array_key_exists( 'bitrate', $metadata ) ){
                        $metadata['bitrate'] = ceil($metadata['bitrate']/10000);    
                    }
                    else{
                        $metadata['bitrate'] = 720;
                    }
                }

                $ad_video_files[ $media_files[$i] ] = array(
                    'id'        =>  $options[ $media_files[$i] ],
                    'url'       =>  wp_get_attachment_url( $options[ $media_files[$i] ] ),
                    'permalink' =>  get_permalink( $media_files[$i] ),
                    'mimetype'  =>  $metadata['mime_type'],
                    'meta'      =>  $metadata
                );
            }
        }
        return $ad_video_files;
    }

    /**
     *
     * Get none Linear params
     * 
     * @return array
     *
     * @since 1.3
     * 
     */
    private function get_ad_nonlinear_media_params(){

        $videos = array();

        $media_files = $this->get_ad_media_files();

        if( $media_files ){
            foreach ( $media_files as $resolution => $value ) {
                $videos[] = array(
                    'url'       =>  $value['url'],
                    'mimetype'  =>  $value['mimetype']
                );
            }
        }

        return $videos;
    }

    /**
     *
     * Get Ad Tag Type
     * 
     * @param  int $ad_tag_id
     * @return string vmap or vast
     *
     * @since 1.3
     * 
     */
    public function get_ad_tag_type( $ad_tag_id ){

        $options = $this->get_options( $ad_tag_id );

        if( wp_http_validate_url( $options['ad_adtag_url'] ) ){

            $response = wp_remote_get( $options['ad_adtag_url'] );

            if( ! is_wp_error( $response ) ){
                $options['ad_adtag_url'] = wp_remote_retrieve_body( $response );
            }
        }

        if( strpos( $options['ad_adtag_url'], '<vmap:VMAP' ) !== false ){
            return 'vmap';
        }

        if( strpos( $options['ad_adtag_url'], '</VAST>' ) !== false ){
            return 'vast';
        }
    }    

    /**
     *
     * Get image data
     * 
     * @return array
     */
    private function get_image_data(){
        $options = $this->get_options();

        $image_id = $options['ad_image_id'];

        if( ! $image_id || ! wp_attachment_is( 'image', $image_id ) ){
            return false;
        }

        return wp_get_attachment_image_src( $image_id, 'full' );
    }

    /**
     *
     * AJAX import vast from external Ad server
     * 
     * @since 1.3
     */
    public function ajax_import_vast(){
        if( ! current_user_can( 'administrator' ) || ! isset( $_POST ) ){
            exit;
        }

        $data = wp_parse_args( $_POST, array(
            'url'       =>  '',
            'post_id'   =>  0
        ) );

        $data['url'] = trim( $data['url'] );

        if( empty( $data['url'] ) || empty( $data['post_id'] ) ){
            wp_send_json_error( esc_html__( 'Invalid Ad Tag or Post ID', 'streamtube-core' ) );
        }

        if( ! wp_http_validate_url( $data['url'] ) ){
             wp_send_json_error( esc_html__( 'It seems you have pasted an invalid Ad tag URL.', 'streamtube-core' ) );
        }

        $response = wp_remote_get( $data['url'] );

        if( is_wp_error( $response ) ){
            wp_send_json_error( $response->get_error_message() );
        }

        $maybe_body = wp_remote_retrieve_body( $response );

        if( empty( $maybe_body ) ){
            wp_send_json_error( esc_html__( 'No Ad content was found, you may open the Adtag URL on browser and copy the Ad content instead.', 'streamtube-core' ) );
        }

        $maybe_body = str_replace( '<?xml version="1.0" encoding="UTF-8"?>', '', $maybe_body );

        wp_send_json_success( array(
            'ad_content'    =>  trim($maybe_body),
            'button'        =>  esc_html__( 'Ad imported', 'streamtube-core' )
        ) );
    }

    /**
     * @since 1.3
     */
    public function load_mp4_url(){
        if( function_exists( 'wp_video_encoder' ) ){
            remove_filter(
                'wp_get_attachment_url',
                array( $GLOBALS['wp_video_encoder']->get()->post, 'filter_get_attachment_url' ),
                100
            );
        }
    }    

    /**
     * Template controller
     *
     * @since 1.3
     * 
     */
    public function template_redirect(){
        if ( is_singular( self::CPT_AD_TAG ) ){
            if( isset( $_GET['action'] ) && $_GET['action'] = 'tracking_event' && isset( $_GET['event'] )){
                $this->load_tracking_template();
            }else{
                $this->load_vast_template();
            }
        }
    }

    /**
     *
     * The Event Tracking template
     * 
     * @since 1.4
     */
    private function load_tracking_template(){
        do_action( 'streamtube/core/advertising/tracking_events' );
    }

    /**
     *
     * The Vast template
     * 
     * @since 1.3
     * 
     */
    private function load_vast_template(){ 
        $options = $this->get_options();

        $args = array(
            'ad_tag_id'             =>  get_the_ID(),
            'ad_system'             =>  get_bloginfo( 'name' ),
            'ad_title'              =>  get_the_title(),
            'ad_description'        =>  get_the_excerpt(),
        );

        switch ( $options['ad_server'] ) {
            case 'self_ad':

            $this->load_mp4_url();

                $args = array_merge( $args, array(
                    'ad_target_url'         =>  $options['ad_target_url'],
                    'ad_type'               =>  $options['ad_type'],
                    'ad_duration'           =>  $options['ad_duration'],
                    'ad_media_files'        =>  $this->get_ad_media_files(),
                    'ad_video_duration'     =>  '',
                    'ad_skipoffset'         =>  $this->verify_time_offset( $options['ad_skipoffset'] ),
                    'ad_params'             =>  array(),
                    'ad_image_data'         =>  false,
                    'tracking_events'       =>  $this->tracking_events
                ) );

                if( $args['ad_type'] == 'nonlinear' ){

                    if( $options['ad_image_id'] ){

                        $image_data = $this->get_image_data();

                        $args['ad_params']['image'] = array(
                            'url'       =>  $image_data[0],
                            'width'     =>  $image_data[1],
                            'height'     =>  $image_data[2],
                            'position'  =>  $options['ad_image_position']
                        );
                    }

                    if( $args['ad_media_files'] ){
                        $args['ad_params']['videos'] = $this->get_ad_nonlinear_media_params();
                    }

                    $args['scripts_url'] = add_query_arg( array(
                        'version'   =>  filemtime( plugin_dir_path( __FILE__ ) . 'public/VpaidNonLinear.js' )
                    ), plugin_dir_url( __FILE__ ) . 'public/VpaidNonLinear.js' );
                }

                load_template( 
                    plugin_dir_path( __FILE__ ) . sprintf( 'public/self-ad-%s.php', $args['ad_type'] ),
                    true,
                    $args
                );

            break;
            
            case 'vast':

                $args = array_merge( $args, array(
                    'ad_adtag_url'  =>  $options['ad_adtag_url']
                ) );

                load_template( 
                    plugin_dir_path( __FILE__ ) . 'public/vast_compliant_ad_server.php',
                    true,
                    $args
                );
            break;
        }

        exit;
    }

    /**
     * Add custom fields to the Ad Tag table
     *
     * @param array $columns
     *
     * @since 1.3
     * 
     */
    public function admin_post_table( $columns ){

        unset( $columns['date'] );

        $new_columns = array(
            'ad_server'         =>  esc_html__( 'Server', 'streamtube-core' ),
            'ad_target_url'     =>  esc_html__( 'Target URL', 'streamtube-core' ),
            'ad_type'           =>  esc_html__( 'Ad Type', 'streamtube-core' ),
            'ad_skipoffset'     =>  esc_html__( 'Skippable', 'streamtube-core' ),
            'date'              =>  esc_html__( 'Date', 'streamtube-core' )
        );

        return array_merge( $columns, $new_columns );
    }    

    /**
     *
     * Custom Columns callback
     * 
     * @param  string $column
     * @param  int $post_id
     *
     * @since 1.3
     * 
     */
    public function admin_post_table_columns( $column, $post_id ){

        $options = $this->get_options( $post_id );

        switch ( $column ) {

            case 'ad_server':
                if( $options['ad_server'] && array_key_exists( $options['ad_server'], $this->ad_servers ) ){

                    $ad_tag_type = $options['ad_server'] == 'self_ad' ? 'vast' : $this->get_ad_tag_type( $post_id );

                    printf(
                        '%s (%s)',
                        $this->ad_servers[ $options['ad_server'] ],
                        '<strong>'. strtoupper( $ad_tag_type ) .'</strong>'
                    );
                }
                
            break;

            case 'ad_target_url':
                if( ! empty( $options['ad_target_url'] ) ){
                    printf(
                        '<a target="_blank" href="%s">%s</a>',
                        esc_url( $options['ad_target_url'] ),
                        esc_html__( 'Open URL', 'streamtube-core' )
                    );
                }
            break;

            case 'ad_type':
                echo $this->ad_types[ $options['ad_type'] ];
            break;

            case 'ad_skipoffset':
                if( $options['ad_skipoffset'] ){
                    echo $this->verify_time_offset( $options['ad_skipoffset'] );
                }
            break;

        }
    }    
}