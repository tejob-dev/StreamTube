<?php
/**
 * The videojs template file
 *
 * @link       https://1.envato.market/mgXE4y
 * @since      1.0.0
 *
 * @package    WordPress
 * @subpackage StreamTube
 * @author     phpface <nttoanbrvt@gmail.com>
 */
if( ! defined( 'ABSPATH' ) ){
	exit;
}

if( ! defined( 'STREAMTUBE_IS_PLAYER' ) ){
	return;
}

wp_enqueue_style( 'streamtube-player' );

$is_single_video 	= is_singular( 'video' );

extract( $args );

$ratio = ! empty( $ratio ) ? 'ratio ratio-' . sanitize_html_class( $ratio ) : 'ratio-default';

?>
<?php printf(
	'<div class="player-wrapper bg-black %s" data-player-wrap-id="%s">',
	$is_single_video && ! $is_embed && get_option( 'floating_player' ) ? 'jsappear' : 'no-jsappear',
	$post_id ? esc_attr( $post_id ) : $args['uniqueid']
)?>
	<?php printf(
		'<div class="player-wrapper-ratio %s">',
		$ratio
	);?>

		<?php if( ! empty( $source ) ): ?>

			<div class="player-container">
		
				<?php if( $is_single_video && ! $is_embed ): ?>

					<div class="player-header p-3">
						<div class="d-flex align-items-center">

							<?php the_title( '<h5 class="post-title post-title-md h5">', '</h5>' );?>

							<div class="ms-auto">
								<?php printf(
									'<button type="button" class="btn-close outline-none shadow-none" aria-label="%s"></button>',
									esc_html__( 'Close', 'streamtube' )
								);?>
							</div>
						</div>
					</div>	

				<?php endif;?>

			    <?php printf( 
				    	'<div class="player-embed overflow-hidden bg-black %s"><div class="player-embed-inner">', 
				    	$ratio
			    	); ?>
			    	<?php

			    		$techOrder = array();

			    		$src = $type = $ads_tag_url = '';

			    		$skin = get_option( 'player_skin', 'forest' );

			    		if( $skin == 'custom' ){
			    			$skin = get_option( 'player_skin_custom' );
			    		}

			    		if( wp_attachment_is( 'video', $source  ) || wp_attachment_is( 'audio', $source  ) ){

			    			$src = wp_get_attachment_url( $source );

			    			if( strpos( $src , '.m3u8' ) !== false ){
			    				$type = 'application/x-mpegURL';
			    			}
			    			else{
			    				$type = get_post_mime_type( $source );
			    			}
			    		}

			    		if( $youtube && false != $maybe_youtube_url = streamtube_get_youtube_url( $source ) ){
			    			$src 			= $maybe_youtube_url;
			    			$type 			= 'video/youtube';
			    			$techOrder[] 	= 'youtube';
			    		}

			    		if( ! $src ){
				    		$_mimetype = streamtube_get_external_source_mimetype( $source );

				    		if( $_mimetype ){
				    			$src 			= $source;
				    			$type 			= $_mimetype;
				    		}
			    		}

				    	if( ! empty( $src ) ):

				    		if( in_array( $skin, array( 'city', 'forest', 'fantasy', 'sea' ) ) ){
				    			wp_enqueue_style( 'videojs-theme-' . $skin );	
				    		}

				    		if( $type == 'application/x-mpegURL' ){
			    				wp_enqueue_script( 'videojs-contrib-quality-levels' );
			    				wp_enqueue_script( 'videojs-hls-quality-selector' );
				    		}

				    		if( $type == 'video/youtube' ){
				    			wp_enqueue_script( 'videojs-youtube' );
				    		}

				    		$setup = array(
				    			'classes'			=>	array( 'position-absolute', 'videojs-streamtube' ),
				    			'preload'			=>	'auto',
				    			'inactivityTimeout'	=>	0,
				    			'sources'			=>	array(
				    				array(
				    					'src'		=>	$src,
				    					'type'		=>	$type
				    				)
				    			),
				    			'plugins'			=>	array()
				    		);

				    		if( ! $is_embed ){
				    			$setup['classes'][] = 'videojs-native';
				    		}else{
				    			$setup['classes'][] = 'videojs-embed';
				    		}

				    		$setup = array_merge( $setup, compact( 
				    			'mediaid',
				    			'controls', 
				    			'muted', 
				    			'autoplay', 
				    			'loop', 
				    			'trailer', 
				    			'is_embed',
				    			'poster',
				    			'poster2'
				    		) );

				    		if( $type == 'application/x-mpegURL' ){
				    			$setup['plugins']['playerhlsQualitySelector'] = array(
				    				'displayCurrentQuality'	=>	true
				    			);
				    		}

				    		if( ! empty( $skin ) ){
				    			$setup['classes'][] = 'vjs-theme-' . sanitize_html_class( $skin );	
				    		}

				    		if( $techOrder ){
				    			$setup['techOrder'] = $techOrder;
				    		}

				    		/**
				    		 *
				    		 * filter the player setup
				    		 *
				    		 * @param  array $setup
				    		 *
				    		 * @since  1.0.0
				    		 * 
				    		 */
				    		$setup = apply_filters( 'streamtube/player/file/setup', $setup, $source );

				    		if( $setup['plugins'] ){
				    			$setup['jplugins'] = $setup['plugins'];
				    			unset( $setup['plugins'] );
				    		}

				    		// crossorigin="anonymous"
				    		$player = sprintf(
				    			'<video-js data-parent-post-id="%1$s" data-player-id="%2$s" id="player_%2$s" class="%3$s" data-settings="%4$s" playsinline></video-js>',
				    			$args['post_id'],
				    			$post_id ? $post_id : $args['uniqueid'],
				    			esc_attr( join(' ', $setup['classes'] ) ),
				    			esc_attr( json_encode( $setup ) )
				    		);

				    		wp_enqueue_style( 'videojs' );	
				    		wp_enqueue_script( 'player' );

				    		/**
				    		 *
				    		 * Fires before videojs loaded
				    		 *
				    		 * @since 1.0.9
				    		 * 
				    		 */
				    		do_action( 'streamtube/player/videojs/loaded', $player, $setup, $source );

				    		/**
				    		 *
				    		 * filter the player output
				    		 *
				    		 * @param  HTML $player
				    		 * @param  array $setup
				    		 * @param string $source
				    		 *
				    		 * @since  1.0.0
				    		 * 
				    		 */
				    		echo apply_filters( 'streamtube/player/file/output', $player, $setup, $source );

				    	else:

					    	$oembed_html = wp_oembed_get( $source );

					    	if( ! $oembed_html ){
					    		$oembed_html = do_shortcode( $source  );
					    	}

				    		/**
				    		 *
				    		 * filter the oembed_html output
				    		 *
				    		 * @param  HTML $oembed_html
				    		 * @param  array $source
				    		 *
				    		 * @since  1.0.0
				    		 * 
				    		 */
				    		$oembed_html = apply_filters( 
				    			'streamtube/player/embed/output', 
				    			$oembed_html, 
				    			compact(
				    				'mediaid',
					    			'controls', 
					    			'muted', 
					    			'autoplay', 
					    			'loop', 
					    			'trailer', 
					    			'is_embed',
					    			'poster',
					    			'poster2'
				    			),
				    			$source
				    		);

					    	printf(
					    		'<div class="%s">%s</div>',
					    		$ratio != 'ratio-default' ? 'embed-responsive' : 'embed-custom-responsive',
					    		$oembed_html
					    	);

					    endif;

			    		/**
			    		 *
			    		 * Fires after player loaded
			    		 *
			    		 * @since 1.0.9
			    		 * 
			    		 */
			    		do_action( 'streamtube/player/loaded', $source );

				    ?>
			    </div><!--.player-embed-inner--></div><!--.player-embed-->

		    </div><!--.player-container-->
		<?php
			else:
				printf(
					'<div class="video-not-found"><h3 class="position-absolute top-50 start-50 translate-middle text-white">%s</h3></div>',
					esc_html__( 'Video unavailable', 'streamtube' )
				);	
			endif;// end check if source is empty
		?>
		
	</div><!--.player-wrapper-ratio-->
</div><!--.player-wrapper-->