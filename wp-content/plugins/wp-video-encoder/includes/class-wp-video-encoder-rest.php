<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define the rest functionality.
 *
 *
 * @since      1.0.0
 * @package    WP_Video_Encoder
 * @subpackage WP_Video_Encoder/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */
class WP_Video_Encoder_Rest{

    /**
     *
     * Holds the namespace
     * 
     * @var string
     */
    private $namespace = 'wp-video-encoder/';

    /**
     *
     * Holds the verion
     * 
     * @var string
     */
    private $version = 'v1';

    protected $Queue;

    /**
     *
     * Plugin instance
     * 
     */
    public function __construct(){
        $this->Queue = new WP_Video_Encoder_Queue();
    }

    /**
     *
     * Rest API check the queue
     * 
     * @since 1.0.0
     */
    public function rest_api_init(){

        register_rest_route(
            "{$this->namespace}{$this->version}",
            '/encoding/', 
            array(
                'methods'   =>  WP_REST_Server::READABLE,
                'callback'  =>  function( $data ){
                    return $this->Queue->run_queue_items();
                },
                'args'      =>  array(
                    'attachment_id' =>  array(
                        'validate_callback' => function( $param, $request, $key ) {
                          return is_numeric( $param );
                        }
                    ),
                    'parent'    =>  array(
                        'validate_callback' => function( $param, $request, $key ) {
                          return is_numeric( $param );
                        }
                    )
                ),
                'permission_callback'   =>  function( $request ){
                    return current_user_can( 'upload_files' );
                }
            )
        ); 

        register_rest_route(
            "{$this->namespace}{$this->version}",
            '/queue/', 
            array(
                'methods'   =>  WP_REST_Server::CREATABLE,
                'callback'  =>  function( $data ){
                    return $this->Queue->requeue_item( $data['attachment_id'] );
                },
                'args'      =>  array(
                    'attachment_id' =>  array(
                        'validate_callback' => function( $param, $request, $key ) {
                          return is_numeric( $param );
                        },
                        'required'  =>  true
                    )
                ),
                'permission_callback'   =>  function( $request ){
                    return current_user_can( 'edit_others_posts' );
                }
            )
        );
    }
}