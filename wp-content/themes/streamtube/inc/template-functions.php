<?php
/**
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
 * Get streamtube core instance
 * 
 * @return object|false
 * 
 */
function streamtube_get_core(){
    global $streamtube;

    if( class_exists( 'Streamtube_Core' ) && $streamtube instanceof Streamtube_Core ){
        return $streamtube;
    }

    return false;
}

/**
 * Adds custom classes to the array of body classes.
 *
 * @since 1.0.0
 *
 * @param array $classes Classes for the body element.
 *
 * @return array
 */
function streamtube_filter_body_classes( $classes ) {

	$classes[] = 'd-flex flex-column h-100vh';

	// Helps detect if JS is enabled or not.
	$classes[] = 'no-js';

	// Adds `singular` to singular pages, and `hfeed` to all other pages.
	$classes[] = is_singular() ? 'singular' : 'hfeed';

	if( has_nav_menu( 'primary' ) ){
		$classes[] = 'has-primary-menu';
	}

	if( has_nav_menu( 'secondary' ) ){
		$classes[] = 'has-secondary-menu';
	}

    $classes[] = 'header-template-' . get_option( 'header_template', '2' );

    $classes[] = 'content-' . sanitize_html_class( streamtube_get_site_content_width() );

    if( get_option( 'preloader' ) ){
        $classes[] = 'has-preloader';
    }

    if( is_admin_bar_showing() && is_user_logged_in() ){
        $classes[] = 'admin-bar';
    }

    if( is_embed() ){
        $classes[] = 'is-embed';
    }

    if( function_exists( 'WC' ) ){
        $classes[] = 'woocommerce';
    }

    if( wp_is_mobile() ){
        $classes[] = 'is-mobile-device';
    }

    if( is_singular() ){

        if( null != $template = streamtube_get_custom_single_template() ){
            $classes[] = str_replace( '.php', '', basename( $template ) );    
        }

        if( ! comments_open() ){

            $classes[] = 'single-comment-closed';

            if( ! get_comments_number() ){
                $classes[] = 'single-no-comments';    
            }
        }else{
            $classes[] = 'single-comment-open';
        }
    }

    if( streamtube_is_login_page() && streamtube_is_theme_login() ){
        $classes[] = 'custom-theme-login';
    }

    $classes[] = 'streamtube';

	return array_unique( $classes );
}
add_filter( 'body_class', 'streamtube_filter_body_classes' );
add_filter( 'login_body_class', 'streamtube_filter_body_classes', 10, 2 );

/**
 *
 * Load the login header
 *
 * @since 1.0.0
 * 
 */
function streamtube_load_login_header(){
	get_template_part( 'template-parts/header/header' );
}

/**
 *
 * Load the login header
 *
 * @since 1.0.0
 * 
 */
function streamtube_load_login_footer(){
	get_template_part( 'template-parts/footer/footer' );
}

if( streamtube_is_login_page() && streamtube_is_theme_login() ){

    add_action( 'login_header', 'streamtube_load_login_header', 10 );
    add_action( 'login_footer', 'streamtube_load_login_footer', 10 );
    add_action( 'login_footer', function(){
        do_action( 'streamtube/footer/after' );
    } );

    add_action( 'login_enqueue_scripts', 'streamtube_enqueue_scripts' );

    add_action( 'init', function(){
        if( function_exists( 'elementor_theme_do_location' ) ){
            // Load elementor CSS
            add_action( 'login_enqueue_scripts', function(){

                if( file_exists( ELEMENTOR_PATH . '/assets/css/frontend.min.css' ) ){
                    wp_enqueue_style( 
                        'elementor-frontend', 
                        ELEMENTOR_URL . '/assets/css/frontend.min.css',
                        array(),
                        filemtime( ELEMENTOR_PATH . '/assets/css/frontend.min.css' )
                    );
                }
            } );
        }
    } );
}

/**
 *
 * Ask if current page is login page
 * 
 * @return true|false
 *
 * @since 1.0.0
 * 
 */
function streamtube_is_login_page(){

    $is = false;

    if ( $GLOBALS['pagenow'] === 'wp-login.php' ) {
        $is = true;
    }

    /**
     *
     * Filter the result
     *
     * @param boolean $is
     * 
     */
    return apply_filters( 'streamtube_is_login_page', $is );
}

/**
 *
 * Check if theme login is enabled
 * 
 * @return booelan
 */
function streamtube_is_theme_login(){
    $option = get_option( 'custom_theme_login', 'on' ) ? true : false;

    /**
     *
     * Filter the option
     *
     * @param boolean $option
     * 
     */
    return apply_filters( 'streamtube_is_theme_login', $option );
}

/**
 *
 * Get default theme mode: dark or light
 * 
 * @return string
 *
 * @since 1.0.0
 * 
 */
function streamtube_get_theme_mode(){
    $theme_mode = get_option( 'theme_mode', 'light' );

    /**
     *
     * Delete theme mode cookies if custom option is disabled
     * 
     */
    if( ! get_option( 'custom_theme_mode', 'on' ) ){
        unset( $_COOKIE['theme_mode'] );
    }

    if( isset( $_COOKIE['theme_mode'] ) && in_array( $_COOKIE['theme_mode'], array( 'light', 'dark' ) ) ){
        $theme_mode = $_COOKIE['theme_mode'];
    }    

    if( streamtube_is_login_page() && ! streamtube_is_theme_login() ){
        $theme_mode = '';
    }

    return $theme_mode;
}

/**
 *
 * Hide the float sidebar on WP login page
 *
 * @since 1.0.0
 * 
 */
function streatube_hide_float_sidebar_on_login(){
    if ( streamtube_is_login_page() ) {
        add_filter( 'sidebar_float', '__return_false' );   
    }
}
add_action( 'init', 'streatube_hide_float_sidebar_on_login' );

/**
 *
 * Filter post excerpt more link
 * 
 * @param  string $more
 * @return string
 *
 * @since 1.0.0
 * 
 */
function streamtube_filter_excerpt_more_link( $more ){
    return sprintf( '<div class="more-link-wrap mt-3"><a href="%1$s" class="more-link">%2$s</a></div>',
        esc_url( get_permalink( get_the_ID() ) ),
        esc_html__( 'Continue reading', 'streamtube' )
    );
}
add_filter( 'excerpt_more', 'streamtube_filter_excerpt_more_link', 10, 1 );

/**
 *
 * Filter the archive title
 * Remove taxonomy name on archive pages
 *
 * @since 1.0.0
 * 
 */
function streamtube_filter_archive_title( $title, $original_title, $prefix ){

    if( is_category() || is_tag() || is_tax( 'categories' ) || is_tax( 'video_tag' ) ){
        $title = single_term_title( '', false );
    }

    return $title;
}
add_filter( 'get_the_archive_title', 'streamtube_filter_archive_title', 10, 4 );

/**
 *
 * Filter the post password form
 * 
 * @param  string $output
 * @param  object $post
 * @return string
 *
 * @since 1.0.0
 * 
 */
function streamtube_filter_the_post_password_form( $output, $post ){

    $output = '<div class="post-password-form-wrapper">';

        $output .= sprintf(
            '<form action="%s" class="post-password-form text-center border p-4" method="post">',
            esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) )
        );

            $output .= sprintf(
                '<p>%s</p>',
                esc_html__( 'Please enter your password to unlock this content', 'streamtube' )
            );

            $output .= '<div class="input-group mx-auto">';
                $output .= '<input type="password" name="post_password" class="form-control form-control-sm">';
                $output .= sprintf(
                    '<button type="submit" class="btn btn-danger p-2">%s</button>',
                    esc_html__( 'Unlock', 'streamtube' )
                );
            $output .= '</div>';

        $output .= '</form>';

    $output .= '</div>';

    return $output;

}
add_filter( 'the_password_form', 'streamtube_filter_the_post_password_form', 10, 2 );

/**
 *
 * Filter widget archive link
 *
 * Add span tag for the count
 * 
 * @param  string $links
 * @return string
 *
 * @since 1.0.0
 * 
 */
function sreamtube_filter_get_archives_link( $links ) {
    $links = str_replace( '</a>&nbsp;(', '</a><span class="li-post-count">(', $links );
    $links = str_replace( ')', ')</span>', $links );
    return $links;
}
add_filter('get_archives_link', 'sreamtube_filter_get_archives_link', 10, 1 );

/**
 *
 * Filter widget category link
 *
 * Add span tag for the count
 * 
 * @param  string $links
 * @return string
 *
 * @since 1.0.0
 * 
 */
function sreamtube_filter_wp_list_categories( $links ) {
    $links = str_replace( '</a> (', '</a><span class="li-post-count">(', $links );
    $links = str_replace( ')', ')</span>', $links );
    return $links;
}
add_filter( 'wp_list_categories', 'sreamtube_filter_wp_list_categories', 10, 1 );

/**
 *
 * Add the preloader
 * 
 * @since 1.0.0
 * 
 */
function streamtube_add_preloader(){
    if( get_option( 'preloader' ) ){
        get_template_part( 'template-parts/preloader' );
    }
}
add_action( 'streamtube/header/before', 'streamtube_add_preloader', 1 );

/**
 *
 * Get customizer URl with url param
 *
 * @return string
 *
 * @since  1.0.0
 * 
 */
function streamtube_get_customize_url(){
	return add_query_arg( array(
		'url'	=>	 home_url( $GLOBALS['wp']->request )
	), wp_customize_url() );
}

/**
 *
 * Check if given URL is youtube
 * 
 * @param  string $url
 * @return true|false
 *
 * @since  1.0.0
 * 
 */
function streamtube_get_youtube_url( $url ){
    if( class_exists( 'Streamtube_Core_oEmbed' ) && method_exists( 'Streamtube_Core_oEmbed' , 'get_youtube_url' ) ){
        return Streamtube_Core_oEmbed::get_youtube_url( $url );
    }
    return false;
}

/**
 *
 * Check if given source is playable
 * 
 * @param  string $url
 * @return string mimetype or WP_Error
 *
 * @since 1.1
 * 
 */
function streamtube_get_external_source_mimetype( $url = '' ){

    $mimetype     = '';

    if( ! wp_http_validate_url( $url ) ){
        false;
    }

    $headers = wp_get_http_headers( $url );

    if( ! $headers ){
        return false;      
    }

    $mimetype = $headers['content-type'];

    $content_types = array(
        'application/x-mpegURL',
        'application/vnd.apple.mpegurl',
        'audio/x-mpegurl'
    );

    /**
     *
     * Filter supported content types
     * 
     * @var array
     */
    $content_types = apply_filters( 
        'streamtube_get_external_source_mimetype/content_types', 
        $content_types 
    );

    if( in_array( $mimetype , $content_types ) ){
        return apply_filters( 'streamtube_get_external_source_mimetype', 'application/x-mpegURL', $url );
    }

    $filetype = explode( '/', $mimetype );

    if( ! in_array( $filetype[0] , array( 'video', 'audio' ) ) ){
        return false;
    }    

    if( $filetype[0] == 'video' && ! in_array( strtolower( $filetype[1] ) , wp_get_video_extensions() ) ){
        return false;
    }

    if( $filetype[0] == 'audio' && ! in_array( strtolower( $filetype[1] ) , wp_get_audio_extensions() ) ){
        return false;
    }

    return apply_filters( 'streamtube_get_external_source_mimetype', $mimetype, $url );
}

/**
 *
 * Convert seconds to video length
 * 
 * @param  int|string $seconds
 * @return string
 *
 * @since 1.0.0
 * 
 */
function streamtube_seconds_to_length( $seconds ){

    $length = '';

    $maybe_string = explode( ':', $seconds );

    if( is_array( $maybe_string ) && count( $maybe_string ) > 1 ){
        return apply_filters( 'streamtube_seconds_to_length', join( ':', $maybe_string ), $seconds );
    }

    $seconds = (int)$seconds;

    if( $seconds > 0 ){
        if( $seconds >= 3600 ){
            $length = gmdate( "H:i:s", $seconds%86400);
        }else{
            $length = gmdate( "i:s", $seconds%86400);
        }
    }

    /**
     *
     * Filter the length string
     * 
     */
    return apply_filters( 'streamtube_seconds_to_length', $length, $seconds );
}

/**
 *
 * Get header template
 *
 * @return string
 * 
 * @since 1.0.0
 */
function streamtube_get_header_template(){
    $template = get_option( 'header_template', '1' );

    /**
     *
     * Filter and return the template
     *
     * @since 1.0.0
     * 
     */
    return apply_filters( 'streamtube_get_header_template', $template );
}

/**
 *
 * Get container classes
 * 
 * @param  string $class
 * @return array
 *
 * @since 1.0.0
 * 
 */
function streamtube_get_container_classes( $class = '' ){
    $classes = array();

    if( $class == 'container-wide' ){
        $classes[] = 'container';
        $classes[] = $class;
    }
    else{
        $classes[] = $class;
    }

    return array_unique( $classes );
}

/**
 *
 * Get header container classes
 * 
 * @return string
 *
 * @since 1.0.0
 * 
 */
function streamtube_get_container_header_classes(){
    return join( ' ', array_merge(
        streamtube_get_container_classes( streamtube_get_site_content_width() ),
        array(
            'container-header'
        )
    ) );
}

/**
 *
 * Get footer container classes
 * 
 * @return string
 *
 * @since 1.0.0
 * 
 */
function streamtube_get_container_footer_classes(){
    return join( ' ', array_merge(
        streamtube_get_container_classes( streamtube_get_footer_content_width() ),
        array(
            'container-footer'
        )
    ) );
}

/**
 *
 * Get site content width
 * 
 * @return string
 *
 * @since 1.0.0
 * 
 */
function streamtube_get_site_content_width(){

    $content_width = get_option( 'site_content_width', 'container' );

    if( isset( $GLOBALS['wp_query']->query_vars['dashboard'] ) ){
        $content_width = 'container-fluid';
    }

    /**
     *
     * Filter site content width
     *
     * @since 1.00
     * 
     */
    return apply_filters( 'streamtube_get_site_content_width', $content_width );
}

/**
 *
 * Get single content width
 * 
 * @return string
 *
 * @since 1.0.0
 * 
 */
function streamtube_get_single_content_width(){

    $content_width = get_option( 'single_video_content_width', 'container-fluid' );

    $site_content_width = streamtube_get_site_content_width();

    if( in_array( $site_content_width, array( 'container', 'container-wide' ) ) ){
         $content_width = $site_content_width;
    }

    /**
     *
     * Filter site content width
     *
     * @since 1.00
     * 
     */
    return apply_filters( 'streamtube_get_single_content_width', $content_width );
}

/**
 *
 * Get container single classes
 * 
 * @return string
 *
 * @since 1.0.0
 * 
 */
function streamtube_get_container_single_classes(){
    return join( ' ', streamtube_get_container_classes( streamtube_get_single_content_width() ) );
}

/**
 *
 * Get footer content width
 * 
 * @return string
 *
 * @since 1.0.0
 * 
 */
function streamtube_get_footer_content_width(){

    $content_width = get_option( 'footer_content_width', 'container' );

    $site_content_width = streamtube_get_site_content_width();

    if( in_array( $site_content_width, array( 'container', 'container-wide' ) ) ){
         $content_width = $site_content_width;
    }    

    /**
     *
     * Filter content width
     *
     * @since 1.00
     * 
     */
    return apply_filters( 'streamtube_get_footer_content_width', $content_width );
}

/**
 *
 * Get the blog template settings
 * 
 * @return array
 *
 * 
 */
function streamtube_get_blog_template_settings(){
    return apply_filters( 'streamtube_get_blog_template_settings', array(
        'post_author'       =>  'on',
        'post_date'         =>  'normal',
        'post_category'     =>  'on',
        'post_comment'      =>  'on',
        'post_views'        =>  'on',
        'thumbnail_size'    =>  get_option( 'blog_thumbnail_size', 'post-thumbnails' )
    ) );
}

/**
 *
 * Get the archive template settings
 * 
 * @return array
 *
 * @since 1.0.0
 * 
 */
function streamtube_get_archive_template_settings(){
    $options = array(
        'content_width'     =>  get_option( 'archive_content_width', 'container-fluid' ),
        'posts_per_column'  =>  get_option( 'archive_posts_per_column', 5 ),
        'col_xl'            =>  get_option( 'archive_col_xl', 4 ),
        'col_lg'            =>  get_option( 'archive_col_lg', 4 ),
        'col_md'            =>  get_option( 'archive_col_md', 2 ),
        'col_sm'            =>  get_option( 'archive_col_sm', 2 ),
        'col'               =>  get_option( 'archive_col_xs', 1 ),
        'rows_per_page'     =>  get_option( 'archive_rows_per_page', 4 ),
        'post_comment'      =>  get_option( 'archive_post_comment', 'on' ),
        'post_date'         =>  get_option( 'archive_post_date', 'normal' ),
        'author_name'       =>  get_option( 'archive_author_name', 'on' ),
        'author_avatar'     =>  get_option( 'archive_author_avatar', 'on' ),
        'pagination'        =>  get_option( 'archive_pagination', 'click' ),
        'thumbnail_size'    =>  get_option( 'archive_thumbnail_size', 'streamtube-image-medium' )
    );

    $site_content_width = streamtube_get_site_content_width();

    if( in_array( $site_content_width, array( 'container', 'container-wide' ) ) ){
        $options['content_width'] = $site_content_width;
    }

    return $options;    
}

/**
 *
 * Get the search template settings
 * 
 * @return array
 *
 * @since 1.0.0
 * 
 */
function streamtube_get_search_template_settings(){
    $options = array(
        'content_width'         =>  get_option( 'search_content_width', 'container' ),
        'layout'                =>  get_option( 'search_layout', 'list_xxl' ),
        'posts_per_column'      =>  (int)get_option( 'search_posts_per_column', 1 ),
        'col_xl'                =>  get_option( 'search_col_xl', 1 ),
        'col_lg'                =>  get_option( 'search_col_lg', 1 ),
        'col_md'                =>  get_option( 'search_col_md', 1 ),
        'col_sm'                =>  get_option( 'search_col_sm', 1 ),
        'col'                   =>  get_option( 'search_col_xs', 1 ),        
        'rows_per_page'         =>  (int)get_option( 'search_rows_per_page', get_option( 'posts_per_page' ) ),
        'post_excerpt_length'   =>  (int)get_option( 'search_post_excerpt_length', 20 ),
        'author_avatar'         =>  get_option( 'search_author_avatar', 'on' ),
        'author_name'           =>  get_option( 'search_author_name', 'on' ),
        'hide_empty_thumbnail'  =>  get_option( 'search_hide_empty_thumbnail', 'on' ),
        'pagination'            =>  get_option( 'search_pagination', 'click' ),
        'post_date'             =>  get_option( 'search_post_date', 'normal' ),
        'post_views'            =>  get_option( 'search_view_count', 'on' ),
        'post_comment'          =>  get_option( 'search_comment_count', 'on' ),
        'thumbnail_size'        =>  get_option( 'search_thumbnail_size', 'streamtube-image-medium' ),
        'thumbnail_ratio'       =>  get_option( 'search_thumbnail_ratio', get_option( 'thumbnail_ratio', '16x9' ) )
    );

    if( $options['posts_per_column'] <= 0 ){
        $options['posts_per_column'] = 1;
    }

    if( $options['rows_per_page'] <= 0 ){
        $options['rows_per_page'] = get_option( 'posts_per_page' );
    }

    $site_content_width = streamtube_get_site_content_width();

    if( in_array( $site_content_width, array( 'container', 'container-wide' ) ) ){
        $options['content_width'] = $site_content_width;
    }

    return $options;
}

/**
 *
 * Get search query value
 * 
 * @return string|null
 *
 * @since 1.1.9
 * 
 */
function streamtube_get_search_query_value(){

    global $wp_query;

    $search_query = get_search_query();

    if( streamtube_is_bbp_search() ){
        $search_query = streamtube_is_bbp_search();
    }

    return $search_query;    
}

/**
 *
 * Check if is bbpress search
 * 
 * @return string|false
 *
 * @since 1.1.9
 * 
 */
function streamtube_is_bbp_search(){

    global $wp_query;

    if( array_key_exists( 'bbp_search', $wp_query->query_vars ) ){
        return $wp_query->query_vars['bbp_search'];
    }    

    return false;
}

/**
 *
 * Check if Google Site Kit Analytics module is activated
 *
 * @return true|false
 * 
 * @since 1.0.8
 */
function streamtube_is_google_analytics_connected(){

    if( class_exists( 'Streamtube_Core_GoogleSiteKit_Analytics' ) ){
        return streamtube_core()->get()->googlesitekit->analytics->is_connected();
    }

    return false;
}

/**
 *
 * Remove comments template widget from the Single V3
 * 
 * @param  array $sidebars_widgets
 * @return array $sidebars_widgets
 *
 * @since 2.1.5
 * 
 */
function streamtube_remove_comments_template_widget( $sidebars_widgets ){
    $widgets = false;

    if( array_key_exists( 'content-bottom' , $sidebars_widgets ) ){
        $widgets = $sidebars_widgets['content-bottom'];

        if( is_array( $widgets ) ){
            for ( $i=0;  $i < count( $widgets );  $i++) { 
                if( isset( $widgets[$i] ) && strpos( $widgets[$i] , 'comments-template-widget' ) !== false ){
                    unset( $widgets[$i] );
                }
            }
        }

        $sidebars_widgets['content-bottom'] = $widgets;
    }
    return $sidebars_widgets;
}

/**
 *
 * Get page template options
 * 
 * @return array
 *
 * @since 2.2
 * 
 */
function streamtube_get_page_template_options( $post_id = 0 ){

    $fallback_default = array(
        'disable_title'                 =>  '',
        'disable_thumbnail'             =>  '',
        'header_alignment'              =>  'default',
        'header_padding'                =>  '5',
        'remove_content_box'            =>  '',
        'disable_content_padding'       =>  '',
        'disable_primary_sidebar'       =>  '',
        'disable_bottom_sidebar'        =>  '',
        'disable_comment_box'           =>  ''        
    );

    if( ! $post_id ){
        $post_id = get_the_ID();
    }

    $streamtube = streamtube_get_core();

    if( ! $streamtube ){
        return $fallback_default;
    }

    if( ! method_exists( $streamtube->get()->metabox, 'get_template_options' ) ){
        return $fallback_default;
    }

    return $streamtube->get()->metabox->get_template_options( $post_id );
}

/**
 *
 * Get main content size
 * 
 * @return int
 */
function streamtube_get_main_content_size(){
    return apply_filters( 'streamtube_main_content_size', 8 );
}

/**
 *
 * Check if has comments
 * 
 */
function streamtube_has_post_comments(){
    $has_comments       = ! comments_open() && ! get_comments_number() ? false : true;

    $template_options   = streamtube_get_page_template_options();

    if( $template_options['disable_comment_box'] ){
        $has_comments = false;
    }

    /**
     *
     * Filter $has_comments
     * 
     */
    return apply_filters( 'streamtube_has_post_comments', $has_comments );
}

/**
 *
 * Check if has sidebar primary
 * 
 */
function streamtube_has_sidebar_primary(){
    $has_sidebar = is_active_sidebar( 'sidebar-1' ) ? 'sidebar-1' : false;

    $template_options   = streamtube_get_page_template_options();

    if( $template_options['disable_primary_sidebar'] ){
        $has_sidebar = false;
    }

    /**
     *
     * Filter sidebar
     * 
     */
    $has_sidebar = apply_filters( 'streamtube/sidebar/primary', $has_sidebar );    

    /**
     *
     * Filter $has_comments
     * 
     */
    return apply_filters( 'streamtube_has_sidebar_primary', $has_sidebar );    
}

/**
 *
 * Check if has sidebar bottom
 * 
 */
function streamtube_has_sidebar_bottom(){
    $has_sidebar = is_active_sidebar( 'content-bottom' ) ? 'content-bottom' : false;

    $template_options   = streamtube_get_page_template_options();

    if( $template_options['disable_bottom_sidebar'] ){
        $has_sidebar = false;
    }

    /**
     *
     * Filter $has_comments
     * 
     */
    return apply_filters( 'streamtube_has_sidebar_bottom', $has_sidebar );        
}

/**
 *
 * Check if Ajax live search enabled
 * 
 */
function streamtube_is_ajax_live_search(){
    global $ajax_live_search;

    return $ajax_live_search;
}

/**
 *
 * Get given post type object
 * 
 */
function streamtube_get_post_type_object( $post = null ){
    if( is_null( $post ) ){
        global $post;
    }

    return get_post_type_object( $post->post_type );
}