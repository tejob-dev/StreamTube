<?php
/**
 * Define the buddyPress Notifications component functionality
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

class StreamTube_Core_buddyPress_Notifications{

    /**
     *
     * Check if Notifications is active
     * 
     * @return boolean
     */
    public function is_active(){
        return bp_is_active( 'notifications' );
    }

    /**
     *
     * Check if notify is enabled, an extra feature of StreamTube Core
     * 
     * @return boolean
     */
    public function is_notify_followers(){
        if( 
            ( defined( 'BP_NOTIFY_FOLLOWERS_NEW_UPDATE' ) && BP_NOTIFY_FOLLOWERS_NEW_UPDATE ) 
            || apply_filters( 'bp_notify_followers_new_update', true ) ){
            return true;
        }

        return false;
    }

    /**
     *
     * Get unread notifications
     * 
     * @return int
     */
    public function get_unread_notifications( $user_id = 0 ){

        if( ! $this->is_active() ){
            return 0;
        }

        if( $user_id && is_user_logged_in() ){
            $user_id = get_current_user_id();
        }

        return (int)bp_notifications_get_unread_notification_count( $user_id );
    }

    /**
     * Get the notification badge
     */
    public function get_unread_notifications_badge( $user_id = 0 ){

        $badge = '';

        $count = $this->get_unread_notifications( $user_id );

        if( $count ){
            $badge = sprintf(
                '<span class="badge bg-danger">%s<span>',
                apply_filters( 'streamtube/format_number', number_format_i18n( $count ) )
            );
        }

        /**
         *
         * Filter the badge
         *
         * @param string $badge
         * @param int $user_id
         * 
         */
        return apply_filters( 'streamtube/core/bp/notifications/unread_notifications_badge', $badge, $user_id );
    }

    /**
     *
     * Filter the wpmi
     * Replace the icon with "icon-bell-alt"
     * 
     * @param  array $wpmi
     * @param  object $item
     * @param  array $args
     * 
     */
    public function filter_notification_icon( $wpmi, $item, $args, $depth ){

        if( ! $this->is_active() || ! is_object( $item ) || ! is_array( $item->classes ) ){
            return $wpmi;
        }

        if( in_array( 'bp-notifications-nav', $item->classes ) ){
            $wpmi = array_merge( $wpmi, array(
                'icon'  =>  'icon-bell-alt'
            ) );
        }

        return $wpmi;
    }

    /**
     *
     * Get all tracking post types
     *
     * @return array of post type and args
     * 
     */
    public function get_tracking_post_types(){

        // Video and Post post types are supported if activity isn't active.
        $post_types = array( 'video', 'post' );

        $_post_types = array_keys( get_post_types( array( 'public' => true ), 'names' ) );

        if( ! $_post_types ){
            return;
        }

        for ( $i = 0; $i < count( $_post_types ); $i++ ) { 
            if( bp_is_active( 'activity' ) && post_type_supports( $_post_types[$i], 'buddypress-activity' ) ){
                $post_types[] = $_post_types[$i];
            }
        }

        return array_unique( $post_types );
    }

    /**
     *
     * Filter the notification components
     * 
     * @param  array $component_names
     */
    public function filter_bp_notifications_get_registered_components( $component_names = array(), $active_components = array() ){

        if( ! $component_names ){
            $component_names = array();
        }

        $tracking_post_types = $this->get_tracking_post_types();

        if( $tracking_post_types ){
            return array_merge( $component_names, $tracking_post_types );
        }

        return $component_names;
    }

    /**
     *
     * Filter the custom notification message content
     * 
     */
    public function filter_notification_description( $description, $notification ){

        if( ! in_array( $notification->component_name, $this->get_tracking_post_types() ) ){
            return $description;
        }

        $_post  = get_post( $notification->item_id );

        if( ! $_post ){
            return sprintf(
                '<p class="m-0 text-muted">%s</p>',
                esc_html__( 'Activity has been deleted or is inaccessible.', 'streamtube-core' )
            );
        }

        $action = explode( "_" ,  $notification->component_action );

        if( ! is_array( $action ) ){
            return $description;
        }

        $user   = get_userdata( $notification->secondary_item_id );

        switch ( $action[0] ) {
            case 'new':
                $description = sprintf(
                   __( '<a href="%1$s">%2$s</a> %3$s: <a class="text-muted post-title" href="%4$s">%5$s</a>', 'streamtube-core' ),
                   get_author_posts_url( $notification->secondary_item_id ),
                   is_object( $user ) ? $user->display_name : esc_html__( 'Unknown', 'streamtube-core' ),
                   ( $notification->component_name == 'video' ) ? esc_html__( 'uploaded', 'streamtube-core' ) : esc_html__( 'wrote','streamtube-core' ),
                   get_permalink( $_post->ID ),
                   $_post->post_title
                );
            break;

            case 'rejected':
                $description = sprintf(
                   __( '<a href="%1$s">%2$s</a> %3$s: <a class="text-muted post-title" href="%4$s">%5$s</a>', 'streamtube-core' ),
                   get_author_posts_url( $notification->secondary_item_id ),
                   is_object( $user ) ? $user->display_name : esc_html__( 'Unknown', 'streamtube-core' ),
                   '<span class="text-danger">'. esc_html__( 'rejected', 'streamtube-core' ) .'</span>',
                   get_permalink( $_post->ID ),
                   $_post->post_title
                );
            break;

            case 'approved':
                $description = sprintf(
                   __( '<a href="%1$s">%2$s</a> %3$s: <a class="text-muted post-title" href="%4$s">%5$s</a>', 'streamtube-core' ),
                   get_author_posts_url( $notification->secondary_item_id ),
                   is_object( $user ) ? $user->display_name : esc_html__( 'Unknown', 'streamtube-core' ),
                   '<span class="text-success">'. esc_html__( 'approved', 'streamtube-core' ) .'</span>',
                   get_permalink( $_post->ID ),
                   $_post->post_title
                );
            break;            
        
        }

        /**
         *
         * Filter the description
         * 
         * @param string $description
         * @param object $notification
         * @param object WP_Post $_post
         * 
         */
        $description = apply_filters( "streamtube/core/bp/notification/description", $description, $notification, $_post );

        /**
         *
         * Filter the description
         *
         * @param string $description
         * @param object $notification
         * @param object WP_Post $_post
         * 
         */
        $description = apply_filters( "streamtube/core/bp/notification/description/{$_post->post_type}", $description, $notification, $_post );        

        return $description;
    }

    /**
     *
     * Send an internal notification after post has been moderated
     * 
     */
    public function notify_author_post_moderated( $post_id, $action, $message = '' ){

        if( ! $this->is_active() ){
            return;
        }

        $post = get_post( $post_id );

        if( ! is_a( $post, 'WP_Post' ) || ! in_array( $post->post_type , $this->get_tracking_post_types() ) ){
            return;
        }

        $notification_id = bp_notifications_add_notification( array(
            'user_id'           => $post->post_author,
            'item_id'           => $post->ID,
            'secondary_item_id' => get_current_user_id(),
            'component_name'    => $post->post_type,
            'component_action'  => "{$action}_{$post->post_type}",
            'date_notified'     => bp_core_current_time(),
            'is_new'            => 1,
            'allow_duplicate'   => true
        ) );

        if( $notification_id ){
            do_action( 
                'streamtube/core/bp/notify_author_post_moderated', 
                $post_id, $action, $message
            );
        }

        return $notification_id;
    }

    /**
     *
     * Display the Notification Bell button on header
     * 
     */
    public function display_header_notification_button(){
        if( $this->is_active() && is_user_logged_in() && ! streamtube_core_has_mobile_footer_bar() ){
            bp_get_template_part( 'members/single/notifications/notifications-dropdown' );
        }
    }    

    /**
     *
     * Display the unread badge on bottom menu item
     *
     * Hooked into "wp_menu_item_title" filter
     * 
     */
    public function display_unread_notifications_badge( $title, $wpmi, $item, $args, $depth ){

        if( ! $this->is_active() || ! is_object( $item ) || ! is_array( $item->classes ) || ! is_user_logged_in() ){
            return $title;
        }

        if( in_array( 'bp-notifications-nav', $item->classes ) ){
            $title .= $this->get_unread_notifications_badge();
        }

        return $title;
    }

    /**
     *
     * Display the Notification menu item
     */
    public function display_dashboard_menu_item( $menu_items ){

        if( $this->is_active() ){
            $menu_items[ bp_get_notifications_slug() ] = array(
                'title'     =>  esc_html__( 'Notifications', 'streamtube-core' ),
                'desc'      =>  esc_html__( 'All Notifications', 'streamtube-core' ),
                'icon'      =>  'icon-bell-alt',
                'callback'  =>  function(){
                    streamtube_core_load_template( 'user/dashboard/notifications.php' );
                },
                'badge'     =>  $this->get_unread_notifications_badge(),
                'parent'    =>  'dashboard',
                'cap'       =>  'read',
                'priority'  =>  10
            );
        } 

        return $menu_items;
    }
}