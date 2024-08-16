<?php
/**
 * Define the comment list elementor widget functionality
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

class Streamtube_Core_Widget_Comments_Elementor extends \Elementor\Widget_Base{

    public function get_name(){
        return 'streamtube-comments';
    }

    public function get_title(){
        return esc_html__( 'Comment List', 'streamtube-core' );
    }

    public function get_icon(){
        return 'eicon-comments';
    }

    public function get_keywords(){
        return array( 'streamtube', 'comments' );
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
                    'default'   =>  5,
                    'decription'    =>  esc_html__( 'Number of comments to retrieve', 'streamtube-core' )
                )
            );

            $this->add_control(
                'avatar_size',
                array(
                    'label'     =>  esc_html__( 'Avatar Size', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::NUMBER,
                    'default'   =>  40,
                    'decription'    =>  esc_html__( 'Size of commenter avatar', 'streamtube-core' )
                )
            );            

            $this->add_control(
                'current_logged_user',
                array(
                    'label'     =>  esc_html__( 'Current Logged In User', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SWITCHER,
                    'decription'    =>  esc_html__( 'Retrieve comments of current logged in user.', 'streamtube-core' )
                )
            );

            $this->add_control(
                'current_author',
                array(
                    'label'     =>  esc_html__( 'Current Author', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SWITCHER,
                    'decription'    =>  esc_html__( 'Retrieve comments of current author.', 'streamtube-core' )
                )
            );   

            $this->add_control(
                'current_post',
                array(
                    'label'     =>  esc_html__( 'Current Post', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SWITCHER,
                    'decription'    =>  esc_html__( 'Retrieve comments of current post.', 'streamtube-core' )
                )
            ); 

        $this->end_controls_section();
    }

    protected function render(){

        $instance = $this->get_settings_for_display();

        the_widget( 'Streamtube_Core_Widget_Comments', $instance, array(
            'before_widget' => '<section class="widget comments-widget streamtube-widget widget-elementor">',
            'after_widget'  => '</section>',
            'before_title'  => '<div class="widget-title-wrap"><h2 class="widget-title d-flex align-items-center">',
            'after_title'   => '</h2></div>'
        ) );
    }    
}

if( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION , '3.5.0', '<' ) ){
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Streamtube_Core_Widget_Comments_Elementor() );
}
else{
    \Elementor\Plugin::instance()->widgets_manager->register( new Streamtube_Core_Widget_Comments_Elementor() );
}