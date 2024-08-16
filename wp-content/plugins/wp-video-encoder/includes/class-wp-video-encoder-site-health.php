<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * Define the site health functionality.
 *
 * @since      1.0.0
 * @package    WP_Video_Encoder
 * @subpackage WP_Video_Encoder/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */
class WP_Video_Encoder_Site_Health {

    /**
     *
     * The Not Found text
     * 
     * @return string
     *
     * @since 1.1
     * 
     */
    private function not_found_text(){
        return esc_html__( 'Not found', 'wp-video-encoder' );
    }

    /**
     *
     * Get FFmpeg info
     * 
     * @return array
     *
     * @since 1.1
     * 
     */
    private function get_ffmpeg_info(){

        $settings = WP_Video_Encoder_Settings::get_settings();

        $encoder = new WP_Video_Encoder_Encoder( null, $settings['bin_path'], $settings['nice_path'] );

        return $encoder->get_ffmpeg_info();

    }

    /**
     *
     * Debug info
     * 
     * @param  array $debug_info
     * @return array $debug_info
     *
     * @since 1.1
     * 
     */
    public function debug( $debug_info ){

        $ffmpeg_info = $this->get_ffmpeg_info();

        $debug_info['wp-video-encoder'] = array(
            'label'         =>  esc_html__( 'WP Video Encoder', 'wp-video-encoder' ),
            'fields'        => array(
                'version'           =>  array(
                    'label'         =>  esc_html__( 'Version', 'wp-video-encoder' ),
                    'value'         =>  defined( 'WP_VIDEO_ENCODER_VERSION' ) ? WP_VIDEO_ENCODER_VERSION : '1.0.0',
                    'private'       =>  true
                ),                   
                'settings'          =>  array(
                    'label'         =>  esc_html__( 'Settings', 'wp-video-encoder' ),
                    'value'         =>  WP_Video_Encoder_Settings::get_settings(),
                    'private'       =>  true
                ),
                'ffmpeg_version'     =>  array(
                    'label'         =>  esc_html__( 'FFmpeg Version', 'wp-video-encoder' ),
                    'value'         =>  $ffmpeg_info['version'] ? $ffmpeg_info['version'] : $this->not_found_text(),
                    'private'       =>  true
                ),
                'ffmpeg_hls'     =>  array(
                    'label'         =>  esc_html__( 'FFmpeg HLS', 'wp-video-encoder' ),
                    'value'         =>  $ffmpeg_info['hls'] ? $ffmpeg_info['hls'] : $this->not_found_text(),
                    'private'       =>  true
                ),
                'ffmpeg_segments'     =>  array(
                    'label'         =>  esc_html__( 'FFmpeg Segments', 'wp-video-encoder' ),
                    'value'         =>  $ffmpeg_info['segments'] ? $ffmpeg_info['segments'] : $this->not_found_text(),
                    'private'       =>  true
                ),
                'ffmpeg_h264'     =>  array(
                    'label'         =>  esc_html__( 'FFmpeg H264', 'wp-video-encoder' ),
                    'value'         =>  $ffmpeg_info['h264'] ? $ffmpeg_info['h264'] : $this->not_found_text(),
                    'private'       =>  true
                )                
            )
        );
     
        return $debug_info;
    }
}