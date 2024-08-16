<?php
/**
 * Elementor
 *
 * @link       https://themeforest.net/user/phpface
 * @since      1.1.7
 *
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 */

/**
 *
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */

if( ! defined('ABSPATH' ) ){
    exit;
}

class StreamTube_Core_Download_File{

    /**
     *
     * Holds the meta field name
     *
     * @since 1.0.9
     * 
     */
    const META_KEY  = 'download_video';

    private $Post;

    public function __construct(){
        $this->Post = new Streamtube_Core_Post();
    }

    /**
     *
     * Get settings
     * 
     * @return array
     *
     *
     * @since 1.1.7
     * 
     */
    public function get_settings(){

        $settings = array(
            'perm'              =>  get_option( self::META_KEY, '' ),
            'type'              =>  'direct',
            'button_icon'       =>  'icon-download',
            'button_label'      =>  esc_html__( 'Download', 'streamtube-core' ),
            'file_url'          =>  '',
            'count'             =>  0,
            'download'          =>  false
        );

        /**
         *
         * @since 1.1.7
         * 
         */
        return apply_filters( 'streamtube/core/video/download_files_settings', $settings );
    }

    /**
     *
     * Check if video is downloadable, self-hosted file only.
     * 
     * @return true if is downloadable, otherwise is false
     *
     * @since 1.1.7
     * 
     */
    public function is_downloadable(){

        $is_downloadable    = false;

        $maybe_attachment   = $this->Post->get_source();

        if( ! $maybe_attachment ){
            return $is_downloadable;
        }

        if( wp_attachment_is( 'video', $maybe_attachment ) || wp_attachment_is( 'audio', $maybe_attachment ) ){
            $is_downloadable = true;
        }

        return apply_filters( 'streamtube/core/video/is_downloadable', $is_downloadable, $maybe_attachment );
    }

    /**
     *
     * Check if user can download video file
     * 
     * @return true if can, otherwise is false
     *
     * @since 1.1.7
     * 
     */
    public function can_user_download(){

        $can        = true;

        $settings   = $this->get_settings();

        if( ! $settings['perm'] ){
            // Return false if feature isn't enabled yet.
            return false;
        }

        if( $settings['perm'] == 'member' && ! is_user_logged_in() ){
            return false;
        }

        // Always return true if current user is admin or owner
        if( Streamtube_Core_Permission::moderate_posts() || Streamtube_Core_Permission::is_post_owner( get_the_ID() ) ){
            return true;
        }

        return apply_filters( 'streamtube/core/video/can_user_download', $can );
    }

    /**
     *
     * Get download endpoint URL
     * 
     * @return true
     *
     * @since 1.1.7
     * 
     */
    public function get_file_url(){

        $post_id = get_the_ID();

        $url = add_query_arg( array(
            'download'  =>  '1'
        ), get_permalink( $post_id ) );

        return apply_filters( 'streamtube/core/video/download_file_url', $url, $post_id );
    }

    /** 
     *
     * Get file path
     * 
     */
    public function get_file_path(){
        return get_attached_file( $this->Post->get_source( get_the_ID() ) );
    }

    /**
     *
     * process download file if download param found    
     *
     * @since 1.1.7
     * 
     */
    public function process_download(){

        if( ! is_singular( 'video' ) ){
            return;
        }

        if( ! isset( $_GET['download'] ) ){
            return;
        }

        if( ! $this->can_user_download() || ! $this->is_downloadable() ){
            return;
        }

        $file       = $this->get_file_path();
        $filetype   = wp_check_filetype( $file );
        $filename   = sanitize_file_name( get_the_title() )  . '.' . $filetype['ext'];

        /**
         *
         * Fires before downloading file
         *
         * @since 1.1.7
         * 
         */
        do_action( 'streamtube/core/video/before_download', $file, $filename );

        header('Content-Description: File Transfer');
        header('Content-Type: ' . $filetype['type'] );
        header('Content-Disposition: attachment; filename=' . $filename ); 
        header('Content-Transfer-Encoding: binary');
        header('Connection: Keep-Alive');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize( $file ) );

        ob_clean();
        flush();
        readfile($file);
        exit();
    }

    /**
     *
     * The Download button template
     * 
     */
    public function button_download(){

        if( ! $this->can_user_download() || ! $this->is_downloadable() ){
            return;
        }

        $settings = $this->get_settings();

        $settings['file_url'] = $this->get_file_url();

        if( ! $settings['file_url'] ){
            return false;
        }

        $settings['filename'] = basename( $this->get_file_path() );

        streamtube_core_load_template( 'video/button-download.php', true, $settings );
    }
}