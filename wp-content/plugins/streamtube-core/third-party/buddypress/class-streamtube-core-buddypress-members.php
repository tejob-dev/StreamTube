<?php
/**
 * Define the buddyPress follow functionality
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

class StreamTube_Core_buddyPress_Members{
    /**
     *
     * Check if members is active
     * 
     * @return boolean
     */
    public function is_active( $feature = null ){
        return bp_is_active( 'members', $feature );
    }

    /**
     *
     * Display last active
     * 
     */
    public function display_last_active_time( $user_id = 0 ){
        ?>
        <div class="last-active item-meta my-3">
            <span class="activity text-muted small"><?php bp_member_last_active(); ?></span>
        </div>
        <?php
    }    

    /**
     *
     * Filter member avatar
     * 
     */
    public function filter_bp_get_member_avatar( $avatar, $r ){
        if( bp_get_member_user_id() ){
            return streamtube_core_get_user_avatar( array(
                'link'          =>  false,
                'wrap_size'     =>  'lg',
                'user_id'       =>  bp_get_member_user_id()
            ) );
        }else{
            return $avatar;
        }
    }

    /**
     *
     * Filter the member invigation permalink
     * 
     */
    public function filter_bp_get_members_invitations_send_invites_permalink( $retval, $user_id ){
        return trailingslashit(get_author_posts_url( $user_id )) . 'dashboard/invitations/send-invites';
    }

    /**
     *
     * Filter the member invigation permalink
     * 
     */
    public function filter_bp_get_members_invitations_list_invites_permalink( $retval, $user_id ){
        return trailingslashit(get_author_posts_url( $user_id )) . 'dashboard/invitations/list-invites';
    }           

    /**
     *
     * The dashboard menu
     * 
     * @param array $menu_items
     */
    public function display_dashboard_menu_item( $menu_items ){     

        if( $this->is_active( 'invitations' ) && get_option( 'bp-enable-members-invitations' ) ){
            $menu_items[ 'invitations' ]    = array(
                'id'            =>  'members-invitations',
                'title'         =>  esc_html__( 'Invitations', 'streamtube-core' ),
                'icon'          =>  'icon-mail-alt',
                'callback'      =>  function(){
                    streamtube_core_load_template( 'user/dashboard/invitations.php' );
                },
                'widgetizer'    =>  false,
                'parent'        =>  'dashboard',
                'priority'      =>  30
            );             
        }        

        return $menu_items;
    }       
}