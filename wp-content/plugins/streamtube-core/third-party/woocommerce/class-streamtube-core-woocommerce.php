<?php
/**
 * Menu
 *
 * @link       https://themeforest.net/user/phpface
 * @since      1.0.0
 *
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 */

if( ! defined('ABSPATH' ) ){
    exit;
}

class Streamtube_Core_Woocommerce{

    /**
     *
     * Holds Sell Content object
     * 
     * @var object
     */
    public $sell_content;

    public function __construct(){
         $this->load_dependencies();

         $this->sell_content = new StreamTube_Core_Woocommerce_Sell_Content();
    }

    /**
     *
     * Check if WC is active
     * 
     */
    public function is_active(){
        return function_exists( 'WC' ) ? true : false;
    }

    /**
     *
     * Load the required dependencies for this plugin.
     * 
     * @since 1.1
     */
    private function load_dependencies(){
        $this->include_file( 'class-streamtube-core-woocommerce-permission.php' );
        $this->include_file( 'class-streamtube-core-woocommerce-sell-content.php' );
    }    

    /**
     *
     * Include file in WP environment
     * 
     * @param  string $file
     *
     * @since 1.0.9
     * 
     */
    protected function include_file( $file ){
        require_once trailingslashit( plugin_dir_path( __FILE__ ) ) . $file;
    }    

    /**
     *
     * Remove default Woocommerce hooks
     * 
     */
    public function remove_default(){

        // Remove default single product title
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );

        // Remove tab description title
        add_filter( 'woocommerce_product_description_heading', '__return_null' );
        add_filter( 'woocommerce_product_additional_information_heading', '__return_null' );

        remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
        remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );

        remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );

        remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );

        // Remove default product thumbnails
        remove_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20 );

        // Remove the default rating and display it under the product title instead
        // display_single_product_rating()
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );

        // Remove WC lost password
        remove_filter( 'lostpassword_url', 'wc_lostpassword_url', 10 );

        remove_action( 'template_redirect', 'wc_disable_author_archives_for_customers', 10 );
    }

    /**
     *
     * Get product URL
     * 
     */
    private function get_product_url(){

        global $product;

        return apply_filters( 'woocommerce_loop_product_link', get_the_permalink(), $product );
    }

    /**
     *
     * Get the thumbnail image ratio
     * 16x9 is default
     * 
     * @return string
     */
    private function get_thumbnail_ratio(){
        return apply_filters( 
            'woocommerce_loop_product_thumbnail_ratio', 
            get_option( 'woocommerce_thumbnail_ratio', '16x9' ) 
        );
    }

    /**
     *
     * Get the thumbnail image ratio
     * 16x9 is default
     * 
     * @return string
     */
    private function get_thumbnail_size(){
        return apply_filters( 
            'woocommerce_loop_product_thumbnail_size', 
            get_option( 'woocommerce_thumbnail_size', 'streamtube-image-medium' )
        );
    }    

    /**
     *
     * Get the single thumbnail image ratio
     * 16x9 is default
     * 
     * @return string
     */
    private function get_single_thumbnail_ratio(){
        return apply_filters( 
            'woocommerce_single_product_thumbnail_ratio', 
            get_option( 'woocommerce_single_thumbnail_ratio', '16x9' ) 
        );
    }

    /**
     *
     * Display the product loop thumbnail
     * 
     */
    public function display_template_loop_product_thumbnail(){
        printf(
            '<a href="%s"><div class="post-thumbnail ratio ratio-%s rounded overflow-hidden bg-dark">%s</div></a>',
            esc_url( $this->get_product_url() ),
            $this->get_thumbnail_ratio(),
            woocommerce_get_product_thumbnail()
        );
    }

    /**
     *
     * Display the product loop title
     * 
     */
    public function display_template_loop_product_title(){

        $class = apply_filters( 'woocommerce_product_loop_title_classes', 'woocommerce-loop-product__title post-title' );
        
        printf(
            '<h2 class="%s"><a href="%s" class="text-body">%s</a></h2>',
            esc_attr( $class ),
            esc_url( $this->get_product_url() ),
            get_the_title()
        );
    }

    /**
     *
     * Get cart content
     * 
     * @return array
     *
     * @since 1.0.5
     * 
     */
    public function get_cart_total(){

        if( ! $this->is_active() ){
            return new WP_Error( 'WC_not_found', esc_html__( 'WC was not found', 'streamtube-core' ) );
        }

        $item_count = WC()->cart->get_cart_contents_count();

        return array(
            'item_count'        =>  (int)$item_count,
            'item_count_text'   =>  sprintf( 
                _n( '%s item', '%s items', $item_count, 'streamtube-core' ), 
                number_format_i18n( $item_count ) 
            ),
            'total'             =>  wc_price( WC()->cart->total )
        );
    }

    /**
     *
     * AJAX get cart content
     * 
     * @return prints JSON results
     *
     * @since 1.0.5
     * 
     */
    public function ajax_get_cart_total(){

        check_ajax_referer( '_wpnonce' );

        wp_send_json_success( $this->get_cart_total() );
    }

    /**
     *
     * The Cart button
     * 
     */
    public function the_cart_button(){

        if( ! $this->is_active() || 
            ! get_option( 'woocommerce_enable_header_cart', 'on' ) ||
            streamtube_core_has_mobile_footer_bar() ){
            return;
        }

        $count = WC()->cart->get_cart_contents_count();

        ?>
        <div class="header-user__cart">
            <div class="dropdown">
                <button class="btn btn-cart shadow-none px-2 position-relative" data-bs-toggle="dropdown" data-bs-display="static">
                    <span class="btn__icon icon-cart-plus"></span>
                    <?php printf(
                        '<span class="badge cart-count bg-danger position-absolute top-0 end-0 %s">%s</span>',
                        (int)$count == 0 ? 'd-none' : '',
                        number_format_i18n( $count )
                    )?>
                </button>

                <div class="dropdown-menu dropdown-menu-end dropdown-menu-mini-cart">
                    <div class="widget-title-wrap d-flex m-0 p-0 border-bottom">
                        <h2 class="widget-title p-3 m-0 no-after"><?php esc_html_e( 'Shopping cart', 'streamtube-core' ); ?></h2>
                    </div>                    
                    <div class="widget_shopping_cart_content">
                        <?php woocommerce_mini_cart(); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     *
     * Only completed orders will be considered as paid
     * 
     * @param  array  $statuses
     * 
     */
    public function filter_order_is_paid_statuses( $statuses = array() ){
        return array( 'completed' );
    }

    /**
     *
     * Always return true if current page is User profile page
     * 
     * @return boolean
     */
    public function filter_is_account_page( $retvar ){
        if( is_author() ){
            return true;
        }

        return $retvar;
    }

    /**
     *
     * Filter is_purchasable
     *
     * By default, only published product is purchasable
     * We support "unlist" status as well
     * 
     */
    public function filter_is_purchasable( $retvar, $product ){
        if( $product->exists() && 
            in_array( $product->get_status() , array( 'publish', 'unlist' ) ) && 
            '' !== $product->get_price() &&
            $this->sell_content->is_builitin_product( $product->get_id() )
        ){
            $retvar = true;
        }

        return $retvar;
    }

    /**
     *
     * Display cart product count
     * 
     */
    public function filter_wp_menu_item_title( $title, $wpmi, $item, $args, $depth  ){

        if( ! is_object( $item ) || ! $this->is_active() ){
            return $title;
        } 

        if( $item->url == wc_get_cart_url() ){
            $count = WC()->cart->get_cart_contents_count();
            $title .= sprintf(
                '<span class="menu-badge badge bg-danger cart-count %s">%s</span>',
                (int)$count == 0 ? 'd-none' : '',
                number_format_i18n( $count )
            ); 
        }

        return $title;
    }

    /**
     *
     * Add more attributes to the [products] shortcode
     * 
     */
    public function filter_shortcode_atts_products( $out, $pairs, $atts, $shortcode ){

        $atts = wp_parse_args( $atts, array(
            'author'            =>  0,
            'post_status'       =>  '',
            'search'            =>  ''

        ) );

        return array_merge( $out, $atts );
    }

    /**
     *
     * Filter the [products] shortcode query
     * 
     */
    public function filter_shortcode_products_query( $args, $atts = array(), $type = '' ){
        $atts = wp_parse_args( $atts, array(
            'author'            =>  0,
            'post_status'       =>  '',
            'search'            =>  ''
        ) );

        if( $atts['author'] ){
            $args['author'] = (int)$atts['author'];    
        }

        if( $atts['post_status'] ){
            $args['post_status'] = array_map('trim', array_filter(explode( ',', $atts['post_status'])));
        }

        if( $atts['search'] ){
            $args['s'] = wp_unslash( $atts['search'] );
        }

        return $args;
    }

    /**
     *
     * Display unlist product as well if "unlist" status found
     * 
     */
    public function filter_product_is_visible(){
        global $product;

        if( is_object( $product ) && in_array( $product->get_status() , array( 'publish', 'unlist' ) ) ){
            return true;
        }

        return false;
    }

    public function display_single_product_rating(){
        woocommerce_template_single_rating();
    }

    /**
     *
     * Add wrapper to single thumbnail image
     * Make it appear within defined aspect ratio
     *
     * @param string $html
     * @param int $post_thumbnail_id
     * 
     */
    public function filter_single_product_image_thumbnail_html( $html, $post_thumbnail_id ){
        //wp_get_attachment_image( $post_thumbnail_id, $this->get_single_thumbnail_size() )
        return sprintf(
            '<div class="shadow-sm product-thumbnail post-thumbnail ratio ratio-%s rounded overflow-hidden bg-dark">%s</div>',
            esc_attr( $this->get_single_thumbnail_ratio() ),
            $html
        );
    }

    /**
     *
     * Set default thumbnail size
     * 
     * @param  string $size
     * 
     */
    public function filter_thumbnail_image_size( $size ){
        return $this->get_thumbnail_size();
    }

    /**
     *
     * Product gallery tab
     * 
     */
    public function display_product_gallery_tab( $tabs ){

        global $product;

        $attachment_ids = $product->get_gallery_image_ids();

        if ( $attachment_ids ) {
            $tabs['gallery'] = array(
                'title'     =>  esc_html__( 'Gallery', 'streamtube-core' ),
                'priority'  =>  50,
                'callback'  =>  function(){
                    woocommerce_show_product_thumbnails();
                }
            );
        }

        return $tabs;
    }

    /**
     *
     * Display notifications within user dashboard
     * 
     */
    public function display_dashboard_notices(){
        if( function_exists( 'wc_print_notices' ) ){
            wc_print_notices();
        }
    }
}

