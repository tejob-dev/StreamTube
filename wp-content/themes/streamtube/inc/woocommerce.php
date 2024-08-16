<?php
/**
 *
 * Woocommerce plugin compatibility file
 * 
 */
if( ! defined( 'ABSPATH' ) ){
	exit;
}

/**
 * Register widget area.
 *
 * @since 1.0.0
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 *
 * @return void
 */

if( ! get_option( 'woocommerce_enable', 'on' ) ){
    return;
} 

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * @since 1.0.0
 *
 * @return void
 */
function streamtube_woo_setup() {
    add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'streamtube_woo_setup' );

function streamtube_woo_widgets_init() {

    register_sidebar(
        array(
            'name'          => esc_html__( 'Woocommerce Primary', 'streamtube' ),
            'id'            => 'woocommerce',
            'description'   => esc_html__( 'Add widgets here to appear in Woocommerce primary sidebar.', 'streamtube' ),
            'before_widget' => '<div id="%1$s" class="widget widget-primary widget-woocommerce shadow-sm %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<div class="widget-title-wrap"><h2 class="widget-title d-flex align-items-center">',
            'after_title'   => '</h2></div>',
        )
    );
}
add_action( 'widgets_init', 'streamtube_woo_widgets_init' );

// Hide default shop page title
add_filter( 'woocommerce_show_page_title', '__return_null' );

/**
 *
 * Filter my-account endpoints
 *
 * @since 1.0.5
 * 
 */
function streamtube_woo_filer_account_endpoint_url( $url, $endpoint, $value = '', $permalink = '' ){

    $myaccount_endpoints = array(
        'orders',
        'view-order',
        'downloads',
        'edit-address',
        'edit-account'
    );

    if( in_array( $endpoint, $myaccount_endpoints ) && is_user_logged_in() ){
        $url = trailingslashit( get_author_posts_url( get_current_user_id() ) ) . 'dashboard/shop/' . $endpoint;

        if( $value ){
            $url= trailingslashit( $url ) . $value;
        }
    }

    return $url;

}
add_filter( 'woocommerce_get_endpoint_url', 'streamtube_woo_filer_account_endpoint_url', 10, 4 );

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
function streamtube_woo_get_container_classes(){
    $classes = streamtube_get_container_classes( get_option( 'woocommerce_content_width', 'container' ) );

    /**
     *
     * Filter container classes
     *
     * @param array $classes
     * 
     */
    return apply_filters( 'streamtube_woo_get_container_classes', array_unique( $classes ) );
}

/**
 *
 * Check if woocommerce sidebar is active
 * 
 * @return string|false
 */
function streamtube_woo_has_sidebar(){
    $retvar = is_active_sidebar( 'woocommerce' ) ? 'woocommerce' : false;

    /**
     *
     * Filter the retvar
     *
     * @param string|false
     * 
     */
    return apply_filters( 'streamtube_woo_has_sidebar', $retvar );
}

/**
 *
 * Get custom single template
 * 
 * @return string
 */
function streamtube_woo_get_single_template(){
    return apply_filters( 
        'streamtube/woocommerce/single_template', 
        get_option( 'woocommerce_single_template', 'v2' ) 
    );
}

/**
 *
 * Add wrapper before login form
 * 
 */
function streamtube_woo_wrap_before_customer_login_form(){
    ?>
    <div class="woocommerce-login-wrapper">
    <?php
}
add_action( 'woocommerce_before_customer_login_form', 'streamtube_woo_wrap_before_customer_login_form', 1 );


/**
 *
 * Add wrapper after login form
 * 
 */
function streamtube_woo_wrap_after_customer_login_form(){
    ?>
    </div>
    <?php
}
add_action( 'woocommerce_after_customer_login_form', 'streamtube_woo_wrap_after_customer_login_form', 1000 );

/**
 *
 * Redirect default WC myaccount to dashboard if user is logged in
 * 
 * @since 1.0.5
 */
function streamtube_woo_redirect_myaccount_page(){
    $myaccount = (int)wc_get_page_id( 'myaccount' );
    if( $myaccount && is_page( $myaccount ) ){

        if( is_user_logged_in() ){
            wp_redirect( trailingslashit( get_author_posts_url( get_current_user_id() ) ) . 'dashboard' );
        }
        else{
            wp_redirect( wp_login_url() );
        }
        
        exit;            
    }
}
add_action( 'wp', 'streamtube_woo_redirect_myaccount_page' );

/**
 *
 * Save address
 * 
 * @since 1.0.5
 * 
 */
function streamtube_woo_update_address(){

    if ( ! isset( $_POST['address_type'] ) || ! in_array( $_POST['address_type'], array( 'billing', 'shipping' ) )  ) {
        return;
    }    

    $form = new WC_Form_Handler();

    $GLOBALS['wp']->query_vars = array_merge( (array)$GLOBALS['wp']->query_vars, array(
        'edit-address'  =>  $_POST['address_type']
    ) );

    $form->save_address();

}
add_action( 'init', 'streamtube_woo_update_address' );