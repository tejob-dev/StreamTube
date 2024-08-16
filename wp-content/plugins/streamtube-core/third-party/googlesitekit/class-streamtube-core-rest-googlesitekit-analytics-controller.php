<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define the rest functionality.
 *
 * @since      1.0.8
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */
class StreamTube_Core_GoogleSiteKit_Analytics_Rest_Controller extends StreamTube_Core_GoogleSiteKit_Rest_Controller{

    protected $path = '/googlesitekit';

    protected $analytics;

    protected $search_console;

    public function __construct(){
        $this->analytics        = new Streamtube_Core_GoogleSiteKit_Analytics();
        $this->search_console   = new Streamtube_Core_GoogleSiteKit_Search_Console();
    }

    /**
     * @since 1.0.8
     */
    public function rest_api_init(){

        register_rest_route(
            "{$this->namespace}{$this->version}",
            $this->path . '/(?P<endpoint>[a-zA-Z0-9-]+)',
            array(
                'methods'   =>  WP_REST_Server::READABLE,
                'callback'  =>  array( $this , 'get_reports' ),
                'args'      =>  array(
                    'endpoint' =>  array(
                        'validate_callback' => function( $param, $request, $key ) {
                            return is_string( $param ) && ! empty( $param );
                        }
                    ),
                    'start_date' =>  array(
                        'validate_callback' => function( $param, $request, $key ) {
                            return is_string( $param ) && ! empty( $param );
                        }
                    ),
                    'end_date' =>  array(
                        'validate_callback' => function( $param, $request, $key ) {
                            return is_string( $param ) && ! empty( $param );
                        }
                    ),
                    'limit' =>  array(
                        'validate_callback' => function( $param, $request, $key ) {
                            return is_int( $param ) && $param > 0;
                        }
                    )
                ),
                'permission_callback'   =>  function( $request ){
                    return is_user_logged_in();
                }
            )
        );
    }

    /**
     *
     * Check if current user has public posts
     * 
     * @return boolean
     *
     * @since 2.0
     * 
     */
    public function user_has_public_posts(){
        return count_user_posts( get_current_user_id(), Streamtube_Core_Post::CPT_VIDEO, true );
    }

    /**
     *
     * Get default path
     * 
     * @return string
     *
     * @since 1.0.9
     * 
     */
    public function get_default_path(){
        return $this->get_page_path( home_url('/') );
    }

    public function get_page_path( $url = '' ){
        $_url = parse_url( $url );

        return str_replace( $_url['scheme'] . '://' .  $_url['host'] , "", $url );
    }

    /**
     *
     * Return array of page paths or false
     * 
     * @since 2.0
     */
    public function get_user_own_page_paths( $limit = -1 ){

        if( ! $this->user_has_public_posts() ){
            return false;
        }

        $page_paths = array();

        $query_args = array(
            'author'            =>  get_current_user_id(),
            'post_type'         =>  Streamtube_Core_Post::CPT_VIDEO,
            'post_status'       =>  'publish',
            'posts_per_page'    =>  $limit,
            'meta_query'        =>  array(
                array(
                    'key'       =>  Streamtube_Core_Post::VIDEO_URL,
                    'compare'   =>  'EXISTS'
                ),  
                array(
                    'key'       =>  '_thumbnail_id',
                    'compare'   =>  'EXISTS'
                )                
            )
        );

        $posts = get_posts( $query_args );

        if( ! $posts ){
            return false;
        }

        foreach ( $posts as $post ) {
            $page_paths[] = $this->get_page_path( get_permalink( $post->ID ) );
        }

        return $page_paths;
    }

    /**
     *
     * Get overview reports metrics
     * 
     * @since 1.0.8
     * 
     */
    public function get_overview_metrics(){
        $metrics = array(
            'screenPageViews'           =>  esc_html__( 'Page Views', 'streamtube-core' ),           
            'totalUsers'                =>  esc_html__( 'Users', 'streamtube-core' ),
            'newUsers'                  =>  esc_html__( 'New Users', 'streamtube-core' ),            
            'sessions'                  =>  esc_html__( 'Sessions', 'streamtube-core' ),
            'userEngagementDuration'    =>  esc_html__( 'Session Duration', 'streamtube-core' )
        );

        /**
         * @since 1.0.8
         */
        return apply_filters( 'streamtube/core/googlesitekit/reports/overview_metrics', $metrics );
    }

    /**
     *
     * Pre get overview metrics
     * 
     * @return array
     *
     * @since 1.0.8
     * 
     */
    public function pre_get_overview_metrics(){
        $metrics = $this->get_overview_metrics();

        $custom_metrics = get_option( 'sitekit_reports_overview_metrics' );

        if( ! $custom_metrics || ! is_array( $custom_metrics ) ){
            return $metrics;
        }

        foreach ( $metrics as $key => $value ) {
            if( array_key_exists( $key, $custom_metrics ) && ! $custom_metrics[ $key ] ){
                unset( $metrics[ $key ] );
            }
        }

        return $metrics;
    }

    /**
     *
     * Get overview video play event reports metrics
     * 
     * @since 1.0.8
     * 
     */
    public function get_overview_video_metrics(){
        return array(
            'eventCount' =>  esc_html__( 'Video Views', 'streamtube-core' )
        );
    }

    /**
     *
     * Pre get overview metrics
     * 
     * @return array
     *
     * @since 1.0.8
     * 
     */
    public function pre_get_overview_video_metrics(){

        $metrics = $this->get_overview_video_metrics();

        $custom_metrics = get_option( 'sitekit_reports_overview_video_metrics' );

        if( ! $custom_metrics || ! is_array( $custom_metrics ) ){
            return $metrics;
        }

        foreach ( $metrics as $key => $value ) {
            if( array_key_exists( $key, $custom_metrics ) && ! $custom_metrics[ $key ] ){
                unset( $metrics[ $key ] );
            }
        }

        return $metrics;
    }

    private function add_thumbnail_dimension( $response ){
        for ( $i=0;  $i < count( $response['rows'] );  $i++) { 
            $page_url = $response['rows'][$i]['dimensionValues'][0]['value'];

            $maybe_post_id = url_to_postid( streamtube_core_get_hostname( true ) . $page_url );

            if( $maybe_post_id && ( $thumbnail_id = get_post_thumbnail_id( $maybe_post_id ) ) ){
                $response['rows'][$i]['dimensionValues'][]['value'] = wp_get_attachment_image_url( $thumbnail_id, 'large' );
            }else{
                $response['rows'][$i]['dimensionValues'][]['value'] = '';
            }
        }

        return $response;        
    }

    /**
     *
     * Get Reports endpointer
     *
     * @since 2.0
     * 
     */
    public function get_reports( $request ){

        $query = array();

        if( $request['start_date'] == 'all' ){

            $maybe_page_id = url_to_postid( streamtube_core_get_hostname( true ) . $request['page_path']);

            if( $maybe_page_id ){
                $start_date = date( 'Y-m-d', strtotime( get_post( $maybe_page_id )->post_date ) );
            }
            else{
                $start_date = get_option( 'site_start_date', '2006-01-01' );
            }

            $request['start_date'] = $start_date;
            $request['end_date'] = 'today';
        }

        $date_ranges = $this->get_date_ranges( $request['start_date'], $request['end_date'] );

        if( is_wp_error( $date_ranges ) ){
            wp_send_json_error( $date_ranges );
        }

        error_reporting(0);

        switch ( $request['endpoint'] ) {
            case 'overview':
                $query = $this->get_overview_reports( $date_ranges );
            break;

            case 'totalUsers':
            case 'newUsers':
            case 'sessions':
            case 'screenPageViews':
            case 'userEngagementDuration':

                $metrics = $this->pre_get_overview_metrics();

                if( ! array_key_exists( $request['endpoint'], $metrics ) ){
                    wp_send_json_error( new WP_Error(
                        'invalid_request',
                        esc_html__( 'Invalid Request', 'streamtube-core' )
                    ) );
                }

                $query = $this->get_default_metrics_reports( $request, $date_ranges );
            break;

            case 'videooverview':
                $query = $this->get_video_overview_reports( $date_ranges );
            break;

            case 'videoviews':
                $query = $this->get_video_views_reports( $date_ranges );
            break;

            case 'topcontent':
                $query = $this->get_top_content_reports( $date_ranges );
            break;

            case 'topchannels':
                $query = $this->get_top_channels_reports( $date_ranges );
            break;

            case 'topcountries':
                $query = $this->get_top_countries_reports( $date_ranges );
            break;

            case 'topsearch':
                return $this->get_top_search_reports( $date_ranges );
            break;
        }

        extract( $query );

        if( $request['page_path'] ){
            $params['dimensionFilter']['andGroup']['expressions'][] = array(
                'filter'    =>  array(
                    'stringFilter'  =>  array(
                        'value' =>   $request['page_path'],
                        'matchType' =>  'BEGINS_WITH'
                    ),
                    'fieldName'     =>  'pagePath'
                )
            );
        }else{
            if( $this->analytics->can_moderate() ){
                $params['dimensionFilter']['andGroup']['expressions'][] = array(
                    'filter'    =>  array(
                        'stringFilter'  =>  array(
                            'value' =>  $this->get_default_path(),
                            'matchType' =>  'BEGINS_WITH'
                        ),
                        'fieldName'     =>  'pagePath'
                    )
                );
            }else{
                $page_paths = $this->get_user_own_page_paths();

                if( ! $page_paths ){
                    return new WP_Error(
                        'no_public_posts',
                        esc_html__( 'You have not published any posts yet.', 'streamtube-core' )
                    );
                }

                $params['dimensionFilter']['andGroup']['expressions'][] = array(
                    'filter'    =>  array(
                        'inListFilter'  =>  array(
                            'values' =>  $page_paths
                        ),
                        'fieldName'     =>  'pagePath'
                    )
                );            
            }
        }

        $response = $this->analytics->get_reports( $params );

        if( is_wp_error( $response )){
            wp_send_json_error( array_merge( compact( 'response', 'params' ), array(
                'message'   =>  $response->get_error_messages()
            ) ) );
        }

        if( ! array_key_exists( 'rowCount', $response ) ){
            wp_send_json_error( array_merge( compact( 'response', 'params' ), array(
                'message'   =>  esc_html__( 'No data available', 'streamtube-core' )
            ) ) );
        }

        if( $request['endpoint'] ){
            $response = $this->add_thumbnail_dimension( $response );
        }

        $response = compact( 'response', 'params', 'headers' );

        wp_send_json_success( $response );
    }

    /**
     *
     * Gt overview reports
     * 
     * @param  $request
     * 
     *
     * @since 2.0
     * 
     */
    private function get_overview_reports( $date_ranges ){

        $metrics = $this->pre_get_overview_metrics();

        $headers = array_values( $metrics );

        $params = array(
            'keepEmptyRows' =>  true
        );

        foreach ( $metrics as $key => $value ) {
            $params['metrics'][] = array(
                'name'    =>  $key
            );
        }

        $params['dimensions'][] = array(
            'name'  =>  'date'
        );

        $params['metricAggregations'] = array( 'TOTAL', 'MAXIMUM', 'MINIMUM' );
        $params['orderBys'] = array(
            'dimension' =>  array(
                'dimensionName' =>  'date',
                'orderType'     =>  'ALPHANUMERIC'
            )
        );

        $params['dateRanges'] = $date_ranges;

        return compact( 'params', 'headers' );
    }

    private function get_default_metrics_reports( $request, $date_ranges ){

        $metrics = $this->pre_get_overview_metrics();
        $headers = $metrics[ $request['endpoint'] ];

        $params = array(
            'keepEmptyRows' =>  true
        );        

        $params['metrics'][] = array(
            'name'    =>  $request['endpoint']
        );
        $params['dimensions'][] = array(
            'name'  =>  'date'
        );
        $params['orderBys'] = array(
            'dimension' =>  array(
                'dimensionName' =>  'date',
                'orderType'     =>  'ALPHANUMERIC'
            )
        );

        $params['dateRanges'] = $date_ranges;

        return compact( 'params', 'headers' );
    }

    private function get_video_overview_reports( $date_ranges ){

        $metrics = $this->pre_get_overview_video_metrics();

        $headers = array_values( $metrics );

        $params = array(
            'keepEmptyRows' =>  true
        );

        foreach ( $metrics as $key => $value ) {
            $params['metrics'][] = array(
                'name'    =>  $key
            );
        }

        $params['dimensions'][] = array(
            'name'  =>  'date'
        );

        $params['dimensionFilter']['andGroup']['expressions'][] = array(
            'filter'    =>  array(
                'stringFilter'  =>  array(
                    'value' =>  'video_play',
                    'matchType' =>  'EXACT'
                ),
                'fieldName'     =>  'eventName'
            )
        );                
        $params['orderBys'] = array(
            'dimension' =>  array(
                'dimensionName' =>  'date',
                'orderType'     =>  'ALPHANUMERIC'
            )
        );

        $params['metricAggregations'] = array( 'TOTAL', 'MAXIMUM', 'MINIMUM' );

        $params['dateRanges'] = $date_ranges;

        return compact( 'params', 'headers' );
    }

    private function get_video_views_reports( $date_ranges ){

        $headers = esc_html__( 'Video Views', 'streamtube-core' );

        $params = array(
            'keepEmptyRows' =>  true
        );     

        $params['metrics'][] = array(
            'name'    =>  'eventCount'
        );
        $params['dimensions'][] = array(
            'name'  =>  'date'
        );        
        
        $params['dimensionFilter']['andGroup']['expressions'][] = array(
            'filter'    =>  array(
                'stringFilter'  =>  array(
                    'value' =>  'video_play',
                    'matchType' =>  'EXACT'
                ),
                'fieldName'     =>  'eventName'
            )
        );
        $params['orderBys'] = array(
            'dimension' =>  array(
                'dimensionName' =>  'date',
                'orderType'     =>  'ALPHANUMERIC'
            )
        );
        $params['metricAggregations'] = array( 'TOTAL', 'MAXIMUM', 'MINIMUM' );

        $params['dateRanges'] = $date_ranges;

        return compact( 'params', 'headers' );     
    }

    private function get_top_content_reports( $date_ranges ){

        $metrics = array(
            'screenPageViews'   =>  esc_html__( 'Page Views', 'streamtube-core' ),
            'totalUsers'        =>  esc_html__( 'Total Users', 'streamtube-core' ),
            'newUsers'          =>  esc_html__( 'New Users', 'streamtube-core' )
        );

        $headers = array_values( $metrics );

        $params = array(
            'keepEmptyRows' =>  true,
            'limit'         =>  10
        );

        foreach ( $metrics as $metric => $alias ) {
            $params['metrics'][] = array(
                'name'      =>  $metric
            );
        }
        $params['dimensions'][] = array(
            'name'      =>  'pagePath'
        );
        $params['dimensions'][] = array(
            'name'      =>  'pageTitle'
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

        $params['orderBys'] = array(
            'desc'      =>  true,
            'metric'    =>  array(
                'metricName'    =>  'screenPageViews'
            )
        );

        $params['metricAggregations'] = array( 'TOTAL', 'MAXIMUM', 'MINIMUM' );

        $params['dateRanges'] = $date_ranges[0];

        return compact( 'params', 'headers' );
    }

    private function get_top_channels_reports( $date_ranges ){

        $metrics = array(
            'sessions'      =>  esc_html__( 'Sessions', 'streamtube-core' ),
            'totalUsers'    =>  esc_html__( 'Total Users', 'streamtube-core' ),
            'newUsers'      =>  esc_html__( 'New Users', 'streamtube-core' )
        );

        $headers = array_values( $metrics );

        $params = array(
            'keepEmptyRows' =>  true
        );

        foreach ( $metrics as $metric => $alias ) {
            $params['metrics'][] = array(
                'name'      =>  $metric
            );
        }

        $params['dimensions'][] = array(
            'name'  =>  'sessionDefaultChannelGrouping'
        );

        $params['orderBys'] = array(
            'desc'      =>  true,
            'metric'    =>  array(
                'metricName'    =>  'newUsers'
            )
        );

        $params['metricAggregations'] = array( 'TOTAL', 'MAXIMUM', 'MINIMUM' );

        $params['dateRanges'] = $date_ranges[0];

        return compact( 'params', 'headers' );
    }

    private function get_top_countries_reports( $date_ranges ){

        $headers = array();

        $params = array(
            'keepEmptyRows' =>  true
        );        
        $params['metrics'][] = array(
            'name'    =>  'totalUsers'
        );
        $params['dimensions'][] = array(
            'name'  =>  'country'
        );

        $params['orderBys'] = array(
            'desc'      =>  true,
            'metric'    =>  array(
                'metricName'    =>  'totalUsers'
            )
        );

        $params['metricAggregations'] = array( 'TOTAL', 'MAXIMUM', 'MINIMUM' );

        $params['dateRanges'] = $date_ranges[0];

        return compact( 'params', 'headers' );       
    }

    private function get_top_search_reports( $date_ranges ){
        $params = array(
            'includeEmptyRows'      =>  true,
            'limit'                 =>  10,
            'aggregationType'       =>  'auto',
            'dimensions'            =>  'query',
            'url'                   =>  untrailingslashit( get_site_url('/') )
        );    

        $params = array_merge( $params, $date_ranges[0] );

        $response = $this->search_console->get_reports( $params );

        if( is_wp_error( $response )){
            wp_send_json_error( array_merge( compact( 'response', 'params' ), array(
                'message'   =>  $response->get_error_messages()
            ) ) );
        }

        $response = compact( 'response', 'params' );

        wp_send_json_success( $response );  
    }
}