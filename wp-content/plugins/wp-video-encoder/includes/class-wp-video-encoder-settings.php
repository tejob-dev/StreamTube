<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * Define the settings functionality.
 *
 * @since      1.0.0
 * @package    WP_Video_Encoder
 * @subpackage WP_Video_Encoder/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */
class WP_Video_Encoder_Settings {
    /**
     *
     * Get plugin settings
     * 
     * @return array
     *
     * @since  1.0.0
     * 
     */
    public static function get_settings(){

        $defaults = array(
            'encoder_version'           =>  'v2',
            'bin_path'                  =>  '/usr/bin/',
            'auto_generate_image'       =>  '1',
            'auto_generate_webp_image'  =>  '',
            'webp_start_at'             =>  '00:00:01',
            'webp_setpts'               =>  '0.4',
            'webp_fixed_time'           =>  5,
            'webp_resolution'           =>  '640x360',
            'image_file_type'           =>  'webp',
            'auto_encode'               =>  '1',
            'nice'                      =>  '',
            'nice_path'                 =>  '/usr/bin',
            'nice_priority'             =>  10,
            'max_threads'               =>  2,
            'allow_formats'             =>  array(),
            'vcodec'                    =>  'h264',
            'h264_profile'              =>  'baseline',
            'moov'                      =>  '',
            'rate_control'              =>  'crf',
            'h264_crf'                  =>  23,
            'hls_encrypt'               =>  '',
            'hls_encrypt_endpoint'      =>  '',
            'hls_encrypt_file_url'      =>  '',
            'hls_encrypt_file_path'     =>  '',
            'hls_encrypt_iv'            =>  '',
            'res_426x240'               =>  '1',
            'res_640x360'               =>  '1',
            'res_854x480'               =>  '1',
            'res_1280x720'              =>  '',
            'res_1920x1080'             =>  '',
            'res_2560x1440'             =>  '',
            'res_3840x2160'             =>  '',
            'publish_parent'            =>  '1',
            'notification'              =>  '1',
            'enable_admin_ajax'         =>  '',
            'hls_playlist_type'         =>  'vod',
            'hls_segment_type'          =>  'mpegts',
            'hls_flags'                 =>  'independent_segments',
            'hls_segment_folder_name'   =>  'resolution',
            'strict_2'                  =>  'on',
            'extra_params'              =>  '',
            'watermark'                 =>  '',
            'watermark_position'        =>  'top_right',
            'watermark_padding'         =>  '20:20',
            'watermark_opacity'         =>  1,
            'watermark_size'            =>  'fixed',
            'watermark_size_percentage' =>  '0.3'
        );        

        $defaults = array_merge( $defaults, array(
            'allow_formats' =>  implode( ",", wp_get_video_extensions() )
        ) );

        $settings = get_option( 'wp_video_encoder' );

        if( ! $settings || ! is_array( $settings ) ){
            $settings = array();
        }

        return array_merge( $defaults, $settings );
    }

}