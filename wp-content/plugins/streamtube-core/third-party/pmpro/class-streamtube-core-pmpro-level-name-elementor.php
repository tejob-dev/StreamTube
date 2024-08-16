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

class Streamtube_Core_PMPro_Level_Name_Elementor extends \Elementor\Widget_Base{
    public function get_name(){
        return 'streamtube-pmpro-level-name';
    }

    public function get_title(){
        return esc_html__( 'Membership Level Name', 'streamtube-core' );
    }

    public function get_icon(){
        return 'eicon-lock-user';
    }

    public function get_keywords(){
        return array( 'pmpro', 'pmp', 'name', 'membership', 'paid membership pro', 'level', 'streamtube' );
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

            $this->add_control(
                'html_tag',
                array(
                    'label'     =>  esc_html__( 'HTML Tag', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SELECT,
                    'default'   =>  'h2',
                    'options'   =>  streamtube_core_get_heading_options()
                )
            );

            $this->add_control(
                'text_color',
                array(
                    'label'     =>  esc_html__( 'Text Color', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SELECT,
                    'default'   =>  'body',
                    'options'   =>  streamtube_core_get_text_styles()
                )
            );  

            $this->add_control(
                'text_align',
                [
                    'label' => esc_html__( 'Alignment', 'streamtube-core' ),
                    'type' => \Elementor\Controls_Manager::CHOOSE,
                    'options'       => array(
                        'left'      => array(
                            'title' => esc_html__( 'Left', 'streamtube-core' ),
                            'icon'  => 'eicon-text-align-left',
                        ),
                        'center'    => array(
                            'title' => esc_html__( 'Center', 'streamtube-core' ),
                            'icon'  => 'eicon-text-align-center',
                        ),
                        'right'     => array(
                            'title' => esc_html__( 'Right', 'streamtube-core' ),
                            'icon'  => 'eicon-text-align-right',
                        )
                    ),
                    'default'       => 'center',
                    'toggle'        => true,
                    'selectors'     => array(
                        '{{WRAPPER}} .level-name' => 'text-align: {{VALUE}};'
                    )
                ]
            );            

            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                array(
                    'name' => 'typography',
                    'selector' => '{{WRAPPER}} .level-name'                
                )
            );

            $this->add_group_control(
                \Elementor\Group_Control_Text_Stroke::get_type(),
                array(
                    'name' => 'text_stroke',
                    'selector' => '{{WRAPPER}} .level-name'
                )
            );

            $this->add_group_control(
                \Elementor\Group_Control_Text_Shadow::get_type(),
                array(
                    'name' => 'text_shadow',
                    'selector' => '{{WRAPPER}} .level-name'
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
            '<%1$s class="level-name text-%2$s">%3$s</%1$s>',
            $settings['html_tag'],
            esc_attr( $settings['text_color'] ),
            $level->name
        );
    }
}

if( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION , '3.5.0', '<' ) ){
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Streamtube_Core_PMPro_Level_Name_Elementor() );
}
else{
    \Elementor\Plugin::instance()->widgets_manager->register( new Streamtube_Core_PMPro_Level_Name_Elementor() );
}