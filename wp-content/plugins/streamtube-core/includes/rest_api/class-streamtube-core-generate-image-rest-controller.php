<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define the rest functionality.
 *
 * @since      1.0.6
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */
class StreamTube_Core_Generate_Image_Rest_Controller extends StreamTube_Core_Rest_API{

    /**
     * @since 1.0.6
     */
    public function rest_api_init(){

        register_rest_route(
            "{$this->namespace}{$this->version}",
            '/generate-image',
            array(
                'methods'   =>  WP_REST_Server::CREATABLE,
                'callback'  =>  array( $this , 'create_item' ),
                'args'      =>  array(
                    'mediaid' =>  array(
                        'validate_callback' => function( $param, $request, $key ) {
                            return is_numeric( $param ) || is_string( $param );
                        }
                    ),
                    'parent'    =>  array(
                        'validate_callback' => function( $param, $request, $key ) {
                            return is_numeric( $param ) && get_post_type( $param ) == 'video';
                        }
                    ),
                    'type'    =>  array(
                        'validate_callback' => function( $param, $request, $key ) {
                            return is_string( $param ) && in_array( $param , array( 'image', 'animated_image' ));
                        }
                    )
                ),
                'permission_callback'   =>  function( $request ){
                    return current_user_can( 'edit_posts' );
                }
            )
        );          
    }

    /**
     *
     * Create item
     * 
     * @param  WP_Rest_Request $request
     * @since 1.0.6
     */
    public function create_item( $request ){

        if( ! $request['mediaid'] ){

            $Post = new Streamtube_Core_Post();

            $request['mediaid'] = $Post->get_source( $request['parent'] );
        }

         if( wp_attachment_is( 'video', $request['mediaid'] ) ){

            if( $request['type'] == 'image' ){
                return $this->generate_image_from_file( $request['mediaid'], $request );
            }else{
                return $this->generate_animated_image_from_file( $request['mediaid'], $request );
            }
        }
        else{
            return $this->generate_image_from_url( $request['mediaid'], $request );
        }
    }

    /**
     *
     * Generate thumbnail from given attachment
     * 
     * @param  int $attachment_id
     *
     * @since 1.0.6
     * 
     */
    private function generate_image_from_file( $attachment_id, $request ){

        $thumbnail_id = 0;

        if( has_post_thumbnail( $attachment_id ) ){
            $thumbnail_id = get_post_thumbnail_id( $attachment_id );
        }

        /**
         *
         * Filter thumbnail image ID
         * 
         */
        $thumbnail_id = apply_filters( 'streamtube/core/generate_image_from_file', $thumbnail_id, $attachment_id );

        if( is_wp_error( $thumbnail_id ) ){
            wp_send_json_error( $thumbnail_id );
        }

        if( empty( $thumbnail_id ) ){
            wp_send_json_error( new WP_Error(
                'thumbnail_id_not_found',
                esc_html__( 'Thumbnail Image was not found', 'streamtube-core' )
            ) );
        }

        if( is_int( $thumbnail_id ) && wp_attachment_is( 'image', $thumbnail_id ) && $request['parent'] ){
            set_post_thumbnail( $request['parent'], $thumbnail_id );

            wp_send_json_success( array(
                'post_id'       =>  $request['parent'],
                'thumbnail_url' =>  wp_get_attachment_image_url( $thumbnail_id, 'large' )
            ) );              
        }
    }

    /**
     *
     * Generate animated image from given attachment
     * 
     * @param  int $attachment_id
     *
     * @since 1.0.6
     * 
     */
    private function generate_animated_image_from_file( $attachment_id, $request ){

        $Post = new Streamtube_Core_Post();

        $thumbnail_url = '';

        if( "" != $maybe_thumbnail_url = $Post->get_thumbnail_image_url_2( $attachment_id ) ){
            if( attachment_url_to_postid( $maybe_thumbnail_url ) ){
                $thumbnail_url = $maybe_thumbnail_url;    
            }
        }

        /**
         *
         * Filter thumbnail image ID
         * 
         */
        $thumbnail_url = apply_filters( 'streamtube/core/generate_animated_image_from_file', $thumbnail_url, $attachment_id );

        if( is_wp_error( $thumbnail_url ) ){
            wp_send_json_error( $thumbnail_url );
        }

        if( empty( $thumbnail_url ) ){
            wp_send_json_error( new WP_Error(
                'thumbnail_not_found',
                esc_html__( 'Thumbnail Image was not found', 'streamtube-core' )
            ) );
        }

        if( is_string( $thumbnail_url ) && ! empty( $thumbnail_url ) && $request['parent'] ){

            $Post->update_thumbnail_image_url_2( $attachment_id, $thumbnail_url );
            $Post->update_thumbnail_image_url_2( $request['parent'], $thumbnail_url );

            wp_send_json_success( array(
                'post_id'       =>  $request['parent'],
                'thumbnail_url' =>  $thumbnail_url
            ) );              
        }

        wp_send_json_error( new WP_Error(
            'undefined_error',
            esc_html__( 'Undefined Error', 'streamtube-core' )
        ) );        
    }

    /**
     *
     * Generate thumbnail image from given URL
     * 
     * @param  string $url
     * @param  object $request
     *
     * 
     */
    private function generate_image_from_url( $url, $request ){

        $thumbnail_id = 0;

        /**
         *
         * Filter thumbnail image ID
         * 
         */
        $thumbnail_id = apply_filters( 'streamtube/core/generate_image_from_url', $thumbnail_id, $url );

        if( ! $thumbnail_id || is_wp_error( $thumbnail_id ) ){
            $oembed = new Streamtube_Core_oEmbed();

            $results = $oembed->generate_image( $request['parent'], $url );

            if( is_wp_error( $results ) ){
                wp_send_json_error( $results );
            }

            wp_send_json_success( array_merge( $results, array(
                'thumbnail_url' =>  wp_get_attachment_image_url( $results['thumbnail_id'], 'large' )
            ) ) );            
        }

        if( is_int( $thumbnail_id ) && wp_attachment_is( 'image', $thumbnail_id ) ){
            wp_send_json_success( array(
                'thumbnail_url' =>  wp_get_attachment_image_url( $thumbnail_id, 'large' )
            ) );
        }

        wp_send_json_error( new WP_Error(
            'undefined_error',
            esc_html__( 'Undefined Error', 'streamtube-core' )
        ) );
    }
}