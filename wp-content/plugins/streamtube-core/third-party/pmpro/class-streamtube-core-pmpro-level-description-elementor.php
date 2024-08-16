<?php
/**
 * Define the pmpro Level Name functionality
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

class Streamtube_Core_PMPro_Level_Description_Elementor extends \Elementor\Widget_Base{
    public function get_name(){
        return 'streamtube-pmpro-level-description';
    }

    public function get_title(){
        return esc_html__( 'Membership Level Description', 'streamtube-core' );
    }

    public function get_icon(){
        return 'eicon-lock-user';
    }

    public function get_keywords(){
        return array( 'pmpro', 'pmp', 'description', 'membership', 'paid membership pro', 'level', 'streamtube' );
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
                'level',
                array(
                    'label'     =>  esc_html__( 'Level', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SELECT2,
                    'default'   =>  '',
                    'options'   =>  streamtube_core_get_pmp_levels_options(),
                    'multiple'  =>  false
                )
            );        

        $this->end_controls_section();
    }

    protected function render(){
        if( ! function_exists( 'pmpro_getLevel' ) ){
            return;
        }

        $settings = $this->get_settings_for_display();

        $level = pmpro_getLevel( $settings['level'] );

        if( ! $level ){
            return;
        }

        printf(
            '<div class="pmpro-plan-description mb-4">%s</div>',
            do_shortcode( $level->description )
        );
    }
}

if( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION , '3.5.0', '<' ) ){
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Streamtube_Core_PMPro_Level_Description_Elementor() );
}
else{
    \Elementor\Plugin::instance()->widgets_manager->register( new Streamtube_Core_PMPro_Level_Description_Elementor() );
}