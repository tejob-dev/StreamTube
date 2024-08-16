<?php
/**
 * Define the buddyPress Group component functionality
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

class StreamTube_Core_buddyPress_Group{
    /**
     *
     * Check if groups is active
     * 
     * @return boolean
     */
    public function is_active(){
        return bp_is_active( 'groups' );
    }

    /**
     *
     * Filter the Join Group button
     * 
     */
    public function filter_bp_get_group_join_button( $button_args, $group ){

        $classes = array( 'btn', 'btn-sm' );

        switch ( $button_args['id'] ) {
            case 'join_group':
                $classes[] = 'btn-primary';
            break;

            case 'leave_group':
                $classes[] = 'btn-danger';
            break;            
            
            default:
                $classes[] = 'btn-secondary';
            break;
        }

        $button_args['link_class'] .= ' ' . implode(' ', $classes );

        return $button_args;
    }

    /**
     *
     * Filter the group description
     * 
     */
    public function filter_bp_get_group_description( $description, $group ){
        return wp_trim_words( $description, 50 );
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
            $menu_items[ bp_get_groups_slug() ]    = array(
                'id'            =>  'groups-personal',
                'title'         =>  esc_html__( 'Groups', 'streamtube-core' ),
                'icon'          =>  'icon-users',
                'callback'      =>  function(){
                    streamtube_core_load_template( 'user/profile/groups.php' );
                },
                'widgetizer'    =>  false,
                'priority'      =>  30
            );
        }

        return $menu_items;
    }     

}