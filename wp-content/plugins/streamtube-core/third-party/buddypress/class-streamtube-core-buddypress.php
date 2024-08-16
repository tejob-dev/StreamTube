<?php
/**
 * Define the buddyPress functionality
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

class StreamTube_Core_buddyPress{

    /**
     *
     * Holds the Messages object
     * 
     * @var object
     */
    public $messages;

    /**
     *
     * Holds the Notifications object
     * 
     * @var object
     */
    public $notifications;    

    /**
     *
     * Holds the Friends object
     * 
     * @var object
     */
    public $friends;

    /**
     *
     * Holds the members object
     * 
     * @var object
     */
    public $members;

    /**
     *
     * Holds the activity object
     * 
     * @var object
     */
    public $activity;

    /**
     *
     * Holds the groups object
     * 
     * @var object
     */
    public $groups;      

    /**
     *
     * Holds the follow object
     * 
     * @var object
     */
    public $follow;

    public function __construct(){

        require_once plugin_dir_path( __FILE__ ) . 'class-streamtube-core-buddypress-messages.php';
        require_once plugin_dir_path( __FILE__ ) . 'class-streamtube-core-buddypress-notifications.php';
        require_once plugin_dir_path( __FILE__ ) . 'class-streamtube-core-buddypress-members.php';
        require_once plugin_dir_path( __FILE__ ) . 'class-streamtube-core-buddypress-friends.php';
        require_once plugin_dir_path( __FILE__ ) . 'class-streamtube-core-buddypress-follow.php';
        require_once plugin_dir_path( __FILE__ ) . 'class-streamtube-core-buddypress-activity.php';
        require_once plugin_dir_path( __FILE__ ) . 'class-streamtube-core-buddypress-groups.php';
        require_once plugin_dir_path( __FILE__ ) . 'class-streamtube-core-buddypress-widget-user-list.php';

        $this->messages         = new StreamTube_Core_buddyPress_Messages();
        $this->notifications    = new StreamTube_Core_buddyPress_Notifications();
        $this->members          = new StreamTube_Core_buddyPress_Members();
        $this->friends          = new StreamTube_Core_buddyPress_Friends();
        $this->activity         = new StreamTube_Core_buddyPress_Activity();
        $this->groups           = new StreamTube_Core_buddyPress_Group();
        $this->follow           = new StreamTube_Core_buddyPress_Follow();
    }

    /**
     *
     * Check if buddyPress is activated
     * 
     * @return boolean
     */
    public function is_active(){
        return class_exists( 'BuddyPress' ) && ( ! defined( 'BP_DISABLE_STREAMTUBE_COMPAT' ) || BP_DISABLE_STREAMTUBE_COMPAT == false );
    }

    /**
     *
     * setup_bp_environments
     * 
     */
    public function setup_bp_environments( $com = 'activity' ){

        global $wp_query;

        add_filter( 'bp_displayed_user_id', function( $user_id ){
            return get_queried_object_id();
        }, 9999, 1 );

        add_filter( 'bp_displayed_user_fullname', function( $fullname){
            return get_queried_object()->display_name;
        });
  
        add_filter( 'bp_current_component', function( $component ) use( $com ){
            return $com;
        } );

        add_filter( 'bp_is_my_profile', function( $retvar ){
            if( is_user_logged_in() && is_author() && get_current_user_id() == get_queried_object_id() ){
                return true;
            }
            return $retvar;
        } );

        add_filter( 'bp_is_current_component', '__return_true' );      
     
        if( array_key_exists( $com, $wp_query->query ) ){

            $scope = $wp_query->query[$com];

            if( ! $scope ){
                if( $com == 'activity' ){
                    $scope = 'just-me';
                }
                if( $com == 'friends' ){
                    $scope = 'my-friends';
                }                      
                if( $com == 'groups' ){
                    $scope = 'my-groups';
                }
            }        
            
            add_filter( 'bp_current_action', function( $action ) use ( $scope ){
                return $scope;
            }, 10, 2 );
        } 
    }

    /**
     *
     * Add additional body classes
     * 
     * @param  array $classes
     */
    public function filter_body_class( $classes ){
        if( $this->has_float_user_list() ){
            $classes[] = 'has-friend-list-widget';

            if( $this->is_mini_float_user_list() ){
                $classes[] = 'friend-list-widget-mini';
            }            
        }

        return $classes;
    }

    /**
     * buddypress is always when on Author page
     */
    public function filter_is_buddypress( $retvar ){

        if( is_author() ){
            return __return_true();
        }

        return $retvar;
    }    

    /**
     *
     * Filter user profile URL
     * 
     */
    public function filter_bp_members_get_user_url( $url, $user_id, $slug, $path_chunks ){

        $path_chunks = wp_parse_args( $path_chunks, array(
            'component_id'                  =>  '',
            'single_item'                   =>  '',
            'single_item_component'         =>  '',
            'single_item_action'            =>  '',
            'single_item_action_variables'  =>  array()
        ) );

        extract( $path_chunks );

        $author_base = get_author_posts_url( $user_id );

        if( in_array( $single_item_component , array( 'notifications', 'messages' ) ) ){
            $author_base = sprintf(
                '%s/dashboard',
                untrailingslashit( $author_base ),
            );
        }

        $url = sprintf(
            '%s/%s/%s',
            untrailingslashit( $author_base ),
            $single_item_component,
            $single_item_action
        );

        if( $single_item_action_variables ){
            $url = trailingslashit( $url ) . join( '/', (array) $single_item_action_variables );
        }

        return untrailingslashit( $url );      
    }

    /**
     *
     * Set displayed user ID using cookie
     * 
     */
    public function _set_displayed_user_id(){
        if( is_author() ){
            setcookie( 'wpbp_displayed_user_id', get_queried_object_id(), time() + 3600, COOKIEPATH, COOKIE_DOMAIN );          
        }else{
            setcookie( 'wpbp_displayed_user_id', 0, time() - 3600, COOKIEPATH, COOKIE_DOMAIN );
        }       
    }

    /**
     *
     * Set displayed user ID using cookie
     * 
     */
    public function set_displayed_user_id(){
        if( wp_doing_ajax() ){
            add_filter( 'bp_displayed_user_id', function( $user_id ){
                if( isset( $_COOKIE['wpbp_displayed_user_id'] ) && absint( $_COOKIE['wpbp_displayed_user_id'] ) > 0 ){
                    $user_id = absint($_COOKIE['wpbp_displayed_user_id']);
                }

                return $user_id;
            }, 1, 1 ); 
        }
    }

    /**
     * Filters the logged in user's avatar.
     *
     *
     * @param string $value User avatar string.
     * @param array  $r     Array of parsed arguments.
     * @param array  $args  Array of initial arguments.
     */
        
    public function filter_bp_get_loggedin_user_avatar( $avatar, $r, $args ){
        return get_avatar( get_current_user_id(), 50 );
    }

    /**
     *
     * Add bp button additional css name
     * 
     * @param  array $button_args
     */
    public function filter_bp_button( $args ){

        $args = wp_parse_args( $args, array(
            'id'        =>  '',
            'component' =>  ''
        ) );        

        $class = 'secondary';

        switch ( $args['id'] ) {

            case 'pending':
            case 'awaiting_response':
                $class = 'warning';
            break;            

            case 'is_friend':
                $class = 'success';
            break;

            case 'not_friends':
            case 'following':
                $class = 'info';
            break;

            case 'private_message';

                $user_login = 0;

                if( is_author() ){
                    $user_login = get_queried_object()->data->user_login;
                }elseif( is_singular() ){

                    global $post;

                    if( $post->post_author ){
                        $user_login = get_userdata( $post->post_author )->user_login;    
                    }   
                }

                if( $user_login ){
                    $args['link_href'] = sprintf(
                        '%s/dashboard/messages/compose/?r=' . $user_login,
                        untrailingslashit( get_author_posts_url( get_current_user_id() ) )
                    );
                }
            break;
        }

        if( in_array( $args['component'], array( 'friends', 'follow' ) ) ){
            $args['wrapper_class'] .= ' btn-group';
        }

        $args['link_class'] .= " btn btn-sm btn-{$class} text-white";

        $args['link_class'] = str_replace( 'remove', '', $args['link_class'] );

        return $args;        
    }

    /**
     *
     * Filter get button output
     * 
     */
    public function filter_bp_get_button( $contents, $args, $button ){
        $args = wp_parse_args( $args, array(
            'id'        =>  '',
            'component' =>  ''
        ) );

        if( $args['id'] ){

            $count = 0;

            if ( preg_match( '/(<a[^>]*>.*?<\/a>)/' , $contents, $matches )) {

                $button = $matches[0];

                if ( preg_match( '/id="(follow|unfollow|friend)-(\d+)"/', $button, $id_matchs ) ) {

                    switch ( $args['component']  ) {
                        case 'follow':
                            if( function_exists( 'bp_follow_total_follow_counts' ) ){
                                $count = bp_follow_total_follow_counts( array(
                                    'user_id' =>  $id_matchs[2]
                                ) );
                                $count = absint( $count['followers'] );
                            }
                        break;
                        
                        case 'friends':
                            if( function_exists( 'friends_get_total_friend_count' ) ){
                                $count = friends_get_total_friend_count( $id_matchs[2] );
                            }
                        break;
                    }
                }

                $button = preg_replace( '/(<a[^>]*>)([^<]*)<\/a>/', '$1' . '<span class="btn__icon icon-plus me-1"></span>$2</a>' , $button );

                if( absint( $count ) > 0 ){
                    $button = sprintf(
                        '%s%s',
                        $button,
                        sprintf(
                        '<button class="btn btn-sm btn-danger btn-count px-3">%s</button>',
                            apply_filters( 'streamtube/core/number_format', number_format_i18n( $count ), $count, $args )
                        )
                    );                    
                }
                
                $contents = str_replace( $matches[0], $button, $contents );
            }

            $contents = str_replace( 'generic-button', 'bp-button', $contents );

        }

        return force_balance_tags( $contents );
    }  

    /**
     *
     * Filter embed html
     * 
     */
    public function filter_bp_embed_oembed_html( $html, $url, $attr, $rawattr ){

        global $streamtube;

        return sprintf(
            '<div class="bp-oembed-html %s-oembed-html">%s</div>',
            sanitize_html_class( bp_current_component() ),
            $streamtube->get()->oembed->filter_embed_oembed_html( $html, $url, $attr, $rawattr )
        );
    }

    /**
     * Notify followers when a new activity is added.
     * Hooked into "bp_activity_post_type_published"
     *
     * @param int    $activity_id    ID of the activity.
     * @param WP_Post $post           WordPress post object.
     * @param array  $activity_args  Additional arguments for the activity.
     */
    public function notify_followers_of_new_activity( $activity_id = null, $post = null, $activity_args = array() ) {

        if( defined( 'BP_DISABLE_NOTIFY_FOLLOWER_NEW_UPDATE' ) && BP_DISABLE_NOTIFY_FOLLOWER_NEW_UPDATE == true ){
            return;
        }

        // Return if Notifications isn't active
        if (  ! $this->notifications->is_active() || ! $this->notifications->is_notify_followers() ) {
            return;
        }

        // Return if $post is not a valid WP_Post object or not buddypress activity
        if ( ! is_a( $post, 'WP_Post' ) ) {
            return;
        }

        if( ! in_array( $post->post_type , $this->notifications->get_tracking_post_types() ) ){
            return;
        }

        if( $activity_id !== null && ! post_type_supports( $post->post_type, 'buddypress-activity' ) ){
            return;
        }

        // holds an array of followers and friends
        $followers = array();

        $author_id = $post->post_author;

        if( $this->follow->is_active() ){
            $followers = array_merge( $followers, $this->follow->get_followers( $author_id ) );
        }

        if( $this->friends->is_active() ){
            $followers = array_merge( $followers, $this->friends->get_friends( $author_id ) );
        }        

        $followers = array_values( array_filter( array_unique ( $followers ) ) );

        // Return if no followers
        if ( ! $followers ) {
            return;
        }

        /**
         *
         * Filter the followers list before sending
         * 
         * @param array $followers
         * @param int $notification_id
         * @param int|null $activity_id
         * @param WP_Post $post
         * @param array $activity_args
         * 
         */
        $followers = apply_filters( 
            'streamtube/core/bp/notify_followers_new_update/followers', 
            $followers,
            $activity_id, 
            $post, 
            $activity_args
        );

        for ( $i = 0; $i < count( $followers ); $i++ ) { 
            $notification_id = bp_notifications_add_notification( array(
                'user_id'           => $followers[$i],
                'item_id'           => $post->ID,
                'secondary_item_id' => $author_id,
                'component_name'    => $post->post_type,
                'component_action'  => 'new_' . $post->post_type,
                'date_notified'     => bp_core_current_time(),
                'is_new'            => 1
            ) );

            if( $notification_id ){

                /**
                 * @param int $follower_id
                 * @param int $notification_id
                 * @param int|null $activity_id
                 * @param WP_Post $post
                 * @param array $activity_args
                 * 
                 */
                do_action( 
                    'streamtube/core/bp/notify_followers_new_update/added', 
                    $followers[$i], 
                    $notification_id, 
                    $activity_id, 
                    $post, 
                    $activity_args 
                );
            }
        }
    }    

    /**
     * Notify followers when a new post is publish
     *
     * Always works even if Activity is disabled.
     *
     * @param int    $post_id    ID of the post.
     * @param WP_Post $post           WordPress post object.
     * @param string  old_status
     */
    public function notify_followers_of_new_submit( $post_id, $post, $old_status ) {
        return $this->notify_followers_of_new_activity( null, $post, array() );
    }    

    /**
     *
     * Display the primary button on user card and member loop
     *
     * Display the follow button, otherwise display the Add friend button
     * 
     */
    public function display_primary_button( $user_id = 0 ){

        if( $this->follow->is_active() ){
            return $this->follow->the_follow_button( $user_id );
        }else{
            return $this->friends->the_add_friend_button( $user_id );
        }
    }

    /**
     *
     * Display the featured activities which located at the top activity archive page
     * 
     */
    public function display_featured_activities(){
        bp_get_template_part( 'common/featured-activities' );
    }

    public function display_notices(){
        ?>
        <div id="template-notices" role="alert" aria-atomic="true">
            <?php

            /** This action is documented in bp-templates/bp-legacy/buddypress/activity/index.php */
            do_action( 'template_notices' ); 
            ?>
        </div>
        <?php
    }

    /**
     *
     * Check if floating friend list is active
     * 
     * @return boolean
     */
    public function has_float_user_list(){

        $retvar = bp_current_component() == 'activity'
                && ! wp_is_mobile()
                && apply_filters( 'streamtube/core/bp/_float_user_list', false ); 

        return apply_filters( 'streamtube/core/bp/float_user_list', $retvar );
    }

    /**
     *
     * Check if mini floating friend list is active
     * 
     * @return boolean
     */
    public function is_mini_float_user_list(){
        return apply_filters( 'streamtube/core/bp/mini_float_friend_list', $this->has_float_user_list() ); 
    } 

    /**
     *
     * The floating member list widget at the right side
     * 
     */
    public function display_float_user_list(){
        if( $this->has_float_user_list() ){
            bp_get_template_part( 'common/friend-list', null, array(
                'collapsed' =>  $this->is_mini_float_user_list(),
                'location'  =>  'end-0'
            ) );
        }
    }

    /**
     *
     * Register Sidebar
     * 
     */
    public function register_sidebar(){
        register_sidebar(
            array(
                'name'          => esc_html__( 'BuddyPress Primary', 'streamtube-core' ),
                'id'            => 'buddypress',
                'description'   => esc_html__( 'Add widgets here to appear in BuddyPress primary sidebar.', 'streamtube-core' ),
                'before_widget' => '<div id="%1$s" class="widget widget-primary widget-buddypress shadow-sm %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<div class="widget-title-wrap"><h2 class="widget-title d-flex align-items-center">',
                'after_title'   => '</h2></div>'
            )
        );        
    }

    /**
     *
     * Enqueue scripts
     * 
     */
    public function enqueue_scripts(){

        wp_register_script( 
            'streamtube-bp-scripts', 
            trailingslashit( plugin_dir_url( __FILE__ ) ) . 'scripts.js', 
            array( 'videojs', 'jquery' ), 
            filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'scripts.js' ), 
            true 
        );

        wp_localize_script( 'streamtube-bp-scripts', 'BP_PM_Star', array(
            'strings' => array(
                'text_unstar'  => __( 'Unstar', 'buddypress' ),
                'text_star'    => __( 'Star', 'buddypress' ),
                'title_unstar' => __( 'Starred', 'buddypress' ),
                'title_star'   => __( 'Not starred', 'buddypress' ),
                'title_unstar_thread' => __( 'Remove all starred messages in this thread', 'buddypress' ),
                'title_star_thread'   => __( 'Star the first message in this thread', 'buddypress' ),
            ),
            'is_single_thread' => (int) bp_is_messages_conversation(),
            'star_counter'     => 0,
            'unstar_counter'   => 0
        ) );          

        if( is_buddypress() ){

            wp_enqueue_style( 'videojs' );   
            wp_enqueue_style( 'videojs-theme-' . get_option( 'player_skin', 'forest' ) );
            wp_enqueue_style( 'streamtube-player' );

            wp_enqueue_script( 'countdown.upcoming' );

            wp_enqueue_script( 'videojs' );
            wp_enqueue_script( 'videojs-contrib-quality-levels' );
            wp_enqueue_script( 'videojs-hls-quality-selector' );            
            wp_enqueue_script( 'videojs-youtube' );
            wp_enqueue_script( 'videojs-contrib-ads' );
            wp_enqueue_script( 'ima3sdk' );
            wp_enqueue_script( 'videojs-ima' );
            wp_enqueue_script( 'videojs-hotkeys' );
            wp_enqueue_script( 'videojs-landscape-fullscreen' );
            wp_enqueue_script( 'videojs-xr' );
            wp_enqueue_script( 'player' );

            wp_enqueue_script(  'streamtube-bp-scripts' );          
        }
    }

    /**
     *
     * Enqueue admin scripts
     * 
     */
    public function admin_enqueue_scripts(){
        wp_enqueue_script(
            'streamtube-bp-admin', 
            plugin_dir_url( __FILE__ ) . 'admin-scripts.js', 
            array( 'jquery' ), 
            filemtime( plugin_dir_path( __FILE__ ) . 'admin-scripts.js' ), 
            true
        );
    }
}