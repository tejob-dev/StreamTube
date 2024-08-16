<?php
/**
 * Define the Sell_Content functionality
 *
 *
 * @link       https://themeforest.net/user/phpface
 * @since      2.2
 *
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 */

/**
 *
 * @since      2.2
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */

if( ! defined('ABSPATH' ) ){
    exit;
}

class StreamTube_Core_Woocommerce_Sell_Content{

    /**
     *
     * Define addon slug
     * 
     */
	const SLUG                          = 'woocommerce_sell_content';

    /**
     *
     * Define reference product meta field
     * 
     */
    const META_FIELD_REF_PRODUCT        = '_ref_product';

    /**
     *
     * Define reference product meta field
     * 
     */
    const META_FIELD_BUILTIN_PRODUCT    = '_builtin_product';    

    /**
     *
     * Check if is active
     * 
     * @return boolean
     * 
     */
    public function is_active(){
        return get_option( self::SLUG, 'on' );
    }

    /**
     *
     * Check if selling is disabled for given post
     * 
     * @param  integer $post_id
     * 
     */
    public function is_post_disabled_selling( $post_id = 0 ){
        $retvar = get_post_meta( $post_id, '_disable_selling', true );

        return apply_filters(
            'streamtube/core/woocommerce/sell_content/is_post_disabled_selling' ,
            $retvar,
            $post_id
        );
    }

    /**
     *
     * Check if author_sell is enabled
     * 
     */
    public function can_author_sell(){
        $retvar = get_option( 'woocommerce_author_sell' );

        /**
         *
         * Filter the retvar
         * 
         */
        return apply_filters( 'streamtube/core/woocommerce/can_author_sell_video', $retvar );
    }

    /**
     *
     * Get product data
     * 
     * @param  int $product_id
     * @return wc_get_product|false
     * 
     */
    public function get_product( $product_id ){
        if( function_exists( 'wc_get_product' ) ) {
            return wc_get_product( $product_id );
        }

        return false;
    } 

    /**
     *
     * Create new product based on given video id
     * 
     * @param  integer $post_id
     */
    public function add_builtin_product( $post, $regular_price = 0, $sale_price = 0 ){

        $errors = new WP_Error();

        if( is_int( $post ) ){
            $post = get_post( $post );    
        }

        if( ! $this->can_set_builtin_product( $post->ID ) ){
            $errors->add(
                'no_permission',
                esc_html__( 'You do not have permission to do this action.', 'streamtube-core' )
            );
        }

        $_product = $this->get_builtin_product( $post->ID );

        if( $_product && ! StreamTube_Core_Woocommerce_Permission::can_edit_product( $_product->get_id() ) ){
            $errors->add(
                'no_permission',
                esc_html__( 'You do not have permission to update this product.', 'streamtube-core' )
            );
        }

        /**
         *
         * Filter WP_Error
         * 
         * @param @WP_Error $errors
         * @param WP_Post $post
         * @param float $regular_price
         * @param float $sale_price
         * 
         */
        $errors = apply_filters( 
            'streamtube/core/woocommerce/sell_content/add_builtin_product', 
            $errors, 
            $post, 
            $regular_price, 
            $sale_price 
        );

        if( $errors->get_error_codes() ){
            return $errors;
        }

        $product = new WC_Product_Simple( $_product );

        $product->set_name( $post->post_title );
        $product->set_slug( $post->post_name );

        if( is_numeric( $regular_price ) ){
            $product->set_regular_price( $regular_price );
        }else{
            $product->set_regular_price( '' );
        }
        
        if( is_numeric( $sale_price ) ){
            $product->set_sale_price( $sale_price );
        }else{
            $product->set_sale_price( '' );
        }

        $product->set_catalog_visibility( 'catalog' );
        $product->set_virtual( true );

        if( has_post_thumbnail( $post->ID ) ){
            $product->set_image_id( get_post_thumbnail_id( $post->ID ) );
        }

        /**
         *
         * Make product purchasable
         * Filter the status for further checking
         *
         * @param string $status
         * @param WP_Post
         * @param WC_Product_Simple$product
         * 
         */
        $product->set_status( $post->post_status );

        do_action( 
            'streamtube/core/woocommerce/add_builtin_product/before_save', 
            array( &$product ),
            $post 
        );

        $product_id = $product->save();

        do_action( 
            'streamtube/core/woocommerce/add_builtin_product/after_save', 
            array( &$product ),
            $post 
        );        

        if( is_numeric( $product_id ) ){
            // update product author  
            wp_update_post( array(
                'ID'            =>  $product_id,
                'post_author'   =>  $post->post_author
            ) );

            $this->update_builtin_product( $post->ID, $product_id );

            if( isset( $_REQUEST['disable_selling'] ) ){
                update_post_meta( $post->ID, '_disable_selling', 'on' );
            }else{
                delete_post_meta( $post->ID, '_disable_selling' );
            }

            /**
             *
             * Fires after builtin product added
             *
             * @param int $product_id
             * @param WP_Post $post
             * 
             */
            do_action( 'streamtube/core/woocommerce/added_builtin_product', $product_id, $post );
        }

        return $product_id;
    }    

    /**
     *
     * Check if user can set a builtin product for given post
     * Only admin and editor are allowed by default
     * 
     */
    public function can_set_builtin_product( $post_id = 0 ){

        $retvar = false;

        if( Streamtube_Core_Permission::can_edit_post( $post_id ) && 
            StreamTube_Core_Woocommerce_Permission::can_edit_products() ){
            $retvar = true;
        }

        /**
         *
         * Filter the retvar
         *
         * @param boolean $retvar
         * @param int $post_id
         * 
         */
        return apply_filters( 
            'streamtube/core/woocommerce/sell_content/can_set_builtin_product', 
            $retvar, 
            $post_id 
        );
    }     

    /**
     *
     * Get the built-in product
     * 
     * @param  int $post_id
     */
    public function get_builtin_product( $post_id ){
        $product_id = get_post_meta( $post_id, self::META_FIELD_BUILTIN_PRODUCT, true );

        /**
         *
         * Filter builtin product ID
         *
         * @param  int $product
         * @param  int $post_id
         * 
         */
        $product_id = apply_filters( 'streamtube/core/woocommerce/sell_content/builtin_product', $product_id, $post_id );

        return $this->get_product( $product_id );
    }

    /**
     *
     * Update built-in product metadata
     * 
     */
    public function update_builtin_product( $post_id, $product_id ){
        return update_post_meta( $post_id, self::META_FIELD_BUILTIN_PRODUCT, $product_id );
    }

    /**
     *
     * Delete builtin product
     *
     * @param int string $post_id video id
     * @param boolean $force_delete
     * 
     */
    public function delete_builtin_product( $post_id, $force_delete = true ){
        $product = $this->get_builtin_product( $post_id );

        if( $product && $product->exists() ){
            return wp_delete_post( $product->get_id(), $force_delete );
        }
    }

    /**
     *
     * Check if given product is a builtin
     * 
     * @param  integer $product_id
     * @return int $video id
     * 
     */
    public function is_builitin_product( $product_id = 0 ){
        global $wpdb;

        $result = $wpdb->get_col( $wpdb->prepare(
            "
                SELECT post_id FROM {$wpdb->prefix}postmeta
                WHERE   meta_key = '_builtin_product'
                AND     meta_value = %d
            ",
            $product_id 
        ) );

        return $result ? $result[0] : 0;
    }

    /**
     *
     * Check if user can set a relevant product for given post
     * Only admin and editor are allowed by default
     * 
     */
    public function can_set_relevant_product( $post_id = 0 ){

        $retvar = false;

        if( ( Streamtube_Core_Permission::can_edit_post( $post_id ) && $this->can_author_sell() ) ||
            Streamtube_Core_Permission::moderate_posts() || 
            StreamTube_Core_Woocommerce_Permission::is_shop_manager() ){
            $retvar = true;
        }

        /**
         *
         * Filter the retvar
         *
         * @param boolean $retvar
         * @param int $post_id
         * 
         */
        return apply_filters( 
            'streamtube/core/woocommerce/sell_content/can_set_relevant_product', 
            $retvar, 
            $post_id 
        );
    }     

    /**
     *
     * Get reference product ID
     * 
     * @param  int $post_id
     * @return WP_Product
     * 
     */
    public function get_relevant_product( $post_id = 0 ){

        if( ! is_numeric( $post_id ) || get_post_type( $post_id ) !== 'video' ){
            return false;
        }

        $product_id = (int)get_post_meta( $post_id, self::META_FIELD_REF_PRODUCT, true );

        /**
         *
         * Filter reference product ID
         *
         * @param  int $product_id
         * @param  int $post_id
         * 
         */
        $product_id = apply_filters( 'streamtube/core/woocommerce/sell_content/ref_product', $product_id, $post_id );

        return $this->get_product( $product_id );
    }

    /**
     *
     * Update relevant product ID
     * Delete post meta if Product ID isn't found
     * 
     * @param  int $post_id
     * 
     */
    public function update_relevant_product( $post_id ){

        $http_data = wp_parse_args( $_POST, array(
            'product_id'                =>  0,
            'update_relevant_product'   =>  ''
        ) );

        extract( $http_data );

        if ( ! wp_verify_nonce( $update_relevant_product, 'update_relevant_product' ) ){
            return false;
        }

        if( ! $this->can_set_relevant_product( $post_id ) ){
            return false;
        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return false;
        }

        if( $product_id ){
            return update_post_meta( $post_id, self::META_FIELD_REF_PRODUCT, $product_id );
        }else{
            return delete_post_meta( $post_id, self::META_FIELD_REF_PRODUCT );
        }
    }

    /**
     *
     * Set default reference product ID
     * 
     * @param int|false $ref_product
     * 
     */
    public function set_default_relevant_product( $product, $post_id ){

        $default_product = (int)get_option( 'woocommerce_default_product_id' );

        if( ! $product && $default_product ){
            $product = $default_product;
        }

        return $product;
    }

    /**
     *
     * Check if current user purchased given product
     * 
     * @param  int  $product_id
     * @return boolean
     * 
     */
    public function is_customer_bought_product( $product = 0 ){

        // Always return false if wc_customer_bought_product was not found or user is not logged in yet
        if( ! function_exists( 'wc_customer_bought_product' ) || ! is_user_logged_in() ){
            return false;
        }

        if( is_int( $product ) ){
            $product = $this->get_product( $product );
        }

        $user_data = wp_get_current_user();

        if( wc_customer_bought_product( $user_data->user_email, $user_data->ID, $product->get_id() ) ){
            return apply_filters( 
                'streamtube/core/woocommerce/sell_content/did_customer_buy_product', 
                true, 
                $user_data, 
                $product 
            );
        }

        return false;
    }    

    /**
     *
     * Check if customer purchased given video
     * 
     */
    public function is_customer_bought_video( $post_id ){

        $product = $this->get_relevant_product( $post_id );

        if( $product ){
            return $this->is_customer_bought_product( $product );
        }

        return false;
    }

    /**
     *
     * Get thumbnail image URL
     * 
     * @return string
     *
     * @since 1.0.9
     * 
     */
    public function get_thumbnail_url(){
        $thumbnail_url = '';

        if( has_post_thumbnail() ){
            $thumbnail_url = wp_get_attachment_image_url( get_post_thumbnail_id(), 'large' );
        }

        /**
         *
         * @since 1.0.9
         * 
         */
        return apply_filters( 'streamtube/core/woocommerce/sell_content/player/thumbnail_url', $thumbnail_url );
    }    

    /**
     *
     * Render Sell Content notice
     * 
     * @param  HTML $player
     * @return Rendered HTML
     * 
     */
    private function render_sell_content( $player, $setup = array() ){     

        $product = $this->get_relevant_product( $setup['mediaid'] );

        if( ! is_a( $product , 'WC_Product' ) ){
            return $player;
        }

        /**
         *
         * Filter is_purchasable
         *
         * @param $boolean $is_purchasable
         * @param WP_Product $product
         * @param int $post_id
         * 
         */
        $is_purchasable = apply_filters( 
            'streamtube/core/woocommerce/sell_content/player/is_purchasable', 
            $product->is_purchasable(), 
            $product, 
            $setup['mediaid'] 
        );

        $heading = apply_filters( 
            'streamtube/core/woocommerce/sell_content/player/heading', 
            esc_html__( 'Premium Content', 'streamtube-core' ), 
            $player, 
            $product 
        );

        ob_start();
        ?>
        <div class="no-permission error-message">
            <div class="position-absolute top-50 start-50 translate-middle center-x center-y">
                <div class="product">
                    <?php if( $heading ): ?>
                        <?php printf(
                            '<h3 class="purchase-heading">%s</h3>',
                            $heading
                        );?>
                    <?php endif;?>
                    
                    <div class="woocommerce-loop-product__title d-none">
                        <?php echo $product->get_title() ?>
                    </div>
                    <?php 

                        /**
                         *
                         * Fires before message
                         *
                         * @param WP_Product $product
                         * 
                         */
                        do_action( 'streamtube/core/woocommerce/sell_content/player/message/before', $product );

                        if( $is_purchasable ):

                            $add_to_cart = do_shortcode( '[add_to_cart id="'. $product->get_id() .'"]' );

                            if( $add_to_cart ):

                                $add_to_cart = str_replace( 
                                    'add_to_cart_inline',  
                                    'add_to_cart_inline border-0 mb-2', 
                                    $add_to_cart 
                                );

                                $add_to_cart = str_replace( 
                                    'woocommerce-Price-amount',  
                                    'woocommerce-Price-amount fw-bold d-block', 
                                    $add_to_cart 
                                );                        

                                $add_to_cart = str_replace( 
                                    '<span class="icon-cart-plus"></span>', 
                                    '<span class="icon-cart-plus"></span><span class="add-to-cart-text ms-2">' .esc_html__( 'Add to cart', 'streamtube-core' ) . '</span>',
                                    $add_to_cart 
                                );

                                $add_to_cart = str_replace( 
                                    'add_to_cart_button',  
                                    'add_to_cart_button d-block text-decoration-none', 
                                    $add_to_cart 
                                );

                                if( is_embed() ){
                                    $add_to_cart = preg_replace_callback(
                                        '/href=("|\')\?add-to-cart=(\d+)("|\')/',
                                        function($matches) {
                                            $product_id = $matches[2];

                                            $cart_url = function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : home_url('/');

                                            $url = add_query_arg( array(
                                                'add-to-cart'   =>  $product_id
                                            ), $cart_url );

                                            return 'href="' . esc_url($url) . '"';

                                        },
                                        $add_to_cart
                                    );
                                }

                                echo $add_to_cart;
                            endif;

                        else:
                            printf(
                                '<p class="text-warning">%s</p>',
                                esc_html__( 'This video is not ready for sale.', 'streamtube-core' )
                            );
                        endif;

                        /**
                         *
                         * Fires after message
                         *
                         * @param WP_Product $product
                         * 
                         */
                        do_action( 'streamtube/core/woocommerce/sell_content/player/message/after', $product );                        
                    ?>
                </div>
            </div>
        </div>       
        <?php

        printf(
            '<div class="player-poster bg-cover" style="background-image:url(%s)"></div>',
            $setup['poster2'] ? $setup['poster2'] : $setup['poster']
        );

        $output = ob_get_clean();

        /**
         *
         * Filter the rendered content
         *
         * @param string $output
         * @param string $player
         * @param array $setup
         * @param WP_product $product
         *
         */
        return apply_filters( 
            'streamtube/core/woocommerce/sell_content/rendered_player/output', 
            $output, 
            $player, 
            $setup, 
            $product 
        );
    }

    /**
     *
     * Check if content is restricted
     *
     * @return product_id or false
     * 
     */
    public function is_content_restricted( $setup ){  

        $product = $this->get_relevant_product( $setup['mediaid'] );

        if( ! $product
            || ! $this->is_active()
            || Streamtube_Core_Permission::moderate_posts() 
            || Streamtube_Core_Permission::is_post_owner( $product->get_id() )
            || StreamTube_Core_Woocommerce_Permission::is_shop_manager()
            || $this->is_customer_bought_product( $product )
            || $this->is_post_disabled_selling( $setup['mediaid'] )
            || $setup['trailer']
            ){

            return apply_filters( 
                'streamtube/core/woocommerec/sell_content/is_content_restricted', 
                false, 
                $setup['mediaid'] 
            );
        }

        return $product;
    }

    /**
     *
     * Set builtin product instead of reference product
     * 
     */
    public function set_builtin_product( $product_id, $post_id ){
        $product = $this->get_builtin_product( $post_id );

        if( $product ){
            $product_id = $product->get_id();
        }

        return $product_id;
    }

    /**
     *
     * Add builtin product through the POST request
     * 
     * @param integer $post_id
     */
    public function do_add_builtin_product( $post_id, $post, $update ){

        if( ! isset( $_POST ) || 
            ! isset( $_POST['regular_price'] ) || 
            ( is_string( $_POST['regular_price'] ) && $_POST['regular_price'] === "" ) || 
            ! isset( $_POST['update_builtin_product'] ) ||
            ! wp_verify_nonce( $_POST['update_builtin_product'], 'update_builtin_product' ) ){
            return;
        }

        $sale_price = isset( $_POST['sale_price'] ) ? $_POST['sale_price'] : false;

        return $this->add_builtin_product( $post,  (float)$_POST['regular_price'], $sale_price );
    }

    /**
     *
     * Update the built-in status if post has been approved, rejected, pending or trashed
     * 
     */
    public function do_update_builtin_product( $post_id ){
        $product = $this->get_builtin_product( $post_id );

        if( ! $product ){
            return false;
        }

        $product = new WC_Product_Simple( $product );

        $product->set_status( get_post( $post_id )->post_status );

        return $product->save();
    }

    /**
     *
     * Delete the product after deleting related video
     * Hooked into "after_delete_post"
     * 
     * @param  int $post_id
     * @param WP_Post $post
     * 
     */
    public function do_delete_builtin_product( $post_id, $post ){
        if( $post->post_type == 'video' ){
            return $this->delete_builtin_product( $post_id );
        }
    }

    /**
     *
     * Hooked into "streamtube/core/get_full_post_data"
     * Attach price field to check in JS
     */
    public function filter_get_full_post_data( $response, $post_id, $post ){
        if( false !== $product = $this->get_builtin_product( $post_id ) ){
            $response->product_id = $product->get_id();
        }

        return $response;
    }

    /**
     *
     * Hooked into "after_delete_post"
     * Delete post metadata
     */
    public function delete_product_metadata( $post_id, $post  ){

        if( $post->post_type == 'product' ){
            global $wpdb;

            $wpdb->query(
                $wpdb->prepare(
                    "
                    DELETE FROM $wpdb->postmeta
                    WHERE (meta_value = %d AND meta_key = %s)
                    OR (meta_value = %d AND meta_key = %s)
                    ",
                    $post_id,
                    self::META_FIELD_REF_PRODUCT,
                    $post_id,
                    self::META_FIELD_BUILTIN_PRODUCT
                )
            );
        }
    }

    /**
     * Filter player advertisements
     * Remove Ad if user purchased levels
     * 
     */
    public function filter_advertisements( $vast_tag_url, $setup, $source  ){
        
        if( ! get_post_status( $setup['mediaid'] ) ){
            return $vast_tag_url;
        } 

        $product = $this->get_relevant_product( $setup['mediaid'] );

        if( $product                                        &&
            $this->is_customer_bought_product( $product )   && 
            get_option( 'woocommerce_disable_advertisement' ) ){
            return false;
        }

        return $vast_tag_url;
    }

    /**
     *
     * Filter player output, show "Purchase" if reference product found
     * 
     * @param  HTML $player
     * @return HTML
     */
    public function filter_player_output( $player, $setup ){

        if( $this->is_content_restricted( $setup ) ){
            return $this->render_sell_content( $player, $setup );
        }
        return $player;
    }

    /**
     *
     * Filter embed html
     * 
     */
    public function filter_player_embed_output( $embed_html, $setup ){
        return $this->filter_player_output( $embed_html, $setup );
    }

    /**
     *
     * Filter post classes
     * 
     * @param  array $post_classes
     * @return array $post_classes
     * 
     */
    public function filter_post_classes( $post_classes ){

        $classes = array();

        $product = $this->get_relevant_product( get_the_ID() );

        if( $product ){

            $classes[] = 'purchase-required';

            if( $this->is_customer_bought_product( $product ) ){
                $classes[] = 'product-purchased';                
            }
        }

        return array_merge( $post_classes, $classes );
    }

    /**
     *
     * Filter download permission
     * 
     */
    public function filter_download_permission( $retvar ){

        $product = $this->get_relevant_product( get_the_ID() );

        if( $product && ! $this->is_customer_bought_product( $product ) ){
            $retvar = false;
        }

        return $retvar;
    }

    /**
     *
     * Apply to the Woocommerce tab within Post List widget
     * 
     */
    public function filter_widget_post_list_query( $query_args, $instance ){

        if( ! $this->is_active() ){
            return $query_args;
        }

        $instance = wp_parse_args( $instance, array(
            'content_cost'  =>  '',
            'ref_products'  =>  array()
        ) );

        extract( $instance );

        if( is_array( $post_type ) && ! in_array( 'video', $post_type ) ){
            return $query_args;
        }

        if( is_string( $ref_products ) ){
            $ref_products = trim( $ref_products );

            if( empty( $ref_products ) ){
                return $query_args;
            }

            $ref_products = array_map( 'intval' , explode( ',', $ref_products ) );
        }

        if( is_array( $ref_products ) ){
            $ref_products = array_map( 'intval' , $ref_products );
        }

        if( get_option( 'woocommerce_filter_content_cost', 'on' ) ){
            switch ( $content_cost ) {
                case 'free':
                    $ref_products = array( 'free' );
                break;
                
                case 'premium':
                    $ref_products = array( 1 );
                break;
            }
        }

        if( $ref_products ){
            if( in_array( 1, $ref_products ) ){
                $query_args['meta_query'][] = array(
                    'key'       =>  '_ref_product',
                    'compare'   =>  'EXISTS'
                );
            }
            elseif( in_array( 'free', $ref_products ) ){
                $query_args['meta_query'][] = array(
                    'key'       =>  '_ref_product',
                    'compare'   =>  'NOT EXISTS',
                    'value'     =>  'free'
                );                
            }
            else{
                $query_args['meta_query'][] = array(
                    'key'       =>  '_ref_product',
                    'compare'   =>  'IN',
                    'value'     =>  $ref_products
                );
            }
        }

        return $query_args;
    }

    /**
     *
     * Display Price within the loop
     * 
     */
    public function add_price_badge(){

        global $post;

        if( ! $post instanceof WP_Post || $post->post_type != 'video' || $this->is_post_disabled_selling( $post->ID ) ){
            return;
        }
        
        $product = $this->get_relevant_product( $post->ID );

        if( ! $product 
            || ! $this->is_active()
            || ! get_option( 'woocommerce_video_price_label', 'on' ) ){
            return;
        }

        return printf(
            '<div class="woocommerce-sell-content"><span class="price">%s</span></div>',
            $product->get_price_html()
        );
    }

    /**
     *
     * Add admin metabox
     *
     * @since 2.1
     * 
     */
    public function add_meta_boxes(){

        if( ! function_exists( 'WC' ) ){
            return;
        }

        add_meta_box( 
            self::SLUG, 
            esc_html__( 'Woocommerce Sell Content', 'streamtube-core' ), 
            array( $this , 'the_sell_content_box' ), 
            array( 'video' ), 
            'advanced', 
            'high'
        );
    }

    /**
     *
     * Backend Sell Content box
     * 
     * @param WP_Post $post
     * 
     */
    public function the_sell_content_box( $post ){

        if( ! is_object( $post ) || $post->post_type != 'video' ){
            return;
        }
                
        if( $this->can_set_relevant_product( $post->ID ) || $this->can_set_builtin_product( $post->ID ) ){
            load_template( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'admin/sell-content-box.php' );
        }
    }

    /**
     *
     * Frontend Sell Content box
     * 
     */
    public function display_metabox_sell_content(){

        global $post;

        if( ! is_object( $post ) || $post->post_type != 'video' || ! function_exists( 'WC' ) ){
            return;
        }

        if( $this->can_set_relevant_product( $post->ID ) || $this->can_set_builtin_product( $post->ID ) ){
            load_template( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'public/sell-content-box.php' );    
        }
    }

    /**
     *
     * Enqueue frontend scripts
     * 
     */
    public function enqueue_scripts(){
        wp_enqueue_script( 
            'woocommerce-sell-content', 
            plugin_dir_url( __FILE__ ) . 'public/scripts.js', 
            array( 'jquery' ), 
            filemtime( plugin_dir_path( __FILE__ ) . 'public/scripts.js' ), 
            true
        );
    }

}