<?php
/**
 * Define the self embed elementor functionality
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

class Streamtube_Core_Embed_Elementor extends \Elementor\Widget_Base{

    public function get_name(){
        return 'streamtube-embed';
    }

    public function get_title(){
        return esc_html__( 'Embed', 'streamtube-core' );
    }

    public function get_icon(){
        return 'eicon-video-camera';
    }

    public function get_keywords(){
        return array( 'embed', 'video', 'posts', 'streamtube' );
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
            'source',
            array(
                'label'     =>  esc_html__( 'Source', 'streamtube-core' ),
                'type'      =>  \Elementor\Controls_Manager::TEXT
            )
        );

        $this->add_control(
            'ratio',
            array(
                'label'     =>  esc_html__( 'Aspect Ratio', 'streamtube-core' ),
                'type'      =>  \Elementor\Controls_Manager::SELECT,
                'default'   =>  '16x9',
                'options'       =>  array(
                    '21x9'  =>  esc_html__( '21x9', 'streamtube-core' ),
                    '16x9'  =>  esc_html__( '16x9', 'streamtube-core' ),
                    '4x3'   =>  esc_html__( '4x3', 'streamtube-core' ),
                    '1x1'   =>  esc_html__( '1x1', 'streamtube-core' )
                )
            )
        );        

        $this->end_controls_section();

    }    

    protected function content_template() {
    }

    public function render_plain_content( $instance = array() ) {
    }

    protected function render(){
        $settings = $this->get_settings_for_display();

        if( ! array_key_exists( 'source', $settings ) ){
            return;
        }

        $output = wp_oembed_get( $settings['source'] );

        if( empty( $output ) ){
            return;
        }

        printf(
            '<div class="ratio ratio-%s">%s</div>',
            $settings['ratio'],
            $output
        );
    }
}

if( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION , '3.5.0', '<' ) ){
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Streamtube_Core_Embed_Elementor() );
}
else{
    \Elementor\Plugin::instance()->widgets_manager->register( new Streamtube_Core_Embed_Elementor() );
}