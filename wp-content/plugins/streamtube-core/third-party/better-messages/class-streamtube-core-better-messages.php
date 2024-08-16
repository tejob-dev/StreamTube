<?php
/**
 * Define the Better Messages functionality
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

class StreamTube_Core_Better_Messages{

    public $admin;

    const BP_CHAT_SLUG = 'messages';

    /**
     *
     * Check if plugin is activated
     * 
     * @return boolean
     *
     * @since 1.1
     * 
     */
    public function is_active(){
        return class_exists( 'BP_Better_Messages' );
    }

    /**
     *
     * Get the better messages version
     * 
     * @return string
     */
    private function get_bpm_version(){
        return BP_Better_Messages()->version;
    }

    /**
     *
     * Get better messages plugin url
     * 
     * @return string|false
     */
    private function get_bpm_url(){
        return BP_Better_Messages()->url;
    }

    /**
     *
     * Get better messages plugin path
     * 
     * @return string|false
     */
    private function get_bpm_path(){
        return BP_Better_Messages()->path;
    }    

    /**
     *
     * Check if current user can enable live chat
     * 
     * @return boolean
     *
     * @since 2.1.7
     * 
     */
    public function can_user_enable_live_chat(){

        /** 
         *
         * @since 2.1.7
         * 
         */
        return apply_filters( 
            'streamtube/core/better_messages/can_user_enable_live_chat', 
            Streamtube_Core_Permission::can_upload() 
        );
    }

    /**
     *
     * Check if current user can enable full live chat
     * 
     * @return boolean
     *
     * @since 2.1.7
     * 
     */
    public function can_user_enable_full_live_chat(){

        /** 
         *
         *
         * @since 2.1.7
         * 
         */
        return apply_filters( 
            'streamtube/core/better_messages/can_user_enable_full_live_chat', 
            Streamtube_Core_Permission::moderate_posts()
        );
    }     

    /**
     *
     * Get settings
     * 
     * @return array
     *
     * @since 1.1.5
     * 
     */
    public function get_settings(){

        $_default_settings = array(
            'private_message'               =>  'on',
            'enable_livechat_label'         =>  'on',
            'livechat_label_text'           =>  esc_html__( 'Live Chat', 'streamtube-core' ),
            'allow_author_create_livechat'  =>  'on'
        );

        $settings = array(
            'menu_text'         =>  esc_html__( 'Messages', 'streamtube-core' ),
            'menu_desc'         =>  esc_html__( 'Messages', 'streamtube-core' ),
            'menu_icon'         =>  'icon-chat',
            'button_id'         =>  'private-message',
            'button_icon'       =>  'icon-mail',
            'button_text'       =>  esc_html__( 'Private Message', 'streamtube-core' ),
            'button_type'       =>  'secondary',
            'button_classes'    =>  array( 'btn', 'px-2', 'shadow-none', 'd-flex', 'align-items-center', 'btn-sm', 'position-relative', 'btn-private-message' ),
            'modal_id'          =>  'modal-private-message',
            'modal_title'       =>  esc_html__( 'Private Message', 'streamtube-core' ),
            'recipient_id'      =>  ''
        );

        if( ! is_user_logged_in() ){
            $settings['modal_id'] = 'modal-login';
        }

        $settings['button_classes'][] = 'btn-' . sanitize_html_class( $settings['button_type'] );

        $settings = array_merge( $settings, get_option( 'better_messages', $_default_settings ) );

        foreach ( $_default_settings as $key => $value ) {
            if( ! array_key_exists( $key, $settings ) ){
                $settings[$key] = $_default_settings[ $key ];
            }
        }

        /**
         *
         * Filter settings
         * 
         * @since 1.1.5
         */
        $settings = apply_filters( 'streamtube/core/better_messages/settings', $settings );

        return (object)$settings;
    }

    /**
     *
     * Get plugin settings
     * 
     * @param  string $setting
     * @return BP_Better_Messages->settings();
     *
     * @since 1.1.5
     * 
     */
    public function get_bpm_settings( $setting = '' ){
        return BP_Better_Messages()->settings[ $setting ];
    }

    /**
     *
     * Check if has bp settings callback
     * 
     * @return boolean
     */
    public function get_post_settings_fields( $post = null ){
        $this->enqueue_bp_scripts();

        if ( ! function_exists('get_editable_roles')) {
           require_once(ABSPATH . '/wp-admin/includes/user.php');
        }        

        setup_postdata( $GLOBALS['post'] =& $post );

        load_template( plugin_dir_path( __FILE__ ) . 'public/metabox.php' );

        wp_reset_postdata();
    }

    /**
     *
     * Get default post settings
     * 
     * @return array
     *
     * @since 2.1.7
     * 
     */
    public function _get_post_default_settings(){

        global $wp_roles;
        $roles = array_keys( $wp_roles->roles ); 

        return array(
            'enable'                =>  '',
            'disable_reply'         =>  '',
            'avatar_size'           =>  '30',
            'only_joined_can_read'  =>  '',
            'auto_join'             =>  '1',
            'hide_participants'     =>  '',
            'hide_from_thread_list' =>  '',
            'allow_guests'          =>  '1',
            'can_join'              =>  $roles,
            'can_reply'             =>  $roles
        );
    }

    /**
     *
     * Get live chat settings
     * 
     * @param  int $post_id
     * @return array
     *
     * @since 2.1.7
     * 
     */
    public function get_post_settings( $post_id = 0 ){

        $settings = array();

        if( ! $post_id ){
            return $this->_get_post_default_settings();
        }

        $settings = get_post_meta( $post_id, 'bpbm-chat-settings', true );

        if( ! $settings ){
            $settings = $this->_get_post_default_settings();
        }else{
            $settings = wp_parse_args( $settings, $this->_get_post_default_settings() );
        }

        if( ! array_key_exists( 'allow_guests', $settings ) ){
            $settings['allow_guests'] = '1';
        }

        return $settings;
    } 

    /**
     *
     * Save settings
     * 
     * @param  int $post_id
     *
     * @since 2.1.7
     * 
     */
    public function update_post_settings( $post_id ){

        if( ! $this->can_user_enable_live_chat() 
            || ! isset( $_REQUEST['bpbm'] ) 
            || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )) {
            return;
        }

        $settings = (array)wp_unslash( $_REQUEST['bpbm'] );

        if( $this->can_user_enable_full_live_chat() ){
            return update_post_meta( $post_id, 'bpbm-chat-settings', $settings );
        }

        $settings = wp_parse_args( $settings, $this->_get_post_default_settings() );

        return update_post_meta( $post_id, 'bpbm-chat-settings', $settings );
    }

    /**
     *
     * Check if post livechat enabled
     * 
     * @param  int  $post_id
     * @return boolean
     *
     * @since 2.1.7
     * 
     */
    public function is_post_live_enabled( $post_id = 0 ){

        $settings = $this->get_post_settings( $post_id );

        return $settings['enable'] && $this->is_active();
    }

    /**
     *
     * Check if reply open
     * 
     * @param  int  $post_id
     * @return boolean
     *
     * @since 2.1.7
     * 
     */
    public function is_reply_open( $post_id ){
        $settings = $this->get_post_settings( $post_id );

        return ! wp_validate_boolean( $settings['disable_reply'] );
    }

    /**
     *
     * Get chat ID, video or regular post ID from thread_id
     * 
     * @return int
     */
    private function get_chat_id( $thread_id ){
        return Better_Messages()->functions->get_thread_meta( $thread_id, 'chat_id' );
    }

    /**
     *
     * Get given user Inbox url
     * 
     * @param  integer $user_id
     * @return false or URL
     *
     * @since 1.1.5
     * 
     */
    public function get_inbox_url( $user_id = 0 ){
        if( ! $user_id && is_user_logged_in() ){
            $user_id = get_current_user_id();
        }

        if( ! $user_id ){
            return false;
        }

        return trailingslashit( get_author_posts_url( $user_id ) ) . 'dashboard/' . self::BP_CHAT_SLUG;
    }

    /**
     *
     * Check if current request page is inbox
     * 
     * @return boolean
     *
     * @sine 1.1.5
     * 
     */
    public function is_inbox(){

        if( ! is_user_logged_in() ){
            return false;
        }

        if( strpos( $_SERVER['REQUEST_URI'], '/dashboard/inbox' ) == false ){
            return false;
        }

        return true;
    }

    /**
     *
     * do AJAX get recipient display name
     * 
     * @since 1.1.5
     */
    public function get_recipient_info(){

        check_ajax_referer( '_wpnonce' );

        if( ! isset( $_GET['recipient_id'] ) ){
            wp_send_json_error( new WP_Error(
                'recipient_id_not_found',
                esc_html__( 'Recipient ID was not found', 'streamtube-core' )
            ) );
        }

        $userdata = get_user_by( 'ID', $_GET['recipient_id'] );

        if( ! $userdata ){
            wp_send_json_error( new WP_Error(
                'recipient_not_found',
                esc_html__( 'Recipient was not found', 'streamtube-core' )
            ) );            
        }

        wp_send_json_success( array(
            'id'                =>  $userdata->ID,
            'display_name'      =>  $userdata->display_name,
            'avatar'            =>  streamtube_core_get_user_avatar( array(
                'user_id'       =>  $userdata->ID,
                'name'          =>  true,
                'name_class'    =>  'm-0',
                'wrap_size'     =>  'xl',
                'before'        =>  '<div class="d-flex flex-column justify-content-center"><div class="mx-auto text-center">',
                'after'         =>  '</div></div>',
                'echo'          =>  false
            ) )
        ) ); 
    }

    /**
     *
     * Get total unread threads of given user ID
     * 
     * @return int $user_id
     *
     * @since 1.1.5
     * 
     */
    public function get_unread_threads( $user_id = 0 ){

        if( ! $user_id && is_user_logged_in() ){
            $user_id = get_current_user_id();
        }

        if( ! $user_id ){
            return 0;
        }

        if( ! is_callable( array( 'Better_Messages_Functions', 'get_total_threads_for_user' ) ) ){
            return 0;
        }

        return Better_Messages_Functions::get_total_threads_for_user( $user_id, 'unread' );
    }

    /**
     *
     * The unread threads badge
     *
     * @since 1.1.5
     * 
     */
    public function get_unread_threads_badge(){

        $badge = '';

        $unread_threads = $this->get_unread_threads();

        if( $unread_threads ){
            $badge = sprintf(
                '<span class="badge bg-danger">%s</span>',
                number_format_i18n( $unread_threads )
            );
        }

        /**
         *
         * @since 1.1.5
         * 
         */
        return apply_filters( 'streamtube/core/better_messages/unread_threads_badge', $badge, $unread_threads );

    }

    /**
     *
     * Show the unread threads on current logged in user avatar
     * 
     * @return output the badge
     *
     * @since 1.1.7
     * 
     */
    public function show_unread_threads_badge_on_avatar(){
        printf(
            '<div class="position-absolute unread-threads">%s</div>',
            $this->get_unread_threads_badge()
        );
    }    

    /**
     *
     * Add Messages menu item
     * 
     * @param array $items
     *
     * @since 1.1.5
     */
    public function add_profile_menu( $items ){

        $settings = $this->get_settings();

        if( ! $settings->private_message ){
            return $items; 
        }

        $items[ self::BP_CHAT_SLUG ]  = array(
            'title'         =>  $this->get_settings()->menu_text,
            'badge'         =>  $this->get_unread_threads_badge(),
            'desc'          =>  $this->get_settings()->menu_desc,
            'icon'          =>  $this->get_settings()->menu_icon,
            'url'           =>  $this->get_inbox_url(),
            'priority'      =>  120,
            'private'       =>  true
        );
        return $items;
    }

    /**
     *
     * Add Messages menu item
     * 
     * @param array $items
     *
     * @since 1.1.5
     */
    public function add_dashboard_menu( $items ){

        $settings = $this->get_settings();

        if( ! $settings->private_message ){
            return $items; 
        }        

        $items[ self::BP_CHAT_SLUG ] = array(
            'title'     =>  $this->get_settings()->menu_text,
            'badge'     =>  $this->get_unread_threads_badge(),
            'desc'      =>  $this->get_settings()->menu_desc,
            'icon'      =>  $this->get_settings()->menu_icon,
            'callback'  =>  function(){
                load_template( 
                    untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/public/inbox.php', 
                    false
                );
            },
            'parent'    =>  'dashboard',
            'cap'       =>  'read',
            'priority'  =>  60
        );

        return $items;
    }

    /**
     *
     * Load button private message
     * 
     * @return load_template()
     *
     * @since 1.1.5
     * 
     */
    public function button_private_message( $recipient_id = 0 ){

        $settings = $this->get_settings();

        if( ! $settings->private_message ){
            return;
        }

        if( is_author() ){
            $settings->recipient_id = get_queried_object_id();
        }

        if( is_singular() ){
            global $post;        

            $settings->recipient_id = $post->post_author;
        }

        if( $recipient_id ){
            $settings->recipient_id = $recipient_id;
        }

        if( ! $settings->recipient_id || ( is_user_logged_in() && get_current_user_id() == $settings->recipient_id ) ){
            return;
        }          

        load_template( 
            untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/public/button-private-message.php', 
            false,
            $settings
        );
    }

    public function user_list_button_private_message( $user = 0 ){

        $user_id = is_object( $user ) ? $user->ID : ( is_int( $user ) ? $user : 0 );

        return $this->button_private_message( $user_id );    
    }

    /**
     *
     * Load modal private message
     * 
     * @return load_template()
     *
     * @since 1.1.5
     * 
     */
    public function modal_private_message(){

        $settings = $this->get_settings();

        if( ! $settings->private_message ){
            return;
        }        

        if( did_action( 'streamtube/core/better_messages/button_private_message/after' ) ){
            load_template( 
                untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/public/modal-private-message.php', 
                true,
                $settings
            );
        }
    }

    /**
     *
     * Navigate to inbox page if thread_id found
     * 
     * @since 1.1.5
     */
    public function goto_inbox(){
        if( isset( $_GET['thread_id'] ) && ! $this->is_inbox() && ! $this->get_bp_settings( 'chatPage' ) ){

            wp_redirect( add_query_arg( array(
                'thread_id' =>  $_GET['thread_id']
            ), $this->get_inbox_url() ) );

            exit;

        }
    }

    /**
     *
     * Get the chatroom output
     * 
     * @param  int $post_id rooom ID
     * @return string
     *
     * @since 2.1.7
     * 
     */
    public function get_chat_room_output( $post_id, $echo = false ){
        $output = do_shortcode( sprintf(
            '[bp_better_messages_chat_room id="%s"]',
            $post_id
        ) );

        if( $output ){
            $find       = 'class="button button-primary"';
            $replace    = 'class="button button-primary btn btn-sm btn-danger"';
            $output     = str_replace( $find, $replace, $output );
        }

        /**
         *
         * Filter the chatroom output
         *
         * @param string $output
         * @param int $post_id
         *
         * @since 2.1.7
         * 
         */
        $output = apply_filters( 'streamtube/core/better_messages/chatroom', $output, $post_id );

        if( $echo ){
            echo $output;
        }else{
            return $output;
        }
    }

    /**
     *
     * Add Live Chat to post nav item from user dashboard
     * 
     * @param array $items
     *
     * @since 2.1.7
     * 
     */
    public function add_post_nav_item( $items ){

        if( ! $this->get_settings()->allow_author_create_livechat || 
            ! $this->can_user_enable_live_chat()
        ){
            return $items;
        }

        $this->enqueue_bp_scripts();

        $items['livechat']   = array(
            'title'         =>  esc_html__( 'Live Chat', 'streamtube-core' ),
            'icon'          =>  'icon-chat',
            'template'      =>  plugin_dir_path( __FILE__ ) . 'public/post-settings.php',
            'priority'      =>  30
        ); 
         
        return $items;
    }

    /**
     *
     * Filter body classes
     * 
     * @param  array $classes
     * @return array
     *
     * @since 2.1.7
     * 
     */
    public function filter_body_class( $classes ){

        global $post;

        if( ! $post instanceof WP_Post ){
            return $classes;
        }

        if( $this->is_post_live_enabled( $post->ID ) ){
            $classes[] = 'live-chat-template';
        }

        return $classes;
    }

    /**
     *
     * Filter show comments
     * 
     * @param  $boolean
     * 
     */
    public function filter_has_post_comments( $boolean ){
        global $post;

        if( $this->is_post_live_enabled( $post->ID ) ){
            $boolean = true;
        }

        return $boolean;

    }

    /**
     *
     * Filter the comment template file if bp_show_live_chat option found
     * 
     * @param  string $file
     * @return string Live Chat box template
     *
     * @since 2.1.7
     * 
     */
    public function filter_comments_template( $file ){

        global $post;

        if( is_object( $post ) && $this->is_post_live_enabled( $post->ID ) ){
            $file = plugin_dir_path( __FILE__ ) . 'public/comments-livechat.php';
        }

        return $file;
    }

    /**
     *
     * Add livechat icon on the post thumbnail
     *
     * @since 2.1.7
     * 
     */
    public function add_post_thumbnail_livechat_icon(){
        global $post;

        $settings = $this->get_settings();

        if( ! $settings->enable_livechat_label ){
            return;
        }

        if( $post instanceof WP_Post && ! $this->is_reply_open( $post->ID ) ){
            return;
        }

        if( $post instanceof WP_Post && $this->is_post_live_enabled( $post->ID ) ){
            ?>
            <div class="livechat-icon badge">
                <span class="dot"></span>
                <?php if( $settings->livechat_label_text ){
                    printf(
                        '<span class="text">%s</span>',
                        esc_html( $settings->livechat_label_text )
                    );
                }?>
            </div>
            <?php
        } 
    }

    /**
     *
     * Filter disable reply
     *
     * @since 2.1.7
     * 
     */
    public function filter_disable_reply( $allowed, $user_id, $thread_id ){

        if( ! $this->is_reply_open( $this->get_chat_id( $thread_id ) ) ){
            global $bp_better_messages_restrict_send_message;
            $bp_better_messages_restrict_send_message['disable_bulk_replies'] = esc_html__( 'Replies are disabled' , 'streamtube-core');            
            $allowed = false;
        }

        return $allowed;
    }

    /**
     *
     * Filter thread_type
     * 
     */
    public function filter_thread_type( $thread_type, $thread_id ){

        $chat_id = $this->get_chat_id( $thread_id );

        if( get_post_type( $chat_id ) == 'video' ){
            $thread_type = 'chat-room';
        }

        return $thread_type;
    }

    /**
     *
     * Filter the user avatar
     * 
     */
    public function filter_rest_user_item( $item, $user_id, $include_personal ){
        $item['avatar'] = get_avatar_url( $user_id );

        return $item;        
    }

    /**
     *
     * Send a pm after post has been moderated
     * 
     */
    public function send_pm_after_post_moderated( $post_id, $action, $message = '' ){

        // Requires defining BM_NOTIFY_POST_MODERATED within wp-config.php
        if( ! defined( 'BM_ENABLE_NOTIFY_POST_MODERATION' ) || ! BM_ENABLE_NOTIFY_POST_MODERATION ){
            return false;
        }

        $post               = get_post( $post_id );

        if( get_current_user_id() == $post->post_author ){
            return false;
        }

        $moderation_thread  = (array)get_post_meta( $post_id,'_bp_message_moderation', true );

        $thread_id          = 0;
        $subject            = '';
        $recipients         = array( $post->post_author );
        $rest_url           = '';
        $params             = array();

        if( $action == 'approved' ){
            $subject = sprintf(
                esc_html__( '"%s" has been approved', 'streamtube-core' ),
                $post->post_title
            );
        }

        if( $action == 'rejected' ){
            $subject = sprintf(
                esc_html__( '"%s" has been rejected', 'streamtube-core' ),
                $post->post_title
            );
        }

        $message = sprintf(
           '<span class="text-%s">%s: %s</span> <br/>%s',
           $action == 'approved' ? 'success' : 'warning',
           $subject,
           get_permalink( $post->ID ),
           $message
        );

        $message = wpautop( $message );          

        if( is_array( $moderation_thread ) && array_key_exists( 'thread_id', $moderation_thread ) ){
            $thread_id = (int)$moderation_thread['thread_id'];

            if( function_exists( 'Better_Messages' ) && Better_Messages()->functions->get_thread( $thread_id ) ){
                $rest_url = "/better-messages/v1/thread/{$thread_id}/send";
            }else{
                // Reset thread.
                $thread_id = 0;
            }
        }

        if( ! $thread_id ){
            $rest_url = "/better-messages/v1/thread/new";
        }

        $params = compact( 'subject', 'message', 'recipients' );

        if( $thread_id ){
            $params['thread_id'] = $thread_id;
        }

        /**
         *
         * Filter the params
         * 
         */
        $params = apply_filters( 'streamtube/core/better_messages/post_moderation/params', $params, $post );

        $request    = new WP_REST_Request( 'POST', $rest_url );
        $request->set_query_params( $params );

        $response   = rest_do_request( $request );
        $server     = rest_get_server();

        $data       = $server->response_to_data( $response, false );

        if( is_array( $data ) && array_key_exists( 'thread_id', $data ) ){
            update_post_meta( $post_id, '_bp_message_moderation', $data );
        }

        /**
         *
         * Fires after message sent.
         *
         * @param array $data
         * 
         */
        do_action( 'streamtube/core/better_messages/post_moderation/sent', $data, $post );

        return $data;

    }

    /**
     *
     * Enqueue bp custom scripts
     * 
     */
    public function enqueue_bp_scripts(){
        if( version_compare( $this->get_bpm_version(), '2.4.20', '>=' ) ){
            wp_enqueue_script( 
                'better-messages-admin',
                trailingslashit( $this->get_bpm_url() ) . 'assets/admin/admin.min.js',
                array( 'jquery' ),
                filemtime( trailingslashit( $this->get_bpm_path() ) . 'assets/admin/admin.min.js' ),
                true
            );

            $script_variables = array(
                'restUrl' => esc_url_raw( get_rest_url( null, '/better-messages/v1/' ) ),
                'nonce'   => wp_create_nonce( 'wp_rest' ),
            );

            wp_set_script_translations( 
                'better-messages-admin', 
                'bp-better-messages', 
                trailingslashit( $this->get_bpm_path() ) . 'languages/' 
            );

            wp_localize_script( 'better-messages-admin', 'Better_Messages_Admin', $script_variables );            
        }
    }

    /**
     *
     * @see add_meta_box()
     *
     * @since 2.1.7
     * 
     */
    public function add_meta_boxes(){
        add_meta_box( 
            'better-messages-settings', 
            esc_html__( 'Live Chat', 'streamtube-core' ), 
            array( $this , 'get_post_settings_fields' ), 
            'video', 
            'advanced', 
            'default'
        );
    }

    /**
     *
     * @see add_meta_box()
     *
     * @since 2.1.7
     * 
     */
    public function unregistered_meta_boxes(){
        add_meta_box( 
            'unregistered-better-messages-settings', 
            esc_html__( 'Live Chat Settings', 'streamtube-core' ), 
            array( $this , 'unregistered_settings_template' ), 
            'video', 
            'advanced', 
            'default'
        );
    }    

    public function unregistered_settings_template( $post ){
        return printf(
            esc_html__( '%s to unlock this feature.', 'streamtube-core' ),
            '<a href="'. esc_url( admin_url( 'themes.php?page=license-verification' ) ) .'">'. esc_html__( 'Verify Purchase', 'streamtube-core' ) .'</a>'
        );
    }    
}