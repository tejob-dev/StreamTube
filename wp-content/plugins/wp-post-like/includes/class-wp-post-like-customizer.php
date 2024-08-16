<?php

/**
 * Define the Customizer functionality
 *
 *
 * @link       https://themeforest.net/user/phpface
 * @since      1.0.0
 *
 * @package    WP_Post_Like
 * @subpackage WP_Post_Like/includes
 */

/**
 *
 * @since      1.0.0
 * @package    WP_Post_Like
 * @subpackage WP_Post_Like/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */
if( ! defined( 'ABSPATH' ) ){
    exit;
}

class WP_Post_Like_Customizer {
    public static function register( $customizer ){

        $customizer->add_section( 'wp_post_like', array(
            'title'             =>  esc_html__( 'WP Post Like', 'wp-post-like' ),
            'priority'          =>  100
        ) );

            $customizer->add_setting( 'wp_post_like[post_types]', array(
                'default'           =>  'video,post,attachment,product',
                'type'              =>  'option',
                'capability'        =>  'edit_theme_options',
                'sanitize_callback' =>  'sanitize_text_field'
            ) );

            $customizer->add_control( 'wp_post_like[post_types]', array(
                'label'             =>  esc_html__( 'Supported Post Types', 'wp-post-like' ),
                'type'              =>  'text',
                'section'           =>  'wp_post_like',
                'description'       =>  esc_html__( 'Separated by comma.', 'wp-post-like' )
            ) );        

            $customizer->add_setting( 'wp_post_like[button_like_enable]', array(
                'default'           =>  'on',
                'type'              =>  'option',
                'capability'        =>  'edit_theme_options',
                'sanitize_callback' =>  'sanitize_text_field'
            ) );

            $customizer->add_control( 'wp_post_like[button_like_enable]', array(
                'label'             =>  esc_html__( 'Enable Like button', 'wp-post-like' ),
                'type'              =>  'checkbox',
                'section'           =>  'wp_post_like'
            ) );

            $customizer->add_setting( 'wp_post_like[button_like_icon]', array(
                'default'           =>  'icon-thumbs-up',
                'type'              =>  'option',
                'capability'        =>  'edit_theme_options',
                'sanitize_callback' =>  'sanitize_text_field'
            ) );

            $customizer->add_control( 'wp_post_like[button_like_icon]', array(
                'label'             =>  esc_html__( 'Like button icon', 'wp-post-like' ),
                'type'              =>  'text',
                'section'           =>  'wp_post_like'
            ) );

            $customizer->add_setting( 'wp_post_like[button_dislike_enable]', array(
                'default'           =>  'on',
                'type'              =>  'option',
                'capability'        =>  'edit_theme_options',
                'sanitize_callback' =>  'sanitize_text_field'
            ) );

            $customizer->add_control( 'wp_post_like[button_dislike_enable]', array(
                'label'             =>  esc_html__( 'Enable Dislike button', 'wp-post-like' ),
                'type'              =>  'checkbox',
                'section'           =>  'wp_post_like'
            ) );

            $customizer->add_setting( 'wp_post_like[button_dislike_icon]', array(
                'default'           =>  'icon-thumbs-down',
                'type'              =>  'option',
                'capability'        =>  'edit_theme_options',
                'sanitize_callback' =>  'sanitize_text_field'
            ) );

            $customizer->add_control( 'wp_post_like[button_dislike_icon]', array(
                'label'             =>  esc_html__( 'DisLike button icon', 'wp-post-like' ),
                'type'              =>  'text',
                'section'           =>  'wp_post_like'
            ) );

            $customizer->add_setting( 'wp_post_like[safe_click]', array(
                'default'           =>  'on',
                'type'              =>  'option',
                'capability'        =>  'edit_theme_options',
                'sanitize_callback' =>  'sanitize_text_field'
            ) );

            $customizer->add_control( 'wp_post_like[safe_click]', array(
                'label'             =>  esc_html__( 'Prevent Multiple Clicks', 'wp-post-like' ),
                'type'              =>  'checkbox',
                'section'           =>  'wp_post_like',
                'description'       =>  esc_html__( 'Do not allow users to click on the button multiple times', 'wp-post-like' )
            ) );

            $customizer->add_setting( 'wp_post_like[safe_click_expire]', array(
                'default'           =>  60*15,
                'type'              =>  'option',
                'capability'        =>  'edit_theme_options',
                'sanitize_callback' =>  'sanitize_text_field'
            ) );

            $customizer->add_control( 'wp_post_like[safe_click_expire]', array(
                'label'             =>  esc_html__( 'Multiple Clicks Expiration', 'wp-post-like' ),
                'type'              =>  'number',
                'section'           =>  'wp_post_like'
            ) );

            $customizer->add_setting( 'wp_post_like[hover_buttons]', array(
                'default'           =>  'on',
                'type'              =>  'option',
                'capability'        =>  'edit_theme_options',
                'sanitize_callback' =>  'sanitize_text_field'
            ) );

            $customizer->add_control( 'wp_post_like[hover_buttons]', array(
                'label'             =>  esc_html__( 'Show buttons when hovering over the post thumbnail', 'wp-post-like' ),
                'type'              =>  'checkbox',
                'section'           =>  'wp_post_like'
            ) );

            $customizer->add_setting( 'wp_post_like[progress_bar]', array(
                'default'           =>  '',
                'type'              =>  'option',
                'capability'        =>  'edit_theme_options',
                'sanitize_callback' =>  'sanitize_text_field'
            ) );

            $customizer->add_control( 'wp_post_like[progress_bar]', array(
                'label'             =>  esc_html__( 'Show progress bar', 'wp-post-like' ),
                'type'              =>  'checkbox',
                'section'           =>  'wp_post_like'
            ) );

            $customizer->add_setting( 'wp_post_like_uninstall_delete_data', array(
                'default'           =>  '',
                'type'              =>  'option',
                'capability'        =>  'edit_theme_options',
                'sanitize_callback' =>  'sanitize_text_field'
            ) );

            $customizer->add_control( 'wp_post_like_uninstall_delete_data', array(
                'label'             =>  esc_html__( 'Delete Data After Uninstalling', 'wp-post-like' ),
                'type'              =>  'checkbox',
                'section'           =>  'wp_post_like',
                'description'       =>  esc_html__( 'Permanently DELETE all data after uninstalling the plugin, CAUTION, this action cannot be undone', 'wp-post-like' ),
            ) ); 
    }

    /**
     *
     * Get options
     *
     * @return array|string
     *
     * @since 1.2
     * 
     */
    public static function get_options(){

        $settings = wp_parse_args( get_option( 'wp_post_like', array() ), array(
            'post_types'            =>  'video,post,attachment,product',
            'button_like_enable'    =>  'on',
            'button_like_icon'      =>  'icon-thumbs-up',
            'button_dislike_enable' =>  'on',
            'button_dislike_icon'   =>  'icon-thumbs-down',
            'safe_click'            =>  'on',
            'safe_click_expire'     =>  60*15,
            'hover_buttons'         =>  'on',
            'progress_bar'          =>  ''
        ) );

        if( ! $settings['post_types'] ){
            $settings['post_types'] = array( 'video' );
        }

        if( is_string( $settings['post_types'] ) ){
            $settings['post_types'] = array_map( 'trim', explode( ',' , $settings['post_types'] ) );
        }

        return $settings;
    }
}