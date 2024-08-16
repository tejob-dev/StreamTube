<?php
/**
 * Define the myCred-Woocommerce functionality
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

class Streamtube_Core_myCRED_Woocommerce extends Streamtube_Core_myCRED_Base{

    /**
     *
     * Holds settings
     * 
     * @var array
     * 
     */
    public $settings;

    /**
     *
     * Holds WC_Order
     * 
     */
    private $order;

    /**
     *
     * Holds WC_Refund
     * 
     */
    private $refund;

    /**
     *
     * Holds the refund type
     * 
     * @var string
     */
    private $refund_type; // fully or partially

    /**
     *
     * Check if has entry
     * 
     * @var boolean
     */
    private $is_revert = false;

    /**
     *
     * Class contructor
     * 
     * @param array $settings
     *
     * @since 1.1
     * 
     */
    public function __construct( $settings = array() ){
        $this->settings = $settings;
    }

    /**
     *
     * Get order ID
     * 
     * @return int
     */
    private function get_order_id(){
        return $this->order->get_id();
    }    

    /**
     *
     * Get order customer ID
     * 
     * @return int
     */
    private function get_customer_id(){
        return $this->order->get_user_id();
    }

    /**
     *
     * Get refund id
     * 
     * @return int
     */
    private function get_refund_id(){
        return $this->refund->get_id();
    }

    /**
     *
     * Get refund season
     * 
     * @return string
     * 
     */
    private function get_refund_reason(){
        return $this->refund->get_reason();
    }

    /**
     *
     * Get exchange rate from given post ID and Point Type
     * 
     * @param  int $post_id
     * @param  string $ctype   [description]
     * @return int
     * 
     */
    public function get_exchange_rate( $post_id, $ctype = MYCRED_DEFAULT_TYPE_KEY ){
        $rate = (float)get_post_meta( $post_id, '_e_rate_ctype_' . $ctype, true );

        /**
         *
         * Filter the rate
         *
         * @param int $rate
         * @param int $post_id
         * @param $string $ctype
         * 
         */
        return apply_filters( 'streamtube/core/mycred/woocommerce/exchange_rate', $rate, $post_id, $ctype );
    }

    /**
     *
     * Update the exchange rate post meta
     * 
     * @param  int $post_id
     * @return update_post_meta()
     *
     */
    public function update_exchange_rate( $post_id ){

        if ( ! isset( $_POST[ 'points_exchange_rate_nonce' ] ) || 
             ! wp_verify_nonce( $_POST[ 'points_exchange_rate_nonce' ], plugin_dir_path( __FILE__ ) ) ||
             ! current_user_can( 'edit_post', $post_id ) ||
             ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ||
             get_post_type( $post_id ) != 'product'
        ){
            return;
        }

        global $mycred_types;

        foreach ( $mycred_types as $ctype => $value ) {
            update_post_meta( 
                $post_id, 
                '_e_rate_ctype_' . $ctype, 
                (float)$_POST[ '_e_rate_ctype_' . $ctype ]
            );
        }
    }

    /**
     *
     * Update order item meta rate after order has been created
     *
     * Hooked into "woocommerce_checkout_order_processed"
     * 
     */
    public function update_order_item_rate( $order_id, $posted_data, $order ){

        global $mycred_types;

        $items = $order->get_items();

        foreach ( $items as $item ) {

            $product_id = $item->get_product_id();

            foreach ( $mycred_types as $ctype => $value ){
                $rate = $this->get_exchange_rate( $product_id, $ctype );

                if( $rate ){
                    wc_update_order_item_meta( $item->get_id(), '_e_rate_ctype_' . $ctype, $rate );
                }
            }
            
        }        
    }

    /**
     *
     * Add creds
     * 
     * @param string $ctype
     * @param string $amount
     * 
     */
    private function _add_creds( $ctype, $amount = 0 ){

        $amount     = (float)$amount;

        if( ! $amount ){
            return false;
        }

        $mycred  = mycred( $ctype );

        if( $amount < 0 || $this->refund ){
            $reference      = 'refund_product';
            $reference_id   = $this->get_refund_id();

            $entry              = sprintf(
                esc_html__( '%1$s %2$s %3$s for order #%4$s - refund #%5$s store purchase', 'streamtube-core' ),
                ucwords( $this->refund_type ),
                esc_html__( 'Refunded', 'streamtube-core' ),
                $mycred->plural(),
                $this->get_order_id(),
                $reference_id
            );
        }else{
            $reference      = 'purchase_product';
            $reference_id   = $this->get_order_id();

            $entry              = sprintf(
                esc_html__( '%1$s %2$s for order #%3$s store purchase', 'streamtube-core' ),
                $this->is_revert ? esc_html__( 'Reverted', 'streamtube-core' ) : esc_html__( 'Funded', 'streamtube-core' ),
                $mycred->plural(),
                $reference_id
            );            
        }
        
        $user_id        = $this->get_customer_id();

        $data           = array( 
            'ref_type'  => 'product',
            'order_id'  =>  $this->get_order_id()
        );

        if( $this->refund ){
            $data['refund_id'] = $this->get_refund_id();
        }

        if( $this->is_revert ){
            $data['revert'] = 'on';
        }

        if( $amount > 0 && ! $this->is_revert && $mycred->has_entry( $reference, $reference_id, $user_id, $data, $ctype ) ){
            return false;
        }

        return $mycred->add_creds(
            $reference,
            $user_id,
            $amount,
            $entry,
            $reference_id,
            $data,
            $ctype
        );
    }

    private function _revert_creds( $ctype, $amount ){

        $amount  = (float)$amount;

        if( $amount > 0 ){
            $amount = (float)"-{$amount}";
        }

        return $this->_add_creds( $ctype, $amount );
    }

    /**
     *
     * Do exchange real money to points
     * Hooked into "woocommerce_order_status_completed"
     * 
     * @param  int $order_id
     * 
     */
    public function add_funds( $order_id ){

        global $mycred_types;

        $creds = array();

        $this->order = wc_get_order( $order_id );

        // Do not payout if order was paid using points
        if ( $this->order && $this->order->payment_method == 'mycred' ){
            return;
        }

        foreach ( $this->order->get_items() as $item ) {

            foreach ( $mycred_types as $ctype => $value ){

                $creds[ $ctype ] = 0;

                $rate = (float)wc_get_order_item_meta( $item->get_id(), '_e_rate_ctype_' . $ctype );

                if( $rate ){
                    $creds[ $ctype ] = $item->get_total()/$rate;
                }
            }
        }

        if( $creds ){
            foreach ( $creds as $ctype => $amount ) {
                $this->_add_creds( $ctype, $amount );
            }
        }
    }

    public function refunds( $order_id, $refund_id ){

        global $mycred_types;

        $creds          = array();

        $this->order    = wc_get_order( $order_id );

        $this->refund   = new WC_Order_Refund( $refund_id );

        foreach ( $this->refund->get_items() as $item ) {

            $item_line_id = wc_get_order_item_meta( $item->get_id(), '_refunded_item_id', true );

            if( $item_line_id ){
                foreach ( $mycred_types as $ctype => $value ){

                    $creds[ $ctype ] = 0;

                    $rate = (float)wc_get_order_item_meta( $item_line_id, '_e_rate_ctype_' . $ctype );

                    if( $rate ){
                        $creds[ $ctype ] = $item->get_total()/$rate;
                    }
                }  
            }
        }

        if( $creds ){
            foreach ( $creds as $ctype => $amount ) {
                $this->_revert_creds( $ctype, $amount );
            }
        } 
    }

    /**
     *
     * Fully Refund points
     * Hooked into "woocommerce_order_fully_refunded"
     * 
     * @param  int $order_id
     * @param  int $refund_id
     */
    public function fully_refund( $order_id, $refund_id ){

        $this->refund_type = 'fully';

        return $this->refunds( $order_id, $refund_id );
    }

    /**
     *
     * Partial refund
     *
     * Hooked int "woocommerce_order_partially_refunded"
     * 
     * @param  int $order_id
     * @param  int $refund_id
     * 
     */
    public function partially_refunded( $order_id, $refund_id ){

        $this->refund_type = 'partially';

        return $this->refunds( $order_id, $refund_id );      
    }

    /**
     *
     * Hooked into "woocommerce_delete_order_refund"
     * 
     * @param  int $refund_id
     * 
     */
    public function revert_funds_after_refund_deleted( $refund_id, $order_id = 0 ){

        global $wpdb;

        if( ! $order_id ){
            return $refund_id;
        }

        $this->order = wc_get_order( $order_id );

        $results = $wpdb->get_results(
            $wpdb->prepare(
                "
                    SELECT creds, ctype FROM {$wpdb->prefix}myCRED_log
                    WHERE ref = %s AND ref_id = %d
                ",
                'refund_product',
                $refund_id
            )
        );

        if( $results ){

            $this->is_revert = true;

            foreach ( $results as $result ) {
                $this->_add_creds( $result->ctype, abs( $result->creds ) );
            }
        }
    }

    /**
     *
     * Add "[StreamTube] myCred" tab
     * 
     * @param array $tabs
     * 
     */
    public function filter_product_data_tab( $tabs ){
        $tabs['points_exchange'] = array(
            'label'     => esc_html__( 'Points Exchange', 'streamtube-core' ),
            'target'    => 'points_exchange',
            'class'     => array('show_if_simple')
        );

        return $tabs;
    }

    /**
     *
     * The tab content
     * 
     */
    public function points_exchange_tab_content(){
        global $post, $mycred_types;

        ?>
        <div id="points_exchange" class="panel woocommerce_options_panel">

            <div class="exchange-rates">

                <div class="alert alert-info"><p><?php printf(
                    esc_html__( 'Exchange rates are calculated based on %s', 'streamtube-core' ),
                    get_woocommerce_currency()
                );?></p></div>

                <?php foreach ( $mycred_types as $key => $value ):

                    $mycred = mycred( $key );

                    printf(
                        '<div class="point-type point-type-%s">',
                        esc_attr( $key )
                    );

                        woocommerce_wp_text_input( array( 
                            'label'             =>  sprintf( '1 %s = ', $mycred->singular() ),
                            'id'                =>  '_e_rate_ctype_' . $key,
                            'value'             =>  $this->get_exchange_rate( $post->ID, $key ),
                            'desc_tip'          =>  true,
                            'description'       =>  sprintf(
                                esc_html__( 'The exchange rate is in %s, and 0 means no exchange', 'streamtube-core' ),
                                get_woocommerce_currency()
                            )
                        ) );

                    echo '</div>';

                endforeach;?>

            </div>
        </div>
        <?php

        wp_nonce_field( plugin_dir_path( __FILE__ ), 'points_exchange_rate_nonce' );
    }
}