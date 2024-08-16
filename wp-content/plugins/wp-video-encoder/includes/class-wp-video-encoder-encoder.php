<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * Define the FFmpeg functionality.
 *
 *
 * @since      1.0.0
 * @package    WP_Video_Encoder
 * @subpackage WP_Video_Encoder/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */
class WP_Video_Encoder_Encoder{

	protected $encoder_version					=	'v2';

	/**
	 *
	 * Holds the file path
	 * 
	 * @var string
	 */
	protected $file_path 						=	'';

	protected $file_data 						=	array();

	/**
	 *
	 * Holds the file ino
	 * 
	 * @var array
	 */
	protected $file_info 						=	array();

	/**
	 *
	 * Holds the watermark file
	 * 
	 * @var string
	 */
	protected $watermark 						=	'';

	/**
	 *
	 * Holds the watermark position
	 * 
	 * @var string
	 */
	protected $watermark_position				=	'top_right';

	/**
	 *
	 * Holds the watermark padding
	 * 
	 * @var array
	 */
	protected $watermark_padding				=	array( 20, 20 );

	/**
	 *
	 * Holds the watermark opacity
	 * 
	 * @var int|float
	 */
	protected $watermark_opacity				=	1;

	/**
	 *
	 * Holds the watermark size
	 * 
	 * @var string
	 */
	protected $watermark_size					=	'fixed';

	/**
	 *
	 * Holds the watermark size percentage
	 * 
	 * @var string
	 */
	protected $watermark_size_percentage		=	'0.3';	

	/**
	 *
	 * Holds the video codec
	 * 
	 * @var string
	 */
	protected $video_codec  					=	'h264';//libx264

	/**
	 *
	 * Holds the audio codec
	 * 
	 * @var string
	 */
	protected $audio_codec  					=	'aac';

	/**
	 *
	 * Holds the profile
	 * 
	 * @var string
	 */
	protected $h264_profile 					=	'baseline';

	protected $h264_crf							=	23;

	protected $h264_moov						=	'';

	/**
	 *
	 * Holds the file keyinfo URL
	 * 
	 * @var string
	 *
	 * @since 1.1
	 * 
	 */
	protected $hls_file_keyinfo					=	'';

	/**
	 *
	 * holds the hls playlist type
	 * 
	 * @var string
	 */
	protected $hls_playlist_type				=	'vod';

	/**
	 *
	 * Holds the hls segment type
	 * 
	 * @var string
	 */
	protected $hls_segment_type					=	'mpegts';

	/**
	 *
	 * Holds the hls segment type
	 * 
	 * @var string
	 */
	protected $hls_segment_file_name			=	'%003d.ts';

	/**
	 *
	 * Holds the segment folder name
	 * 
	 * @var string
	 */
	protected $hls_segment_folder_name			=	'resolution'; // or resolution

	/**
	 *
	 * Holds the hls flag
	 * 
	 * @var string
	 */
	protected $hls_flags 						=	''; // independent_segments

	/**
	 *
	 * Holds the strict 2
	 * 
	 * @var string
	 */
	protected $strict_2							=	'';

	/**
	 *
	 * Holds the extra params
	 * 
	 * @var string
	 */
	protected $extra_params						=	'';

	/**
	 *
	 * Try to create a new segment every X seconds
	 * 
	 * @var integer
	 */
	protected $segment_target_duration 			=	4;

	/**
	 *
	 * Maximum accepted bitrate fluctuations
	 * 
	 * @var float
	 */
	protected $max_bitrate_ratio				=	1.07;

	/**
	 *
	 * Maximum buffer size between bitrate conformance checks		
	 * 
	 * @var float
	 */
	protected $rate_monitor_buffer_ratio		=	1.5;

	/**
	 *
	 * Holds the renditions
	 * 
	 * @var array
	 */
	protected $renditions2 						=	array(
		'426x240'	=>	array( '400k', '64k' ),
		'640x360'	=>	array( '800k', '96k' ),
		'854x480'	=>	array( '1400k', '128k' ),
		'1280x720'	=>	array( '2800k', '128k' )
	);

	protected $renditions						=	array();

	/**
	 *
	 * Holds the exec result output
	 * 
	 * @var string
	 */
	protected $encode_output 					=	'';

	/**
	 *
	 * Holds the exec result code
	 * 
	 * @var array
	 */
	protected $result_code 						=	array();

	/**
	 *
	 * holds the log file path
	 * 
	 * @var string
	 */
	private $log_file 							=	'log.log';

	/**
	 *
	 * Holds the hls master playlist
	 * 
	 * @var string
	 */
	private $file_master 						=	'playlist.m3u8';

	/**
	 *
	 * Holds the application path
	 * 
	 * @var string
	 *
	 * @since  1.0.0
	 * 
	 */
	protected $bin_path							=	'/usr/bin/';

	/**
	 *
	 * Holds the nice
	 * 
	 * @var string
	 *
	 * @since  1.0.0
	 * 
	 */
	protected $nice								=	false;	

	/**
	 *
	 * Holds the nice application path
	 * 
	 * @var string
	 *
	 * @since  1.0.0
	 * 
	 */
	protected $nice_path						=	'/usr/bin/';	

	/**
	 *
	 * Holds the nice shell option
	 * 
	 * @var string
	 *
	 * @since  1.0.0
	 * 
	 */	
	protected $nice_priority 					=	10;

	/**
	 * The class contructor
	 *
	 * @since    1.0.0
	 * @access   protected
	 */	
	public function __construct( $file_path = null, $bin_path = '', $nice_path = '' ){

		$this->file_path 		= $file_path;

		$this->file_info 		= pathinfo( $file_path );

		if( $bin_path ){
			$this->bin_path 		= $bin_path;	
		}

		if( $nice_path ){
			$this->nice_path 		= $nice_path;	
		}
	}

	/**
	 *
	 * Set encoder version
	 * 
	 * @param string $version
	 */
	public function set_encoder_version( $version = 'v2' ){
		$this->encoder_version = $version;
	}

	/**
	 *
	 * Set file path
	 * 
	 * @param string $file_path
	 *
	 * @since 1.0.5
	 * 
	 */
	public function set_file_path( $file_path ){
		$this->file_path 		= $file_path;

		$this->file_info 		= pathinfo( $file_path );
	}

	/**
	 *
	 * Get file metadata
	 * 
	 * @return false|array
	 *
	 * @since 1.0.7.5
	 * 
	 */
	public function read_file_metadata(){
		if( ! function_exists( 'wp_read_video_metadata' ) ){
			require_once( ABSPATH . 'wp-admin/includes/media.php' );
		}

		return wp_read_video_metadata( $this->file_path );
	}

	/**
	 *
	 * Get file width
	 * 
	 * @return int e.g: 320
	 *
	 * @since 1.0.7.7
	 * 
	 */
	public function get_file_width(){
		$metadata = $this->read_file_metadata();

		if( ! $metadata || ! is_array( $metadata ) ){
			return false;
		}

		return array_key_exists( 'width', $metadata ) ? (int)$metadata['width'] : false;
	}

	/**
	 *
	 * Get file height
	 * 
	 * @return int e.g: 240
	 *
	 * @since 1.0.7.7
	 * 
	 */
	public function get_file_height(){
		$metadata = $this->read_file_metadata();

		if( ! $metadata || ! is_array( $metadata ) ){
			return false;
		}

		return array_key_exists( 'height', $metadata ) ? (int)$metadata['height'] : false;
	}

	/**
	 *
	 * Get file length
	 * 
	 * @return int e.g: 120
	 *
	 * @since 1.0.7.7
	 * 
	 */
	public function get_file_length(){
		$metadata = $this->read_file_metadata();

		if( ! $metadata || ! is_array( $metadata ) ){
			return false;
		}

		return array_key_exists( 'length', $metadata ) ? (int)$metadata['length'] : false;
	}

	/**
	 *
	 * Get file resolution
	 * 
	 * @return string e.g: 320x240
	 *
	 * @since 1.0.7.5
	 * 
	 */
	public function get_file_resolution(){

		$metadata = $this->read_file_metadata();

		if( ! $metadata || ! is_array( $metadata ) ){
			return false;
		}

		if( ! array_key_exists( 'width', $metadata ) || ! array_key_exists( 'height', $metadata ) ){
			return false;
		}

		return sprintf(
			'%sx%s',
			$metadata['width'],
			$metadata['height']
		);
	}

	/**
	 *
	 * Get file audio
	 * 
	 * @return array
	 * 
	 */
	public function get_file_audio(){
		$metadata = $this->read_file_metadata();

		if( ! $metadata || ! is_array( $metadata ) ){
			return false;
		}

		return array_key_exists( 'audio', $metadata ) ? $metadata['audio'] : false;
	}

	/**
	 *
	 * Write file metadata if not existed
	 * 
	 * @return array|WP_Error
	 */
	public function write_file_metadata(){

		$folder = $this->create_file_folder();

		if( ! $folder ){
			return new WP_Error(
				'cannot_create_folder',
				esc_html__( 'Cannot create folder.', 'wp-video-encoder' )
			);
		}		

		$resolution = $this->get_file_resolution();

		if( ! $resolution ){

			$new_file = trailingslashit( $folder ) . current_time( 'timestamp' ) . basename($this->file_path);

			$this->exec( "ffmpeg -i {$this->file_path} -c copy -map 0 -metadata Title='' {$new_file}" );

			if( file_exists( $new_file ) ){
				@unlink( $this->file_path );
				rename( $new_file, $this->file_path );
			}
		}

		return $this->read_file_metadata();
	}		

	/**
	 *
	 * Get folder of given file
	 * 
	 * @param  string $subfolder
	 * @return string
	 *
	 * @since 1.0.5
	 * 
	 */
	public function get_file_folder(){

		return trailingslashit( dirname( $this->file_path ) ) . sanitize_file_name( $this->file_info['filename'] );
	}

	/**
	 *
	 * Create folder of given file
	 * 
	 * @return true|false
	 *
	 * @since 1.0.5
	 * 
	 */
	public function create_file_folder(){

		$folder = $this->get_file_folder();

		if( ! file_exists( $folder ) ){
			mkdir( $folder, 0777, true );
		}

		return file_exists( $folder ) ? $folder : false;
	}

	/**
	 *
	 * Delete folder of given file
	 * 
	 * @return rmdir|false
	 *
	 * @since 1.0.5
	 * 
	 */
	public function delete_file_folder(){
		return is_dir( $this->get_file_folder() ) ? rmdir( $this->get_file_folder() ) : false;
	}

	/**
	 *
	 * Delete all encoded files.
	 * 
	 */
	public function delete_encoded_files(){

		$path = trailingslashit($this->get_file_folder());

		@unlink( $path . 'playlist.m3u8' );
		@unlink( $path . 'log.log' );

		array_map( 'unlink', array_filter((array) glob( $path . '*.ts' ) ) );
		array_map( 'unlink', array_filter((array) glob( $path . '*.m3u8' ) ) );

		foreach ( glob( $path . '/stream*' ) as $stream ) {
			if( is_dir( $stream ) ){
				array_map( 'unlink', array_filter((array) glob( $stream . '/*.ts' ) ) );
				array_map( 'unlink', array_filter((array) glob( $stream . '/*.m3u8' ) ) );
				rmdir( $stream );
			}
		}

		if( count( glob( $path . '*' ) ) == 0 ){
			if( function_exists( 'rmdir' ) ){
				rmdir( $path );
			}
		}	
	}

	/**
	 *
	 * Set bin bath
	 * 
	 * @param string $bin_path
	 *
	 * @since  1.0.0
	 * 
	 */
	public function set_bin_path( $bin_path = '' ){
		$this->bin_path = $bin_path;
	}

	/**
	 *
	 * Set renditions
	 * 
	 * @param array $renditions
	 *
	 * @since 1.0.5
	 * 
	 */
	public function set_renditions( $renditions ){
		$this->renditions = $renditions;
	}

	/**
	 *
	 * Get file rendition
	 * 
	 * @return array
	 *
	 * @since 1.0.7.7
	 * 
	 */
	public function get_file_rendition(){

		$bitrate 		=	'400k';
		$audiorate 		=	'64k';

		$resolution = $this->get_file_resolution();

		if( ! $resolution ){
			return false;
		}

		$resolution = explode( 'x' , $resolution );

		if( min( $resolution[1], 240 ) <= 240 ){
			$bitrate = '400k';
			$audiorate = '64k';
		}

		if( 240 < $resolution[1] && $resolution[1] <= 360 ){
			$bitrate = '8000k';
			$audiorate = '96k';
		}

		if( 360 < $resolution[1] && $resolution[1] <= 480 ){
			$bitrate = '1400k';
			$audiorate = '128k';
		}

		if( 480 < $resolution[1] && $resolution[1] <= 720 ){
			$bitrate = '2800k';
			$audiorate = '128k';
		}

		if( 720 < $resolution[1] && $resolution[1] <= 1080 ){
			$bitrate = '4000k';
			$audiorate = '192k';
		}

		if( 1080 < $resolution[1] && $resolution[1] <= 1440 ){
			$bitrate = '6000k';
			$audiorate = '192k';
		}

		if( 1440 < $resolution[1] && $resolution[1] <= 2160 ){
			$bitrate = '8000k';
			$audiorate = '192k';
		}

		if( 2160 < $resolution[1] ){
			$bitrate = '10000k';
			$audiorate = '250k';
		}

		$resolution = sprintf( '%sx%s', $resolution[0], $resolution[1] );

		return array(
			$resolution	=>	array( $bitrate, $audiorate )
		);
	}

	/**
	 *
	 * Set video codec
	 * 
	 * @param string $codec
	 *
	 * @since 1.0.5
	 * 
	 */
	public function set_video_codec( $codec ){
		$this->video_codec  = $codec;
	}

	/**
	 *
	 * Set audio codec
	 * 
	 * @param string $codec
	 *
	 * @since 1.0.5
	 * 
	 */
	public function set_audio_codec( $codec ){
		$this->audio_codec  = $codec;
	}

	/**
	 *
	 * Set profile
	 * 
	 * @param string $profile
	 *
	 * @since 1.0.5
	 * 
	 */
	public function set_h264_profile( $profile ){
		$this->h264_profile  = $profile;
	}

	/**
	 *
	 * Set crf
	 * 
	 * @param string $profile
	 *
	 * @since 1.0.5
	 * 
	 */
	public function set_h264_crf( $crf ){
		$this->h264_crf  = $crf;
	}	

	/**
	 *
	 * Set moov
	 * 
	 * @param string $moov
	 *
	 * @since 1.0.5
	 * 
	 */
	public function set_h264_moov( $moov ){
		$this->h264_moov  = $moov;
	}		

	/**
	 *
	 * Set hls key fileinro URL
	 * 
	 * @param string $url
	 *
	 * @since 1.1
	 * 
	 */
	public function set_hls_file_keyinfo( $url ){
		$this->hls_file_keyinfo = $url;
	}

	/**
	 *
	 * Set hls playlist type
	 * 
	 * @param string $type
	 *
	 * @since 1.0.5
	 * 
	 */
	public function set_hls_playlist_type( $type ){
		$this->hls_playlist_type = $type;
	}

	/**
	 *
	 * Set hls segment type
	 * 
	 * @param string $type
	 *
	 * @since 1.0.5
	 * 
	 */
	public function set_hls_segment_type( $type ){
		$this->hls_segment_type = $type;
	}

	public function set_hls_segment_folder_name( $name = 'index' ){
		$this->hls_segment_folder_name = $name;
	}

	/**
	 *
	 * Set hls flag
	 * 
	 * @param string $type
	 *
	 * @since 1.0.5
	 * 
	 */
	public function set_hls_flags( $flag ){
		$this->hls_flags = $flag;
	}	

	/**
	 *
	 * Set strict 2 param
	 * 
	 * @param string $params;
	 *
	 * @since 1.0.7.3
	 * 
	 */
	public function set_strict_2( $on ){
		$this->strict_2  = $on;
	}

	/**
	 *
	 * Set extra params
	 * 
	 * @param string $params;
	 *
	 * @since 1.0.7.3
	 * 
	 */
	public function set_extra_params( $params ){
		$this->extra_params  = $params;
	}

	/**
	 *
	 * Set segment target duration
	 * 
	 * @param int $number
	 *
	 * @since 1.0.5
	 * 
	 */
	public function set_segment_target_duration( $number ){
		$this->segment_target_duration = $number;
	}		

	/**
	 *
	 * Set Maximum accepted bitrate fluctuations
	 * 
	 * @param int $number
	 *
	 * @since 1.0.5
	 * 
	 */
	public function set_max_bitrate_ratio( $number ){
		$this->max_bitrate_ratio = $number;
	}

	/**
	 *
	 * Set Maximum buffer size between bitrate conformance checks
	 * 
	 * @param int $number
	 *
	 * @since 1.0.5
	 * 
	 */
	public function set_rate_monitor_buffer_ratio( $number ){
		$this->rate_monitor_buffer_ratio = $number;
	}

	/**
	 *
	 * Set nice
	 * 
	 * @param true|false
	 *
	 * @since 1.0.5
	 * 
	 */
	public function set_nice( $boolean ){
		$this->nice = $boolean;
	}

	/**
	 *
	 * Set nice path
	 * 
	 * @param string $path
	 *
	 * @since 1.0.5
	 * 
	 */
	public function set_nice_path( $path ){
		$this->nice_path = $path;
	}

	/**
	 *
	 * Set nice priprity
	 * 
	 * @param int $priority
	 *
	 * @since 1.0.5
	 * 
	 */
	public function set_nice_priority( $priority ){
		$this->nice_priority = $priority;
	}

	/**
	 *
	 * Set watermark
	 * 
	 * @param string $file
	 */
	public function set_watermark( $file = '' ){
		// Accept an external URL or local file
		$this->watermark = $file;	
	}

	/**
	 *
	 * Set watermark position
	 * 
	 * @param string $position
	 */
	public function set_watermark_position( $position = "top_right" ){
		$this->watermark_position = $position;
	}

	/**
	 *
	 * Set watermark padding
	 * 
	 * @param string $padding
	 */
	public function set_watermark_padding( $padding = "20:20" ){

		$padding = array_map('intval', explode(':', $padding));

		if( is_array( $padding ) && count( $padding ) == 2 ){

			$this->watermark_padding = $padding;
		}

	}

	/**
	 *
	 * Set watermark opacity
	 * 
	 * @param string $opacity
	 */
	public function set_watermark_opacity( $opacity = 1 ){
		$this->watermark_opacity = (float)$opacity;
	}

	/**
	 *
	 * Set watermark size
	 * 
	 * @param string $size
	 */
	public function set_watermark_size( $size = 'fixed' ){

		if( ! in_array( $size, array( 'fixed', 'percentage_w', 'percentage_h' ) ) ){
			$size = 'fixed';
		}

		$this->watermark_size = $size;
	}

	/**
	 *
	 * Set watermark size percentage
	 * 
	 * @param string $percentage
	 */
	public function set_watermark_size_percentage( $percentage = '0.3' ){
		$this->watermark_size_percentage = (float)$percentage;
	}

	/**
	 *
	 * Get exec output
	 *
	 * @return null|string
	 *
	 * @since  1.0.0
	 * 
	 */
	public function get_encode_output(){
		return $this->encode_output;
	}	

	/**
	 *
	 * Get exec result_code
	 *
	 * @return int 0 if success
	 *
	 * @since  1.0.0
	 * 
	 */
	public function get_result_code(){
		return $this->result_code;
	}

	/**
	 *
	 * Get the log file
	 * 
	 * @return string
	 *
	 * @since  1.0.0
	 * 
	 */
	public function get_log_file(){
		return trailingslashit( $this->get_file_folder() ) . $this->log_file;
	}

	/**
	 *
	 * Get encode log file
	 * 
	 * @return log file path
	 *
	 * @since  1.0.0
	 * 
	 */
	public function get_log_file_content(){

		$log_file = $this->get_log_file();

		return file_exists( $log_file ) ? trim( file_get_contents( $log_file ) ) : false;
	}

	/**
	 *
	 * Get the m3u8 playlist file
	 * 
	 * @return string
	 *
	 * @since  1.0.0
	 * 
	 */
	public function get_encode_playlist_file(){
		return trailingslashit( $this->get_file_folder() ) . $this->file_master;
	}
	
	/**
	 *
	 * Get encoded length in seconds
	 * 
	 * @return number
	 *
	 * @since  1.0.0
	 * 
	 */
	public function get_encoded_percentage(){

		$log_file = $this->get_log_file_content();

		if( ! $log_file ){
			return 0;
		}

		$matches = array();

		$file_metadata = (array)$this->read_file_metadata( $this->file_path );

		preg_match_all("/time=(.*?) bitrate=/", $log_file, $matches );

		if( $matches[1] ){

			$file_metadata = wp_parse_args( $file_metadata, array(
				'length'	=>	0
			) );

			$lengthed = 0;

			$last = count( $matches[1] ) - 1;

			$encoded_length = explode( ":", trim( $matches[1][ $last ] ) );

			$lengthed += $encoded_length[0]*60*60;
			$lengthed += $encoded_length[1]*60;
			$lengthed += ceil($encoded_length[2]);

			if( (int)$file_metadata['length'] == 0 ){
				return 0;
			}

			return min( 100, round( $lengthed*100/ (int)$file_metadata['length'] ) );
		}

		return 0;
	}

	/**
	 *
	 * Retrieve encode status from the log file.
	 * 
	 * @return string
	 */
	public function get_encode_file_status(){

		$log = $this->get_log_file_content();

		if( empty( $log ) ){
			return null;
		}

		$errors = array( 
			'hard exiting', 
			'Conversion failed!', 
			'not found',
			'At least one output file must be specified',
			'No such file or directory'
		);

		for ( $i = 0; $i < count( $errors  ); $i++) { 
			if( strpos( $log, $errors[$i] ) !== false ){
				return new WP_Error( sanitize_key( $errors[$i] ), $errors[$i] );
			}
		}		

		return true;
	}

	/**
	 *
	 * Get master playlist file
	 * 
	 * @return string
	 */
	public function get_file_master(){
		return $this->file_master;
	}

	/**
	 *
	 * Build HLS params
	 * 
	 * @return array
	 */
	private function build_hls_params(){

		$folder 		= $this->get_file_folder();

		$params[] 		= "-f hls";
		$params[] 		= "-hls_time {$this->segment_target_duration}";

		if( $this->hls_file_keyinfo ){
			$params[] = "-hls_key_info_file " . $this->hls_file_keyinfo;
		}

		if( $this->hls_playlist_type ){
			$params[] = "-hls_playlist_type {$this->hls_playlist_type}";
		}

		if( $this->hls_segment_type ){
			$params[] = "-hls_segment_type {$this->hls_segment_type}";
		}

		if( $this->hls_flags ){

			switch ( $this->hls_flags ) {
				case 'independent_segments':
					$params[] = "-hls_flags " . $this->hls_flags;
					$this->hls_segment_file_name = '%02d.ts';
				break;
				
				case 'second_level_segment_index':
					$params[] = "-strftime 1 -hls_flags " . $this->hls_flags;

					$this->hls_segment_file_name = '%Y%m%d_%%04d.ts';
				break;

				case 'second_level_segment_size':

					$params[] = "-strftime 1 -hls_flags " . $this->hls_flags;

					$this->hls_segment_file_name = '%%08s.ts';
				break;

				case 'second_level_segment_duration':
					$params[] = "-strftime 1 -hls_flags " . $this->hls_flags;

					$this->hls_segment_file_name = '%%013t.ts';
				break;
			}
		}

		$params[] = "-hls_segment_filename {$folder}/stream_%v/data_" . $this->hls_segment_file_name;

		$params[] = "-master_pl_name /playlist.m3u8";

		return $params;
	}

	/**
	 *
	 * @param  string $cmd
	 * @return int
	 *
	 * @since  1.0.0
	 * 
	 */
	private function exec( $cmd ){

		if( ! function_exists( 'exec' ) ){
			return new WP_Error(
				'exec_not_found',
				esc_html__( 'Exec function was not found.', 'wp-video-encoder' )
			);
		}

		$this->bin_path = trailingslashit( $this->bin_path );

		$cmd = $this->bin_path . $cmd;

		if( $this->nice && $this->nice_path ){
			$cmd = sprintf(
				'%snice -n %s %s',
				trailingslashit( $this->nice_path ),
				$this->nice_priority,
				$cmd
			);
		}

		return exec( $cmd, $this->encode_output, $this->result_code );
	}

	/**
	 * Generate unique file name
	 */
	private function unique_filename( $file_base, $suffix, $number = 0 ){

		if( $number > 0 ){
			$file_path = $file_base . "_{$number}{$suffix}";
		}
		else{
			$file_path = $file_base . $suffix;
		}
		
		if( file_exists( $file_path ) ){
			$number++;
			return $this->unique_filename( $file_base ,$suffix, $number );
		}

		return $file_path;
	}

	/**
	 *
	 * Get ffmpeg info for debugging purpose
	 * 
	 * @return string
	 *
	 * @since 1.1
	 * 
	 */
	public function get_ffmpeg_info(){

		return array(
			'version'	=>	$this->exec( 'ffmpeg -version | grep "ffmpeg version"' ),
			'hls'		=>	$this->exec( 'ffmpeg -formats | grep hls' ),
			'segments'	=>	$this->exec( 'ffmpeg -formats | grep segment' ),
			'h264'		=>	$this->exec( 'ffmpeg -formats | grep h264' ),
		);
	}		

	/**
	 *
	 * Create an image from given video
	 *
	 * @param  string $at_frame
	 *
	 * @return image path
	 *
	 * @since 1.0.0
	 * 
	 */
	public function generate_image( $at_frame = '00:00:01' ){

		$metadata = $this->write_file_metadata();

		if( is_wp_error( $metadata ) ){
			return $metadata;
		}

		if( ! $metadata ){
			return new WP_Error(
				'metadata_not_found',
				esc_html__( 'Metadata was not found', 'wp-video-encoder' )
			);				
		}

		$folder = $this->get_file_folder();

		$image_base = trailingslashit( $folder ) . sanitize_file_name( $this->file_info['filename'] );	

		$image_path = $this->unique_filename( $image_base, '_thumbnail.jpg' );

		$this->exec( "ffmpeg -i {$this->file_path} -ss {$at_frame} -f image2 -vframes 1 {$image_path}" );

		return $image_path;
	}

	/**
	 * 
	 * Generate webp image
	 * 
	 * @param  string $start
	 * @param  string $to
	 * @return string $image_path
	 *
	 * @since 1.0.5
	 * 
	 */
	public function generate_image_webp( $args = array() ){
		$args = wp_parse_args( $args, array(
			'start'			=>	'00:00:01',
			'time'			=>	05,
			'resolution'	=>	'640x360',
			'loop'			=>	1,
			'setpts'		=>	'0.4',
			'type'			=>	'webp'// or gif
		) );

		$metadata = $this->write_file_metadata();

		if( is_wp_error( $metadata ) ){
			return $metadata;
		}

		if( ! $metadata ){
			return new WP_Error(
				'metadata_not_found',
				esc_html__( 'Metadata was not found', 'wp-video-encoder' )
			);				
		}

		$folder = $this->get_file_folder();		

		if( ! $args['type'] || ! in_array( $args['type'], array( 'webp', 'gif' ) ) ){
			$args['type'] = 'webp';
		}

		if( empty( $args['resolution'] ) ){
			$args['resolution'] = '640x360';
		}

		if( (int)$metadata['width'] < (int)$metadata['height'] ){
			$_resolution = explode( 'x' , $args['resolution'] );

			$args['resolution'] = sprintf( '%sx%s', $_resolution[1], $_resolution[0] );
		}

		$args['resolution'] = explode("x", $args['resolution'] );

		if( false === $length = $this->get_file_length() ){
			return new WP_Error(
				'file_length_not_found',
				esc_html__( 'File length was not found.', 'wp-video-encoder' )
			);
		}

		$args['end'] = '00:00:' . absint( min( absint( $args['time'] ), absint( $length ) ) );

		extract( $args );

		$folder = $this->get_file_folder();

		$image_base = trailingslashit( $folder ) . sanitize_file_name( $this->file_info['filename'] ) ;

		$image_path = $this->unique_filename( $image_base, '_webp_thumbnail.' . $type );

		$cmd = "ffmpeg -i {$this->file_path}";
		$cmd .= " -ss {$start}";
		$cmd .= " -t {$end}";
		$cmd .= " -filter:v 'setpts={$setpts}*PTS,scale={$args['resolution'][0]}:-1'";
		$cmd .= " -loop {$loop}";
		$cmd .= " -preset picture";
		$cmd .= " {$image_path}";

		$exec = $this->exec( $cmd );

		if( is_wp_error( $exec ) ){
			return $exec;
		}

		return $image_path;
	}

	/**
	 *
	 * Create HLS playlist
	 * 
	 * @since 1.0.0
	 * 
	 */
	public function generate_hls_stream_v1(){

		$metadata = $this->write_file_metadata();

		if( is_wp_error( $metadata ) ){
			return $metadata;
		}

		if( ! $metadata ){
			return new WP_Error(
				'metadata_not_found',
				esc_html__( 'Metadata was not found', 'wp-video-encoder' )
			);				
		}

		$folder = $this->get_file_folder();		

		$file_resolution = $this->get_file_resolution();

		if( ! $file_resolution ){
			return new WP_Error(
				'file_resolution_not_found',
				esc_html__( 'File resolution was not found.', 'wp-video-encoder' )
			);
		}

		$file_resolution = explode( 'x' , $file_resolution );

		$master_playlist = "#EXTM3U\n#EXT-X-VERSION:3\n";

		$misc_params = " -hide_banner -y";

		$params = " -c:a {$this->audio_codec}";

		if( $this->strict_2 ){
			$params .= " -strict -2";
		}

		if( $this->extra_params ){
			$params .= " {$this->extra_params}";
		}		

		$params .= " -ar 48000";
		$params .= " -vcodec {$this->video_codec}";

		if( $this->video_codec == 'h264' ){
			$params .= " -profile:v {$this->h264_profile} -pix_fmt yuv420p";
			$params .= " -crf {$this->h264_crf}";

			if( $this->h264_moov == 'movflag' ){
				$params .= " -movflags faststart";
			}
		}
		
		$params .= " -sc_threshold 0";
		$params .= " -g 48 -keyint_min 48";

		$params .= " -f hls";
		$params .= " -hls_time {$this->segment_target_duration}";

		if( $this->hls_file_keyinfo ){
			$params .= " -hls_key_info_file " . $this->hls_file_keyinfo;
		}

		if( $this->hls_playlist_type ){
			$params .= " -hls_playlist_type {$this->hls_playlist_type}";
		}

		if( $this->hls_flags ){

			switch ( $this->hls_flags ) {
				case 'independent_segments':
					$params .= " -hls_flags " . $this->hls_flags;
				break;
				
				case 'second_level_segment_index':
					$params .= " -strftime 1 -hls_flags " . $this->hls_flags;

					$this->hls_segment_file_name = '%Y%m%d_%%04d.ts';
				break;

				case 'second_level_segment_size':

					$params .= " -strftime 1 -hls_flags " . $this->hls_flags;

					$this->hls_segment_file_name = '%%08s.ts';
				break;

				case 'second_level_segment_duration':
					$params .= " -strftime 1 -hls_flags " . $this->hls_flags;

					$this->hls_segment_file_name = '%%013t.ts';
				break;
			}
		}

		if( $this->hls_segment_type ){
			$params .= " -hls_segment_type {$this->hls_segment_type}";
		}

 		$cmd = "";

 		if( ! is_array( $this->renditions ) || count( $this->renditions ) == 0 ){
 			$this->renditions = $this->get_file_rendition();
 		}

 		$break = false;

		foreach ( $this->renditions as $rendition => $value ) {
			$resolution 	= 	explode( 'x', $rendition );
			$bitrate 		=	$value[0];
			$audiorate 		=	$value[1];

			$width 			=	(int)$resolution[0];
			$height 		=	(int)$resolution[1];

			if( $height >= $file_resolution[1] ){
				$height = $file_resolution[1];
				$width = $file_resolution[0];

				$break = true;
			}

			if( $height <= (int)$file_resolution[1] ){
				$maxrate 		=	absint( (int)$bitrate*(int)$this->max_bitrate_ratio );
				$bufsize 		=	absint( (int)$bitrate*(int)$this->rate_monitor_buffer_ratio );
				$bandwidth  	=	(int)$bitrate * 1000;
				
				$cmd .= " {$params} -vf 'scale=w={$width}:h={$height}:force_original_aspect_ratio=decrease,pad=ceil(iw/2)*2:ceil(ih/2)*2'";

				$cmd .= " -b:v {$bitrate} -maxrate {$maxrate}k -bufsize {$maxrate}k -b:a {$audiorate}";
				$cmd .= " -hls_segment_filename {$folder}/{$rendition}_{$this->hls_segment_file_name} -f hls {$folder}/{$rendition}.m3u8";

				$master_playlist .= "#EXT-X-STREAM-INF:BANDWIDTH={$bandwidth},RESOLUTION={$rendition}\n{$rendition}.m3u8\n";
				
			}

			if( $break ){
				break;
			}			
		}

		$cmd .= " >/dev/null 2>&1 2> {$folder}/{$this->log_file} & echo $!";

		$cmd = "ffmpeg {$misc_params} -i {$this->file_path} {$cmd}";

		$exec = $this->exec( $cmd );

		if( is_wp_error( $exec ) ){
			return $exec;
		}

		if( $this->result_code != 0 ){
			return new WP_Error(
				$this->result_code,
				$this->encode_output
			);
		}

		if( ! function_exists( 'fopen' ) || ! function_exists( 'fwrite' ) || ! function_exists( 'fclose' ) ){
			return new WP_Error( 'cannot_read_file', esc_html__( 'Cannot read/write file playlist.m3u8', 'wp-video-encoder' ) );
		}

		$playlist = fopen( $this->get_encode_playlist_file() , 'w' );

		if( $playlist ){
			fwrite( $playlist, $master_playlist );
		}

		fclose( $playlist );

		return $this->get_encode_playlist_file();
	}

	public function generate_hls_stream_v2(){

 		$break 	= false;
 		$count 	= 0;
 		$_count = $map_v = $map_a = $stream_map = $misc_params = $filter_complex = $_resolution = $split = array();

 		$has_audio = $this->get_file_audio();

		$metadata = $this->write_file_metadata();

		if( is_wp_error( $metadata ) ){
			return $metadata;
		}

		if( ! $metadata ){
			return new WP_Error(
				'metadata_not_found',
				esc_html__( 'Metadata was not found', 'wp-video-encoder' )
			);				
		}

		$folder = $this->get_file_folder();

		$file_resolution = $this->get_file_resolution();

		if( ! $file_resolution ){
			return new WP_Error(
				'file_resolution_not_found',
				esc_html__( 'File resolution was not found.', 'wp-video-encoder' )
			);
		}

		$file_resolution = explode( 'x' , $file_resolution );

 		if( ! is_array( $this->renditions ) || count( $this->renditions ) == 0 ){
 			$this->renditions = $this->get_file_rendition();
 		}		

 		$misc_params[] 		= "-hide_banner -y";

		foreach ( $this->renditions as $rendition => $value ) {
			$resolution 	= 	explode( 'x', $rendition );
			$bitrate 		=	$value[0];
			$audiorate 		=	$value[1];

			$width 			=	(int)$resolution[0];
			$height 		=	(int)$resolution[1];

			if( $height >= $file_resolution[1] ){
				$height = $file_resolution[1];
				$width 	= $file_resolution[0];

				$break 	= true;
			}

			if( $height <= (int)$file_resolution[1] ){
				$maxrate 		=	absint( (int)$bitrate*(int)$this->max_bitrate_ratio );
				$bufsize 		=	absint( (int)$bitrate*(int)$this->rate_monitor_buffer_ratio );
				$bandwidth  	=	(int)$bitrate * 1000;

				$data 			=	compact( 'width', 'height', 'bitrate', 'audiorate', 'maxrate', 'bufsize', 'bandwidth', 'count' );

				$_resolution[] 	= "[v{$count}]scale=w={$width}:h={$height}:force_original_aspect_ratio=decrease,pad=ceil(iw/2)*2:ceil(ih/2)*2[v{$count}out]";

				$map_v[] 		= "-map [v{$count}out] -c:v:{$count} {$this->video_codec}";

				if( $this->video_codec == 'h264' ){
					$map_v[] 	= "-profile:v:{$count} {$this->h264_profile} -pix_fmt yuv420p";
					$map_v[] 	= "-crf {$this->h264_crf}";

					if( $this->h264_moov == 'movflag' ){
						$map_v[] = "-movflags faststart";
					}
				}				

				$map_v[] = "-ar 48000 -g 48 -sc_threshold 0 -keyint_min 48";

				if( $this->strict_2 ){
					$map_v[] = "-strict -2";
				}

				if( $this->extra_params ){
					$map_v[] = "{$this->extra_params}";
				}

				$map_v[] 	= "-b:v:{$count} {$bitrate}";
				$map_v[] 	= "-maxrate:v:{$count} {$maxrate}k";
				$map_v[] 	= "-bufsize:v:{$count} {$maxrate}k";

				$map_v 		= apply_filters( 'wpve_generate_hls_stream_map_v', $map_v, $data );

				if( $has_audio ){
					$map_a[] 	= "-map a:0 -c:a:{$count} {$this->audio_codec} -b:a:{$count} {$audiorate} -ac 2";	
				}
				
				$map_a 		= apply_filters( 'wpve_generate_hls_stream_map_a', $map_a, $data );

				$_count[$count] = $height;
				$count++;
	
			}

			if( $break ){
				break;
			}
		}

		for ( $i=0; $i < $count; $i++) { 

			if( $has_audio ){
				if( $this->hls_segment_folder_name == 'index' ){
					$stream_map[] = sprintf( "v:%s,a:%s", $i, $i );
				}
				else{
					$stream_map[] = sprintf( "v:%s,a:%s,name:%s", $i, $i, $_count[$i] );
				}
			}
			else{
				if( $this->hls_segment_folder_name == 'index' ){
					$stream_map[] = sprintf( "v:%s", $i );
				}else{
					$stream_map[] = sprintf( "v:%s,name:%s", $i, $_count[$i] );	
				}
				
			}
			
			$split[] = sprintf( "[v%s]", $i );
		}

		$_stream_map = join(" ", $stream_map );

		$stream_map = "'{$_stream_map}' {$folder}/stream_%v/playlist.m3u8";

		$filter_complex[] = sprintf( "split=%s%s;", $count, join( '', $split ) );
		$filter_complex[] = implode(";", $_resolution );


		$misc_params 		= implode(" ", $misc_params);
		$map_v 				= implode(" ", $map_v);
		$map_a 				= implode(" ", $map_a);
		$hls_params 		= implode(" ", $this->build_hls_params() );
		$filter_complex 	= implode(" ", $filter_complex);

		$cmd = "ffmpeg {$misc_params}";

		$cmd .= " -i {$this->file_path}";

		if( $this->watermark ){

			$wm_position = $wm_size = '';
			$wm_padding  = $this->watermark_padding;

			$cmd .= " -i {$this->watermark}";

			switch ( $this->watermark_position ) {
				case 'top_right':
					$wm_position = "main_w-overlay_w-{$wm_padding[0]}:{$wm_padding[1]}";
				break;

				case 'top_left':
					$wm_position = "{$wm_padding[0]}:{$wm_padding[1]}";
				break;

				case 'bottom_left':
					$wm_position = "{$wm_padding[0]}:main_h-overlay_h-{$wm_padding[1]}";
				break;
				
				case 'bottom_right':
					$wm_position = "main_w-overlay_w-{$wm_padding[0]}:main_h-overlay_h-{$wm_padding[1]}";
				break;

				default:
					$wm_position = "(main_w-overlay_w)/2:(main_h-overlay_h)/2";
				break;
			}

			switch ( $this->watermark_size ) {

				case 'percentage_w':
					$wm_size = "=iw*{$this->watermark_size_percentage}:ow/mdar";
				break;

				case 'percentage_h':
					$wm_size = "=w=oh*mdar:h=ih*{$this->watermark_size_percentage}";
				break;
			}

			if( ! empty( $wm_size ) ){
				$wm_size 		= "[1][0]scale2ref{$wm_size}[wm][vid];";
				$filter_complex = "{$wm_size}[wm]lut=a=val*{$this->watermark_opacity}[wm];[vid][wm]overlay={$wm_position}," . $filter_complex;
			}
			else{
				$filter_complex = "[1]lut=a=val*{$this->watermark_opacity}[wm];[0][wm]overlay={$wm_position}," . $filter_complex;
			}	
		}

		$cmd .= " -filter_complex '{$filter_complex}'";
		$cmd .= " {$map_v}";
		$cmd .= " {$map_a}";
		$cmd .= " {$hls_params}";
		$cmd .= " -var_stream_map {$stream_map}";
		$cmd .= " >/dev/null 2>&1 2> {$folder}/{$this->log_file} & echo $!";

		/**
		 *
		 * Filter command
		 * 
		 * @var string
		 */
		$cmd = apply_filters( 'wpve_generate_hls_stream_command', $cmd );

		$exec = $this->exec( $cmd );

		if( is_wp_error( $exec ) ){
			return $exec;
		}

		if( $this->result_code != 0 ){
			return new WP_Error(
				$this->result_code,
				$this->encode_output
			);
		}

		return $this->get_encode_playlist_file();
	}

	/**
	 *
	 * Create HLS playlist
	 * 
	 * @since 1.0.0
	 * 
	 */
	public function generate_hls_stream(){
		switch ( $this->encoder_version ) {
			case 'v1':
				return $this->generate_hls_stream_v1();
			break;

			case 'v2':
				return $this->generate_hls_stream_v2();
			break;
		}
	}
}