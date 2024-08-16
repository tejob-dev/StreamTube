<?php
/**
 * Define the Google Analytics 4 Report functionality
 *
 *
 * @link       https://themeforest.net/user/phpface
 * @since      1.0.0
 *
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 */

/**
 * Define the analytics functionality
 *
 * @since      1.0.8
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */

if( ! defined('ABSPATH' ) ){
    exit;
}

class Streamtube_Core_GoogleSiteKit_Analytics extends Streamtube_Core_GoogleSiteKit{

    /**
     *
     * Holds the Endpoint URL
     * 
     * @var string
     */
    protected $endpoint                     = 'https://analyticsdata.googleapis.com/v1beta/properties/GA4_PROPERTY_ID:runReport?alt=json';

    /**
     *
     * Holds the module slug
     * 
     * @var string
     *
     * @since 1.0.8
     * 
     */
    protected $module                       = 'analytics-4';

    /**
     *
     * Holds the datapoint slug
     * 
     * @var string
     *
     * @since 1.0.8
     * 
     */    
    protected $datapoint                    = 'report';

    /**
     *
     * Holds the Analytics Cap
     * 
     * @var string
     *
     * @since 2.0
     * 
     */
    protected $view_analytics_cap           = 'edit_posts';

    /**
     *
     * Enqueue embed scripts
     * 
     */
    public function enqueue_scripts(){

        wp_register_script( 
            'streamtube-reports', 
            trailingslashit( STREAMTUBE_CORE_PUBLIC_URL ) . 'assets/js/reports.js', 
            array( 'jquery' ), 
            filemtime( trailingslashit( STREAMTUBE_CORE_PUBLIC ) . 'assets/js/reports.js' ),
            true 
        );

        $jsvars = array(
            'user_id'               =>  is_user_logged_in() ? get_current_user_id() : 0,
            'home_url'              =>  untrailingslashit( home_url() ),
            'hosturl'               =>  streamtube_core_get_hostname(true),
            'rest_url'              =>  rest_url( '/streamtube/v1' ),
            'hour'                  =>  esc_html__( 'h', 'streamtube-core' ),
            'minute'                =>  esc_html__( 'm', 'streamtube-core' ),
            'second'                =>  esc_html__( 's', 'streamtube-core' ),
            'title'                 =>  esc_html__( 'Title', 'streamtube-core' ),
            'channel'               =>  esc_html__( 'Channel', 'streamtube-core' ),
            'percentage'            =>  esc_html__( 'Percentage', 'streamtube-core' ),
            'country'               =>  esc_html__( 'Country', 'streamtube-core' ),
            'users'                 =>  esc_html__( 'Users', 'streamtube-core' ),
            'mode'                  =>  function_exists( 'streamtube_get_theme_mode' ) ? streamtube_get_theme_mode() : 'light',
            'previous_period'       =>  esc_html__( 'Previous period', 'streamtube-core' ),
            'data_not_available'    =>  esc_html__( 'Data Not Available', 'streamtube-core' ),
            'keyword'               =>  esc_html__( 'Keyword', 'streamtube-core' ),
            'clicks'                =>  esc_html__( 'Clicks', 'streamtube-core' ),
            'impressions'           =>  esc_html__( 'Impressions', 'streamtube-core' ),
            'ctr'                   =>  esc_html__( 'CTR', 'streamtube-core' ),
            'position'              =>  esc_html__( 'Position', 'streamtube-core' ),
            'language'              =>  get_locale(),
            'mapapikey'             =>  get_option( 'sitekit_mapapikey' ),
            'session_storage'       =>  get_option( 'sitekit_session_storage', 1 ),
            'no_keywords_found'     =>  esc_html__( 'No keywords were found.', 'streamtube-core' )
        );

        /**
         * @since 1.0.8
         */
        $jsvars = apply_filters('streamtube/core/analytics/jsvars', $jsvars );

        wp_localize_script( 'streamtube-reports', 'analytics', $jsvars );
    }

    public function enqueue_embed_scripts(){
        wp_enqueue_script( 'google-analytics', '//www.google-analytics.com/analytics.js' );
    }    

    /**
     *
     * Supported Post Type
     * 
     * @return [type] [description]
     */
    private function get_supported_post_types(){
        return apply_filters( 'streamtube/core/googlesitekit/analytics/supported_post_types', array(
            'post',
            'video'
        ) );
    }

    /**
     *
     * Get GA4 property ID
     * 
     * @return false|string
     *
     * @since 2.0
     * 
     */
    public function get_property_id(){
        if( ! $this->is_connected() ){
            return false;
        }

        global $wpdb;

        $results = $wpdb->get_row(  "SELECT * FROM {$wpdb->options} WHERE `option_name` = 'googlesitekit_analytics-4_settings' " );     

        if( ! $results ){
            return false;
        }   

        $settings = unserialize( $results->option_value );

        if( ! $settings || ! is_array( $settings ) || ! array_key_exists( 'propertyID', $settings ) ){
            return false;
        }

        return $settings['propertyID'];
    }    

    /**
     *
     * Get Analytics profile ID
     * 
     * @return false|string
     *
     * @since 1.0.9
     * 
     */
    public function get_profile_id(){

        if( ! $this->is_connected() ){
            return false;
        }

        global $wpdb;

        $results = $wpdb->get_row(  "SELECT * FROM {$wpdb->options} WHERE `option_name` = 'googlesitekit_analytics_settings' " );     

        if( ! $results ){
            return false;
        }   

        $settings = unserialize( $results->option_value );

        if( ! $settings || ! is_array( $settings ) || ! array_key_exists( 'profileID', $settings ) ){
            return false;
        }

        return $settings['profileID'];
    }    

    /**
     * Get API endpoint
     *
     * @since 2.0
     * 
     */
    public function get_endpoint(){

        $property_id = $this->get_property_id();

        if( $property_id ){
            $this->endpoint = str_replace( 'GA4_PROPERTY_ID' , $property_id, $this->endpoint );
        }

        return $this->endpoint;
    }        

    /**
     *
     * Get view Analytics Data cap
     * 
     * @return string
     */
    public function get_view_cap(){
        $cap = get_option( 'sitekit_reports_cap', $this->view_analytics_cap );

        if( empty( $cap ) ){
            $cap = $this->view_analytics_cap;
        }

        return $cap;
    }

    /**
     *
     * Posts per page
     * 
     * @return int
     *
     * @since 1.0.8
     * 
     */
    public function get_cron_posts_per_page(){
        return apply_filters( 'streamtube/cron_posts_per_page', 100 );
    }

    /**
     *
     * Check if Google Sitekit Analytics module activated
     * 
     * @return true|false
     *
     * @since 1.0.8
     * 
     */
    public function is_connected(){
        if( ! $this->is_sitekit_active() ){
            return false;
        }

        $modules = get_option( 'googlesitekit_active_modules' );

        if( ! is_array( $modules ) ){
            return false;
        }

        if( array_search( 'analytics', $modules ) || array_search( $this->module, $modules ) ){
            return true;
        }

        return false;
    }

    /**
     *
     * Check if module is active
     * 
     * @return boolean
     *
     * @since 1.0.8
     * 
     */
    public function is_active(){

        if( ! get_option( 'sitekit_reports', 'on' ) || ! $this->is_connected() ){
            return false;
        }

        if( Streamtube_Core_Permission::moderate_posts() ){
            return true;
        }

        if( ! current_user_can( $this->get_view_cap() ) ){
            return false;
        }

        /**
         *
         * Filter the is_connected() results
         * 
         */
        return apply_filters( "streamtube/core/googlesitekit/{$this->module}/active", true );
    }

    /**
     *
     * Check if current user can view analytics data
     * 
     * @param  integer $post_id
     *
     * @since 2.0
     *
     */
    public function can_view( $post_id = 0 ){

        if( ! $this->is_active() ){
            return false;
        }

        if( $post_id && current_user_can( 'edit_post', $post_id ) ){
            return true;
        }

        return false;
    }

    /**
     *
     * Check if current logged in user can moderate analytics data
     * 
     * @return true|false
     *
     * @since 2.0
     * 
     */
    public function can_moderate(){

        $can = false;

        if( current_user_can( 'administrator' ) || current_user_can( 'editor' ) ){
            $can = true;
        }

        return apply_filters( "streamtube/core/googlesitekit/{$this->module}/can_moderate", $can );
    }

    /**
     *
     * Get start dates
     * 
     * @return array
     *
     * @since 1.0.8
     * 
     */
    public function get_start_dates(){
        $date_ranges = array(
            'today'         =>  esc_html__( 'Today', 'streamtube-core' ),
            'yesterday'     =>  esc_html__( 'Yesterday', 'streamtube-core' ),
            '7daysAgo'      =>  esc_html__( 'Last 7 days', 'streamtube-core' ),
            '14daysAgo'     =>  esc_html__( 'Last 14 days', 'streamtube-core' ),
            '28daysAgo'     =>  esc_html__( 'Last 28 days', 'streamtube-core' ),
            '90daysAgo'     =>  esc_html__( 'Last 90 days', 'streamtube-core' ),
            '180daysAgo'    =>  esc_html__( 'Last 180 days', 'streamtube-core' ),
        );  

        return apply_filters( 'streamtube/core/analytics/start_dates', $date_ranges );
    }

    /**
     *
     * Get tabs
     * 
     * @return array
     *
     * @since 1.0.8
     * 
     */
    public function get_tabs(){
        return array(
            'users'             =>  esc_html__( 'Users', 'streamtube-core' ),
            'sessions'          =>  esc_html__( 'Sessions', 'streamtube-core' ),
            'bounce_rate'       =>  esc_html__( 'Bounce Rate', 'streamtube-core' ),
            'session_duration'  =>  esc_html__( 'Session Duration', 'streamtube-core' )
        );
    }

    /**
     *
     * Check if given post if exists
     * 
     * @param  integer $post_id
     * @return $post_id or WP_Error
     *
     * @since 1.0.8
     * 
     */
    private function is_exist_post( $post_id = 0 ){

        $post = get_post( $post_id );

        if( ! $post ){
            return new WP_Error(
                'invalid_post',
                esc_html__( 'Invalid Post', 'streamtube-core' )
            );
        }        

        return $post;
    }

    /**
     *
     * Get post path
     * 
     * @param  int $post_id
     * @return string
     */
    private function get_post_path( $post_id = 0 ){
        return $path = str_replace( streamtube_core_get_hostname( true ), '', get_permalink( $post_id ) );
    }

    /**
     *
     * Retrieving data from Analytics
     * 
     */
    public function get_top_page_views( $args = array() ){
        $args = wp_parse_args( $args, array(
            'metric'        =>  'eventCount', // screenPageViews
            'beginWith'     =>  '',
            'startDate'     =>  '30daysAgo',
            'endDate'       =>  'today',
            'limit'         =>  10
        ) );

        extract( $args );

        $params = array(
            'limit'                 =>  $limit,
            'metricAggregations'    =>  array( 'TOTAL', 'MAXIMUM', 'MINIMUM' ),
            'dimensions'            =>  array(
                array(
                    "name" => "fullPageUrl"
                )
            ),
            'metrics'               =>  array(
                array(
                    'name'        =>  $metric
                )
            ),
            'dimensionFilter'       =>  array(
                'andGroup'  =>  array(
                    'expressions'   =>  array(
                        array(
                            "filter"        => array(
                                "fieldName"         => "fullPageUrl",
                                "stringFilter"      => array(
                                    "matchType"     => "BEGINS_WITH",
                                    "value"         => $beginWith,
                                    "caseSensitive" => false
                                )
                            )
                        )                        
                    )
                )
            )
        );

        if( $metric == 'eventCount' ){
            $params['dimensionFilter']['andGroup']['expressions'][] = array(
                'filter'    =>  array(
                    'stringFilter'  =>  array(
                        'value'     =>  'video_play',
                        'matchType' =>  'EXACT'
                    ),
                    'fieldName'     =>  'eventName'
                )
            );
        }

        $params['dateRanges'][]  = compact( 'startDate', 'endDate' ); 

        $response = $this->call_api( $params );

        if( is_wp_error( $response ) ){
            return $response;
        }

        if( ! array_key_exists( 'rows', $response ) ){
            return false;
        }

        $data = array();

        $rows = $response['rows'];

        for ( $i=0; $i < count( $rows ); $i++) { 
            $data[] = array(
                $rows[$i]['dimensionValues'][0]['value'],
                $rows[$i]['metricValues'][0]['value']
            );
        }

        return $data;
    }

    /**
     *
     * Get page view by post Id or page Path
     * 
     * @param  array $args
     * @return int|WP_Error
     *
     * @since 1.0.8
     * 
     */
    public function get_post_views( $args = array() ){

        $params = array();

        $args = wp_parse_args( $args, array(
            'post_id'   =>  0,
            'pagePath'  =>  '',
            'startDate' =>  '',
            'endDate'   =>  'today'
        ) );

        extract( $args );

        if( empty( $args['pagePath'] ) ){
            $post = $this->is_exist_post( $post_id );

            if( is_wp_error( $post )){
                return $post;
            }

            $args['pagePath'] = $this->get_post_path( $post->ID ); 

            if( empty( $startDate ) ){
                $startDate = date( 'Y-m-d', strtotime( $post->post_date ) );
            }
        }

        $params['metricAggregations'] = array( 'TOTAL', 'MAXIMUM', 'MINIMUM' );

        $params['metrics'][] = array(
            'name'        =>  'screenPageViews'
        );

        $params['dimensions'][] = array(
            'name'        =>  'pagePath'
        );        

        $params['dimensionFilter']['andGroup']['expressions'] = array(
            array(
                'filter'    =>  array(
                    'stringFilter'  =>  array(
                        'value' =>  $args['pagePath'],
                        'matchType' =>  'CONTAINS'
                    ),
                    'fieldName'     =>  'pagePath'
                )
            )
        );

        $params['dateRanges'][]  = compact( 'startDate', 'endDate' );

        $response = $this->call_api( $params );

        if( is_wp_error( $response ) ){
            return $response;
        }

        if( array_key_exists( 'rowCount', $response ) ){
            $page_views = (int)$response['rows'][0]['metricValues'][0]['value'];
            update_post_meta( $post_id, '_pageviews', $page_views );
        }

        return compact( 'response', 'params', 'args' );
    }

    /**
     *
     * Get video views by Play event
     * 
     * @param  array  $args
     * @return array|WP_Error
     *
     * @since 1.0.8
     * 
     */
    public function get_video_views( $args = array() ){
        $params = array();

        $args = wp_parse_args( $args, array(
            'post_id'   =>  0,
            'startDate' =>  '',
            'endDate'   =>  'today',
            'pagePath'  =>  ''
        ) );

        extract( $args );

        if( empty( $args['pagePath'] ) ){
            $post = $this->is_exist_post( $post_id );

            if( is_wp_error( $post )){
                return $post;
            }

            $args['pagePath'] = $this->get_post_path( $post->ID ); 

            if( empty( $startDate ) ){
                $startDate = date( 'Y-m-d', strtotime( $post->post_date ) );
            }
        }

        if( empty( $startDate ) ){
            $startDate = date( 'Y-m-d', strtotime( $post->post_date ) );
        }

        $params['metricAggregations'] = array( 'TOTAL', 'MAXIMUM', 'MINIMUM' );

        $params['metrics'][] = array(
            'name'        =>  'eventCount'
        );   

        $params['dimensionFilter']['andGroup']['expressions'] = array(
            array(
                'filter'    =>  array(
                    'stringFilter'  =>  array(
                        'value'     =>  $args['pagePath'],
                        'matchType' =>  'CONTAINS'
                    ),
                    'fieldName'     =>  'pagePath'
                )
            ),
            array(
                'filter'    =>  array(
                    'stringFilter'  =>  array(
                        'value'     =>  'video_play',
                        'matchType' =>  'EXACT'
                    ),
                    'fieldName'     =>  'eventName'
                )
            )            
        );        

        $params['dateRanges'][]  = compact( 'startDate', 'endDate' );

        $response = $this->call_api( $params );

        if( is_wp_error( $response ) ){
            return $response;
        }

        if( array_key_exists( 'rowCount', $response ) ){
            $video_views = (int)$response['rows'][0]['metricValues'][0]['value'];
            update_post_meta( $post_id, '_videoviews', $video_views );
        }

        return compact( 'response', 'params', 'args' ); 
    }

    /**
     *
     * Update post list pageviews
     * 
     * @return array
     *
     * @since 1.0.8
     * 
     */
    public function update_post_list_pageviews( $query_args = array() ){

        $dimensionFilterClauses = array();

        $query_args = wp_parse_args( $query_args, array(
            'post_type'         =>  'video',
            'posts_per_page'    =>  -1,
            'paged'             =>  1
        ) );

        extract( $query_args );

        $posts = get_posts( array(
            'post_type'         =>  $post_type,
            'post_status'       =>  'publish',
            'posts_per_page'    =>  $posts_per_page,
            'paged'             =>  $paged
        ) );

        /**
        $meta_query = array(
            array(
                'key'       =>  '_last_seen',
                'value'     =>  date( 'Y-m-d H:i:s', strtotime( current_time( 'mysql', true ) . " {$interval}" ) ),
                'compare'   =>  '>=',
                'type'      =>  'DATETIME'
            )
        );
        **/

        if( ! $posts ){
            return compact( 'query_args' );
        }        

        foreach( $posts as $post ){
            $expressions[] = $this->get_post_path( $post->ID );
        }

        $params['metricAggregations'] = array( 'TOTAL', 'MAXIMUM', 'MINIMUM' );

        $params['metrics'][] = array(
            'name'        =>  'screenPageViews'
        );

        $params['dimensions'][] = array(
            'name'        =>  'pagePath'
        );      

        $params['dimensionFilter']['andGroup']['expressions'] = array(
            array(
                'filter'    =>  array(
                    'inListFilter'  =>  array(
                        'values'     =>  $expressions
                    ),
                    'fieldName'     =>  'pagePath'
                )
            )
        );

        $params['metricFilter'] = array(
            'filter'    =>  array(
                'numericFilter'  =>  array(
                    'operation' => 'GREATER_THAN',
                    'value'     =>  array(
                        'int64Value'    =>  0
                    )
                ),
                'fieldName'     =>  'screenPageViews'
            )
        );

        $params['dateRanges']  = array(
            'startDate' =>  '2015-08-15',
            'endDate'   =>  'today'
        );

        $response = $this->get_reports( $params );

        if( is_wp_error( $response ) || ! array_key_exists( 'rowCount', $response ) ){
            return compact( 'response', 'params' );
        }

        $data = $response['rows'];

        $posts_updated = array();

        for ( $i=0;  $i < count( $data );  $i++) {

            $page_path  = $data[$i]['dimensionValues'][0]['value'];

            $page_url   = untrailingslashit( streamtube_core_get_hostname( true ) ) . $page_path;

            $page_id    = url_to_postid( $page_url );     

            if( $page_id ){

                $pageviews = (int)$data[$i]['metricValues'][0]['value'];

                if( $pageviews > 0 ){
                    update_post_meta( $page_id, '_pageviews', $pageviews );    
                    $posts_updated[] = compact( 'page_id', 'page_url', 'pageviews' );

                    /**
                     * @since 2.0
                     */
                    do_action( "streamtube/core/post_updated_{$page_id}_postviews", $pageviews );
                }
            }
        }

        $response = compact( 'query_args', 'params', 'response', 'posts_updated' );

        /**
         *
         * Fires after updated into database
         *
         * @param $response
         * 
         */
        do_action( "streamtube/core/updated_post_list_pageviews", $response );

        return $response;
    }

    /**
     *
     * Update post list videoviews
     * 
     * @return array
     *
     * @since 1.0.8
     * 
     */    
    public function update_post_list_videoviews( $query_args = array() ){

        $dimensionFilterClauses = array();

        $query_args = wp_parse_args( $query_args, array(
            'post_type'         =>  'video',
            'posts_per_page'    =>  -1,
            'interval'          =>  '-5 minutes',
            'paged'             =>  1
        ) );

        extract( $query_args );

        $posts = get_posts( array(
            'post_type'         =>  $post_type,
            'post_status'       =>  'publish',
            'posts_per_page'    =>  $posts_per_page,
            /**
            'meta_query'        =>  array(
                array(
                    'key'       =>  '_last_seen',
                    'compare'   =>  'EXISTS'
                )
            )
            **/
        ) );

        if( ! $posts ){
            return compact( 'query_args' );
        }

        foreach( $posts as $post ){
            $expressions[] = $this->get_post_path( $post->ID );
        }

        $params['metricAggregations'] = array( 'TOTAL', 'MAXIMUM', 'MINIMUM' );

        $params['metrics'][] = array(
            'name'        =>  'eventCount'
        );   

        $params['dimensions'][] = array(
            'name'        =>  'pagePath'
        );         

        $params['dimensionFilter']['andGroup']['expressions'] = array(
            array(
                'filter'    =>  array(
                    'inListFilter'  =>  array(
                        'values'     =>  $expressions
                    ),
                    'fieldName'     =>  'pagePath'
                )
            ),
            array(
                'filter'    =>  array(
                    'stringFilter'  =>  array(
                        'value'     =>  'video_play',
                        'matchType' =>  'EXACT'
                    ),
                    'fieldName'     =>  'eventName'
                )
            )            
        );    

        $params['metricFilter'] = array(
            'filter'    =>  array(
                'numericFilter'  =>  array(
                    'operation' => 'GREATER_THAN',
                    'value'     =>  array(
                        'int64Value'    =>  0
                    )
                ),
                'fieldName'     =>  'eventCount'
            )
        );

        $params['dateRanges']  = array(
            'startDate' =>  '2015-08-15',
            'endDate'   =>  'today'
        );

        $response = $this->get_reports( $params );

        if( is_wp_error( $response ) || ! array_key_exists( 'rowCount', $response ) ){
            return $response;
        }

        $data = $response['rows'];

        $post_updated = array();

        for ( $i=0;  $i < count( $data );  $i++ ) {

            $page_path  = $data[$i]['dimensionValues'][0]['value'];

            $page_url   = untrailingslashit( streamtube_core_get_hostname( true ) ) . $page_path;

            $page_id    = url_to_postid( $page_url );

            if( $page_id ){

                $videoviews = (int)$data[$i]['metricValues'][0]['value'];

                if( $videoviews > 0 ){

                    update_post_meta( $page_id, '_videoviews', $videoviews );

                    $posts_updated[] = compact( 'page_id', 'page_url', 'videoviews' );

                    /**
                     * Fires after videoviews updated into database
                     */
                    do_action( "streamtube/core/updated_post_{$page_id}_videoviews", $videoviews );

                }
            }
        }

        $response = compact( 'query_args', 'params', 'response', 'posts_updated' );

        /**
         *
         * Fires after updated into database
         *
         * @param $response
         * 
         */
        do_action( "streamtube/core/updated_post_list_videoviews", $response );

        return $response;
    }

    /**
     *
     * Cron job auto update pageviews
     * 
     * @since 1.0.8
     * 
     */
    public function cron_update_post_list_pageviews(){

        $per_page = $this->get_cron_posts_per_page();

        $results = array();

        $post_types = array( 'post', 'video' );

        for ( $i=0; $i < count( $post_types ); $i++) {

            $_paged = (int)get_option( '_update_post_list_pageviews_' . $post_types[$i], 1 );

            $total_posts = wp_count_posts( $post_types[$i], 'readable' )->publish;

            if( $total_posts > 0 ){

                $total_pages = ceil($total_posts/$per_page);

                $paged = min( $_paged, $total_pages );

                $results[$post_types[ $i ]] = $this->update_post_list_pageviews( apply_filters(
                    'streamtube/core/post_list_pageviews_query_args',
                    array(
                        'post_type'         =>  $post_types[$i],
                        'posts_per_page'    =>  $per_page,
                        'paged'             =>  $paged,
                        'max_pages'         =>  $total_pages
                    )
                ) );

                if( $paged >= $total_pages ){
                    $paged = 0;
                }

                update_option( '_update_post_list_pageviews_' . $post_types[$i], $paged+1 );
            }
        }

        return compact( 'results', 'post_types' );
    }

    /**
     *
     * Cron job auto update videoviews
     * 
     * @since 1.0.8
     * 
     */
    public function cron_update_post_list_videoviews(){
        
        $per_page = $this->get_cron_posts_per_page();

        $results = array();

        $post_types = array( 'video' );

        $_paged = (int)get_option( '_update_post_list_videoviews', 1 );

        $total_posts = wp_count_posts( 'video', 'readable' )->publish;

        if( $total_posts == 0 ){
            return;
        }

        $total_pages = ceil($total_posts/$per_page);

        $paged = min( $_paged, $total_pages );

        for ( $i=0; $i < count( $post_types ); $i++) {
            $results = $this->update_post_list_videoviews( apply_filters(
                'streamtube/core/post_list_videoviews_query_args',
                array(
                    'post_type'         =>  $post_types[$i],
                    'posts_per_page'    =>  $per_page,
                    'paged'             =>  $paged,
                    'max_pages'         =>  $total_pages
                )
            ) );            
        }

        if( $paged >= $total_pages ){
            $paged = 0;
        }

        update_option( '_update_post_list_videoviews', $paged+1 );

        return compact( 'results', 'post_types' );
    }

    /**
     *
     * Auto update pageViews and videoViews on hearbeat tick event
     * 
     * @param  array $response
     * @param  string $screen_id
     * @return array
     */
    public function heartbeat_tick( $response, $screen_id ){

        $expiration = (int)get_option( 'sitekit_heartbeat_tick_transient', 60*1*30 );

        if( false !== ($cache = get_transient( 'cache_page_list_views' )) ){
            return array_merge( $response, array(
                'page_list_views'           =>  $cache,
                'page_list_views_cached'    =>  true
            ) );
        }

        $pageviews = $this->cron_update_post_list_pageviews();
        $videoviews = $this->cron_update_post_list_videoviews();

        $data = compact( 'pageviews', 'videoviews' );

        if( $expiration > 0 ){
            set_transient( 'cache_page_list_views', $data, $expiration );
        }

        return array_merge( $response, array(
            'page_list_views'   =>  $data
        ) );
    }

    /**
     *
     * The Analytics Post Button
     * 
     */
    public function button_analytics(){

        global $post;

        if( ! $this->can_view( $post->ID ) ){
            return;
        }

        $path = "dashboard/{$post->post_type}/{$post->ID}/analytics/";

        $url = trailingslashit( get_author_posts_url( $post->post_author ) ) . $path;

        printf(
            '<li><a class="dropdown-item" href="%s"><span class="icon-chart-area me-2"></span><span class="menu-text">%s</span></a></li>',
            esc_url( $url ),
            esc_html__( 'Analytics', 'streamtube' )
        );
    }

    /**
     *
     * Load the dashboard analytics
     * 
     * @since 1.0.8
     */
    public function dashboard(){

        if( ! $this->is_active() ){
            return;
        }

        if( $this->can_moderate() || count_user_posts( get_current_user_id(), $this->get_supported_post_types(), true ) ){
            streamtube_core_load_template( 'googlesitekit/reports.php' );
        }     
    }

    /**
     * 
     * Get user nav items
     * 
     * @return array
     *
     * @since  1.0.0
     * 
     */
    public function add_post_nav_item( $menu_items ){
        if( $this->is_active() ){
            $menu_items['analytics']     = array(
                'title'         =>  esc_html__( 'Analytics', 'streamtube-core' ),
                'icon'          =>  'icon-chart-area',
                'template'      =>  streamtube_core_get_template( 'post/analytics.php' ),
                'priority'      =>  100
            );            
        }

        return $menu_items;
    }       

    /**
     *
     * Load single video post views 
     * 
     * @since 1.0.8
     * 
     */
    public function load_single_post_views(){
        get_template_part( 'template-parts/post-views', '', array( 'realtime' => false ) );
    }    

    /**
     * Add custom fields to the Video table
     *
     * @param array $columns
     */
    public function filter_post_table( $columns ){
        return array_merge( $columns, array(
            'pageviews' =>  esc_html__( 'Page Views', 'streamtube-core' )
        ) );
    }

    /**
     *
     * Custom Columns callback
     * 
     * @param  string $column
     * @param  int $post_id
     * 
     */
    public function filter_post_table_columns( $column, $post_id ){
        if( $column == 'pageviews' ){
            $view_types = streamtube_core_get_post_view_types();

            $keys = array_keys( $view_types );

            for ( $i=0; $i < count( $keys ); $i++) { 
                if( 0 < $count = get_post_meta( $post_id, '_' . $keys[$i], true ) ){
                    printf(
                        '<div class="view-count %s">%s: %s</div>',
                        $keys[$i],
                        $view_types[ $keys[$i] ],
                        streamtube_core_format_page_views( $count )
                    );
                }
            }            
        }
    }    
}