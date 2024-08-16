<?php
/**
 * Define the Watch Video hook functionality
 *
 *
 * @link       https://themeforest.net/user/phpface
 * @since      1.1
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

if( ! class_exists( 'myCRED_Hook' ) ){
    return;
}

class Streamtube_Core_myCRED_Hook_Watch_Video extends myCRED_Hook{

    const REF = 'watching_video';

    public $errors;

    /**
     *
     * Holds the recipient ID
     * 
     * @var integer
     */
    public $recipient_id   =   0;

    /**
     *
     * Holds the Post object 
     * 
     * @var WP_Post
     */
    public $post;

    /**
     *
     * Hold the player ID
     * 
     * @var string
     */
    public $player_id      =   '';

    /**
     *
     * Hold the ctype
     * 
     * @var string
     */
    public $ctype           =   '';    
    
    /**
    * Construct
    * Used to set the hook id and default settings.
    */
   
    function __construct( $hook_prefs, $type = MYCRED_DEFAULT_TYPE_KEY ) {

        parent::__construct( array(
            'id'        => 'streamtube_mycred_watch_video',
            'defaults'  => $this->_get_defaults()
        ), $hook_prefs, $type );
    }

    /**
     *
     * Default hook settings
     * 
     * @return array
     * 
     */
    private function _get_defaults(){
        return array(
            'creds'                 =>  1,
            'recipient'             =>  'viewer', // or owner
            'prevent_seeking'       =>  '',
            'percentage'            =>  0,
            'verified_user'         =>  '',
            'check_if_points_sent'  =>  '',
            'check_if_moderator'    =>  '',
            'roles'                 =>  '',
            'log'                   =>  esc_html__( 'Award %plural% for watching video', 'streamtube-core' ),
            'success_message'       =>  '',
            'warning_message'       =>  ''
        );
    }

    /**
     *
     * Get hook settings
     * 
     * @return array
     * 
     */
    private function _get_prefs(){
        $settings = get_option( "_mycred_hook_watch_video_{$this->mycred_type}", $this->prefs );

        return wp_parse_args( $settings, $this->_get_defaults() );
    }

    /**
     *
     * Update hook settings
     * 
     * @return update_option()
     * 
     */
    private function _update_prefs( $prefs ){
        return update_option( "_mycred_hook_watch_video_{$this->mycred_type}", $prefs );
    }

    /**
     *
     * Register `streamtube_mycred_watch_video`
     * 
     */
    public static function register( $installed, $point_type ){
        $installed['streamtube_mycred_watch_video'] = array(
            'title'        => esc_html__( '[StreamTube] %_plural% for Watching Video' , 'streamtube-core' ),
            'description'  => esc_html__( 'Award %_plural% for watching videos', 'streamtube-core' ),
            'callback'     => array( __CLASS__ )
        );

        return $installed;
    }    

    /**
    * Run
    * Fires by myCRED when the hook is loaded.
    * Used to hook into any instance needed for this hook
    * to work.
    */
    public function run() {

        add_action( 'mycred_register_assets',       array( $this, 'register_script' ) );

        add_action( 'mycred_front_enqueue_footer',  array( $this, 'enqueue_footer' ) );

        add_action( 'enqueue_embed_scripts', array( $this , 'enqueue_footer' ) );

        add_action( 'streamtube/player/file/setup', array( $this , 'load_player_plugin' ), 10, 2 );

        add_action( 'wp_ajax_streamtube/mycred/hook/watch_video',   array( $this , 'ajax_watch_video' ) );

        add_action( 'wp_ajax_nopriv_streamtube/mycred/hook/watch_video',   array( $this , 'ajax_watch_video' ) );
    }

    /**
     *
     * Register scripts
     * 
     */
    public function register_script(){

        wp_register_script( 
            'streamtube-mycred-watch-video', 
            plugin_dir_url( __FILE__ ) . 'public/js/watch-video.js', 
            array( 'jquery' ), 
            filemtime( plugin_dir_path( __FILE__  ) . 'public/js/watch-video.js' ), 
            true 
        );

    }

    /**
     *
     * Load scripts in footer
     * 
     */
    public function enqueue_footer(){
        wp_enqueue_script( 'streamtube-mycred-watch-video' );
    }

    /**
     *
     * Get wpnonce
     * 
     * @return string
     * 
     */
    private function _get_wpnonce(){
        return json_encode( $this->_get_prefs() );
    }

    /**
     *
     * Check if login required
     * 
     * @return boolean
     */
    private function require_login(){
        return apply_filters( 
            "streamtube/core/mycred/hook/watch_video/{$this->mycred_type}/require_login", 
            __return_true()
        );
    }

    /**
     *
     * Filter the player's plugins
     * 
     */
    public function load_player_plugin( $setup, $source ){

        $args = array(
            'prevent_seeking'       =>  wp_validate_boolean( $this->prefs['prevent_seeking'] ),
            'percentage'            =>  (int)$this->prefs['percentage'],
            'success_message'       =>  $this->show_notice( 'success' ),
            'success_icon'          =>  'icon-dollar',
            'warning_message'       =>  $this->show_notice( 'warning' ),
            'require_login'         =>  $this->require_login(),
            'is_logged_in'          =>  is_user_logged_in(),
            '_wpnonce'              =>  wp_create_nonce( $this->_get_wpnonce() ),
            'is_embed'              =>  is_embed(),
            'ctype'                 =>  $this->mycred_type
        );

        $args = apply_filters( 
            'streamtube/core/mycred/hook/watch_video/player_plugin_args', 
            $args, 
            $this->prefs, 
            $setup, 
            $source 
        );

        $setup['plugins']['mycred_watch_video'][ $this->mycred_type ] = $args;

        return $setup;
    }

    /**
     *
     * Get array of recipient
     * 
     * @return array
     */
    public function get_recipient_types(){
        return array(
            'viewer'    =>  esc_html__( 'Viewer', 'streamtube-core' ),
            'owner'     =>  esc_html__( 'Post Owner', 'streamtube-core' )
        );
    }

    /**
     *
     * Check roles and cap
     * 
     * @return boolean
     * 
     */
    public function has_role( $roles ){

        // Return true if roles field is empty
        if( ! $roles ){
            return true;
        }        

        if( is_string( $roles ) ){
            $roles = array_map( 'trim', explode( ',', $roles ) );
        }        

        $user_roles = get_userdata( $this->recipient_id )->roles;

        // Return false if current user does not have any roles
        if( ! $user_roles ){
            return false;
        }

        if( array_intersect( $user_roles, $roles ) ){
            return true;
        }

        // Check if roles are cap
        for ( $i = 0; $i < count( $roles ); $i++ ) { 
            if( current_user_can( $roles[$i] ) ){
                return true;
            }
        }

        return false;
    }

    public function is_verified(){

        $User = new Streamtube_Core_User();

        return $User->is_verified( $this->recipient_id );
    }

    /**
     *
     * Check if current logged in is post owner
     * 
     * @return boolean
     * 
     */
    public function is_owner(){
        if( get_current_user_id() == $this->post->post_author ){
            return true;
        }

        return false;
    }

    /**
     *
     * Check if notice is enabled
     * 
     * @param  string $type
     * @return boolean
     * 
     */
    public function show_notice( $type = 'success' ){

        $this->prefs        = $this->_get_prefs();

        return wp_validate_boolean( $this->prefs[ "{$type}_message" ] );
    }

    /**
     *
     * Get log data
     * 
     * @return string
     */
    public function get_data(){
        return sprintf('%s-user_%s', $this->player_id, get_current_user_id() );
    }

    /**
     *
     * Get point
     * 
     * @return object
     * 
     */
    public function _get_ctype(){
        return mycred( $this->mycred_type );
    }

    /**
     *
     * Send points
     * 
     */
    public function send_points(){

        $this->prefs = $this->_get_prefs();

        $log = $this->prefs['log'];

        if( is_object( $this->post ) ){
            $log = sprintf(
                '%s - %s (%s)',
                $log,
                '<a href="'. esc_url( get_permalink( $this->post->ID ) ) .'">'. $this->post->post_title .'</a>',
                $this->get_data()                
            );
        }

        /**
         * Filter the log
         */
        $log = apply_filters( 'streamtube/core/mycred/hook/watch_video/log', $log, $this->post );
                
        return mycred_add( 
            self::REF, 
            $this->recipient_id, 
            $this->prefs['creds'], 
            $log, 
            $this->post->ID, 
            $this->get_data(),
            $this->mycred_type
        );
    }

    /**
     *
     * Add points
     * 
     */
    public function watch_video( $post_id = 0, $player_id = 0 ){

        $this->prefs        = $this->_get_prefs();

        $this->errors       = new WP_Error();

        $this->player_id    = $player_id;

        $this->post         = get_post( $post_id );

        if( ! is_object( $this->post ) ){
            $this->errors->add(
                'post_not_found',
                esc_html__( 'Post was not found', 'streamtube-core' )
            );

            return $this->errors;
        }            

        if( $this->prefs['recipient'] == 'viewer' ){
            $this->recipient_id = get_current_user_id();    
        }
        else{
            $this->recipient_id = $this->post->post_author;
        }

        if( $this->prefs['roles'] && ! $this->has_role( $this->prefs['roles'] ) ){
            $this->errors->add(
                'not_allowed',
                sprintf(
                    esc_html__( 'You are not allow to receive %s for watching this video', 'streamtube-core' ),
                    $this->_get_ctype()->name['plural']
                )
            );
        }

        if( get_current_user_id() == $this->post->post_author ){
            $this->errors->add(
                'post_owner',
                sprintf(
                    esc_html__( 'You are the post owner and are not allow to receive %s for watching this video', 'streamtube-core' ),
                    $this->_get_ctype()->name['plural']
                )
            );
        }        

        if( wp_validate_boolean( $this->prefs['verified_user'] ) && ! $this->is_verified() ){
            $this->errors->add(
                'not_verified',
                sprintf(
                    esc_html__( 'Verify your account to receive %s for watching this video.', 'streamtube-core' ),
                    $this->_get_ctype()->name['plural']
                )
            );
        }

        /**
         *
         * Filter the query
         *
         * @param boolean $check_if_points_sent
         * @param int $recipient_id
         * @param WP_Post $post
         * @param string $player_id
         * 
         */
        $check_if_points_sent = apply_filters( 
            'streamtube/core/mycred/hook/watch_video/check_if_points_sent', 
            wp_validate_boolean( $this->prefs['check_if_points_sent']  ),
            $this->recipient_id,
            $this->post,
            $this->player_id,
            $this->mycred_type,
            $this->prefs
        );

        if( $check_if_points_sent && $this->has_entry( self::REF, $this->post->ID, $this->recipient_id, $this->get_data(), $this->mycred_type ) ){
            $this->errors->add(
                'points_sent',
                sprintf(
                    esc_html__( '%s were already sent', 'streamtube-core' ),
                    $this->_get_ctype()->name['plural']
                )
            );
        }

        if( wp_validate_boolean( $this->prefs['check_if_moderator'] ) && Streamtube_Core_Permission::moderate_posts() ){
            $this->errors->add(
                'moderator',
                sprintf(
                    esc_html__( 'You are the moderator and not allow to receive %s for watching this video', 'streamtube-core' ),
                    $this->_get_ctype()->name['plural']
                )
            );            
        }

        /**
         *
         * Filter instance
         *
         * @param object $this
         * 
         */
        do_action( 'streamtube/core/mycred/hook/watch_video', array( &$this ) );

        if( $this->errors->get_error_code() ){
            return $this->errors;
        }

        $response = $this->send_points();

        if( $response ){

            /**
             *
             * Filter instance
             *
             * @param object $this
             * 
             */
            do_action( 'streamtube/core/mycred/hook/watch_video/points_sent', array( &$this ) );

            return $response;
        }

        return new WP_Error(
            'undefined_error',
            esc_html__( 'Undefined Error', 'streamtube-core' )
        );
    }

    /**
     *
     * AJAX add points
     * 
     */
    public function ajax_watch_video(){

        $http_data = wp_parse_args( $_POST, array(
            'player_id'     =>  '',
            'post_id'       =>  0,
            'ctype'         =>  $this->mycred_type
        ) );

        extract( $http_data );

        $this->mycred_type = $ctype;

        check_ajax_referer( $this->_get_wpnonce(), '_wpnonce' );

        if( $this->require_login() && ! is_user_logged_in() ){
            wp_send_json_error( new WP_Error(
                'not_allowed',
                esc_html__( 'Not allowed', 'streamtube-core' )
            ) );
        }        

        $response = $this->watch_video( $post_id, $player_id );

        if( is_wp_error( $response ) ){
            wp_send_json_error( $response );
        }

        $ctype = $this->_get_ctype();

        if( $this->prefs['recipient'] == 'viewer' ){
            $message = sprintf(
                esc_html__( 'Awesome, you have received %1$s %2$s for watching %3$s', 'streamtube-core' ),
                $this->prefs['creds'],
                $this->prefs['creds'] > 1 ? $ctype->name['plural'] : $ctype->name['singular'],
                '<strong>'. esc_html( get_the_title( $post_id ) ) .'</strong>'
            );
        }else{
            $message = sprintf(
                esc_html__( '%s has received %s %s from watching this video', 'streamtube-core' ),
                get_userdata( $this->recipient_id )->display_name,
                $this->prefs['creds'],
                $this->prefs['creds'] > 1 ? $ctype->name['plural'] : $ctype->name['singular']       
            );
        }

        wp_send_json_success( compact( 'message' ) );
    }

    /**
    * Hook Settings
    * Needs to be set if the hook has settings.
    */
    public function preferences() {

        // Our settings are available under $this->prefs
        $prefs = $this->prefs;

        ?>
        <div class="hook-instance">
            <div class="row">
                <div class="col-6">
                    <div class="form-group mb-4">

                        <?php printf(
                            '<label for="%s">%s</label>',
                            esc_attr( $this->field_id( 'creds' ) ),
                            esc_html( $this->core->plural() )
                        );?>

                        <?php printf(
                            '<input type="text" name="%s" id="%s" value="%s" class="form-control">',
                            esc_attr( $this->field_name( 'creds' ) ),
                            esc_attr( $this->field_id( 'creds' ) ),
                            esc_attr( $this->core->number( $prefs['creds'] ) )
                        );?>

                    </div>
                </div>

                <div class="col-6">
                    <div class="form-group mb-4">

                        <?php printf(
                            '<label for="%s">%s</label>',
                            esc_attr( $this->field_id( 'recipient' ) ),
                            esc_html__( 'Recipient', 'streamtube-core' )
                        );?>

                        <?php printf(
                            '<select name="%s" id="%s" class="form-control">',
                            esc_attr( $this->field_name( 'recipient' ) ),
                            esc_attr( $this->field_id( 'recipient' ) )
                        );?>

                            <?php foreach ( $this->get_recipient_types() as $key => $value ): ?>
                                
                                <?php printf(
                                    '<option value="%s" %s>%s</option>',
                                    esc_attr( $key ),
                                    selected( $key, $prefs['recipient'], false ),
                                    esc_html( $value )
                                );?>

                            <?php endforeach ?>

                        </select>

                    </div>
                </div>

                <div class="col-6">
                    <div class="form-group mb-4">

                        <?php printf(
                            '<label for="%s">%s</label>',
                            esc_attr( $this->field_id( 'percentage' ) ),
                            esc_html__( 'Percentage of played video', 'streamtube-core' )
                        );?>

                        <?php printf(
                            '<input type="number" name="%s" id="%s" value="%s" class="form-control">',
                            esc_attr( $this->field_name( 'percentage' ) ),
                            esc_attr( $this->field_id( 'percentage' ) ),
                            esc_attr( $prefs['percentage'] )
                        );?>

                        <p>
                            <?php esc_html_e( 'E.g: 0, 25, 50, 75 or 100', 'streamtube-core' );?>
                        </p>

                    </div>
                </div>                  

                <div class="col-6">
                    <div class="form-group mb-4">

                        <?php printf(
                            '<label for="%s">%s</label>',
                            esc_attr( $this->field_id( 'log' ) ),
                            esc_html__( 'Log Template', 'streamtube-core' )
                        );?>

                        <?php printf(
                            '<input type="text" name="%s" id="%s" value="%s" class="form-control">',
                            esc_attr( $this->field_name( 'log' ) ),
                            esc_attr( $this->field_id( 'log' ) ),
                            esc_attr( $prefs['log'] )
                        );?>

                    </div>
                </div>     

                <div class="col-6">
                    <div class="form-group">

                        <?php printf(
                            '<label for="%s">%s</label>',
                            esc_attr( $this->field_id( 'percentage' ) ),
                            esc_html__( 'Roles (or capabilities)', 'streamtube-core' )
                        );?>

                        <?php printf(
                            '<input type="text" name="%s" id="%s" value="%s" class="form-control">',
                            esc_attr( $this->field_name( 'roles' ) ),
                            esc_attr( $this->field_id( 'roles' ) ),
                            esc_attr( $prefs['roles'] )
                        );?>

                        <p>
                            <?php esc_html_e( 'Only give awards for specific roles (or capabilities), separated by commas. Leave this field blank to allow all logged-in members (or all post owners)', 'streamtube-core' );?>
                        </p>

                    </div>
                </div>

                <div class="col-6">
                    <div class="form-group mb-4">

                        <div class="d-flex gap-2 align-items-center">
                            <?php printf(
                                '<input type="checkbox" name="%s" id="%s" %s>',
                                esc_attr( $this->field_name( 'prevent_seeking' ) ),
                                esc_attr( $this->field_id( 'prevent_seeking' ) ),
                                checked( 'on', $prefs['prevent_seeking'], false )
                            );?>

                            <?php printf(
                                '<label for="%s">%s</label>',
                                esc_attr( $this->field_id( 'prevent_seeking' ) ),
                                esc_html__( 'Prevent user from seeking progress bar', 'streamtube-core' )
                            );?>                        
                        </div>
                    </div>
                </div>                

                <div class="col-6">
                    <div class="form-group mb-4">

                        <div class="d-flex gap-2 align-items-center">
                            <?php printf(
                                '<input type="checkbox" name="%s" id="%s" %s>',
                                esc_attr( $this->field_name( 'verified_user' ) ),
                                esc_attr( $this->field_id( 'verified_user' ) ),
                                checked( 'on', $prefs['verified_user'], false )
                            );?>

                            <?php printf(
                                '<label for="%s">%s</label>',
                                esc_attr( $this->field_id( 'verified_user' ) ),
                                esc_html__( 'Only give award for verified viewers (or verified post owners)', 'streamtube-core' )
                            );?>                        
                        </div>
                    </div>
                </div>

                <div class="col-6">
                    <div class="form-group mb-4">

                        <div class="d-flex gap-2 align-items-center">
                            <?php printf(
                                '<input type="checkbox" name="%s" id="%s" %s>',
                                esc_attr( $this->field_name( 'check_if_points_sent' ) ),
                                esc_attr( $this->field_id( 'check_if_points_sent' ) ),
                                checked( 'on', $prefs['check_if_points_sent'], false )
                            );?>

                            <?php printf(
                                '<label for="%s">%s</label>',
                                esc_attr( $this->field_id( 'check_if_points_sent' ) ),
                                esc_html__( 'Do not give an award if current user (or post owner) has already received an award for the current post', 'streamtube-core' )
                            );?>                        
                        </div>
                    </div>
                </div>

                <div class="col-6">
                    <div class="form-group mb-4">

                        <div class="d-flex gap-2 align-items-center">
                            <?php printf(
                                '<input type="checkbox" name="%s" id="%s" %s>',
                                esc_attr( $this->field_name( 'check_if_moderator' ) ),
                                esc_attr( $this->field_id( 'check_if_moderator' ) ),
                                checked( 'on', $prefs['check_if_moderator'], false )
                            );?>

                            <?php printf(
                                '<label for="%s">%s</label>',
                                esc_attr( $this->field_id( 'check_if_moderator' ) ),
                                esc_html__( 'Do not give an award if current user is a moderator (administrator or editor)', 'streamtube-core' )
                            );?>                        
                        </div>
                    </div>
                </div>                

                <div class="col-6">
                    <div class="form-group mb-4">

                        <div class="d-flex gap-2 align-items-center">
                            <?php printf(
                                '<input type="checkbox" name="%s" id="%s" %s>',
                                esc_attr( $this->field_name( 'success_message' ) ),
                                esc_attr( $this->field_id( 'success_message' ) ),
                                checked( 'on', $prefs['success_message'], false )
                            );?>

                            <?php printf(
                                '<label for="%s">%s</label>',
                                esc_attr( $this->field_id( 'success_message' ) ),
                                esc_html__( 'Show a success message after viewer has received award', 'streamtube-core' )
                            );?>
                        </div>
                    </div>
                </div>              

                <div class="col-6">
                    <div class="form-group mb-4">

                        <div class="d-flex gap-2 align-items-center">
                            <?php printf(
                                '<input type="checkbox" name="%s" id="%s" %s>',
                                esc_attr( $this->field_name( 'warning_message' ) ),
                                esc_attr( $this->field_id( 'warning_message' ) ),
                                checked( 'on', $prefs['warning_message'], false )
                            );?>

                            <?php printf(
                                '<label for="%s">%s</label>',
                                esc_attr( $this->field_id( 'warning_message' ) ),
                                esc_html__( 'Also show a warning message immediately if the viewer is ineligible to receive the award', 'streamtube-core' )
                            );?>                        
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <?php

    }

    /**
    * Sanitize Preferences
    * If the hook has settings, this method must be used
    * to sanitize / parsing of settings.
    */
    public function sanitise_preferences( $data ) {

        $this->_update_prefs( $data );

        return $data;
    }
}