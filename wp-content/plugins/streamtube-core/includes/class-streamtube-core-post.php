<?php
/**
 * Define the post functionality
 *
 *
 * @link       https://themeforest.net/user/phpface
 * @since      1.0.0
 *
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 */

/**
 * Define the profile functionality
 *
 * @since      1.0.0
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */

if( ! defined('ABSPATH' ) ){
    exit;
}

class Streamtube_Core_Post{

	const CPT_VIDEO 						=	'video';

	/**
	 *
	 * Holds the video meta field name
	 * 
	 * @var string
	 *
	 * @since  1.0.0
	 * 
	 */
	const VIDEO_URL 						=	'video_url';

	/**
	 *
	 * Holds the embed privacy meta name
	 *
	 * @var string
	 * 
	 */
	const EMBED_PRIVACY 					= 	'_embed_privacy';

	/**
	 *
	 * Holds the embed privacy allowed domains meta name
	 *
	 * @var string
	 * 
	 */
	const EMBED_PRIVACY_ALLOWED_DOMAINS 	=	'_embed_allowed_domains';

	/**
	 *
	 * Holds the embed privacy blocked domains meta name
	 *
	 * @var string
	 * 
	 */
	const EMBED_PRIVACY_BLOCKED_DOMAINS 	=	'_embed_blocked_domains';

	protected $User;

	public function __construct(){
		$this->User = new Streamtube_Core_User();
	}

	/**
	 *
	 * Register video post type
	 *
	 * @since    1.0.0
	 */
	public function cpt_video(){
		/**
		 * Post Type: Videos.
		 *
		 * @since 1.0.0
		 */

		$labels = array(
			'name' 									=> esc_html__( 'Videos', 'streamtube-core' ),
			'singular_name' 						=> esc_html__( 'Video', 'streamtube-core' )	
		);

		$args = array(
			'label' 								=> esc_html__( 'Videos', 'streamtube-core' ),
			'labels' 								=> $labels,
			'description' 							=> '',
			'public' 								=> true,
			'publicly_queryable' 					=> true,
			'show_ui' 								=> true,
			'show_in_rest' 							=> true,
			'rest_base' 							=> self::CPT_VIDEO,
			'rest_controller_class' 				=> 'WP_REST_Posts_Controller',
			'has_archive' 							=> get_option( 'archive_video', 'on' ) ? true : false,
			'show_in_menu' 							=> true,
			'show_in_nav_menus' 					=> true,
			'delete_with_user' 						=> false,
			'exclude_from_search' 					=> false,
			'capability_type' 						=> 'post',
			'map_meta_cap' 							=> true,
			'hierarchical' 							=> false,
			'rewrite' 								=> array( 
				'slug'			=>	sanitize_key( strtolower( get_option( 'video_slug', self::CPT_VIDEO ) ) ), 
				'with_front'	=>	true 
			),
			'query_var' 							=> true,
			'supports' 								=>  array( 
				'title', 
				'editor', 
				'thumbnail', 
				'excerpt', 
				'trackbacks', 
				'comments', 
				'author'
			),
			'taxonomies'							=>	array(
				'categories',
				'video_tag'
			),
			'menu_icon'								=>	'dashicons-video-alt3'
		);

		if( function_exists( 'buddypress' ) && bp_is_active( 'activity' ) ){
			$args['labels'] = array_merge( $args['labels'], array(
	            'bp_activity_admin_filter' 			=> esc_html__( 'New video uploaded', 'streamtube-core' ),
	            'bp_activity_front_filter' 			=> esc_html__( 'Videos', 'streamtube-core' ),
	            'bp_activity_new_post'    			=> __( '%1$s uploaded a new <a href="%2$s">video</a>', 'streamtube-core' ),
	            'bp_activity_new_post_ms'  			=> __( '%1$s uploaded a new <a href="%2$s">video</a>, on the site %3$s', 'streamtube-core' ),
				'bp_activity_comments_admin_filter' => __( 'Comments about videos', 'streamtube-core' ),
				'bp_activity_comments_front_filter' => __( 'Video Comments', 'streamtube-core' ),
				'bp_activity_new_comment'           => __( '%1$s commented on the <a href="%2$s">video</a>', 'streamtube-core' ),
				'bp_activity_new_comment_ms'        => __( '%1$s commented on the <a href="%2$s">video</a>, on the site %3$s', 'streamtube-core' )	            
			) ) ;

			$args['supports'][]						=	'buddypress-activity';

			// Syncing comments requires Site Tracking component activated.
			$args['bp_activity']					=	array(
	            'component_id' 		=>	buddypress()->activity->id,
	            'action_id'    		=>	'new_video',
	            'comment_action_id'	=>	'new_video_comment',
	            'contexts'     		=>	array( 'activity', 'member', 'member_groups' ),
	            'position'     		=>	40
        	);
		}

		register_post_type( self::CPT_VIDEO, $args );
	}

	/**
	 *
	 * Get supported track format
	 * 
	 * @return array
	 */
	public static function get_text_track_format(){
		return apply_filters( 'streamtube/core/text_track_format',  array( 'vtt' ) );
	}

	/**
	 *
	 * Get CPT video slug
	 * 
	 * @return false|string
	 *
	 * @since 1.0.8
	 * 
	 */
	public function get_post_type_slug( $post_type ){

		if( $post_type == 'post' ){
			return $post_type;
		}

		if( ! post_type_exists( $post_type ) ){
			return false;
		}

		$post_type_object = get_post_type_object( $post_type );

		if( ! $post_type_object->rewrite ){
			return $post_type;
		}

		return $post_type_object->rewrite['slug'];
	}

	/**
	 *
	 * Check if Ad disabled for given post
	 * 
	 * @param  int $post_id
	 * @return string
	 *
	 * @since 1.3
	 * 
	 */
	public function is_ad_disabled( $post_id = 0 ){

		if( ! $post_id ){
			$post_id = get_the_ID();
		}

		$disable_ad = get_post_meta( $post_id, 'disable_ad', true );

		if( $disable_ad ){
			$disable_ad = true;
		}
		else{
			$disable_ad = false;
		}

		/**
		 *
		 * @since 1.3
		 * 
		 */
		return apply_filters( 'streamtube/core/video/is_ad_disabled', $disable_ad, $post_id );
	}

	/**
	 *
	 * Disable Ad for given post
	 * 
	 * @param  int $post_id
	 *
	 * @since 1.3
	 */
	public function disable_ad( $post_id = 0 ){
		if( ! $post_id ){
			$post_id = get_the_ID();
		}

		return update_post_meta( $post_id, 'disable_ad', 'on' );
	}

	/**
	 *
	 * Enable Ad for given post
	 * 
	 * @param  int $post_id
	 *
	 * @since 1.3
	 */
	public function enable_ad( $post_id = 0 ){
		if( ! $post_id ){
			$post_id = get_the_ID();
		}

		return delete_post_meta( $post_id ,'disable_ad' );
	}

	/**
	 *
	 * Update Ad Schedule for given post
	 * 
	 * @param  int $post_id
	 *
	 * @since 1.3
	 */
	public function update_ad_schedules( $post_id = 0, $ad_schedules = array() ){
		if( ! $post_id ){
			$post_id = get_the_ID();
		}

		return update_post_meta( $post_id ,'ad_schedules', $ad_schedules );
	}

	/**
	 *
	 * Get Ad Schedule from given post
	 * 
	 * @param  int $post_id
	 *
	 * @since 1.3
	 */
	public function get_ad_schedules( $post_id = 0 ){
		if( ! $post_id ){
			$post_id = get_the_ID();
		}

		$ad_schedules = (array)get_post_meta( $post_id ,'ad_schedules', true );

		if( is_array( $ad_schedules ) ){
			$ad_schedules = array_unique( $ad_schedules );
		}

		/**
		 *
		 * Filter and return the ad
		 * 
		 */
		return apply_filters( 'streamtube/core/video/ad_schedules', $ad_schedules, $post_id );
	}	

	/**
	 *
	 * Get video trailer source
	 * 
	 * @param  integer $post_id
	 *
	 * @return array|string|int
	 * 
	 */
	public function get_video_trailer( $post_id = 0, $single = true ){

		$trailer = 0;

		if( ! $post_id ){
			$post_id = get_the_ID();
		}		

		$source = get_post_meta( $post_id, 'video_trailer', true );

		if( is_array( $source ) && isset( $source[0] ) ){
			if( $single ){
				$trailer = $source[0];
			}else{
				$trailer = $source;
			}
		}

		if( is_string( $source ) ){
			$trailer = $source;
		}

		/**
		 *
		 * Filter trailer source
		 *
		 * @param int $post_id
		 * 
		 */
		return apply_filters( 'streamtube/core/video/trailer', $trailer, $source, $post_id );
	}

	/**
	 *
	 * Update video trailer
	 * 
	 */
	public function update_video_trailer( $post_id = 0, $source = '' ){
		return update_post_meta( $post_id, 'video_trailer', $source );
	}

	/**
	 *
	 * Get video source
	 * 
	 * @param  int $post_id
	 * @return string
	 *
	 * @since  1.0.0
	 * 
	 */
	public function get_source( $post_id = 0 ){

		$source = '';

		if( ! $post_id ){
			$post_id = get_the_ID();
		}

		$source = trim( get_post_meta( $post_id, self::VIDEO_URL, true ) );

		if( empty( $source ) ){
			// Firstly, check if file was found.
			if( "" != $maybe_video_file = get_post_meta( $post_id, 'video_file', true ) ){

				if( is_int( $maybe_video_file ) ){
					$source = $maybe_video_file;
				}

				if( is_array( $maybe_video_file ) && count( $maybe_video_file ) > 0 ){
					$source = $maybe_video_file[0];
				}
			}

			if( "" != $maybe_vafpress = get_post_meta( $post_id, '_format_video_embed', true ) ){
				$source = $maybe_vafpress;
			}

			if( ! empty( $source ) ){
				update_post_meta( $post_id, self::VIDEO_URL, $source );
			}			
		}

		/**
		 *
		 * Filter and return the source
		 * 
		 */
		return apply_filters( 'streamtube/core/video/source', $source, $post_id );
	}

	/**
	 * 
	 * Update post source
	 * @param  int $post_id
	 * @param  string $sourc
	 * @return update_post_meta()
	 *
	 * @since 1.0.6
	 * 
	 */
	public function update_source( $post_id = 0, $source = '' ){

		if( ! $post_id ){
			$post_id = get_the_ID();
		}

		$source = trim( wp_unslash($source) );

        return update_post_meta( $post_id, self::VIDEO_URL,$source );
	}


	/**
	 *
	 * Get upcoming date
	 * 
	 * @param  integer $post_id
	 * 
	 */
	public function get_upcoming_date( $post_id = 0 ){
		return get_post_meta( $post_id, '_upcoming_date', true );
	}

	/**
	 *
	 * Update upcoming date
	 * 
	 * @param  integer $post_id
	 * @param  string  $datetime
	 * 
	 */
	public function update_upcoming_date( $post_id = 0, $datetime = '' ){

		if( ! $post_id ){
			return false;
		}

		if( ! $datetime || ! is_string( $datetime ) ){
			/**
			 * Fires before deleting meta
			 *
			 * @param int $post_id
			 */
			do_action( 'streamtube/core/upcoming/before_delete_meta', $post_id, $datetime );

			return delete_post_meta( $post_id, '_upcoming_date' );
		}

		$datetime  = streamtube_core_format_datetime_local( $datetime );

		/**
		 * Fires before updating meta
		 *
		 * @param int $post_id
		 */
		do_action( 'streamtube/core/upcoming/before_update_meta', $post_id, $datetime );

		return update_post_meta( $post_id, '_upcoming_date', $datetime );
	}

	/**
	 *
	 * Check if given video is upcoming
	 * 
	 * @param  integer $post_id
	 * @return boolean
	 * 
	 */
	public function is_post_upcoming( $post_id = 0 ){

		if( ! $post_id ){
			$post_id = get_the_ID();
		}

		$upcoming = $this->get_upcoming_date( $post_id );

		if( ! $upcoming ){
			return false;
		}

		$upcoming 	= new DateTime( $upcoming );
		$current 	= new DateTime( current_datetime()->format('Y-m-d H:i:s') );

		if( $upcoming > $current ){
			return (object)compact( 'upcoming', 'current' );
		}

		return false;
	}

	/**
	 *
	 * Check if given video is portrait
	 * 
	 * @param  integer $post_or_attachment_id
	 * @return boolean
	 */
	public function is_portrait_video( $post_or_attachment_id = 0 ){

		$source = false;

		if( get_post_type( $post_or_attachment_id ) == self::CPT_VIDEO ){
			$source = (int)$this->get_source( $post_or_attachment_id );	
		}

		if( wp_attachment_is( 'video', $post_or_attachment_id ) ){
			$source = (int)$post_or_attachment_id;
		}

		if( $source && wp_attachment_is( 'video', $source ) ){
			$metadata = wp_get_attachment_metadata( $source );

			if( is_array( $metadata ) ){
				$metadata = wp_parse_args( $metadata, array(
					'width'		=>	0,
					'height'	=>	0
				) );

				if( (int)$metadata['height'] > (int)$metadata['width'] ){
					return true;
				}
			}
		}

		return false;
	}

	/**
	 *
	 * Get video source
	 * 
	 * @param  int $post_id
	 * @return string
	 *
	 * @since  1.0.0
	 * 
	 */
	public function get_aspect_ratio( $post_id = 0 ){

		if( ! $post_id ){
			$post_id = get_the_ID();
		}

		$default = get_option( 'player_ratio', '21x9' );

		$ratio = get_post_meta( $post_id, '_aspect_ratio', true );

		if( strpos( $ratio, 'field_' ) !== false ){
			// It seems ACF was used before.
			$ratio = $default;
		}

		if( empty( $ratio ) ){
			$ratio = $default;
		}

		if( empty( $ratio ) ){
			$ratio = '21x9';
		}

		if( get_option( 'enforce_player_ratio' ) && ! empty( $default ) ){
			$ratio = $default;
		}

		if( get_option( 'auto_portrait_player_ratio' ) && $this->is_portrait_video( $post_id ) ){
			$ratio = '9x16';
		}

		return apply_filters( 'streamtube/core/post/aspect_ratio', $ratio, $post_id );
	}

	/**
	 *
	 * Update ratio
	 * 
	 * @param  int $post_id
	 * @param  string $aspect_ratio
	 * @return update_post_meta() or false
	 *
	 * @since 1.0.6
	 * 
	 */
	public function update_aspect_ratio( $post_id = 0, $aspect_ratio  = '' ){

		if( ! $post_id ){
			$post_id = get_the_ID();
		}		

		$aspect_ratio = sanitize_text_field( $aspect_ratio );

		if( empty( $aspect_ratio ) ){
			return update_post_meta( $post_id, '_aspect_ratio', '' );
		}

		$supported_ratios = streamtube_core_get_ratio_options();

		if( array_key_exists( $aspect_ratio, $supported_ratios ) ){
			return update_post_meta( $post_id, '_aspect_ratio', $aspect_ratio );
		}

		return false;
	}

	/**
	 *
	 * Get post thumbnail
	 * 
	 * @param  int $post_id
	 * @return string
	 *
	 * @since  1.0.0
	 * 
	 */
	public function get_thumbnail_url( $post_id = 0, $size = 'large' ){

		$thumbnail_url = '';

		if( ! $post_id ){
			$post_id = get_the_ID();
		}

		if( has_post_thumbnail( $post_id ) ){
			$thumbnail_url = wp_get_attachment_image_url( get_post_thumbnail_id(  $post_id ), $size );
		}

		/**
		 *
		 * Filter and return the thumbnail url
		 *
		 * param $thumbnail_url
		 * @param int $post_id
		 *
		 * @since 1.0.6
		 * 
		 */
		return apply_filters( 'streamtube/core/video/thumbnail_url', $thumbnail_url, $post_id );
	}

	/**
	 *
	 * Get post thumbnail 2
	 * 
	 * @param  int $post_id
	 * @return string
	 *
	 * @since  1.0.0
	 * 
	 */
	public function get_thumbnail_image_url_2( $post_id = 0 ){

		if( ! $post_id ){
			$post_id = get_the_ID();
		}

		$image_url = '';

		$image_id = get_post_meta( $post_id, '_thumbnail_url_2', true );

		if( wp_attachment_is_image( $image_id ) ){
			$image_url = wp_get_attachment_image_url( $image_id, 'large' );
		}else{
			$image_url = $image_id;
		}

		/**
		 *
		 * Filter and return the thumbnail url 2
		 *
		 * param $image_url
		 * @param int $post_id
		 *
		 * @since 1.0.6
		 * 
		 */
		return apply_filters( 'streamtube/core/video/thumbnail_url_2', $image_url, $image_id, $post_id );
	}

	/**
	 *
	 * Update post thumbnail image 2
	 * 
	 * @param  int $post_id
	 * @param  int $thumbnail_id
	 * @return update_post_meta() or false
	 *
	 * @since 1.0.6
	 * 
	 */
	public function update_thumbnail_image_url_2( $post_id = 0, $thumbnail_id = 0 ){

		if( ! $post_id ){
			$post_id = get_the_ID();
		}

		$image_url = $thumbnail_id;

		if( wp_attachment_is_image( $thumbnail_id ) ){
			$image_url = wp_get_attachment_image_url( $thumbnail_id, 'full' );
		}

		return update_post_meta( $post_id, '_thumbnail_url_2', $image_url );
	}

	/**
	 *
	 * Get video source
	 * 
	 * @param  int $post_id
	 * @return string
	 *
	 * @since  1.0.0
	 * 
	 */
	public function get_length( $post_id = 0, $format = false ){

		if( ! $post_id ){
			$post_id = get_the_ID();
		}

		$length = get_post_meta( $post_id, '_length', true );

		$source = $this->get_source( $post_id );

		if( wp_attachment_is( 'video', $source ) ){
			$metadata = wp_get_attachment_metadata( $source );

			if( is_array( $metadata ) ){
				if( array_key_exists( 'length', $metadata ) ){
					$length = absint( $metadata['length'] );	
				}
			}
		}

		if( $format && is_int( $length ) ){
            if( $length >= 3600 ){
                $length = gmdate( "H:i:s", $length%86400);
            }else{
                $length = gmdate( "i:s", $length%86400);
            }
		}

		/**
		 *
		 * Filter and return the length
		 * 
		 */
		return apply_filters( 'streamtube/core/video/length', $length, $post_id );		
	}

	/**
	 *
	 * Update length
	 * 
	 * @param  int $post_id
	 * @param  string $length
	 * @return update_post_meta() or false
	 *
	 * @since 1.0.6
	 * 
	 */
	public function update_length( $post_id = 0, $length  = '' ){

		if( ! $post_id ){
			$post_id = get_the_ID();
		}		

		$length = sanitize_text_field( $length );

		if( empty( $length ) ){
			return false;
		}

		return update_post_meta( $post_id, '_length', $length );
	}

	/**
	 *
	 * Update video degree
	 * 
	 */
	public function update_video_vr( $post_id = 0, $vr = true ){

		if( $vr ){
			return update_post_meta( $post_id, '_vr', 'vr' );
		}

		return delete_post_meta( $post_id, '_vr' );
	}

	/**
	 *
	 * Get video degree
	 * 
	 * @param  $post_id
	 * @return string|false
	 * 
	 */
	public function is_video_vr( $post_id = 0 ){
		$vr = get_post_meta( $post_id, '_vr', true );

		if( ! empty( $vr ) ){
			return $vr;
		}

		return false;
	}

	/**
	 *
	 * get post views meta data
	 * 
	 * @return string
	 * 
	 */
	public function get_post_views_meta(){
		$types = array_keys( streamtube_core_get_post_view_types() );

		$type = get_option( 'sitekit_pageview_type', 'pageviews' );

		if( ! in_array( $type, $types ) ){
			$type = 'pageviews';
		}

		return '_' . $type;
	}

	/**
	 *
	 * Get post views
	 * 
	 * @param  int $post_id
	 * @return int
	 *
	 * @since 1.0.8
	 * 
	 */
	public function get_post_views( $post_id = 0 ){

		if( ! $post_id ){
			$post_id = get_the_ID();
		}

		$pageviews = (int)get_post_meta( $post_id, $this->get_post_views_meta(), true );

		/**
		 *
		 * @since 1.0.8
		 * 
		 */
		return apply_filters( 'streamtube/core/post/views', $pageviews, $post_id );
	}

	/**
	 *
	 * Get text tracks
	 * 
	 * @param  integer $post_id
	 * @return array|false
	 */
	public function get_text_tracks( $post_id = 0 ){

		$_tracks = array();

		$tracks = get_post_meta( $post_id, 'text_tracks', true );

		if( ! $tracks || ! is_array( $tracks ) ){
			return false;
		}

		if( ! array_key_exists( 'languages', $tracks ) || ! is_array( $tracks['languages'] ) ){
			return false;
		}

		for ( $i = 0; $i < count( $tracks['languages'] ); $i++) {
			if( $tracks['languages'][$i] && $tracks['sources'][$i] ){

				$file_type = wp_check_filetype( $tracks['sources'][$i] );

				if( array_key_exists( 'ext', $file_type ) && in_array( strtolower( $file_type['ext'] ), self::get_text_track_format() ) ){
					$_tracks[] = array(
						'language'	=>	$tracks['languages'][$i],
						'source'	=>	$tracks['sources'][$i]
					);
				}
			}
		}

		return apply_filters( 'streamtube/core/post/text_tracks', $_tracks, $post_id );
	}

	/**
	 * 
	 * Get Alt sources
	 * 
	 * @param  integer $post_id
	 * @param  integer $index
	 * @return array|false
	 * 
	 */
	public function get_altsources( $post_id = 0, $index = false, $include_main = true ){

		$_sources = array();

		$sources = get_post_meta( $post_id, 'altsources', true );

		if( ! $sources || ! is_array( $sources ) ){
			return false;
		}

		if( ! array_key_exists( 'sources', $sources ) || ! is_array( $sources['sources'] ) ){
			return false;
		}

		for ( $i = 0; $i < count( $sources['sources'] ); $i++) {
			if( ! empty( $sources['sources'][$i] ) &&  ! empty( $sources['labels'][$i] ) ){
				$_sources[] = array(
					'label'		=>	$sources['labels'][$i],
					'source'	=>	$sources['sources'][$i]
				);
			}
		}

		if( $include_main && $_sources ){
			$_sources = array_merge( array(
				array(
					'label'		=>	esc_html__( 'Main', 'streamtube-core' ),
					'source'	=>	$this->get_source( $post_id )
				)
			), $_sources );
		}

		if( $index !== false && isset( $_sources[ $index ] ) ){
			$_sources = $_sources[ $index ];
		}

		return apply_filters( 'streamtube/core/post/altsources', $_sources, $post_id, $index );
	}

	/**
	 *
	 * Get timestamps
	 * 
	 * @param  int $post_id
	 * @return array
	 */
	public function get_timestamps( $post_id ){

		$_timestamps = array();

		$timestamps = get_post_meta( $post_id, 'timestamps', true );

		if( ! $timestamps || ! is_array( $timestamps ) ){
			return false;
		}

		if( ! array_key_exists( 'points', $timestamps ) || ! is_array( $timestamps['points'] ) ){
			return false;
		}		

		for ( $i = 0; $i < count( $timestamps['points'] ); $i++) {
			if( ! empty( $timestamps['points'][$i] ) && ! empty( $timestamps['texts'][$i] ) ){
				$_timestamps[] = array(
					'point'		=>	$timestamps['points'][$i],
					'text'		=>	$timestamps['texts'][$i]
				);
			}
		}

		return apply_filters( 'streamtube/core/post/timestamps', $_timestamps, $post_id );
	}

	/**
	 *
	 * Get last seen post meta
	 * 
	 * @param  int $post_id
	 * @return datetime
	 *
	 * @since 1.0.8
	 */
	public function get_last_seen( $post_id = 0, $unix_timestamp = false ){
		
		if( ! $post_id ){
			$post_id = get_the_ID();
		}

		$last_seen = get_post_meta( $post_id, '_last_seen', true );

		if( $last_seen ){

			$last_seen = get_date_from_gmt(date( 'Y-m-d H:i:s', strtotime($last_seen) ));

			if( $unix_timestamp ){
				$last_seen = strtotime( $last_seen );
			}
		}

		/**
		 *
		 * @since 1.0.8
		 * 
		 */
		return apply_filters( 'streamtube/core/post/last_seen', $last_seen, $post_id );
	}

	/**
	 *
	 * Get embed privacy
	 * 
	 * @param  integer $post_id
	 * @return true|false|array of allowed domain
	 * 
	 */
	public function get_embed_privacy( $post_id = 0 ){

		$array = array();

		$embed_privacy 	= get_option( 'embed_privacy', 'anywhere' );

		switch ( $embed_privacy ) {
			case 'anywhere':
			case 'nowhere':

				$allowed_domains = get_option( 'embed_privacy_allowed_domains' );
				$blocked_domains = get_option( 'embed_privacy_blocked_domains' );

				$array = array(
					'embed_privacy'		=>	$embed_privacy,
					'allowed_domains'	=>	array_map( 'trim' , explode( "\n", wp_strip_all_tags( $allowed_domains ) ) ),
					'blocked_domains'	=>	array_map( 'trim' , explode( "\n", wp_strip_all_tags( $blocked_domains ) ) )
				);
			break;
			
			default:
				$embed_privacy 			= get_post_meta( $post_id, self::EMBED_PRIVACY, true );

				$allowed_domains 		= get_post_meta( $post_id, self::EMBED_PRIVACY_ALLOWED_DOMAINS, true );
				$blocked_domains 		= get_post_meta( $post_id, self::EMBED_PRIVACY_BLOCKED_DOMAINS, true );

				$array = array(
					'embed_privacy'		=>	$embed_privacy ? $embed_privacy : 'anywhere',
					'allowed_domains'	=>	array_map( 'trim' , explode( "\n", wp_strip_all_tags( $allowed_domains ) ) ),
					'blocked_domains'	=>	array_map( 'trim' , explode( "\n", wp_strip_all_tags( $blocked_domains ) ) )
				);
			break;
		}

		return $array;
	}

	/**
	 *
	 * Check if current user can manage embedding
	 * 
	 * @return boolean
	 * 
	 */
	public function can_manage_embed_privacy( $post_id = 0 ){

		if( ! is_user_logged_in() ){
			return false;
		}

		if( Streamtube_Core_Permission::moderate_posts() ){
			return true;
		}

		$role_cap = trim( get_option( 'embed_privacy_roles', 'author' ) );

		// Always return true if empty
		if( ! $role_cap ){
			return true;
		}

		if( is_string( $role_cap ) && current_user_can( $role_cap ) ){
			return true;
		}

		$roles = array_map( 'trim' , explode(",", $role_cap ));

		$user_data = wp_get_current_user();

		if( array_intersect( $user_data->roles, $roles )
			&& $post_id
			&& current_user_can( 'edit_post', $post_id ) ){
			return true;
		}

		return false;
	}

	/**
	 *
	 * Update embed privacy
	 * 
	 */
	public function update_embed_privacy(){

		$errors = new WP_Error();

		$http_data = wp_parse_args( $_POST, array(
			'post_ID'						=>	'',
			'embed_privacy'					=>	'',
			'embed_allowed_domains'			=>	'',
			'embed_blocked_domains'			=>	''
		) );

		$http_data = wp_unslash( $http_data );

		extract( $http_data );	

		if( ! $this->can_manage_embed_privacy( $post_ID ) ){
			$errors->add( 
				'no_permission', 
				esc_html__( 'You do not have permission to update the embedding privacy for this post.', 'streamtube-core' ) 
			);
		}

		if( ! in_array( $embed_privacy , array( 'anywhere', 'nowhere' ))) {
			$embed_privacy = 'anywhere';
		}

		/**
		 *
		 * Filter the errors
		 * 
		 * @var WP_Error
		 *
		 * @since  1.0.0
		 * 
		 */
		$errors = apply_filters( 'streamtube/core/post/update_embed_privacy/errors', $errors, $post_ID );

		if( $errors->get_error_code() ){
			return $errors;
		}

		update_post_meta( 
			$post_ID, 
			self::EMBED_PRIVACY, 
			$embed_privacy 
		);

		update_post_meta( 
			$post_ID, 
			self::EMBED_PRIVACY_ALLOWED_DOMAINS, 
			wp_strip_all_tags( wp_unslash( $embed_allowed_domains ) ) 
		);

		update_post_meta( 
			$post_ID, 
			self::EMBED_PRIVACY_BLOCKED_DOMAINS, 
			wp_strip_all_tags ( wp_unslash( $embed_blocked_domains ) ) 
		);

		return $http_data;
	}

	/**
	 *
	 * Register reject post status
	 *
	 * @see  https://developer.wordpress.org/reference/functions/register_post_status/
	 * 
	 * @since 1.0.0
	 * 
	 */
	public function new_post_statuses(){

		register_post_status( 'unlist', array(
			'label'                     =>	esc_html__( 'Unlisted', 'streamtube-core' ),
			'internal'					=>	true,
			'public'                    =>	true,
			'private'					=>	true,
			'exclude_from_search'       =>	true,
			'publicly_queryable'		=>	true,
			'show_in_admin_all_list'    =>	true,
			'show_in_admin_status_list' =>	true,
			'label_count'               => _n_noop( 'Unlisted <span class="count">(%s)</span>', 'Unlisted <span class="count">(%s)</span>' ),
		) );

		register_post_status( 'reject', array(
			'label'                     =>	esc_html__( 'Reject', 'streamtube-core' ),
			'internal'					=>	true,
			'public'                    =>	false,
			'private'					=>	true,
			'exclude_from_search'       =>	true,
			'show_in_admin_all_list'    =>	true,
			'show_in_admin_status_list' =>	true,
			'label_count'               => _n_noop( 'Reject <span class="count">(%s)</span>', 'Reject <span class="count">(%s)</span>' ),
		) );

		register_post_status( 'encoding', array(
			'label'                     =>	esc_html__( 'Encoding', 'streamtube-core' ),
			'internal'					=>	true,
			'public'                    =>	false,
			'private'					=>	true,
			'exclude_from_search'       =>	true,
			'show_in_admin_all_list'    =>	true,
			'show_in_admin_status_list' =>	true,
			'label_count'               => _n_noop( 'Encoding <span class="count">(%s)</span>', 'Encoding <span class="count">(%s)</span>' ),
		) );
	}

	/**
	 *
	 * Get all visiblity statuses
	 * 
	 * @param  boolean $filter
	 * @return array
	 * 
	 */
	public function get_post_statuses_for_edit( $filter = false ){
		$statuses = array(
			'pending'			=>	esc_html__( 'Pending Review', 'streamtube-core' ),
			'publish'			=>	esc_html__( 'Public', 'streamtube-core' ),
			'unlist'    		=>  esc_html__( 'Unlist', 'streamtube-core' ),
			'private'			=>	esc_html__( 'Private', 'streamtube-core' ),
			'reject'    		=>  esc_html__( 'Reject', 'streamtube-core' ),
			'trash'				=>	esc_html__( 'Trash', 'streamtube-core' )
		);		

		if( $filter && ! Streamtube_Core_Permission::moderate_posts() ){
			if( ! get_option( 'auto_publish', 'on' ) ){
				unset( $statuses['publish'] );
			}

			unset( $statuses['reject'] );
		}

		return apply_filters( 'streamtube/core/post_statuses_for_edit', $statuses, $filter );
	}

	/**
	 *
	 * Get all visiblity statuses
	 * 
	 * @param  boolean $filter
	 * @return array
	 * 
	 */
	public function get_post_statuses_for_read( $status = '' ){
		$statuses = array(
			'any'					=>	esc_html__( 'All', 'streamtube-core' ),
			'publish'				=>	esc_html__( 'Published', 'streamtube-core' ),
			'unlist'    			=>  esc_html__( 'Unlisted', 'streamtube-core' ),
			'private'				=>	esc_html__( 'Private', 'streamtube-core' ),
			'future'				=>	esc_html__( 'Scheduled', 'streamtube-core' ),
			'upcoming'				=>	esc_html__( 'Upcoming', 'streamtube-core' ),
			'membership'			=>	esc_html__( 'Membership', 'streamtube-core' ),
			'password_protected'	=>	esc_html__( 'Password Protected', 'streamtube-core' ),
			'pending'				=>	esc_html__( 'Pending Review', 'streamtube-core' ),
			'reject'    			=>  esc_html__( 'Rejected', 'streamtube-core' ),
			'draft'					=>	esc_html__( 'Draft', 'streamtube-core' ),
			'trash'					=>	esc_html__( 'Trash', 'streamtube-core' )
		);

		if( ! function_exists( 'pmpro_activation' ) ){
			unset( $statuses['membership'] );
		}

		if( class_exists( 'WP_Cloudflare_Stream_Permission' ) ){
			if( WP_Cloudflare_Stream_Permission::can_live_stream() ){
				$statuses['live'] = esc_html__( 'Live', 'streamtube-core' );	
			}
		}

		if( $status && array_key_exists( $status, $statuses ) ){
			return $statuses[ $status ];
		}

		return apply_filters( 'streamtube/core/post_statuses_for_read', $statuses );
	}

	/**
	 * 
	 * Update post thumbnail
	 * 
	 * @param int $post
	 * @param int $thumbnail_id
	 *
	 * @since 1.0.0
	 * 
	 */
	private function set_post_thumbnail( $post, $thumbnail_id ){

		set_post_thumbnail( $post, $thumbnail_id );

		wp_update_post( array(
			'ID'			=>	$thumbnail_id,
			'post_parent'	=>	$post
		) );
	}

	/**
	 *
	 * Upload featured image
	 * 
	 * @return media_handle_upload()
	 *
	 * @since  1.0.0
	 * 
	 */
	public function upload_featured_image(){

		if( ! isset( $_FILES[ 'featured-image' ] ) ){
			return false;
		}

		$attachment_id = media_handle_upload( 'featured-image', $_POST['post_ID'], array( '' ), array( 'test_form' => false ) );

		if( ! is_wp_error( $attachment_id ) ){
			$this->set_post_thumbnail( $_POST['post_ID'], $attachment_id );
		}

		return $attachment_id;
	}

	/**
	 *
	 * Get full post data
	 * 
	 * @param  [type] $post_id
	 *
	 * @since  1.0.0
	 * 
	 */
	private function get_post( $post_id ){

		$post = get_post( $post_id, ARRAY_A );

		$response = (object)array_merge( $post, array(
			'post_date_format'	=>	date( 'Y-m-d\TH:i' , strtotime( $post['post_date'] ) ),
			'post_thumbnail'	=>	get_the_post_thumbnail_url( $post_id, 'size-560-315' ),
			'post_embed_html'	=>	get_post_embed_html( 560, 315, $post_id ),
			'post_short_link'	=>	wp_get_shortlink( $post_id ),
			'post_link'			=>	get_permalink( $post_id ),
			'post_edit_link'	=>	add_query_arg( array(
				'edit_post'	=>	1
			), wp_get_shortlink( $post_id ) )
		) );

		/**
		 *
		 * Filter get_post result
		 *
		 * @param object $response
		 * @param int $post_id
		 * @param array $post
		 * 
		 */
		return apply_filters( 'streamtube/core/get_full_post_data', $response, $post_id, $post );
	}

	/**
	 *
	 * Add new post on POST request
	 *
	 * @return int  $post_id
	 *
	 * @since 1.0.0
	 * 
	 * 
	 */
	private function add_post( $postarr = array() ){

		$errors = new WP_Error();

		$postarr = wp_parse_args( $postarr, array(
			'post_title'		=>	'Untitled',
			'post_status'		=>	'draft',
			'comment_status'	=>	'open'
		) );		

		if( ! Streamtube_Core_Permission::can_edit_posts() ){
			$errors->add(
				'no_permission',
				esc_html__( 'Sorry, you do not have permission to add new post.', 'streamtube-core' )
			);
		}

		if( ! Streamtube_Core_Permission::moderate_posts()
			&& get_option( 'upload_files_verified_user' )
			&& ! $this->User->is_verified( get_current_user_id() )
		){
			$errors->add(
				'not_verified',
				esc_html__( 'Sorry, you have not been verified yet.', 'streamtube-core' )
			);
		}

		if( ! $postarr['post_title'] ){
			$errors->add(
				'empty_title',
				esc_html__( 'Title is required', 'streamtube-core' )
			);
		}

		/**
		 *
		 * Filter the errors
		 * 
		 * @var WP_Error
		 *
		 * @since  1.0.0
		 * 
		 */
		$errors = apply_filters( 'streamtube/core/post/add_post/errors', $errors, $postarr );

		if( $errors->get_error_code() ){
			return $errors;
		}

		if( $postarr['post_status'] == 'publish' && ! Streamtube_Core_Permission::moderate_posts() ){
			$postarr['post_status'] = 'pending';
		}

		if( get_option( 'auto_publish' ) ){
			$postarr['post_status'] = 'publish';
		}

		$postarr  = apply_filters( 'streamtube/core/post/add/postarr/pre', $postarr );

		$post_id = wp_insert_post( $postarr, true );

		if( ! is_wp_error( $post_id ) && is_int( $post_id ) ){

			$_POST['post_ID'] = $post_id;

			$this->upload_featured_image();

			do_action( 'streamtube/core/post/added', $post_id, $postarr );

			return $this->get_post( $post_id );
		}

		// WP_Error
		return $post_id;
	}

	/**
	 *
	 * Do update post on POST request
	 * 
	 * @return int post_ID|WP_Error
	 *
	 * @since  1.0.0
	 * 
	 */
	public function update_post(){

		$errors = new WP_Error();

		if( ! isset( $_POST ) || ! array_key_exists( 'post_ID', $_POST ) ){
			$errors->add( 
				'post_not_found', 
				esc_html__( 'Post was not found.', 'streamtube-core' ) 
			);
		}

		$post_id = (int)$_POST['post_ID'];

		$postdata = get_post( $post_id );

		if( ! Streamtube_Core_Permission::can_edit_post( $post_id ) ){
			$errors->add( 
				'no_permission', 
				esc_html__( 'Sorry, you are not allowed to edit this post.', 'streamtube-core' ) 
			);
		}

		if( array_key_exists( 'post_date' , $_POST ) ){
			$_POST['post_date'] = date( 'Y-m-d H:i:s', strtotime( $_POST['post_date'] ));
			$_POST['post_date_gmt'] = get_gmt_from_date($_POST['post_date']);
		}

		if( ! Streamtube_Core_Permission::moderate_posts() ){

			if( isset( $_POST['post_status'] ) && ! empty( $_POST['post_status'] ) ){

				if( ! array_key_exists( $_POST['post_status'], $this->get_post_statuses_for_edit( true ) ) ){
					$_POST['post_status'] = 'pending';
				}
			}

			// If the status is reject, move it to pending review
			if( $postdata->post_status == 'reject' ){
				$_POST['post_status'] = 'pending';
			}
		}

		// Parse tax_input
		if( array_key_exists( 'tax_input' , $_POST ) ){
			$tax_input = $_POST['tax_input'];

			if( is_array( $tax_input ) ){

				$taxonomies = get_object_taxonomies( $postdata->post_type , 'object' );

				if( $taxonomies ){
					foreach ( $taxonomies as $tax => $object ){

						if( array_key_exists( $tax, $tax_input ) ){

							if( is_string( $tax_input[$tax] ) ){
								$terms = array_map( 'trim' , explode(",", $tax_input[$tax]) );
							}

							if( is_array( $tax_input[$tax] ) ){
								$terms = $tax_input[$tax];
							}

							$allow_max_items   = (int)get_option( $postdata->post_type . '_taxonomy_' . $tax . '_max_items', 0 );

							if( 
								! current_user_can( 'administrator' ) &&
								count( $terms ) > 0 && 
								$allow_max_items > 0 && 
								$allow_max_items < count( $terms ) 
							){

								$errors->add( 
									"max_{$tax}_items", 
									sprintf(
										esc_html__( 'You can only add up to %s %s', 'streamtube-core' ),
										$allow_max_items,
										$object->label
									)
								);
							}
						}
					}
				}				

			}
		}

		$_POST['post_author'] = $postdata->post_author;

		if( array_key_exists( 'post_name', $_POST ) && ! empty( $_POST['post_name'] ) ){
			$_POST['post_name'] = wp_unique_post_slug( 
				$_POST['post_name'], 
				$post_id, 
				$postdata->post_status, 
				$postdata->post_type, 
				$postdata->post_parent 
			);
		}

		if( array_key_exists( 'post_password', $_POST ) ){

			if( apply_filters( 'streamtube/core/post/edit/post_password', true, $postdata ) === true ){
				$_POST['post_password'] = wp_strip_all_tags( $_POST['post_password'] );
			}else{
				unset( $_POST['post_password'] );
			}
		}

        if( isset( $_FILES ) && array_key_exists( 'featured-image', $_FILES ) && $_FILES['featured-image'] ){

            $thumbnail_file = $_FILES[ 'featured-image' ];

            if( $thumbnail_file['error'] == 0 ){
                $type = array_key_exists( 'type' , $thumbnail_file ) ? $thumbnail_file['type'] : '';

                if ( 0 !== strpos( $type, 'image/' ) ) {
                    $errors->add( 
                        'file_not_accepted', 
                        esc_html__( 'File format is not accepted.', 'streamtube-core' )
                    );
                }

                $max_size = streamtube_core_get_max_upload_image_size();

                if( $thumbnail_file['size'] > $max_size ){
                    $errors->add( 
                        'file_size_not_allowed',
                        sprintf(
                            esc_html__( 'File size has to be smaller than %s', 'streamtube-core' ),
                            size_format( $max_size )
                        )
                    );                    
                }
            }
        }		

		/**
		 *
		 * Filter the errors
		 * 
		 * @var WP_Error
		 *
		 * @since  1.0.0
		 * 
		 */
		$errors = apply_filters( 'streamtube/core/post/update/errors', $errors );

		if( $errors->get_error_code() ){
			return $errors;
		}		

		$post_id = edit_post();

		if( is_int( $post_id ) ){

			$this->upload_featured_image();

			if( array_key_exists( 'meta_input', $_POST ) ){

				$meta_input = $_POST['meta_input'];

				// Update custom field values
				$custom_fields = array( 
					'_embed', 
					'_length', 
					'_aspect_ratio',
					'_vr', 
					'_upcoming_date' 
				);
				
				for ( $i=0; $i < count( $custom_fields ); $i++ ) { 

					$_meta_value = isset( $meta_input[ $custom_fields[$i] ] ) ? wp_strip_all_tags( $meta_input[ $custom_fields[$i] ] ) : false;

					switch ( $custom_fields[$i] ) {
						case '_upcoming_date':
							$this->update_upcoming_date( $post_id, $_meta_value );
						break;
						
						default:
							if( $_meta_value ){
								update_post_meta( $post_id, $custom_fields[$i], $_meta_value );
							}
							else{
								delete_post_meta( $post_id, $custom_fields[$i] );
							}
						break;
					}
				}
			}

			$source = $this->get_source( $post_id );

			if( wp_attachment_is( 'video', $source ) ){
				$thumbnail_id_2 = get_post_meta( $source, '_thumbnail_id_2', true );

				if( $thumbnail_id_2 ){
					$this->update_thumbnail_image_url_2( $post_id, $thumbnail_id_2 );
				}
			}

			if( current_user_can( 'edit_others_posts' ) ){
				if( isset( $_POST['disable_ad'] ) ){
					update_post_meta( $post_id, 'disable_ad', 'on' );
				}
				else{
					delete_post_meta( $post_id, 'disable_ad' );	
				}
			}

			if( get_option( 'allow_edit_source' ) || current_user_can( 'administrator' ) ){

				if( isset( $_POST['video_trailer'] ) ){

					$trailer = ! current_user_can( 'unfiltered_html' ) ? wp_strip_all_tags( $_POST['video_trailer'] ) : wp_unslash( $_POST['video_trailer'] );

					$this->update_video_trailer( $post_id, $trailer );
				}				

				if( isset( $_POST['video_source'] ) ){

					$source = ! current_user_can( 'unfiltered_html' ) ? wp_strip_all_tags( $_POST['video_source'] ) : wp_unslash( $_POST['video_source'] );

					$this->update_source( $post_id, $source );
				}
			}

			/**
			 * Fires after post updated successfully.
			 *
			 * @param  int $post_id
			 * 
			 * @since  1.0.0
			 */
			do_action( 'streamtube/core/post/updated', $post_id );
		}

		return $this->get_post( $post_id );
	}

	/**
	 *
	 * Do trash post on POST request
	 * 
	 * @return int post_ID|WP_Error
	 *
	 * @since  1.0.0
	 * 
	 */
	public function trash_post( $post_id = 0 ){

		$errors = new WP_Error();		

		if( isset( $_POST ) && array_key_exists( 'post_id' , $_POST ) ){
			$post_id = (int)$_POST['post_id'];
		}

		if ( ! Streamtube_Core_Permission::can_edit_post( $post_id ) ) {
			$errors->add( 
				'no_permission', 
				esc_html__( 'Sorry, you are not allowed to trash this post.', 'streamtube-core' ) 
			);
		}

		/**
		 *
		 * Filter the errors
		 * 
		 * @var WP_Error
		 *
		 * @since  1.0.0
		 * 
		 */
		$errors = apply_filters( 'streamtube/core/post/trash/errors', $errors, $post_id );

		if( $errors->get_error_code() ){
			return $errors;
		}		

		$post = wp_trash_post( $post_id );

		if( is_object( $post ) ){
			/**
			 *
			 * Fires after post rejected.
			 *
			 * @param  int $post_id
			 *
			 * @since  1.0.0
			 * 
			 */
			do_action( 'streamtube_core_post_trashed', $post_id );
		}

		return $post;
	}

	/**
	 *
	 * Do Delete permanently post on POST request
	 * 
	 * @return int post_ID|WP_Error
	 *
	 * @since  1.0.0
	 * 
	 */
	public function delete_post( $post_id = 0 ){

		if( isset( $_POST ) && array_key_exists( 'post_id' , $_POST ) ){
			$post_id = (int)$_POST['post_id'];
		}

		$errors = new WP_Error();

		if ( ! Streamtube_Core_Permission::can_delete_post( $post_id ) ) {
			$errors->add( 
				'no_permission', 
				esc_html__( 'Sorry, you are not allowed to delete this post.', 'streamtube-core' ) 
			);
		}

		/**
		 *
		 * Filter the errors
		 * 
		 * @var WP_Error
		 *
		 * @since  1.0.0
		 * 
		 */
		$errors = apply_filters( 'streamtube/core/post/delete/errors', $errors, $post_id );

		if( $errors->get_error_code() ){
			return $errors;
		}			

		return wp_delete_post( $post_id, true );
	}

	/**
	 *
	 * Do approve post on POST request
	 * 
	 * @return int post_ID|WP_Error
	 *
	 * @since  1.0.0
	 * 
	 */
	public function approve_post( $post_id = 0 ){

		$errors = new WP_Error();

		if( isset( $_POST ) && array_key_exists( 'post_id' , $_POST ) ){
			$post_id = (int)$_POST['post_id'];
		}

		if( ! Streamtube_Core_Permission::moderate_posts() ){
			$errors->add( 
				'no_permission', 
				esc_html__( 'You do not have permission to approve this post.', 'streamtube-core' ) 
			);
		}

		/**
		 *
		 * Filter the errors
		 * 
		 * @var WP_Error
		 *
		 * @since  1.0.0
		 * 
		 */
		$errors = apply_filters( 'streamtube/core/post/approve/errors', $errors, $post_id );

		if( $errors->get_error_code() ){
			return $errors;
		}		

		$message = '';

		$response = wp_update_post( array(
			'ID'			=>	$post_id,
			'post_status'	=>	'publish'
		) );

		if( ! $response || is_wp_error( $response ) ){
			return $response;
		}

		if( apply_filters( 'notify_author_post_approve', true ) === true ){

			$message = isset( $_POST['message'] ) ? wp_unslash( $_POST['message'] ) : '';

			streamtube_core_notify_author_on_post_approve( $post_id, $message );
		}

		/**
		 *
		 * Fires after post approved.
		 *
		 * @param  int $post_id
		 *
		 * @since  1.0.0
		 * 
		 */
		do_action( 'streamtube_core_post_approved', $post_id, 'approved', $message );

		return $response;
	}

	/**
	 *
	 * Reject post
	 * 
	 * @param  integer $post_id
	 * 
	 */
	public function reject_post( $post_id = 0 ){

		$errors = new WP_Error();

		if( isset( $_POST ) && array_key_exists( 'post_id' , $_POST ) ){
			$post_id = (int)$_POST['post_id'];
		}

		if( ! Streamtube_Core_Permission::moderate_posts() ){
			$errors->add( 
				'no_permission', 
				esc_html__( 'You do not have permission to reject this post.', 'streamtube-core' ) 
			);
		}

		/**
		 *
		 * Filter the errors
		 * 
		 * @var WP_Error
		 *
		 * @since  1.0.0
		 * 
		 */
		$errors = apply_filters( 'streamtube/core/post/reject/errors', $errors, $post_id );

		if( $errors->get_error_code() ){
			return $errors;
		}		

		$message = '';

		$response = wp_update_post( array(
			'ID'			=>	$post_id,
			'post_status'	=>	'reject'
		) );

		if( ! $response || is_wp_error( $response ) ){
			return $response;
		}

		if( apply_filters( 'notify_author_post_reject', true ) === true ){

			$message = isset( $_POST['message'] ) ? wp_unslash( $_POST['message'] ) : '';

			streamtube_core_notify_author_on_post_reject( $post_id, $message );
		}		

		/**
		 *
		 * Fires after post rejected.
		 *
		 * @param  int $post_id
		 *
		 * @since  1.0.0
		 * 
		 */
		do_action( 'streamtube_core_post_rejected', $post_id, 'rejected', $message );

		return $response;			
	}

	/**
	 *
	 * Mark post as pending
	 * 
	 * @param  integer $post_id
	 * @return WP_Error|wp_update_post()
	 *
	 * @since 1.0.0
	 * 
	 */
	public function pending_post( $post_id = 0 ){

		$errors = new WP_Error();

		if( isset( $_POST ) && array_key_exists( 'post_id', $_POST ) ){
			$post_id = (int)$_POST['post_id'];
		}

		if( ! Streamtube_Core_Permission::moderate_posts() ){
			$errors->add( 
				'no_permission', 
				esc_html__( 'You do not have permission to move this post to pending.', 'streamtube-core' ) 
			);
		}

		/**
		 *
		 * Filter the errors
		 * 
		 * @var WP_Error
		 *
		 * @since  1.0.0
		 * 
		 */
		$errors = apply_filters( 'streamtube/core/post/pending/errors', $errors, $post_id );

		if( $errors->get_error_code() ){
			return $errors;
		}			

		$post = wp_update_post( array(
			'ID'			=>	$post_id,
			'post_status'	=>	'pending'
		), true );	

		if( ! is_wp_error( $post ) ){
			/**
			 *
			 * Fires after post pending.
			 *
			 * @param  int $post_id
			 *
			 * @since  1.0.0
			 * 
			 */
			do_action( 'streamtube_core_post_pending', $post_id );
		}

		return $post;
	}

	/**
	 *
	 * Restore a give post
	 * 
	 * @param  integer $post_id
	 * @return WP_Error|wp_untrash_post()
	 *
	 * @since 1.0.0
	 * 
	 */
	public function restore_post( $post_id = 0 ){

		$errors = new WP_Error();

		if( ! Streamtube_Core_Permission::can_edit_post( $post_id ) ){
			$errors->add( 
				'no_permission', 
				esc_html__( 'You do not have permission to restore this post.', 'streamtube-core' ) 
			);
		}

		/**
		 *
		 * Filter the errors
		 * 
		 * @var WP_Error
		 *
		 * @since  1.0.0
		 * 
		 */
		$errors = apply_filters( 'streamtube/core/post/restore/errors', $errors, $post_id );

		if( $errors->get_error_code() ){
			return $errors;
		}		

		$post = wp_untrash_post( $post_id );

		if( is_object( $post ) ){
			/**
			 *
			 * Fires after post restored.
			 *
			 * @param  int $post_id
			 *
			 * @since  1.0.0
			 * 
			 */
			do_action( 'streamtube_core_post_restored', $post_id );
		}

		return $post;
	}

	/**
	 *
	 * Encode video post
	 * 
	 * @param  integer $post_id
	 *
	 * @since  1.0.0
	 * 
	 */
	public function encode_post( $post_id = 0 ){

		$errors = new WP_Error();

		if( ! function_exists( 'wp_video_encoder' ) ){
			$errors->add( 
				'wp_video_encoder_not_activated', 
				esc_html__( 'WP Video Encoder is not activated yet.', 'streamtube-core' ) 
			);
		}		

		if( ! Streamtube_Core_Permission::can_edit_post( $post_id ) ){
			$errors->add( 
				'no_permission', 
				esc_html__( 'You do not have permission to encode this video.', 'streamtube-core' ) 
			);
		}		

		/**
		 *
		 * Filter the errors
		 * 
		 * @var WP_Error
		 *
		 * @since  1.0.0
		 * 
		 */
		$errors = apply_filters( 'streamtube/core/post/encode/errors', $errors, $post_id );

		if( $errors->get_error_code() ){
			return $errors;
		}		

		$source = $this->get_source( $post_id );

		if( wp_attachment_is( 'video', $source ) ){
			return wpve_insert_queue_item( $source );
		}
	}

	/**
	 *
	 * Bulk action
	 * 
	 * @param  integer $post_id
	 * @param  string action
	 * @return WP_Error|wp_untrash_post()
	 *
	 * @since 1.0.0
	 * 
	 */
	public function bulk_action( $post_id, $action = '' ){

		$errors = new WP_Error();

		$allow_actions = array( 'approve', 'reject', 'pending', 'trash', 'delete', 'restore', 'encode' );

		if( ! $post_id || ! $action || ! in_array( $action , $allow_actions ) ){
			$errors->add(
				'invalid_request',
				esc_html__( 'Invalid Request', 'streamtube-core' )
			);
		}

		if( in_array( $action , array( 'approve', 'reject', 'pending', 'encode' ) ) ){
				if( ! Streamtube_Core_Permission::moderate_posts() ){
				$errors->add(
					'no_permission', 
					esc_html__( 'You do not have permission to process this action.', 'streamtube-core' ) 
				);
			}
		}

		/**
		 *
		 * Filter the errors
		 * 
		 * @var WP_Error
		 *
		 * @since  1.0.0
		 * 
		 */
		$errors = apply_filters( 'streamtube/core/post/bulk_action', $errors, $action );

		if( $errors->get_error_code() ){
			return $errors;
		}

		return call_user_func( array( $this , $action . '_post' ), $post_id );
	}

	/**
	 *
	 * Import embed URL
	 * 
	 * @param  string $source
	 * @return WP_Post
	 *
	 * @since 2.0
	 * 
	 */
	public function import_embed( $source = '' ){

		$errors = new WP_Error();

		$thumbnail_url = '';

		$source = wp_unslash( trim( $source ) );

		if( ! current_user_can( 'unfiltered_html' ) ){
			$source = wp_strip_all_tags( $source );
		}

		if( ! get_option( 'embed_videos', 'on' ) ){
			$errors->add(
				'embed_videos_disabled',
				esc_html__( 'Sorry, Embedding video is disabled', 'streamtube-core' )
			);
		}

		if( ! Streamtube_Core_Permission::can_edit_posts() ){
			$errors->add(
				'no_permission',
				esc_html__( 'Sorry, You do not have permission to embed videos', 'streamtube-core' )
			);
		}		

		if(
			! Streamtube_Core_Permission::moderate_posts() 
			&& get_option( 'upload_files_verified_user' )
			&& ! $this->User->is_verified( get_current_user_id() )
		){
			$errors->add(
				'not_verified',
				esc_html__( 'Sorry, you have not been verified yet', 'streamtube-core' )
			);
		}

		if( empty( $source ) ){
			$errors->add(
				'empty_source',
				esc_html__( 'Source is required.', 'streamtube-core' )
			);
		}

		/**
		 *
		 * Filter the errors
		 * 
		 * @var WP_Error
		 *
		 * @since  1.0.0
		 * 
		 */
		$errors = apply_filters( 'streamtube/core/post/import_embed/errors', $errors , $source );

		if( $errors->get_error_code() ){
			return $errors;
		}		

		$postarr = array(
			'post_title'		=>	'Untitled',
			'post_type'			=>	self::CPT_VIDEO,
			'comment_status'	=>	'open',
			'meta_input'		=>	array(
				self::VIDEO_URL => $source
			)
		);

		$oEmbed = new Streamtube_Core_oEmbed();

		$oembed_data = $oEmbed->get_data( $source );

		if( ! is_wp_error( $oembed_data ) ){
			$postarr = array_merge( $postarr, array(
				'post_content'	=>	$oembed_data['provider_name']
			) );

			if( ! empty( $oembed_data['title'] ) ){
				$postarr['post_title'] = $oembed_data['title'];
			}

			$thumbnail_url = $oembed_data['thumbnail_url'];
		}

		/**
		 *
		 * Fires post args
		 *
		 * @param  array $postarr
		 * @param  string $source
		 * @param  array $oembed_data
		 *
		 * @since  1.0.0
		 * 
		 */
		$postarr  = apply_filters( 'streamtube/core/embed/postarr', $postarr, $source, $oembed_data );

		$response = $this->add_post( $postarr );

		if( is_wp_error( $response ) ){
			return $response;
		}

		/**
		 *
		 * Filter thumbnail URL
		 * 
		 */
		$thumbnail_url = apply_filters( 'streamtube/core/embed/thumbnail_url', $thumbnail_url, $source, $oembed_data );

		if( $thumbnail_url ){

			if( ! function_exists( 'media_sideload_image' ) ){
				require_once(ABSPATH . 'wp-admin/includes/media.php');
				require_once(ABSPATH . 'wp-admin/includes/file.php');
				require_once(ABSPATH . 'wp-admin/includes/image.php');				
			}

			$thumbnail_id = media_sideload_image( $thumbnail_url, $response->ID, null, 'id' );

			if( is_int( $thumbnail_id ) ){
				$this->set_post_thumbnail( $response->ID, $thumbnail_id );
			}
		}

		/**
		 *
		 * Fires after embed imported
		 *
		 * @param  WP_Post $response
		 * @param  string $source
		 * @param  array $oembed_data
		 *
		 * @since  1.0.0
		 * 
		 */
		do_action( 'streamtube/core/embed/imported', $response, $source, $oembed_data );

		return $this->get_post( $response->ID );
	}

	/**
	 *
	 * AJAX import embed
	 * 
	 * @since  1.0.0
	 * 
	 */
	public function ajax_import_embed(){

		check_ajax_referer( '_wpnonce' );	

		$data = wp_parse_args( $_POST, array(
			'source'	=>	''
		) );	

		$response  = $this->import_embed( $data['source'] );

		if( is_wp_error( $response ) ){
			wp_send_json_error( array(
				'message'	=>	$response->get_error_messages(),
				'errors'	=>	$response
			) );
		}

		wp_send_json_success( array(
			'message'		=>	sprintf(
				esc_html__( '%s has been imported successfully.' , 'streamtube-core'),
				'<strong>'. $response->post_title .'</strong>'
			),
			'post'			=>	$response,
			'form'			=>	$this->the_edit_post_form( $response )
		) );
	}

	/**
	 *
	 * do Upload video on regular POST request
	 * 
	 * @return Wp_Error|Array
	 *
	 * @since  1.0.0
	 * 
	 */
	public function upload_video(){

		$errors = new WP_Error();

		if( ! get_option( 'upload_files', 'on' ) ){
			$errors->add( 
				'upload_files_disabled', 
				esc_html__( 'Uploading files is disabled.', 'streamtube-core' ) 
			);			
		}

		if( ! Streamtube_Core_Permission::can_upload() ){
			$errors->add(
				'no_permission',
				esc_html__( 'Sorry, You do not have permission to upload videos', 'streamtube-core' )
			);
		}		

		// Check size
		$allow_size = (int)streamtube_core_get_max_upload_size();

		if( ! isset( $_FILES['video_file'] ) || (int)$_FILES['video_file']['error'] != 0 ){
			$errors->add( 
				'file_error', 
				esc_html__( 'File was not found or empty.', 'streamtube-core' ) 
			);
		}

		if( $allow_size < (int)$_FILES['video_file']['size'] ){
			$errors->add( 
				'file_size_not_allowed', 
				esc_html__( 'The upload file exceeds the maximum allow file size.', 'streamtube-core' ) 
			);
		}

		$file_type = wp_check_filetype( $_FILES['video_file']['name'] );

		if( ! $file_type ){
			$errors->add( 
				'file_type_not_allowed', 
				esc_html__( 'File Type is not allowed.', 'streamtube-core' ) 
			);
		}

		$type = explode( '/' , $file_type['type'] );

		if( ! is_array( $type ) || count( $type ) != 2 || ! in_array( $type[0], array( 'video', 'audio' )) ){
			$errors->add( 
				'file_type_not_allowed', 
				esc_html__( 'File Type is not allowed.', 'streamtube-core' ) 
			);
		}

		if( $type[0] == 'video' && ! in_array( strtolower( $file_type['ext'] ) , wp_get_video_extensions() ) ){
			$errors->add( 
				'video_format_not_allowed', 
				esc_html__( 'Video Format is not allowed.', 'streamtube-core' ) 
			);
		}

		if( $type[0] == 'audio' && ! in_array( $file_type['ext'] , wp_get_audio_extensions() ) ){
			$errors->add( 
				'audio_format_not_allowed', 
				esc_html__( 'Audio Format is not allowed.', 'streamtube-core' ) 
			);
		}		

		/**
		 *
		 * Filter the errors
		 * 
		 * @param WP_Error $errors
		 *
		 * @since  1.0.0
		 * 
		 */
		$errors = apply_filters( 'streamtube/core/upload/video/errors', $errors );

		if( $errors->get_error_code() ){
			return $errors;
		}

		/**
		 * Add new draft post
		 * @var [type]
		 */
		$post = $this->add_post( array(
			'post_title'	=>	preg_replace( '/\.[^.]+$/', '', basename( $_FILES['video_file']['name'] ) ),
			'post_type'		=>	self::CPT_VIDEO,
			'post_status'	=>	'draft'
		) );

		if( is_wp_error( $post ) ){
			return $post;
		}

		$attachment_id = media_handle_upload( 'video_file', $post->ID );

		if( is_wp_error( $attachment_id ) ){

			wp_delete_post( $post->ID, true );

			return $attachment_id;
		}

		$video_meta = get_post_meta( $attachment_id, '_wp_attachment_metadata', true );

		wp_update_post( array(
			'ID'			=>	$post->ID,
			'post_status'	=>	get_option( 'auto_publish' ) ? 'publish' : 'pending',
			'meta_input'	=>	array(
				self::VIDEO_URL 		=> $attachment_id,
				'_thumbnail_id'			=> get_post_thumbnail_id( $attachment_id ),
				'_length'				=> $video_meta['length']
			)
		), true );

		$thumbnail_id_2 = get_post_meta( $attachment_id, '_thumbnail_id_2', true );

		if( $thumbnail_id_2 ){
			$this->update_thumbnail_image_url_2( $post->ID, $thumbnail_id_2 );
		}

		wp_update_post( array(
			'ID'			=>	$attachment_id,
			'post_parent'	=>	$post->ID
		), true );

		/**
		 *
		 * Fires after video post added
		 *
		 * @param  $post WP_Post
		 * @param  int $attachment_id
		 *
		 * @since  1.0.0
		 * 
		 */
		do_action( 'streamtube/core/video/added', $post, $attachment_id );

		return $this->get_post( $post->ID );
	}

	/**
	 *
	 * Have to run this function after chunks uploaded to create new video post with given attachment
	 * 
	 * @param  integer $attachment_id
	 * @return $this->get_post();
	 *
	 * @since  1.0.0
	 * 
	 */
	public function upload_video_chunks( $attachment_id = 0 ){

		$errors = new WP_Error();

		$file_type = get_post_mime_type( $attachment_id );

		if( ! $file_type ){
			$errors->add(
				'invalid_file_format',
				esc_html__( 'Invalid file format.', 'streamtube-core' )
			);			
		}

		$type = explode( '/', $file_type );

		if( ! is_array( $type ) || count( $type ) != 2 || ! in_array( $type[0], array( 'video', 'audio' )) ){
			$errors->add( 
				'file_type_not_allowed', 
				esc_html__( 'File Type is not allowed.', 'streamtube-core' ) 
			);
		}

		if( ! in_array( $type[0] , array( 'video', 'audio' )) ){
			$errors->add( 
				'file_format_not_allowed', 
				esc_html__( 'File Format is not allowed.', 'streamtube-core' ) 
			);
		}

		if( ! Streamtube_Core_Permission::can_edit_post( $attachment_id ) ){
			$errors->add(
				'no_permission',
				esc_html__( 'You do not have permission to do this action.', 'streamtube-core' )
			);
		}

		/**
		 *
		 * Filter the errors
		 * 
		 * @param WP_Error $errors
		 *
		 * @since  1.0.0
		 * 
		 */
		$errors = apply_filters( 'streamtube/core/upload_chunks/video/errors', $errors );		

		if( $errors->get_error_code() ){

			wp_delete_attachment( $attachment_id );

			return $errors;
		}		

		$video_meta = get_post_meta( $attachment_id, '_wp_attachment_metadata', true );

		$postarr = array(
			'post_title'	=>	get_the_title( $attachment_id ),
			'post_type'		=>	self::CPT_VIDEO,
			'meta_input'	=>	array(
				self::VIDEO_URL => $attachment_id,
				'_thumbnail_id'			=> get_post_thumbnail_id( $attachment_id ),
				'_length'				=> $video_meta['length']
			)
		);

		$post = $this->add_post( $postarr );

		if( ! is_wp_error( $post ) ){
			wp_update_post( array(
				'ID'			=>	$attachment_id,
				'post_parent'	=>	$post->ID
			) );

			$thumbnail_id_2 = get_post_meta( $attachment_id, '_thumbnail_id_2', true );

			if( $thumbnail_id_2 ){
				update_post_meta( 
					$post->ID, 
					'_animation_image',
					wp_get_attachment_image_url( $thumbnail_id_2, 'full' ) 
				);			
			}

			/**
			 *
			 * Fires after video post added
			 *
			 * @param  $post WP_Post
			 * @param  int $attachment_id
			 *
			 * @since  1.0.0
			 * 
			 */
			do_action( 'streamtube/core/video/added', $post, $attachment_id );			

			return $this->get_post( $post->ID );
		}

		return $post;
	}

	/**
	 *
	 * Report video
	 * 
	 * @param  integer $post_id
	 * @param  integer $category_id
	 * @return true|WP_Error
	 *
	 * @since 2.2.1
	 * 
	 */
	public function report_video(){

		if( ! isset( $_POST ) ){
			return new WP_Error(
				'invalid_requested',
				esc_html_( 'Invalid Requested', 'streamtube-core' )
			);
		}

		$http_data = wp_parse_args( $_POST, array(
			'post_id'		=>	0,
			'category'		=>	0,
			'description'	=>	''
		) );

		if( get_post_type( $http_data['post_id'] ) != self::CPT_VIDEO ){
			return new WP_Error(
				'invalid_video_id',
				esc_html__( 'Invalid Video ID', 'streamtube-core' )
			);
		}

		$_cache = sprintf( 'report_%s_%s', get_current_user_id(), $http_data['post_id'] );

		if( false !== $was_sent = get_transient( $_cache ) ){
			return new WP_Error(
				'report_was_sent',
				sprintf(
					esc_html__( 'Report was sent %s ago', 'streamtube-core' ),
					human_time_diff( $was_sent, current_time( 'timestamp' ) )
				)
			);			
		}

		if( $http_data['category'] ){

			$http_data['category'] = (int)$http_data['category'];

			$check_term = get_term_by( 'term_id', $http_data['category'], Streamtube_Core_Taxonomy::TAX_REPORT );

			if( $check_term ){
				wp_set_post_terms( $http_data['post_id'], $http_data['category'], Streamtube_Core_Taxonomy::TAX_REPORT, true );	
			}else{
				$http_data['category'] = 0;
			}
		}

		streamtube_core_notify_admin_on_report( 
			$http_data['post_id'], 
			$http_data['category'], 
			$http_data['description'] 
		);

		/**
		 * @since 2.2.1
		 */
		do_action( 'streamtube/core/video/report_sent' );

		return set_transient( $_cache, current_time( 'timestamp' ), 60*60 );
	}

	/**
	 *
	 * do AJAX upload video
	 * 
	 * @since  1.0.0
	 */
	public function ajax_upload_video(){

		check_ajax_referer( '_wpnonce' );

		$response  = $this->upload_video();

		if( is_wp_error( $response ) ){
			wp_send_json_error( array(
				'message'	=>	$response->get_error_messages(),
				'errors'	=>	$response
			) );
		}

		wp_send_json_success( array(
			'message'			=>	sprintf(
				esc_html__( '%s has been uploaded successfully.' , 'streamtube-core'),
				'<strong>'. $response->post_title .'</strong>'
			),
			'post'	=>	$response,
			'form'	=>	$this->the_edit_post_form( $response )
		) );
	}

	/**
	 *
	 * do AJAX check video chunk before sending to BigFileUploads->ajax_chunk_receiver();
	 * 
	 * @since  1.0.0
	 * 
	 */
	public function ajax_upload_video_chunk(){

		$errors = new WP_Error();

		$_post = wp_parse_args( $_POST, array(
			'name'	=>	'',
			'type'	=>	'',
			'size'	=>	''
		) );

		if( ! get_option( 'upload_files', 'on' ) ){
			$errors->add( 
				'upload_files_disabled', 
				esc_html__( 'Uploading files is disabled.', 'streamtube-core' ) 
			);			
		}		

		if( ! Streamtube_Core_Permission::can_upload() ){
			$errors->add(
				'no_permission',
				esc_html__( 'Sorry, You do not have permission to upload videos', 'streamtube-core' )
			);
		}

		if( 
			! Streamtube_Core_Permission::moderate_posts() 
			&& get_option( 'upload_files_verified_user' )
			&& ! $this->User->is_verified( get_current_user_id() )
		 ){
			$errors->add(
				'not_verified',
				esc_html__( 'Sorry, you have not been verified yet', 'streamtube-core' )
			);			
		}

		// Check size
		$allow_size = (int)streamtube_core_get_max_upload_size();

		if( $allow_size < (int)$_POST['size'] ){
			$errors->add( 
				'file_size_not_allowed', 
				esc_html__( 'The upload file exceeds the maximum allow file size.', 'streamtube-core' ) 
			);
		}		

		$file_type = wp_check_filetype( $_post['name'] );

		if( ! $file_type ){
			$errors->add( 
				'file_type_not_allowed', 
				esc_html__( 'File Type is not allowed.', 'streamtube-core' ) 
			);			
		}

		$type = explode( '/' , $file_type['type'] );

		if( ! is_array( $type ) || count( $type ) != 2 || ! in_array( $type[0], array( 'video', 'audio' )) ){
			$errors->add( 
				'file_type_not_allowed', 
				esc_html__( 'File Type is not allowed.', 'streamtube-core' ) 
			);
		}

		if( $type[0] == 'video' && ! in_array( strtolower( $file_type['ext'] ) , wp_get_video_extensions() ) ){
			$errors->add( 
				'video_format_not_allowed', 
				esc_html__( 'Video Format is not allowed.', 'streamtube-core' ) 
			);
		}

		if( $type[0] == 'audio' && ! in_array( $file_type['ext'] , wp_get_audio_extensions() ) ){
			$errors->add( 
				'audio_format_not_allowed', 
				esc_html__( 'Audio Format is not allowed.', 'streamtube-core' ) 
			);
		}

		if( ! class_exists( 'BigFileUploads' ) || ! method_exists( 'BigFileUploads', 'ajax_chunk_receiver' ) ){
			$errors->add(
				'BigFileUploads_not_found',
				esc_html__( 'BigFileUploads plugin was not found.', 'streamtube-core' )
			);
		}

		$errors = apply_filters( 'streamtube/core/upload_chunk/video/errors', $errors );

		if( $errors->get_error_code() ){
			wp_send_json_error( array(
				'message'	=>	$errors->get_error_messages(),
				'errors'	=>	$errors
			) );
		}

		$upload = new BigFileUploads();

		$upload->ajax_chunk_receiver();
	}

	/**
	 *
	 * Create new video after chunks uploaded completely.
	 * 
	 * @since 1.0.0
	 * 
	 */
	public function ajax_upload_video_chunks(){

		check_ajax_referer( '_wpnonce' );

		if( ! isset( $_POST['attachment_id'] ) || empty( $_POST['attachment_id'] ) ){
			wp_send_json_error( array(
				'message'	=>	esc_html__( 'video file not found.', 'streamtube-core' )
			) );
		}

		$response = $this->upload_video_chunks( $_POST['attachment_id'] );

		if( is_wp_error( $response ) ){
			wp_send_json_error( array(
				'message'	=>	$response->get_error_messages(),
				'errors'	=>	$response
			) );
		}

		wp_send_json_success( array(
			'message'			=>	sprintf(
				esc_html__( '%s has been uploaded successfully.' , 'streamtube-core' ),
				'<strong>'. $response->post_title .'</strong>'
			),
			'post'	=>	$response,
			'form'	=>	$this->the_edit_post_form( $response )
		) );
	}

	public function ajax_add_post(){

		check_ajax_referer( '_wpnonce' );

		$response = $this->add_post( $_POST );	

		if( is_wp_error( $response ) ){
			wp_send_json_error( array(
				'message'	=>	 $response->get_error_messages(),
				'errors'	=>	 $response
			) );
		}

		$url = streamtube_core_get_user_dashboard_url( get_current_user_id(), $response->post_type );

		if( ! get_option( 'permalink_structure' ) ){
			$url = add_query_arg( array(
				'post_id'	=>	$response->ID
			), $url );
		}
		else{
			$url = trailingslashit( $url ) . $response->ID;
		}

		wp_send_json_success( array(
			'message'		=> esc_html__( 'Post added.', 'streamtube-core' ),
			'redirect_url'	=> $url
		) );
	}

	/**
	 *
	 * Do AJAX update post on POST request
	 * 
	 * @since 1.0.0
	 * 
	 */
	public function ajax_update_post(){

		check_ajax_referer( '_wpnonce' );

		$quick_update 	= $auto_draft = false;
		$message1 		= $message2 = '';

		$post = $this->update_post();

		if( is_wp_error( $post ) ){
			wp_send_json_error( $post );
		}

		$post_type_object = get_post_type_object( $post->post_type );

		$message1 = sprintf(
			esc_html__( '%s updated', 'streamtube-core' ),
			$post_type_object->labels->singular_name
		);

		switch ( $post->post_status ) {
			case 'pending':
				$message2 = sprintf(
					esc_html__( 'This %s is pending review.', 'streamtube-core' ),
					strtolower( $post_type_object->labels->singular_name )
				);
			break;	
			
			case 'private':
				$message2 = sprintf(
					esc_html__( 'This %s is privated.', 'streamtube-core' ),
					strtolower( $post_type_object->labels->singular_name )
				);
			break;

			case 'unlist':
				$message2 = sprintf(
					esc_html__( 'This %s is unlisted.', 'streamtube-core' ),
					strtolower( $post_type_object->labels->singular_name )
				);
			break;			

			case 'future':
				$message2 = sprintf(
					esc_html__( 'This %s is scheduled.', 'streamtube-core' ),
					strtolower( $post_type_object->labels->singular_name )
				);
			break;

			case 'draft':
				$message2 = sprintf(
					esc_html__( 'This %s is drafted.', 'streamtube-core' ),
					strtolower( $post_type_object->labels->singular_name )
				);
			break;

			case 'reject':
				$message2 = sprintf(
					esc_html__( 'This %s is rejected.', 'streamtube-core' ),
					strtolower( $post_type_object->labels->singular_name )
				);
			break;

			case 'trash':
				$message2 = sprintf(
					esc_html__( 'This %s is trashed.', 'streamtube-core' ),
					strtolower( $post_type_object->labels->singular_name )
				);
			break;			
		}		

		if( isset( $_POST['quick_update'] ) ){
			$quick_update = true;
		}

		if( isset( $_POST['_auto_draft'] ) && wp_unslash( $_POST['_auto_draft'] ) == 'auto-draft' ){
			$auto_draft = true;
		}

		$response = compact( 'post', 'quick_update', 'message1', 'message2', 'auto_draft' );

		/**
		 *
		 * Filter the response
		 * 
		 * @param array $response
		 *
		 * @since  1.0.0
		 * 
		 */
		$response = apply_filters( 'streamtube/core/post/update/ajax/response', $response );

		wp_send_json_success( $response );
	}

	/**
	 *
	 * Do AJAX trash post on POST request
	 * 
	 * @since 1.0.0
	 * 
	 */
	public function ajax_trash_post(){
		check_ajax_referer( '_wpnonce' );

		$response = $this->trash_post();

		if( is_wp_error( $response ) ){
			wp_send_json_error( array(
				'message'	=>	$response->get_error_messages(),
				'errors'	=>	$response
			) );
		}

		wp_send_json_success( array(
			'message'		=>	sprintf(
				esc_html__( '%s has been trashed.', 'streamtube-core' ),
				'<strong>'. $response->post_title .'</strong>'
			),
			'post'			=>	$response,
			'redirect_url'	=>	streamtube_core_get_user_dashboard_url( get_current_user_id(), $response->post_type )
		) );
	}

	/**
	 *
	 * Do AJAX approve post on POST request
	 * 
	 * @since 1.0.0
	 * 
	 */	
	public function ajax_approve_post(){

		check_ajax_referer( '_wpnonce' );

		$response = $this->approve_post();

		if( is_wp_error( $response ) ){
			wp_send_json_error( array(
				'message'	=>	$response->get_error_messages(),
				'errors'	=>	$response
			) );
		}

		wp_send_json_success( array(
			'message'	=>	sprintf(
				esc_html__( '%s has been approved successfully.', 'streamtube-core' ),
				'<strong>'. get_post( $_POST['post_id'] )->post_title .'</strong>'
			),
			'post_id'	=>	$response
		) );
	}

	/**
	 *
	 * Do AJAX reject post on POST request
	 * 
	 * @since 1.0.0
	 * 
	 */	
	public function ajax_reject_post(){

		check_ajax_referer( '_wpnonce' );

		$response = $this->reject_post();

		if( is_wp_error( $response ) ){
			wp_send_json_error( array(
				'message'	=>	$response->get_error_messages(),
				'errors'	=>	$response
			) );
		}

		wp_send_json_success( array(
			'message'	=>	sprintf(
				esc_html__( '%s has been rejected successfully.', 'streamtube-core' ),
				'<strong>'. get_post( $_POST['post_id'] )->post_title .'</strong>'
			),
			'post_id'	=>	$response
		) );
	}

	public function ajax_restore_post(){

		check_ajax_referer( '_wpnonce' );

		if( ! isset( $_POST ) || ! isset( $_POST['data'] ) ){
			wp_send_json_error( array(
				'message'	=>	esc_html__( 'Invalid Request', 'streamtube-core' )
			) );
		}

		$data = json_decode( wp_unslash( sanitize_text_field( $_POST['data'] ) ), true );

		$data = wp_parse_args( $data, array(
			'post_id'	=>	0
		) );

		$response = $this->restore_post( $data['post_id'] );

		if( ! $response ){
			wp_send_json_error( array(
				'message'	=>	esc_html__( 'Undefined Error, please try again later.', 'streamtube-core' )
			) );
		}

		if( is_wp_error( $response ) ){
			wp_send_json_error( array(
				'message'	=>	$response->get_error_messages(),
				'errors'	=>	$response
			) );
		}

		wp_send_json_success( array(
			'message'	=>	sprintf(
				esc_html__( '%s has been restored successfully.', 'streamtube-core' ),
				'<strong>'. $response->post_title .'</strong>'
			),
			'post'		=>	$response
		) );
	}

	/**
	 * AJAX search posts
	 */
	public function ajax_search_posts(){

		check_ajax_referer( '_wpnonce' );

		$request = wp_parse_args( $_GET, array(
			'post_type'		=>	self::CPT_VIDEO,
			'responseType'	=>	'',
			's'				=>	''
		) );

		$query_args = array(
			'post_type'			=>	$request['post_type'],
			'post_status'		=>	'publish',
			'posts_per_page'	=>	20,
			's'					=>	$request['s'],
			'orderby'			=>	'name',
			'order'				=>	'ASC',
			'meta_query'		=>	array()
		);

		$posts = get_posts( $query_args );

		if( $request['responseType'] == 'select2' ){

			$results = array();

			if( $posts ){
				foreach( $posts as $post ){
					$results[] = array(
						'id'	=>	$post->ID,
						'text'	=>	sprintf( '(#%1$s) %2$s', $post->ID, $post->post_title )
					);
				}
			}

			wp_send_json_success( array(
				'results'	=>	$results,
				'pagination'	=>	array(
					'more'	=>	true
				)
			) );
		}

		wp_send_json_success( $posts );
	}

	/**
	 *
	 * AJAX report video
	 * 
	 * @since 2.2.1
	 */
	public function ajax_report_video(){

		check_ajax_referer( '_wpnonce' );

		if( ! get_option( 'button_report', 'on' ) ){
			wp_send_json_error( array(
				'message'	=>	esc_html__( 'Report is disabled', 'streamtube-core' )
			) );			
		}

		$response = $this->report_video();

		if( is_wp_error( $response ) ){
			wp_send_json_error( array(
				'message'	=>	$response->get_error_messages(),
				'errors'	=>	$response
			) );
		}

		wp_send_json_success( array(
			'message'	=>	esc_html__( 'Report has been sent successfully', 'streamtube-core' )
		) );
	}

	/**
	 *
	 * Update post meta on POST request
	 * 
	 * @param  [type] $post_id [description]
	 * @return [type]          [description]
	 */
	public function update_post_meta( $post_id ){

		$_meta = array( '_embed', '_ratio' );

		if( ! array_key_exists( 'meta_input' , $_POST ) || ! is_array( $_POST['meta_input'] ) ){
			$_POST['meta_input'] = array_fill_keys( $_meta, '' );
		}

		$meta_input = $_POST['meta_input'];

		for ( $i=0; $i < count( $_meta ); $i++) { 
			if( array_key_exists( $_meta[$i], $meta_input ) ){
				update_post_meta( $post_id, $_meta[$i], $meta_input[ $_meta[$i] ] );
			}
			else{
				delete_post_meta( $post_id, $_meta[$i] );	
			}
		}
	}

	/**
	 * Upload text track file controller
	 */
	public function upload_text_track(){

		$errors = new WP_Error();

		$max_size = apply_filters( 'streamtube/core/max_text_track_size', 1024*1024*10 );// 10MB

		$post_id = isset( $_POST['post_ID'] ) ? (int)$_POST['post_ID'] : 0;

		if( ! current_user_can( 'edit_post', $post_id ) || get_post_type( $post_id ) != self::CPT_VIDEO ){
			$errors->add( 
				'no_permission', 
				esc_html__( 'You do not have permission to upload subtitle for this post.', 'streamtube-core' ) 
			);
		}		

		if( ! isset( $_FILES['file'] ) || (int)$_FILES['file']['error'] != 0 ){
			$errors->add( 
				'file_error', 
				esc_html__( 'File was not found or empty.', 'streamtube-core' ) 
			);
		}

		if( $max_size < (int)$_FILES['file']['size'] ){
			$errors->add( 
				'file_size_not_allowed', 
				esc_html__( 'The upload file exceeds the maximum allow file size.', 'streamtube-core' ) 
			);
		}

		$file_type = wp_check_filetype($_FILES['file']['name']);

		if( ! in_array( strtolower( $file_type['ext'] ), self::get_text_track_format() ) ){
			$errors->add( 'file_not_accepted', esc_html__( 'File format is not accepted', 'streamtube-core' ) );
		}

		/**
		 *
		 * Filter the errors
		 * 
		 * @param WP_Error $errors
		 *
		 * @since  1.0.0
		 * 
		 */
		$errors = apply_filters( 'streamtube/core/upload/text_track/errors', $errors );

		if( $errors->get_error_code() ){
			return $errors;
		}

		return media_handle_upload( 'file', $post_id );
	}

	/**
	 * AJAX upload text track file controller
	 */
	public function ajax_upload_text_track(){
		check_ajax_referer( '_wpnonce' );

		$track_id = $this->upload_text_track();

		if( is_wp_error( $track_id ) ){
			wp_send_json_error( $track_id );
		}

		wp_send_json_success( wp_get_attachment_url( $track_id ) );
	}

	/**
	 * AJAX upload text track file controller
	 */
	public function update_text_tracks(){

		$errors 	= new WP_Error();

		$post_id 	= isset( $_POST['post_ID'] ) ? (int)$_POST['post_ID'] : 0;

		if( ! current_user_can( 'edit_post', $post_id ) || get_post_type( $post_id ) != self::CPT_VIDEO ){
			$errors->add( 
				'no_permission', 
				esc_html__( 'You do not have permission to update subtitles for this post.', 'streamtube-core' ) 
			);
		}

		if( ! array_key_exists( 'text_tracks', $_POST ) ){
			$errors->add( 
				'tracks_not_found', 
				esc_html__( 'No Subtitles were found.', 'streamtube-core' ) 
			);			
		}

		$_text_tracks = array();

		$text_tracks = wp_unslash( $_POST['text_tracks'] );

		for ( $i=0;  $i < count( $text_tracks['sources'] );  $i++) { 

			$language 	= $text_tracks['languages'][$i];
			$source 	= $text_tracks['sources'][$i];

			if( wp_http_validate_url( $source ) && $language ){
				$_text_tracks['languages'][] 	= $language;
				$_text_tracks['sources'][] 		= $source;
			}
		}

		/**
		 *
		 * Filter the errors
		 * 
		 * @param WP_Error $errors
		 *
		 * @since  1.0.0
		 * 
		 */
		$errors = apply_filters( 'streamtube/core/update/text_tracks/errors', $errors, $_text_tracks );

		if( $errors->get_error_code() ){
			return $errors;
		}

		return update_post_meta( $post_id, 'text_tracks', $_text_tracks );
	}

	/**
	 * AJAX uddate text tracks
	 */
	public function ajax_update_text_tracks(){
		check_ajax_referer( '_wpnonce' );

		$result = $this->update_text_tracks();

		if( is_wp_error( $result ) ){
			wp_send_json_error( $result );
		}		

		if( ! $result ){
			wp_send_json_error( new WP_Error(
				'error',
				esc_html__( 'Cannot update subtitles, it seems you tried to update the same content.', 'streamtube-core' )
			) );
		}

		wp_send_json_success( array(
			'message'	=>	esc_html__( 'Subtitles have been updated successfully', 'streamtube-core' )
		) );
	}

	/**
	 *
	 * Updaet Alt sources
	 * 
	 */
	public function update_altsources(){
		$errors 	= new WP_Error();

		$post_id 	= isset( $_POST['post_ID'] ) ? (int)$_POST['post_ID'] : 0;

		if( ! current_user_can( 'edit_post', $post_id ) || get_post_type( $post_id ) != self::CPT_VIDEO ){
			$errors->add( 
				'no_permission', 
				esc_html__( 'You do not have permission to update alt sources for this post.', 'streamtube-core' ) 
			);
		}

		if( ! array_key_exists( 'altsources', $_POST ) ){
			$errors->add( 
				'altsources_not_found', 
				esc_html__( 'No sources were found.', 'streamtube-core' ) 
			);			
		}

		$_altsources = array();

		$altsources = $_POST['altsources'];

		for ( $i=0;  $i < count( $altsources['sources'] );  $i++) {

			$source = $altsources['sources'][$i];
			$label 	= $altsources['labels'][$i];

			if( ! current_user_can( 'unfiltered_html' ) ){
				$source = wp_strip_all_tags( trim( $source ) );
			}

			if( ! empty( $source ) && ! empty( $label ) ){
				$_altsources['labels'][] 	= $label;
				$_altsources['sources'][] 	= $source;
			}
		}

		/**
		 *
		 * Filter the errors
		 * 
		 * @param WP_Error $errors
		 *
		 * @since  1.0.0
		 * 
		 */
		$errors = apply_filters( 'streamtube/core/update/altsources/errors', $errors, $_altsources );

		if( $errors->get_error_code() ){
			return $errors;
		}

		return update_post_meta( $post_id, 'altsources', $_altsources );
	}

	/**
	 *
	 * Updaet Alt sources
	 * 
	 */
	public function ajax_update_altsources(){
		check_ajax_referer( '_wpnonce' );

		$result = $this->update_altsources();

		if( is_wp_error( $result ) ){
			wp_send_json_error( $result );
		}

		if( ! $result ){
			wp_send_json_error( new WP_Error(
				'error',
				esc_html__( 'Cannot update sources, it seems you tried to update the same content.', 'streamtube-core' )
			) );
		}

		wp_send_json_success( array(
			'message'	=>	esc_html__( 'Sources have been updated successfully', 'streamtube-core' )
		) );		
	}

	/**
	 *
	 * AJAX update embed privacy
	 * 
	 */
	public function ajax_update_embed_privacy(){

		check_ajax_referer( '_wpnonce' );

		$result = $this->update_embed_privacy();

		if( is_wp_error( $result ) ){
			wp_send_json_error( $result );
		}

		if( ! $result ){
			wp_send_json_error( new WP_Error(
				'error',
				esc_html__( 'Cannot update sources, it seems you tried to update the same content.', 'streamtube-core' )
			) );
		}

		wp_send_json_success( array(
			'message'	=>	esc_html__( 'Embedding Privacy have been updated successfully', 'streamtube-core' )
		) );		
	}

	public function ajax_get_post_thumbnail(){
		check_ajax_referer( '_wpnonce' );

		$post_id = isset( $_GET['post_id'] ) ? (int)$_GET['post_id'] : 0;

		if( ! $post_id || ! get_post_status( $post_id ) ){
			exit;
		}

		if( has_post_thumbnail( $post_id ) ){
			wp_send_json_success( get_the_post_thumbnail( $post_id ) );
		}

		wp_send_json_error( new WP_Error(
			'no_thumbnail',
			esc_html__( 'No Thumbnail was found', 'streamtube-core' )
		) );
	}

	/**
	 *
	 * AJAX get post by given url
	 * 
	 */
	public function ajax_get_post_by_url(){
		check_ajax_referer( '_wpnonce' );

		$url = isset( $_REQUEST['url'] ) ? trim( $_REQUEST['url'] ) : false;

		if( ! $url ){
			wp_send_json_error( new WP_Error(
				'url_not_found',
				esc_html__( 'URL was not found', 'streamtube-core' )
			) );
		}

		$post_id = url_to_postid( $url );

		if( ! $post_id ){
			wp_send_json_error( new WP_Error(
				'post_not_found',
				esc_html__( 'Post was not found', 'streamtube-core' )
			) );			
		}

		$post = get_post( $post_id );

		$length = (int)$this->get_length( $post_id );

	    if( $length >= 3600 ){
	        $length = gmdate( "H:i:s", $length%86400);
	    }else{
	        $length = gmdate( "i:s", $length%86400);
	    }

	    $author = get_userdata( $post->post_author );

		wp_send_json_success( array(
			'thumbnail'		=>	get_the_post_thumbnail_url( $post_id, 'streamtube-image-medium' ),
			'permalink'		=>	$url,
			'title'			=>	$post->post_title,
			'length'		=>	$length,
			'embed'			=>	get_post_embed_html( 560, 315, $post_id ),
			'author'		=>	array(
				'name'	=>	$author->display_name,
				'url'	=>	get_author_posts_url( $author->ID )
			)
		) );
	}

	/**
	 *
	 * Auto Complete search
	 * 
	 */
	public function ajax_search_autocomplete(){

		check_ajax_referer( '_wpnonce' );

		$output = '';

		$http_get = wp_parse_args( $_GET, array(
			's'					=>	'',
			'content_type'		=>	'video'
		) );

		extract( $http_get );

		if( empty( $s ) ){
			wp_send_json_error( new WP_Error(
				'keyword_not_found',
				esc_html__( 'No keywords were found', 'streamtube-core' )
			) );
		}

		add_filter( 'wp_doing_ajax', '__return_false' );

		unset( $_REQUEST[ 'action' ] );

		ob_start();

		$search_args = array(
			'id'					=>	'search_autocomplete',
			'post_type'				=>	$content_type,
			'post_status'			=>	'publish',
			'posts_per_page'		=>	get_option( 'search_autocomplete_number', 20 ),
			's'						=>	sanitize_text_field( $s ),
			'auto_tax_query'		=>	true,
			'layout'				=>	'list_sm',
			'thumbnail_ratio'		=>	'16x9',
			'thumbnail_size'		=>	'thumbnail',
			'hide_empty_thumbnail'	=>	true,
			'hide_if_empty'			=>	true,
			'pagination'			=>	false,
			'container'				=>  false,
			'show_post_date'		=>	'diff',
			'show_post_view'		=>	true,
			'col_xxl'				=>	1,
			'col_xl'				=>	1,
			'col_lg'				=>	1,
			'col_md'				=>	1,
			'col_sm'				=>	1,
			'col'					=>	1,
			'margin_bottom'			=>	0,
			'verified_users_only'   =>  get_option( 'search_verified_users_only' )
		);

		/**
		 *
		 * Filter $search_args
		 * 
		 */
		$search_args = apply_filters( 'streamtube/core/search/autocomplete/args', $search_args );

		the_widget( 'Streamtube_Core_Widget_Posts', $search_args );

		$output = trim( ob_get_clean() );

		if( ! empty( $output ) ){
			wp_send_json_success( $output );
		}

		wp_send_json_error( new WP_Error(
			'content_not_found',
			esc_html__( 'No content were found', 'streamtube-core' )
		) );
	}

	/**
	 *
	 * Update attachment title after updating its parent
	 *
	 * @since 2.1
	 * 
	 */
	public function sync_post_attachment( $post_id, $post ){
		$source = $this->get_source( $post_id );

		if( wp_attachment_is( 'video', $source ) ){
			wp_update_post( array(
				'ID'			=>	$source,
				'post_title'	=>	$post->post_title
			) );
		}
	}

	public function get_edit_post_url( $post_id, $endpoint = '' ){

		$userDashboard = new Streamtube_Core_User_Dashboard();

		$postdata = get_post( $post_id );

		$base_url = $userDashboard->get_endpoint( $postdata->post_author, $postdata->post_type );

		if( get_option( 'permalink_structure' ) ){
			return untrailingslashit( $base_url ) . '/' . $post_id . '/' . $endpoint;
		}

		return add_query_arg( array(
			$endpoint => 1,
			'post_id'	=>	$post_id
		), $base_url );
	}

	/**
	 *
	 * Get request post ID to edit
	 * 
	 * @since 1.0.0
	 * 
	 */
	public function get_edit_post_id(){

		$post_id = false;

		if( ! get_option( 'permalink_structure' ) ){
			if( isset( $GLOBALS['wp_query']->query_vars['post_id'] ) ){
				$post_id = (int)$GLOBALS['wp_query']->query_vars['post_id'];
			}
		}
		else{
			if( isset( $GLOBALS['wp_query']->query_vars['dashboard'] ) ){
				$request = explode( "/" , $GLOBALS['wp_query']->query_vars['dashboard'] );

				if( count( $request ) > 1 && get_post_status( $request[1] ) ){
					$post_id = (int)$request[1];
				}
			}
		}

		return $post_id;
	}

	/**
	 *
	 * Check if current is edit post screen
	 * 
	 * @return true|false
	 *
	 * @since  1.0.0
	 * 
	 */
	public function is_edit_post_screen(){
		return $this->get_edit_post_id();
	}

	/**
	 *
	 * Check if current is add new post screen
	 * 
	 * @return true|false
	 *
	 * @since  1.0.0
	 * 
	 */
	public function is_add_new_post_screen(){
		return isset( $_GET['view'] ) && $_GET['view'] == 'add-post' ? true : false;
	}	

	/**
	 * 
	 * Get user nav items
	 * 
	 * @return array
	 *
	 * @since  1.0.0
	 * 
	 */
	public function get_edit_post_menu_items( $post = null ){

		$post_id = $this->get_edit_post_id();

		if( $post_id ){
			$post = get_post( $post_id );
		}

		$items = array();

		$items['details'] 	= array(
			'title'			=>	esc_html__( 'Details', 'streamtube-core' ),
			'icon'			=>	'icon-edit',
			'template'		=>	streamtube_core_get_template( 'post/edit/details.php' ),
			'priority'		=>	1
		);

		$items['comments'] 	= array(
			'title'			=>	esc_html__( 'Comments', 'streamtube-core' ),
			'icon'			=>	'icon-comment',
			'template'		=>	streamtube_core_get_template( 'post/comments.php' ),
			'priority'		=>	20
		);

		/**
		 * filter items
		 *
		 * @since 1.0.0
		 */
		$items = apply_filters( 'streamtube_core_get_edit_post_nav_items', $items, $post );

		if( $post ){	

			if( $post->post_type == 'video' ){

				if( get_option( 'embed_privacy', 'anywhere' ) == 'custom' && $this->can_manage_embed_privacy( $post_id ) ){
					$items['embedding'] 	= array(
						'title'			=>	esc_html__( 'Embedding', 'streamtube-core' ),
						'icon'			=>	'icon-code',
						'template'		=>	streamtube_core_get_template( 'post/embedding.php' ),
						'priority'		=>	10
					);
				}		
							
				$items['subtitles'] 	= array(
					'title'			=>	esc_html__( 'Subtitles', 'streamtube-core' ),
					'icon'			=>	'icon-doc',
					'template'		=>	streamtube_core_get_template( 'post/subtitles.php' ),
					'priority'		=>	15
				);

				$items['altsources'] 	= array(
					'title'			=>	esc_html__( 'Alternative Sources', 'streamtube-core' ),
					'icon'			=>	'icon-server',
					'template'		=>	streamtube_core_get_template( 'post/altsources.php' ),
					'priority'		=>	16
				);
			}

			/**
			 * filter items
			 *
			 * @since 1.0.0
			 */
			$items = apply_filters( "streamtube_core_get_edit_post_{$post->post_type}_nav_items", $items, $post );			
		}

		return $items;	
	}

	/**
	 *
	 * Get current active menu item
	 * 
	 * @since 1.0.0
	 * 
	 */
	private function get_edit_post_active_menu(){

		global $wp_query;

		$menu_items = $this->get_edit_post_menu_items();

		if( get_option( 'permalink_structure' ) ){

			$request = explode( "/", $wp_query->query_vars['dashboard'] );

			if( count( $request ) == 2 ){
				$request = array_keys( $menu_items )[0];
			}

			elseif( count( $request ) == 3 ){
				$request = $request[2];
			}
		}
		else{
			$request = array_keys( $menu_items )[0];

			foreach ( $menu_items as $key => $value) {
				if( isset( $_GET[ $key ] ) ){
					$request = $key;
				}
			}
		}

		if( is_string( $request ) && ! array_key_exists( $request, $menu_items ) ){
			$request = array_keys( $menu_items )[0];
		}

		return $request;
	}

	/**
	 *
	 * The menu
	 * 
	 * @param  array  $args
	 *
	 * 
	 */
	public function the_edit_post_menu( $args = array() ){

		$menu_items = $this->get_edit_post_menu_items( $args['post'] );

		$menu = new Streamtube_Core_Menu( array_merge( $args, array(
			'menu_classes'	=>	'nav nav-tabs secondary-nav mb-4',
			'item_classes'	=>	'text-muted d-flex align-items-center small',
			'menu_items'	=>	$menu_items,
			'current'		=>	$this->get_edit_post_active_menu(),
			'icon'			=>	true
		) ) );

		return $menu->the_menu();
	}

	/**
	 *
	 * The Edit Post main template
	 * 
	 */
	public function the_edit_post_main(){

		$menu_items = $this->get_edit_post_menu_items();

		load_template( $menu_items[$this->get_edit_post_active_menu()]['template'] );
	}

	/**
	 *
	 * Load the edit thumbnail box
	 * 
	 * @since 1.0.0
	 * 
	 */
	public function load_thumbnail_metabox(){
		streamtube_core_load_template( 'post/edit/thumbnail.php', false );
	}

	/**
	 *
	 * Load the edit taxonimies box
	 * 
	 * @since 1.0.0
	 * 
	 */
	public function load_taxonomies_metabox(){
		streamtube_core_load_template( 'post/edit/taxonomies-hierarchical.php', false );
	}

	/**
	 *
	 * Load the taxonimies tags box
	 * 
	 * @since 1.0.0
	 * 
	 */
	public function load_taxonomies_tags_metabox(){
		streamtube_core_load_template( 'post/edit/taxonomies-tags.php', false );
	}	

	/**
	 *
	 * Load the edit post template
	 * 
	 * @since 1.0.0
	 * 
	 */
	public function load_edit_template(){

		if( ! current_user_can( 'edit_posts' ) ){
			return;
		}

		$GLOBALS['post_type_screen'] = 'post';

		$post_id  = $this->get_edit_post_id();

		if( $post_id || $this->is_add_new_post_screen() ){

			if( $post_id ){
				$post = get_post( $post_id );

				$GLOBALS['post_type_screen'] = $post->post_type;

				setup_postdata( $GLOBALS['post'] =& $post );
			}

			if( $this->is_add_new_post_screen() ){
				$GLOBALS['add_new_post_screen'] = true;
			}

			add_filter( 'sidebar_float', function( $show ){
				return false;
			} );			

			streamtube_core_load_template( 'post/edit.php', true );

			wp_reset_postdata();

			exit;
		}
	}

	/**
	 *
	 * Auto redirect to the edit post page if "edit_post" param found.
	 * 
	 * @since 1.0.0
	 * 
	 */
	public function redirect_to_edit_page(){
		global $wp_query;

		if( is_singular() && isset( $wp_query->query_vars['edit_post'] ) ){

			wp_redirect( get_edit_post_link( get_the_ID() ) );

			exit;
		}
	}

	public function load_video_schema(){
		if( is_singular( 'video' ) ){

			global $post;

			$excerpt = $post->post_excerpt ? $post->post_excerpt : $post->post_content;

			$data = array(
				'@context'		=>	'https://schema.org/',
				'@type'			=>	'VideoObject',
				'name'			=>	$post->post_title,
				'id'			=>	wp_get_shortlink( $post->ID ),
				'datePublished'	=>	get_the_date( 'Y-m-d H:i:s', $post->ID ),
				'uploadDate'	=>	get_the_date( 'Y-m-d H:i:s', $post->ID ),
				'author'		=>	array(
					'@type'		=>	'Person',
					'name'		=>	get_the_author_meta( 'display_name', $post->post_author )
				),
				'description'	=>	wp_trim_words( wp_kses( $excerpt, array() ), 50 ),
				'embedUrl'		=>	get_post_embed_url()
			);

			/**
			 * Add images
			 */
			if( has_post_thumbnail( $post ) ){

				$images = array();

				$sizes = get_intermediate_image_sizes();

				for ( $i=0; $i < count( $sizes ); $i++) { 
					$_image = get_the_post_thumbnail_url( $post, $sizes[$i] );

					if( ! empty( $_image ) ){
						$images[] = $_image;
					}
				}

				if( $images ){
					$data['thumbnailUrl'] = $images;
				}
			}

			/**
			 * Add contentUrl
			 */
			if( apply_filters( 'streamtube_video_schema_source', false ) === true ){
				$source = $this->get_source( $post->ID );

				if( wp_attachment_is( 'video', $source ) ){
					$data['contentUrl'] = wp_get_attachment_url( $source );
				}
			}

			if( 0 < $duration = $this->get_length( $post->ID ) ){
				$data['duration'] = streamtube_core_iso8601_duration( $duration );
			}

			printf(
				'<script type="application/ld+json">%s</script>',
				json_encode( $data )
			);
		}
	}

	/**
	 *
	 * Limit logged in user from accessing other user files
	 * 
	 * @param  array $query_args 
	 * @return array
	 *
	 * @since 1.0.8
	 * 
	 */
	public function filter_ajax_query_attachments_args( $query_args ){

		if( ! get_option( 'show_current_user_attachment', 'on' ) ){
			return $query_args;
		}

		if( ! current_user_can( 'administrator' ) ){
			$query_args['author'] = get_current_user_id();
		}

		return $query_args;
	}

	/**
	 *
	 * Add post meta data after post inserted into database
	 * @since 1.0.8
	 */
	public function wp_insert_post( $post_ID, $post, $update ){

		if( in_array( $post->post_type, array( 'post', 'video' ) ) ){

			$_metadata = array(
				'pageviews', 'uniquepageviews'
			);

			if( $post->post_type == self::CPT_VIDEO ){
				$_metadata = array_merge( $_metadata, array(
					'videoviews',
					'uniquevideoviews'
				) );
			}

			for ( $i=0; $i < count( $_metadata ); $i++) {
				if( (int)get_post_meta( $post_ID, '_' . $_metadata[$i], true ) == 0 ){
					update_post_meta( $post_ID, '_' . $_metadata[$i], 0 );
				}
			}
		}
	}

	/**
	 *
	 * update last seen post meta
	 * 
	 * @since 1.0.8
	 */
	public function update_last_seen(){
		if( is_singular() ){
			update_post_meta( get_the_ID(), '_last_seen', current_time( 'mysql', true ) );
		}
	}

	/**
	 *
	 * Delete all attached files after a video is deleted
	 *
	 * This action fires after a video post is deleted.
	 * 
	 * @since 1.0.8
	 */
	public function delete_attached_files( $postid, $post ){
		if( get_option( 'delete_attached_files', 'on' ) && in_array( $post->post_type, array( 'video', 'attachment' ) ) ){
			$child_posts = get_posts( array(
				'post_parent'		=>	$post->ID,
				'post_type'			=>	'attachment',
				'posts_per_page'	=>	-1
			) );

			if( $child_posts ){
				foreach( $child_posts as $child ){
					wp_delete_attachment( $child->ID, true );
				}
			}
		}
	}

	/**
	 *
	 * Hide video attachment page, move to its parent page if exists
	 * Otherwise load 404 error template
	 * 
	 * @since 1.0.9
	 */
	public function attachment_template_redirect( $template ){

		if( is_attachment() && wp_attachment_is( 'video', get_the_ID() ) ){

			if( get_option( 'hide_video_attachment_page', 'on' ) && ! is_embed() ){

				global $post;

				if( $post->post_parent ){
					wp_redirect( get_permalink( $post->post_parent ) );
					exit;
				}
				else{
					wp_redirect( home_url('/404') );
					exit;
				}
			}
		}

	}

	public function get_pending_posts_badge( $post_type = 'post' ){

		$badge = '';

		$query_args = array(
			'post_type'		=>	$post_type,
			'post_status'	=>	'pending'
		);

		if( ! Streamtube_Core_Permission::moderate_posts() ){
			$query_args['author'] = get_current_user_id();
		}

		$posts = get_posts( $query_args );

		if( ! $posts ){
			return;
		}

        $badge = sprintf(
            '<span class="badge bg-danger">%s</span>',
            number_format_i18n( count( $posts ) )
        );

        /**
         *
         * @since 1.1.5
         * 
         */
        return apply_filters( 'streamtube/core/posts_count_badge', $badge, $posts, $post_type );
	}

	/**
	 *
	 * Load Alt Source if Index found
	 * 
	 * @param  array $args
	 * @return array
	 * 
	 */
	public function filter_altsource( $args ){
		
		if( ! isset( $_GET['source_index'] ) || (int)$_GET['source_index'] == 0 ){
			return $args;
		}

		$source_index = (int)$_GET['source_index'];

		$source = $this->get_altsources( $args['post_id'], $source_index );

		if( is_array( $source ) && array_key_exists( 'source', $source ) ){
			$args['source'] = $source['source'];
		}

		return $args;
	}

	/**
	 *
	 * The sources navigator
	 * 
	 * @param  integer $post_id
	 * @return HTML
	 * 
	 */
	public function the_altsources_navigator(){
		load_template( trailingslashit( STREAMTUBE_CORE_PUBLIC ) . 'misc/altsources-navigator.php' );
	}

	/**
	 *
	 * The trailer button
	 * 
	 */
	public function the_trailer_button(){

		if( $this->get_video_trailer( get_the_ID() ) ){
			load_template( trailingslashit( STREAMTUBE_CORE_PUBLIC ) . 'video/button-trailer.php' );
		}
	}

	/**
	 *
	 * Add custom videojs skin classname
	 * 
	 * @param  array $setup
	 * @param  string $source
	 * 
	 */
	public function filter_player_setup_text_tracks( $setup, $source ){

		$default_subtitle 	= strtolower(get_option( 'player_default_subtitle' ));

		// Text Tracks
		$tracks 		= $this->get_text_tracks( $setup['mediaid'] );

		if( $tracks ){
			for ( $i = 0; $i < count( $tracks ); $i++) {

				$language = streamtube_core_get_language_by_code( strtolower( $tracks[$i]['language'] ) );

				$track = array(
					'kind'		=>	'captions',
					'srclang'	=>	$tracks[$i]['language'],
					'label'		=>	$language ? $language['name'] : '',
					'src'		=>	$tracks[$i]['source']
				);

				if( ($default_subtitle == 'first' && $i == 0) || ( $default_subtitle == strtolower( $tracks[$i]['language'] ) ) ){
					$track['default'] = true;
				}

				/**
				 * Filter the track
				 */
				$track = apply_filters( 'streamtube/core/post/player/track', $track, $setup, $source );

				$setup['tracks'][] = $track;
			}
		}

		return $setup;
	}

	/**
	 *
	 * Filter player output
	 * 
	 */
	public function filter_player_output( $output, $setup ){

		if( post_password_required( $setup['mediaid'] ) ){

			if( Streamtube_Core_Permission::moderate_posts() 
				|| Streamtube_Core_Permission::is_post_owner( $setup['mediaid'] ) ){
				add_filter( 'post_password_required', '__return_false', 10, 1 );

				return $output;
			}

			$output = sprintf(
				'<div class="video-not-found require-membership password-protected"><div class="position-absolute top-50 start-50 translate-middle text-white">%s</div></div>',
				get_the_password_form( $setup['mediaid'] )
			);

			$output .= sprintf(
				'<div class="player-poster bg-cover" style="background-image:url(%s)"></div>',
				$setup['poster2'] ? $setup['poster2'] : $setup['poster']
			);			

			if( apply_filters( 'streamtube/content_password_protected', false ) === false ){
				add_filter( 'post_password_required', '__return_false', 10, 1 );
			}			
		}

		return $output;
	}

	public function display_embed_privacy_notice( $player, $setup ){

		// This action works on Embed only.
		if( ! is_embed() || ! is_singular( self::CPT_VIDEO ) || ! get_post_status( $setup['mediaid'] ) ){
			return $player;
		}

		$errors 		= new WP_Error();

		$referer 		= isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : false;

		if( ! $referer || strpos( $referer,  home_url('/') ) !== false ){
			// Always return player if no referer or referer is the current website.
			return $player;
		}

		$referer_data	= parse_url( $referer );

		// Host was not found
		if( ! isset( $referer_data['host'] ) ){
			return $player;
		}

		$embed_privacy 	= $this->get_embed_privacy( $setup['mediaid'] );

		if( $embed_privacy['embed_privacy'] == 'anywhere' ){
			// Check the blocked domains.
			if( is_array( $embed_privacy['blocked_domains'] ) && in_array( $referer_data['host'], $embed_privacy['blocked_domains'] ) ){
				$errors->add(
					'blocked_domain',
					esc_html__( 'Blocked Domain', 'streamtube-core' )
				);
			}
		}

		if( $embed_privacy['embed_privacy'] == 'nowhere' ){
			// Check the allowed domains.
			if( is_array( $embed_privacy['allowed_domains'] ) && ! in_array( $referer_data['host'], $embed_privacy['allowed_domains'] ) ){
				$errors->add(
					'blocked_domain',
					esc_html__( 'Blocked Domain', 'streamtube-core' )
				);
			}
		}

		if( $errors->has_errors() ){

			$errors->remove( 'blocked_domain' );

            $message = esc_html__( 'Playback has been disabled by the video owner', 'streamtube-core' );

            $message .=  '<br/>' . sprintf(
                '<a href="%s">%s</a>',
                esc_url( add_query_arg(
                	array(
                		'from'		=>	'embed_privacy',
                		'referer'	=>	urlencode( $referer )
                	),
                	wp_get_shortlink( $setup['mediaid'] )
                ) ),
                sprintf(
                    esc_html__( 'Watch on %s', 'streamtube-core' ),
                    get_bloginfo( 'name' )
                )
            );

            $errors->add(
                'embed_privacy_not_allowed',
                $message
            );			
            /**
             *
             * Filter $errors
             * 
             * @var WP_Error
             */
            $errors = apply_filters( 'streamtube/core/embed_privacy/errors', $errors, $player, $setup );

            $RestrictContent = new Streamtube_Core_Restrict_Content();

            $player = $RestrictContent->get_notice_message( $errors , $setup );
		}

		return $player;
	}

	/**
	 *
	 * The Upcoming notice
	 * 
	 */
	public function display_upcoming_notice( $output, $setup ){

        if( 
            Streamtube_Core_Permission::moderate_posts() 
            || Streamtube_Core_Permission::is_post_owner( $setup['mediaid'] )
            || $setup['trailer'] ){
			return $output;
		}

		$upcoming = $this->is_post_upcoming( $setup['mediaid'] );

		if( ! $upcoming ){
			return $output;
		}

		wp_enqueue_script( 'jquery.countdown' );
		wp_enqueue_script( 'countdown.upcoming' );

		ob_start();

		$options = array(
			'time'	=>	$upcoming->upcoming->format('Y-m-d H:i:s'),
			'day'	=>	array(
				esc_html__( 'day', 'streamtube-core' ),
				esc_html__( 'days', 'streamtube-core' )
			),
			'hour'	=>	array(
				esc_html__( 'hour', 'streamtube-core' ),
				esc_html__( 'hours', 'streamtube-core' )
			),
			'minute'	=>	array(
				esc_html__( 'minute', 'streamtube-core' ),
				esc_html__( 'minutes', 'streamtube-core' )
			),
			'seconds'	=>	array(
				esc_html__( 'seconds', 'streamtube-core' ),
				esc_html__( 'seconds', 'streamtube-core' )
			),
			'button'	=>	esc_html__( 'Watch now', 'streamtube-core' ),
			'url'		=>	wp_get_shortlink( $setup['mediaid'] )
		);

		/**
		 *
		 * Filter the options
		 * 
		 * @var array
		 */
		$options = apply_filters( 'streamtube/core/player/upcoming/options', $options, $upcoming, $output, $setup );

		?>

		<div class="no-permission error-message upcoming-wrapper">
			<div class="position-absolute top-50 start-50 translate-middle center-x center-y">

				<?php
				/**
				 *
				 * Fire before countdown
				 *
				 * @param object $upcoming
				 * 
				 */
				do_action( 'streamtube/core/player/upcoming/countdown/before', $upcoming, $output, $setup );
				?>				

				<?php printf(
					'<div class="countdown upcoming d-flex gap-3" id="countdown-%s" data-options="%s"></div>',
					esc_attr( $setup['mediaid'] ),
					esc_attr( json_encode( $options ) )
				);?>

				<?php
				/**
				 *
				 * Fire after countdown
				 *
				 * @param object $upcoming
				 * 
				 */
				do_action( 'streamtube/core/player/upcoming/countdown/after', $upcoming, $output, $setup );
				?>

			</div>
		</div>

		<?php 
		printf(
			'<div class="player-poster bg-cover" style="background-image:url(%s)"></div>',
			$setup['poster2'] ? $setup['poster2'] : $setup['poster']
		);

		return ob_get_clean();
	}

	/**
	 * Display the upcoming heading
	 */
	public function display_upcoming_notice_heading( $upcoming, $output, $setup ){

		$heading = esc_html__( 'Upcoming', 'streamtube-core' );

		/**
		 *
		 * Filter the heading
		 * 
		 */
		$heading = apply_filters( 'streamtube/core/player/upcoming/heading', $heading, $upcoming, $output, $setup );

		if( $heading && is_string( $heading ) ){
			printf(
				'<h3>%s</h3>',
				esc_html__( 'Upcoming', 'streamtube-core' )
			);
		}
	}

	/**
	 *
	 * The Edit Post form
	 * 
	 * @param  WP_Post $post
	 * @return HTML
	 */
	public function the_edit_post_form( $post ){
		ob_start();

		$GLOBALS['post_type_screen'] = $post->post_type;

		setup_postdata( $GLOBALS['post'] =& $post );

		?>
            <div class="row">
                <div class="col-12 col-xl-8">
                    <?php streamtube_core_load_template( 'post/edit/details/main.php', false ); ?>
        		</div>
                <div class="col-12 col-xl-4">
                    <?php streamtube_core_load_template( 'post/edit/metaboxes.php', false ); ?>
                </div><!--.col-3-->
    		</div>
		<?php

		wp_reset_postdata();

		return ob_get_clean();
	}

    /**
     * Add custom fields to the Video table
     *
     * @param array $columns
     */
    public function filter_post_table( $columns ){
        return array_merge( $columns, array(
			'mediaid'	=>	esc_html__( 'Media ID', 'streamtube-core' ),
			'thumbnail'	=>	esc_html__( 'Thumbnail', 'streamtube-core' ),        	
            'last_seen' =>  esc_html__( 'Last Seen', 'streamtube-core' )
        ) );
    }

    /**
     *
     * Custom Columns callback
     * 
     * @param  string $column
     * @param  int $post_id
     * 
     */
    public function filter_post_table_columns( $column, $post_id ){

    	$source = $this->get_source( $post_id );

		switch ( $column ) {

			case 'mediaid':
				if( wp_attachment_is( 'video', $source ) ){
					printf(
						'<a target="_blank" href="%s">%s</a>',
						esc_url( admin_url( 'post.php?post='.$source.'&action=edit' ) ),
						get_the_title( $source )
					);
				}else{
					esc_html_e( 'Embedded', 'streamtube-core' );
				}
			break;

			case 'thumbnail':
				if( has_post_thumbnail( $post_id ) ){
					printf(
						'<div class="ratio ratio-16x9"><a target="_blank" href="%s">%s</a></div>',
						esc_url( get_permalink( $post_id ) ),
						get_the_post_thumbnail( $post_id, 'thumbnail' )
					);
				}
			break;

			case 'last_seen':
				$last_seen = $this->get_last_seen( $post_id, true );

				if( $last_seen > 0 ){
					printf(
						esc_html__( '%s ago', 'streamtube-core' ),
						human_time_diff( 
							$last_seen, 
							current_time( 'timestamp' )
						)
					);
				}  
			break;

		}

    } 	

}