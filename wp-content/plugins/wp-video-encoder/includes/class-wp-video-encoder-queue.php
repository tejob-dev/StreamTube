<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define the queue functionality.
 *
 *
 * @since      1.0.0
 * @package    WP_Video_Encoder
 * @subpackage WP_Video_Encoder/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */
class WP_Video_Encoder_Queue extends WP_Video_Encoder_DB{

	/**
	 *
	 * Holds allowed statuses
	 * 
	 * @var array
	 *
	 * @since  1.0.0
	 * 
	 */
	public $statuses = array( 'waiting', 'encoding', 'encoded', 'fail' );

	protected $settings;

	/**
	 * The class contructor
	 *
	 * @since    1.0.0
	 */	
	public function __construct( $settings = array() ){
		$this->settings = WP_Video_Encoder_Settings::get_settings();
	}

	/**
	 *
	 * Insert attachment to the queue
	 *
	 * @param  int $attachment_id
	 *
	 * @since  1.0.0
	 * 
	 */
	public function insert_queue_item( $attachment_id = 0 ){

		$status = 'waiting';

		return $this->_insert( compact( 'attachment_id', 'status' ) );
	}

	/**
	 *
	 * Auto Queue File
	 * 
	 * @param  integer $attachment_id
	 */
	public function auto_queue( $attachment_id = 0 ){
		/**
		 *
		 * Return if attachment was not found or can't encode
		 * 
		 */
		if( ! $this->settings['auto_encode'] || ! $attachment_id ){
			return $attachment_id;
		}

		if( wp_attachment_is( 'video', $attachment_id ) ){

			$file = get_attached_file( $attachment_id );

			if( file_exists( $file ) && ! is_dir( $file ) ){
				return $this->insert_queue_item( $attachment_id );
			}
		}

		return $attachment_id;
	}

	/**
	 *
	 * Requeue an item
	 * 
	 * @param  int $attachment_id
	 *
	 * @since  1.0.0 
	 * 
	 */
	public function requeue_item( $attachment_id ){
		$this->delete_queue_item( $attachment_id );

		$encoder = new WP_Video_Encoder_Encoder( 
			get_attached_file( $attachment_id ),
			$this->settings['bin_path'],
			$this->settings['nice_path']
		);

		$encoder->delete_encoded_files();

		return $this->insert_queue_item( $attachment_id );
	}

	/**
	 *
	 * update attachment from the queue
	 *
	 * @param  int $attachment_id
	 *
	 * @since  1.0.0
	 * 
	 */
	public function update_queue_item( $attachment_id, $status = 'waiting' ){

		if( ! in_array( $status , $this->statuses ) ){
			$status == 'waiting';
		}

		return $this->_update( compact( 'attachment_id', 'status' ) );
	}

	/**
	 *
	 * Delete given queue item
	 *
	 * @param  int $attachment_id
	 *
	 * @since  1.0.0
	 * 
	 */
	public function delete_queue_item( $attachment_id ){
		return $this->_delete( $attachment_id );
	}

	/**
	 *
	 * Get queue items
	 * 
	 * @param  array  $args
	 * @return array
	 *
	 * @since  1.0.0
	 * 
	 */
	public function get_queue_items( $args = array() ){

		$args = wp_parse_args( $args, array(
			'author'		=>	0,
			'attachment_id'	=>	0,
			'parent'		=>	0,
			'status'		=>	'waiting',
			'limit'			=>	2
		) );

		extract( $args );

		global $wpdb;

		$where = array();

		$where[] = $wpdb->prepare( 'posts.post_type=%s', 'attachment' );

		if( $attachment_id ){
			$where[] = $wpdb->prepare( 'encoder.attachment_id=%d', $attachment_id );
		}

		if( $parent ){
			$where[] = $wpdb->prepare( 'posts.post_parent=%d', $parent );
		}		

		if( $status && $status != 'all' ){
			$where[] = $wpdb->prepare( 'encoder.status=%s', $status );
		}		

		if( $author ){
			$where[] = $wpdb->prepare( 'posts.post_author=%d', $author );
		}

		$where = join( ' AND ', $where );

		$sql = "
			SELECT 
			    posts.ID AS ID,
			    posts.post_title AS name,
			    posts.post_author AS author,
			    posts.post_mime_type AS mime_type,
			    posts.post_parent as parent,
			    encoder.pid AS pid,
			    encoder.format AS format,
			    encoder.status AS status,
			    encoder.date AS date,
			    encoder.date_modified AS date_modified
			FROM
			    {$wpdb->prefix}video_encoder AS encoder
			        INNER JOIN
			    {$wpdb->prefix}posts AS posts ON encoder.attachment_id = posts.ID
			WHERE {$where}
			ORDER BY date ASC LIMIT {$limit}
		";

		$results = $wpdb->get_results( $sql, ARRAY_A );

		if( $results ){
			for ( $i=0; $i < count( $results ); $i++ ) {

				$encoder = new WP_Video_Encoder_Encoder( 
					get_attached_file( $results[$i]['ID'] ),
					$this->settings['bin_path'],
					$this->settings['nice_path']
				);

				$encode_status 						= $encoder->get_encode_file_status();

				$results[$i]['percentage'] 			= $encoder->get_encoded_percentage();
				$results[$i]['encode_log_status']	= is_wp_error( $encode_status ) ? $encode_status->get_error_message() : $encode_status;
				$results[$i]['parent_url']			= get_permalink( $results[$i]['parent'] );
				$results[$i]['parent_name']			= get_the_title( $results[$i]['parent'] );

				if( has_post_thumbnail( $results[$i]['ID'] ) ){
					$results[$i]['thumbnail'] = get_the_post_thumbnail_url( $results[$i]['ID'], 'streamtube-image-medium' );
				}

				if( current_user_can( 'administrator' ) ){
					$results[$i]['encode_log']		= $encoder->get_log_file_content();	
				}
			}
		}

		return $results;
	}

	/**
	 *
	 * Get queue item
	 * 
	 * @param  int $attachment_id
	 * @param  string $status
	 * @return array
	 *
	 * @since  1.0.0
	 * 
	 */
	public function get_queue_item( $attachment_id, $status = '' ){

		$item = $this->get_queue_items( compact( 'attachment_id', 'status' ) );

		if( is_array( $item ) && count( $item ) > 0 ){
			$item = $item[0];
		}

		return $item;
	}

	public function filter_waiting_items(){

		$items = $this->get_queue_items( array(
			'limit'		=>	10,
			'status'	=>	'waiting'
		) );

		if( ! $items ){
			return $items;
		}

		for ($i=0; $i < count( $items ); $i++) { 
			for ( $i = 0; $i < count( $items ); $i++ ) {
				if( $items[$i]['status'] == 'waiting' ){
					$file = get_attached_file( $items[$i]['ID'] );

					if( is_dir( $file ) ){
						$this->delete_queue_item( $items[$i]['ID'] );
					}
				}
			}
		}
	}

	/**
	 *
	 * Run the queue items
	 * 
	 * @since 1.0.0
	 * 
	 */
	public function run_queue_items(){

		$args = array(
			'limit'		=>	$this->settings['max_threads'],
			'status'	=>	isset( $_GET['status'] ) ? sanitize_text_field( $_GET['status'] ) : 'encoding',
			'parent'	=>	isset( $_GET['parent'] ) ? (int)( $_GET['parent'] ) : null,
		);

		if( ! current_user_can( 'edit_others_posts' ) ){
			$args['author'] = get_current_user_id();
		}

		/**
		 * Filter the args
		 * @since 1.0.0
		 */
		$args = apply_filters( 'wp_video_encoder_run_queue_args', $args );

		$encoding_items = $waiting_items = array();

		$encoding_items = $this->get_queue_items( $args );

		if( $encoding_items ){

			for ( $i = 0; $i < count( $encoding_items ); $i++ ) {

				$percentage = absint( $encoding_items[$i]['percentage'] );

				if( is_string( $encoding_items[$i]['encode_log_status'] ) ){
					$this->update_queue_item( $encoding_items[$i]['ID'], 'failed' );

					/**
					 *
					 * Fires after file encoded successfully
					 *
					 * @param int attachment_id
					 * @param queue item
					 *
					 * @since  1.0.0
					 * 
					 */
					do_action( 'wpve_video_encoded_failed', $encoding_items[$i]['ID'], (object)$encoding_items[$i] );
				}
				else{
					if( $percentage >= 99 ){
						$this->update_queue_item( $encoding_items[$i]['ID'], 'encoded' );

						$encoding_items[$i]['percentage'] = 100;

						if( $this->settings['publish_parent'] ){							
							wp_update_post( array(
								'ID'			=>	$encoding_items[$i]['parent'],
								'post_status'	=>	'publish'
							) );
						}

						/**
						 *
						 * Fires after file encoded successfully
						 *
						 * @param int attachment_id
						 * @param queue item
						 *
						 * @since  1.0.0
						 * 
						 */
						do_action( 'wpve_video_encoded_success', $encoding_items[$i]['ID'], (object)$encoding_items[$i] );
					}					
				}
			}
		}

		if( ! $encoding_items || count( $encoding_items ) < absint( $args['limit'] ) ){

			$waiting_items = $this->get_queue_items( array_merge( $args, array(
				'status'	=>	'waiting',
				'limit'		=>	absint( $args['limit'] ) - ( ! $encoding_items ? 0 : count( $encoding_items ) )
			) ));

			if( $waiting_items ){
				for ( $i = 0; $i < count( $waiting_items ); $i++ ) { 
					$this->encode_queue_item( $waiting_items[$i]['ID'] );
				}
			}
		}

		return array_merge( $encoding_items, $waiting_items );
	}

	/**
	 *
	 * Encode the item in the queue
	 * 
	 * @param  int $attachment_id
	 * @since 1.0.0
	 * 
	 */
	private function encode_queue_item( $attachment_id ){

		$encoder = new WP_Video_Encoder_Encoder( 
			get_attached_file( $attachment_id ),
			$this->settings['bin_path'],
			$this->settings['nice_path']
		);

		$renditions = array();

		if( $this->settings['res_426x240'] ){
			$renditions['426x240'] = array( '400k', '64k' );
		}

		if( $this->settings['res_640x360'] ){
			$renditions['640x360'] = array( '800k', '96k' );
		}

		if( $this->settings['res_854x480'] ){
			$renditions['854x480'] = array( '1400k', '128k' );
		}

		if( $this->settings['res_1280x720'] ){
			$renditions['1280x720'] = array( '2800k', '128k' );
		}

		if( $this->settings['res_1920x1080'] ){
			$renditions['1920x1080'] = array( '4000k', '192k' );
		}

		if( $this->settings['res_2560x1440'] ){
			$renditions['2560x1440'] = array( '6000k', '192k' );
		}

		if( $this->settings['res_3840x2160'] ){
			$renditions['3840x2160'] = array( '8000k', '192k' );
		}

		$encoder->set_renditions( $renditions );

		$encoder->set_video_codec( $this->settings['vcodec'] );

		if( $this->settings['vcodec'] == 'h264' ){
			$encoder->set_h264_profile( $this->settings['h264_profile'] );

			if( $this->settings['rate_control'] == 'crf' ){
				$encoder->set_h264_crf( (int)$this->settings['h264_crf'] );
			}

			if( ! empty( $this->settings['moov'] ) ){
				if( $this->settings['moov'] == 'movflag' ){
					$encoder->set_h264_moov( $this->settings['moov'] );
				}
			}
		}

		if( $this->settings['hls_playlist_type'] ){
			$encoder->set_hls_playlist_type( $this->settings['hls_playlist_type'] );
		}

		if( $this->settings['hls_segment_type'] ){
			$encoder->set_hls_segment_type( $this->settings['hls_segment_type'] );
		}

		if( $this->settings['hls_flags'] ){
			$encoder->set_hls_flags( $this->settings['hls_flags'] );	
		}
		
		if( $this->settings['strict_2'] ){
			$encoder->set_strict_2( $this->settings['strict_2'] );
		}

		if( $this->settings['extra_params'] ){
			$encoder->set_extra_params( $this->settings['extra_params'] );
		}

		if( $this->settings['nice'] ){
			$encoder->set_nice( true );

			$encoder->set_nice_path( $this->settings['nice_path'] );

			// Set nice priority
			$nice_priority = (int)$this->settings['nice_priority'];

			if( $nice_priority == 0 ){
				$nice_priority = 10;
			}
			$encoder->set_nice_priority( $nice_priority );
		}

		if( $this->settings['hls_encrypt'] && wp_video_encoder()->get()->encryption->valid_file_key_info() ){
			$encoder->set_hls_file_keyinfo( wp_video_encoder()->get()->encryption->get_endpoint() );
		}

		$encoder->set_hls_segment_folder_name( $this->settings['hls_segment_folder_name'] );

		/**
		 *
		 * Filter watermark
		 * 
		 */
		$watermark = apply_filters( 'wpve_watermark_url', $this->settings['watermark'], $attachment_id );

		if( $watermark ){
			$encoder->set_watermark( $watermark );	
			$encoder->set_watermark_position( $this->settings['watermark_position'] );
			$encoder->set_watermark_padding( $this->settings['watermark_padding'] );
			$encoder->set_watermark_opacity( $this->settings['watermark_opacity'] );
			$encoder->set_watermark_size( $this->settings['watermark_size'] );
			$encoder->set_watermark_size_percentage( $this->settings['watermark_size_percentage'] );
		}

		$encoder->set_encoder_version( $this->settings['encoder_version'] );

		$results = $encoder->generate_hls_stream();

		if( is_wp_error( $results ) ){
			return $results;
		}

		if( is_int( $results ) && $results < 0 ){
			return $results;
		}

		if(  $encoder->get_result_code() == 0 ){
			/**
			 *
			 * Fires after video being encoded
			 *
			 * @param  int $attachment_id
			 *
			 * @since  1.0.0
			 * 
			 */
			do_action( 'wpve_video_being_encoded', $attachment_id );

			return $this->update_queue_item( $attachment_id, 'encoding' );
		}

		if( is_array( $results ) || is_object( $results ) ){
			error_log( print_r( $results ) );
		}
		else{
			error_log( $results );
		}

		return $results;
	}

	/**
	 * Run WP_Cron to check the queue
	 * 
	 * @return $this->run_queue_items();
	 *
	 * @since 1.0.0
	 * 
	 */
	public function cron_run_queue_items(){
		return $this->run_queue_items();
	}

	public function ajax_check_encode_queue(){

		if( current_user_can( 'administrator' ) ){
			$this->filter_waiting_items();
		}

		$results = $this->run_queue_items();

		echo json_encode( $results );

		exit;
	}
}