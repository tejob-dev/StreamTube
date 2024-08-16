<?php
/**
 * Define the World Map elementor functionality
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

if( ! class_exists( 'WP_Post_Location_Shortcode' ) ){
    return;
}

class Streamtube_Core_World_Map_Elementor extends \Elementor\Widget_Base{

    public function get_name(){
        return 'streamtube-world-map';
    }

    public function get_title(){
        return esc_html__( 'WP Post Location', 'streamtube-core' );
    }

    public function get_icon(){
        return 'eicon-google-maps';
    }

    public function get_keywords(){
        return array( 'maps', 'world', 'posts', 'streamtube', 'location' );
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
            'find_my_location',
            array(
                'label'         =>  esc_html__( 'Find My Location Button', 'streamtube-core' ),
                'type'          =>  \Elementor\Controls_Manager::SWITCHER,
                'default'       =>  'yes'
            )
        );        

        $this->add_control(
            'height',
            array(
                'label'         =>  esc_html__( 'Height', 'streamtube-core' ),
                'type'          =>  \Elementor\Controls_Manager::SLIDER,
                'size_units'    =>  array( 'vh', 'px', '%' ),
                'range'         =>  array(
                    'px' => array(
                        'min' => 20,
                        'max' => 100,
                        'step' => 1,
                    ),
                    'px' => array(
                        'min' => 100,
                        'max' => 1000,
                        'step' => 1,
                    ),
                    '%' => array(
                        'min' => 1,
                        'max' => 100,
                    )
                ),
                'default'       =>  array(
                    'unit'  =>  'vh',
                    'size'  =>  '80'
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

        if( class_exists( 'WP_Post_Location_Shortcode' ) ){

            $is_builder = true;

            $height = $settings['height']['size'] . $settings['height']['unit'];

            echo WP_Post_Location_Shortcode::_the_map( array_merge( 
                $settings,
                compact( 'height', 'is_builder' )
            ) );
        }
    }
}

if( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION , '3.5.0', '<' ) ){
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Streamtube_Core_World_Map_Elementor() );
}
else{
    \Elementor\Plugin::instance()->widgets_manager->register( new Streamtube_Core_World_Map_Elementor() );
}