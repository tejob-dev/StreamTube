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

class Streamtube_Core_myCRED_Transfers extends Streamtube_Core_myCRED_Base{

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
     * Check if addon activated
     * 
     * @return boolean
     *
     * @since 1.1
     * 
     */
    public function is_activated(){
        return class_exists( 'myCRED_Transfer_Module' );
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
            'donate_roles'  =>  ''
        ) );

        $roles = $this->settings['donate_roles'];

        if( trim( $roles ) == "" ){
            return true;
        }

        if( ! $recipient->roles ){
            return false;
        }

        $roles = array_map( 'trim', explode(',', $this->settings['donate_roles'] ) );

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
        if( $this->settings['donate'] == 'verified' && ! $this->User->is_verified( $recipient->ID ) ){
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
        if( $this->settings['donate_point_type'] ){
            return mycred( $this->settings['donate_point_type'] );
        }

        return false;
    }    

    /**
     *
     * Transfers points
     * 
     * @return array|WP_Error
     *
     * @since 1.1
     * 
     */
    public function transfers_points(){

        $errors = new WP_Error();

        if( ! $this->is_activated() || ! isset( $_POST ) || ! isset( $_POST['token'] ) || ! is_user_logged_in() ){
            return new WP_Error(
                'invalid_requested',
                esc_html__( 'Invalid Requested', 'streamtube-core' )
            );
        }

        $data = wp_parse_args( $_POST, array(
            'token'                 => '',
            'recipient_id'          => 0,
            'amount'                => 1
        ) );

        $data = array_merge( $data, array(
            'reference'     => 'donation',
            'ctype'         =>  $this->settings['donate_point_type'] ? $this->settings['donate_point_type'] : MYCRED_DEFAULT_TYPE_KEY
        ) );

        $mycred     = mycred( $data['ctype'] );

        $recipient  = get_userdata( $data['recipient_id'] );

        if( ! $recipient ){
            $errors->add(
                'recipient_not_found',
                esc_html__( 'Recipient was not found', 'streamtube-core' )
            );            
        }

        if( ! $this->check_recipient_roles( $recipient ) ){
            $errors->add(
                'recipient_not_allowed',
                esc_html__( 'Recipient is not allowed to receive donation', 'streamtube-core' )
            );              
        }

        if( (int)$data['amount'] < (int)$this->settings['donate_min_points'] ){
            $errors->add(
                'min_points',
                sprintf(
                    esc_html__( 'Minimum is %s %s', 'streamtube-core' ),
                    $this->settings['donate_min_points'],
                    $mycred->name['plural'],
                )
            );
        }

        if( ! $this->need_verified( $recipient ) ){
            $errors->add(
                'unverified_recipient',
                sprintf(
                    esc_html__( 'Sorry %s is unverified', 'streamtube-core' ),
                    $recipient->display_name
                )
            );
        }

        /**
         *
         * Filter error
         * 
         */
        $errors = apply_filters( 'streamtube/core/mycred/donation/errors', $errors, $recipient, $data );        

        if( $errors->get_error_code() ){
            return $errors;
        }        

        /**
         *
         * Filter data before sending
         * 
         */
        $data = apply_filters( 'streamtube/core/mycred/donated_points_data', $data );

        extract( $data );

        $request = compact(
            'token',
            'recipient_id',
            'amount',
            'ctype',
            'reference'
        );

        add_filter( 'mycred_get_transfer_settings', function( $settings ){
            $settings['logs'] = array(
                'sending'   => esc_html__( 'Donation of %plural% to %display_name%', 'streamtube-core' ),
                'receiving' => esc_html__( 'Donation of %plural% from %display_name%', 'streamtube-core' )
            );
            return $settings;
        },10 );

        $mycred_transfer = new myCRED_Transfer();

        $results = mycred_new_transfer( array_merge( $request, array(
            'transfered_attributes' =>  $mycred_transfer->encode( json_encode( $request ) )
        ) ), serialize( $_POST ) );

        if( is_string( $results ) ){

            $messages = apply_filters( 'mycred_transfer_messages', array(
                'completed' => esc_html__( 'Transaction completed.', 'streamtube-core' ),
                'error_1'   => esc_html__( 'Security token could not be verified. Please contact your site administrator!', 'streamtube-core' ),
                'error_2'   => esc_html__( 'Communications error. Please try again later.', 'streamtube-core' ),
                'error_3'   => esc_html__( 'Recipient not found. Please try again.', 'streamtube-core' ),
                'error_4'   => esc_html__( 'Transaction declined by recipient.', 'streamtube-core' ),
                'error_5'   => esc_html__( 'Incorrect amount. Please try again.', 'streamtube-core' ),
                'error_6'   => esc_html__( 'This myCRED Add-on has not yet been setup! No transfers are allowed until this has been done!', 'streamtube-core' ),
                'error_7'   => esc_html__( 'Insufficient Funds. Please try a lower amount.', 'streamtube-core' ),
                'error_8'   => esc_html__( 'Transfer Limit exceeded.', 'streamtube-core' ),
                'error_9'   => esc_html__( 'Communications error. Please try again later.', 'streamtube-core' ),
                'error_10'  => esc_html__( 'The selected point type can not be transferred.', 'streamtube-core' ),
                'error_11'  => esc_html__( 'Selected recipient ain\'t allowed by admin.', 'streamtube-core' ),
            ) );

            $message = array_key_exists( $results , $messages ) ? $messages[ $results ] : $results;

            return new WP_Error(
                $results,
                sprintf(
                    esc_html__( 'Error: %s', 'streamtube-core' ),
                    apply_filters( 'streamtube/core/mycred/donate_points_failed_message', $message, $messages, $results )
                )
            );
        }

        /**
         *
         * Fires after points sent
         *
         * @param array $results
         *
         * @sine 1.0.9
         * 
         */
        do_action( 'streamtube/core/mycred/donated_points', $results, $recipient, $data );

        $message = sprintf(
            esc_html__( 'You have sent %s %s to %s successfully.', 'streamtube-core' ),
            $results['amount'],
            $mycred->name['plural'],
            $recipient->display_name
        );

        $message = apply_filters( 'streamtube/core/mycred/donate_points_success_message', $message, $results );

        return array(
            'data'      =>  $results,
            'message'   =>  $message
        );        
    }

    /**
     *
     * AJAX Transfer Points handler
     * 
     * @since 1.0.9
     */
    public function ajax_transfers_points(){

        check_ajax_referer( '_wpnonce' );

        if( ! $this->is_activated() || ! $this->settings['donate'] ){
            return;
        }        

        $results = $this->transfers_points();

        if( is_wp_error( $results ) ){
            wp_send_json_error( $results );
        }

        wp_send_json_success( $results );
    }

    /**
     *
     * The Transfer (Donate) Points button
     * 
     * @since 1.0.9
     */
    public function button_donate(){

        $recipient_id = $this->get_recipient_id();

        if( ! $this->is_activated() 
            || ! $this->settings['donate'] 
            || ! $recipient_id 
            || ( is_user_logged_in() && get_current_user_id() == $recipient_id ) ){
            return;
        }

        $recipient = get_userdata( $recipient_id );        

        if( ! $this->need_verified( $recipient ) || ! $this->check_recipient_roles( $recipient ) ){
            return;
        }

        $enable = apply_filters( 'streamtube/core/mycred/button_donate', true, $recipient );

        if( ! $enable ){
            return;
        }

        $args = array(
            'button'            =>  esc_html__( 'Donate', 'streamtube-core' ),
            'button_icon'       =>  'icon-dollar',
            'button_classes'    =>  array( 
                'btn', 
                'btn-sm',
                'btn-' . $this->settings['donate_button_style'],
                'px-4', 
                'shadow-none', 
                'd-flex', 
                'align-items-center', 
                'justify-content-center',
                'gap-1',
                'd-block',
                'w-100'
            )
        );

        if( $this->settings['donate_button_icon'] ){
            $args['button_icon'] = $this->settings['donate_button_icon'];
        }

        /**
         *
         * Filter the button args
         * 
         * @var array $args
         */
        $args = apply_filters( 'streamtube/core/mycred/button_donate/args', $args );

        load_template( 
            untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/public/button-donate.php', 
            false,
            $args
        );
    }

    /**
     *
     * Load the modal donate
     * 
     */
    public function modal_donate(){

        if( did_action( 'streamtube/core/mycred/button_donate/after' ) ){
            load_template( 
                untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/public/modal-donate.php', 
                true,
                array(
                    'modal_title'   =>  sprintf(
                         esc_html__( 'Donate %s', 'streamtube-core' ),
                         $this->get_ctype()->plural()
                    )
                )
            );
        }
    }  
}