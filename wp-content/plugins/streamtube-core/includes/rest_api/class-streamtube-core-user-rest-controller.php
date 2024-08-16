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
class StreamTube_Core_User_Rest_Controller extends StreamTube_Core_Rest_API{

    protected $path     =   '/user';

    /**
     * @since 1.0.6
     */
    public function rest_api_init(){

        register_rest_route(
            "{$this->namespace}{$this->version}",
            $this->path . '/update-profile', 
            array(
                'methods'   =>  WP_REST_Server::CREATABLE,
                'callback'  =>  array( $this , 'update_profile' ),
                'permission_callback'   =>  function( $request ){
                    return is_user_logged_in();
                }
            )
        ); 

        register_rest_route(
            "{$this->namespace}{$this->version}",
            $this->path . '/update-social-profiles', 
            array(
                'methods'   =>  WP_REST_Server::CREATABLE,
                'callback'  =>  array( $this , 'update_social_profiles' ),
                'permission_callback'   =>  function( $request ){
                    return is_user_logged_in();
                }
            )
        );

        register_rest_route(
            "{$this->namespace}{$this->version}",
            $this->path . '/upload-photo', 
            array(
                'methods'   =>  WP_REST_Server::CREATABLE,
                'callback'  =>  array( $this , 'upload_photo' ),
                'permission_callback'   =>  function( $request ){
                    return is_user_logged_in();
                }
            )
        );
    }

    /**
     *
     * Update profile
     * 
     * @param WP_Rest_Request $request
     * @return JSON
     *
     * @since 1.0.6
     */
    public function update_profile( $request ){

        if( ! function_exists( 'edit_user' ) ){
            require_once ABSPATH . 'wp-admin/includes/user.php';    
        }

        if( apply_filters( 'remove_pre_user_description', true ) === true ){
            remove_filter( 'pre_user_description', 'wp_filter_kses' );    
        }

        $results = edit_user( get_current_user_id() );

        if( is_wp_error( $results ) ){
            wp_send_json_error( array(
                'message'   =>  $results->get_error_messages()
            ) );
        }

        wp_send_json_success( array(
            'message'   =>  esc_html__( 'Profile updated.', 'streamtube-core' )
        ) );             
    }

    /**
     *
     * Update Social Profiles controller
     *
     * @since 2.2.1
     * 
     */
    public function update_social_profiles( $request ){

        if( ! isset( $_POST['socials'] ) || ! is_array( $_POST['socials'] ) ){
            wp_send_json_error( array(
                'message'   =>  esc_html__( 'Invalid Requested.', 'streamtube-core' )
            ) );
        }

        update_user_meta( get_current_user_id(), '_socials', wp_unslash( $_POST['socials'] ) );

        wp_send_json_success( array(
            'message'   =>  esc_html__( 'Profile updated.', 'streamtube-core' )
        ) );
    }

    /**
     *
     * Upload profile photo and avatar
     * 
     * @param WP_Rest_Request $request
     * @return JSON
     *
     * @since 1.0.6
     * 
     */
    public function upload_photo( $request ){

        $user = new Streamtube_Core_User();
        
        $attachment_id = $user->upload_photo();

        if( is_wp_error( $attachment_id ) ){
            wp_send_json_error( array(
                'error'     =>  $attachment_id,
                'message'   =>  $attachment_id->get_error_messages()
            ) );
        }

        $response = array(
            'action'    =>  'reload',
            'message'   =>  esc_html__( 'Image has been uploaded.', 'streamtube-core' ),
            'field'     =>  $request['field']
        );

        if( $response['field'] == 'avatar' ){
            $response['output'] = get_avatar( get_current_user_id(), 200 );
        }
        else{
            $response['output'] = $user->get_profile_photo( array(
                'user_id'   =>  get_current_user_id(),
                'link'      =>  false,
                'echo'      =>  false
            ) );
        }

        wp_send_json_success( $response );
    }

}