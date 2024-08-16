<?php
/**
 * Define the Ad Schedule functionality
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

class Streamtube_Core_Advertising_Ad_Schedule{

    /**
     *
     * Holds the option name
     *
     * @since 1.3
     * 
     */
    const OPTION_NAME        = 'ad_schedule';

    /**
     *
     * Holds the nonce action and name
     *
     * @since 1.3
     * 
     */
    const NONCE              = 'ad_schedule_nonce';

    /**
     *
     * Define Ad Schedule post type id
     *
     * @since 1.3
     * 
     */
    const CPT_AD_SCHEDULE   = 'ad_schedule';

    const VMAP_URL          = 'http://www.iab.net/videosuite/vmap';

    const VMAP_VERSION      = '1.0';

    /**
     *
     * Holds the placement
     * 
     * @var array
     */
    public $placement       = array();

    protected $post;

    /**
     *
     * Class contructor
     *
     * @since 1.3
     * 
     */
    public function __construct(){
        $this->placement = array(
            'preroll'       =>  esc_html__( 'Pre-roll', 'streamtube-core' ),
            'midroll'       =>  esc_html__( 'Mid-roll', 'streamtube-core' ),
            'postroll'      =>  esc_html__( 'Post-roll', 'streamtube-core' )
        );

        $this->post = new Streamtube_Core_Post();
    }

    /**
     *
     * Register ad tag post type
     * 
     */
    public function post_type(){
        $labels = array(
            'name'                                  => esc_html__( 'Ad Schedules', 'streamtube-core' ),
            'singular_name'                         => esc_html__( 'Ad Schedule', 'streamtube-core' ) 
        );

        $args = array(
            'label'                                 => esc_html__( 'Ad Schedule', 'streamtube-core' ),
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
                'slug'          =>  self::CPT_AD_SCHEDULE, 
                'with_front'    =>  true 
            ),
            'query_var'                             => true,
            'supports'                              =>  array( 
                'title'
            ),
            'menu_icon'                             =>  'dashicons-welcome-widgets-menus'
        );

        register_post_type( self::CPT_AD_SCHEDULE, $args );
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
     * @see add_meta_box()
     *
     * @since 1.3
     * 
     */
    public function add_meta_boxes(){
        add_meta_box( 
            self::CPT_AD_SCHEDULE, 
            esc_html__( 'Ad Tags', 'streamtube-core' ), 
            array( $this , 'ad_tags_box' ), 
            self::CPT_AD_SCHEDULE
        ); 

        $taxonomies = get_object_taxonomies( 'video', 'object' );

        foreach ( $taxonomies as $tax => $object ){
            add_meta_box( 
                self::CPT_AD_SCHEDULE . '_' . $tax, 
                sprintf(
                    esc_html__( 'Apply To %s', 'streamtube-core' ),
                    $object->label
                ), 
                array( $this , 'ad_taxonomy_box' ), 
                self::CPT_AD_SCHEDULE,
                'side',
                'core',
                compact( 'tax' )
            );
        }
     
    }

    /**
     *
     * Ad Tags metabox template
     * 
     * @param  object $post
     *
     * @since 1.3
     * 
     */
    public function ad_tags_box( $post ){
        load_template( plugin_dir_path( __FILE__ ) . 'admin/ad-schedule/ad-tags.php' );
    }

    /**
     *
     * Ad Categories metabox template
     * 
     * @param  object $post
     *
     * @since 1.3
     * 
     */
    public function ad_taxonomy_box( $post, $args ){
        load_template( plugin_dir_path( __FILE__ ) . 'admin/ad-schedule/ad-taxonomy.php', false, $args['args'] );
    }

    /**
     *
     * AJAX get custom taxonomy terms
     * 
     * @since 1.3
     */
    public function ajax_get_tax_terms(){

        check_ajax_referer( '_wpnonce' );

        $results = array();

        if( ! current_user_can( 'administrator' ) ){
            exit;
        }

        $data = wp_parse_args( $_GET, array(
            'search'    =>  '',
            'tax'       =>  ''
        ) );

        if( ! $data['search'] || ! $data['tax'] ){
            exit;
        }

        $terms = get_terms( array(
            'taxonomy'      =>  $data['tax'],
            'hide_empty'    =>  false,
            'name__like'    =>  sanitize_text_field( $data['search'] ),
            'number'        =>  20
        ) );

        if( $terms ){
            foreach( $terms as $term ){
                $results[] = array(
                    'id'    =>  $term->slug,
                    'text'  =>  $term->name
                );
            }
        }

        wp_send_json_success( compact( 'results' ) );
    }

    /**
     * Save metabox data
     *
     * @since 1.3
     * 
     */
    public function save_ad_tags_box( $post_id ){

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

        if ( isset( $_POST['post_type'] ) && self::CPT_AD_SCHEDULE !== $_POST['post_type'] ) {
            return;
        }

        $data = wp_parse_args( $_POST[ self::OPTION_NAME ], $this->get_default() );

        foreach ( $data as $key => $value ) {

            if( is_string( $value ) ){
                $value = sanitize_text_field( $value );
            }

            if( ! $value ){
                $value = '';
            }

            update_post_meta( $post_id, $key, $value );
        }
    }

    /**
     *
     * Default options
     * 
     * @return array
     *
     * @since 1.3
     * 
     */
    public function get_default(){
        return array(
            'enable'            =>  '',
            'alias_schedule'    =>  '',
            'ad_tags'           =>  array(),
            'start_date'        =>  '',
            'end_date'          =>  '',
            'videos'            =>  array(),
            'tags'              =>  array(),
            'cache_expiry'      =>  ''
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
            $options[ $key ] = get_post_meta( $post_id, $key, true );
        }        

        return $options;
    }

    /**
     *
     * Check if Ad schedule is enabled
     * 
     * @param  integer $post_id
     * @return boolean
     *
     * @since 1.3
     * 
     */
    public function is_enabled( $post_id = 0 ){

        $options = $this->get_options( $post_id );

        if( $options['enable'] ){
            return true;
        }

        return false;
    }

    /**
     *
     * Check if Ad schedule is active which means RUNNING
     * 
     * @param  integer $post_id
     * @return true|WP_Error
     *
     * @since 1.3
     * 
     */
    public function is_active( $post_id = 0 ){

        // Check if ad is enabled
        if( ! $this->is_enabled( $post_id ) ){
            return new WP_Error( 
                'disabled', 
                esc_html__( 'Disabled', 'streamtube-core' ) 
            );
        }

        // Check ad status
        if( 'publish' != $status = get_post_status( $post_id ) ){
            return new WP_Error( 
                $status, 
                esc_html__( 'Non-Public', 'streamtube-core' )
            );
        }

        // Check start date and end date
        $current        = current_time( 'timestamp' );
        $start_date     = strtotime( $this->get_start_date( $post_id ) );
        $end_date       = strtotime( $this->get_end_date( $post_id ) );

        if( $end_date < $start_date  ){
            return new WP_Error(
                'invalid_datetime',
                esc_html__( 'Invalid Date Times', 'streamtube-core' )
            );
        }

        if( $start_date > $current ){
            return new WP_Error(
                'scheduling',
                esc_html__( 'Scheduling', 'streamtube-core' )
            );            
        }

        if( $end_date < $current ){
            return new WP_Error(
                'expired',
                esc_html__( 'Expired', 'streamtube-core' )
            );
        }

        // Check if Ad Tags not empty
        if( ! $this->get_ad_tags( $post_id ) ){
            return new WP_Error(
                'ad_tags_empty',
                esc_html__( 'No Ad Tags', 'streamtube-core' )
            );            
        }
        
        return true;
    }

    /**
     *
     * Get active Ads
     * 
     * @param  integer $post_id
     * @return false|array
     *
     * @since 1.3
     * 
     */
    public function get_active_ad_schedules( $post_id = 0 ){

        if( $post_id ){
            $maybe_post_ads = $this->post->get_ad_schedules( $post_id );

            if( count( $maybe_post_ads ) > 0 ){
                // Remove inactive ads
                for ( $i=0;  $i < count( $maybe_post_ads );  $i++) { 

                    $is_active = $this->is_active( $maybe_post_ads[ $i ] );

                    if( is_wp_error( $is_active ) ){
                        unset( $maybe_post_ads[ $i ] );
                    }
                }

                // Reset the list
                $maybe_post_ads = array_values( $maybe_post_ads );

                if( count( $maybe_post_ads ) > 0 ){
                    return $maybe_post_ads;
                }
            }
        }

        $ad_schedules = $this->query_ads( array(
            'ref_post_id'   =>  $post_id
        ) );

        if( $ad_schedules ){
            return $ad_schedules = wp_list_pluck( $ad_schedules, 'ID' );
        }

        return false;
    }

    /**
     *
     * Get start date
     * 
     * @param  integer $post_id
     * @return string
     *
     * @since 1.3
     * 
     */
    public function get_start_date( $post_id = 0 ){

        $options = $this->get_options( $post_id );

        return $options['start_date'];
    }

    /**
     *
     * Get end date
     * 
     * @param  integer $post_id
     * @return string
     *
     * @since 1.3
     * 
     */
    public function get_end_date( $post_id = 0 ){

        $options = $this->get_options( $post_id );

        return $options['end_date'];
    }

    /**
     *
     * Get Ad Tags
     * 
     * @param  integer $post_id
     * @return array
     *
     * @since 1.3
     * 
     */
    public function get_ad_tags( $post_id = 0 ){

        $options = $this->get_options( $post_id );

        if( ! is_array( $options['ad_tags'] ) || count( $options['ad_tags'] ) == 0 ){
            return false;
        }

        return $options['ad_tags'];
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
     * Get time offset
     * 
     * @param  array $ad_tag
     * @param  string $placement
     * @return string
     *
     * @since 1.3
     * 
     */
    public function get_time_offset( $ad_tag, $placement = 'preroll' ){

        if( ! array_key_exists( $placement, $this->placement ) ){
            return 'start';
        }

        switch ( $placement ) {
            case 'preroll':
                return 'start';
            break;

            case 'midroll':
                return $this->verify_time_offset( $ad_tag['position'] );
            break;
            
            case 'postroll':
                return 'end';
            break;
        }
    }

    /**
     *
     * Get Ad Tag Type, call HTTP remote to determine the type
     * 
     * @param  int $ad_tag_id
     * @return string vmap or vast
     *
     * @since 1.3
     * 
     */
    public function get_ad_tag_type( $ad_tag_id ){
        
        $response = wp_remote_get( get_permalink( $ad_tag_id ) );

        if( ! is_wp_error( $response ) ){
            $body = wp_remote_retrieve_body( $response );

            if( strpos( $body, '<vmap:VMAP' ) !== false ){
                return 'vmap';
            }
        }

        return 'vast';
    }       

    /**
     *
     * Get Ad Tags by placement
     * 
     * @param  int $post_id
     * @param  string $placement
     * @return array|false
     *
     * @since 1.3
     * 
     */
    public function get_ad_tags_by_placement( $post_id, $placement = 'preroll' ){

        $tags = array();

        $options = $this->get_options( $post_id );

        if( ! is_array( $options ) || ! array_key_exists( 'ad_tags', $options ) ||  ! is_array( $options['ad_tags'] ) ){
            return false;
        }

        if( ! array_key_exists( 'placement', $options['ad_tags'] ) ){
            return false;
        }

        $options = $options['ad_tags'];

        for ( $i=0; $i < count( $options['placement'] ); $i++) { 
            if( $placement && $placement == $options['placement'][$i] ){

                $position = ( $options['placement'][$i] == 'midroll' ) ? trim( $options['position'][$i] ) : '';

                $tag = array(
                    'ad_tag_type'   =>  $this->get_ad_tag_type( $options['id'][$i] ),
                    'ad_tag'        =>  $options['id'][$i]
                );

                if( $options['placement'][$i] == 'midroll' ){
                    $tag['position'] = trim( $options['position'][$i] );
                }

                $tags[] = $tag;
            }
        }

        return $tags;
    }

    /**
     *
     * Get VMAP content
     * 
     * @param  int $ad_tag_id
     * @return string
     *
     * @since 1.3
     * 
     */
    public function get_vmap_content( $ad_tag_id, $filter = true ){

        $content = '';

        $response = wp_remote_get( get_permalink( $ad_tag_id ) );

        if( ! is_wp_error( $response ) ){
            $content = wp_remote_retrieve_body( $response );
            if( $filter ){
                $content = str_replace( '<?xml version="1.0" encoding="UTF-8"?>', '', $content );
                $content = str_replace( array( "\r", "\n" ), '', $content );
                $content = preg_replace( '#<vmap:VMAP (.*?)>(.+)<\/vmap:VMAP>#i', '$2', $content );
            }

            /**
             *
             * Filter VMAP content
             * 
             * @since 1.3
             */
            $content = apply_filters( 'streamtube/advertising/vmap_content_filter', $content, $ad_tag_id );
        }

        return $content;        
    }

    /**
     *
     * Load vmap template
     * 
     * @since 1.3
     * 
     */
    public function load_vmap_template(){ 
        if ( is_singular( self::CPT_AD_SCHEDULE ) ){

            $options = $this->get_options( get_the_ID() );

            $args = array(
                'schedule_id'       =>  get_the_ID(),
                'expiration'        =>  $options['cache_expiry'],
                'alias_schedule'    =>  $options['alias_schedule']
            );

            load_template( 
                plugin_dir_path( __FILE__ ) . 'public/vmap.php',
                true,
                $args
            );

            exit;
        }
    }

    /**
     *
     * Clear schedule cache while update post
     *
     * @since 1.3
     * 
     */
    public function clear_cache( $post_id ){
        if( get_post_type( $post_id ) == self::CPT_AD_SCHEDULE ){
            delete_transient( 'ad_schedule_' . $post_id );
            // Create new cache with new data
            wp_remote_get( get_permalink( $post_id ), array(
                'timeout'   =>  5
            ) );
        }
    }

    /**
     *
     * Query Ad Posts
     * 
     * @return get_posts()
     *
     * @since 1.3
     * 
     */
    public function query_ads( $args = array() ){

        $args = wp_parse_args( $args, array(
            'ref_post_id'       =>  0,
            'posts_per_page'    =>  10,
            's'                 =>  ''
        ) );

        $query_args = array(
            'post_type'         =>  self::CPT_AD_SCHEDULE,
            'post_status'       =>  'publish',
            'posts_per_page'    =>  $args['posts_per_page'],
            's'                 =>  $args['s'],
            'tax_query'         =>  array(),
            'meta_query'        =>  array(
                'relation'      =>  'AND',
                array(
                    'key'       =>  'enable',
                    'value'     =>  'on'
                ),
                array(
                    'key'       =>  'ad_tags',
                    'compare'   =>  'EXISTS'
                ),
                array(
                    'key'       =>  'start_date',
                    'value'     =>  date( 'Y-m-d H:i:s', strtotime( current_time( 'mysql', true ) ) ),
                    'compare'   =>  '<=',
                    'type'      =>  'DATETIME'
                ),
                array(
                    'key'       =>  'end_date',
                    'value'     =>  date( 'Y-m-d H:i:s', strtotime( current_time( 'mysql', true ) ) ),
                    'compare'   =>  '>=',
                    'type'      =>  'DATETIME'
                )
            )
        );

        if( get_post_type( $args['ref_post_id'] ) == Streamtube_Core_Post::CPT_VIDEO ){

            $taxonomies = get_object_taxonomies( Streamtube_Core_Post::CPT_VIDEO, 'object' );

            if( $taxonomies ){
                foreach ( $taxonomies as $tax => $object ) {
                    $terms = get_the_terms( $args['ref_post_id'], $tax );

                    if( $terms ){
                        $query_args['tax_query'][] = array(
                            'taxonomy'  =>  $tax,
                            'field'     =>  'slug',
                            'terms'     =>  wp_list_pluck( $terms, 'slug' ),
                            'operator'  =>  'IN'
                        );
                    }
                }
            }

            if( count( $query_args['tax_query'] ) > 1 ){
                $query_args['tax_query']['relation'] = 'OR';
            }
        }

        return get_posts( $query_args );
    }

    /**
     *
     * AJAX search ad schedule posts
     * 
     */
    public function ajax_search_ads(){

        check_ajax_referer( '_wpnonce' );

        $request = wp_parse_args( $_GET, array(
            'responseType'  =>  '',
            's'             =>  ''
        ) );

        extract( $request );

        $posts = $this->query_ads( array(
            's' =>  $request['s']
        ) );

        if( $responseType == 'select2' ){

            $results = array();

            if( $posts ){
                foreach( $posts as $post ){
                    $results[] = array(
                        'id'    =>  $post->ID,
                        'text'  =>  sprintf( '(#%1$s) %2$s', $post->ID, $post->post_title )
                    );
                }
            }

            wp_send_json_success( array(
                'results'   =>  $results,
                'pagination'    =>  array(
                    'more'  =>  true
                )
            ) );
        }

        wp_send_json_success( $posts );
    }

    /**
     * Add custom fields to the Ad Schedule table
     *
     * @param array $columns
     *
     * @since 1.3
     * 
     */
    public function admin_post_table( $columns ){

        unset( $columns['date'] );

        $new_columns = array(
            'status'        =>  esc_html__( 'Status', 'streamtube-core' ),
            'ad_tags'       =>  esc_html__( 'Ad Tags', 'streamtube-core' ),
            'start_date'    =>  esc_html__( 'Start Date', 'streamtube-core' ),
            'end_date'      =>  esc_html__( 'End Date', 'streamtube-core' ),
            'date'          =>  esc_html__( 'Date', 'streamtube-core' )
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
        switch ( $column ) {
            
            case 'status':

                $is_active = $this->is_active( $post_id );

                if( is_wp_error( $is_active ) ){
                    printf(
                        '<span class="badge bg-warning bg-%1$s">%2$s</span>',
                        $is_active->get_error_code(),
                        $is_active->get_error_message()
                    );                                         
                }else{
                    printf(
                        '<span class="badge bg-success">%s</span>',
                        esc_html__( 'Running', 'streamtube-core' )
                    );
                }            
            break;

            case 'ad_tags':
                $ad_tags = $this->get_ad_tags();

                if( $ad_tags ){

                    echo '<ol>';

                        $ad_tag_ids = array_values( array_unique( $ad_tags['id'] ) );

                        for ( $i=0; $i < count( $ad_tag_ids ); $i++ ) { 
                            printf(
                                '<li><a target="_blank" href="%s">%s</a></li>',
                                esc_url( get_permalink( $ad_tag_ids[$i] ) ),
                                get_the_title( $ad_tag_ids[$i] )
                            );
                        }

                    echo '</ol>';
                }
            break;

            case 'start_date':
            case 'end_date':
                echo streamtube_convert_local_datetime( get_post_meta( $post_id, $column, true ));
            break;            
        }
    }    
}


