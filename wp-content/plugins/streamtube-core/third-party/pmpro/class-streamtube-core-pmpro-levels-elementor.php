<?php
/**
 * Define the pmpro levels shortocde functionality
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

class Streamtube_Core_PMPro_Levels_Elementor extends \Elementor\Widget_Base{
    public function get_name(){
        return 'streamtube-pmpro-levels';
    }

    public function get_title(){
        return esc_html__( 'Membership Levels', 'streamtube-core' );
    }

    public function get_icon(){
        return 'eicon-lock-user';
    }

    public function get_keywords(){
        return array( 'pmpro', 'pmp', 'membership', 'paid membership pro', 'levels', 'streamtube' );
    }

    public function get_categories(){
        return array( 'streamtube_pmp' );
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
                'heading_tag',
                array(
                    'label'     =>  esc_html__( 'Heading Tag', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SELECT,
                    'default'   =>  'h2',
                    'options'   =>  streamtube_core_get_heading_options()
                )
            );

            $this->add_control(
                'custom_levels',
                array(
                    'label'     =>  esc_html__( 'Custom Levels', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SELECT2,
                    'default'   =>  '',
                    'options'   =>  streamtube_core_get_pmp_levels_options(),
                    'multiple'  =>  true
                )
            );            

            $this->add_control(
                'plan_description',
                array(
                    'label'     =>  esc_html__( 'Plan Description', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SWITCHER,
                    'default'   =>  'on',
                    'description'   =>  esc_html__( 'Displays Plan Description', 'streamtube-core' )
                )
            );

            $this->add_control(
                'button_size',
                array(
                    'label'     =>  esc_html__( 'Button Size', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SELECT,
                    'default'   =>  'md',
                    'options'   =>  array(
                        'sm'        =>  esc_html__( 'Small', 'streamtube-core' ),
                        'md'        =>  esc_html__( 'Medium', 'streamtube-core' ),
                        'lg'        =>  esc_html__( 'Large', 'streamtube-core' )
                    )
                )
            );            

            $this->add_control(
                'select_button',
                array(
                    'label'     =>  esc_html__( 'Select Button', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SELECT,
                    'default'   =>  'primary',
                    'options'   =>  streamtube_core_get_button_styles()
                )
            );

            $this->add_control(
                'renew_button',
                array(
                    'label'     =>  esc_html__( 'Renew Button', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SELECT,
                    'default'   =>  'primary',
                    'options'   =>  streamtube_core_get_button_styles()
                )
            );

            $this->add_control(
                'your_level_button',
                array(
                    'label'     =>  esc_html__( 'Your Level Button', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SELECT,
                    'default'   =>  'success',
                    'options'   =>  streamtube_core_get_button_styles()
                )
            );

            $this->add_control(
                'shadow',
                array(
                    'label'     =>  esc_html__( 'Shadow', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SELECT,
                    'default'   =>  'sm',
                    'options'   =>  array(
                        'none'      =>  esc_html__( 'None', 'streamtube-core' ),
                        'sm'        =>  esc_html__( 'Small', 'streamtube-core' ),
                        'lg'        =>  esc_html__( 'Large', 'streamtube-core' )
                    )
                )
            );            

            $this->add_control(
                'min_height',
                array(
                    'label'     =>  esc_html__( 'Min Height', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::NUMBER,
                    'default'   =>  ''
                )
            );              

        $this->end_controls_section();

        $this->start_controls_section(
            'section-layout',
            array(
                'label'     =>  esc_html__( 'Layout', 'streamtube-core' ),
                'tab'       =>  \Elementor\Controls_Manager::TAB_CONTENT
            )
        );

            $this->add_control(
                'mb',
                array(
                    'label'     =>  esc_html__( 'Margin Bottom', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::NUMBER,
                    'default'   =>  '4'
                )
            );         

            $this->add_control(
                'col_xxl',
                array(
                    'label'     =>  esc_html__( 'Extra extra large ≥1400px', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::NUMBER,
                    'default'   =>  4
                )
            );

            $this->add_control(
                'col_xl',
                array(
                    'label'     =>  esc_html__( 'Extra large ≥1200px', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::NUMBER,
                    'default'   =>  4
                )
            );

            $this->add_control(
                'col_lg',
                array(
                    'label'     =>  esc_html__( 'Large ≥992px', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::NUMBER,
                    'default'   =>  2
                )
            );

            $this->add_control(
                'col_md',
                array(
                    'label'     =>  esc_html__( 'Medium ≥768px', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::NUMBER,
                    'default'   =>  2
                )
            );

            $this->add_control(
                'col_sm',
                array(
                    'label'     =>  esc_html__( 'Small ≥576px', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::NUMBER,
                    'default'   =>  1
                )
            );

            $this->add_control(
                'col',
                array(
                    'label'     =>  esc_html__( 'Extra small <576px', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::NUMBER,
                    'default'   =>  1
                )
            );

        $this->end_controls_section();
    }

    protected function render(){

        global $streamtube;

        if( ! $streamtube ){
            return;
        }

        echo $streamtube->get()->pmpro->_shortcode_membership_levels( $this->get_settings_for_display() );
    }    
}

if( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION , '3.5.0', '<' ) ){
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Streamtube_Core_PMPro_Levels_Elementor() );
}
else{
    \Elementor\Plugin::instance()->widgets_manager->register( new Streamtube_Core_PMPro_Levels_Elementor() );
}