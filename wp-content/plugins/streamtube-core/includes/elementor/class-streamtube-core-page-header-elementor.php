<?php
/**
 * Define the Page Header elementor functionality
 *
 *
 * @link       https://themeforest.net/user/phpface
 * @since      2.2
 *
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 */

/**
 *
 * @since      2.2
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */

if( ! defined('ABSPATH' ) ){
    exit;
}

class Streamtube_Core_Page_Header_Elementor extends \Elementor\Widget_Base{
    public function get_name(){
        return 'streamtube-page-header';
    }

    public function get_title(){
        return esc_html__( 'Page Header', 'streamtube-core' );
    }

    public function get_icon(){
        return 'eicon-header';
    }

    public function get_keywords(){
        return array( 'page', 'header', 'head', 'streamtube' );
    }

    public function get_categories(){
        return array( 'streamtube' );
    }

    protected function register_controls(){

        $this->start_controls_section(
            'section-general',
            array(
                'label'     =>  esc_html__( 'General', 'streamtube-core' ),
                'tab'       =>  \Elementor\Controls_Manager::TAB_CONTENT
            )
        );

            $this->add_control(
                'heading',
                array(
                    'label'     =>  esc_html__( 'Heading', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::TEXT
                )
            );

            $this->add_control(
                'header_alignment',
                array(
                    'label'     =>  esc_html__( 'Alignment', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SELECT,
                    'default'   =>  'default',
                    'options'   =>  array(
                        'default'   =>  esc_html__( 'Default', 'streamtube-core' ),
                        'center'    =>  esc_html__( 'Center', 'streamtube-core' )
                    )                    
                )
            );

            $this->add_control(
                'header_padding',
                array(
                    'label'     =>  esc_html__( 'Padding', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::NUMBER,
                    'default'   =>  5,
                )
            );            

        $this->end_controls_section();
    }

    protected function render(){
        get_template_part( 'template-parts/page', 'header', $this->get_settings_for_display() );
    }

}

if( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION , '3.5.0', '<' ) ){
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Streamtube_Core_Page_Header_Elementor() );
}
else{
    \Elementor\Plugin::instance()->widgets_manager->register( new Streamtube_Core_Page_Header_Elementor() );
}