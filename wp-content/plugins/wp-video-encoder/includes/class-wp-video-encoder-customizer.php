<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define the custom table functionality.
 *
 * @since      1.0.0
 * @package    WP_Video_Encoder
 * @subpackage WP_Video_Encoder/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */
class WP_Video_Encoder_Customizer {

	const PANEL_ID = 'wp_video_encoder';

	/**
	 *
	 * Show admin notics
	 * 
	 * @since 1.0.5
	 */
	public static function notice(){

		$errors = new WP_Error();

		if( ! function_exists( 'exec' ) ){
			$errors->add(
				'exec_disabled',
				sprintf(
					'<p>'.esc_html__( '%s function is disabled', 'wp-video-encoder' ).'</p>',
					'<strong>EXEC</strong>'
				)				
			);
		}

		if( ! $errors->get_error_codes() ){
			return;
		}

		return sprintf(
			'<div class="bg-danger" style="padding: .5rem">%s</div></div>',
			join( '<br/>', $errors->get_error_messages() )
		);
	}

	public static function register( $customizer ){

		$customizer->add_panel( self::PANEL_ID, array(
			'title' 	=>	esc_html__( 'WP Video Encoder', 'wp-video-encoder' ),
			'priority'	=>	100
		) );

			$customizer->add_section( self::PANEL_ID . '_general', array(
				'title'				=>	esc_html__( 'General', 'wp-video-encoder' ),
				'priority'			=>	10,
				'panel'				=>	self::PANEL_ID,
				'description'		=>	self::notice()
			) );

				$customizer->add_setting( 'wp_video_encoder[encoder_version]', array(
					'default'			=>	'v2',
					'type'				=>	'option',
					'capability'		=>	'edit_theme_options',
					'sanitize_callback'	=>	'sanitize_text_field'
				) );

				$customizer->add_control( 'wp_video_encoder[encoder_version]', array(
					'label'				=>	esc_html__( 'Encoder Version', 'wp-video-encoder' ),
					'type'				=>	'select',
					'section'			=>	self::PANEL_ID . '_general',
					'choices'			=>	array(
						'v1'	=>	esc_html__( 'Version 1 - Legacy', 'wp-video-encoder' ),
						'v2'	=>	esc_html__( 'Version 2 - Recommended', 'wp-video-encoder' )
					)
				) );			

				$customizer->add_setting( 'wp_video_encoder[bin_path]', array(
					'default'			=>	'/usr/bin/',
					'type'				=>	'option',
					'capability'		=>	'edit_theme_options',
					'sanitize_callback'	=>	'sanitize_text_field'
				) );

				$customizer->add_control( 'wp_video_encoder[bin_path]', array(
					'label'				=>	esc_html__( 'FFmpeg bin Path', 'wp-video-encoder' ),
					'type'				=>	'text',
					'section'			=>	self::PANEL_ID . '_general'
				) );

				$customizer->add_setting( 'wp_video_encoder[auto_generate_image]', array(
					'default'			=>	'1',
					'type'				=>	'option',
					'capability'		=>	'edit_theme_options',
					'sanitize_callback'	=>	'sanitize_text_field'
				) );

				$customizer->add_control( 'wp_video_encoder[auto_generate_image]', array(
					'label'				=>	esc_html__( 'Auto Generate Thumbnail Image', 'wp-video-encoder' ),
					'description'		=>	esc_html__( 'Auto generate video image after uploading.', 'wp-video-encoder' ),
					'type'				=>	'checkbox',
					'section'			=>	self::PANEL_ID . '_general'
				) );

				$customizer->add_setting( 'wp_video_encoder[auto_generate_webp_image]', array(
					'default'			=>	'',
					'type'				=>	'option',
					'capability'		=>	'edit_theme_options',
					'sanitize_callback'	=>	'sanitize_text_field'				
				) );

				$customizer->add_control( 'wp_video_encoder[auto_generate_webp_image]', array(
					'label'				=>	esc_html__( 'Auto Generate Animated Image', 'wp-video-encoder' ),
					'description'		=>	esc_html__( 'Auto generate video animated (webp) image after uploading.', 'wp-video-encoder' ),
					'type'				=>	'checkbox',
					'section'			=>	self::PANEL_ID . '_general'
				) );

				$customizer->add_setting( 'wp_video_encoder[auto_encode]', array(
					'default'			=>	'1',
					'type'				=>	'option',
					'capability'		=>	'edit_theme_options',
					'sanitize_callback'	=>	'sanitize_text_field'				
				) );

				$customizer->add_control( 'wp_video_encoder[auto_encode]', array(
					'label'				=>	esc_html__( 'Auto Encode', 'wp-video-encoder' ),
					'description'		=>	esc_html__( 'Auto encode file after uploading.', 'wp-video-encoder' ),
					'type'				=>	'checkbox',
					'section'			=>	self::PANEL_ID . '_general'
				) );	

				$customizer->add_setting( 'wp_video_encoder[nice]', array(
					'default'			=>	'',
					'type'				=>	'option',
					'capability'		=>	'edit_theme_options',
					'sanitize_callback'	=>	'sanitize_text_field'
				) );

				$customizer->add_control( 'wp_video_encoder[nice]', array(
					'label'				=>	esc_html__( 'Nice', 'wp-video-encoder' ),
					'description'		=>	esc_html__( 'Run at a lower priority to prevent crashing system resources.', 'wp-video-encoder' ),
					'type'				=>	'checkbox',
					'section'			=>	self::PANEL_ID . '_general'
				) );

				$customizer->add_setting( 'wp_video_encoder[nice_path]', array(
					'default'			=>	'/usr/bin',
					'type'				=>	'option',
					'capability'		=>	'edit_theme_options',
					'sanitize_callback'	=>	'sanitize_text_field'
				) );

				$customizer->add_control( 'wp_video_encoder[nice_path]', array(
					'label'				=>	esc_html__( 'Nice Bin Path', 'wp-video-encoder' ),
					'type'				=>	'text',
					'section'			=>	self::PANEL_ID . '_general',
					'active_callback'	=>	function(){
						$settings = WP_Video_Encoder_Settings::get_settings();
						return ! empty( $settings['nice'] ) ? true : false;
					}
				) );

				$customizer->add_setting( 'wp_video_encoder[nice_priority]', array(
					'default'			=>	10,
					'type'				=>	'option',
					'capability'		=>	'edit_theme_options',
					'sanitize_callback'	=>	'sanitize_text_field'
				) );

				$customizer->add_control( 'wp_video_encoder[nice_priority]', array(
					'label'				=>	esc_html__( 'Nice Priority Value', 'wp-video-encoder' ),
					'type'				=>	'number',
					'section'			=>	self::PANEL_ID . '_general',
					'active_callback'	=>	function(){
						$settings = WP_Video_Encoder_Settings::get_settings();
						return ! empty( $settings['nice'] ) ? true : false;
					}
				) );			

				$customizer->add_setting( 'wp_video_encoder[max_threads]', array(
					'default'			=>	2,
					'type'				=>	'option',
					'capability'		=>	'edit_theme_options',
					'sanitize_callback'	=>	'sanitize_text_field'				
				) );

				$customizer->add_control( 'wp_video_encoder[max_threads]', array(
					'label'				=>	esc_html__( 'Encode Threads', 'wp-video-encoder' ),
					'description'		=>	esc_html__( 'Maximum FFmpeg encode threads', 'wp-video-encoder' ),
					'type'				=>	'number',
					'section'			=>	self::PANEL_ID . '_general'
				) );

				$customizer->add_setting( 'wp_video_encoder[allow_formats]', array(
					'default'			=>	implode( ",", wp_get_video_extensions() ),
					'type'				=>	'option',
					'capability'		=>	'edit_theme_options',
					'sanitize_callback'	=>	'sanitize_text_field'				
				) );

				$customizer->add_control( 'wp_video_encoder[allow_formats]', array(
					'label'				=>	esc_html__( 'Allow Formats', 'wp-video-encoder' ),
					'type'				=>	'text',
					'section'			=>	self::PANEL_ID . '_general'
				) );

				$customizer->add_setting( 'wp_video_encoder[vcodec]', array(
					'default'			=>	'h264',
					'type'				=>	'option',
					'capability'		=>	'edit_theme_options',
					'sanitize_callback'	=>	'sanitize_text_field'				
				) );

				$customizer->add_control( 'wp_video_encoder[vcodec]', array(
					'label'				=>	esc_html__( 'Video Codec', 'wp-video-encoder' ),
					'type'				=>	'select',
					'section'			=>	self::PANEL_ID . '_general',
					'choices'			=>	array(
						'h264'			=>	esc_html__( 'H.264', 'wp-video-encoder' )
					)
				) );

				$customizer->add_setting( 'wp_video_encoder[h264_profile]', array(
					'default'			=>	'baseline',
					'type'				=>	'option',
					'capability'		=>	'edit_theme_options',
					'sanitize_callback'	=>	'sanitize_text_field'				
				) );

				$customizer->add_control( 'wp_video_encoder[h264_profile]', array(
					'label'				=>	esc_html__( 'H.264 Profile', 'wp-video-encoder' ),
					'type'				=>	'select',
					'section'			=>	self::PANEL_ID . '_general',
					'choices'			=>	array(
						''			=>	esc_html__( 'None', 'wp-video-encoder' ),
						'baseline'	=>	esc_html__( 'Baseline', 'wp-video-encoder' ),
						'main'		=>	esc_html__( 'Main', 'wp-video-encoder' ),
						'high'		=>	esc_html__( 'High', 'wp-video-encoder' )
					),
					'active_callback'	=>	function(){
						$settings = WP_Video_Encoder_Settings::get_settings();
						return $settings['vcodec'] == 'h264' ? true : false;
					}
				) );

				$customizer->add_setting( 'wp_video_encoder[moov]', array(
					'default'			=>	'',
					'type'				=>	'option',
					'capability'		=>	'edit_theme_options',
					'sanitize_callback'	=>	'sanitize_text_field'				
				) );

				$customizer->add_control( 'wp_video_encoder[moov]', array(
					'label'				=>	esc_html__( 'Method to fix encoded H.264 headers for streaming', 'wp-video-encoder' ),
					'type'				=>	'select',
					'section'			=>	self::PANEL_ID . '_general',
					'choices'			=>	array(
						''				=>	esc_html__( 'none', 'wp-video-encoder' ),
						'movflag'		=>	esc_html__( 'movflags faststart', 'wp-video-encoder' )
					),
					'active_callback'	=>	function(){
						$settings = WP_Video_Encoder_Settings::get_settings();
						return $settings['vcodec'] == 'h264' ? true : false;
					}
				) );

				$customizer->add_setting( 'wp_video_encoder[rate_control]', array(
					'default'			=>	'crf',
					'type'				=>	'option',
					'capability'		=>	'edit_theme_options',
					'sanitize_callback'	=>	'sanitize_text_field'				
				) );			

				$customizer->add_control( 'wp_video_encoder[rate_control]', array(
					'label'				=>	esc_html__( 'Encode quality control method', 'wp-video-encoder' ),
					'type'				=>	'select',
					'section'			=>	self::PANEL_ID . '_general',
					'choices'			=>	array(
						'crf'		=>	esc_html__( 'Constant Rate Factor (CRF)', 'wp-video-encoder' )
					)
				) );

				$customizer->add_setting( 'wp_video_encoder[h264_crf]', array(
					'default'			=>	23,
					'type'				=>	'option',
					'capability'		=>	'edit_theme_options',
					'sanitize_callback'	=>	'sanitize_text_field'				
				) );			

				$customizer->add_control( 'wp_video_encoder[h264_crf]', array(
					'label'				=>	esc_html__( 'H.264 Constant Rate Factors (CRF)', 'wp-video-encoder' ),
					'type'				=>	'number',
					'section'			=>	self::PANEL_ID . '_general',
					'active_callback'	=>	function(){
						$settings = WP_Video_Encoder_Settings::get_settings();
						return $settings['rate_control'] == 'crf' && $settings['vcodec'] == 'h264' ? true : false;					
					}
				) );		

				$customizer->add_setting( 'wp_video_encoder[hls_playlist_type]', array(
					'default'			=>	'vod',
					'type'				=>	'option',
					'capability'		=>	'edit_theme_options',
					'sanitize_callback'	=>	'sanitize_text_field'				
				) );			

				$customizer->add_control( 'wp_video_encoder[hls_playlist_type]', array(
					'label'				=>	esc_html__( 'HLS PlayList Type', 'wp-video-encoder' ),
					'type'				=>	'select',
					'section'			=>	self::PANEL_ID . '_general',
					'choices'			=>	array(
						''		=>	esc_html__( 'Default', 'wp-video-encoder' ),
						'vod'	=>	esc_html__( 'Vod', 'wp-video-encoder' ),
						'event'	=>	esc_html__( 'Event', 'wp-video-encoder' ),
						'live'	=>	esc_html__( 'Live', 'wp-video-encoder' )
					)
				) );

				$customizer->add_setting( 'wp_video_encoder[hls_segment_type]', array(
					'default'			=>	'mpegts',
					'type'				=>	'option',
					'capability'		=>	'edit_theme_options',
					'sanitize_callback'	=>	'sanitize_text_field'				
				) );			

				$customizer->add_control( 'wp_video_encoder[hls_segment_type]', array(
					'label'				=>	esc_html__( 'HLS Segment Type', 'wp-video-encoder' ),
					'type'				=>	'select',
					'section'			=>	self::PANEL_ID . '_general',
					'choices'			=>	array(
						''		=>	esc_html__( 'Default', 'wp-video-encoder' ),
						'mpegts'=>	esc_html__( 'MPEGTS', 'wp-video-encoder' ),
						'fmp4'	=>	esc_html__( 'FMP4', 'wp-video-encoder' )
					)
				) );

				$customizer->add_setting( 'wp_video_encoder[hls_flags]', array(
					'default'			=>	'',
					'type'				=>	'option',
					'capability'		=>	'edit_theme_options',
					'sanitize_callback'	=>	'sanitize_text_field'				
				) );			

				$customizer->add_control( 'wp_video_encoder[hls_flags]', array(
					'label'				=>	esc_html__( 'HLS Flags', 'wp-video-encoder' ),
					'type'				=>	'select',
					'section'			=>	self::PANEL_ID . '_general',
					'choices'			=>	array(
						''								=>	esc_html__( 'None', 'wp-video-encoder' ),
						'independent_segments'			=>	'independent_segments',
						'second_level_segment_index'	=>	'second_level_segment_index',
						'second_level_segment_size'		=>	'second_level_segment_size',
						'second_level_segment_duration'	=>	'second_level_segment_duration'
					)
				) );

				$customizer->add_setting( 'wp_video_encoder[hls_segment_folder_name]', array(
					'default'			=>	'resolution',
					'type'				=>	'option',
					'capability'		=>	'edit_theme_options',
					'sanitize_callback'	=>	'sanitize_text_field'				
				) );				

				$customizer->add_control( 'wp_video_encoder[hls_segment_folder_name]', array(
					'label'				=>	esc_html__( 'HLS Segment Folder Name', 'wp-video-encoder' ),
					'type'				=>	'select',
					'section'			=>	self::PANEL_ID . '_general',
					'choices'			=>	array(
						'resolution'	=>	esc_html__( 'Resolution', 'wp-video-encoder' ),
						'index'			=>	esc_html__( 'Index', 'wp-video-encoder' )
					),
					'description'		=>	esc_html__( 'Structure of segment folder name', 'wp-video-encoder' )
				) );				

				$customizer->add_setting( 'wp_video_encoder[strict_2]', array(
					'default'			=>	'on',
					'type'				=>	'option',
					'capability'		=>	'edit_theme_options',
					'sanitize_callback'	=>	'sanitize_text_field'				
				) );			

				$customizer->add_control( 'wp_video_encoder[strict_2]', array(
					'label'				=>	esc_html__( 'Strict 2', 'wp-video-encoder' ),
					'type'				=>	'checkbox',
					'section'			=>	self::PANEL_ID . '_general',
					'description'		=>	esc_html__( 'Add -strict -2 parameter', 'wp-video-encoder' )
				) );

				$customizer->add_setting( 'wp_video_encoder[extra_params]', array(
					'default'			=>	'',
					'type'				=>	'option',
					'capability'		=>	'edit_theme_options',
					'sanitize_callback'	=>	'sanitize_text_field'				
				) );			

				$customizer->add_control( 'wp_video_encoder[extra_params]', array(
					'label'				=>	esc_html__( 'Extra Parameters', 'wp-video-encoder' ),
					'type'				=>	'text',
					'section'			=>	self::PANEL_ID . '_general',
					'description'		=>	esc_html__( 'Add your extra parameters', 'wp-video-encoder' )			
				) );			

				$customizer->add_setting( 'wp_video_encoder[bitrate_multiplier]', array(
					'default'			=>	0.1,
					'type'				=>	'option',
					'capability'		=>	'edit_theme_options',
					'sanitize_callback'	=>	'sanitize_text_field'				
				) );					

				$customizer->add_setting( 'wp_video_encoder[publish_parent]', array(
					'default'			=>	'1',
					'type'				=>	'option',
					'capability'		=>	'edit_theme_options',
					'sanitize_callback'	=>	'sanitize_text_field'				
				) );

				$customizer->add_control( 'wp_video_encoder[publish_parent]', array(
					'label'				=>	esc_html__( 'Auto publish parent post', 'wp-video-encoder' ),
					'description'		=>	esc_html__( 'Auto publish post after encode done.', 'wp-video-encoder' ),
					'type'				=>	'checkbox',
					'section'			=>	self::PANEL_ID . '_general'
				) );

				$customizer->add_setting( 'wp_video_encoder[notification]', array(
					'default'			=>	'1',
					'type'				=>	'option',
					'capability'		=>	'edit_theme_options',
					'sanitize_callback'	=>	'sanitize_text_field'				
				) );

				$customizer->add_control( 'wp_video_encoder[notification]', array(
					'label'				=>	esc_html__( 'Notification', 'wp-video-encoder' ),
					'description'		=>	esc_html__( 'Auto send notification to the author after encode done.', 'wp-video-encoder' ),
					'type'				=>	'checkbox',
					'section'			=>	self::PANEL_ID . '_general'
				) );

				$customizer->add_setting( 'wp_video_encoder[enable_admin_ajax]', array(
					'default'			=>	'',
					'type'				=>	'option',
					'capability'		=>	'edit_theme_options',
					'sanitize_callback'	=>	'sanitize_text_field'				
				) );

				$customizer->add_control( 'wp_video_encoder[enable_admin_ajax]', array(
					'label'				=>	esc_html__( 'Admin Ajax', 'wp-video-encoder' ),
					'description'		=>	esc_html__( 'Enable admin ajax request instead of Rest.', 'wp-video-encoder' ),
					'type'				=>	'checkbox',
					'section'			=>	self::PANEL_ID . '_general'
				) );

			$customizer->add_section( self::PANEL_ID . '_animated_image', array(
				'title'				=>	esc_html__( 'Animated Image', 'wp-video-encoder' ),
				'priority'			=>	10,
				'panel'				=>	self::PANEL_ID
			) );


				$customizer->add_setting( 'wp_video_encoder[webp_start_at]', array(
					'default'			=>	'00:00:01',
					'type'				=>	'option',
					'capability'		=>	'edit_theme_options',
					'sanitize_callback'	=>	'sanitize_text_field'
				) );

				$customizer->add_control( 'wp_video_encoder[webp_start_at]', array(
					'label'				=>	esc_html__( 'Start At', 'wp-video-encoder' ),
					'type'				=>	'text',
					'section'			=>	self::PANEL_ID . '_animated_image'
				) );

				$customizer->add_setting( 'wp_video_encoder[webp_setpts]', array(
					'default'			=>	'0.4',
					'type'				=>	'option',
					'capability'		=>	'edit_theme_options',
					'sanitize_callback'	=>	'sanitize_text_field'
				) );

				$customizer->add_control( 'wp_video_encoder[webp_setpts]', array(
					'label'				=>	esc_html__( 'Setpts', 'wp-video-encoder' ),
					'type'				=>	'text',
					'section'			=>	self::PANEL_ID . '_animated_image'
				) );

				$customizer->add_setting( 'wp_video_encoder[webp_fixed_time]', array(
					'default'			=>	5,
					'type'				=>	'option',
					'capability'		=>	'edit_theme_options',
					'sanitize_callback'	=>	'sanitize_text_field'
				) );

				$customizer->add_control( 'wp_video_encoder[webp_fixed_time]', array(
					'label'				=>	esc_html__( 'Length', 'wp-video-encoder' ),
					'type'				=>	'number',
					'section'			=>	self::PANEL_ID . '_animated_image',
					'description'		=>	esc_html__( 'Length of animated image, default is 5 seconds', 'wp-video-encoder' )
				) );				

				$customizer->add_setting( 'wp_video_encoder[webp_resolution]', array(
					'default'			=>	'640x360',
					'type'				=>	'option',
					'capability'		=>	'edit_theme_options',
					'sanitize_callback'	=>	'sanitize_text_field'
				) );

				$customizer->add_control( 'wp_video_encoder[webp_resolution]', array(
					'label'				=>	esc_html__( 'Resolution', 'wp-video-encoder' ),
					'type'				=>	'text',
					'section'			=>	self::PANEL_ID . '_animated_image'
				) );				

				$customizer->add_setting( 'wp_video_encoder[image_file_type]', array(
					'default'			=>	'webp',
					'type'				=>	'option',
					'capability'		=>	'edit_theme_options',
					'sanitize_callback'	=>	'sanitize_text_field'
				) );

				$customizer->add_control( 'wp_video_encoder[image_file_type]', array(
					'label'				=>	esc_html__( 'Image File Type', 'wp-video-encoder' ),
					'type'				=>	'text',
					'section'			=>	self::PANEL_ID . '_animated_image',
					'description'		=>	esc_html__( 'Image File Type: webp or gif', 'wp-video-encoder' )
				) );


			$customizer->add_section( self::PANEL_ID . '_watermark', array(
				'title'				=>	esc_html__( 'Watermark', 'wp-video-encoder' ),
				'priority'			=>	10,
				'panel'				=>	self::PANEL_ID
			) );

				$customizer->add_setting( 'wp_video_encoder[watermark]', array(
					'default'			=>	'',
					'type'				=>	'option',
					'capability'		=>	'edit_theme_options',
					'sanitize_callback'	=>	'sanitize_text_field',
				) );

				$customizer->add_control(
					new WP_Customize_Image_Control(
						$customizer,
						'wp_video_encoder[watermark]',
						array(
							'label'      	=> esc_html__( 'Watermark', 'wp-video-encoder' ),
							'description'	=>	esc_html__( 'Upload your own watermark', 'wp-video-encoder' ),
							'section'   	 => self::PANEL_ID . '_watermark'
						)
					)
				);

				$customizer->add_setting( 'wp_video_encoder[watermark_position]', array(
					'default'			=>	'top_right',
					'type'				=>	'option',
					'capability'		=>	'edit_theme_options',
					'sanitize_callback'	=>	'sanitize_text_field'				
				) );

				$customizer->add_control( 'wp_video_encoder[watermark_position]', array(
					'label'				=>	esc_html__( 'Position', 'wp-video-encoder' ),
					'type'				=>	'select',
					'choices'			=>	array(
						'top_right'		=>	esc_html__( 'Top Right', 'wp-video-encoder' ),
						'top_left'		=>	esc_html__( 'Top Left', 'wp-video-encoder' ),
						'bottom_right'	=>	esc_html__( 'Bottom Right', 'wp-video-encoder' ),
						'bottom_left'	=>	esc_html__( 'Bottom Left', 'wp-video-encoder' ),
						'center'		=>	esc_html__( 'Center', 'wp-video-encoder' )
					),
					'section'			=>	self::PANEL_ID . '_watermark'
				) );

				$customizer->add_setting( 'wp_video_encoder[watermark_size]', array(
					'default'			=>	'fixed',
					'type'				=>	'option',
					'capability'		=>	'edit_theme_options',
					'sanitize_callback'	=>	'sanitize_text_field'				
				) );

				$customizer->add_control( 'wp_video_encoder[watermark_size]', array(
					'label'				=>	esc_html__( 'Size', 'wp-video-encoder' ),
					'type'				=>	'select',
					'choices'			=>	array(
						'fixed'			=>	esc_html__( 'Fixed', 'wp-video-encoder' ),
						'percentage_w'	=>	esc_html__( 'Percentage Of Video Width', 'wp-video-encoder' ),
						'percentage_h'	=>	esc_html__( 'Percentage Of Video Height', 'wp-video-encoder' )
					),
					'section'			=>	self::PANEL_ID . '_watermark'
				) );				

				$customizer->add_setting( 'wp_video_encoder[watermark_size_percentage]', array(
					'default'			=>	'0.3',
					'type'				=>	'option',
					'capability'		=>	'edit_theme_options',
					'sanitize_callback'	=>	'sanitize_text_field'				
				) );

				$customizer->add_control( 'wp_video_encoder[watermark_size_percentage]', array(
					'label'				=>	esc_html__( 'Percentage', 'wp-video-encoder' ),
					'type'				=>	'text',
					'description'		=>	esc_html__( 'E.g: 0.3 or 0.5, 1 is equal to Video Width', 'wp-video-encoder' ),
					'section'			=>	self::PANEL_ID . '_watermark',
					'active_callback'	=>	function(){
						$settings = WP_Video_Encoder_Settings::get_settings();

						return $settings['watermark_size'] != 'fixed' ? true : false;
					}
				) );

				$customizer->add_setting( 'wp_video_encoder[watermark_padding]', array(
					'default'			=>	'20:20',
					'type'				=>	'option',
					'capability'		=>	'edit_theme_options',
					'sanitize_callback'	=>	'sanitize_text_field'				
				) );				

				$customizer->add_control( 'wp_video_encoder[watermark_padding]', array(
					'label'				=>	esc_html__( 'Padding', 'wp-video-encoder' ),
					'type'				=>	'text',
					'section'			=>	self::PANEL_ID . '_watermark'
				) );

				$customizer->add_setting( 'wp_video_encoder[watermark_opacity]', array(
					'default'			=>	'1',
					'type'				=>	'option',
					'capability'		=>	'edit_theme_options',
					'sanitize_callback'	=>	'sanitize_text_field'				
				) );

				$customizer->add_control( 'wp_video_encoder[watermark_opacity]', array(
					'label'				=>	esc_html__( 'Opacity', 'wp-video-encoder' ),
					'type'				=>	'text',
					'description'		=>	esc_html__( 'E.g: 0.5 or 0.8, 1 is no opacity.', 'wp-video-encoder' ),
					'section'			=>	self::PANEL_ID . '_watermark'
				) );				

			$customizer->add_section( self::PANEL_ID . '_encrypt', array(
				'title'				=>	esc_html__( 'Encrypt', 'wp-video-encoder' ),
				'priority'			=>	10,
				'panel'				=>	self::PANEL_ID,
				'description'		=>	self::notice()
			) );

				$customizer->add_setting( 'wp_video_encoder[hls_encrypt]', array(
					'default'			=>	'',
					'type'				=>	'option',
					'capability'		=>	'edit_theme_options',
					'sanitize_callback'	=>	'sanitize_text_field'				
				) );			

				$customizer->add_control( 'wp_video_encoder[hls_encrypt]', array(
					'label'				=>	esc_html__( 'Enable', 'wp-video-encoder' ),
					'type'				=>	'checkbox',
					'section'			=>	self::PANEL_ID . '_encrypt'
				) );

				$customizer->add_setting( 'wp_video_encoder[hls_encrypt_endpoint]', array(
					'default'			=>	WP_Video_Encoder_Encryption::ENCRYPT_FILE_INFO,
					'type'				=>	'option',
					'capability'		=>	'edit_theme_options',
					'sanitize_callback'	=>	'sanitize_text_field'
				) );			

				$customizer->add_control( 'wp_video_encoder[hls_encrypt_endpoint]', array(
					'label'				=>	esc_html__( 'HLS Encryption File Info Endpoint', 'wp-video-encoder' ),
					'type'				=>	'text',
					'section'			=>	self::PANEL_ID . '_encrypt',
					'description'		=>	sprintf(
						esc_html__( 'Leave this as default or change to an unique endpoint, once changing this option, you have to update %s', 'wp-video-encoder' ),
						sprintf(
							'<a href="%s">%s</a>',
							esc_url( admin_url('options-permalink.php') ),
							esc_html__( 'Permalinks', 'wp-video-encoder' )
						)
					),
					'active_callback'	=>	function(){
						$settings = WP_Video_Encoder_Settings::get_settings();
						return wp_validate_boolean( $settings['hls_encrypt'] ) ? true : false;
					}				
				) );			

				$customizer->add_setting( 'wp_video_encoder[hls_encrypt_file_url]', array(
					'default'			=>	'',
					'type'				=>	'option',
					'capability'		=>	'edit_theme_options',
					'sanitize_callback'	=>	'sanitize_text_field',
				) );

				$customizer->add_control( 'wp_video_encoder[hls_encrypt_file_url]', array(
					'label'				=>	esc_html__( 'HLS Encryption File URL', 'wp-video-encoder' ),
					'type'				=>	'url',
					'section'			=>	self::PANEL_ID . '_encrypt',
					'description'	=>	esc_html__( 'Encryption file URL, keep it accessible forever during your website lifetime, using for segment encryption, if the file is not found, we cannot decrypt your encoded videos.', 'wp-video-encoder' ),
					'active_callback'	=>	function(){
						$settings = WP_Video_Encoder_Settings::get_settings();
						return wp_validate_boolean( $settings['hls_encrypt'] ) ? true : false;					
					}				
				) );

				$customizer->add_setting( 'wp_video_encoder[hls_encrypt_file_path]', array(
					'default'			=>	'',
					'type'				=>	'option',
					'capability'		=>	'edit_theme_options',
					'sanitize_callback'	=>	'sanitize_text_field',
				) );

				$customizer->add_control(
					new WP_Customize_Image_Control(
						$customizer,
						'wp_video_encoder[hls_encrypt_file_path]',
						array(
							'label'      => esc_html__( 'HLS Encryption File', 'wp-video-encoder' ),
							'description'		=>	esc_html__( 'Upload the Encryption file here', 'wp-video-encoder' ),
							'section'    		=> self::PANEL_ID . '_encrypt',
							'active_callback'	=>	function(){
								$settings = WP_Video_Encoder_Settings::get_settings();
								return wp_validate_boolean( $settings['hls_encrypt'] ) ? true : false;					
							}						
						)
					)
				);

				$customizer->add_setting( 'wp_video_encoder[hls_encrypt_iv]', array(
					'default'			=>	'',
					'type'				=>	'option',
					'capability'		=>	'edit_theme_options',
					'sanitize_callback'	=>	'sanitize_text_field',
				) );

				$customizer->add_control( 'wp_video_encoder[hls_encrypt_iv]', array(
					'label'				=>	esc_html__( 'HLS Encrypt Initialisation Vector', 'wp-video-encoder' ),
					'type'				=>	'text',
					'section'			=>	self::PANEL_ID . '_encrypt',
					'description'	=>	esc_html__( 'For segment encryption, optional', 'wp-video-encoder' ),
					'active_callback'	=>	function(){
						$settings = WP_Video_Encoder_Settings::get_settings();
						return wp_validate_boolean( $settings['hls_encrypt'] ) ? true : false;					
					}					
				) );

			$customizer->add_section( self::PANEL_ID . '_resolution', array(
				'title'				=>	esc_html__( 'Resolutions', 'wp-video-encoder' ),
				'priority'			=>	10,
				'panel'				=>	self::PANEL_ID,
				'description'		=>	self::notice()
			) );

				$customizer->add_setting( 'wp_video_encoder[res_426x240]', array(
					'default'			=>	'on',
					'type'				=>	'option',
					'capability'		=>	'edit_theme_options',
					'sanitize_callback'	=>	'sanitize_text_field'				
				) );

				$customizer->add_control( 'wp_video_encoder[res_426x240]', array(
					'label'				=>	esc_html__( '426x240 Resolution', 'wp-video-encoder' ),
					'type'				=>	'checkbox',
					'section'			=>	self::PANEL_ID . '_resolution',
					'description'		=>	esc_html__( 'Enable 426x240 resolution', 'wp-video-encoder' )
				) );

				$customizer->add_setting( 'wp_video_encoder[res_640x360]', array(
					'default'			=>	'on',
					'type'				=>	'option',
					'capability'		=>	'edit_theme_options',
					'sanitize_callback'	=>	'sanitize_text_field'				
				) );

				$customizer->add_control( 'wp_video_encoder[res_640x360]', array(
					'label'				=>	esc_html__( '640x360 Resolution', 'wp-video-encoder' ),
					'type'				=>	'checkbox',
					'section'			=>	self::PANEL_ID . '_resolution',
					'description'		=>	esc_html__( 'Enable 640x360 resolution', 'wp-video-encoder' )
				) );

				$customizer->add_setting( 'wp_video_encoder[res_854x480]', array(
					'default'			=>	'on',
					'type'				=>	'option',
					'capability'		=>	'edit_theme_options',
					'sanitize_callback'	=>	'sanitize_text_field'				
				) );

				$customizer->add_control( 'wp_video_encoder[res_854x480]', array(
					'label'				=>	esc_html__( '854x480 Resolution', 'wp-video-encoder' ),
					'type'				=>	'checkbox',
					'section'			=>	self::PANEL_ID . '_resolution',
					'description'		=>	esc_html__( 'Enable 854x480 resolution', 'wp-video-encoder' )
				) );

				$customizer->add_setting( 'wp_video_encoder[res_1280x720]', array(
					'default'			=>	'',
					'type'				=>	'option',
					'capability'		=>	'edit_theme_options',
					'sanitize_callback'	=>	'sanitize_text_field'				
				) );

				$customizer->add_control( 'wp_video_encoder[res_1280x720]', array(
					'label'				=>	esc_html__( '1280x720 (HD 720) Resolution', 'wp-video-encoder' ),
					'type'				=>	'checkbox',
					'section'			=>	self::PANEL_ID . '_resolution',
					'description'		=>	esc_html__( 'Enable 1280x720 resolution', 'wp-video-encoder' )
				) );

				$customizer->add_setting( 'wp_video_encoder[res_1920x1080]', array(
					'default'			=>	'',
					'type'				=>	'option',
					'capability'		=>	'edit_theme_options',
					'sanitize_callback'	=>	'sanitize_text_field'				
				) );

				$customizer->add_control( 'wp_video_encoder[res_1920x1080]', array(
					'label'				=>	esc_html__( '1920x1080 (HD 1080) Resolution', 'wp-video-encoder' ),
					'type'				=>	'checkbox',
					'section'			=>	self::PANEL_ID . '_resolution',
					'description'		=>	esc_html__( 'Enable 1920x1080 resolution', 'wp-video-encoder' ),
					'input_attrs'		=>	array(
						'disabled'	=>	'',
						'readonly'	=>	''
					)
				) );

				$customizer->add_setting( 'wp_video_encoder[res_2560x1440]', array(
					'default'			=>	'',
					'type'				=>	'option',
					'capability'		=>	'edit_theme_options',
					'sanitize_callback'	=>	'sanitize_text_field'				
				) );

				$customizer->add_control( 'wp_video_encoder[res_2560x1440]', array(
					'label'				=>	esc_html__( '2560x1440 (HD 1440) Resolution', 'wp-video-encoder' ),
					'type'				=>	'checkbox',
					'section'			=>	self::PANEL_ID . '_resolution',
					'description'		=>	esc_html__( 'Enable 2560x1440 resolution, this may hurt your server resource.', 'wp-video-encoder' )				
				) );

				$customizer->add_setting( 'wp_video_encoder[res_3840x2160]', array(
					'default'			=>	'',
					'type'				=>	'option',
					'capability'		=>	'edit_theme_options',
					'sanitize_callback'	=>	'sanitize_text_field'				
				) );

				$customizer->add_control( 'wp_video_encoder[res_3840x2160]', array(
					'label'				=>	esc_html__( '3840x2160 (4K) Resolution', 'wp-video-encoder' ),
					'type'				=>	'checkbox',
					'section'			=>	self::PANEL_ID . '_resolution',
					'description'		=>	esc_html__( 'Enable 3840x2160 resolution, this may hurt your server resource.', 'wp-video-encoder' )			
				) );			
	}
}