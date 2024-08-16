<?php
/**
 * Rest
 *
 * @link       https://themeforest.net/user/phpface
 * @since      1.0.3
 *
 * @package    WP_User_Follow
 * @subpackage WP_User_Follow/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.3
 * @package    WP_User_Follow
 * @subpackage WP_User_Follow/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */

class WP_User_Follow_Rest_API {
    /**
     *
     * Holds the namespace
     * 
     * @var string
     *
     * @since 1.0.3
     */
    private $namespace = 'wp-user-follow/';

    /**
     *
     * Holds the verion
     * 
     * @var string
     *
     * @since 1.0.3
     */
    private $version    = 'v1';

    /**
     *
     * Holds the path
     * 
     * @var string
     *
     * @since 1.0.3
     */
    private $path       =   '/follow';

    /**
     *
     * Get rest URL
     * 
     * @return string
     *
     * @since 1.0.3
     * 
     */
    public function get_rest_url(){
        return rest_url( "{$this->namespace}{$this->version}{$this->path}" );
    }

    /**
     *
     * Plugin instance
     *
     * @since 1.0.3
     * 
     */
    private function plugin(){
        return run_wp_user_follow()->get();
    }

    /**
     *
     * Rest API check the queue
     * 
     * @since 1.0.3
     */
    public function rest_api_init(){

        register_rest_route(
            "{$this->namespace}{$this->version}",
            $this->path, 
            array(
                'methods'   =>  WP_REST_Server::CREATABLE,
                'callback'  =>  array( $this , 'create_item' ),
                'args'      =>  array(
                    'user_id' =>  array(
                        'validate_callback' => function( $param, $request, $key ) {
                            return is_numeric( $param ) || get_userdata( $param );
                        }
                    )
                ),
                'permission_callback'   =>  function( $request ){
                    if( ! is_user_logged_in() ){
                        return new WP_Error(
                            'not_logged_in',
                            esc_html__( 'Please log in to follow this user.', 'wp-user-follow' )
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
     * @since 1.0.3
     * 
     */
    public function create_item( $request ){

        $follower_id = get_current_user_id();
        $following_id = $request['user_id'];

        $results = $this->plugin()->query->_follow( $follower_id, $following_id );

        if( is_wp_error( $results ) ){
            wp_send_json_error( array(
                'message'   =>  $results->get_error_messages()
            ) );
        }

        $button = wpuf_button_follow( array(
            'user_id'   =>  $following_id,
            'echo'      =>  false
        ) );

        wp_send_json_success( compact( 'results', 'button' ) );
    }
}