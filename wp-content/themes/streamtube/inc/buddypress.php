<?php
/**
 *
 * buddyPress plugin compatiblity file
 *
 * @link       https://1.envato.market/mgXE4y
 * @since      1.0.0
 *
 * @package    WordPress
 * @subpackage StreamTube
 * @author     phpface <nttoanbrvt@gmail.com>
 */
if( ! defined( 'ABSPATH' ) ){
	exit;
}

/**
 *
 * Load buddypress style
 * 
 * @since 1.0.0
 */
function streamtube_bp_enqueue_scripts(){
    wp_enqueue_style( 
        'streamtube-buddypress', 
        get_theme_file_uri( '/buddypress.css' ), 
        array( 'streamtube-style' ), 
        filemtime( get_theme_file_path( '/buddypress.css' ) )
    );
}
add_action( 'wp_enqueue_scripts', 'streamtube_bp_enqueue_scripts' );

/**
 *
 * Get buddypress sidebar
 * 
 * @return boolean|string
 */
function streamtube_bp_get_sidebar(){

    $component  = bp_current_component();

    $sidebar    = is_active_sidebar( 'buddypress' ) ? 'buddypress' : false;

    if( in_array( $component, array( 'members', 'groups' ) ) ){
        $sidebar = false;
    }

    return apply_filters( 
        'streamtube/buddypress/sidebar', 
        $sidebar,
        $component
    );
}

/**
 *
 * Output the given user activity subnav
 * 
 */
function streamtube_bp_user_subnav( $user_id = 0, $component = 'activity' ){

    $bp = buddypress();

    global $wp_query;

    if( ! $user_id ){
        $user_id = get_queried_object_id();
    }

    $user_base_url = get_author_posts_url( $user_id );

    $output = $li = '';

    $sub_nav = $bp->$component->sub_nav;

    $component = ( $component == 'members_invitations' ) ? 'invitations' : $component;

    if( in_array( $component , array( 'messages', 'notifications', 'invitations', 'members_invitations' )) ){
        $uri = explode( '/', $wp_query->query['dashboard'] );

        switch ( $component ) {
            case 'messages':
                $selected_item = count( $uri ) == 2 ? $uri[1] : 'inbox';
            break;
            
            default:
                $selected_item = count( $uri ) == 2 ? $uri[1] : 'unread';
            break;
        }

        $user_base_url = trailingslashit( $user_base_url ) . 'dashboard';
    }
    else{
        $selected_item = array_key_exists( $component, $wp_query->query ) ? $wp_query->query[$component] : 'just-me';

        if( ! $selected_item ){
            $selected_item = 'just-me';
        }
    }

    for ( $i=0; $i < count( $sub_nav ); $i++) {

        if( is_string( $sub_nav[$i]['slug'] ) ){

            if( $sub_nav[$i]['slug'] == 'view' ){
                continue;    
            }

            if( $sub_nav[$i]['slug'] == 'notices' && ! bp_current_user_can( 'bp_moderate' )  ){
                continue;    
            }            
        }

        $css_id = sanitize_html_class( $sub_nav[$i]['name'] );

        if( array_key_exists( 'item_css_id', $sub_nav[$i] ) ){
            $css_id = $sub_nav[$i]['item_css_id'];
        }

        if ( $sub_nav[$i]['slug'] === $selected_item ) {
            $selected = 'current selected';
        } else {
            $selected = '';
        }


        $li = sprintf(
            '<li id="%1$s" class="nav-item nav-%1$s %2$s"><a href="%3$s/%4$s/%5$s">%6$s</a></li>',
            esc_attr( $css_id ),
            $selected,
            esc_url( untrailingslashit( $user_base_url ) ),
            $component,
            $sub_nav[$i]['slug'],
            $sub_nav[$i]['name']
        );

        /**
         *
         * Filter li
         * 
         */
        $li = apply_filters( "streamtube/bp/user_{$component}_subnav", $li, $sub_nav[$i], $user_id );        

        /**
         *
         * Filter li
         * 
         */
        $li = apply_filters( "streamtube/bp/user_subnav", $li, $sub_nav[$i], $component, $user_id );

        $output .= $li;
    }   

    printf( '%s', $output );
}

/**
 * Output the Members directory search form.
 *
 * @since 1.0.0
 */
function streamtube_bp_directory_members_search_form() {

    $query_arg = bp_core_get_component_search_query_arg( 'members' );

    if ( ! empty( $_REQUEST[ $query_arg ] ) ) {
        $search_value = stripslashes( $_REQUEST[ $query_arg ] );
    } else {
        $search_value = bp_get_search_default_text( 'members' );
    }

    $form = '<form action="" method="get" id="search-members-form">';
        $form .= '<div class="input-group">';
        $form .= '<input class="form-control form-control-sm px-3 bg-transparent border-0 border-bottom shadow-none" type="text" name="' . esc_attr( $query_arg ) . '" id="members_search" placeholder="'. esc_attr( $search_value ) .'" />';
        $form .= '<button class="btn btn-sm bg-transparent text-muted rounded-1 p-1 shadow-none" type="submit" id="members_search_submit" name="members_search_submit"><span class="btn__icon icon-search"></span></button>';
        $form .= '</div>';
    $form .= '</form>';

    /**
     * Filters the Members component search form.
     *
     * @since 1.9.0
     *
     * @param string $search_form_html HTML markup for the member search form.
     */
    echo apply_filters( 'bp_directory_members_search_form', $form );
}

/**
 *
 * Member class
 * 
 */
function streamtube_bp_directory_members_classes(){
    $classes = array( 
        'row', 
        'row-cols-1', 
        'row-cols-sm-1', 
        'row-cols-md-2', 
        'row-cols-lg-2', 
        'row-cols-xl-4', 
        'row-cols-xxl-4' 
    );

    /**
     *
     * Filter classes
     * 
     */
    $classes = apply_filters( 'streamtube_bp_the_member_classes', $classes );

    printf( 'class="%s"', esc_attr( join( ' ', array_unique( $classes ) ) ) );
}

/**
 *
 * Get notification query args
 * 
 * @return array
 */
function streamtube_bp_get_notifications_query_args(){
    return apply_filters( 'streamtube_bp_get_notifications_query_args', array(
        'user_id'   =>  get_current_user_id(),
        'is_new'    =>  true,
        'max'       =>  20
    ) );
}

/**
 *
 * Generate notification list item class
 * 
 * @return string
 */
function streamtube_bp_the_notification_classes(){
    $notifications = buddypress()->notifications->query_loop->notification;

    $classes = array( 'list-item', 'bg-white', 'border-bottom' );

    $classes = array_merge( $classes, array(
        'notification-'. $notifications->id,
        'notification-' . $notifications->component_name,
        'notification-' . $notifications->component_action
    ) );

    printf(
        'class="%s"',
        esc_attr( join( " ", $classes ) )
    );
}

/**
 *
 * The notification avatar
 * 
 * @return string
 */
function streamtube_bp_the_notification_avatar( $args = array() ){

    $args = wp_parse_args( $args, array(
        'image_size'    =>  96,
        'wrap_size'     =>  'md',
        'wrap_class'    =>  ''
    ) );

    extract( $args );

    $user_id = 0;

    $notifications = buddypress()->notifications->query_loop->notification;

    switch ( $notifications->component_name ) {
        case 'activity':
        case 'messages':
        case 'post':
        case 'video':
            $user_id = $notifications->secondary_item_id;
        break;
        
        default:
            $user_id = $notifications->item_id;
        break;
    }

    if( function_exists( 'streamtube_core_get_user_avatar' ) ){
        streamtube_core_get_user_avatar( array_merge( $args, compact( 'user_id' ) ) );
    }else{
        printf(
            '<div class="user-avatar user-avatar-%s%s">%s</div>',
            esc_attr( $wrap_size ),
            user_can( $user_id, 'role_verified' ) ? ' is-verified' : '',
            get_avatar( $user_id, $image_size, null, null, array(
                'class' =>  'img-thumbnail avatar'
            ) )
        );
    }
}

/**
 *
 * The message sender avatar
 * 
 * @param  array  $args
 */
function streamtube_bp_the_thread_message_sender_avatar( $args = array() ){
    global $thread_template;

    $args = wp_parse_args( $args, array(
        'user_id'       =>  $thread_template->message->sender_id,
        'image_size'    =>  64,
        'wrap_size'     =>  'lg',
        'wrap_class'    =>  ''
    ) );

    extract( $args );    

    if( function_exists( 'streamtube_core_get_user_avatar' ) ){
        streamtube_core_get_user_avatar( array_merge( $args, compact( 'user_id' ) ) );
    }else{
        printf(
            '<div class="user-avatar user-avatar-%s%s">%s</div>',
            esc_attr( $wrap_size ),
            user_can( $user_id, 'role_verified' ) ? ' is-verified' : '',
            get_avatar( $user_id, $image_size, null, null, array(
                'class' =>  'img-thumbnail avatar'
            ) )
        );
    }    
}

/**
 *
 * Load the followers count
 * 
 * @param  object WP_User $user
 *
 * @since 1.0.0
 * 
 */
function streamtube_bp_display_user_card_followers_count( $user ){

    if( function_exists( 'bp_follow_get_the_followers_count' ) ):

        $object_id = is_object( $user ) ? $user->ID : ( is_int( $user ) ? $user : 0 );

        ?>
        <div class="member-info__item flex-fill">
            <div class="member-info__item__count">
                <?php echo number_format_i18n( bp_follow_get_the_followers_count( compact( 'object_id' ) ) ); ?>
            </div>
            <div class="member-info__item__label">
                <?php esc_html_e( 'followers', 'streamtube' ); ?>
            </div>
        </div>
        <?php

    endif;
}
add_action( 'streamtube/core/user/card/info/item', 'streamtube_bp_display_user_card_followers_count', 10, 1 );

