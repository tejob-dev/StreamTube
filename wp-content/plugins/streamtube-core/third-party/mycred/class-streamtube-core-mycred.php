<?php
/**
 * Define the myCred functionality
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

require_once trailingslashit( plugin_dir_path( __FILE__ ) ) . 'class-streamtube-core-mycred-base.php';
require_once trailingslashit( plugin_dir_path( __FILE__ ) ) . 'short-functions.php';

class Streamtube_Core_myCRED extends Streamtube_Core_myCRED_Base{

    /**
     *
     * Holds the Sell Content addon
     * 
     * @var object
     *
     * @since 1.1
     * 
     */
    public $sell_content;

    /**
     *
     * Holds the Buy Cred addon
     * 
     * @var object
     *
     * @since 1.1
     * 
     */
    public $buy_cred;

    /**
     *
     * Holds the Casg Cred addon
     * 
     * @var object
     *
     * @since 1.1
     * 
     */
    public $cash_cred;

    /**
     *
     * Holds the Transfers addon
     * 
     * @var object
     *
     * @since 1.1
     * 
     */
    public $transfers;

    /**
     *
     * Holds the Gift addon
     * 
     * @var object
     *
     * @since 1.1
     * 
     */
    public $gifts;

    /**
     *
     * Holds the woocommerce addon
     * 
     * @var object
     *
     * @since 1.1
     * 
     */
    public $woocommerce;    

    /**
     *
     * Holds the default settings
     * 
     * @var array
     *
     * @since 1.1
     * 
     */
    protected $settings = array(
        'donate'                        =>  '',
        'donate_roles'                  =>  '',
        'donate_min_points'             =>  1,
        'donate_point_type'             =>  '',
        'donate_button_icon'            =>  'icon-dollar',
        'donate_button_style'           =>  'danger',
        'donate_user_balance'           =>  '',
        'gift'                          =>  '',
        'gift_roles'                    =>  '',
        'gift_point_type'               =>  'mycred_default',
        'gift_button_icon'              =>  'icon-gift',
        'gift_amounts'                  =>  '10,20,30,40,50,60',
        'gift_amounts_column'           =>  4,
        'gift_gateway'                  =>  '',
        'buy_points_page'               =>  '',
        'sell_video_content'            =>  '',
        'sell_post_content'             =>  '',
        'author_driven_pricing'         =>  '',
        'sell_content_verified_user'    =>  '',
        'show_user_grid_total_creds'    =>  'on'
    );

    /**
     *
     * Class contructor
     *
     * @since 1.1
     * 
     */
    public function __construct(){

        $this->load_dependencies();

        $this->settings = $this->get_settings();

        if( ! $this->settings['show_user_grid_total_creds'] ){
            add_action( 'init', array( $this , 'remove_user_grid_total_creds' ) );
        }

        $this->sell_content = new Streamtube_Core_myCRED_Sell_Content( $this->settings );

        $this->buy_cred = new Streamtube_Core_myCRED_Buy_CRED( $this->settings );

        $this->cash_cred = new Streamtube_Core_myCRED_Cash_Cred( $this->settings );

        $this->transfers = new Streamtube_Core_myCRED_Transfers( $this->settings );

        $this->gifts = new Streamtube_Core_myCRED_Gift( $this->settings );

        $this->woocommerce = new Streamtube_Core_myCRED_Woocommerce( $this->settings );
    }

    /**
     *
     * Load the required dependencies for this plugin.
     * 
     * @since 1.1
     */
    private function load_dependencies(){

        $this->include_file( 'class-streamtube-core-mycred-sell-content.php' );

        $this->include_file( 'class-streamtube-core-mycred-buy-cred.php' );

        $this->include_file( 'class-streamtube-core-mycred-cash-cred.php' );

        $this->include_file( 'class-streamtube-core-mycred-transfers.php' );

        $this->include_file( 'class-streamtube-core-mycred-gift.php' );

        $this->include_file( 'class-streamtube-core-mycred-woocommerce.php' );

        $this->include_file( 'class-streamtube-core-mycred-widget-buy-points.php' );

        $this->include_file( 'class-streamtube-core-mycred-hook-watch-video.php' );

        $this->include_file( 'class-streamtube-core-mycred-hook-like-post.php' );
    }

    /**
     *
     * Get settings
     * 
     * @return array
     *
     * @since 1.1
     * 
     */
    public function get_settings( $setting = '', $default = '' ){

        $this->settings = array_merge( $this->settings, array(
            'donate_point_type'  =>  defined( 'MYCRED_DEFAULT_TYPE_KEY' ) ? MYCRED_DEFAULT_TYPE_KEY : ''
        ) );

        $settings = get_option( 'plugin_mycred' );

        if( ! $settings || ! is_array( $settings ) ){
            $settings = array();
        }

        $settings = wp_parse_args( $settings, $this->settings );

        if( $setting ){

            if( array_key_exists( $setting , $settings ) ){
                return $settings[ $setting ];
            }

            return $default;
        }

        return $settings;
    }

    /**
     *
     * Get Buy Points URL
     * 
     * @return string
     *
     * @since 1.0.9
     * 
     */
    public function get_buy_points_page( $permalink = true ){

        $page = '';

        $maybe_page_id = $this->settings['buy_points_page'];

        if( $maybe_page_id && get_post_status( $maybe_page_id ) == 'publish' ){
            if( $permalink ){
                $page = get_permalink( $maybe_page_id );
            }else{
                $page = (int)$maybe_page_id;
            }
        }

        /**
         *
         * @since 1.0.9
         * 
         */
        return apply_filters( 'streamtube/core/mycred/buy_points_page', $page, $maybe_page_id );
    }    

    /**
     *
     * Add more references
     * 
     */
    public function filter_references( $references ){
        return array_merge( $references, array(
            'donation'  =>  esc_html__( 'Donation', 'streamtube-core' )
        ) );
    }

    /**
     *
     * Filter Transaction table row.
     *
     * @since 1.1
     * 
     */
    public function filter_log_row_classes( $classes, $entry ){
        return array_merge( $classes, array( 'bg-white' ) );
    }

    /**
     *
     * Filter log username
     * 
     * @param  string $content 
     * @param  int $user_id
     * @param  object $log_entry
     * @return string $content 
     *
     * @since 1.1
     * 
     */
    public function filter_mycred_log_username( $content, $user_id, $log_entry ){

        if( is_admin() ){
            return $content;
        }

        $user = get_user_by( 'ID', $user_id );

        if( ! $user ){
            return esc_html__( 'Deleted User', 'streamtube-core' );
        }

        return sprintf(
            '<a class="text-body fw-bold text-decoration-none" href="%s" target="_blank"><span>%s</span></a>',
            esc_url( get_author_posts_url( $user->ID ) ),
            $user->display_name
        );
    }

    /**
     *
     * Filter log entry content
     * Remove player and user IDs
     * 
     */
    public function filter_mycred_log_entry( $content, $creds, $log_entry ){
        return preg_replace( '/\(player_\d+-user_\d+\)/' , '', $content );
    }

    /**
     *
     * Show user dropdown balances
     * 
     */
    public function show_user_dropdown_profile_balances(){
        $this->load_template( 'user-balances.php', false, array(
            'columns'   =>  apply_filters( 'streamtube/core/mycred/balances/columns', 2, 'dropdown' )
        ) );
    }

    /**
     *
     * Show user dashboard balances
     * 
     */
    public function show_user_balances(){
        $this->load_template( 'user-balances.php', false, array(
            'columns'   =>  apply_filters( 'streamtube/core/mycred/balances/columns', 2, 'dashboard' )
        ) );        
    }

    /**
     *
     * Profile menu
     * 
     * @param array
     * 
     */
    public function add_profile_menu( $items ){
        if( $this->buy_cred->is_activated() && "" != $url = $this->get_buy_points_page() ){
            $items['buy-points'] = array(
                'title'     =>  esc_html__( 'Buy Points', 'streamtube-core' ),
                'icon'      =>  'icon-diamond',
                'url'       =>  $url,
                'cap'       =>  'read',
                'private'   =>  true,
                'priority'  =>  30
            );            
        }

        return $items;
    }

    /**
     *
     * Add dashboard Points menu
     * 
     * @param array
     *
     * @since 1.1
     */
    public function add_dashboard_menu( $items ){

        $items['transactions'] = array(
            'title'     =>  esc_html__( 'Transactions', 'streamtube-core' ),
            'icon'      =>  'icon-arrows-cw',
            'callback'  =>  function(){
                $this->load_template( 'transactions.php' );
            },
            'parent'    =>  'dashboard',
            'cap'       =>  'read',
            'priority'  =>  40
        );

        if( $this->cash_cred->is_activated() ){
            $items[ $this->cash_cred::ENDPOINT ] = array(
                'title'     =>  esc_html__( 'Withdrawal', 'streamtube-core' ),
                'icon'      =>  'icon-money',
                'callback'  =>  function(){
                    $this->load_template( 'withdrawal.php' );
                },
                'parent'    =>  'dashboard',
                'cap'       =>  'read',
                'priority'  =>  50
            );            
        }

        return $items;
    }

    /**
     *
     * Elementor Buy Points Form Widget Register
     *
     * @since 1.1
     */
    public function widgets_registered( $widget_manager ){
        $this->include_file( 'class-streamtube-core-mycred-elementor-buy-points.php' );
    }

    /**
     *
     * Filter the Cancel Checkout button
     * Redirect to current video post if the submit form has been made from single video post
     * 
     * @param  string $content
     * @return string $content
     *
     * @since 1.0.9
     * 
     */
    public function filter_cancel_checkout( $content ){

        $url = is_singular() ? get_permalink() : home_url('/');

        if( isset( $_SERVER['HTTP_REFERER'] ) ){
            $url = $_SERVER['HTTP_REFERER'];
        }

        $content = sprintf(
            '<div class="cancel mt-4"><a class="btn cancel-url btn-outline-secondary w-100" href="%s">%s</a></div>',
            $url,
            esc_html__( 'Cancel Purchase', 'streamtube-core' )
        );

        return $content;
    }       

    /**
     *
     * Redirect unlogged in users to login page when visiting Buy Points page
     * 
     * @since 1.0.9
     */
    public function redirect_buy_points_page(){
        $buy_points_page = $this->get_buy_points_page( false );

        if( ! is_user_logged_in() && $buy_points_page && is_page( $buy_points_page ) ){
            wp_redirect( wp_login_url( get_permalink( $buy_points_page ) ) );
            exit;
        }
    }     

    /**
     *
     * Remove the user grid total creds content based on customizer settings
     * 
     */
    public function remove_user_grid_total_creds(){
        remove_action( 
            'streamtube/core/user/card/info/item', 
            'streamtube_mycred_load_user_card_points_count',
            20,
            1
        );
    }

}