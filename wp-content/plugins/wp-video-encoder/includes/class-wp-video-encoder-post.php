<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * Define the post functionality.
 *
 *
 * @since      1.0.0
 * @package    WP_Video_Encoder
 * @subpackage WP_Video_Encoder/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */
class WP_Video_Encoder_Post {

	private $settings;

	private $queue;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct() {
		$this->settings = WP_Video_Encoder_Settings::get_settings();

		$this->queue = new WP_Video_Encoder_Queue();
	}

	/**
	 *
	 * Set post thumbnail
	 * 
	 * @param image path $image
	 * @param int $post_id
	 *
	 * @return  array()
	 *
	 * @since  1.0.0
	 * 
	 */
	private function set_attachment_image( $image, $attachment_id, $set_thumbnail = false ){

		$filetype = wp_check_filetype( basename( $image ), null );

		$thumbnail_id = wp_insert_attachment(
			array(
				'post_mime_type'	=> $filetype['type'],
				'post_title'		=> preg_replace( '/\.[^.]+$/', '', basename( $image ) ),
				'post_status'		=> 'inherit',
				'post_author'		=>	get_post( $attachment_id )->post_author
			), 
			$image
		);

		if( is_wp_error( $thumbnail_id ) ){
			return $thumbnail_id;
		}

		if ( ! function_exists( 'wp_generate_attachment_metadata' ) ) {
		    include( ABSPATH . 'wp-admin/includes/image.php' );
		}		

		$metadata = wp_generate_attachment_metadata( $thumbnail_id, $image );

		if( is_array( $metadata ) ){
			wp_update_attachment_metadata( $thumbnail_id, $metadata );	
		}
		
		if( $set_thumbnail ){
			set_post_thumbnail( $attachment_id, $thumbnail_id );
		}

		wp_update_post( array(
			'ID'			=>	(int)$thumbnail_id,
			'post_parent'	=>	(int)$attachment_id
		) );

		return compact( 'attachment_id', 'thumbnail_id' );
	}

	/**
	 * 
	 *
	 * Create image from given attachment ID
	 * 
	 * @param  int $attachment_id
	 * @return $this->create_post_thumbnail() or false
	 *
	 * @since  1.0.0
	 * 
	 */
	public function _generate_attachment_image( $attachment_id ){

		// Do nothing if attachment isn't video
		if( ! wp_attachment_is( 'video', $attachment_id ) ){
			return new WP_Error(
				'attachment_not_found',
				esc_html__( 'Invalid Video Format or Attachment was not found', 'wp-video-encoder' )
			);
		}
		
		$encoder = new WP_Video_Encoder_Encoder( 
			get_attached_file( $attachment_id ), 
			$this->settings['bin_path'],
			$this->settings['nice_path']
		);

		$image = $encoder->generate_image();

		if( is_wp_error( $image ) ){
			return $image;
		}

		if( is_string( $image ) && file_exists( $image ) ){
			return $this->set_attachment_image( $image, $attachment_id, true );
		}

		return new WP_Error(
			'undefined_error',
			esc_html__( 'Undefined Error', 'wp-video-encoder' )
		);
	}

	/**
	 * 
	 *
	 * Create image from given attachment ID
	 * 
	 * @param  int $attachment_id
	 * @return $this->create_post_thumbnail() or false
	 *
	 * @since  1.0.0
	 * 
	 */
	public function generate_attachment_image( $attachment_id ){
		if( ! $this->settings['auto_generate_image'] ){
			return $attachment_id;
		}

		return $this->_generate_attachment_image( $attachment_id );
	}	

	/**
	 *
	 * Generate webp image
	 * 
	 * @param  int $attachment_id
	 *
	 * 
	 */
	public function _generate_attachment_image_webp( $attachment_id ){

		// Do nothing if attachment isn't video
		if( ! wp_attachment_is( 'video', $attachment_id ) ){
			return new WP_Error(
				'attachment_not_found',
				esc_html__( 'Invalid Video Format or Attachment was not found', 'wp-video-encoder' )
			);
		}
		
		$encoder = new WP_Video_Encoder_Encoder( 
			get_attached_file( $attachment_id ), 
			$this->settings['bin_path'],
			$this->settings['nice_path']
		);

		$image = $encoder->generate_image_webp( array(
			'resolution'	=>	$this->settings['webp_resolution'],
			'type'			=>	$this->settings['image_file_type'],
			'start'			=>	$this->settings['webp_start_at'],
			'time'			=>	(int)$this->settings['webp_fixed_time'],
			'setpts'		=>	(float)$this->settings['webp_setpts']
		) );

		if( is_wp_error( $image ) ){
			return $image;
		}

		if( is_string( $image ) && file_exists( $image ) ){
			$results = $this->set_attachment_image( $image, $attachment_id );

			if( is_array( $results ) ){
				update_post_meta( (int)$attachment_id, '_thumbnail_id_2', (int)$results['thumbnail_id'] );
			}

			return $results;
		}

		return new WP_Error(
			'failed_unknown_error',
			esc_html__( 'Failed, unknown error', 'wp-video-encoder' )
		);
	}

	/**
	 *
	 * Generate webp image
	 * 
	 * @param  int $attachment_id
	 *
	 * 
	 */
	public function generate_attachment_image_webp( $attachment_id ){

		if( ! $this->settings['auto_generate_webp_image'] ){
			return $attachment_id;
		}

		return $this->_generate_attachment_image_webp( $attachment_id );
	}	

	/**
	 * 
	 *
	 * Filter the video attachment URL
	 * point to playlist.m3u8 URL if found
	 * 
	 * @param  string $url
	 * @param  int $attachment_id
	 * @return string
	 *
	 *
	 * @since  1.0.0
	 * 
	 */
	public function filter_get_attachment_url( $url, $attachment_id ){

		// Do nothing if attachment isn't video
		if( ! wp_attachment_is( 'video', $attachment_id ) ){
			return $url;
		}

		$file_path = get_attached_file( $attachment_id );

		$file_info = pathinfo( $file_path );

		$encoder = new WP_Video_Encoder_Encoder( 
			$file_path, 
			$this->settings['bin_path'],
			$this->settings['nice_path']
		);

		if( file_exists( $encoder->get_encode_playlist_file() ) ){

			$playlist = $encoder->get_file_master() . '?v=' . filemtime( $encoder->get_encode_playlist_file() );

			return trailingslashit( dirname( $url )) . sanitize_file_name( $file_info['filename'] ) . '/' . $playlist;
		}

		return $url;
	}

	/**
	 *
	 * Run before an attachment is deleted
	 * 
	 * @param  int $attachment_id [description]
	 * @param  WP_Post $attachment
	 *
	 * @since 1.0.0
	 * 
	 */
	public function delete_attachment( $attachment_id, $attachment ){

		if( wp_attachment_is( 'video',  $attachment_id ) ){

			/**
			 * Delete all encoded files such as log, ts and m3u8
			 */
			$encoder = new WP_Video_Encoder_Encoder( get_attached_file( $attachment_id ) );

			$encoder->delete_encoded_files();

			/**
			 * Remove queue item
			 */
			$queue = new WP_Video_Encoder_Queue();

			$queue->delete_queue_item( $attachment_id );
		}
	}

	/**
	 *
	 * Filter video extensions
	 * 
	 * @param  array $formats
	 * @return array $formats
	 *
	 * @since  1.0.5
	 */
	public function filter_wp_video_extensions( $formats ){
		$_formats = $this->settings['allow_formats'];

		if( empty( $_formats ) ){
			return $formats;
		}

		return array_map( 'trim', explode(',', $_formats ) );
	}

	public function filter_player_setup( $setup, $source ){

		if( get_post_meta( $source, 'live_status', true ) ){
			return $setup;
		}

		$queue = $this->queue->get_queue_item( $source );

		if( is_array( $queue ) && array_key_exists( 'status', $queue ) && in_array( $queue['status'], array( 'waiting', 'encoding', 'failed' ) ) ){

			$playerLoadSource = array(
            	'message'   =>  esc_html__( 'Waiting ...', 'streamtube-core' ),
            	'progress'	=>	absint( $queue['percentage'] )
        	);

			if( $queue['status'] == 'failed' ){
				$playerLoadSource['spinner'] = false;
			}

            $setup['plugins']['playerLoadSource'] = $playerLoadSource;

            // Reset sources
            $setup['sources'] = array();
		}

		return $setup;
	}

    /**
     *
     * Hooked into "streamtube/core/player/check_video_source" filter
     *
     * 
     * @param  string|WP_Error $source
     * @param  int $post_id
     */
    public function filter_player_load_source( $source, $post_id, $data = array() ){

    	$attachment_id  = get_post_meta( $post_id, 'video_url', true );

    	$queue = $this->queue->get_queue_item( $attachment_id );

		if( is_array( $queue ) && array_key_exists( 'status', $queue ) ){

			if( in_array( $queue['status'], array( 'waiting', 'encoding', 'failed' ) ) ){
				$messages = array(
					'waiting'	=>	esc_html__( 'The video is currently queued for encoding.', 'wp-video-encoder' ),
					'encoding'	=>	esc_html__( 'The video is currently being encoded.', 'wp-video-encoder' ),
					'failed'	=>	esc_html__( 'Encoding has failed.', 'wp-video-encoder' )
				);

				return new WP_Error(
					$queue['status'],
					$messages[ $queue['status'] ],
					array(
						'handler'	=>	'wp-video-encoder',
						'spinner'	=>	( $queue['status'] == 'failed' ) ? false : 'spinner-grow text-success',
						'progress'	=>	absint( $queue['percentage'] )
					)
				);
			}

            return array(
                'type'  =>  'application/x-mpegURL',
                'src'   =>  wp_get_attachment_url( $attachment_id )
            );			
		}

		return $source;
    }

    /**
     * 
     *
     * Rest API generate thumbnail image
     * 
     * @param  int $thumbnail_id
     * @param  int $attachment_id
     * @return int
     */
    public function rest_generate_thumbnail_image( $thumbnail_id = 0, $attachment_id = 0 ){    

        if( ! $thumbnail_id || is_wp_error( $thumbnail_id ) ){
            $results = $this->_generate_attachment_image( $attachment_id );    

            if( is_wp_error( $results ) ){
            	$thumbnail_id = $results;
            }
            else{
            	$thumbnail_id = $results['thumbnail_id'];
        	}
        }

        return $thumbnail_id;
    }

    /**
     *
     * Rest generate animated image (webp)
     * 
     * @param  string $thumbnail_ur
     * @param  int  $attachment_id
     * @return string
     *
     * 
     */
    public function rest_generate_animated_thumbnail_image( $thumbnail_url = '', $attachment_id = 0 ){   

    	if( ! $thumbnail_url || is_wp_error( $thumbnail_url ) ){
            $results = $this->_generate_attachment_image_webp( $attachment_id );

            if( is_wp_error( $results ) ){
                return $results;
            }

            if( is_array( $results ) && array_key_exists( 'thumbnail_id' , $results ) ){
                $thumbnail_url = wp_get_attachment_image_url( $results['thumbnail_id'], 'large' );
            }
    	}

    	return $thumbnail_url;

    }

}