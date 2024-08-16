<?php
/**
 * Define the Dokan functionality
 *
 *
 * @link       https://themeforest.net/user/phpface
 * @since      1.0.0
 *
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 */

/**
 * Define the analytics functionality
 *
 * @since      1.0.8
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */

if( ! defined('ABSPATH' ) ){
    exit;
}

/**
 * 
 */
class StreamTube_Core_Dokan{

    /**
     * Define the seller role
     * 
     */
    const ROLE_SELLER = 'seller';

    /**
     *
     * Holds the store slug
     * 
     * @var string
     */
    protected $store_slug = 'store';
    
    /**
     *
     * Check if Dokan is enabled
     * 
     * @return boolean
     */
    public function is_active(){
        return function_exists( 'dokan' ) && ( ! defined( 'WP_DISABLE_DOKAN_COMPAT' ) || WP_DISABLE_DOKAN_COMPAT !== true );
    }

    /**
     *
     * Get store slug
     * 
     * @return string
     */
    public function get_store_slug(){
        return apply_filters( 'streamtube/core/user/profile/store_slug', $this->store_slug );
    }

    /**
     *
     * Check if within StreamTube Dashboard
     * 
     * @return boolean
     */
    public function is_dashboard_store( $wp = null ){

        if( ! $wp || $wp === null ){
            global $wp;    
        }

        if( ! is_object( $wp ) || ! is_array( $wp->query_vars ) ){
            return false;
        }

        if( array_key_exists( 'dashboard', $wp->query_vars ) ){
            if( strpos( $wp->query_vars['dashboard'], $this->get_store_slug() ) !== false ){
                return true;
            }
        }        

        return false;
    }

    /**
     *
     * Is store settings page
     * 
     * @return boolean
     */
    public function is_settings_page(){
        global $wp;

        if( $this->is_dashboard_store( $wp ) 
            && array_key_exists( 'settings', $wp->query_vars ) 
            && $wp->query_vars['settings'] == 'store'
        ){
            return true;
        }

        return false;
    }

    /**
     *
     * Check if current user is seller
     * 
     * @return boolean
     */
    public function is_seller( $user_id = 0 ){

        $retvar = false;

        if( ! $user_id ){
            $user_id = get_current_user_id();
        }

        if( function_exists( 'dokan_is_user_seller' ) ){
            $retvar = dokan_is_user_seller( $user_id );
        }

        return apply_filters( 'streamtube/core/dokan/is_seller', $retvar, $user_id );
    }

    /**
     *
     * Check if given seller is enabled
     * 
     */
    public function is_seller_enabled( $user_id = 0 ){
        // Always return false if dokan_is_seller_enabled not found
        if( ! function_exists( 'dokan_is_seller_enabled' ) ){
            return false;
        }

        if( ! $user_id ){
            $user_id = get_current_user_id();
        }        

        $retvar = dokan_is_seller_enabled( $user_id );

        /**
         *
         * Filter the $retvar
         *
         * @param boolean $retvar
         * @param int $user_id
         * 
         */
        return apply_filters( 'streamtube/core/dokan/is_seller_enabled', $retvar, $user_id );        
    }

    /**
     *
     * Check if seller is trusted
     * 
     * @return boolean
     * 
     */
    public function is_seller_trusted( $user_id = 0 ){
        // Always return false if dokan_is_seller_trusted not found
        if( ! function_exists( 'dokan_is_seller_trusted' ) ){
            return false;
        }

        if( ! $user_id ){
            $user_id = get_current_user_id();
        }        

        $retvar = dokan_is_seller_trusted( $user_id );

        /**
         *
         * Filter the $retvar
         *
         * @param boolean $retvar
         * @param int $user_id
         * 
         */
        return apply_filters( 'streamtube/core/dokan/is_seller_trusted', $retvar, $user_id );
    }

    /**
     *
     * Check if current user is seller
     * 
     * @return boolean
     */
    public function is_shop_manager( $user_id = 0 ){
        $retvar = false;

        if( ! $user_id ){
            $user_id = get_current_user_id();
        }        

        if( user_can( $user_id, 'administrator' ) || user_can( $user_id, 'shop_manager' ) ){
            $retvar = true;
        }        

        return apply_filters( 'streamtube/core/dokan/is_shop_manager', $retvar, $user_id );
    }

    /**
     *
     * Get dokan option
     * 
     */
    public function get_option( $option, $section, $default = '' ){
        if( ! function_exists( 'dokan_get_option' ) ){
            return $default;
        }

        return dokan_get_option(  $option, $section, $default );
    }

    /**
     *
     * Get seller user data
     * 
     * @param  int $user_id
     * 
     */
    public function get_seller( $user_id ){
        return dokan()->vendor->get( $user_id );
    }

    /**
     *
     * Get seller users
     * 
     * @return get_users()
     * 
     */
    public function get_sellers(){

        $args = array(
            'role__in'              => array(
                'seller',
                'administrator',
                'shop_manager'
            ),
            'has_published_posts'   =>  array( 'video', 'product' ),
            'fields'                =>  'ID'
        );

        return get_users( apply_filters( 'streamtube/core/dokan/get_sellers_args', $args ) );
    }

    public function get_store_dashboard_url( $user_id = 0 ){
        if( ! $user_id ){
            $user_id = get_current_user_id();
        }

        return sprintf(
            '%s/dashboard/%s',
            untrailingslashit( get_author_posts_url( $user_id ) ),
            $this->get_store_slug()
        );
    }

    /**
     *
     * Get wizard store setup URL
     * 
     * @return string
     * 
     */
    public function get_wizard_url(){
        $url = dokan_get_navigation_url();

        if ( 'off' === $this->get_option( 'disable_welcome_wizard', 'dokan_selling', 'off' ) ) {
            $url = apply_filters( 'dokan_seller_setup_wizard_url', site_url( '?page=dokan-seller-setup' ) );
        }

        return apply_filters( 'dokan_customer_migration_redirect', $url );        
    }

    /**
     *
     * Set the 'reports' query variable to load necessary assets
     * 
     */
    public function parse_request( $wp ){
        if( $this->is_dashboard_store( $wp ) ){
            $wp->query_vars['reports'] = true;

            if( strpos( $wp->query_vars['dashboard'], 'products' ) !== false ){
                $wp->query_vars['products'] = true;
            }

            if( $wp->query_vars['dashboard'] == sprintf( '%s/settings/store', $this->get_store_slug() ) ){
                $wp->query_vars['settings'] = 'store';
            }

            add_filter( 'dokan_forced_load_scripts',    '__return_true' );
            add_filter( 'dokan_force_load_extra_args',  '__return_true' );
        }
    }

    /**
     *
     * Set the custom "store" query var to display all dokan builtin widgets
     * 
     */
    public function set_store_query_var(){
        if( is_author() ){
            set_query_var( 
                $this->get_option( 'custom_store_url', 'dokan_general', 'store' ), 
                get_queried_object()->user_login
            );
        }
    }

    /**
     *
     * Always return true if current page is User Dashboard
     * 
     */
    public function filter_get_dashboard_page_id( $page ){
        if( $this->is_settings_page() ){
            add_filter( 'dokan_get_current_page_id', function( $page_id ){
                return (int)$this->get_option( 'dashboard', 'dokan_pages' );
            } );
        }

        return $page;
    }

    /**
     *
     * Filter the store url
     * 
     */
    public function filter_store_url( $store_url, $store_slug, $user_id ){
        return sprintf(
            '%s/%s/',
            untrailingslashit( get_author_posts_url( $user_id ) ),
            $this->get_store_slug()
        );
    }

    /**
     *
     * Add "dokan-dashboard" class to body if "Store" found
     * 
     * @param  array $classes
     */
    public function filter_body_class( $classes ){
        global $wp;

        if( $this->is_dashboard_store( $wp ) ){
            $classes[] = 'dokan-dashboard';
        }

        return $classes;
    }    

    /**
     *
     * Filter the dashboard shortcode query vars
     * After necessary params to load templates such as Dashboard, products, orders ...
     * 
     * @param  array  $query_vars
     */
    public function filter_shortcode_query_vars( $query_vars = array() ){

        global $wp;

        if( ! $this->is_dashboard_store( $wp ) ){
            return $wp->query_vars;
        }

        $qrvars = explode( '/' , $wp->query_vars['dashboard'] );

        if( count( $qrvars ) == 2 ){
            $wp->query_vars[ $qrvars[1] ] = true;
        }
        elseif( count( $qrvars ) == 3 ){
            $wp->query_vars[ $qrvars[1] ] = $qrvars[2];
        }
        else{
            $wp->query_vars['page'] = 'dashboard';
        }  

        return $wp->query_vars;
    }

    /**
     *
     * Filter the dashboard navigation url
     * Only apply to StreamTube Dashboard
     * 
     * @param  string $url
     * @param  string $name
     */
    public function filter_navigation_url( $url , $name ){

        return sprintf( 
            '%s/dashboard/%s/%s',
            untrailingslashit( get_author_posts_url( get_current_user_id() ) ),
            $this->get_store_slug(),
            $name
        );
    }

    public function filter_nav_active( $active_menu, $request, $active ){

        global $wp;

        if( ! $this->is_dashboard_store( $wp ) ){
            return $active_menu;
        }

        $params = explode( '/' , $wp->query_vars['dashboard'] );

        $nav_items = array_keys( dokan_get_dashboard_nav() );

        if( ( count( $params ) == 2 || count( $params ) == 3 )  && in_array( $params[1] , $nav_items ) ){
            return $params[1];
        }

        return 'dashboard';
    }

    /**
     * Remove common links such as log out, accout ... from the Dashboard menu
     * Only apply to StreamTube dashboard
     */
    public function remove_dashboard_common_link( $common_links ){
        if( $this->is_dashboard_store() ){
            return '';
        }
        return $common_links;
    }

    /**
     *
     * Remove the registration page redirect
     * Use the default WP form
     * 
     */
    public function remove_register_page_redirect(){
        remove_action( 'login_init', 'dokan_redirect_to_register' );
    }

    /**
     *
     * Redirect to streamtube dashboard
     * 
     */
    public function redirect_dashboard_page(){
        $page_id = $this->get_option( 'dashboard', 'dokan_pages' );

        if( $page_id && is_page( $page_id ) && is_user_logged_in() ){
            wp_redirect( 
                trailingslashit( get_author_posts_url( get_current_user_id() ) ) . 'dashboard/' . $this->get_store_slug() 
            );
        }
    }

    /**
     *
     * Filter the dokan sidebr args
     * Apply theme style
     * 
     */
    public function filter_store_sidebar_args( $args ){
        return array_merge( $args, array(
            'before_widget' => '<div class="widget widget-primary shadow-sm dokan-store-widget %s">',
            'after_widget'  => '</div>',
            'before_title'  => '<div class="widget-title-wrap"><h3 class="widget-title">',
            'after_title'   => '</h3></div>'         
        ));
    }

    /**
     *
     * Dokan widget args
     * 
     */
    public function filter_store_widget_args( $args ){
        return array_merge( $args, array(
            'before_widget' => '<div id="%1$s" class="widget widget-primary shadow-sm dokan-store-widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<div class="widget-title-wrap"><h2 class="widget-title d-flex align-items-center">',
            'after_title'   => '</h2></div>',
        ));
    }

    /**
     *
     * Filter the edit product url
     * 
     */
    public function filter_edit_product_url( $link, $post_id, $context ){

        if( is_author() ){
            $link = sprintf( 
                '%s/dashboard/%s/products',
                untrailingslashit( get_author_posts_url( get_post( $post_id )->post_author ) ),
                $this->get_store_slug()
            );
        }  

        return add_query_arg( array(
            'product_id'                => $post_id,
            'action'                    => 'edit',
            '_dokan_edit_product_nonce' => wp_create_nonce( 'dokan_edit_product_nonce' ),
        ), $link );
    }

    /**
     *
     * Filter the "dokan_pre_product_listing_args" which is located within the Dashboard
     *
     * Change the "product_cat" param
     * 
     */
    public function filter_pre_product_listing_args( $args ){
        if( isset( $_REQUEST['category_id'] ) && is_string( $_REQUEST['category_id'] ) && (int)$_REQUEST['category_id'] != -1 ){
            $args['tax_query'][] = array(
                'taxonomy'          => 'product_cat',
                'field'             => 'id',
                'terms'             => intval( $_GET['category_id'] ),
                'include_children'  => false
            );
        }

        return $args;
    }

    /**
     *
     * Filter Sell Content get product query args
     * Only display products of current user
     * 
     * @param  array $args
     * 
     */
    public function filter_query_product_args( $args ){

        global $wp_query;

        $args = array_merge( $args, array(
            'author'    =>  get_current_user_id()
        ) );

        return $args;
    }  

    /**
     *
     * Filter the builtin product status on Update event
     *
     * If Selling is not enabled for this author
     * Set the product status to pending
     *
     * @param WP_Product
     * 
     */
    public function filter_builtin_product_status( $product, $post ){

        if( ! $product || ! is_a( $product , 'WC_Product' ) ){
            return $product;
        }

        $user_id = $post->post_author;

        if( (   ! $this->is_seller( $user_id )              || 
                ! $this->is_seller_enabled( $user_id ) )    || 
                ! $this->is_seller_trusted( $user_id ) ){
             return $product->set_status( 'pending' );
        }
    }

    /**
     *
     * Filter the purchasable of content
     *
     * Set to false if author is not seller
     * 
     * @param  boolean $is_purchasable
     * @param  WC_Product $product
     * @param  int $post_id
     * 
     */
    public function filter_is_content_purchasable( $is_purchasable, $product, $post_id ){

        $user_id = get_post( $post_id )->post_author;

        if( ( ! $this->is_seller( $user_id ) || ! $this->is_seller_enabled( $user_id ) ) ){
            $is_purchasable = false;
        }

        return $is_purchasable;
    }

    /**
     *
     * Filter the bp ajax query string
     * Retrieve seller users if stores scope found
     */
    public function filter_bp_ajax_querystring( $qs, $object ){
        // not on the members object? stop now!
        if ( 'members' !== $object ) {
            return $qs;
        }

        // Parse querystring into array.
        $r = wp_parse_args( $qs, array(
            'scope'     =>  array_key_exists( 'bp-members-scope', $_COOKIE ) ? $_COOKIE['bp-members-scope'] : ''
        ) );

        if( $r['scope'] === 'stores' ){
            $users = $this->get_sellers();

            if( $users ){
                $r['include'] = $users;
            }else{
                $r['include'] = array( 0 );
            }
        }

        return $r;
    }

    /**
     *
     * The "become_seller_apply_button" shortcode content
     * 
     */
    public function _become_seller_apply_form( $args = array() ){

        $args = wp_parse_args( $args, array(
            'button_text'  =>  esc_html__( 'Apply now', 'streamtube-core' )
        ) );

        ob_start();
        ?>
        <form class="mt-4 form-ajax" name="become_seller_apply_form">

            <?php
            /**
             *
             * Fires before submit button
             * 
             */
            do_action( 'streamtube/core/dokan/become_seller_apply_form/before_submit', $args );
            ?>

            <input type="hidden" name="action" value="apply_become_seller" />
            
            <?php printf(
                '<button type="submit" class="btn btn-primary">%s</button>',
                $args['button_text']
            )?>

            <?php
            /**
             *
             * Fires after submit button
             * 
             */
            do_action( 'streamtube/core/dokan/become_seller_apply_form/after_submit', $args );
            ?>            

        </form>
        <?php
        return apply_filters( 
            'streamtube/core/dokan/become_seller_apply_form', 
            ob_get_clean() 
        );
    }

    /**
     *
     * The "become_seller_apply_button" shortcode register
     * 
     */
    public function become_seller_apply_form(){
        add_shortcode( 'become_seller_apply_form', array( $this, '_become_seller_apply_form' ) );
    }

    /**
     *
     * Send notification on manual approval event
     * 
     */
    public function notify_seller_manual_approval( $user_id = 0 ){

        $to = get_bloginfo( 'admin_email' );

        $subject = sprintf(
            esc_html__( '[%s] New Seller Request Approval', 'streamtube-core' ),
            get_bloginfo( 'name' )
        );

        $message = sprintf(
            esc_html__( 'User ID %s', 'streamtube-core' ),
            $user_id
        ) . "\r\n";

        $message .= sprintf(
            esc_html__( 'URL %s', 'streamtube-core' ),
            add_query_arg( array(
                'user_id'   =>  $user_id,
                'action'    =>  'manual_approve_seller'
            ), admin_url( 'user-edit.php' ) )
        ) . "\r\n";

        $headers = array();
        $headers[] = 'Content-Type: text/html; charset=UTF-8';
        $headers[] = sprintf(
            'From: %s <%s>',
            get_option( 'blogname' ),
            get_option( 'new_admin_email' )
        );        

        $email = compact( 'to', 'subject', 'message', 'headers' );

        /**
         *
         * filter the email before sending
         * 
         * @param array $email
         * @param  int $post video post type
         *
         * @since  1.0.0
         * 
         */
        $email = apply_filters( 'streamtube/core/dokan/seller_request_email', $email, $post );

        extract( $email );

        $response = wp_mail( $to, $subject, wpautop( $message ), $headers );

        if( ! $response ){
            return new WP_Error(
                'cannot_send_email',
                esc_html__( 'Cannot send email, please try again later.', 'streamtube-core' )
            );
        }

        return $response;
    }

    /**
     *
     * Process_apply_become_seller
     * 
     */
    public function auto_approve_seller( $user_id = 0 ){

        $errors = new WP_Error();

        if( $this->get_option( 'new_seller_enable_selling', 'dokan_selling', 'on' ) !== 'on' ){
            $errors->add( 
                'selling_disabled',
                esc_html__( 'Selling is disabled, please contact administrator for assistance.', 'streamtube-core' )
            );
        }

        if( user_can( $user_id, Streamtube_Core_Permission::ROLE_DEACTIVATE ) ){
            $errors->add( 
                'account_deactivated',
                esc_html__( 'Your account is deactivated.', 'streamtube-core' )
            );            
        }

        /**
         *
         * Filter the errors
         * 
         * @param WP_Error $errors
         * @param int $user_id
         * 
         */
        $errors = apply_filters( 'streamtube/core/dokan/become_seller/errors', $errors, $user_id );

        if( $errors->get_error_codes() ){
            return $errors;
        }

        $vendor = $this->get_seller( $user_id );

        /**
         *
         * Auto approve request
         * 
         */
        if( get_option( 'woocommerce_seller_approval', 'manual' ) == 'auto' ){
            Streamtube_Core_Permission::add_user_role( $user_id, self::ROLE_SELLER );

            $vendor->make_active();

            update_user_meta( 
                $user_id, 
                'dokan_publishing', 
                get_option( 'woocommerce_seller_publishing' ) ? 'yes' : 'no'
            );

            /**
             *
             * Fires after seller is aproved
             * Default Dokan hook
             *
             * @param int $user_id
             * 
             */
            do_action( 'dokan_new_seller_created', $user_id, $vendor->get_shop_info() );

            /**
             *
             * Fires after seller is aproved
             *
             * @param int $user_id
             * 
             */
            do_action( 'streamtube/core/dokan/become_seller/approved', $user_id );

            if( function_exists( 'wc_add_notice' ) ){
                wc_add_notice( esc_html__( 'Congratulations! You have been automatically approved.', 'streamtube-core' ) );
            }            

            return $user_id;

        }else{
            $response = $this->notify_seller_manual_approval( $user_id );

            if( $response === true ){
                /**
                 *
                 * Fires after seller is aproved
                 *
                 * @param int $user_id
                 * 
                 */
                do_action( 'streamtube/core/dokan/become_seller/request_sent', $user_id );

                if( function_exists( 'wc_add_notice' ) ){
                    wc_add_notice( esc_html__( 'The seller request has been successfully sent.', 'streamtube-core' ) );
                }
            }

            return $response;
        }
    }

    /**
     *
     * MManual approving seller through backend form.
     * 
     */
    public function manual_approve_seller(){
        $http_data = wp_parse_args( $_REQUEST, array(
            'user_id'   =>  0,
            'action'    =>  ''
        ) );

        extract( $http_data );

        if( ! $user_id                              || 
            $action != 'manual_approve_seller'      || 
            ! current_user_can( 'administrator' )   || 
            user_can( $user_id, 'administrator' )   ||
            $this->is_seller( $user_id )            ||
            ! is_admin() ){
            return;
        }

        $vendor = $this->get_seller( $user_id );

        if( $vendor ){
            Streamtube_Core_Permission::add_user_role( $user_id, self::ROLE_SELLER );

            $vendor->make_active();

            update_user_meta( 
                $user_id, 
                'dokan_publishing', 
                get_option( 'woocommerce_seller_publishing' ) ? 'yes' : 'no'
            );

            /**
             *
             * Fires after seller is aproved
             * Default Dokan hook
             *
             * @param int $user_id
             * 
             */
            do_action( 'dokan_new_seller_created', $user_id, $vendor->get_shop_info() );

            /**
             *
             * Fires after seller is aproved
             *
             * @param int $user_id
             * 
             */
            do_action( 'streamtube/core/dokan/become_seller/approved', $user_id );

            add_action( 'admin_notices', function() use( $user_id ){
                $user_data = get_userdata( $user_id );
                ?>
                <div class="notice notice-success">
                    <p><?php printf(
                        esc_html__( '(%s) %s has been approved successfully.', 'streamtube-core' ),
                        $user_data->user_login,
                        $user_data->display_name
                    ); ?></p>
                </div>
                <?php                
            } );

            return $user_id;            
        }
    }

    /**
     *
     * Ajax process_apply_become_seller
     * 
     */
    public function ajax_process_apply_become_seller(){

        check_ajax_referer( '_wpnonce' );

        $response = $this->auto_approve_seller( get_current_user_id() );

        if( is_wp_error( $response ) ){
            wp_send_json_error( $response );
        }

        wp_send_json_success( $this->get_wizard_url() );
    }

    /**
     *
     * Display the "Become seller" page content
     * 
     */
    public function display_become_seller_content(){

        $output = '';

        $page_id = get_option( 'woocommerce_become_seller' );

        if( $page_id && ( $_page = get_post( $page_id ) ) ){
            $output = $_page->post_content;
        }
        else{
            $output = $this->_become_seller_apply_form();
        }

        printf(
            '<div class="post-content">%s</div>',
            do_shortcode( $output )
        );

    }

    /**
     *
     * Display the "not_enabled_notice" message before the sell content metabox content
     * 
     */
    public function display_seller_not_enabled_notice(){

        $user_id = get_current_user_id();

        if( ! $this->is_seller( $user_id ) || ! $this->is_seller_enabled( $user_id ) ){
            if( function_exists( 'dokan_seller_not_enabled_notice' ) ){
                dokan_seller_not_enabled_notice();
            }
        }
    }

    /**
     *
     * Display the user products
     * 
     */
    public function display_store_products(){

        global $wp_query;

        $shortcode      = '';

        $category       = '';
        $columns        = 3;
        $limit          = get_option( 'posts_per_page' );
        $author         = get_queried_object_id();
        $attribute      = $terms = array();
        $terms_operator = '';
        $paginate       = true;
        $post_status    = array( 'publish' );

        if( array_key_exists( $this->get_store_slug(), $wp_query->query_vars ) ){
            if( ! empty( $wp_query->query_vars[ $this->get_store_slug() ] ) ){

                $store = trim( $wp_query->query_vars[ $this->get_store_slug() ] );

                if( strpos( $store, 'section/' ) !== false ){
                    $category = explode( '/' , $store );

                    if( $category ){
                        $category = (int)$category[1];
                    }
                }
            }
        }

        if( $_attributes = dokan_get_chosen_taxonomy_attributes() ){
            foreach ( $_attributes as $_key => $_attribute ) {
                $attribute[]    = $_key;
                $terms[]        = join(',', $_attribute['terms'] );
            }
            $terms_operator = 'AND';
        }

        $shortcode_attrs = compact( 
            'author', 
            'category', 
            'columns', 
            'limit', 
            'attribute', 
            'terms',
            'terms_operator',
            'paginate',
            'post_status'
        );

        /**
         *
         * Filter shortcode string
         * 
         * @var string
         */
        $shortcode_attrs = apply_filters( 
            'streamtube/core/user_profile_products_shortcode_attrs', 
            $shortcode_attrs,
        );

        foreach ( $shortcode_attrs as $key => $value ) {
            $shortcode .= sprintf( '%s="%s" ', $key, is_array( $value ) ? implode(',', array_filter($value)) : $value );
        }

        $shortcode = "[products {$shortcode}]";

        /**
         *
         * Filter shortcode string
         * 
         * @var string
         */
        $shortcode = apply_filters( 
            'streamtube/core/user_profile_products_shortcode', 
            $shortcode,
            $shortcode_attrs
        );

        $output = trim( do_shortcode( $shortcode ) );

        preg_match('/<div class="woocommerce columns-\d+ "><\/div>/', $output, $matches );

        if( ! $matches ){
            echo $output;
        }else{

            $message = sprintf(
                esc_html__( '%s has not added any products yet.', 'streamtube-core' ),
                '<strong>'. get_queried_object()->display_name .'</strong>'
            );

            if( get_current_user_id() == get_queried_object_id() ){
                $message = esc_html__( 'You have not added any products yet.', 'streamtube-core' );
            }

            if( isset( $_REQUEST['product-page'] ) || $attribute ){
                $message = esc_html__( 'Not found.', 'streamtube-core' );
            }

            printf(
                '<div class="not-found p-3 text-center text-muted fw-normal h6"><p>%s</p></div>',
                $message
            );
        }
    }

    /**
     *
     * Display the seller content such as Address, Store Schedule ...
     * 
     */
    public function display_store_location( $bio ){

        $location = $this->get_seller( get_queried_object_id() )->get_location();

        if( ! $location ){
            return $bio;
        }

        ob_start();

        $args = dokan_store_sidebar_args();

        if ( dokan()->widgets->is_exists( 'store_location' ) && 'on' === $this->get_option( 'store_map', 'dokan_general', 'on' ) ) {
            the_widget( dokan()->widgets->store_location, [ 'title' => __( 'Location', 'streamtube-core' ) ], $args );
        }

        return ob_get_clean() . $bio;
    }    

    /**
     *
     * Display the Featured badge in the User loop
     * 
     * @param  integer $user_id
     * 
     */
    public function display_store_featured_badge( $user_id = 0 ){
        $seller = $this->get_seller( $user_id );

        if( $seller && $seller->is_featured() && $this->is_seller_enabled( $user_id ) ){
            printf(
                '<span class="badge w-auto h-auto featured-store"><span class="icon-flash"></span> %s</span>',
                esc_html__( 'Featured', 'streamtube-core' )
            );
        }
    }

    /**
     *
     * Display store rating
     * 
     */
    public function display_store_rating( $user_id = 0 ){

        if( ! $user_id ){
            global $post;
            $user_id = $post->post_author;
        }

        $seller = $this->get_seller( $user_id );

        if( is_object( $seller )                            && 
            $this->is_seller_enabled( $user_id )            && 
            function_exists( 'dokan_generate_ratings' )     && 
            get_option( 'woocommerce_store_rating', 'on' ) ){
            $rating = $seller->get_rating();
            if ( ! empty( $rating['count'] ) ) :
                ?>
                <div class="dokan-seller-rating author-rating"
                    title="
                    <?php
                        echo sprintf(
                            // translators: 1) seller rating
                            esc_attr__( 'Rated %s out of 5', 'streamtube-core' ), number_format_i18n( $rating['rating'] )
                        );
                    ?>
                    ">
                    <?php echo wp_kses_post( dokan_generate_ratings( $rating['rating'], 5 ) ); ?>
                    <p class="rating text-muted">
                        <?php
                        echo esc_html(
                            // translators: 1) seller rating
                            sprintf( __( '%s out of 5', 'streamtube-core' ), number_format_i18n( $rating['rating'] ) )
                        );
                        ?>
                    </p>
                </div>
                <?php
            endif; 
        }
    }

    /**
     *
     * The dashboard menu
     * 
     * @param array $menu_items
     * 
     */
    public function display_dashboard_menu_item( $menu_items ){

        if( function_exists( 'WC' ) ){
            $menu_items[ $this->get_store_slug() ] = array(
                'title'     =>  esc_html__( 'My Store', 'streamtube-core' ),
                'icon'      =>  'icon-shop',
                'parent'    =>  'dashboard',
                'callback'  =>  function(){
                    if( $this->is_seller() ){
                        load_template( plugin_dir_path( __FILE__ ) . 'public/dashboard.php' );
                    }else{
                        load_template( plugin_dir_path( __FILE__ ) . 'public/become-seller.php' );
                    }
                },
                'cap'       =>  'read',
                'priority'  =>  5
            );
        }

        return $menu_items;
    }

    /**
     *
     * The dashboard menu
     * 
     * @param array $menu_items
     * 
     */
    public function display_profile_menu_item( $menu_items ){

        $menu_items[ $this->get_store_slug() ]  = array(
            'title'         =>  esc_html__( 'Store', 'streamtube-core' ),
            'icon'          =>  'icon-shop',
            'callback'      =>  function(){
                load_template( plugin_dir_path( __FILE__ ) . 'public/store.php' );
            },
            'cap'           =>  function( $user_id = 0 ){
                return $this->is_seller( $user_id ) && $this->is_seller_enabled( $user_id );
            },
            'priority'      =>  20
        );

        $menu_items[ 'my_' . $this->get_store_slug() ]    = array(
            'title'         =>  esc_html__( 'My Store', 'streamtube-core' ),
            'icon'          =>  'icon-shop',
            'url'           =>  $this->get_store_dashboard_url(),
            'cap'           =>  function( $user_id = 0 ){
                return $this->is_seller( $user_id ) && $this->is_seller_enabled( $user_id );
            },
            'priority'      =>  5,
            'private'       =>  true
        );

        $menu_items[ 'become_seller' ]    = array(
            'title'         =>  esc_html__( 'Become Seller', 'streamtube-core' ),
            'icon'          =>  'icon-shop',
            'url'           =>  $this->get_store_dashboard_url(),
            'cap'           =>  function( $user_id = 0 ){
                return ! $this->is_seller( $user_id );
            },
            'priority'      =>  5,
            'private'       =>  true
        );

        return $menu_items;
    }

    /**
     *
     * Add Stores tab to Members directory
     * 
     */
    public function display_bp_stores_tab(){

        $sellers = $this->get_sellers();

        if ( ! $sellers ) {
            return;
        }

        printf(
            '<li id="members-stores"><a href="#">%s</a></li>',
            sprintf( esc_html__( 'Stores %s', 'streamtube-core' ), '<span>' . esc_html( bp_core_number_format( count( $sellers ) ) ) )
        );
    }

    /**
     *
     * Display the "Add Product" dropdown menu
     * 
     */
    public function display_add_product_menu( $types ){
        $types['add-product'] = array(
            'text'  =>  esc_html__( 'Add Product', 'streamtube-core' ),
            'icon'  =>  'icon-plus',
            'url'   =>  dokan_edit_product_url( 0, true ),
            'cap'   =>  function(){
                return $this->is_seller() && $this->is_seller_enabled() ? true : false;
            }
        );

        return $types;
    }

    /**
     *
     * Enqueue custom assets
     * 
     */
    public function enqueue_scripts(){
        wp_enqueue_style( 
            'streamtube-core-dokan', 
            plugin_dir_url( __FILE__ ) . 'public/assets/style.css', 
            array( 'dokan-style', 'streamtube-style' ), 
            filemtime( plugin_dir_path( __FILE__ ) . 'public/assets/style.css' )
        );

        wp_enqueue_script( 
            'streamtube-core-dokan', 
            plugin_dir_url( __FILE__ ) . 'public/assets/scripts.js', 
            array( 'jquery' ), 
            filemtime( plugin_dir_path( __FILE__ ) . 'public/assets/scripts.js' ),
            true
        );        
    }
}