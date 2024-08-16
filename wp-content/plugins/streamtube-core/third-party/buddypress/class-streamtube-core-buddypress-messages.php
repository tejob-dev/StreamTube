<?php
/**
 * Define the buddyPress messages component functionality
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

class StreamTube_Core_buddyPress_Messages{

    /**
     *
     * Check if Messages is active
     * 
     * @return boolean
     */
    public function is_active(){
        $retvar = bp_is_active( 'messages' ) && ! class_exists( 'BP_Better_Messages' );

        /**
         *
         * Filter the retvar
         *
         * @param boolean $retvar
         * 
         */
        return apply_filters( 'streamtube/core/bp/messages/is_active', $retvar );
    }

    /**
     *
     * Get unread messages
     * 
     */
    public function get_unread_messages( $user_id = 0 ){

        if( ! $user_id && is_user_logged_in() ){
            $user_id = get_current_user_id();
        }

        return (int)messages_get_unread_count( $user_id );        
    }

    /**
     * Get the messages badge
     */
    public function get_unread_messages_badge( $user_id = 0 ){

        $badge = '';

        $count = $this->get_unread_messages( $user_id );

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
        return apply_filters( 'streamtube/core/bp/messages/unread_messages_badge', $badge, $user_id );
    }

    /**
     *
     * Filter message classes, remove "alt" class
     * 
     */
    public function filter_bp_get_message_css_class( $classes ){
        return str_replace( 'alt', 'alt2', $classes );
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
    public function filter_message_icon( $wpmi, $item, $args, $depth ){

        if( ! is_object( $item ) || ! is_array( $item->classes ) ){
            return $wpmi;
        }

        if( in_array( 'bp-messages-nav', $item->classes ) ){
            $wpmi = array_merge( $wpmi, array(
                'icon'  =>  'icon-chat'
            ) );
        }

        return $wpmi;
    }    

    /**
     *
     * The send message button
     * 
     * @return bp_send_private_message_button()
     * 
     */
    public function the_send_message_button(){
        return bp_send_private_message_button();
    }

    /**
     *
     * Display send private message button
     * 
     */
    public function display_send_message_button( $args = array() ){
        if ( $this->is_active() ){
            $this->the_send_message_button();
        }
    }

    /**
     *
     * Display the unread badge
     *
     * Hooked into "wp_menu_item_title" filter
     * 
     */
    public function display_unread_messages_badge( $title, $wpmi, $item, $args, $depth ){

        if( ! is_object( $item ) || ! is_array( $item->classes ) || ! is_user_logged_in() ){
            return $title;
        }

        if( in_array( 'bp-messages-nav', $item->classes ) ){
            $title .= $this->get_unread_messages_badge();           
        }

        return $title;
    }    

    /**
     *
     * Display the global notice on dashboard
     * 
     */
    public function display_global_notice(){

        if( ! class_exists( 'BP_Messages_Notice' )  ){
            return;
        }

        bp_get_template_part( 'members/single/messages/global-notice' );
    }    

    /**
     *
     * Display the Notification menu item
     */
    public function display_dashboard_menu_item( $menu_items ){

        if( $this->is_active() ){
            $menu_items[ bp_get_messages_slug() ] = array(
                'title'     =>  esc_html__( 'Messages', 'streamtube-core' ),
                'desc'      =>  esc_html__( 'All messages', 'streamtube-core' ),
                'icon'      =>  'icon-chat',
                'callback'  =>  function(){
                    streamtube_core_load_template( 'user/dashboard/messages.php' );
                },
                'badge'     =>  $this->get_unread_messages_badge(),
                'parent'    =>  'dashboard',
                'cap'       =>  'read',
                'priority'  =>  30
            );
        } 

        return $menu_items;
    }    
}