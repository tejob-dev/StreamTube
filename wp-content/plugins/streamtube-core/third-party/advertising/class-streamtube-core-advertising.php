<?php
/**
 * Define the Advertising functionality
 *
 *
 * @link       https://themeforest.net/user/phpface
 * @since      1.3
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

class Streamtube_Core_Advertising{

    /**
     *
     * Holds the admin object
     * 
     * @var object
     *
     * @since 2.0
     * 
     */
    public $admin;    

    /**
     *
     * Holds the Ad Tag object
     * 
     * @var object
     *
     * @since 2.0
     * 
     */
    public $ad_tag;

    /**
     *
     * Holds the Ad Schedule object
     * 
     * @var object
     *
     * @since 2.0
     * 
     */
    public $ad_schedule;

    public $settings;

    protected $Post;

    /**
     *
     * Class contructor
     *
     * @since 2.0
     * 
     */
    public function __construct(){

        $this->Post     = new Streamtube_Core_Post();

        $this->settings = $this->get_settings();

        $this->load_dependencies();
    }  

    /**
     *
     * Include file
     * 
     * @param  string $file
     *
     * @since 2.0
     * 
     */
    private function include_file( $file ){
        require_once plugin_dir_path( __FILE__ ) . $file;
    }

    /**
     *
     * Load dependencies
     *
     * @since 2.0
     * 
     */
    public function load_dependencies(){

        $this->include_file( 'class-streamtube-core-advertising-admin.php' );

        $this->admin = new Streamtube_Core_Advertising_Admin();

        $this->include_file( 'class-streamtube-core-advertising-ad-tag.php' );

        $this->ad_tag = new Streamtube_Core_Advertising_Ad_Tag();

        $this->include_file( 'class-streamtube-core-advertising-ad-schedule.php' );

        $this->ad_schedule = new Streamtube_Core_Advertising_Ad_Schedule();
    }

    /**
     *
     * Load Ad scripts
     * 
     */
    private function load_scripts(){
        wp_enqueue_script( 'ima3sdk' );
        wp_enqueue_script( 'videojs-contrib-ads' );
        wp_enqueue_script( 'videojs-ima' );
        wp_enqueue_style( 'videojs-ima' ); 
    }

    /**
     *
     * Get global settings;
     * 
     * @param  string $key
     * 
     */
    public function get_settings( $key = '' ){

        $settings = get_option( 'advertising' );

        if( ! $settings ){
            $settings = array();
        }

        $settings = wp_parse_args( $settings, array(
            'vast_tag_url'      =>  '',
            'visibility'        =>  'overriden',
            'disable_owner'     =>  ''
        ) );

        /**
         *
         * Filter advertising settings
         * 
         * @param array $settings
         * 
         */
        $settings = apply_filters( 'streamtube/core/advertising/settings', $settings );

        if( $key && array_key_exists( $key, $settings ) ){
            return $settings[ $key ];
        }

        return $settings;
    }

    /**
     *
     * Update htaccess file
     * 
     * @since 2.0
     */
    public function update_htaccess(){

        $content = false;

        if( apply_filters( 'streamtube/core/advertising/update_htaccess', true ) === false ){
            return $content;
        }

        if( strpos( $_SERVER['SERVER_SOFTWARE'] , 'nginx' ) !== false ){
            $content = array(
                '<IfModule mod_headers.c>',
                'Header set Access-Control-Allow-Origin "*"',
                'Header set Access-Control-Allow-Credentials true',
                '</IfModule>'
            );
        }

        if( strpos( $_SERVER['SERVER_SOFTWARE'] , 'apache' ) !== false ){
            $content = array(
                'Header set Access-Control-Allow-Origin "*"',
                'Header set Access-Control-Allow-Credentials true'
            );
        }

        if( ! is_multisite() && $content ){

            if( ! function_exists( 'insert_with_markers' ) ){
                require_once( ABSPATH . 'wp-admin/includes/misc.php' );
            }

            $results = insert_with_markers( get_home_path() . '.htaccess', 'Advertising', $content );
        }
    }

    /**
     *
     * 
     * @param  array  $ad_schedules
     * @return array with a random ad
     *
     * @since 2.0
     * 
     */
    private function get_ad_schedule( $ad_schedules = array() ){
        $position = array_rand( $ad_schedules, 1 );

        return $ad_schedules[ $position ];
    }

    /**
     * @param  string $ad_tag_url
     * @since 2.0
     */
    private function get_ad_params( $ad_tag_url ){

        $params = array(
            'adTagUrl'                  =>  $ad_tag_url,
            'showCountdown'             =>  true,
            'forceNonLinearFullSlot'    =>  true,
            'locale'                    =>  get_locale(),
            'vastLoadTimeout'           =>  50000,
            'adLabel'                   =>  esc_html__( 'Advertisement', 'streamtube-core' )
        );

        /**
         *
         * Filter the Ad settings
         *
         * @see https://github.com/googleads/videojs-ima#additional-settings
         * 
         * @since 2.0
         */
        $params = apply_filters( 'streamtube/core/advertising/request_ad/settings', $params );
            
       return $params;
    }

    /**
     *
     * Filter Ads visibility
     * Hide Ads if current user is set in theme option panel
     * 
     */
    public function filter_ads_visibility( $vast_tag_url, $setup, $source ){

        if( ! is_user_logged_in() ){
            return $vast_tag_url;
        }

        $user_id = get_current_user_id();

        if( array_key_exists( 'disable_owner', $this->settings )        && 
            wp_validate_boolean( $this->settings['disable_owner'] )     &&
            Streamtube_Core_Permission::is_post_owner( $setup['mediaid'], $user_id ) ){
            return false;
        }        
        
        $roles = get_userdata( $user_id )->roles;

        if( $roles ){
            for ( $i=0; $i < count( $roles ); $i++) { 
                if( array_key_exists( 'disable_role_' . $roles[$i], $this->settings ) && 
                    wp_validate_boolean( $this->settings[ 'disable_role_' . $roles[$i] ] ) ){
                    return false;
                }
            }
        }

        return $vast_tag_url;
    }

    /**
     *
     * Request Ad and filter player setup params
     * 
     * @param  array $setup
     * @param  string $source
     * @return array $setup
     *
     * @since 2.0
     * 
     */
    public function request_ads( $setup, $source ){
        $vast_tag_url = '';

        // Check if Ad is disabled
        if( $this->Post->is_ad_disabled( $setup['mediaid'] ) || current_user_can( 'no_advertisements' ) ){
            return $setup;
        }

        // Load global ad tag
        if( wp_http_validate_url( $this->settings['vast_tag_url'] ) ){
            $vast_tag_url = $this->settings['vast_tag_url'];
        }

        if( $this->settings['visibility'] == 'overriden' ){
            $ad_schedules       = $this->ad_schedule->get_active_ad_schedules( $setup['mediaid'] );

            if( $ad_schedules ){
                $vast_tag_url    = get_permalink( $this->get_ad_schedule( $ad_schedules ) );    
            }
        }
        
        /**
         *
         * Filter vast tag URL
         * 
         * @param string $vast_tag_url
         * @param array $setup
         * @param string|int $source
         * 
         */
        $vast_tag_url = apply_filters( 'streamtube/core/advertising/vast_tag_url', $vast_tag_url, $setup, $source );

        if( is_string( $vast_tag_url ) && ! empty( $vast_tag_url ) ){
            $setup = array_merge( $setup, array(
                'advertising'   =>  $this->get_ad_params( $vast_tag_url )
            ) );
        }

        if( array_key_exists( 'advertising', $setup ) ){
            $this->load_scripts();
        }

        return $setup;
    }

}