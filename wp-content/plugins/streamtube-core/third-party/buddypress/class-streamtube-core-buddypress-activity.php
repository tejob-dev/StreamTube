<?php
/**
 * Define the buddyPress activity component functionality
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

class StreamTube_Core_buddyPress_Activity{
    /**
     *
     * Check if activity is active
     * 
     * @return boolean
     */
    public function is_active(){
        return bp_is_active( 'activity' );
    }

    /**
     *
     * Get all tracking post types
     * 
     * @return array
     */
    public function get_tracking_post_types(){
        return array_values( 
            array_unique( 
                wp_list_pluck( 
                    bp_activity_get_post_types_tracking_args(), 
                    'post_type' 
                ) 
            ) 
        );   
    }

    /**
     *
     * Filter bp_ajax_querystring for activity component
     *
     * @param string $qs
     * @param string $object
     * 
     */
    public function filter_bp_ajax_querystring( $qs, $object ){
        global $wp_query;

        if( $object != 'activity' || ! $this->is_active() ){
            return $qs;
        }

        $referer = streamtube_core_get_referer_data();

        if( ! $referer ){
            return $qs;
        }

        $r = wp_parse_args( $qs, array(
            'scope' => ! $referer['scope'] ? 'just-me' : $referer['scope']
        ));

        if( $r['scope'] == 'just' ){
            $r['scope'] = 'just-me';
        }

        if( isset( $_COOKIE['wpbp_displayed_user_id'] ) ){
            $r['user_id'] = (int)$_COOKIE['wpbp_displayed_user_id'];
        }

        // Single activity
        if( array_key_exists( 'activity', $wp_query->query_vars ) && (int)$wp_query->query_vars['activity'] > 0 ){
            $r['include'] = $wp_query->query_vars['activity'];
        }

        return $r;
    } 

    /**
     *
     * Filter activity entry class
     * 
     */
    public function filter_bp_get_activity_css_class( $class ){

        $class = str_replace( 'mini', '', $class );

        return $class . ' position-relative jsappear rounded shadow-sm bg-white p-0 mb-4 border-bottom';
    }     

    /**
     *
     * Filter the delete activity button
     * add more classes
     * 
     */
    public function filter_bp_get_activity_delete_link( $link ){
        return str_replace( 'confirm', 'confirm btn-delete icon-custom btn btn-white border-0 btn-sm', $link );
    }

    /**
     *
     * Hooked into "bp_activity_entry_content" action
     * 
     */
    public function display_the_player(){

        if( bp_get_activity_type() != 'new_video' ){
            return;
        }

        $post_id = bp_get_activity_secondary_item_id();

        if( get_post_type( $post_id ) == Streamtube_Core_Post::CPT_VIDEO ){
            global $streamtube;

            $player_args = array(
                'post_id'       =>  $post_id,
                'ratio'         =>  '16x9',
                'has_filter'    =>  true,
                'autoplay'      =>  false
            );

            /**
             *
             * Filter player args
             *
             * @param array $player_args
             * @param int $post_id
             * 
             */
            $player_args = apply_filters( 'streamtube/core/buddypress/activity/player_args', $player_args, $post_id );

            $output = sprintf(
                '<div class="activity-player">%s</div>',
                $streamtube->get()->shortcode->_player( $player_args )
            );

            /**
             *
             * Filter player output
             *
             * @param string $output
             * @param array $player_args
             * @param int $post_id
             * 
             */
            $output = apply_filters( 'streamtube/core/buddypress/activity/player', $output, $player_args, $post_id );

            echo $output;
        }
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
            $menu_items[ bp_get_activity_slug() ]    = array(
                'title'         =>  esc_html__( 'Activity', 'streamtube-core' ),
                'icon'          =>  'icon-globe',
                'callback'      =>  function(){
                    streamtube_core_load_template( 'user/profile/activity.php' );
                },
                'widgetizer'    =>  false,
                'priority'      =>  0
            );            
        }

        return $menu_items;
    }

    /**
     *
     * Do ajax migrating activity
     * 
     */
    public function ajax_migrate_activity(){

        if( ! $this->is_active() || ! current_user_can( 'administrator' ) ) {
            exit;
        }

        $posts = new WP_Query( array(
            'post_type'         =>  $this->get_tracking_post_types(),
            'post_status'       =>  'publish',
            'posts_per_page'    =>  5,
            'fields'            =>  'ids',
            'paged'             =>  isset( $_REQUEST['page'] ) ? (int)$_REQUEST['page'] : 1
        ) );

        if( $posts->have_posts() ){
            for ( $i=0; $i < count( $posts->posts ); $i++) { 
                wp_update_post( array(
                    'ID'            =>  $posts->posts[$i],
                    'post_status'   =>  'publish'
                ) );
            }

            wp_send_json_success( array(
                'posts'             =>  $posts->posts,
                'count'             =>  count( $posts->posts ),
                'total'             =>  $posts->found_posts
            ) );
        }

        update_option( 'dismiss_activity_migration', 'on' );

        wp_send_json_error( array(
            'message'   =>  esc_html__( 'The migration process has been successfully completed!', 'streamtube-core' )
        ) );
    }

    /**
     *
     * AJAX dismiss_migrate_activity
     * 
     */
    public function ajax_dismiss_migrate_activity(){
        if( current_user_can( 'administrator' ) ){
            update_option( 'dismiss_activity_migration', 'on' );
        }
        exit;
    } 

    /**
     *
     * Display admin notices and take necessary actions, such as performing migrations
     * 
     */
    public function display_admin_notices(){

        if( ! $this->is_active() || ! current_user_can( 'administrator' ) || get_option( 'dismiss_activity_migration' ) ){
            return;
        }

        ?>
        <div class="notice notice-info is-dismissible" id="migrate-activity-notice">
            <form id="activity-migration" method="post">
                <p>
                    <?php esc_html_e( 'Would you like to migrate existing tracked posts to activity streams?', 'streamtube-core' );?>
                </p>

                <ul>
                    <li><?php esc_html_e( 'Dismiss this if there are no posts, or if this is a fresh WordPress installation.', 'streamtube-core' );?></li>

                    <li>
                        <?php printf(
                            '<button type="submit" class="button button-primary">%s</button>',
                            esc_html__( 'Start the data migration process', 'streamtube-core' )
                        )?>
                    </li>
                </ul>

                <div class="progress-wrap d-none">
                    <div class="progress-bar" style="width: 0%"></div>
                </div>

                <input type="hidden" name="action" value="migrate_activity" />
            </form>
        </div>
        <?php
    }
}