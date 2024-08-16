<?php
/**
 * Define the pmpro Level Signup_Button functionality
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

class Streamtube_Core_PMPro_Level_Signup_Button_Elementor extends \Elementor\Widget_Base{
    public function get_name(){
        return 'streamtube-pmpro-level-signup';
    }

    public function get_title(){
        return esc_html__( 'Membership Level Sign-Up Button', 'streamtube-core' );
    }

    public function get_icon(){
        return 'eicon-lock-user';
    }

    public function get_keywords(){
        return array( 'pmpro', 'pmp', 'signup', 'membership', 'paid membership pro', 'level', 'streamtube' );
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
                'text_color',
                array(
                    'label'     =>  esc_html__( 'Text Color', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SELECT,
                    'default'   =>  'body',
                    'options'   =>  streamtube_core_get_text_styles()
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
                        '{{WRAPPER}} .level-button' => 'text-align: {{VALUE}};'
                    )
                ]
            );            

            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                array(
                    'name' => 'typography',
                    'selector' => '{{WRAPPER}} .level-button'                
                )
            );

            $this->add_group_control(
                \Elementor\Group_Control_Text_Stroke::get_type(),
                array(
                    'name' => 'text_stroke',
                    'selector' => '{{WRAPPER}} .level-button'
                )
            );

            $this->add_group_control(
                \Elementor\Group_Control_Text_Shadow::get_type(),
                array(
                    'name' => 'text_shadow',
                    'selector' => '{{WRAPPER}} .level-button'
                )
            );            

        $this->end_controls_section();
    }

    protected function render(){

        if( ! function_exists( 'pmpro_getLevel' ) || ! function_exists( 'pmpro_getSpecificMembershipLevelForUser' ) ){
            return;
        }

        $settings = $this->get_settings_for_display();

        $level = pmpro_getLevel( $settings['level'] );

        if( ! $level ){
            return;
        }

        $user_level         = pmpro_getSpecificMembershipLevelForUser( get_current_user_id(), $level->id );
        $has_level          = ! empty( $user_level )    ? true  : false;        

        ?>
            <div class="pmpro-plan-button text-center mt-auto">
                <?php

                if ( ! $has_level ):

                    printf(
                        '<a class="%s" href="%s">%s</a>',
                        pmpro_get_element_class( 'level-button btn btn-'. sanitize_html_class( $settings['button_size'] ) .' btn-'. sanitize_html_class($settings['select_button']) .' d-block text-' . esc_attr( $settings['text_color'] ), 'pmpro_btn-select' ),
                        esc_url( pmpro_url( "checkout", "?level=" . $level->id, "https" ) ),
                        esc_html__( 'Select', 'streamtube-core' ),
                    );

                else:

                    if( pmpro_isLevelExpiringSoon( $user_level ) && $level->allow_signups ) {

                        printf(
                            '<a class="%s" href="%s">%s</a>',
                            pmpro_get_element_class( 'level-button btn btn-'. sanitize_html_class( $settings['button_size'] ) .' btn-'. sanitize_html_class($settings['renew_button']) .' d-block', 'pmpro_btn-select' ),
                            esc_url( pmpro_url( "checkout", "?level=" . $level->id, "https" ) ),
                            esc_html__( 'Renew', 'streamtube-core' )
                        );

                    } else {

                        printf(
                            '<a class="%s" href="%s">%s</a>',
                            pmpro_get_element_class( 'level-button btn btn-'. sanitize_html_class( $settings['button_size'] ) .' btn-'. sanitize_html_class($settings['your_level_button']) .' d-block disabled', 'pmpro_btn' ),
                            esc_url( pmpro_url( "account" ) ),
                            esc_html__('Your&nbsp;Level', 'streamtube-core' )
                        );

                    }

                endif;
                ?>
            </div>
        <?php
    }
}

if( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION , '3.5.0', '<' ) ){
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Streamtube_Core_PMPro_Level_Signup_Button_Elementor() );
}
else{
    \Elementor\Plugin::instance()->widgets_manager->register( new Streamtube_Core_PMPro_Level_Signup_Button_Elementor() );
}