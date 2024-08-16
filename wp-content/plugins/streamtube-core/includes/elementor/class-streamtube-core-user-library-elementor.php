<?php
/**
 * Taxonomy List
 *
 *
 * @link       https://themeforest.net/user/phpface
 * @since      2.2.1
 *
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 */

/**
 *
 * @since      2.2.1
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */
if( ! defined('ABSPATH' ) ){
    exit;
}

class Streamtube_Core_User_Library_Elementor extends \Elementor\Widget_Base{

    public function get_name(){
        return 'streamtube-user-library';
    }

    public function get_title(){
        return esc_html__( 'User Library', 'streamtube-core' );
    }

    public function get_icon(){
        return 'eicon-video-playlist';
    }

    public function get_keywords(){
        return array( 'streamtube', 'library', 'user', 'history', 'collection' );
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
                'posts_per_column',
                array(
                    'label'     =>  esc_html__( 'Posts Per Column', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::NUMBER,
                    'default'   =>  4
                )
            );

            $this->add_control(
                'rows_per_page',
                array(
                    'label'     =>  esc_html__( 'Rows Per Page', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::NUMBER,
                    'default'   =>  1
                )
            );

            $this->add_control(
                'col_xxl',
                array(
                    'label'     =>  esc_html__( 'Columns - Extra extra large ≥1400px', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::NUMBER,
                    'default'   =>  4
                )
            );

            $this->add_control(
                'col_xl',
                array(
                    'label'     =>  esc_html__( 'Columns - Extra large ≥1200px', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::NUMBER,
                    'default'   =>  4
                )
            );

            $this->add_control(
                'col_lg',
                array(
                    'label'     =>  esc_html__( 'Columns - Large ≥992px', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::NUMBER,
                    'default'   =>  2
                )
            );

            $this->add_control(
                'col_md',
                array(
                    'label'     =>  esc_html__( 'Columns - Medium ≥768px', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::NUMBER,
                    'default'   =>  2
                )
            );

            $this->add_control(
                'col_sm',
                array(
                    'label'     =>  esc_html__( 'Columns - Small ≥576px', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::NUMBER,
                    'default'   =>  1
                )
            );

            $this->add_control(
                'col',
                array(
                    'label'     =>  esc_html__( 'Columns - Extra small <576px', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::NUMBER,
                    'default'   =>  1
                )
            );            

        $this->end_controls_section();        
    }    

    protected function content_template() {
    }

    public function render_plain_content( $instance = array() ) {
    }

    protected function render(){
        $instance = $this->get_settings_for_display();

        global $streamtube;

        echo $streamtube->get()->shortcode->_user_library( $instance );
    }    
}

if( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION , '3.5.0', '<' ) ){
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Streamtube_Core_User_Library_Elementor() );
}
else{
    \Elementor\Plugin::instance()->widgets_manager->register( new Streamtube_Core_User_Library_Elementor() );
}