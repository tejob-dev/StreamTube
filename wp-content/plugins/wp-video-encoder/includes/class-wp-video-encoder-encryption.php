<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 *
 * @link       https://themeforest.net/user/phpface
 * @since      1.0.0
 *
 * @package    WP_Video_Encoder
 * @subpackage WP_Video_Encoder/includes
 */

/**
 *
 * @package    WP_Video_Encoder
 * @subpackage WP_Video_Encoder/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */
class WP_Video_Encoder_Encryption {

    protected $endpoint             =   '';

    protected $encrypt_file_url     =   '';

    protected $encrypt_file_path    =   '';

    protected $encrypt_iv           =   '';

    protected $settings             =   '';

    const ENCRYPT_FILE_INFO         =   'encrypt_file_info';

    /**
     *
     * Class contructor
     * 
     * @param array $args
     */
    public function __construct( $args = array() ){

        $this->settings = WP_Video_Encoder_Settings::get_settings();

        $args = wp_parse_args( $args, array(
            'endpoint'              =>  self::ENCRYPT_FILE_INFO,
            'encrypt_file_url'      =>  $this->settings['hls_encrypt_file_url'],
            'encrypt_file_path'     =>  $this->settings['hls_encrypt_file_path'],
            'encrypt_iv'            =>  $this->settings['hls_encrypt_iv']
        ) );

        extract( $args );

        $this->encrypt_file_url     =   $encrypt_file_url;

        $this->encrypt_file_path    =   $this->get_file_path( $encrypt_file_path );

        $this->encrypt_iv           =   $encrypt_iv;

        if( ! empty( $endpoint ) ){
            $this->endpoint             =   $endpoint;    
        }
        else{
            $this->endpoint             =   self::ENCRYPT_FILE_INFO;
        }
        

    }

    /**
     *
     * Get encrypt file path from URL
     * 
     * @param  string $encrypt_file_path
     * @return string
     *
     * @since 1.1
     * 
     */
    protected function get_file_path( $encrypt_file_path ){

        if( ! $encrypt_file_path ){
            return false;
        }

        $maybe_attachment = attachment_url_to_postid( $encrypt_file_path );

        if( ! $maybe_attachment ){
            return false;
        }

        return get_attached_file( $maybe_attachment );
    }


    /**
     *
     * Check if valid key info file
     * 
     * @return true|false
     *
     * @since 1.1
     * 
     */
    public function valid_file_key_info(){

        if( ! $this->endpoint || ! $this->encrypt_file_url || ! $this->encrypt_file_path ){
            return false;
        }

        return true;
    }

    /**
     *
     * Add endpoint
     *
     * @since 1.1
     * 
     */
    public function add_endpoint(){
        add_rewrite_endpoint( $this->endpoint , EP_ALL );
    }

    /**
     *
     * get endpoint
     *
     * @since 1.1
     * 
     */
    public function get_endpoint(){
        return home_url( $this->endpoint );
    }

    /**
     *
     * Load keyinfo file
     * 
     * @return output string
     *
     * @since 1.1
     * 
     */
    public function load_encryption_file_info(){

        global $wp_query;
     
        if ( isset( $wp_query->query_vars[ $this->endpoint ] ) ){

            $key_info = $this->valid_file_key_info();

            if( $key_info ){
                echo $this->encrypt_file_url . "\n";
                echo $this->encrypt_file_path . "\n";
                echo $this->encrypt_iv;
                exit;
            }
        }   
    }

}