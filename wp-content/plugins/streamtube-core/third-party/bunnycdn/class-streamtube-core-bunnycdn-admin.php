<?php
/**
 * Define the BunnyCDN Admin functionality
 *
 *
 * @link       https://themeforest.net/user/phpface
 * @since      2.1
 *
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 */

/**
 *
 * @since      2.1
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */

if( ! defined('ABSPATH' ) ){
    exit;
}

class Streamtube_Core_BunnyCDN_Admin{

    /**
     *
     * Define advertising admin menu slug
     *
     * @since 1.3
     * 
     */
    const ADMIN_SETTINGS_MENU_SLUG   = 'options-general.php';

    private $settings;

    public function __construct(){
        $this->settings = Streamtube_Core_BunnyCDN_Settings::get_settings();
    }

    public function is_enabled(){
        return $this->settings['enable'] && $this->settings['is_connected'] ? true : false;
    }

    /**
     *
     * Check if Bulk Sync supported
     * 
     * @return boolean
     *
     * @since 2.1
     * 
     */
    public function is_bulk_sync_supported(){
        return ( $this->settings['sync_type'] == 'php_curl' ) ? false : true;
    }    

    /**
     *
     * Unregistered Menu
     * 
     */
    public function unregistered(){
        add_submenu_page( 
            self::ADMIN_SETTINGS_MENU_SLUG, 
            esc_html__( 'Bunny Stream', 'streamtube-core' ), 
            esc_html__( 'Bunny Stream', 'streamtube-core' ), 
            'administrator', 
            'sync-bunnycdn', 
            array( 'Streamtube_Core_License' , 'unregistered_template' ), 
            50
        );
    }

    /**
     *
     * Registered Menu
     *
     * @since 2.1
     * 
     */
    public function registered(){
        add_submenu_page( 
            self::ADMIN_SETTINGS_MENU_SLUG, 
            esc_html__( 'Bunny Stream', 'streamtube-core' ), 
            esc_html__( 'Bunny Stream', 'streamtube-core' ), 
            'administrator', 
            'sync-bunnycdn', 
            array( $this, 'settings_template' ), 
            50
        );      
    }

    /**
     *
     * The settings template
     * 
     * @since 2.1
     */
    public function settings_template(){
        load_template( plugin_dir_path( __FILE__ ) . 'admin/settings.php' );
    }

    /**
     *
     * Add metaboxes
     *
     * @since 2.1
     * 
     */
    public function add_meta_boxes(){
        add_meta_box( 
            'bunnycdn-video-details', 
            esc_html__( 'Bunny Stream Details', 'streamtube-core' ), 
            array( $this , 'video_details' ), 
            array( 'video', 'attachment' ), 
            'advanced', 
            'default'
        );
    }

    /**
     *
     * The Video details box template
     * 
     * @param  WP_Post $post
     *
     * @since 2.1
     */
    public function video_details( $post ){

        $post_id = $post->ID;

        if( $post->post_type == 'video' ){
            $post_id = get_post_meta( $post->ID, 'video_url', true );
        }

        $video_data = get_post_meta( $post_id, '_bunnycdn', true );

        if( ( wp_attachment_is( 'video', $post_id ) || wp_attachment_is( 'audio', $post_id )) && ! empty( $video_data ) ){
            load_template( 
                plugin_dir_path( __FILE__ ) . 'admin/video-details.php', 
                true, 
                compact( 'post_id', 'video_data' ) 
            );
        }else{
            return printf(
                '<p>%s</p>',
                esc_html__( 'No content available', 'streamtube-core' )
            );            
        }
    }

    /**
     *
     * AJAX check videos progress
     * 
     * @since 2.1
     */
    public function ajax_check_videos_progress(){
        
        check_ajax_referer( '_wpnonce' );   

        if( ! $this->is_enabled() ){
            exit;
        }

        $response       = array();
        $attachments    = array();
        $posts          = $_POST['posts'];

        for ( $i = 0; $i < count( $posts ); $i++) {
            if( wp_attachment_is( 'video', $posts[$i] ) || wp_attachment_is( 'audio', $posts[$i] ) ){
                $attachments[] = $posts[$i];
            }
        }

        for ( $i=0; $i < count( $attachments ); $i++) {
            ob_start();

            load_template( 
                plugin_dir_path( __FILE__ ) . 'admin/sync-control.php', 
                false, 
                array(
                    'attachment_id' =>  $attachments[$i]
                )
            );

            $response[ $attachments[$i] ] = ob_get_clean();
        }

        wp_send_json_success( $response );
    }

    /**
     *
     * Run interval check videos progress
     * 
     * @since 2.1
     * 
     */
    public function interval_check_videos_progress(){

        if( ! $this->is_enabled() ){
            return;
        }

        $screen = get_current_screen()->id;

        if( ! in_array( $screen , array( 'edit-video', 'upload' )) ){
            return;
        }

        ?>
        <script type="text/javascript">

            setInterval( function(){

                var ajaxUrl = '<?php echo admin_url( 'admin-ajax.php' ); ?>';

                var posts = [];

                jQuery.each( jQuery( 'div.status-attachment' ), function( key, value ) {
                    var Id = parseInt( jQuery(this).attr( 'data-attachment-id' ) );
                    if( ! isNaN( Id ) ){
                        posts.push( Id );
                    }
                });

                if( posts.length > 0 ){
                    jQuery.post( ajaxUrl, {
                        'action'    : 'check_videos_progress',
                        '_wpnonce'  : '<?php echo wp_create_nonce( '_wpnonce' );?>',
                        'posts'     : posts
                    }, function( response ){

                        jQuery.each( response.data, function( key, value ) {
                            jQuery( '#the-list .bunnycdn_sync #status-attachment-' + key ).replaceWith( value );
                        });
                    } );
                }

            }, 5000 );

        </script>
        <?php
    }

    /**
     *
     * Admin notice
     * 
     * @since 2.1.3
     */
    public function notices(){
        if( $this->is_enabled() && function_exists( 'wp_video_encoder' ) ){
            ?>
            <div class="notice notice-warning">
                <p>
                    <?php printf(
                        esc_html__( 'You must deactivate the %s since you have enabled the %s', 'streamtube-core' ),
                        '<strong><a href="'. esc_url( admin_url( 'plugins.php?s=wp-video-encoder&plugin_status=all' ) ) .'">WP Video Encoder</a></strong>',
                        '<strong>Bunny Stream</strong>'
                    );?>
                </p>
            </div>
            <?php
        }
    }
}