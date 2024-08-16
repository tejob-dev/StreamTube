<?php
/**
 * Define the user grid elementor shortcode functionality
 *
 * Requires WP Post Like plugin installed
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

class Streamtube_Core_Shortcode_User_List_Elementor extends \Elementor\Widget_Base{

    public function get_name(){
        return 'user-list';
    }

    public function get_title(){
        return esc_html__( 'User List', 'streamtube-core' );
    }

    public function get_icon(){
        return 'eicon-user-circle-o';
    }

    public function get_keywords(){
        return array( 'user', 'streamtube' );
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
                    'type'      =>  \Elementor\Controls_Manager::TEXT
                )
            );

            $this->add_control(
                'number',
                array(
                    'label'     =>  esc_html__( 'Number', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::NUMBER,
                    'default'   =>  5
                )
            );             

        $this->end_controls_section();

        $this->start_controls_section(
            'section-data-source',
            array(
                'label'     =>  esc_html__( 'Data Source', 'streamtube-core' ),
                'tab'       =>  \Elementor\Controls_Manager::TAB_CONTENT
            )
        );    

            $this->add_control(
                'role__in',
                array(
                    'label'     =>  esc_html__( 'Roles', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SELECT2,
                    'default'   =>  '',
                    'multiple'  =>  true,
                    'options'   =>  streamtube_get_get_role_options()
                )
            );

            $this->add_control(
                'has_published_posts',
                array(
                    'label'     =>  esc_html__( 'Has Published Post Types', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SELECT2,
                    'default'   =>  '',
                    'multiple'  =>  true,
                    'options'   =>  get_post_types()
                )
            );

        $this->end_controls_section();        
    }

    protected function content_template() {
    }

    public function render_plain_content( $instance = array() ) {
    }

    protected function render(){
        the_widget( 'Streamtube_Core_Widget_User_List', $this->get_settings_for_display(), array(
            'before_widget' => '<div class="widget widget-elementor user-list-widget streamtube-widget">',
            'after_widget'  => '</div>',
            'before_title'  => '<div class="widget-title-wrap"><h2 class="widget-title d-flex align-items-center">',
            'after_title'   => '</h2></div>'
        ) );
    }     
}
if( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION , '3.5.0', '<' ) ){
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Streamtube_Core_Shortcode_User_List_Elementor() );
}
else{
    \Elementor\Plugin::instance()->widgets_manager->register( new Streamtube_Core_Shortcode_User_List_Elementor() );
}