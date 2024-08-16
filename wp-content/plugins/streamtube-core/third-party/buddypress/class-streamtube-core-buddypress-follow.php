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

class StreamTube_Core_buddyPress_Follow{

    /**
     *
     * Check if plugin is active
     * 
     * @return boolean
     */
    public function is_active(){
        return function_exists( 'bp_follow_init' );
    }

    /**
    *
    * Remove filter as the built-in Follow button does not work as expected.
    * 
    */
    public function remove_hooks(){
        remove_filter( 'bp_has_members', 'bp_follow_inject_member_follow_status' );
    }    

    /**
     *
     * Get WP User Follow records
     * 
     */
    public function _get_wpuf_records(){
        global $wpdb;

        return $wpdb->get_results(
            "SELECT * FROM {$wpdb->prefix}user_follow"
        );
    } 

    public function _delete_wpuf_records( $record ){
        global $wpdb;
        $wpdb->delete(
            "{$wpdb->prefix}user_follow",
            array(
                'id' => $record->id,
            )
        );        
    }

    /**
     *
     * Migrate wpuf data
     * 
     */
    public function migrate_wpuf(){

        // Require WP User Follow activated
        if( ! function_exists( 'run_wp_user_follow' ) ){
            return;
        }

        if( ! isset( $_POST ) || ! isset( $_POST['wpuf_to_bp_follow'] ) || ! current_user_can( 'administrator' ) ){
            return;
        }

        $count = 0;

        $records = $this->_get_wpuf_records();

        if( ! $records ){

            add_action( 'admin_notices', function(){
                ?>
                <div class="notice notice-info">
                    <p>
                        <?php printf(
                            esc_html__( 'Migrating data has been successfully completed, %s was deactivated.', 'streamtube-core' ),
                            '<strong>WP User Follow</strong>'
                        );?>
                    </p>
                </div>
                <?php
            } );

            return deactivate_plugins( 'wp-user-follow/wp-user-follow.php' );
        }

        foreach ( $records as $record ) {

            $leader_id      = $record->following_id;
            $follower_id    = $record->follower_id;

            $args = compact( 'leader_id', 'follower_id' );

            if( ! bp_follow_is_following( $args ) ){
                bp_follow_start_following( $args );
            }

            $this->_delete_wpuf_records( $record );

            $count++;
        }

        if( $count ){
            add_action( 'admin_notices', function() use( $count ){
                ?>
                <div class="notice notice-success">
                    <p><?php printf( _n( '%s record has been migrated.', '%s records have been migrated.', $count, 'streamtube-core' ), number_format_i18n( $count ) );?></p>
                </div>
                <?php
            } );

            if( $count == count( $records ) ){
                deactivate_plugins( 'wp-user-follow/wp-user-follow.php' );
            }
        }
    }

    /**
     *
     * Get followers of given user
     * 
     * @param  integer $user_id
     * 
     */
    public function get_followers( $user_id = 0 ){
        return bp_follow_get_followers( array(
            'user_id'   =>  $user_id
        ) );
    }

    /**
     *
     * Filter bp_has_members_template
     * 
     */
    public function filter_bp_has_members_template( $has_members, $members_template, $query_args ){

        if( ! $has_members ){
            return $has_members;
        }

        global $members_template;

        foreach ( (array) $members_template->members as $i => $member ) {

            $members_template->members[ $i ]->is_following = false;

            if ( is_user_logged_in() && get_current_user_id() !== $member->id ) {
                if( bp_follow_is_following( array(
                    'leader_id'   => $member->id,
                    'follower_id' => get_current_user_id(),
                ) ) ){
                    $members_template->members[ $i ]->is_following = true;
                }
            }
        }        

        return $has_members;
    }

    /**
     * Filter bp_ajax_querystring
     */
    public function filter_bp_ajax_querystring( $qs, $object ){

        // not on the members object? stop now!
        if ( 'members' !== $object || ! $this->is_active() ) {
            return $qs;
        }

        $r = wp_parse_args( $qs, array(
            'scope'     =>  '',
            'type'      =>  ''
        ) );

        if( ! in_array( $r['scope'], array( 'following', 'followers' ) ) && ! wp_doing_ajax() ){
            return $qs;
        }

        $user_id = 0;

        if( isset( $_COOKIE['wpbp_displayed_user_id'] ) ){
            $user_id = (int)$_COOKIE['wpbp_displayed_user_id'];
        }

        $referer = streamtube_core_get_referer_data();

        if( $referer ){
            $r['scope']  = $referer['action'];
        }

        if( ! $user_id ){
            return $qs;
        }

        $user_ids = array();

        if( $r['scope'] == 'following' ){
            $user_ids = bp_follow_get_following( array(
                'user_id'   =>  $user_id
            ) );
        }

        if( $r['scope'] == 'followers' ){
            $user_ids = bp_follow_get_followers( array(
                'user_id'   =>  $user_id
            ) );
        }

        if( $user_ids ){
            $r['include'] = $user_ids;
        }else{
            $r['include'] = array(0);
        }

        if( ! $r['type'] ){
            $r['type'] = isset( $_COOKIE['bp-members-filter'] ) ? $_COOKIE['bp-members-filter'] : 'active';

            if( ! is_user_logged_in() ){
                $r['type'] = 'newest-follows';
            }
        }

        return $r;

    }

    /**
     *
     * The follow button
     * 
     * @param  integer $leader_id
     * @return bp_follow_add_follow_button()
     * 
     */
    public function the_follow_button( $leader_id = 0, $follower_id = 0 ){

        if( ! $follower_id ){
            $follower_id = get_current_user_id();
        }

        if( ! $this->is_active() || ( $leader_id == $follower_id ) ){
            return;
        }

        return bp_follow_add_follow_button( compact( 'leader_id', 'follower_id' ) );
    }

    /**
     *
     * Display the button on single post/author
     * 
     */
    public function display_the_single_follow_button(){

        $user_id = 0;

        if( is_singular() ){
            $user_id = $GLOBALS['post']->post_author;
        }

        if( is_author() ){
            $user_id = get_queried_object_id();
        }   

        return $this->the_follow_button( $user_id );
    }

    /**
     *
     * BP profile menu
     * 
     * @param array $menu_items
     * 
     */
    public function display_profile_menu( $menu_items ){

        if( ! $this->is_active() ){
            return $menu_items;
        }

        $menu_items[ 'following' ]    = array(
            'id'            =>  'members-following',
            'title'         =>  esc_html__( 'Following', 'streamtube-core' ),
            'icon'          =>  'icon-user-plus',
            'callback'      =>  function(){

                add_filter( 'bp_current_action', function( $action ){
                    return 'following';
                } );

                add_filter( 'bp_ajax_querystring', function( $qs, $object ){

                    // not on the members object? stop now!
                    if ( 'members' !== $object ) {
                        return $qs;
                    }

                    $type = isset( $_COOKIE['bp-members-filter'] ) ? $_COOKIE['bp-members-filter'] : 'active';

                    if( ! is_user_logged_in() ){
                        $type = 'newest-follows';
                    }

                    return 'scope=following&type=' . $type;

                }, 21, 2 );

                streamtube_core_load_template( 'user/profile/bp-following.php' );
            },
            'widgetizer'    =>  false,
            'priority'      =>  30
        );

        $menu_items[ 'followers' ]    = array(
            'id'            =>  'members-followers',
            'title'         =>  esc_html__( 'Followers', 'streamtube-core' ),
            'icon'          =>  'icon-users',
            'callback'      =>  function(){

                add_filter( 'bp_current_action', function( $action ){
                    return 'followers';
                } );

                add_filter( 'bp_ajax_querystring', function( $qs, $object ){

                    // not on the members object? stop now!
                    if ( 'members' !== $object ) {
                        return $qs;
                    }

                    $type = isset( $_COOKIE['bp-members-filter'] ) ? $_COOKIE['bp-members-filter'] : 'active';

                    if( ! is_user_logged_in() ){
                        $type = 'newest-follows';
                    }

                    return 'scope=followers&type=' . $type;

                }, 21, 2 );

                streamtube_core_load_template( 'user/profile/bp-followers.php' );
            },
            'widgetizer'    =>  false,
            'priority'      =>  30
        );

        return $menu_items;
    }


    /**
     *
     * Admin notices
     * 
     */
    public function display_admin_notices(){

        $wpuf_url = add_query_arg(
            array(
                's' =>  'wp-user-follow',
                'plugin_status' =>  'active'
            ),
            admin_url( 'plugins.php' )
        );

        if( ! $this->is_active() && function_exists( 'run_wp_user_follow' ) ){
            ?>
            <div class="notice notice-info">
                <p>
                    <?php printf(
                        esc_html__( 'It is strongly recommended to deactivate the %1$s plugin and activate the %2$s instead.', 'streamtube-core' ),
                        '<a href="'. esc_url( $wpuf_url ) .'"><strong>WP User Follow</strong></a>',
                        '<a target="_blank" href="https://github.com/r-a-y/buddypress-followers"><strong>BuddyPress Follow</strong></a>'
                    )?>
                </p>
            </div>
            <?php
        }

        if( $this->is_active() && function_exists( 'run_wp_user_follow' ) ){

            $records = $this->_get_wpuf_records();

            ?>
            <div class="notice notice-warning">
                <p>
                    <?php printf(
                        esc_html__( '%1$s and %2$s plugins are activated simultaneously, it is strongly recommended to deactivate the %2$s plugin.', 'streamtube-core' ),
                        '<strong>BuddyPress Follow</strong>',
                        '<strong>WP User Follow</strong>'
                    )?>
                </p>

                <p>
                    <?php printf(
                        '<a class="button button-primary" href="%s">%s</a>',
                        esc_url( $wpuf_url ),
                        sprintf(
                            esc_html__( 'Deactivate the %s plugin', 'streamtube-core' ),
                            '<strong>WP User Follow</strong>'
                        )
                    )?>
                </p>                

                <?php if( $records ): ?>
                    <form method="post">
                        <p>
                            <button type="submit" class="button button-primary">
                            <?php printf(
                                esc_html__( 'Migrate data from %s to %s', 'streamtube-core' ),
                                '<strong>WP User Follow</strong>',
                                '<strong>BuddyPress Follow</strong>'
                            )?>
                            </button>
                        </p>

                        <?php wp_nonce_field( 'wpuf_to_bp_follow', 'wpuf_to_bp_follow' );?>
                    </form> 
                <?php endif;?>
              
            </div>
            <?php
        }
    }    
}