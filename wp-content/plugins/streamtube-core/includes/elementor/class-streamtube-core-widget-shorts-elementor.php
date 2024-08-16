<?php
/**
 * Define the shorts elementor widget functionality
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

class Streamtube_Core_Widget_Shorts_Elementor extends \Elementor\Widget_Base{
    public function get_name(){
        return 'streamtube_shorts_elementor';
    }

    public function get_title(){
        return esc_html__( 'Shorts', 'streamtube-core' );
    }

    public function get_icon(){
        return 'eicon-video-playlist';
    }

    public function get_keywords(){
        return array( 'streamtube', 'posts', 'shorts', 'reel' );
    }

    public function get_categories(){
        return array( 'streamtube' );
    }

    protected function register_controls(){

    }

    protected function render(){
        echo do_shortcode( '[activity_shorts]' );
    }
}

if( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION , '3.5.0', '<' ) ){
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Streamtube_Core_Widget_Shorts_Elementor() );
}
else{
    \Elementor\Plugin::instance()->widgets_manager->register( new Streamtube_Core_Widget_Shorts_Elementor() );
}