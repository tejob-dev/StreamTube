<?php
/**
 * Define the User Privacy functionality
 *
 *
 * @link       https://themeforest.net/user/phpface
 * @since      1.0.0
 *
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 */

/**
 * Define the profile functionality
 *
 * @since      1.0.0
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */
if( ! defined('ABSPATH' ) ){
    exit;
}

class Streamtube_Core_User_Privacy extends Streamtube_Core_User {
    /**
     *
     * Get addon settings
     * 
     * @return object
     * 
     */
    public function get_settings(){

        $settings = (object)array(
            'enable'                        =>  get_option( 'account_deactivation_enable' ),
            'deactivation_terms'            =>  get_option( 'account_deactivation_terms', (int)get_option( 'wp_page_for_privacy_policy' ) ),
            'deactivation_period'           =>  get_option( 'account_deactivation_period', 30 ),
            'prevent_multi_deactivation'    =>  get_option( 'account_prevent_multi_deactivation', 60*60 ),
            'reactivation'                  =>  get_option( 'account_reactivation', 'manual' ),
            'reactivation_terms'            =>  get_option( 'account_reactivation_terms', (int)get_option( 'wp_page_for_privacy_policy' ) )
        );

        $settings->deactivation_period          = absint( $settings->deactivation_period );
        $settings->prevent_multi_deactivation   = absint( $settings->prevent_multi_deactivation );

        /**
         *
         * Allow filtering settings for further development
         *
         * @param object $settings
         * 
         */
        return apply_filters( 'streamtube/core/user/privacy/settings', $settings );
    }

    /**
     *
     * Check if given user is deactivated
     * 
     * @param  integer $user_id
     * @return boolean
     * 
     */
    public function is_deactivated( $user_id = 0 ){

        if( ! $user_id ){
            $user_id = get_current_user_id();
        }

        if( user_can( $user_id, 'administrator' ) ){
            return false;
        }

        if( user_can( $user_id, Streamtube_Core_Permission::ROLE_DEACTIVATE ) ){
            return true;
        }

        return false;
    }

    /**
     *
     * Do deactivate user
     * 
     * @param  integer $user_id
     * @return true|WP_Error
     * 
     */
    public function deactivate( $user_id = 0 ){

        $errors = new WP_Error();

        if( user_can( $user_id, 'administrator' ) ){
            $errors->add(
                'cannot_deactivate_admin',
                esc_html__( 'Cannot deactivate Administrator role', 'streamtube-core' )
            );
        }

        $user_data = get_userdata( $user_id );

        if( ! $user_data ){
            $errors->add(
                'user_not_found',
                esc_html__( 'User was not found', 'streamtube-core' )
            );
        }

        /**
         *
         * Filter the errors
         *
         * @param int $user_id
         * @param WP_User
         * 
         */
        $errors = apply_filters( 'streamtube/core/deactivate_user/errors', $errors, $user_id, $user_data );

        if( $errors->get_error_code() ){
            return $errors;
        }

        /**
         *
         * Fires before deactivating user
         *
         * @param int $user_id
         * 
         */
        do_action( 'streamtube/core/before_deactivate_user', $user_id, $user_data );

        // Update current time for further checking
        update_user_meta( $user_id, '_deactivate_time', current_time( 'mysql' ) );
        update_user_meta( 
            $user_id, 
            '_delete_time', 
            date( 'Y-m-d H:i:s', strtotime( sprintf( "+%s days", absint( $this->get_settings()->deactivation_period ) ) ) ) 
        );

        if( $user_data->roles ){
            update_user_meta( $user_id, '_old_roles', $user_data->roles );
            
            for ( $i=0; $i < count( $user_data->roles ); $i++) { 
                Streamtube_Core_Permission::remove_user_role( $user_id, $user_data->roles[$i] );
            }   
        }

        Streamtube_Core_Permission::add_user_role( 
            $user_id, 
            Streamtube_Core_Permission::ROLE_DEACTIVATE 
        );

        /**
         *
         * Fires after deactivating user
         *
         * @param int $user_id
         * 
         */
        do_action( 'streamtube/core/after_deactivated_user', $user_id, $user_data );
    }

    /**
     *
     * Do reactivate user
     * 
     * @param  integer $user_id
     * @return true|WP_Error
     * 
     */
    public function reactivate( $user_id = 0 ){

        $errors = new WP_Error();

        if( user_can( $user_id, 'administrator' ) ){
            $errors->add(
                'cannot_reactivate_admin',
                esc_html__( 'Cannot reactivate admin account', 'streamtube-core' )
            );
        }

        $user_data = get_userdata( $user_id );

        if( ! $user_data ){
            $errors->add(
                'user_not_found',
                esc_html__( 'User was not found', 'streamtube-core' )
            );
        }       

        /**
         *
         * Filter the errors
         *
         * @param int $user_id
         * @param WP_User
         * 
         */
        $errors = apply_filters( 'streamtube/core/reactivate_user/errors', $errors, $user_id, $user_data );

        if( $errors->get_error_code() ){
            return $errors;
        }        

        /**
         *
         * Fires before reactivating user
         *
         * @param int $user_id
         * 
         */
        do_action( 'streamtube/core/before_reactivate_user', $user_id, $user_data );

        if( "" != $old_roles = get_user_meta( $user_id, '_old_roles', true ) ){
            for ( $i=0;  $i < count( $old_roles );  $i++ ) { 

                if( $old_roles[$i] != 'administrator' ){
                    Streamtube_Core_Permission::add_user_role( $user_id, $old_roles[$i] );
                }
            }
        }

        Streamtube_Core_Permission::remove_user_role( 
            $user_id, 
            Streamtube_Core_Permission::ROLE_DEACTIVATE 
        );

        delete_user_meta( $user_id, '_deactivate_time' );
        delete_user_meta( $user_id, '_delete_time' );
        delete_user_meta( $user_id, '_old_roles' );        

        /**
         *
         * Fires after reactivating user
         *
         * @param int $user_id
         * 
         */
        do_action( 'streamtube/core/after_reactivated_user', $user_id, $user_data );
    }

    /**
     *
     * Verify user's plain text password
     *
     * @return boolean
     * 
     */
    private function verify_password( $password, $user_id ){

        $user_data = get_userdata( $user_id );

        return wp_check_password( $password, $user_data->user_pass, $user_id );
    }

    /**
     *
     * Get expired users
     * 
     * @return get_users()
     * 
     */
    public function get_expired_users(){
        return get_users( array(
            'role__in'      =>  Streamtube_Core_Permission::ROLE_DEACTIVATE,
            'meta_query'    =>  array(
                array(
                    'key'       =>  '_delete_time',
                    'value'     =>  current_time( 'mysql' ) ,
                    'compare'   =>  '<=',
                    'type'      =>  'DATETIME'
                )
            )
        ) );
    }

    /**
     *
     * Do AJAX deactivate user
     * 
     */
    public function ajax_deactivate(){

        $settings  = $this->get_settings();

        if( 
            ! wp_verify_nonce( $_POST['deactivate_account'], 'deactivate_account' ) ||
            ! $settings->enable ||
            ! isset( $_POST['password'] )
        ){
            wp_send_json_error( new WP_Error(
                'invalid_requested',
                esc_html__( 'Invalid Requested', 'streamtube-core' )
            ) );
        }

        $user_id = get_current_user_id();

        if( false !== get_transient( "_deactivate_{$user_id}" ) && $settings->prevent_multi_deactivation ){
            wp_send_json_error( new WP_Error(
                'slowdown',
                esc_html__( 'Please slow down, you are clicking too fast.', 'streamtube-core' )
            ) );
        }        

        if( ! $this->verify_password( wp_unslash( $_POST['password'] ), $user_id ) ){
            wp_send_json_error( new WP_Error(
                'incorrect_password',
                esc_html__( 'Password is incorrect', 'streamtube-core' )
            ) );
        }

        if( $settings->deactivation_period ){
            $check = $this->deactivate( get_current_user_id() );    
        }
        else{
            $check = $this->delete_user( get_current_user_id() );
        }
        
        if( is_wp_error( $check ) ){
            wp_send_json_error( $check );
        }

        if( $settings->deactivation_period && $settings->prevent_multi_deactivation ){
            set_transient( "_deactivate_{$user_id}", current_time( 'mysql' ), $settings->prevent_multi_deactivation );
        }

        wp_send_json_success( array(
            'message'       =>  esc_html__( 'You have been successfully deactivated', 'streamtube-core' ),
            'did_action'    =>  $this->get_settings()->deactivation_period ? 'deactivated' : 'deleted'
        ) );
    }

    /**
     *
     * Do AJAX reactivate user
     * 
     */
    public function ajax_reactivate(){

        $settings  = $this->get_settings();

        if( 
            ! wp_verify_nonce( $_POST['reactivate_account'], 'reactivate_account' ) ||
            ! $settings->enable ||
            $settings->reactivation != 'manual'
        ){

            wp_send_json_error( new WP_Error(
                'invalid_requested',
                esc_html__( 'Invalid Requested', 'streamtube-core' )
            ) );
        }

        $check = $this->reactivate( get_current_user_id() );

        if( is_wp_error( $check ) ){
            wp_send_json_error( $check );
        }

        wp_send_json_success( array(
            'message'   =>  esc_html__( 'You have been successfully reactivated', 'streamtube-core' )
        ) );
    }

    /**
     *
     * AJAX admin deactivate/reactivate user account manually
     * 
     */
    public function ajax_admin_deactivate_user(){
        check_ajax_referer( '_wpnonce' );

        if( ! current_user_can( 'administrator' ) || ! $_POST['user_id'] ){
            wp_send_json_error( new WP_Error(
                'no_permission',
                esc_html__( 'You do not have permission to deactivate this user.', 'streamtube-core' ) 
            ) );
        }

        $user_id = (int)$_POST['user_id'];

        if( $this->is_deactivated( $user_id ) ){
            $result = $this->reactivate( $user_id );
        }
        else{
            $result = $this->deactivate( $user_id );
        }

        if( is_wp_error( $result ) ){
            wp_send_json_error( $result );
        }

        wp_send_json_success( array(
            'message'   =>  esc_html__( 'OK', 'streamtube-core' ),
            'button'    =>  $this->get_action_button( $user_id )
        ) );          
    }

    /**
     *
     * Do delete user
     * 
     * @param  integer $user_id
     * 
     */
    public function delete_user( $user_id = 0 ){

        $errors = new WP_Error();

        if( user_can( $user_id, 'administrator' ) ){
            $errors->add(
                'cannot_delete_admin',
                esc_html__( 'Cannot delete admin account', 'streamtube-core' )
            );
        }

        /**
         *
         * Filter the errors
         *
         * @param int $user_id
         * @param WP_User
         * 
         */
        $errors = apply_filters( 'streamtube/core/delete_user/errors', $errors, $user_id );

        if( $errors->get_error_code() ){
            return $errors;
        }

        // Always check if given user is an administrator
        if( ! user_can( $user_id, 'administrator' ) ){

            if( ! function_exists( 'wp_delete_user' ) ){
                include ABSPATH . 'wp-admin/includes/user.php';
            }  

            /**
             *
             * Fires before deleting deactivated user
             *
             * @param int $user
             * 
             */
            do_action( 'streamtube/core/before_delete_deactivated_user', $user_id );

            return wp_delete_user( $user_id, null );
        }

        return false;
    }

    /**
     *
     * Hooked into "delete_expired_transients" cron job 
     * To automatically delete deactivated accounts
     * 
     */
    public function schedule_delete_users(){

        $users = $this->get_expired_users();

        if( ! $users ){
            return;
        }

        foreach ( $users as $user ) {
            $this->delete_user( $user->ID );
        }
    }

    public function get_action_button( $user_id = 0 ){

        $is_deactivated = $this->is_deactivated( $user_id );

        return sprintf(
            '<button type="button" class="button button-%s button-small button-deactivate" data-user-id="%s">%s</button>',
            $is_deactivated ? 'white alert-danger' : 'secondary',
            esc_attr( $user_id ),
            $is_deactivated ? esc_html__( 'Deactivated', 'streamtube-core' ) : esc_html__( 'N/A', 'streamtube-core' )
        );
    }

    /**
     *
     * Add "Account" menu to Dashboard > Settings page
     * 
     * @param array $menu_items
     * 
     */
    public function add_dashboard_settings_menu( $menu_items ){

        if( $this->get_settings()->enable ){
            $menu_items['settings']['submenu']['account'] = array(
                'title'     =>  esc_html__( 'Account Privacy', 'streamtube-core' ),
                'icon'      =>  'icon-user-times',
                'callback'  =>  function(){
                    streamtube_core_load_template( 'user/dashboard/settings/account.php' );
                },
                'priority'  =>  50
            );
        }

        return $menu_items;
    }

    /**
     *
     * Filter the dashboard menu
     * Remove all menu items except the Dashboard and Settings items
     * 
     */
    public function filter_dashboard_menu_item( $menu_items ){
        if( $this->is_deactivated() ){
            // Reset the dashboard menu item
            $menu_items = array(
                'dashboard' =>  $menu_items['dashboard'],
                'settings'  =>  $menu_items['settings']
            );
        }

        return $menu_items;
    }

    /**
     *
     * Filter the dashboard menu
     * Remove all menu items except the Dashboard and Settings items
     * 
     */
    public function filter_profile_menu_item( $menu_items ){
        if( $this->is_deactivated() ){
            // Reset the profile menu item
            $menu_items = array(
                'profile'  =>  $menu_items['profile']
            );
        }

        return $menu_items;
    }    

    /**
     *
     * Show an warning message on backend
     * 
     */
    public function display_notices(){
        if( $this->is_deactivated() ) : ?>
            <div class="alert alert-danger p-3">
                <p class="m-0">
                    <?php printf(
                        esc_html__( 'Your account was deactivated %s ago and will be permanently deleted after %s.', 'streamtube-core' ),
                        human_time_diff( 
                            current_time( 'timestamp' ), 
                            strtotime( get_user_meta( get_current_user_id(), '_deactivate_time', true ) )
                        ),
                        date( "F j, Y g:i A", strtotime( get_user_meta( get_current_user_id(), '_delete_time', true ) ) )
                    );?>
                </p>          
            </div>
        <?php endif;
    }
}