<?php
/**
 * Define the Player functionality
 *
 *
 * @link       https://themeforest.net/user/phpface
 * @since      1.0.0
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

class Streamtube_Core_Player{

	private $is_verified 					= false;

	private $default_controlbar_watermark 	= 'controlbar.png';

	private $default_ref_url 				= '';

	private $license;

	public function __construct(){

		$this->default_ref_url 				= Streamtube_Core_License::ITEMURL;

		$this->is_verified 					= wp_cache_get( "streamtube:license" );

		$this->default_controlbar_watermark = $this->get_default_controlbar();
	}

	/**
	 *
	 * Get supported oEmbed Services
	 * 
	 * @return array
	 */
	public function oembed_services(){
		$services = array(
			'streamtube'	=>	get_post_type_archive_link('video'),
			'vimeo'			=>	'https://player.vimeo.com',
			'youtube'		=>	'https://www.youtube.com/embed',
			'rumble'		=>	'https://rumble.com/embed'
		);

		return apply_filters( 'streamtube/core/oembed_services', $services );
	}

	/**
	 *
	 * Get default controlbar URL
	 * 
	 * @return string
	 */
	private function get_default_controlbar(){
		return trailingslashit( STREAMTUBE_CORE_PUBLIC_URL ) . 'assets/img/' . $this->default_controlbar_watermark;
	}

	/**
	 *
	 * Set built-in events
	 * 
	 */
	public function set_builtin_events( $setup, $source ){
		$setup['plugins']['builtinEvents'] = array(
			'post_id'	=>	$setup['mediaid']
		);

		return $setup;
	}

	/**
	 *
	 * The topbar
	 */
	public function set_topbar( $setup, $source ){

		if( get_option( 'player_topshadowbar', 'on' ) ){
			$setup['plugins']['topBar'] = array();
		}

		return $setup;		
	}

	/**
	 *
	 * Set sharebox
	 * 
	 */
	public function set_share_box( $setup, $source ){
		if( get_option( 'player_share', 'on' ) && get_post_status( $setup['mediaid'] ) ){
			if( get_option( 'share_permalink', 'shorturl' ) == 'shorturl' ){
				$permalink = wp_get_shortlink( $setup['mediaid'] );
			}else{
				$permalink = get_permalink( $setup['mediaid'] );	
			}
			$url = add_query_arg(
				array(
					'from'		=>	'sharebox'
				),
				$permalink
			);			

			$args = array(
				'id'			=>	'share_box_' . $setup['mediaid'],
				'url'			=>	$url,
				'embed_url'		=>	get_post_embed_url( $setup['mediaid'] ),
				'embed_width'	=>	560,
				'embed_height'	=>	315,
				'label_url'		=>	esc_html__( 'Link', 'streamtube-core' ),
				'label_iframe'	=>	esc_html__( 'Iframe', 'streamtube-core' )
			);

			$setup['plugins']['playerShareBox'] = $args;
		}

		return $setup;
	}

	/**
	 *
	 * Set custom skin
	 * 
	 * @param array $setup
	 * @param string|int $source
	 * 
	 */
	public function set_skin( $setup, $source ){
		// Custom skin classname
		if( "" != $skin_class_name = get_option( 'player_skin_custom' ) ){
			$setup['classes'][] = $skin_class_name;
		}
		return $setup;
	}

	/**
	 *
	 * Set inactivity_timeout
	 * 
	 * @param array $setup
	 * @param string|int $source
	 * 
	 */
	public function set_inactivity_timeout( $setup, $source ){
		if( "" != $inactivity_timeout = get_option( 'inactivity_timeout', 1000 ) ){
			$setup['inactivityTimeout'] = (int)$inactivity_timeout;
		}

		return $setup;
	}

	/**
	 *
	 * Set language
	 * 
	 * @param array $setup
	 * @param string|int $source
	 * 
	 */
	public function set_language( $setup, $source ){

		if( "" != $player_language = get_option( 'player_language' ) ){
			wp_enqueue_script( 'videojs-language' );
			$setup['language'] = $player_language;
		}

		return $setup;
	}

	/**
	 *
	 * Set watermark
	 * 
	 * @param array $setup
	 * @param string|int $source
	 * 
	 */
	public function set_watermark( $setup, $source ){
		if( "" != $logo_url = get_option( 'player_logo' ) ){

			$url = add_query_arg(
				array(
					'from'		=>	'watermark',
					'referer'	=>	isset( $_SERVER['HTTP_REFERER'] ) ? urlencode( $_SERVER['HTTP_REFERER'] ) : ''
				),
				get_permalink( $setup['mediaid'] )
			);	

			$args = array(
				'logo'		=>	$logo_url,
				'position'	=>	get_option( 'player_logo_position', 'top-right' ),
				'href'		=>	is_embed() ? $url : '#',
				'alt'		=>	get_bloginfo( 'name' )
			);

			$player_logo_visibility = get_option( 'player_logo_visibility', 'embed' );

			if( ( $player_logo_visibility == 'embed' && is_embed() ) || $player_logo_visibility == 'always' ){
				$setup['plugins']['playerLogo'] = $args;
			}
		}
		return $setup;
	}

	/**
	 *
	 * Set watermark
	 * 
	 * @param array $setup
	 * @param string|int $source
	 * 
	 */
	public function set_controlbar_watermark( $setup, $source ){
		$control_bar_logo = get_option( 'player_control_logo' );

		if( ! $this->is_verified ){
			$control_bar_logo = $this->default_controlbar_watermark;
		}

		$url = '#';

		if( is_embed() && get_post_type( $setup['mediaid'] ) ){
			$url = add_query_arg(
				array(
					'from'		=>	'controlbar',
					'referer'	=>	isset( $_SERVER['HTTP_REFERER'] ) ? urlencode( $_SERVER['HTTP_REFERER'] ) : ''
				),
				get_permalink( $setup['mediaid'] )
			);		
		}

		if( "" != $control_bar_logo ){
			$setup['components']['controlBarLogo'] = array(
				'logo'		=>	$control_bar_logo,
				'href'		=>	$url,
				'alt'		=>	get_bloginfo( 'name' )
			);

			if( ! $this->is_verified ){
				$setup['components']['controlBarLogo']['href'] = $this->default_ref_url;
			}
		}

		return $setup;	
	}		

	/**
	 *
	 * Set playback_rates
	 * 
	 * @param array $setup
	 * @param string|int $source
	 * 
	 */
	public function set_playback_rates( $setup, $source ){
		$default_playback_rates = implode( ',' , array( 0.25, 0.5, 1,1.25, 1.5, 1.75, 2 ) );

		if( "" != $playback_rates = get_option( 'player_playbackrates', $default_playback_rates ) ){
			$playback_rates = array_map( 'floatval', explode(',', $playback_rates ) );

			if( is_array( $playback_rates ) ){
				$setup['playbackRates'] = $playback_rates;
			}
		}

		return $setup;		
	}

	/**
	 *
	 * Set landscape_mode
	 * 
	 * @param array $setup
	 * @param string|int $source
	 * 
	 */
	public function set_landscape_mode( $setup, $source ){
		if( get_option( 'fs_landscape_mode', 'on' ) ){

			$is_mobile = wp_is_mobile();

			if ( ! empty( $_SERVER['HTTP_USER_AGENT'] ) ){
				if( false !== strpos($_SERVER['HTTP_USER_AGENT'], 'iPad' ) 
					|| false !== strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone' ) ){
					$is_mobile = false;
				}
				
			}

			if( $is_mobile ){
				wp_enqueue_script( 'videojs-landscape-fullscreen' );
				$setup['plugins']['landscapeFullscreen'] = array(
					'fullscreen'	=>	array(
						'alwaysInLandscapeMode'	=>	true,
						'enterOnRotate'			=>	true,
						'iOS'					=>	true
					)
				);
			}
		}

		return $setup;
	}

	/**
	 *
	 * Set hotkeys
	 * 
	 * @param array $setup
	 * @param string|int $source
	 * 
	 */
	public function set_hotkeys( $setup, $source ){
		wp_enqueue_script( 'videojs-hotkeys' );
		$setup['plugins']['hotkeys'] = array(
			'volumeStep'				=>	0.1,
			'seekStep'					=>	1,
			'enableModifiersForNumbers'	=>	false,
			'enableVolumeScroll'		=>	false
		);

		return $setup;
	}

	/**
	 *
	 * Set Start At second
	 * 
	 * @param array $setup
	 * @param string|int $source
	 * 
	 */
	public function set_start_at( $setup, $source ){

		if( isset( $_GET['t'] ) && (int)$_GET['t'] > 0 ){
			$setup['plugins']['playerStartAtSecond'] = array(
				'start_at'	=>	(int)$_GET['t']
			);			
		}
		return $setup;
	}

	/**
	 *
	 * Set VR/360
	 * 
	 * @param array $setup
	 */
	public function set_vr( $setup, $source ){

		if( get_post_meta( $setup['mediaid'], '_vr', true ) ){
			wp_enqueue_script( 'videojs-xr' );
			wp_enqueue_style( 'videojs-xr' );
			$setup['plugins']['xr'] = array();

			add_filter( 'streamtube/player/file/output', function( $player, $setup, $source ){
				return str_replace( '<video-js', '<video-js crossorigin="anonymous"',  $player );
			}, 10, 3 );
		}

		return $setup;
	}


	/**
	 *
	 * Set right_click_blocker
	 * 
	 * @param array $setup
	 * @param string|int $source
	 * 
	 */
	public function set_right_click_blocker( $setup, $source ){
		if( get_option( 'player_block_right_click' ) && ! array_key_exists( 'xr', $setup['plugins'] ) ){
			$setup['plugins']['playerTransparentLayer'] = array(
				'disable_right_click'	=>	true
			);
		}
		return $setup;
	}	

	/**
	*
	* Set custom volume
	* 
	* @param array $setup
	*/
	public function set_volume( $setup, $source ){

		$volume = (int)get_option( 'player_volume', 10 );

		$setup['plugins']['playerRememberVolume'] = array(
			'default_volume' 	=> min( $volume, 10 ),
			'save_volume'		=> wp_validate_boolean( get_option( 'player_save_volume' ) )
		);
		return $setup;
	}

	public function set_pause_simultaneous( $setup, $source ){

		$setup['plugins']['playerPauseSimultaneous'] = true;

		return $setup;
	}

	/**
	 *
	 * Convert wp video shortcode to videojs 
	 *
	 * @since 1.0.0
	 * 
	 */
	public function override_wp_video_shortcode( $output = '', $attr = array(), $content = null, $instance = array() ){

		if( get_option( 'override_wp_video_shortcode', 'on' ) ){

			$src = '';

			if( $attr['src'] ){
				$src = $attr['src'];
			}

			if( $attr['mp4'] ){
				$src = $attr['mp4'];
			}			

			$maybe_attachment_id = attachment_url_to_postid( $src );

			$output = do_shortcode( sprintf(
				'[player source="%s" ratio="%s"]',
				$maybe_attachment_id ? $maybe_attachment_id : $src,
				get_option( 'embed_player_ratio', '16x9' )
			) );
		}

		return $output;
	}

	/**
	 *
	 * Filter WP video block
	 *
	 * @since 1.0.9
	 * 
	 */
	public function override_wp_video_block( $block_content, $block ){

		if( get_option( 'override_wp_video_block', 'on' ) ){
			if( $block['blockName'] == 'core/video' ){

				if( array_key_exists( 'id', $block['attrs'] ) ){
					$maybe_attachment_id = $block['attrs']['id'];

					if( wp_attachment_is( 'video', $maybe_attachment_id ) ){

						$block_content = do_shortcode( sprintf(
							'[player source="%s" ratio="%s"]',
							$maybe_attachment_id,
							get_option( 'embed_player_ratio', '16x9' )
						) );
					}
				}
				else{
					preg_match( '#<video .*?src="(.*?)"#', $block_content, $matches );

					if( $matches ){

						$block_content = do_shortcode( sprintf(
							'[player source="%s" ratio="%s"]',
							$matches[1],
							get_option( 'embed_player_ratio', '16x9' )
						) );
					}
				}
			}
		}

		return $block_content;
	}

	/**
	 *
	 * Filter WP Youtube block
	 *
	 * @since 1.0.9
	 * 
	 */
	public function override_wp_youtube_block( $block_content, $block  ){

		if( get_option( 'override_wp_youtube_block', 'on' ) ){
			if( $block['blockName'] == 'core/embed' ){
				if( is_array( $block['attrs'] ) && array_key_exists( 'providerNameSlug', $block['attrs'] ) ){
					if( $block['attrs']['providerNameSlug'] == 'youtube' ){
						$block_content = do_shortcode( sprintf(
							'[player source="%s" ratio="%s"]',
							$block['attrs']['url'],
							get_option( 'embed_player_ratio', '16x9' )
						) );
					}
				}

			}
		}

		return $block_content;
	}

	/**
	 * Auto-Convert YouTube URL/Iframe to VideoJS player
	 */
	public function convert_youtube_to_videojs( $content ){
 
		if( get_option( 'override_youtube', 'on' ) && ! is_admin() ){

			$pattern = '#(?:<iframe[^>]*>.*?</iframe>(*SKIP)(*FAIL)|<a(?:\s[^>]*)?>.*?</a>(*SKIP)(*FAIL))|' . // Exclude URLs within iframe or <a> tags
			           'https?://(?:www\.)?(?:youtube\.com/(?:watch\?v=|live/|embed/|shorts/)|youtu\.be/)' . // Match YouTube URLs
			           '([a-zA-Z0-9_-]+)(?:\S*)?(?:\?.*?)?#'; // Match video ID

			$content = preg_replace_callback( $pattern , function ($matches) {

				if( $matches && isset( $matches[1] ) && strlen( $matches[1] ) == 11 ){

					global $streamtube;

					$player_args = array(
						'post_id'	=>	$matches[1],
						'uniqueid'	=>	$matches[1],
						'source'	=>	'https://youtu.be/' . $matches[1],
						'ratio'		=>	get_option( 'embed_player_ratio', '16x9' )
					);

					/**
					 *
					 * Filter the player args
					 * 
					 * @param array $player_args
					 * @param array $matches
					 * 
					 */
					$player_args = apply_filters( 'streamtube/core/youtube_videojs_args', $player_args, $matches );

					$output = $streamtube->get()->shortcode->_player( $player_args );

					$output = preg_replace('#<!--.*?-->#', '', $output );

					return sprintf(
						'<div class="mb-4 oembed-youtube oembed-wrapper">%s</div>',
						$output
					);
				}
		
			}, $content );
 
		}
 
		return $content;
	}

	/**
	 *
	 * Load video source
	 * 
	 */
	public function load_video_source( $post_data = array() ){

		$post_data = wp_parse_args( $post_data, array(
			'post_id'	=>	0,
			'data'		=>	''
		) );

		extract( $post_data );

		$source = new WP_Error(
			'waiting',
			esc_html__( 'Waiting ...', 'streamtube-core' )
		);

		if( ! $post_id || get_post_type( $post_id ) != Streamtube_Core_Post::CPT_VIDEO ){
			$source->add(
				'invalid_video_post_type',
				esc_html__( 'Invalid video post type', 'streamtube-core' )
			);
		}

		if( is_string( $data ) ){
			$data = json_decode( wp_unslash( $data ), true );
		}

		/**
		 *
		 * Filter source
		 *
		 * @param WP_Error|string $source
		 * @param int $post_id video post type
		 * @param array $data
		 * 
		 */
		return apply_filters( 'streamtube/core/player/load_video_source', $source, $post_id, $data );
	}	

	/**
	 *
	 * Load video source
	 * 
	 */
	public function ajax_load_video_source(){

		check_ajax_referer( '_wpnonce' );

		if( ! isset( $_POST ) ){
			exit;
		}
		
		$response = $this->load_video_source( $_POST );

		if( is_wp_error( $response ) ){

			$data = wp_parse_args( (array)$response->get_error_data(), array(
				'handler'	=>	'default',
				'spinner'	=>	'spinner-grow text-danger',
				'progress'	=>	0
			) );

			wp_send_json_error( array(
				'message'	=>	$response->get_error_message(),
				'code'		=>	$response->get_error_code(),
				'handler'	=>	$data['handler'],
				'spinner'	=>	$data['spinner'],
				'progress'	=>	$data['progress']
			) );
		}
		
		wp_send_json_success( $response );
	}	
}