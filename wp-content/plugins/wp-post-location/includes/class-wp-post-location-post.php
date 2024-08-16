<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}
/**
 *
 * @since      1.0.0
 * @package    WP_Post_Location
 * @subpackage WP_Post_Location/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */
class WP_Post_Location_Post{

    /**
     *
     * Holds the latitude meta field
     *
     * @since 1.0.0
     * 
     */
    const META_FIELD_LATITUDE           = 'latitude';

    /**
     *
     * Holds the longitude meta field
     *
     * @since 1.0.0
     * 
     */
    const META_FIELD_LONGITUDE          = 'longitude';

    /**
     *
     * Holds the zoom meta field
     *
     * @since 1.0.0
     * 
     */
    const META_FIELD_ZOOM               = 'zoom';

    /**
     *
     * Holds the address meta field
     *
     * @since 1.0.0
     * 
     */
    const META_FIELD_ADDRESS            = 'address';    

    /**
     *
     * Holds the country meta field
     *
     * @since 1.0.0
     * 
     */
    const META_FIELD_COUNTRY            = 'country_code';     

    /**
     *
     * Holds the country code meta field
     *
     * @since 1.0.0
     * 
     */
    const META_FIELD_COUNTRY_CODE        = 'country_code';     

    /**
     *
     * Get post location
     * 
     * @param  integer $post_id
     * @return array
     *
     * @since 1.0.0
     * 
     */
    public static function get_location( $post_id = 0 ){

        if( ! $post_id ){
            $post_id = get_the_ID();
        }

        $location = array(
            'lat'           =>  get_post_meta( $post_id, self::META_FIELD_LATITUDE, true ),
            'lng'           =>  get_post_meta( $post_id, self::META_FIELD_LONGITUDE, true ),
            'zoom'          =>  get_post_meta( $post_id, self::META_FIELD_ZOOM, true ),
            'address'       =>  get_post_meta( $post_id, self::META_FIELD_ADDRESS, true ),
            'country'       =>  get_post_meta( $post_id, self::META_FIELD_COUNTRY, true ),
            'country_code'  =>  get_post_meta( $post_id, self::META_FIELD_COUNTRY_CODE, true )
        );

        /**
         *
         * Filter location
         *
         * @param array $location
         * @param int $post_id
         *
         * @since 1.0.0
         * 
         */
        return apply_filters( 'wp_post_location/post_location', $location, $post_id );
    }

    /**
     *
     * Get posts
     *
     * @since 1.0.0
     * 
     */
    public static function get_posts( $args = array() ){

        $args = wp_parse_args( $args, array(
            'post_id'               =>  0,
            'post_type'             =>  array( 'video', 'post' ),
            'post_status'           =>  'publish',
            'posts_per_page'        =>  -1,
            'page'                  =>  1,
            'location_terms'        =>  array(),// array of term slug
            'category_terms'        =>  array(),
            'tag_terms'             =>  array(),
            'hide_empty_thumbnail'  =>  false
        ) );

        if( $args['post_id'] ){
            $args['post_status'] = 'all';
        }

        extract( $args );  

        $query_args = array(
            'p'                 =>  $post_id,
            'post_type'         =>  $post_type,
            'post_status'       =>  $post_status,
            'posts_per_page'    =>  $posts_per_page,
            'paged'             =>  $page,
            'tax_query'         =>  array(),
            'meta_query'        =>  array(
                array(
                    'key'       =>  self::META_FIELD_LATITUDE,
                    'compare'   =>  'EXISTS'
                ),
                array(
                    'key'       =>  self::META_FIELD_LONGITUDE,
                    'compare'   =>  'EXISTS'
                )                
            )
        );

        if( $hide_empty_thumbnail ){
            $query_args['meta_query'][] = array(
                'key'       =>  '_thumbnail_id',
                'compare'   =>  'EXISTS'
            );
        }

        if( $location_terms ){
            $query_args['tax_query'][] = array(
                'taxonomy'  =>  'location',
                'field'     =>  'slug',
                'terms'     =>  $location_terms
            );
        }

        if( $category_terms ){
            $query_args['tax_query'][] = array(
                'taxonomy'  =>  'categories',
                'field'     =>  'slug',
                'terms'     =>  $category_terms
            );
        }

        if( $tag_terms ){
            $query_args['tax_query'][] = array(
                'taxonomy'  =>  'video_tag',
                'field'     =>  'slug',
                'terms'     =>  $tag_terms
            );
        }

        /**
         *
         * Filter query args
         * 
         * @var array $query_args
         *
         * @since 1.0.0
         */
        $query_args = apply_filters( 'wp_post_location/get_posts', $query_args );

        return get_posts( $query_args );
    }

    /**
     *
     * Get post locations
     *
     * @since 1.0.0
     * 
     */
    public static function get_post_locations( $args = array() ){

        $locations = array();

        $posts = self::get_posts( $args );

        if( ! $posts ){
            return $locations;
        }

        foreach ( $posts as $_post ) {

            $locations[] = array_merge( array(
                'id'            =>  $_post->ID,
                'title'         =>  $_post->post_title,
                'thumbnail'     =>  get_the_post_thumbnail_url( $_post->ID, 'streamtube-image-medium' ),
                'author'        =>  array(
                    'name'  =>  get_the_author_meta( 'display_name', $_post->post_author ),
                    'link'  =>  get_author_posts_url( $_post->post_author )
                ),
                'permalink'     =>  get_permalink( $_post ),
                'type'          =>  $_post->post_type,
            ), self::get_location( $_post->ID ) );

        }

        return $locations;
    }

    /**
     *
     * Update location
     * 
     * @return array|WP_Error
     *
     * @since 1.0.0
     * 
     */
    public static function update_location(){

        $settings = WP_Post_Location_Customizer::get_settings();

        $errors = new WP_Error();

        if( ! isset( $_POST ) || ! isset( $_POST['wp_post_location'] ) ){
            $errors->add(
                'invalid_request',
                esc_html__( 'Invalid Requested', 'wp-post-location' )
            );
        }

        $http_post = wp_parse_args( $_POST['wp_post_location'], array(
            'post_ID'       =>  0,
            'lat'           =>  '',
            'lng'           =>  '',
            'address'       =>  '',
            'country'       =>  '',
            'country_code'  =>  '',
            'zoom'          =>  15
        ) );

        extract( $http_post );

        if( ! current_user_can( 'edit_post', $post_ID ) ){
            $errors->add( 
                'no_permission', 
                esc_html__( 'Sorry, you are not allowed to edit this post.', 'wp-post-location' ) 
            );
        }

        if( ! get_post_status( $post_ID ) ){
            $errors->add( 
                'post_not_found', 
                esc_html__( 'Post was not found.', 'wp-post-location' ) 
            );            
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
        $errors = apply_filters( 'wp_post_location/update_location/errors', $errors, $http_post );

        if( $errors->get_error_code() ){
            return $errors;
        }

        $data = array(
            'latitude'      =>  $lat,
            'longitude'     =>  $lng,
            'address'       =>  $address,
            'country'       =>  $country,
            'country_code'  =>  $country_code,
            'zoom'          =>  $zoom
        );

        foreach ( $data as $key => $value) {
            update_post_meta( $post_ID, $key , $value );
        }

        /**
         *
         * Fires after updating post location
         *
         * @since 1.0.0
         * 
         */
        do_action( 'wp_post_location/updated_location', $post_ID, $data );

        return self::get_location( $post_ID );
    }

    public static function reset_location(){

        $errors = new WP_Error();

        $http_post = wp_parse_args( $_POST, array(
            'data'  =>  0
        ) );

        extract( $http_post );

        if( ! $data || ! current_user_can( 'edit_post', $data ) ){
            $errors->add( 
                'no_permission', 
                esc_html__( 'Sorry, you are not allowed to reset this post location.', 'wp-post-location' ) 
            );
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
        $errors = apply_filters( 'wp_post_location/reset_location/errors', $errors, $http_post );

        if( $errors->get_error_code() ){
            return $errors;
        }        

        delete_post_meta( $data, 'latitude' );
        delete_post_meta( $data, 'longitude' );
        delete_post_meta( $data, 'country' );
        delete_post_meta( $data, 'country_code' );
        delete_post_meta( $data, 'zoom' );
        delete_post_meta( $data, 'address' );

        return true;
    }

    /**
     *
     * AJAX update location
     * 
     * @since 1.0.0
     * 
     */
    public static function ajax_update_location(){

        check_ajax_referer( '_wpnonce' );   

        $results = self::update_location();

        if( is_wp_error( $results ) ){
            wp_send_json_error( $results );
        }

        $message = esc_html__( 'The location has been updated', 'wp-post-location' );

        wp_send_json_success( compact( 'results', 'message' ) );
    }

    public static function ajax_reset_location(){

        check_ajax_referer( '_wpnonce' );   
        
        $results = self::reset_location();

        if( is_wp_error( $results ) ){
            wp_send_json_error( $results );
        }

        $message = esc_html__( 'The location has been reset', 'wp-post-location' );

        wp_send_json_success( compact( 'results', 'message' ) );
    }

    /**
     *
     * AJAX get post locations
     * 
     * @since 1.0.0
     */
    public static function ajax_get_post_locations(){

        check_ajax_referer( '_wpnonce' );

        $http_get = wp_parse_args( $_GET, array() );

        $http_get['hide_empty_thumbnail'] = true;

        wp_send_json_success( self::get_post_locations( $http_get ) );
    }
}