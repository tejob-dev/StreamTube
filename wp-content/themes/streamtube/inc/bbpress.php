<?php
/**
 *
 * bbPress plugin compatiblity file
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
 * Load bbpress style
 * 
 * @since 1.0.0
 */
function streamtube_bbp_enqueue_scripts(){
    wp_enqueue_style( 
        'streamtube-bbpress', 
        get_theme_file_uri( '/bbpress/bbpress.css' ), 
        array( 'bbp-default' ), 
        filemtime( get_theme_file_path( '/bbpress/bbpress.css' ) )
    );
}
add_action( 'wp_enqueue_scripts', 'streamtube_bbp_enqueue_scripts' );

/**
 *
 * Register bbPress sidebar
 * 
 * @since 1.0.0
 * 
 */
function streamtube_ppb_widgets_init(){
    register_sidebar(
        array(
            'name'          => esc_html__( 'bbPress', 'streamtube' ),
            'id'            => 'bbpress',
            'description'   => esc_html__( 'Add widgets here to appear in bbPress primary sidebar.', 'streamtube' ),
            'before_widget' => '<div id="%1$s" class="widget widget-primary widget-bbpress %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<div class="widget-title-wrap"><h2 class="widget-title d-flex align-items-center">',
            'after_title'   => '</h2></div>',
        )
    );
}
add_action( 'widgets_init', 'streamtube_ppb_widgets_init' );

/**
 *
 * Load topic excerpt in the topic loop
 * 
 * @since 1.0.0
 * 
 */
function streamtube_bbp_load_topic_excerpt(){
    if( ! bbp_is_search() ){
        bbp_get_template_part( 'loop-topic', 'excerpt' );
    }
}
add_action( 'bbp_theme_after_topic_title',  'streamtube_bbp_load_topic_excerpt' );

/**
 *
 * Filter get user ID
 * 
 * @param  int $bbp_user_id
 * @return int
 *
 * @since 1.0.0
 * 
 */
function streamtube_bbp_filter_get_user_id( $bbp_user_id ){

    if( is_author() ){
        $bbp_user_id = get_queried_object_id();
    }

    return $bbp_user_id;
}
add_filter( 'bbp_get_user_id', 'streamtube_bbp_filter_get_user_id', 10, 1 );

/**
 *
 * Filter check single user profile query
 * return true if current page is author page, otherwise return $check
 * 
 * @param  boolean $check
 * @return $check
 *
 * @since 1.0.0
 * 
 */
function streamtube_bbp_filter_is_single_user_profile( $check ){

    if( is_author() ){
        $check = true;
    }

    return $check;
}
add_filter( 'bbp_is_single_user_profile', 'streamtube_bbp_filter_is_single_user_profile', 10, 1 );

/**
 *
 * Filter default bbPress user profile url
 * Redirect to default WP author page
 * 
 * @param  string $url
 * @param  int $user_id
 * @param  string $user_nicename
 * @return string
 */
function streamtube_bbp_filter_user_profile_url( $url, $user_id, $user_nicename ){
    return get_author_posts_url( $user_id, $user_nicename );
}
add_filter( 'bbp_get_user_profile_url', 'streamtube_bbp_filter_user_profile_url', 10, 3 );

/**
 *
 * Filter is single user topics
 *
 * @since 1.0.0
 * 
 */
function streamtube_bbp_filter_is_single_user_topics( $check ){
    global $wp_query;

    if( isset( $wp_query->query_vars['forums'] ) && ( $wp_query->query_vars['forums'] == 'topics' || $wp_query->query_vars['forums'] == '' ) ){
        $check = true;
    }

    return $check;
}
add_filter( 'bbp_is_single_user_topics', 'streamtube_bbp_filter_is_single_user_topics', 10, 1 );

/**
 *
 * Filter is single user replies
 *
 * @since 1.0.0
 * 
 */
function streamtube_bbp_filter_is_single_user_replies( $check ){
    global $wp_query;

    if( isset( $wp_query->query_vars['forums'] ) && $wp_query->query_vars['forums'] == 'replies' ){
        $check = true;
    }

    return $check;
}
add_filter( 'bbp_is_single_user_replies', 'streamtube_bbp_filter_is_single_user_replies', 10, 1 );

/**
 *
 * Filter is single user engagements
 *
 * @since 1.0.0
 * 
 */
function streamtube_bbp_filter_is_single_user_engagements( $check ){
    global $wp_query;

    if( isset( $wp_query->query_vars['forums'] ) && $wp_query->query_vars['forums'] == 'engagements' ){
        $check = true;
    }

    return $check;
}
add_filter( 'bbp_is_single_user_engagements', 'streamtube_bbp_filter_is_single_user_engagements', 10, 1 );

/**
 *
 * Filter is single user favorites
 *
 * @since 1.0.0
 * 
 */
function streamtube_bbp_filter_is_favorites( $check ){
    global $wp_query;

    if( isset( $wp_query->query_vars['forums'] ) && $wp_query->query_vars['forums'] == 'favorites' ){
        $check = true;
    }

    return $check;
}
add_filter( 'bbp_is_favorites', 'streamtube_bbp_filter_is_favorites', 10, 1 );

/**
 *
 * Filter is single user subscriptions
 *
 * @since 1.0.0
 * 
 */
function streamtube_bbp_filter_is_subscriptions( $check ){
    global $wp_query;

    if( isset( $wp_query->query_vars['forums'] ) && $wp_query->query_vars['forums'] == 'subscriptions' ){
        $check = true;
    }

    return $check;
}
add_filter( 'bbp_is_subscriptions', 'streamtube_bbp_filter_is_subscriptions', 10, 1 );

/**
 *
 * Filter get user topics created URL
 * 
 * @param  string $url
 * @param  int $user_id
 * @return string
 *
 * @since 1.0.0
 * 
 */
function streamtube_bbp_filter_get_user_topics_created_url( $url, $user_id ){
    return bbp_get_user_profile_url() . 'forums/topics';
}
add_filter( 'bbp_get_user_topics_created_url', 'streamtube_bbp_filter_get_user_topics_created_url', 10, 2 );

/**
 *
 * Filter get user replies URL
 * 
 * @param  string $url
 * @param  int $user_id
 * @return string
 *
 * @since 1.0.0
 * 
 */
function streamtube_bbp_filter_get_user_replies_created_url( $url, $user_id ){
    return bbp_get_user_profile_url() . 'forums/replies';
}
add_filter( 'bbp_get_user_replies_created_url', 'streamtube_bbp_filter_get_user_replies_created_url', 10, 2 );

/**
 *
 * Filter get user engagements URL
 * 
 * @param  string $url
 * @param  int $user_id
 * @return string
 *
 * @since 1.0.0
 * 
 */
function streamtube_bbp_filter_get_user_engagements_url( $url, $user_id ){
    return bbp_get_user_profile_url() . 'forums/engagements';
}
add_filter( 'bbp_get_user_engagements_url', 'streamtube_bbp_filter_get_user_engagements_url', 10, 2 );

/**
 *
 * Filter get user favorites URL
 * 
 * @param  string $url
 * @param  int $user_id
 * @return string
 *
 * @since 1.0.0
 * 
 */
function streamtube_bbp_filter_get_favorites_permalink( $url, $user_id ){
    return bbp_get_user_profile_url() . 'forums/favorites';
}
add_filter( 'bbp_get_favorites_permalink', 'streamtube_bbp_filter_get_favorites_permalink', 10, 2 );

/**
 *
 * Filter get user favorites URL
 * 
 * @param  string $url
 * @param  int $user_id
 * @return string
 *
 * @since 1.0.0
 * 
 */
function streamtube_bbp_filter_get_subscriptions_permalink( $url, $user_id ){
    return bbp_get_user_profile_url() . 'forums/subscriptions';
}
add_filter( 'bbp_get_subscriptions_permalink', 'streamtube_bbp_filter_get_subscriptions_permalink', 10, 2 );


