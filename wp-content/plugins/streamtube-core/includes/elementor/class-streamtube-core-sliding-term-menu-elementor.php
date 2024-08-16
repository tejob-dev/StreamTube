<?php
/**
 * Define the Term Menu elementor functionality
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

class Streamtube_Core_Sliding_Term_Menu_Elementor extends \Elementor\Widget_Base{
    public function get_name(){
        return 'streamtube-sliding-term-menu';
    }

    public function get_title(){
        return esc_html__( 'Sliding Term Menu', 'streamtube-core' );
    }

    public function get_icon(){
        return 'eicon-slides';
    }

    public function get_keywords(){
        return array( 'menu', 'video', 'posts', 'term', 'streamtube' );
    }

    public function get_categories(){
        return array( 'streamtube' );
    }

    public function get_taxonomies_choices(){

        $choices    = array();

        $taxonomies = get_taxonomies( array(
            'public'   => true,
        ), 'objects' );

        foreach ( $taxonomies as $taxonomy ) {
            $choices[ $taxonomy->name ] = $taxonomy->label;
        }

        return $choices;
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
                'include_all',
                array(
                    'label'         =>  esc_html__( 'Include All', 'streamtube-core' ),
                    'type'          =>  \Elementor\Controls_Manager::SWITCHER,
                    'default'       =>  'yes',
                    'description'   =>  esc_html__( 'Include All Item', 'streamtube-core' )
                )
            );

            $this->add_control(
                'count',
                array(
                    'label'         =>  esc_html__( 'Show count', 'streamtube-core' ),
                    'type'          =>  \Elementor\Controls_Manager::SWITCHER,
                    'default'       =>  ''
                )
            );             

            $this->add_control(
                'slide',
                array(
                    'label'         =>  esc_html__( 'Sliding', 'streamtube-core' ),
                    'type'          =>  \Elementor\Controls_Manager::SWITCHER,
                    'default'       =>  'yes'
                )
            );

            $this->add_control(
                'slidesToScroll',
                array(
                    'label'         =>  esc_html__( 'Slides To Scroll', 'streamtube-core' ),
                    'type'          =>  \Elementor\Controls_Manager::NUMBER,
                    'default'       =>  3
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
                'taxonomy',
                array(
                    'label'         =>  esc_html__( 'Taxonomies', 'streamtube-core' ),
                    'type'          =>  \Elementor\Controls_Manager::SELECT2,
                    'multiple'      =>  true,
                    'default'       =>  array( 'categories', 'video_tag' ),
                    'options'       =>  $this->get_taxonomies_choices()
                )
            );

            $this->add_control(
                'number',
                array(
                    'label'         =>  esc_html__( 'Number', 'streamtube-core' ),
                    'type'          =>  \Elementor\Controls_Manager::NUMBER,
                    'default'       =>  30,
                    'description'   =>  esc_html__( 'Maximum number of terms ', 'streamtube-core' )
                )
            );

            $this->add_control(
                'orderby',
                array(
                    'label'         =>  esc_html__( 'Order By', 'streamtube-core' ),
                    'type'          =>  \Elementor\Controls_Manager::SELECT,
                    'default'       =>  'count',
                    'options'       =>  streamtube_core_get_term_orderby_options()
                )
            ); 

            $this->add_control(
                'order',
                array(
                    'label'         =>  esc_html__( 'Order', 'streamtube-core' ),
                    'type'          =>  \Elementor\Controls_Manager::SELECT,
                    'default'       =>  'DESC',
                    'options'       =>  streamtube_core_get_order_options()
                )
            );

            $this->add_control(
                'include',
                array(
                    'label'         =>  esc_html__( 'Include Terms', 'streamtube-core' ),
                    'type'          =>  \Elementor\Controls_Manager::TEXT,
                    'default'       =>  '',
                    'description'   =>  esc_html__( 'Term IDs to include, separated by commas', 'streamtube-core' )
                )
            );

            $this->add_control(
                'exclude',
                array(
                    'label'         =>  esc_html__( 'Exclude Terms', 'streamtube-core' ),
                    'type'          =>  \Elementor\Controls_Manager::TEXT,
                    'default'       =>  '',
                    'description'   =>  esc_html__( 'Term IDs to exclude, separated by commas', 'streamtube-core' )
                )
            );

            $this->add_control(
                'exclude_tree',
                array(
                    'label'         =>  esc_html__( 'Exclude Terms Tree', 'streamtube-core' ),
                    'type'          =>  \Elementor\Controls_Manager::TEXT,
                    'default'       =>  '',
                    'description'   =>  esc_html__( 'Term IDs to exclude along with all of their descendant terms, separated by commas', 'streamtube-core' )
                )
            );

            $this->add_control(
                'parent',
                array(
                    'label'         =>  esc_html__( 'Parent', 'streamtube-core' ),
                    'type'          =>  \Elementor\Controls_Manager::NUMBER,
                    'default'       =>  '',
                    'description'   =>  esc_html__( 'Parent term ID to retrieve direct-child terms of', 'streamtube-core' )
                )
            );            

            $this->add_control(
                'childless',
                array(
                    'label'         =>  esc_html__( 'Childless', 'streamtube-core' ),
                    'type'          =>  \Elementor\Controls_Manager::SWITCHER,
                    'default'       =>  '',
                    'description'   =>  esc_html__( 'Limit results to terms that have no children', 'streamtube-core' )
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

        echo $GLOBALS['streamtube']->get()->shortcode->_term_menu( $settings );
    }
}

if( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION , '3.5.0', '<' ) ){
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Streamtube_Core_Sliding_Term_Menu_Elementor() );
}
else{
    \Elementor\Plugin::instance()->widgets_manager->register( new Streamtube_Core_Sliding_Term_Menu_Elementor() );
}