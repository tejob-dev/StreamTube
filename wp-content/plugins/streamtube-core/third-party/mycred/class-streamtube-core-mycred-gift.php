<?php
/**
 * Define the Gift functionality
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

class Streamtube_Core_myCRED_Gift extends Streamtube_Core_myCRED_Buy_CRED{

    /**
     *
     * Holds settings
     * 
     * @var array
     *
     * @since 1.1
     * 
     */
    public $settings;

    public $User;

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

        $this->User     = new Streamtube_Core_User();
    }    

    /**
     *
     * Check recipient roles
     *
     * @param WP_User $recipient
     * 
     * @return boolean
     * 
     */
    public function check_recipient_roles( $recipient ){

        $this->settings = wp_parse_args( $this->settings, array(
            'gift_roles'  =>  ''
        ) );

        $roles = $this->settings['gift_roles'];

        if( trim( $roles ) == "" ){
            return true;
        }

        if( ! $recipient->roles ){
            return false;
        }

        $roles = array_map( 'trim', explode(',', $this->settings['gift_roles'] ) );

        if( ! $roles ){
            return true;
        }

        if( array_intersect( $recipient->roles, $roles ) ){
            return true;
        }

        for ( $i = 0;  $i < count( $roles );  $i++ ) { 
            if( user_can( $recipient, $roles[$i] ) ){
                return true;
            }
        }

        return false;
    }

    /**
     *
     * Check if verified user is allowed
     * 
     * @param WP_User $recipient
     * 
     */
    public function need_verified( $recipient ){
        if( $this->settings['gift'] == 'verified' && ! $this->User->is_verified( $recipient->ID ) ){
            return false;
        }        

        return true;
    }

    /**
     *
     * Get recipient id
     * 
     * @return int|false
     * 
     */
    public function get_recipient_id(){

        if( is_singular() ){
            return $GLOBALS['post']->post_author;
        }

        if( is_author() ){
            return get_queried_object_id();
        }        

        return false;
    }

    /**
     *
     * Get mycred point type
     * 
     * @return object
     */
    public function get_ctype(){

        $ctype = $this->settings['gift_point_type'];

        if( ! $ctype ){
            $ctype = 'mycred_default';
        }

        return mycred( $ctype );
    }

    /**
     *
     * The Gift button
     * 
     */
    public function button_gift(){

        $recipient_id = $this->get_recipient_id();

        if( ! $this->is_activated() || ! $this->settings['gift'] || ! $recipient_id ){
            return;
        }

        $recipient = get_userdata( $recipient_id );        

        if( ! $this->need_verified( $recipient ) || ! $this->check_recipient_roles( $recipient ) ){
            return;
        }

        $enable = apply_filters( 'streamtube/core/mycred/button_gift', true, $recipient );

        if( ! $enable ){
            return;
        }

        $args = array(
            'classes'       =>  '',
            'title'         => sprintf(
                 esc_html__( 'Send %s as a Gift', 'streamtube-core' ),
                 $this->get_ctype()->plural()
            ),
            'label'         =>  '',
            'modal'         =>  ! is_user_logged_in() ? 'modal-login' : 'modal-gift',
            'icon'          =>  $this->settings['gift_button_icon']
        );

        /**
         *
         * Filter the button args
         * 
         * @var array $args
         */
        $args = apply_filters( 'streamtube/core/mycred/button_gift/args', $args );

        load_template( 
            trailingslashit( plugin_dir_path( __FILE__ ) ) . 'public/button-gift.php',
            true,
            $args
        );
    }

    /**
     *
     * Load the modal gift
     * 
     */
    public function modal_gift(){

        if( did_action( 'streamtube/core/mycred/gift_button_loaded' ) ){

            $args = array(
                'modal_title'       =>  sprintf(
                     esc_html__( 'Send %s as a Gift to %s', 'streamtube-core' ),
                     $this->get_ctype()->plural(),
                     get_userdata( $this->get_recipient_id() )->display_name
                ),
                'title'             =>  '',
                'gift_to'           =>  $this->get_recipient_id(),
                'amount'            =>  $this->settings['gift_amounts'],
                'gateway'           =>  $this->settings['gift_gateway'],
                'ctype'             =>  $this->settings['gift_point_type'] ? $this->settings['gift_point_type'] : 'mycred_default',
                'amounts_column'    =>  $this->settings['gift_amounts_column']
            );

            /**
             *
             * Filter modal instance
             * 
             */
            $args = apply_filters( 'streamtube/core/mycred/moda/gift/instance', $args, $this->settings );

            load_template( 
                untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/public/modal-gift.php', 
                true,
                $args
            );
        }
    }

    /**
     *
     * The gift widget content
     * Call `Streamtube_Core_myCRED_Widget_Buy_Points` widget
     * Can be replaced with other widget/form
     * 
     */
    public function gift_widget_content( $args = array() ){
        the_widget( 'Streamtube_Core_myCRED_Widget_Buy_Points', $args );
    }
}