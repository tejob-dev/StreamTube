<?php
/**
 * Define the buddypress user list widget functionality
 *
 *
 * @link       https://themeforest.net/user/phpface
 * @since      1.0.0
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
class StreamTube_Core_buddyPress_Widget_User_List extends WP_Widget{
    /**
     * {@inheritDoc}
     * @see WP_Widget::__construct()
     */
    function __construct(){
    
        parent::__construct( 
            'bp-user-list-widget' ,
            esc_html__('[StreamTube BP] User List', 'streamtube-core' ), 
            array( 
                'classname'     =>  'bp-user-list-widget widget_bp_core_members_widget buddypress streamtube-widget', 
                'description'   =>  esc_html__('[StreamTube BP] User List', 'streamtube-core' )
            )
        );
    }

    /**
     * Register this widget
     */
    public static function register(){
        register_widget( __CLASS__ );
    }

    /**
     *
     * Get user source
     * 
     * @return array
     */
    public static function get_sources(){

        $r = array(
            'all'   =>  esc_html__( 'All members', 'streamtube-core' )
        );

        if( bp_is_active( 'friends' ) ){
            $r['friends'] = esc_html__( 'Current logged in user\'s friends', 'streamtube-core' );
            $r['member_friends'] = esc_html__( 'Current member\'s friends', 'streamtube-core' );
        }

        if( function_exists( 'bp_follow_init' ) ){
            $r = array_merge( $r, array(
                'following'         =>  esc_html__( 'Current logged in user\'s following', 'streamtube-core' ),
                'followers'         =>  esc_html__( 'Current logged in user\'s followers', 'streamtube-core' ),
                'member_following'  =>  esc_html__( 'Current member\'s following', 'streamtube-core' ),
                'member_followers'  =>  esc_html__( 'Current member\'s followers', 'streamtube-core' ),                
            ) );
        }

        return $r;
    }

    /**
     *
     * Get user types
     *
     *  Accepts 'active', 'random', 'newest', 'popular', 'online', 'alphabetical'
     * 
     * @return array
     */
    public static function get_types(){
        return array(
            'active'            =>  esc_html__( 'Last active', 'streamtube-core' ),
            'random'            =>  esc_html__( 'Random', 'streamtube-core' ),
            'newest'            =>  esc_html__( 'Newest', 'streamtube-core' ),
            'popular'           =>  esc_html__( 'Popular', 'streamtube-core' ),
            'online'            =>  esc_html__( 'Online', 'streamtube-core' ),
            'alphabetical'      =>  esc_html__( 'Alphabetical', 'streamtube-core' ),
            'random'            =>  esc_html__( 'Random', 'streamtube-core' )
        );
    }    

    /**
     *
     * Get users
     * 
     */
    private function get_users( $args = array() ){

        $args = wp_parse_args( $args, array(
            'user_id'   =>  0,
            'source'    =>  'friends',
            'type'      =>  'alphabetical',
            'per_page'  =>  20
        ) );

        $user_ids = array();

        extract( $args );

        switch ( $source ) {
            
            case 'following':
            case 'member_following':
                if( function_exists( 'bp_follow_get_following' ) ){
                    $user_ids = bp_follow_get_following( compact( 'user_id' ) );
                }
            break;

            case 'followers':
            case 'member_followers':
                if( function_exists( 'bp_follow_get_following' ) ){
                    $user_ids = bp_follow_get_followers( compact( 'user_id' ) );
                }
            break;

            case 'all':
                $user_ids = bp_core_get_users( array(
                    'type'      =>  $type,
                    'per_page'  =>  $per_page
                ) );

                if( $user_ids ){
                    $user_ids = wp_list_pluck( $user_ids['users'], 'ID' );
                }
            break;

            default:
                $user_ids = apply_filters( "streamtube/core/bp/widget/user_list/{$source}", array(), $args );
            break;
        }

        /**
         *
         * Filter the user ids
         *
         * @param array $user_ids
         * @param array $args
         * @param string $id_base
         * 
         */
        return apply_filters( 'streamtube/core/bp/widget/user_list/users', $user_ids, $args, $this->id_base );

    }

    /**
     *
     * Get total friend count
     * 
     * @return int
     */
    private function get_friend_count(){
        return friends_get_total_friend_count( bp_get_member_user_id() );
    }

    /**
     *
     * Get total follower count
     * 
     * @return int
     */
    private function get_follower_count(){
        return bp_follow_get_the_followers_count( array( 'user_id' => bp_get_member_user_id() ) );
    }

    /**
     * {@inheritDoc}
     * @see WP_Widget::widget()
     */
    
    public function widget( $args, $instance ) {

        global $members_template;

        $instance = wp_parse_args( $instance, array(
            'title'             =>  '',
            'per_page'          =>  20,
            'user_id'           =>  get_current_user_id(),
            'include'           =>  array(),       
            'source'            =>  'friends',
            'type'              =>  'alphabetical',
            'populate_extras'   =>  0,
            'last_active'       =>  '',
            'friend_count'      =>  '',
            'follower_count'    =>  ''
        ) );

        if( in_array( $instance['source'], array( 'member_friends', 'member_followers', 'member_following' ) ) ){
            if( is_author() ){
                $instance['user_id'] = get_queried_object_id();    
            }
            
            if( is_singular() ){
                global $post;
                $instance['user_id'] = $post->post_author;
            }
        }

        if( $instance['source'] != 'all' ){
            if( stripos( $instance['title'], '%s' ) !== false ){
                $instance['title'] = str_replace( '%s' , get_userdata( $instance['user_id'] )->display_name, $instance['title'] );
            }
        }

        /**
         *
         * Filter widget title
         * 
         */
        $instance['title'] = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );

        /**
         *
         * Filter instance
         *
         * @param array $instance
         * @param string $id_base
         * 
         */
        $instance = apply_filters( 'streamtube/core/bp/widget/user_list', $instance, $this->id_base );  

        extract( $instance );      

        $user_ids = $this->get_users( array(
            'user_id'   =>  $user_id,
            'per_page'  =>  $per_page,
            'source'    =>  $source,
            'type'      =>  $type
        ) );

        echo $args['before_widget'];

            if( $instance['title'] ){
                echo $args['before_title'] . $title . $args['after_title'];
            }

            $members_args = compact( 'user_ids', 'per_page', 'type', 'populate_extras' );

            if( in_array( $source , array( 'member_friends', 'friends' ) ) ){
                unset( $members_args['user_ids'] );
                $members_args['user_id'] = $user_id;
            }

            if ( bp_has_members( $members_args ) ):

                // Back up the global.
                $old_members_template = $members_template;

                echo '<ul class="item-list user-list friend-list list-unstyled border-0">';

                    while ( bp_members() ) : bp_the_member();
                    ?>
                    <li class="vcard border-bottom member-<?php bp_member_user_id();?>">
                        <div class="d-flex align-items-start gap-4">
                            <div class="item-avatar">
                                <a data-member-id="<?php bp_member_user_id(); ?>" href="<?php bp_member_permalink(); ?>" class="bp-tooltip" data-bp-tooltip="<?php bp_member_name(); ?>">
                                    <?php bp_member_avatar(); ?>
                                </a>
                            </div>

                            <div class="item m-0">

                                <?php do_action( 'streamtube/core/bp/widget/user_list/title/before', $instance, $this->id_base );?>

                                <div class="item-title">
                                    <a data-member-id="<?php bp_member_user_id(); ?>" href="<?php bp_member_permalink(); ?>" class="bp-tooltip" data-bp-tooltip="<?php bp_member_name(); ?>">
                                        <?php bp_member_name(); ?>
                                    </a>
                                </div>

                                <?php do_action( 'streamtube/core/bp/widget/user_list/title/after', $instance, $this->id_base );?>

                                <div class="item-meta mb-2">

                                    <?php if( $last_active ): ?>
                                        <span class="last-activity text-muted" data-livestamp="<?php bp_core_iso8601_date( bp_get_member_last_active( array( 'relative' => false ) ) ); ?>">
                                            <?php bp_member_last_active(); ?>
                                        </span>
                                    <?php endif;?>

                                    <?php if( bp_is_active( 'friends' ) && $friend_count ): $count = $this->get_friend_count(); ?>
                                        <span class="friend-count text-muted">
                                            <?php printf( _n( '%s friend', '%s friends', $count, 'streamtube-core' ), number_format_i18n( $count ) ); ?>
                                        </span>
                                    <?php endif;?>

                                    <?php if( function_exists( 'bp_follow_init' ) && $follower_count ): $count = $this->get_follower_count(); ?>
                                        <span class="friend-count text-muted">
                                            <?php printf( _n( '%s follower', '%s followers', $count, 'streamtube-core' ), number_format_i18n( $count ) ); ?>
                                        </span>
                                    <?php endif;?>

                                </div>

                                <?php do_action( 'streamtube/core/bp/widget/user_list/meta/after', $instance, $this->id_base );?>
                            </div>
                        </div>
                    </li>
                    <?php
                    endwhile;

                echo '</ul>';

                // Restore the global.
                $members_template = $old_members_template;

            else:

                printf(
                    '<p class="text-muted mb-0">%s</p>',
                    esc_html__( 'No users were found.', 'streamtube-core' )
                );

            endif;

        echo $args['after_widget'];
    }   
    
    /**
     * {@inheritDoc}
     * @see WP_Widget::update()
     */
    public function update( $new_instance, $old_instance ) {
        return $new_instance;
    }

    /**
     * {@inheritDoc}
     * @see WP_Widget::form()
     */
    public function form( $instance ){
        $instance = wp_parse_args( $instance, array(
            'title'             =>  '',
            'per_page'          =>  20,  
            'source'            =>  'friends',
            'type'              =>  'alphabetical',
            'last_active'       =>  '',
            'friend_count'      =>  '',
            'follower_count'    =>  '',
            'add_friend_button' =>  '',
            'follow_button'     =>  ''
        ) );

        ?>
        <div class="field-control">
            <?php printf(
                '<label for="%s">%s</label>',
                esc_attr( $this->get_field_id( 'title' ) ),
                esc_html__( 'Title', 'streamtube-core')

            );?>
            
            <?php printf(
                '<input type="text" class="widefat" id="%s" name="%s" value="%s" />',
                esc_attr( $this->get_field_id( 'title' ) ),
                esc_attr( $this->get_field_name( 'title' ) ),
                esc_attr( $instance['title'] )
            );?>
        </div>

        <div class="field-control">
            <?php printf(
                '<label for="%s">%s</label>',
                esc_attr( $this->get_field_id( 'per_page' ) ),
                esc_html__( 'Number', 'streamtube-core')

            );?>
            
            <?php printf(
                '<input type="text" class="widefat" id="%s" name="%s" value="%s" />',
                esc_attr( $this->get_field_id( 'per_page' ) ),
                esc_attr( $this->get_field_name( 'per_page' ) ),
                esc_attr( $instance['per_page'] )
            );?>

            <p class="field-help">
                <?php esc_html_e( 'Number of results per page.', 'streamtube-core' );?>
            </p>
        </div>

        <div class="field-control">
            <?php printf(
                '<label for="%s">%s</label>',
                esc_attr( $this->get_field_id( 'source' ) ),
                esc_html__( 'Source', 'streamtube-core')

            );?>
            
            <?php printf(
                '<select class="widefat" id="%s" name="%s">',
                esc_attr( $this->get_field_id( 'source' ) ),
                esc_attr( $this->get_field_name( 'source' ) )
            );?>

                <?php foreach ( self::get_sources() as $key => $value): ?>
                    
                    <?php printf(
                        '<option %s value="%s">%s</option>',
                        selected( $key, $instance['source'], false ),
                        esc_attr( $key ),
                        esc_html( $value )
                    );?>

                <?php endforeach ?>

            </select>

        </div>

        <div class="field-control">
            <?php printf(
                '<label for="%s">%s</label>',
                esc_attr( $this->get_field_id( 'type' ) ),
                esc_html__( 'Type', 'streamtube-core')

            );?>
            
            <?php printf(
                '<select class="widefat" id="%s" name="%s">',
                esc_attr( $this->get_field_id( 'type' ) ),
                esc_attr( $this->get_field_name( 'type' ) )
            );?>

                <?php foreach ( self::get_types() as $key => $value): ?>
                    
                    <?php printf(
                        '<option %s value="%s">%s</option>',
                        selected( $key, $instance['type'], false ),
                        esc_attr( $key ),
                        esc_html( $value )
                    );?>

                <?php endforeach ?>

            </select>

        </div>

        <div class="field-control">
            <?php printf(
                '<input type="checkbox" class="widefat" id="%s" name="%s" %s/>',
                esc_attr( $this->get_field_id( 'last_active' ) ),
                esc_attr( $this->get_field_name( 'last_active' ) ),
                checked( 'on', $instance['last_active'], false )
            );?>
            <?php printf(
                '<label for="%s">%s</label>',
                esc_attr( $this->get_field_id( 'last_active' ) ),
                esc_html__( 'Display Last Active', 'streamtube-core')
            );?>            
        </div>           

        <?php if( bp_is_active( 'friends' ) ): ?>

            <div class="field-control">
                <?php printf(
                    '<input type="checkbox" class="widefat" id="%s" name="%s" %s/>',
                    esc_attr( $this->get_field_id( 'friend_count' ) ),
                    esc_attr( $this->get_field_name( 'friend_count' ) ),
                    checked( 'on', $instance['friend_count'], false )

                );?>
                <?php printf(
                    '<label for="%s">%s</label>',
                    esc_attr( $this->get_field_id( 'friend_count' ) ),
                    esc_html__( 'Display Friends Count', 'streamtube-core')
                );?>            
            </div>            
        <?php endif;?>

        <?php if( function_exists( 'bp_follow_init' ) ): ?> 
            <div class="field-control">
                <?php printf(
                    '<input type="checkbox" class="widefat" id="%s" name="%s" %s/>',
                    esc_attr( $this->get_field_id( 'follower_count' ) ),
                    esc_attr( $this->get_field_name( 'follower_count' ) ),
                    checked( 'on', $instance['follower_count'], false )

                );?>
                <?php printf(
                    '<label for="%s">%s</label>',
                    esc_attr( $this->get_field_id( 'follower_count' ) ),
                    esc_html__( 'Display Follower Count', 'streamtube-core')
                );?>            
            </div> 
        <?php endif;?>        
        <?php
    }     
}