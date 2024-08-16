<?php
/**
 * Restrict Content
 *
 * @link       https://themeforest.net/user/phpface
 * @since      1.0.0
 *
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 */

/**
 *
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */

if( ! defined('ABSPATH' ) ){
    exit;
}

class Streamtube_Core_Restrict_Content{
    /**
     *
     * Holds the nonce name
     * 
     * @var string
     *
     * @since 1.0.9
     */
    const NONCE     = 'restrict_content_nonce';

    /**
     *
     * Holds the meta field name
     *
     * @since 1.0.9
     * 
     */
    const META_KEY  = 'restrict_content';

    /**
     *
     * Holds post types
     * 
     * @var array
     *
     * @since 1.0.9
     */
    protected $post_types = array( 'video' );

    protected $Post;

    protected $license;

    /**
     * Get plugin objects
     */
    public function __construct(){

        $this->Post = new Streamtube_Core_Post();
    }

    /**
     *
     * Get post types
     * 
     * @return array
     *
     * @since 1.0.9
     * 
     */
    public function get_post_types(){
        return $this->post_types;
    }

    /**
     *
     * Get Apply For options
     * 
     * @return array
     *
     * @since 1.0.9
     * 
     */
    public function apply_for_options(){
        return array(
            ''              =>  esc_html__( 'None', 'streamtube-core' ),
            'inherit'       =>  esc_html__( 'Global settings', 'streamtube-core' ),
            'logged_in'     =>  esc_html__( 'Logged In Users', 'streamtube-core' ),
            'roles'         =>  esc_html__( 'Custom Roles', 'streamtube-core' ),
            'capabilities'  =>  esc_html__( 'Custom Capabilities', 'streamtube-core' )
        );
    }

    /**
     *
     * Get WP editable roles
     * 
     * @return array
     *
     * @since 1.0.9
     * 
     */
    public function get_editable_roles(){
        $roles = wp_roles()->roles;

        if( is_array( $roles ) ){
            unset( $roles['administrator'] );
            unset( $roles['editor'] );
        }

        return $roles;
    }

    /**
     *
     * Get operator options
     * 
     * @return array
     *
     * @since 1.0.9
     * 
     */
    public function get_operator_options(){
        return array(
            'or'    =>  esc_html__( 'OR', 'streamtube-core' ),
            'and'    =>  esc_html__( 'AND', 'streamtube-core' )   
        );
    }

    /**
     *
     * Get no permission message
     * 
     * @return string
     *
     * @since 1.0.9
     * 
     */
    public function get_no_permission_message(){

        $target = is_embed() ? 'target="_blank"' : '';

        $settings = $this->get_global_settings();

        $message = sprintf(
            esc_html__( 'Sorry, you cannot view this %s content.', 'streamtube-core' ),
            get_post_type()
        );

        $message .= '<br/>';

        $message .= sprintf(
            '<a class="btn btn-danger text-white mt-3" %s>%s</a>',
            $settings['join_us_url'] ? $target . ' href="'.esc_url($settings['join_us_url']).'"' : 'data-bs-toggle="modal" data-bs-target="#modal-join-us"',
            esc_html__( 'Join Us', 'streamtube-core' )
        );

        if( ! $settings['join_us_url'] ){
            /**
             * @since 1.0.9
             */
            do_action( 'streamtube/core/content_restriction/default_join_us' );
        }

        /**
         *
         * @since 1.0.9
         * 
         */
        return apply_filters( 'streamtube/core/content_restriction/no_permission_message', $message );
    }

    /**
     *
     * Get login message
     * 
     * @return string
     *
     * @since 1.0.9
     * 
     */
    public function get_login_message(){

        $message = sprintf( '<h3>%s</h3>', esc_html__( 'Login Required', 'streamtube-core' ) );

        $message .= sprintf(
            esc_html__( 'Please %s to view %s content.', 'streamtube-core' ),
            sprintf(
                '<a target="%s" class="text-white" href="%s">%s</a>',
                is_embed() ? '_blank' : '_self',
                esc_url( wp_login_url( get_permalink() ) ),
                esc_html__( 'log in', 'streamtube-core' )
            ),
            get_post_type()
        );

        /**
         *
         * @since 1.0.9
         * 
         */
        return apply_filters( 'streamtube/core/content_restriction/login_message', $message );
    }

    /**
     *
     * Get notice message
     * 
     * @param  string $message
     * @param  string $code
     * @return string
     *
     * @since 1.0.9
     * 
     */
    public function get_notice_message( $wp_errors, $setup ){
        /**
         *
         * @since 1.0.9
         * 
         */
        $wp_errors = apply_filters( 'streamtube/core/content_restriction/permission_errors', $wp_errors );

        $output = sprintf(
            '<div class="no-permission error-message %s">',
            esc_attr( $wp_errors->get_error_code() ),
        );

            $output .= '<div class="position-absolute top-50 start-50 translate-middle center-x center-y">';

                $output .= implode( '<br/>' , $wp_errors->get_error_messages() );

            $output .= '</div>';

        $output .= '</div>';

        $output .= sprintf(
            '<div class="player-poster bg-cover" style="background-image:url(%s)"></div>',
            $setup['poster2'] ? $setup['poster2'] : $setup['poster']
        );

        return apply_filters( 'streamtube/core/content_restriction/notice_message', $output, $wp_errors );
    }

    /**
     *
     * Add metaboxes
     *
     * @since 1.0.9
     * 
     */
    public function metaboxes(){
        add_meta_box(
            'streamtube-content_restriction',
            esc_html__( 'Content Restriction', 'streamtube-core' ),
            array( $this , 'metaboxes_html' ),
            $this->get_post_types(),
            'advanced',
            'high'
        );  
    }

    /**
     *
     * The metabox html
     * 
     * @param  object $post
     * @since 1.0.9
     * 
     */
    public function metaboxes_html( $post ){

        $settings = $this->get_global_settings();

        if( ! $settings['enable'] ){

            printf(
                esc_html__( 'Content Restriction is disabled, %s.', 'streamtube-core' ),
                sprintf(
                    '<a href="%s">%s</a>',
                    esc_url( admin_url( '/customize.php?autofocus[section]=restrict_content' ) ),
                    esc_html__( 'enable the feature', 'streamtube-core' )
                )
            );
        }
        else{
            include trailingslashit( STREAMTUBE_CORE_ADMIN_PARTIALS ) . 'restrict-content.php';   
        }  
    }

    /**
     *
     * Save video data
     * 
     * @param  int $post_id
     * @since 1.0.9
     * 
     */
    public function save_data( $post_id ){

        if ( ! isset( $_POST[ self::NONCE ] ) || ! wp_verify_nonce( $_POST[ self::NONCE ], self::NONCE ) ){
            return;
        }

        if( ! current_user_can( 'edit_post', $post_id ) ){
            return;
        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        if( ! in_array( get_post_type( $post_id ), $this->get_post_types() ) ){
            return;
        }

        if( ! array_key_exists( 'data', $_POST ) ){
            return;
        }

        return update_post_meta( $post_id, self::META_KEY, wp_unslash( $_POST[ 'data' ] ) );
    }

    /**
     *
     * Get global data
     * 
     * @return array
     *
     * @since 1.0.9
     */
    public function get_global_settings(){

        $roles = array();

        $default = array(
            'enable'        =>  '',
            'apply_all'     =>  '',
            'join_us_url'   =>  '',
            'roles'         =>  array(),
            'capabilities'  =>  array(),
            'operator'      =>  'or'
        );

        $settings = (array)get_option( self::META_KEY, $default );

        if( ! array_key_exists( 'enable', $settings ) ){
            $settings['enable'] = '';
        }

        if( ! array_key_exists( 'apply_all', $settings ) ){
            $settings['apply_all'] = '';
        }

        if( ! array_key_exists( 'join_us_url', $settings ) ){
            $settings['join_us_url'] = '';
        }

        if( $settings['join_us_url'] && get_post_status( $settings['join_us_url'] ) == 'publish' ){
            $settings['join_us_url'] = get_permalink( $settings['join_us_url'] );
        }
        else{
            $settings['join_us_url'] = '';
        }

        if( array_key_exists( 'roles', $settings ) && is_array( $settings['roles'] ) ){
            foreach ( $settings['roles'] as $role => $value ) {
                if( $value ){
                    $roles[] = $role;
                }
            }
        }

        $settings['roles'] = $roles;

        if( ! wp_cache_get( 'streamtube:license' ) ){
            return $default;
        }

        /**
         * Filtr settings
         */
        return apply_filters( 'streamtube/core/content_restriction/global_settings', $settings );
    }    

    /**
     *
     * Get post data
     * 
     * @param  integer $post_id
     * @return array
     *
     * @since 1.0.9
     * 
     */
    public function get_post_data( $post_id = 0, $global = false ){

        $default = array(
            'apply_for'     =>  '',
            'roles'         =>  array(),
            'capabilities'  =>  array(),
            'operator'      =>  'or'
        );

        if( ! $post_id ){
            $post_id = get_the_ID();
        }

        $post_data = (array)get_post_meta( $post_id, self::META_KEY, true );

        $post_data = array_merge( $default, $post_data );

        $post_data = $post_data;

        if( is_string( $post_data['roles'] ) ){
            $post_data['roles'] = explode( ',', $post_data['roles'] );
        }

        if( is_string( $post_data['capabilities'] ) ){
            $post_data['capabilities'] = array_map('trim', explode(',', $post_data['capabilities']));
        }

        if( ! $global ){
            return (object)$post_data;
        }

        $global_settings = wp_parse_args( $this->get_global_settings(), $default );

        if( is_array( $global_settings['roles'] ) ){
            $global_settings['roles'] = $global_settings['roles'];
        }

        if( is_string( $global_settings['capabilities'] ) ){
            $global_settings['capabilities'] = explode( ',', $global_settings['capabilities'] );
        }

        if( $global_settings['apply_all'] ){
            $post_data['apply_for'] = 'inherit';
        }

        /**
         *
         * Filter post data
         * 
         * @var array $post_data
         * @var int $post_id
         * @var boolean $global
         *
         * @since 1.0.9
         * 
         */
        $post_data = apply_filters( 'streamtube/core/content_restriction/post_data', $post_data, $post_id, $global );

        if( $post_data['apply_for'] == 'inherit' ){
            return (object)$global_settings;
        }

        return (object)array_merge( $global_settings, $post_data );
    }

    /**
     *
     * Check permission
     * 
     * @return true|false
     *
     * @since 1.0.9
     * 
     */
    public function check_permission( $post_id ){

        $post = get_post( $post_id );

        $user_data = wp_get_current_user();

        /**
         *
         * Always return true if current user is admin or post owner
         * 
         */
        if( current_user_can( 'administrator' ) || current_user_can( 'editor' ) || $post->post_author == $user_data->ID ){
            return true;
        }

        if( ! wp_cache_get( 'streamtube:license' ) ){
            return true;
        }

        $post_data = $this->get_post_data( $post->ID, true );

        if( ! $post_data->enable ){
            return true;
        }

        $operator = $post_data->operator;

        switch ( $post_data->apply_for ) {
            case 'logged_in':
                if( ! is_user_logged_in() ){
                    return new WP_Error(
                        'not_logged_in',
                        $this->get_login_message()
                    );
                }
            break;

            case 'roles':

                if( $post_data->roles ){
                    if( ! is_user_logged_in() ){
                        return new WP_Error(
                            'not_logged_in',
                            $this->get_login_message()
                        );
                    }

                    if( $operator == 'or' ){

                        $check_roles = array_intersect( $user_data->roles, $post_data->roles );

                        if( ! $check_roles ){
                            return new WP_Error(
                                'no_role_permission',
                                $this->get_no_permission_message()
                            );
                        }
                    }

                    if( $operator == 'and' ){

                        $check_roles = 0;

                        for ( $i=0; $i < count( $user_data->roles ); $i++ ) { 
                            if( in_array( $user_data->roles[$i], $post_data->roles ) ){
                                $check_roles++;
                            }
                        }

                        if( $check_roles != count( $post_data->roles ) ){
                            return new WP_Error(
                                'no_role_permission',
                                $this->get_no_permission_message()
                            );
                        }
                    }
                }

            break;
            
            case 'capabilities':
                if( $post_data->capabilities ){

                    if( ! is_user_logged_in() ){
                        return new WP_Error(
                            'not_logged_in',
                            $this->get_login_message()
                        );
                    }                    

                    if( is_string( $post_data->capabilities ) ){
                        $post_data->capabilities = explode(",", $post_data->capabilities );
                    }

                    if( count( $post_data->capabilities ) == 1 ){
                        if( ! current_user_can( $post_data->capabilities[0] ) ){
                            return new WP_Error(
                                'no_cap_permission',
                                $this->get_no_permission_message()
                            );
                        }
                    }else{
                        if( $operator == 'or' ){

                            $check_caps = array_intersect( array_keys( $user_data->allcaps ), $post_data->capabilities );

                            if( ! $check_caps ){
                                return new WP_Error(
                                    'no_cap_permission',
                                    $this->get_no_permission_message()
                                );
                            }
                        }

                        if( $operator == 'and' ){

                            $check_caps = 0;

                            for ( $i=0; $i < count( $post_data->capabilities ); $i++) { 
                                if( array_key_exists( $post_data->capabilities[$i], $user_data->allcaps ) ){
                                    $check_caps++;
                                }
                            }

                            if( $check_caps != count( $post_data->capabilities ) ){
                                return new WP_Error(
                                    'no_cap_permission',
                                    $this->get_no_permission_message()
                                );
                            }
                        }                            
                    }
                }
            break;
        }
    }

    /**
     *
     * Filter player output
     * 
     * @param  string $player 
     * @return string
     *
     * @since 1.0.9
     * 
     */
    public function filter_player_output( $player, $setup ){

        if( ! get_post_status( $setup['mediaid'] ) ){
            return $player;
        }

        if( $this->Post->get_video_trailer( $setup['mediaid'] ) && isset( $_GET['view_trailer'] ) ){
            return $player;
        }
        
        $check_permission = $this->check_permission( $setup['mediaid'] );

        if( is_wp_error( $check_permission ) ){
            /**
             *
             * @since 1.0.9
             * 
             */
            do_action( 'streamtube/core/content_restriction/no_permission', $check_permission );

            return $this->get_notice_message( $check_permission, $setup );
        }

        /**
         *
         * @since 1.0.9
         * 
         */
        do_action( 'streamtube/core/content_restriction/has_permission', $check_permission );

        return $player;
    }

    /**
     *
     * Filter embed output
     * 
     * @param  string $player 
     * @return string
     *
     * @since 1.0.9
     * 
     */
    public function filter_player_embed_output( $oembed_html, $setup ){
        return $this->filter_player_output( $oembed_html, $setup );
    }

    /**
     *
     * Filter download permission
     * 
     */
    public function filter_download_permission( $can ){

        if( is_wp_error( $this->check_permission( get_the_ID() ) ) ){
            $can = false;
        }

        return $can;
    }    

    /**
     *
     * Load modal Join Us
     * 
     * @since 1.0.9
     */
    public function load_modal_join_us(){

        if( is_user_logged_in() && did_action( 'streamtube/core/content_restriction/default_join_us' ) ){
            streamtube_core_load_template( 'modal/modal-join-us.php', false );    
        }
    }

    /**
     *
     * AJAX request Join Us action
     * 
     * @since 1.0.9
     */
    public function ajax_request_join_us(){

        check_ajax_referer( '_wpnonce' );

        $user_data          = wp_get_current_user();

        if( false !== $cache = get_transient( 'request_join_us_' . $user_data->ID ) ){
            wp_send_json_success( array(
                'message'   =>  esc_html__( 'You have already sent request.', 'streamtube-core' )
            ) );
        }

        $email              = array();

        $email['to']        = get_option( 'admin_email' );

        $email['subject']   = sprintf(
            '%s - %s',
            get_bloginfo('name' ),
            esc_html__( 'Join Us Request', 'streamtube-core' )
        );

        $email['message']    = esc_html__( 'You have a Join Us request with below information' , 'streamtube-core' ) . "\r\n";

        $email['message']    .= sprintf(
            esc_html__( 'Reference URL %s' , 'streamtube-core' ),
            get_permalink( $_POST['post_id'] ),
        ) . "\r\n";

        $email['message']   .= sprintf(
            esc_html__( 'User ID: %s', 'streamtube-core' ),
            $user_data->ID
        ) . "\r\n";

        $email['message']   .= sprintf(
            esc_html__( 'User Name: %s', 'streamtube-core' ),
            $user_data->display_name
        ) . "\r\n";        

        $email['message']   .= sprintf(
            esc_html__( 'User URL: %s', 'streamtube-core' ),
            get_author_posts_url( $user_data->ID )
        ) . "\r\n";               

        $email['message']   .= get_permalink( $post_id ) . "\r\n";

        if( isset( $_POST['content'] ) && ! empty( $_POST['content'] ) ){
            $email['message'] .= esc_html__( 'Message Content', 'streamtube-core' ) . "\r\n";
            $email['message'] .= wp_unslash( $_POST['content'] );
        }

        $email['headers']   = array( 'Content-Type: text/plain; charset="' . get_option( 'blog_charset' ) );

        /**
         *
         * Filter the email before send
         *
         * @param  array $email
         *
         * @since  1.0.0
         * 
         */
        $email = apply_filters(  'streamtube/core/content_restriction/notify', $email );        

        extract( $email );

        $maybe_sent = wp_mail( $to, $subject, $message, $headers );

        if( ! $maybe_sent ){
            wp_send_json_error( new WP_Error(
                'cannot_send_request',
                esc_html__( 'Error: cannot send request, please try again or contact our support.', 'streamtube-core' )
            ) );
        }

        set_transient( 'request_join_us_' . $user_data->ID, 'on', 60*1*24 );

        wp_send_json_success( array(
            'message'   =>  esc_html__( 'Request has been sent successfully.', 'streamtube-core' )
        ) );
    }

    /**
     * Add custom fields to the Video table
     *
     * @param array $columns
     */
    public function filter_post_table( $columns ){
        return array_merge( $columns, array(
            'restrict_content' =>  esc_html__( 'Restriction', 'streamtube-core' )
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
        if( $column == 'restrict_content' ){
            $post_data = $this->get_post_data( $post_id, true );

            if( ! $post_data->apply_for ){
                esc_html_e( 'None', 'streamtube-core' );
            }

            if( $post_data->apply_for == 'inherit' ){
                esc_html_e( 'Global Settings', 'streamtube-core' );
            }

            if( $post_data->apply_for == 'logged_in' ){
                esc_html_e( 'Logged In Users', 'streamtube-core' );
            }

            if( $post_data->apply_for == 'roles' ){
                $roles = (array)$post_data->roles;

                if( $roles ){
                    ?>
                    <div class="custom-roles tags">
                        <?php echo '<span class="role tag">' . implode( '</span><span class="role tag">', $roles ) . '</span>'; ?>
                    </div>
                    <?php
                }
            }

            if( $post_data->apply_for == 'capabilities' ){
                $capabilities = (array)$post_data->capabilities;

                if( $capabilities ){
                    ?>
                    <div class="custom-capabilities tags">
                        <?php echo '<span class="capabilitie tag">' . implode( '</span><span class="capabilitie tag">', $capabilities ) . '</span>'; ?>
                    </div>
                    <?php
                }
            }          
        }
    }     
}