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
update_option( 'access_token', 'xxxxxxxxxxx' );
update_option( 'envato_purchase_code_33821786', 'xxxxxxxxxxx' );
update_option( 'envato_username_33821786', 'username' );
update_option( 'envato_33821786', true);
$user_id = get_current_user_id();
set_transient( "dismiss_verify_33821786_{$user_id}", true );
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * @since 1.0.0
 *
 * @return void
 */
function streamtube_setup() {
	//remove_theme_support( 'widgets-block-editor' );
	/*
	 * Make theme available for translation.
	 */
	load_theme_textdomain( 'streamtube', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 */
	add_theme_support( 'title-tag' );

	add_image_size( 'streamtube-image-medium', 560, 315, true );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	set_post_thumbnail_size( 1568, 800 );

	register_nav_menus(
		array(
			'primary'		=>	esc_html__( 'Primary Menu', 'streamtube' )
		)
	);

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support(
		'html5',
		array(
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
			'navigation-widgets',
		)
	);

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */

	add_theme_support(
		'custom-logo',
		array(
			'flex-width'           => true,
			'flex-height'          => true,
			'unlink-homepage-logo' => false,
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	// Add support for Block Styles.
	add_theme_support( 'wp-block-styles' );

	// Add support for full and wide align images.
	add_theme_support( 'align-wide' );

	// Custom background color.
	add_theme_support(
		'custom-background',
		array(
			'default-color' => '#f6f6f6',
		)
	);

	// Add support for responsive embedded content.
	add_theme_support( 'responsive-embeds' );
	
	add_theme_support( 'woocommerce' );
}
add_action( 'after_setup_theme', 'streamtube_setup' );

/**
 * Register widget area.
 *
 * @since 1.0.0
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 *
 * @return void
 */
function streamtube_widgets_init() {

	register_sidebar(
		array(
			'name'          => esc_html__( 'Primary', 'streamtube' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here to appear in primary sidebar.', 'streamtube' ),
			'before_widget' => '<div id="%1$s" class="widget widget-primary shadow-sm %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="widget-title-wrap"><h2 class="widget-title d-flex align-items-center">',
			'after_title'   => '</h2></div>'
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Secondary', 'streamtube' ),
			'id'            => 'secondary',
			'description'   => esc_html__( 'Add widgets here to appear in secondary sidebar.', 'streamtube' ),
			'before_widget' => '<div id="%1$s" class="widget widget-secondary %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="widget-title-wrap"><h2 class="widget-title d-flex align-items-center">',
			'after_title'   => '</h2></div>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Featured', 'streamtube' ),
			'id'            => 'featured',
			'description'   => esc_html__( 'Add widgets here to appear in featured sidebar.', 'streamtube' ),
			'before_widget' => '<div id="%1$s" class="widget widget-featured %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="widget-title-wrap"><h2 class="widget-title d-flex align-items-center">',
			'after_title'   => '</h2></div>'
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Content Bottom', 'streamtube' ),
			'id'            => 'content-bottom',
			'description'   => esc_html__( 'Add widgets here to appear in content bottom sidebar.', 'streamtube' ),
			'before_widget' => '<div id="%1$s" class="widget widget-content-bottom %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="widget-title-wrap"><h2 class="widget-title d-flex align-items-center">',
			'after_title'   => '</h2></div>'
		)
	);		

	$footer_widgets = absint( get_option( 'footer_widgets', 4 ) );

	if( $footer_widgets > 0 ){

		for ( $i=1; $i <= $footer_widgets ; $i++) { 
			register_sidebar(
				array(
					'name'          => sprintf(
						esc_html__( 'Footer %s', 'streamtube' ),
						$i
					),
					'id'            => 'footer-' . $i,
					'description'   => esc_html__( 'Add widgets here to appear in footer sidebar.', 'streamtube' ),
					'before_widget' => '<div id="%1$s" class="widget widget-footer %2$s">',
					'after_widget'  => '</div>',
					'before_title'  => '<div class="widget-title-wrap"><h2 class="widget-title d-flex align-items-center">',
					'after_title'   => '</h2></div>',
				)
			);	
		}

	}
}
add_action( 'widgets_init', 'streamtube_widgets_init' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @since 1.0.0
 *
 * @global int $content_width Content width.
 *
 * @return void
 */
function streamtube_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'streamtube_content_width', 750 );
}
add_action( 'after_setup_theme', 'streamtube_content_width', 0 );

/**
 * Enqueue scripts and styles.
 *
 * @since 1.0.0
 *
 * @return void
 */
function streamtube_enqueue_scripts() {

	wp_enqueue_script(
		'bootstrap',
		get_theme_file_uri( '/assets/js/bootstrap.bundle.min.js' ),
		array( 'jquery' ),
		filemtime( get_theme_file_path( '/assets/js/bootstrap.bundle.min.js' ) ),
		true
	);

	wp_register_script(
		'bootstrap-tagsinput',
		get_theme_file_uri( '/assets/js/bootstrap-tagsinput.min.js' ),
		array( 'jquery', 'bootstrap' ),
		filemtime( get_theme_file_path( '/assets/js/bootstrap-tagsinput.min.js' ) ),
		true
	);

	wp_add_inline_script( 'bootstrap-tagsinput', 'jQuery("input[data-role=tagsinput]").each(function(){var a=jQuery(this).attr("data-max-tags");void 0===a&&(a=0),jQuery(this).tagsinput({maxTags:a})});' );

	// Threaded comment reply styles.
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	wp_enqueue_script(
		'headroom',
		get_theme_file_uri( '/assets/js/headroom.min.js' ),
		array( 'jquery' ),
		filemtime( get_theme_file_path( '/assets/js/headroom.min.js' ) ),
		true
	);	

	wp_enqueue_script(
		'streamtube-scripts',
		get_theme_file_uri( '/assets/js/scripts.js' ),
		array( 'jquery' ),
		filemtime( get_theme_file_path( '/assets/js/scripts.js' ) ),
		true
	);

	if( streamtube_is_login_page() ){
		$theme = streamtube_get_theme_mode();
		wp_add_inline_script( 'jquery', "jQuery( 'html' ).attr( 'data-theme', '{$theme}' )");
	}

	if( get_option( 'google_fonts' ) ){
		wp_enqueue_style( 
			'google-font-lato-poppins', 
			get_theme_file_uri( '/assets/css/google-fonts.css' ), 
			array(), 
			filemtime( get_theme_file_path( '/assets/css/google-fonts.css' ) )
		);
	}
	else{
		wp_enqueue_style( 
			'google-font-lato-poppins', 
			streamtube_google_fonts_url() 
		);
	}
	
	wp_enqueue_style( 
		'bootstrap', 
		get_theme_file_uri( '/assets/css/bootstrap.min.css' ), 
		array(), 
		filemtime( get_theme_file_path( '/assets/css/bootstrap.min.css' ) )
	);

	wp_enqueue_style( 
		'streamtube-fontello', 
		get_theme_file_uri( '/assets/css/fontello.css' ), 
		array(), 
		filemtime( get_theme_file_path( '/assets/css/fontello.css' ) )
	);

	wp_register_style( 
		'bootstrap-tagsinput', 
		get_theme_file_uri( '/assets/css/bootstrap-tagsinput.css' ), 
		array( 'bootstrap' ), 
		filemtime( get_theme_file_path( '/assets/css/bootstrap-tagsinput.css' ) )
	);

	wp_enqueue_style(
		'streamtube-style',
		get_template_directory_uri() . '/style.css',
		array( 'bootstrap' ),
		filemtime( get_template_directory() . '/style.css' )
	);

	if( 0 < $root_size = (int)get_option( 'root_size', '15' ) ){
		wp_add_inline_style( 'streamtube-style', sprintf(
			':root{font-size:%spx}',
			$root_size
		) );
	}

	if( is_rtl() ){
		wp_enqueue_style(
			'streamtube-style-rtl',
			get_template_directory_uri() . '/style-rtl.css',
			array( 'streamtube-style' ),
			filemtime( get_template_directory() . '/style-rtl.css' )
		);		
	}

}
add_action( 'wp_enqueue_scripts', 'streamtube_enqueue_scripts' );

/**
 * Enqueue admin scripts and styles.
 *
 * @since 1.0.5
 *
 */
function streamtube_enqueue_admin_scripts(){

	wp_enqueue_style(
		'streamtube-admin-style',
		get_template_directory_uri() . '/assets/css/admin.css',
		array(),
		filemtime( get_template_directory() . '/assets/css/admin.css' )
	);

	wp_enqueue_script(
		'streamtube-admin-scripts',
		get_theme_file_uri( '/assets/js/admin.js' ),
		array( 'jquery' ),
		filemtime( get_theme_file_path( '/assets/js/admin.js' ) ),
		true
	);

	wp_localize_script( 'streamtube-admin-scripts', 'streamtube_admin', array(
		'ajaxurl'	=>	admin_url( 'admin-ajax.php' )
	) );
}
add_action( 'admin_enqueue_scripts', 'streamtube_enqueue_admin_scripts' );

/**
 *
 * Google Fonts
 * 
 * @since 1.0.0
 */
function streamtube_google_fonts_url() {
	$fonts_url = '';
	$fonts     = array();

	$fonts[]	=	'Lato:ital,wght@0,100;0,300;0,400;0,700;1,100;1,300;1,400';
	$fonts[]	=	'Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;1,100;1,200;1,300;1,400;1,500;1,600';

	$fonts	=	apply_filters( 'streamtube_google_fonts_url' , $fonts );

	if ( $fonts ) {
		$fonts_url = add_query_arg( array(
			'family' 	=> urlencode( implode( '|', $fonts ) ),
			'display'	=>	'swap'
		), '//fonts.googleapis.com/css' );
	}

	return $fonts_url;
}

require get_template_directory() . '/inc/class-wp-bootstrap-navwalker.php';

require get_template_directory() . '/inc/class-theme-license.php';

require get_template_directory() . '/inc/class-tgm-plugin-activation.php';

require get_template_directory() . '/inc/register-plugins.php';

require get_template_directory() . '/inc/option-functions.php';

require get_template_directory() . '/inc/template-functions.php';

require get_template_directory() . '/inc/post-functions.php';

require get_template_directory() . '/inc/comment-functions.php';

require get_template_directory() . '/inc/template-tags.php';

require get_template_directory() . '/inc/elementor-pro.php';	

if( function_exists( 'run_wp_user_follow' ) ){
	require get_template_directory() . '/inc/wp-user-follow.php';	
}

if( function_exists( 'mycred_core' ) ){
	require get_template_directory() . '/inc/mycred.php';	
}

if( ( function_exists( '_WPMI' ) || class_exists( 'QuadLayers\WPMI\Frontend' ) ) 
	|| class_exists( 'QuadLayers\WPMI\Plugin' ) ){
	require get_template_directory() . '/inc/wp-menu-icons.php';
}

if( class_exists( 'OCDI_Plugin' ) ){
	require get_template_directory() . '/inc/sample-data.php';
}

if( function_exists( 'aioseo' ) ){
	require get_template_directory() . '/inc/aioseo.php';
}

if( function_exists( 'wpseo_init' ) ){
	require get_template_directory() . '/inc/yoast.php';
}

if( class_exists( 'WP_Easy_Review' ) ){
	require get_template_directory() . '/inc/wp-easy-review.php';	
}

if( class_exists( 'BuddyPress' ) ){
	require get_template_directory() . '/inc/buddypress.php';		
}

if( function_exists( 'bbpress' ) ){
	require get_template_directory() . '/inc/bbpress.php';	
}

if( function_exists( 'WC' ) ){
	require get_template_directory() . '/inc/woocommerce.php';		
}

if( function_exists( 'dokan' ) ){
	require get_template_directory() . '/inc/dokan.php';		
}