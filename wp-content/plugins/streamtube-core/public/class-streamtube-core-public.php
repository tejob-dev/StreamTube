<?php
if( ! defined('ABSPATH' ) ){
    exit;
}

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://themeforest.net/user/phpface
 * @since      1.0.0
 *
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/public
 * @author     phpface <nttoanbrvt@gmail.com>
 */
class Streamtube_Core_Public {

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( 
			'placeholder-loading', 
			plugin_dir_url( __FILE__ ) . 'assets/css/placeholder-loading.min.css', 
			array(), 
			filemtime(trailingslashit( plugin_dir_path( __FILE__ ) ) . 'assets/css/placeholder-loading.min.css' )
		);

		wp_enqueue_style( 
			'select2', 
			plugin_dir_url( __FILE__ ) . 'assets/vendor/select2/select2.min.css', 
			array(), 
			filemtime(trailingslashit( plugin_dir_path( __FILE__ ) ) . 'assets/vendor/select2/select2.min.css' )
		);		

		wp_register_style( 
			'videojs', 
			plugin_dir_url( __FILE__ ) . 'assets/vendor/video.js/video-js.min.css', 
			array(), 
			filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'assets/vendor/video.js/video-js.min.css' ),
			'all' 
		);

		if( "" != $skin_css = get_option( 'player_skin_css' ) ){
			wp_add_inline_style( 'videojs', $skin_css );
		}

		wp_register_style( 
			'videojs-theme-forest', 
			plugin_dir_url( __FILE__ ) . 'assets/vendor/video.js/themes/forest/index.css', 
			array( 'videojs' ), 
			filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'assets/vendor/video.js/themes/forest/index.css' ),
			'all' 
		);	

		wp_register_style( 
			'videojs-theme-city', 
			plugin_dir_url( __FILE__ ) . 'assets/vendor/video.js/themes/city/index.css', 
			array( 'videojs' ), 
			filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'assets/vendor/video.js/themes/city/index.css' ),
			'all' 
		);

		wp_register_style( 
			'videojs-theme-fantasy', 
			plugin_dir_url( __FILE__ ) . 'assets/vendor/video.js/themes/fantasy/index.css', 
			array( 'videojs' ), 
			filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'assets/vendor/video.js/themes/fantasy/index.css' ),
			'all' 
		);

		wp_register_style( 
			'videojs-theme-sea', 
			plugin_dir_url( __FILE__ ) . 'assets/vendor/video.js/themes/sea/index.css', 
			array( 'videojs' ), 
			filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'assets/vendor/video.js/themes/sea/index.css' ),
			'all' 
		);

		wp_register_style( 
			'videojs-ima', 
			plugin_dir_url( __FILE__ ) . 'assets/vendor/video.js/videojs.ima.css', 
			array( 'videojs' ), 
			filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'assets/vendor/video.js/videojs.ima.css' ),
			'all' 
		);

		wp_register_style( 
			'videojs-xr', 
			plugin_dir_url( __FILE__ ) . 'assets/vendor/video.js/videojs-xr.css', 
			array( 'videojs' ), 
			filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'assets/vendor/video.js/videojs-xr.css' ),
			'all' 
		);		

		wp_register_style( 
			'cropperjs', 
			plugin_dir_url( __FILE__ ) . 'assets/vendor/cropper/cropper.min.css', 
			array(), 
			filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'assets/vendor/cropper/cropper.min.css' ),
			'all' 
		);

		wp_enqueue_style( 
			'slick', 
			plugin_dir_url( __FILE__ ) . 'assets/vendor/slick/slick.css', 
			array(), 
			filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'assets/vendor/slick/slick.css' ),
			'all' 
		);

		wp_enqueue_style( 
			'slick-theme', 
			plugin_dir_url( __FILE__ ) . 'assets/vendor/slick/slick-theme.css', 
			array( 'slick' ), 
			filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'assets/vendor/slick/slick-theme.css' ),
			'all' 
		);

		wp_register_style( 
			'lightbox', 
			plugin_dir_url( __FILE__ ) . 'assets/vendor/lightbox2/css/lightbox.min.css', 
			array( 'bootstrap' ), 
			filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'assets/vendor/slick/slick-theme.css' ),
			'all' 
		);		

		wp_register_style(
			'streamtube-player',
			plugin_dir_url( __FILE__ ) . 'assets/css/player.css', 
			array(), 
			filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'assets/css/player.css' ),
			'all' 
		);

	    if( ! function_exists( 'get_editable_roles' ) ){
	        require_once ABSPATH . 'wp-admin/includes/user.php';
	    }		

		$custom_css = '';

		foreach ( wp_roles()->roles as $key => $value ) {
			if( "" != $color = get_option( 'role_badge_' . $key, '#6c757d' ) ){
				$custom_css .= sprintf(
					'.user-roles .user-role.role-%s{background: %s!important}',
					$key,
					$color
				);
			}
		}

		wp_add_inline_style( 'streamtube-style', $custom_css );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( 'heartbeat' );

		if( is_user_logged_in() && ! is_embed() ){
			wp_enqueue_editor();
			wp_enqueue_script( 'wp-ajax-response' );
		}

		wp_enqueue_script(
			'select2',
			plugin_dir_url( __FILE__ ) . 'assets/vendor/select2/select2.min.js', 
			array( 'jquery' ),
			filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'assets/vendor/select2/select2.min.js' ),
			true
		);		

		wp_register_script(
			'videojs', 
			plugin_dir_url( __FILE__ ) . 'assets/vendor/video.js/video.min.js', 
			array(), 
			filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'assets/vendor/video.js/video.min.js' ),
			true
		);	

		if( "" != $player_language = get_option( 'player_language' ) ){
			wp_register_script(
				'videojs-language', 
				plugin_dir_url( __FILE__ ) . 'assets/vendor/video.js/lang/'.sanitize_file_name( $player_language ).'.js', 
				array( 'videojs' ), 
				filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'assets/vendor/video.js/lang/'.sanitize_file_name( $player_language ).'.js' ),
				true
			);			
		}	

		wp_register_script(
			'videojs-contrib-quality-levels', 
			plugin_dir_url( __FILE__ ) . 'assets/vendor/video.js/videojs-contrib-quality-levels.min.js', 
			array( 'videojs' ), 
			filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'assets/vendor/video.js/videojs-contrib-quality-levels.min.js' ),
			true 
		);

		wp_register_script(
			'videojs-hls-quality-selector', 
			plugin_dir_url( __FILE__ ) . 'assets/vendor/video.js/videojs-hls-quality-selector.min.js', 
			array( 'videojs', 'videojs-contrib-quality-levels' ), 
			filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'assets/vendor/video.js/videojs-hls-quality-selector.min.js' ),
			true 
		);

		wp_register_script(
			'videojs-youtube', 
			plugin_dir_url( __FILE__ ) . 'assets/vendor/video.js/youtube.min.js', 
			array( 'videojs' ), 
			filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'assets/vendor/video.js/youtube.min.js' ),
			true 
		);		

		wp_register_script(
			'videojs-contrib-ads', 
			plugin_dir_url( __FILE__ ) . 'assets/vendor/video.js/videojs-contrib-ad_s.min.js', 
			array( 'videojs' ), 
			filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'assets/vendor/video.js/videojs-contrib-ad_s.min.js' ),
			true
		);

		wp_register_script(
			'ima3sdk', 
			plugin_dir_url( __FILE__ ) . 'assets/vendor/video.js/imasdk.js', 
			array( 'videojs' ), 
			filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'assets/vendor/video.js/imasdk.js' ),
			true 
		);

		wp_register_script(
			'videojs-ima', 
			plugin_dir_url( __FILE__ ) . 'assets/vendor/video.js/videojs.ima.min.js', 
			array( 'videojs' ), 
			filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'assets/vendor/video.js/videojs.ima.min.js' ),
			true 
		);	

		wp_register_script(
			'videojs-hotkeys', 
			plugin_dir_url( __FILE__ ) . 'assets/vendor/video.js/videojs.hotkeys.min.js', 
			array( 'videojs' ), 
			filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'assets/vendor/video.js/videojs.hotkeys.min.js' ),
			true
		);

		wp_register_script(
			'videojs-landscape-fullscreen', 
			plugin_dir_url( __FILE__ ) . 'assets/vendor/video.js/videojs-landscape-fullscreen.min.js', 
			array( 'videojs' ), 
			filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'assets/vendor/video.js/videojs-landscape-fullscreen.min.js' ),
			true
		);

		wp_register_script(
			'videojs-xr', 
			plugin_dir_url( __FILE__ ) . 'assets/vendor/video.js/videojs-xr.min.js', 
			array( 'videojs' ), 
			filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'assets/vendor/video.js/videojs-xr.min.js' ),
			true
		);

		wp_register_script(
			'player', 
			plugin_dir_url( __FILE__ ) . 'assets/js/player.js', 
			array( 'videojs' ), 
			filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'assets/js/player.js' ),
			true
		);

		wp_register_script(
			'cropperjs', 
			plugin_dir_url( __FILE__ ) . 'assets/vendor/cropper/cropper.min.js', 
			array( 'jquery' ), 
			filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'assets/vendor/cropper/cropper.min.js' ),
			true 
		);

		wp_enqueue_script(
			'slick', 
			plugin_dir_url( __FILE__ ) . 'assets/vendor/slick/slick.min.js', 
			array( 'jquery' ), 
			filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'assets/vendor/slick/slick.min.js' ),
			true 
		);		

		wp_enqueue_script( 
			'jquery.scrolling', 
			plugin_dir_url( __FILE__ ) . 'assets/js/jquery.scrolling.js', 
			array( 'jquery' ), 
			filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'assets/js/jquery.scrolling.js' ),
			true 
		);		

		wp_enqueue_script( 
			'autosize', 
			plugin_dir_url( __FILE__ ) . 'assets/js/autosize.min.js', 
			array( 'jquery' ), 
			filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'assets/js/autosize.min.js' ),
			true 
		);

		wp_register_script( 
			'bootstrap-masonry.pkgd', 
			plugin_dir_url( __FILE__ ) . 'assets/js/masonry.pkgd.min.js', 
			array( 'jquery' ), 
			filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'assets/js/masonry.pkgd.min.js' ),
			true 
		);

		wp_enqueue_script(
			'typeahead',
			plugin_dir_url( __FILE__ ) . 'assets/js/typeahead.js', 
			array( 'jquery', 'bootstrap' ),
			filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'assets/js/typeahead.js' ),
			true
		);

		wp_register_script(
			'lightbox',
			plugin_dir_url( __FILE__ ) . 'assets/vendor/lightbox2/js/lightbox.min.js', 
			array( 'jquery', 'bootstrap' ),
			filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'assets/vendor/lightbox2/js/lightbox.min.js' ),
			true
		);

		wp_register_script(
			'jquery.countdown',
			plugin_dir_url( __FILE__ ) . 'assets/js/jquery.countdown.min.js', 
			array( 'jquery' ),
			filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'assets/js/jquery.countdown.min.js' ),
			true
		);	

		wp_register_script(
			'countdown.upcoming',
			plugin_dir_url( __FILE__ ) . 'assets/js/upcoming.js', 
			array( 'jquery', 'jquery.countdown' ),
			filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'assets/js/upcoming.js' ),
			true
		);		

		wp_enqueue_script( 
			'streamtube-core-functions', 
			plugin_dir_url( __FILE__ ) . 'assets/js/functions.js', 
			array( 'jquery' ), 
			filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'assets/js/functions.js' ),
			true 
		);	

		wp_enqueue_script( 
			'streamtube-core-scripts', 
			plugin_dir_url( __FILE__ ) . 'assets/js/public.js', 
			array( 'jquery', 'streamtube-core-functions' ), 
			filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'assets/js/public.js' ),
			true 
		);

		$jsvars = array(
			'home_url'				=> home_url('/'),
			'rest_url'				=> esc_url_raw( rest_url( 'streamtube/v1' ) ),
			'nonce'					=> wp_create_nonce( 'wp_rest' ),
			'ajaxUrl'				=> admin_url( 'admin-ajax.php' ),
			'_wpnonce'				=> wp_create_nonce('_wpnonce'),
			'media_form'			=> wp_create_nonce( 'media-form' ),
			'chunkUpload'			=> class_exists( 'BigFileUploads' ) ? 'on' : 'off',
			'sliceSize'				=> (int)get_option( 'chunk_size', 10240 ),
			'restRootUrl'			=> esc_url_raw( rest_url() ),
			'cart_url'				=> function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : '',
			'video_extensions'		=> wp_get_video_extensions(),
			'max_upload_size'		=> (int)streamtube_core_get_max_upload_size(),
			'incorrect_image'		=> esc_html__( 'Incorrect file type, please choose an image file.', 'streamtube-core' ),
			'can_upload_video'		=> current_user_can( 'edit_posts' ),
			'can_upload_video_error_message'	=>	esc_html__( 'Sorry, You do not have permission to upload video, please contact administrator.', 'streamtube-core' ),
			'invalid_file_format'	=>	esc_html__( 'Invalid file format.', 'streamtube-core' ),
			'exceeds_file_size'		=>	sprintf(
				esc_html__( 'The uploaded file size {size}MB exceeds the maximum allowed size: %sMB', 'streamtube-core' ),
				'<strong>'.round( streamtube_core_get_max_upload_size()/1048576 ).'</strong>'
			),
			'copy'					=>	esc_html__( 'COPY', 'streamtube-core' ),
			'iframe'				=>	esc_html__( 'Iframe', 'streamtube-core' ),
			'shorturl'				=>	esc_html__( 'Short URL', 'streamtube-core' ),
			'video_published'		=>	esc_html__( 'Video Published', 'streamtube-core' ),
			'pending_review'		=>	esc_html__( 'Pending Review', 'streamtube-core' ),
			'file_encode_done'		=>	esc_html__( 'has been encoded successfully.', 'streamtube-core' ),
			'view_video'			=>	esc_html__( 'view video', 'streamtube-core' ),
			'light_logo'			=>	wp_get_attachment_image_url( get_theme_mod( 'custom_logo' ), 'full' ),
			'dark_logo'				=>	get_option( 'dark_logo' ),
			'light_mode_text'		=>	esc_html__( 'Light mode', 'streamtube-core' ),
			'dark_mode_text'		=>	esc_html__( 'Dark mode', 'streamtube-core' ),
			'dark_editor_css'		=>	trailingslashit( STREAMTUBE_CORE_PUBLIC_URL ) . 'assets/css/editor-dark.css?ver=1',
			'light_editor_css'		=>	trailingslashit( STREAMTUBE_CORE_PUBLIC_URL ) . 'assets/css/editor-light.css?ver=1',
			'editor_toolbar'		=>	array(
				'bold',
				'italic',
				'underline',
				'strikethrough',
				'hr',
				'bullist',
				'numlist',
				'link',
				'unlink',
				'forecolor',
				'undo',
				'redo',
				'removeformat',
				'blockquote'
			),
			'has_woocommerce'		=>	function_exists( 'WC' ) ? true : false,
			'view_cart'				=>	esc_html__( 'view cart', 'streamtube-core' ),
			'added_to_cart'			=>	esc_html__( '%s has been added to cart', 'streamtube-core' ),
			'added_to_cart_no_name'	=>	esc_html__( 'Added to cart', 'streamtube-core' ),
			'public'				=>	esc_html__( 'Public', 'streamtube-core' ),
			'publish'				=>	esc_html__( 'Publish', 'streamtube-core' ),
			'published'				=>	esc_html__( 'Published', 'streamtube-core' ),
			'bp_message_sent'		=>	esc_html__( 'You have sent message successfully.', 'streamtube-core' ),
			'view_ad'				=>	esc_html__( 'View Ad', 'streamtube-core' ),
			'cancel'				=>	esc_html__( 'Cancel', 'streamtube-core' ),
			'play_now'				=>	esc_html__( 'Play now', 'streamtube-core' ),
			'edit'					=>	esc_html__( 'Edit', 'streamtube-core' ),
			'save'					=>	esc_html__( 'Save', 'streamtube-core' ),
			'report'				=>	esc_html__( 'Report', 'streamtube-core' ),
			'comment_reviewed'		=>	esc_html__( 'This comment is currently being reviewed', 'streamtube-core' ),
			'tax_terms_cache'		=>	array( 'video_tag', 'post_tag' ),
			'must_cache_terms'		=>	current_user_can( 'edit_posts' ) ? true : false,
			'upnext_time'			=>	5,
			'sound'					=>	array(
				'success'	=>	trailingslashit( STREAMTUBE_CORE_PUBLIC_URL ) . 'assets/media/success.mp3',
				'warning'	=>	trailingslashit( STREAMTUBE_CORE_PUBLIC_URL ) . 'assets/media/warning.mp3'
			),
			'toast'					=>	array(
				'delay'	=>	3000
			)

		);

		$jsvars = apply_filters( 'streamtube/core/public_scripts/localize', $jsvars );

		wp_localize_script( 'streamtube-core-scripts', 'streamtube', $jsvars );
	}

	/**
	 *
	 * enqueue embed scripts
	 * 
	 * @return [type] [description]
	 */
	public function enqueue_embed_scripts(){

		wp_register_style( 
			'videojs', 
			plugin_dir_url( __FILE__ ) . 'assets/vendor/video.js/video-js.min.css', 
			array(), 
			filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'assets/vendor/video.js/video-js.min.css' ),
			'all' 
		);

		if( "" != $skin_css = get_option( 'player_skin_css' ) ){
			wp_add_inline_style( 'videojs', $skin_css );
		}

		wp_register_style( 
			'videojs-theme-forest', 
			plugin_dir_url( __FILE__ ) . 'assets/vendor/video.js/themes/forest/index.css', 
			array( 'videojs' ), 
			filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'assets/vendor/video.js/themes/forest/index.css' ),
			'all' 
		);	

		wp_register_style( 
			'videojs-theme-city', 
			plugin_dir_url( __FILE__ ) . 'assets/vendor/video.js/themes/city/index.css', 
			array( 'videojs' ), 
			filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'assets/vendor/video.js/themes/city/index.css' ),
			'all' 
		);

		wp_register_style( 
			'videojs-theme-fantasy', 
			plugin_dir_url( __FILE__ ) . 'assets/vendor/video.js/themes/fantasy/index.css', 
			array( 'videojs' ), 
			filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'assets/vendor/video.js/themes/fantasy/index.css' ),
			'all' 
		);

		wp_register_style( 
			'videojs-theme-sea', 
			plugin_dir_url( __FILE__ ) . 'assets/vendor/video.js/themes/sea/index.css', 
			array( 'videojs' ), 
			filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'assets/vendor/video.js/themes/sea/index.css' ),
			'all' 
		);

		wp_register_style( 
			'videojs-ima', 
			plugin_dir_url( __FILE__ ) . 'assets/vendor/video.js/videojs.ima.css', 
			array( 'videojs' ), 
			filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'assets/vendor/video.js/videojs.ima.css' ),
			'all' 
		);					

		wp_enqueue_style( 
			'embed', 
			plugin_dir_url( __FILE__ ) . 'assets/css/embed.css', 
			array( 'videojs' ), 
			filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'assets/css/embed.css' ),
			'all' 
		);

		if( 0 < $root_size = (int)get_option( 'root_size', '15' ) ){
			wp_add_inline_style( 'embed', sprintf(
				':root{font-size:%spx}',
				$root_size
			) );
		}		

		wp_register_style( 
			'videojs-xr', 
			plugin_dir_url( __FILE__ ) . 'assets/vendor/video.js/videojs-xr.css', 
			array( 'videojs' ), 
			filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'assets/vendor/video.js/videojs-xr.css' ),
			'all' 
		);			

		wp_enqueue_style(
			'streamtube-player',
			plugin_dir_url( __FILE__ ) . 'assets/css/player.css', 
			array(), 
			filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'assets/css/player.css' ),
			'all' 
		);					

		wp_register_script(
			'videojs', 
			plugin_dir_url( __FILE__ ) . 'assets/vendor/video.js/video.min.js', 
			array( 'jquery' ), 
			filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'assets/vendor/video.js/video.min.js' ),
			true 
		);

		if( "" != $player_language = get_option( 'player_language' ) ){
			wp_register_script(
				'videojs-language', 
				plugin_dir_url( __FILE__ ) . 'assets/vendor/video.js/lang/'.sanitize_file_name( $player_language ).'.js', 
				array( 'videojs' ), 
				filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'assets/vendor/video.js/lang/'.sanitize_file_name( $player_language ).'.js' ),
				true
			);			
		}			

		wp_register_script(
			'videojs-contrib-quality-levels', 
			plugin_dir_url( __FILE__ ) . 'assets/vendor/video.js/videojs-contrib-quality-levels.min.js', 
			array( 'jquery', 'videojs' ), 
			filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'assets/vendor/video.js/videojs-contrib-quality-levels.min.js' ),
			true 
		);

		wp_register_script(
			'videojs-hls-quality-selector', 
			plugin_dir_url( __FILE__ ) . 'assets/vendor/video.js/videojs-hls-quality-selector.min.js', 
			array( 'jquery', 'videojs', 'videojs-contrib-quality-levels' ), 
			filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'assets/vendor/video.js/videojs-hls-quality-selector.min.js' ),
			true 
		);			

		wp_register_script(
			'videojs-youtube', 
			plugin_dir_url( __FILE__ ) . 'assets/vendor/video.js/youtube.min.js', 
			array( 'jquery', 'videojs' ), 
			filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'assets/vendor/video.js/youtube.min.js' ),
			true 
		);			

		wp_register_script(
			'videojs-contrib-ads', 
			plugin_dir_url( __FILE__ ) . 'assets/vendor/video.js/videojs-contrib-ad_s.min.js', 
			array( 'jquery', 'videojs' ), 
			filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'assets/vendor/video.js/videojs-contrib-ad_s.min.js' ),
			true
		);	

		wp_register_script(
			'ima3sdk', 
			plugin_dir_url( __FILE__ ) . 'assets/vendor/video.js/imasdk.js', 
			array( 'jquery', 'videojs' ), 
			filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'assets/vendor/video.js/imasdk.js' ),
			true 
		);			

		wp_register_script(
			'videojs-ima', 
			plugin_dir_url( __FILE__ ) . 'assets/vendor/video.js/videojs.ima.min.js', 
			array( 'jquery', 'videojs' ), 
			filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'assets/vendor/video.js/videojs.ima.min.js' ),
			true 
		);			

		wp_register_script(
			'videojs-hotkeys', 
			plugin_dir_url( __FILE__ ) . 'assets/vendor/video.js/videojs.hotkeys.min.js', 
			array( 'videojs' ), 
			filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'assets/vendor/video.js/videojs.hotkeys.min.js' ),
			true
		);					

		wp_register_script(
			'videojs-landscape-fullscreen', 
			plugin_dir_url( __FILE__ ) . 'assets/vendor/video.js/videojs-landscape-fullscreen.min.js', 
			array( 'videojs' ), 
			filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'assets/vendor/video.js/videojs-landscape-fullscreen.min.js' ),
			true
		);			

		wp_register_script(
			'videojs-xr', 
			plugin_dir_url( __FILE__ ) . 'assets/vendor/video.js/videojs-xr.min.js', 
			array( 'jquery', 'videojs' ), 
			filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'assets/vendor/video.js/videojs-xr.min.js' ),
			true
		);		

		wp_register_script(
			'jquery.countdown',
			plugin_dir_url( __FILE__ ) . 'assets/js/jquery.countdown.min.js', 
			array( 'jquery' ),
			filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'assets/js/jquery.countdown.min.js' ),
			true
		);		
		
		wp_register_script(
			'countdown.upcoming',
			plugin_dir_url( __FILE__ ) . 'assets/js/upcoming.js', 
			array( 'jquery', 'jquery.countdown' ),
			filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'assets/js/upcoming.js' ),
			true
		);			

		wp_register_script(
			'player', 
			plugin_dir_url( __FILE__ ) . 'assets/js/player.js', 
			array( 'jquery', 'videojs','videojs-hls-quality-selector' ), 
			filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'assets/js/player.js' ),
			true
		);

		$jsvars = array(
			'ajaxUrl' 	=> admin_url( 'admin-ajax.php' ),
			'_wpnonce'	=> wp_create_nonce( '_wpnonce' )
		);

		$jsvars = apply_filters( 'streamtube/core/player_scripts/localize', $jsvars );

		wp_localize_script( 'player', 'streamtube', $jsvars );		

		wp_enqueue_script(
			'embed', 
			plugin_dir_url( __FILE__ ) . 'assets/js/embed.js', 
			array( 'jquery' ), 
			filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'assets/js/embed.js' ),
			true 
		);		
	}

	/**
	 *
	 * Load search template
	 *
	 * @since 1.00
	 * 
	 */
	public function load_search_template( $template ){
		if( is_search() ){
			$template = plugin_dir_path( __FILE__ ) . 'page/search.php';
		}

		return $template;
	}

	/**
	 *
	 * Load the Upload section button
	 * 
	 * @since    1.0.0
	 * 
	 */
	public function the_upload_button(){
		if( is_user_logged_in() ){

			$args = array(
				'button_icon'	=>	'icon-videocam'
			);

			/**
			 * @since 2.1.7
			 */
			$args = apply_filters( 'streamtube/core/button/upload/args', $args );

			streamtube_core_load_template( 'misc/btn-upload.php', true, $args);

			do_action( 'btn_upload_loaded' );
		}
	}

	/**
	 *
	 * Load all required modals
	 * 
	 * @since 1.0.0
	 * 
	 */
	public function load_modals(){

		$is_logged_in = is_user_logged_in();

		if( get_option( 'upload_files', 'on' ) && $is_logged_in ){
			streamtube_core_load_template( "modal/upload-video.php", false );
		}

		if( get_option( 'embed_videos', 'on' ) && $is_logged_in ){
			streamtube_core_load_template( "modal/embed-video.php", false );
		}

		if( ! $is_logged_in ){
			streamtube_core_load_template( "modal/login.php", false );	
		}
	}
}
