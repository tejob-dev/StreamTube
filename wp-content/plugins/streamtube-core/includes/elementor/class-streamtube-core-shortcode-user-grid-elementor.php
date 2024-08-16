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

class Streamtube_Core_Shortcode_User_Grid_Elementor extends \Elementor\Widget_Base{

    public function get_name(){
        return 'user-grid';
    }

    public function get_title(){
        return esc_html__( 'User Grid', 'streamtube-core' );
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
                    'type'      =>  \Elementor\Controls_Manager::TEXT,
                    'default'   =>  ''
                )
            );

            $this->add_control(
                'number',
                array(
                    'label'     =>  esc_html__( 'Number', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::NUMBER,
                    'default'   =>  12
                )
            );

            $this->add_control(
                'include_search',
                array(
                    'label'     =>  esc_html__( 'Search Form', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SWITCHER,
                    'default'   =>  'yes'
                )
            );

            $this->add_control(
                'include_sortby',
                array(
                    'label'     =>  esc_html__( 'Sortby', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SWITCHER,
                    'default'   =>  'yes',
                    'description'   =>  esc_html__( 'Enable Sortby dropdown', 'streamtube-core' )
                )
            );

            $this->add_control(
                'orderby',
                array(
                    'label'     =>  esc_html__( 'Order By', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::TEXT,
                    'default'   =>  'login'
                )
            );

            $this->add_control(
                'order',
                array(
                    'label'     =>  esc_html__( 'Order', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SELECT,
                    'default'   =>  'ASC',
                    'options'   =>  streamtube_core_get_order_options()
                )
            );


            $this->add_control(
                'count_post_type',
                array(
                    'label'     =>  esc_html__( 'Post Type Count', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SELECT,
                    'default'   =>  'video',
                    'options'   =>  get_post_types()
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
                'search',
                array(
                    'label'     =>  esc_html__( 'Search', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::TEXT,
                    'default'   =>  ''
                )
            );

            $this->add_control(
                'roles',
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

        $this->start_controls_section(
            'section-layout',
            array(
                'label'     =>  esc_html__( 'Layout', 'streamtube-core' ),
                'tab'       =>  \Elementor\Controls_Manager::TAB_CONTENT
            )
        );      

            $this->add_control(
                'margin_bottom',
                array(
                    'label'     =>  esc_html__( 'Margin Bottom', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::NUMBER,
                    'default'   =>  4,
                    'description'   =>  esc_html__( 'Set margin bottom: from 1 to 5', 'streamtube-core' )
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

        $settings = wp_parse_args( $this->get_settings_for_display(), array(
            'roles' =>  array()
        ) );

        if( $settings['roles'] ){
            $settings['role__in'] = $settings['roles'];

            unset( $settings['roles'] );
        }

        the_widget( 'Streamtube_Core_Widget_User_Grid', $settings, array(
            'before_widget' => '<div class="widget widget-elementor user-grid-widget streamtube-widget">',
            'after_widget'  => '</div>',
            'before_title'  => '<div class="widget-title-wrap"><h2 class="widget-title d-flex align-items-center">',
            'after_title'   => '</h2></div>'
        ));        
    }    
}

if( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION , '3.5.0', '<' ) ){
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Streamtube_Core_Shortcode_User_Grid_Elementor() );
}
else{
    \Elementor\Plugin::instance()->widgets_manager->register( new Streamtube_Core_Shortcode_User_Grid_Elementor() );
}