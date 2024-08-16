<?php
/**
 * Define the buddyPress Friends component functionality
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

class StreamTube_Core_buddyPress_Friends{
    /**
     *
     * Check if Friends is active
     * 
     * @return boolean
     */
    public function is_active(){
        return bp_is_active( 'friends' );
    }

    /**
     *
     * Filter bp_ajax_querystring for friends component
     *
     * @param string $qs
     * @param string $object
     * 
     */
    public function filter_bp_ajax_querystring( $qs, $object ){

        if( $object != 'members' || ! $this->is_active() ){
            return $qs;
        }

        $r = wp_parse_args( $qs, array(
            'scope'     =>  isset( $_REQUEST['scope'] ) ? wp_unslash( $_REQUEST['scope'] ) : ''
        ) );

        if( ! $r['scope'] || ! array_key_exists( 'wpbp_displayed_user_id', $_COOKIE ) ){
            return $qs;
        }

        switch ( $r['scope'] ) {
            case 'friends':

                $friends = friends_get_friend_user_ids( $_COOKIE['wpbp_displayed_user_id'] );

                if( $friends ){
                    $r['include'] = $friends;
                }
                else{
                    $r['include'] = array(0);
                }
            break;
        }

        return $r;
    }    

    /**
     *
     * Get friends of given user
     * 
     * @param  integer $user_id
     * @return array
     * 
     */
    public function get_friends( $user_id = 0 ){
        return friends_get_friend_user_ids( $user_id );
    }

    /**
     *
     * The Add friend button
     * 
     * @param  integer $user_id
     */
    public function the_add_friend_button( $user_id = 0 ){

        if( ! $this->is_active() ){
            return;
        }

        return bp_add_friend_button( $user_id );
    }

    /**
     *
     * Display the button on single post/author
     * 
     */
    public function display_the_single_add_friend_button(){

        $user_id = 0;

        if( is_singular() ){
            $user_id = $GLOBALS['post']->post_author;
        }

        if( is_author() ){
            $user_id = get_queried_object_id();
        }

        if( ! $user_id || ( is_user_logged_in() && get_current_user_id() == $user_id ) ){
            return;
        }

        return $this->the_add_friend_button( $user_id );
    }    

    /**
     *
     * BP profile menu
     * 
     * @param array $menu_items
     * 
     */
    public function display_profile_menu( $menu_items ){
        if( $this->is_active() ){
            $menu_items[ bp_get_friends_slug() ]    = array(
                'id'            =>  'members-friends',
                'title'         =>  esc_html__( 'Friends', 'streamtube-core' ),
                'icon'          =>  'icon-users',
                'callback'      =>  function(){
                    streamtube_core_load_template( 'user/profile/friends.php' );
                },
                'widgetizer'    =>  false,
                'priority'      =>  30
            );            
        }

        return $menu_items;
    }    
}