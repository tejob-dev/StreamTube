<?php

/**
 * Rest API
 *
 * @link       https://1.envato.market/mgXE4y
 * @since      1.0.2
 *
 * @package    WP_Post_Like
 * @subpackage WP_Post_Like/includes
 */

/**
 *
 * @since      1.0.2
 * @package    WP_Post_Like
 * @subpackage WP_Post_Like/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */
if( ! defined( 'ABSPATH' ) ){
	exit;
}

class WP_Post_Like_Rest_API {
    /**
     *
     * Holds the namespace
     * 
     * @var string
     *
     * @since 1.0.2
     */
    private $namespace = 'wp-post-like/';

    /**
     *
     * Holds the verion
     * 
     * @var string
     *
     * @since 1.0.2
     */
    private $version    = 'v1';

    /**
     *
     * Holds the path
     * 
     * @var string
     *
     * @since 1.0.2
     */
    private $path       =   '/like';

    /**
     *
     * Get rest URL
     * 
     * @return string
     *
     * @since 1.0.2
     * 
     */
    public function get_rest_url(){
        return rest_url( "{$this->namespace}{$this->version}{$this->path}" );
    }

    /**
     * Actions
     */
    public function get_actions(){
        return array( 'like', 'dislike' );
    }

    /**
     *
     * Rest API check the queue
     * 
     * @since 1.0.2
     */
    public function rest_api_init(){

        register_rest_route(
            "{$this->namespace}{$this->version}",
            $this->path, 
            array(
                'methods'   =>  WP_REST_Server::CREATABLE,
                'callback'  =>  array( $this , 'create_item' ),
                'args'      =>  array(
                    'post_id' =>  array(
                        'validate_callback' => function( $param, $request, $key ) {
                            $param = (int)$param;
                            return $param == 0 ? false : true;
                        }
                    ),
                    'do_action' =>  array(
                        'validate_callback' => function( $param, $request, $key ) {
                            return is_string( $param ) && in_array( $param , $this->get_actions() ) ? true : false;
                        }
                    )
                ),
                'permission_callback'   =>  function( $request ){
                    if( ! is_user_logged_in() ){
                        return new WP_Error(
                            'not_logged_in',
                            sprintf(
                                esc_html__( 'Please log in to %s this post.', 'wp-post-like' ),
                                $request['submit']
                            )
                        );
                    }

                    return true;
                }
            )
        );      
    } 

    /**
     *
     * Create item
     * 
     * @param  WP_Rest_Request $request
     * @return JSON
     *
     * @since 1.0.2
     * 
     */
    public function create_item( $request ){

        $did_action     = '';

        $post_id        = $request['post_id'];
        $action         = $request['do_action'];
        $user_id        = get_current_user_id();

        $options        = WP_Post_Like_Customizer::get_options();

        $safe_click     = apply_filters( 'wp_post_like_safe_click', array(
            'expire'    =>  $options['safe_click'] ? absint( $options['safe_click_expire'] ) : false,
            'message'   =>  sprintf(
                esc_html__( 'Slow down, you have clicked the %s button so fast.', 'wp-post-like' ),
                ucwords( $action )
            )
        ), $request );

        $cache = sprintf( '%s-%s-%s', $user_id, $post_id, $action );

        if( false !== get_transient( $cache ) && is_array( $safe_click ) && is_int( $safe_click['expire'] ) ){
            wp_send_json_error( array(
                'message'   =>  $safe_click['message']
            ) );
        }

        if( ! in_array( get_post_type( $post_id ), $options['post_types'] ) ){
            wp_send_json_error( array(
                'message'   =>  esc_html__( 'Post Type is not supported', 'wp-post-like' )
            ) );
        }

        $query = new WP_Post_Like_Query();

        $has_reacted = $query->has_reacted( $post_id, $user_id );

        if( ! $has_reacted ){
            $results = $query->insert( compact( 'user_id', 'post_id', 'action' ) );
            $did_action = $action;
        }else{

            if( $action == $has_reacted[0]->action ){
                $results = $query->delete( $user_id, $post_id );

                $did_action= 'un' . $action;
            }
            else{
                $results = $query->update( compact( 'user_id', 'post_id', 'action' ) );

                $did_action = $action;
            }      
        } 

        if( ! $results ){
            wp_send_json_error( array(
                'message'   =>  esc_html__( 'Undefined error', 'wp-post-like' )
            ) );
        }

        if( is_array( $safe_click ) && is_int( $safe_click['expire'] ) ){
            set_transient( $cache, 'on', $safe_click['expire'] );
        }

        $results['progress'] = $query->get_progress( $post_id );

        /**
         *
         * @since 1.2
         * 
         */
        do_action( 'wp_post_like_action', $did_action, $action, $results, $post_id );

        wp_send_json_success( compact( 'results', 'did_action' ) );
    }
}