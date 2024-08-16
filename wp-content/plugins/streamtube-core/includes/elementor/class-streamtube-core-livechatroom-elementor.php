<?php
/**
 * Define the Live Chat Room elementor functionality
 *
 *
 * @link       https://themeforest.net/user/phpface
 * @since      2.1.7
 *
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 */

/**
 *
 * @since      2.1.7
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */
if( ! defined('ABSPATH' ) ){
    exit;
}

class Streamtube_Core_LiveChatRoom_Elementor extends \Elementor\Widget_Base{

    public function get_name(){
        return 'livechat';
    }

    public function get_title(){
        return esc_html__( 'Live Chat Room', 'streamtube-core' );
    }

    public function get_icon(){
        return 'eicon-comments';
    }

    public function get_keywords(){
        return array( 'streamtube', 'livechat', 'chat', 'room', 'live chat' );
    }

    public function get_categories(){
        return array( 'streamtube' );
    }

    protected function register_controls(){

        $this->start_controls_section(
            'section-appearance',
            array(
                'label'     =>  esc_html__( 'Appearance', 'streamtube-core' ),
                'tab'       =>  \Elementor\Controls_Manager::TAB_CONTENT
            )
        );

             $this->add_control(
                'title',
                array(
                    'label'     =>  esc_html__( 'Title', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::TEXT,
                    'default'   =>  ''
                )
            );

             $this->add_control(
                'post_id',
                array(
                    'label'     =>  esc_html__( 'Chat Room (Post) ID', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::NUMBER,
                    'default'   =>  ''
                )
            );             

        $this->end_controls_section();

    }

    protected function content_template() {
    }

    public function render_plain_content( $instance = array() ) {
    }

    protected function render(){

        if( ! class_exists( 'Streamtube_Core_Widget_LiveChat' ) ){
            return;
        }

        $settings = $this->get_settings_for_display();

        the_widget( 'Streamtube_Core_Widget_LiveChat', $settings, array(
            'before_widget' => '<div class="widget widget-elementor user-list-widget streamtube-widget">',
            'after_widget'  => '</div>',
            'before_title'  => '<div class="widget-title-wrap"><h2 class="widget-title d-flex align-items-center">',
            'after_title'   => '</h2></div>'
        ) );
    }
}

if( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION , '3.5.0', '<' ) ){
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Streamtube_Core_LiveChatRoom_Elementor() );
}
else{
    \Elementor\Plugin::instance()->widgets_manager->register( new Streamtube_Core_LiveChatRoom_Elementor() );
}