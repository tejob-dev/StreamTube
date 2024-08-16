<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://1.envato.market/mgXE4y
 * @since      1.0.0
 *
 * @package    WP_Cloudflare_Stream
 * @subpackage WP_Cloudflare_Stream/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WP_Cloudflare_Stream
 * @subpackage WP_Cloudflare_Stream/admin
 * @author     phpface <nttoanbrvt@gmail.com>
 */
class WP_Cloudflare_Stream_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

    private $post;

    /**
     *
     * Plugin settings
     * 
     * @var [type]
     */
    private $settings;

    /**
     *
     * Define advertising admin menu slug
     *
     * @since 1.3
     * 
     */
    const ADMIN_SETTINGS_MENU_SLUG   = 'options-general.php';	

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

        $this->settings = WP_Cloudflare_Stream_Settings::get_settings();

        $this->post = new WP_Cloudflare_Stream_Post();
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( 
			$this->plugin_name, 
			plugin_dir_url( __FILE__ ) . 'css/wp-cloudflare-stream-admin.css', 
			array(), 
			filemtime( plugin_dir_path( __FILE__ ) . 'css/wp-cloudflare-stream-admin.css' ), 
			'all' 
		);

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( 
			$this->plugin_name, 
			plugin_dir_url( __FILE__ ) . 'js/wp-cloudflare-stream-admin.js', 
			array( 'jquery' ), 
			filemtime( plugin_dir_path( __FILE__ ) . 'js/wp-cloudflare-stream-admin.js' ), 
			false 
		);

		wp_localize_script( $this->plugin_name, 'wp_cloudflare_stream', array(
			'ajax_url'	=>	admin_url( 'admin-ajax.php' ),
            '_wpnonce'  =>  wp_create_nonce( '_wpnonce' )
		) );
	}   

	/**
	 *
	 * Admin settings menu
	 *
	 * @since 1.0.0
	 * 
	 */
	public function add_settings_menu(){
        add_submenu_page( 
            self::ADMIN_SETTINGS_MENU_SLUG, 
            esc_html__( 'WP Cloudflare Stream', 'wp-cloudflare-stream' ), 
            esc_html__( 'WP Cloudflare Stream', 'wp-cloudflare-stream' ), 
            'administrator', 
            'wp-cloudflare-stream', 
            array( 'WP_Cloudflare_Stream_Settings', 'admin_settings' ), 
            50
        );        
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
            'cloudflare-video-details', 
            esc_html__( 'Cloudflare Stream - Details', 'wp-cloudflare-stream' ), 
            array( $this , 'video_details' ), 
            array( 'attachment', 'video' ), 
            'advanced', 
            'core'
        );

        add_meta_box( 
            'cloudflare-video-outputs', 
            esc_html__( 'Cloudflare Stream - Simulcast', 'wp-cloudflare-stream' ), 
            array( $this , 'video_outputs' ), 
            array( 'attachment', 'video' ), 
            'advanced', 
            'core'
        );        
    }	

    /**
     *
     * Get actual attachment ID
     * 
     */
    private function get_attachment_id( $post ){

        $attachment_id = 0;

        if( $post->post_type == 'video' ){

            $maybe_video_id = (int)get_post_meta( $post->ID, 'video_url', true );

            if( wp_attachment_is( 'video', $maybe_video_id ) || wp_attachment_is( 'audio', $maybe_video_id ) ){
                $attachment_id = $maybe_video_id;
            }
        }else{
            $attachment_id = $post->ID;
        }

        return $attachment_id;
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

    	$data = $this->post->get_stream( $this->get_attachment_id( $post ) );

        if( $data ){

            $data['stream']['hls_url'] = $this->post->get_playback_url( $data['stream']['uid'] );
            $data['stream']['dash_url'] = $this->post->get_playback_url( $data['stream']['uid'], false );
            $data['stream']['preview_url'] = $this->post->get_preview_url( $data['stream']['uid'] );

            load_template( 
                plugin_dir_path( __FILE__ ) . 'metabox/video-details.php', 
                true, 
                $data
            );

        }else{
            return printf(
                '<p>%s</p>',
                esc_html__( 'No content available', 'wp-cloudflare-stream' )
            );
        }
    }

    public function video_outputs( $post ){

        $data = $this->post->is_live_stream( $this->get_attachment_id( $post ) );

        if( $data ){
            load_template( 
                plugin_dir_path( __FILE__ ) . 'metabox/video-outputs.php', 
                true, 
                $data
            );

        }else{
            return printf(
                '<p>%s</p>',
                esc_html__( 'Live Outputs are not available', 'wp-cloudflare-stream' )
            );            
        }
    }

    /**
     *
     * Start Live Stream metabox
     * 
     * @param  int $post
     *
     * @since 1.0.0
     * 
     */
    public static function start_live_stream( $post ){
        ?>
        <button 
            data-action="admin_start_live_stream" 
            data-post-id="<?php echo $post->ID ?>" 
            id="start_live_stream" 
            type="button" 
            class="button button-large button-primary d-block w-100">
            <?php esc_html_e( 'Start Live Stream', 'wp-cloudflare-stream' );?>
            <span class="spinner"></span>
        </button>
        <?php
    }

    /**
     *
     * AJAX start live stream
     * 
     * @since 1.0.0
     */
    public function ajax_start_live_stream(){
        $response = $this->post->start_live_stream( $_POST );

        if( is_wp_error( $response ) ){
            wp_send_json_error( $response );
        }

        wp_send_json_success( array_merge( $response, array(
            'edit_link' =>  get_edit_post_link($response['video_id'])
        ) ) );
    }

    /**
     *
     * AJAX close|open live stream
     * 
     * @since 1.0.0
     */
    public function ajax_close_open_live_stream(){

        $results = $this->post->process_live_stream( $_POST['video_id'] );

        if( is_wp_error( $results ) ){
            wp_send_json_error( $results );
        }

        if( $results['new_status'] == 'close' ){
            wp_send_json_success( array(
                'message'   =>  esc_html__( 'Live Stream has been closed successfully', 'wp-cloudflare-stream' ),
                'edit_link' =>  get_edit_post_link($results['post_id'])
            ) );
        }
        else{
            wp_send_json_success( array(
                'message'   =>  esc_html__( 'Live Stream has been opened successfully', 'wp-cloudflare-stream' ),
                'edit_link' =>  get_edit_post_link($results['post_id'])
            ) );            
        }
    }

    /**
     *
     * AJAX view error log content
     * 
     */
    public function ajax_get_cloudflare_error(){

        if( ! isset( $_GET['attachment_id'] ) ){
            exit;
        }

        $attachment_id = (int)$_GET['attachment_id'];

        if( ! current_user_can( 'edit_post', $attachment_id ) ){
            esc_html_e( 'Sorry, You do not have permission to view logs', 'wp-cloudflare-stream' );
        }
        else{

            $log_content = esc_html__( 'No logs were found', 'wp-cloudflare-stream' );

            $logs = get_post_meta( $attachment_id, WP_Cloudflare_Stream_Settings::POST_CLOUDFLARE, true );    

            if( is_array( $logs ) && array_key_exists( 'status' , $logs ) ){
                if( is_array( $logs['status'] ) && array_key_exists( 'status' , $logs['status'] ) && $logs['status']['state'] == 'error' ){
                    $log_content = $logs['status']['errReasonText'];
                }
            }

            echo $log_content;
        }
    
        exit;
    }

    /**
     *
     * AJAX sync video to cloudflare
     * 
     * @since 1.0.0
     */
    public function ajax_sync_cloudflare_upload(){
        
        if( ! isset( $_POST['attachment_id'] ) ){
            exit;
        }

        $attachment_id = (int)$_POST['attachment_id'];

        if( ! current_user_can( 'edit_post', $attachment_id ) ){
            wp_send_json_error( new WP_Error(
                'no_permission',
                esc_html__( 'Sorry, You do not have permission to do this action', 'wp-cloudflare-stream' )
            ) );
        }

        $results = $this->post->_add_attachment( $attachment_id );

        if( is_wp_error( $results ) ){
            wp_send_json_error( $results );
        }

        wp_send_json_success( array(
            'message'   =>  esc_html__( 'Synced', 'wp-cloudflare-stream' ),
            'results'   =>  $results
        ) );
    }

    /**
     *
     * The Video table
     *
     * @since 1.0.0
     * 
     */
    public function post_table( $columns ){

        if( ! $this->settings['enable'] ){
            return $columns;
        }

        unset( $columns['date'] );

        $new_columns = array(
            'cloudflare_stream' =>  esc_html__( 'Cloudflare Stream', 'wp-cloudflare-stream' ),
            'date'              =>  esc_html__( 'Date', 'streamtube-core' )
        );

        return array_merge( $columns, $new_columns );
    }

    /**
     *
     * The Video table
     *
     * @since 1.0.0
     * 
     */
    public function post_table_columns( $column, $post_id ){
        switch ( $column ) {

            case 'cloudflare_stream':
                $attachment_id = get_post_meta( $post_id, 'video_url', true );

                if( wp_attachment_is( 'video', $attachment_id ) ){
                    load_template( 
                        plugin_dir_path( __FILE__ ) . 'metabox/cloudflare-control.php', 
                        false, 
                        compact( 'attachment_id' )
                    );
                }
            break;
            
        }
    }

    /**
     *
     * The media table
     * 
     * @since  2.1
     * 
     */
    public function media_table( $columns ){
        return $this->post_table( $columns );
    }

    /**
     *
     * The media table
     * 
     * @since  2.1
     * 
     */
    public function media_table_columns( $column, $post_id ){

        switch ( $column ) {

            case 'cloudflare_stream':

                $attachment_id = $post_id;

                if( wp_attachment_is( 'video', $attachment_id ) ){
                    load_template( 
                        plugin_dir_path( __FILE__ ) . 'metabox/cloudflare-control.php', 
                        false, 
                        compact( 'attachment_id' )
                    );
                }
            break;

        }
    }        

}