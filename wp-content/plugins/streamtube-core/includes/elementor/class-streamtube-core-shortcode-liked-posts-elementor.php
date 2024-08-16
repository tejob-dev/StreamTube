<?php
/**
 * Define the liked posts elementor shortcode functionality
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

if( ! function_exists( 'WPPL' ) ){
    return;
}

class Streamtube_Core_Shortcode_Liked_Posts_Elementor extends \Elementor\Widget_Base{

    public function get_name(){
        return 'liked-posts';
    }

    public function get_title(){
        return esc_html__( 'Liked Posts', 'streamtube-core' );
    }

    public function get_icon(){
        return 'eicon-video-playlist';
    }

    public function get_keywords(){
        return array( 'like', 'liked', 'posts', 'streamtube' );
    }

    public function get_categories(){
        return array( 'streamtube' );
    }

    /**
     *
     * Get default supported post types
     * 
     * @return array
     *
     * @since  1.0.0
     * 
     */
    private function get_post_types(){
        return apply_filters( 'streamtube/core/elementor/widget/liked_posts/post_types', array(
            'video'         =>  esc_html__( 'Video', 'wp-post-like' ),
            'post'          =>  esc_html__( 'Post', 'wp-post-like' ),
            'attachment'    =>  esc_html__( 'Attachment', 'wp-post-like' )
        ) );
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
                'heading',
                array(
                    'label'     =>  esc_html__( 'Title', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::TEXT
                )
            );

            $this->add_control(
                'posts_per_page',
                array(
                    'label'     =>  esc_html__( 'Posts Per Page', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::NUMBER,
                    'default'   =>  10
                )
            );

            $this->add_control(
                'pagination',
                array(
                    'label'     =>  esc_html__( 'Pagination', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SELECT,
                    'default'   =>  '',
                    'options'   =>  array(
                        ''          =>  esc_html__( 'None', 'streamtube-core' ),
                        'numbers'   =>  esc_html__( 'Numbers', 'streamtube-core' ),
                        'scroll'    =>  esc_html__( 'Load on scroll', 'streamtube-core' ),
                        'click'     =>  esc_html__( 'Load on click', 'streamtube-core' )
                    )
                )
            );

            $this->add_control(
                'post_excerpt_length',
                array(
                    'label'     =>  esc_html__( 'Post excerpt length', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::NUMBER,
                    'default'   =>  0,
                    'description'   =>  esc_html__( 'Limit post excerpt length, 0 is disabled', 'streamtube-core' )
                )
            );

           $this->add_control(
                'show_post_date',
                array(
                    'label'     =>  esc_html__( 'Show post date', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SELECT,
                    'default'   =>  'diff',
                    'options'   => array(
                        ''          =>  esc_html__( 'None', 'streamtube-core' ),
                        'normal'    =>  esc_html__( 'Normal', 'streamtube-core' ),
                        'diff'      =>  esc_html__( 'Diff', 'streamtube-core' ),
                    )
                )
            );

            $this->add_control(
                'show_post_comment',
                array(
                    'label'     =>  esc_html__( 'Show post comment', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SWITCHER,
                    'default'   =>  'yes'
                )
            );  

            $this->add_control(
                'show_author_name',
                array(
                    'label'     =>  esc_html__( 'Show author name', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SWITCHER,
                    'default'   =>  'yes'
                )
            );

            $this->add_control(
                'author_avatar',
                array(
                    'label'     =>  esc_html__( 'Show post author avatar', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SWITCHER,
                    'default'   =>  ''
                )
            );

            $this->add_control(
                'avatar_size',
                array(
                    'label'     =>  esc_html__( 'Avatar size', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SELECT,
                    'default'   =>  'md',
                    'options'   =>  array(
                        'sm'    =>  esc_html__( 'Small', 'streamtube-core' ),
                        'md'    =>  esc_html__( 'Medium', 'streamtube-core' ),
                        'lg'    =>  esc_html__( 'Large', 'streamtube-core' )
                    ),
                    'condition' =>  array(
                        'author_avatar' =>  'yes'
                    )
                )
            );                   

            $this->add_control(
                'hide_thumbnail',
                array(
                    'label'     =>  esc_html__( 'Hide thumbnail image', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SWITCHER,
                    'default'   =>  ''
                )
            );

            $this->add_control(
                'hide_empty_thumbnail',
                array(
                    'label'     =>  esc_html__( 'Hide empty thumbnail posts', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SWITCHER,
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
                'layout',
                array(
                    'label'     =>  esc_html__( 'Layout', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SELECT,
                    'default'   =>  'grid',
                    'options'   =>  array(
                        'grid'      =>  esc_html__( 'Grid', 'streamtube-core' ),
                        'list_sm'   =>  esc_html__( 'List Small', 'streamtube-core' ),
                        'list_md'   =>  esc_html__( 'List Medium', 'streamtube-core' ),
                        'list_lg'   =>  esc_html__( 'List Large', 'streamtube-core' ),
                        'list_xl'   =>  esc_html__( 'List Extra Large', 'streamtube-core' )
                    )
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

        $this->start_controls_section(
            'section-datasource',
            array(
                'label'     =>  esc_html__( 'Data Source', 'streamtube-core' ),
                'tab'       =>  \Elementor\Controls_Manager::TAB_CONTENT
            )
        );

            $this->add_control(
                'post_type',
                array(
                    'label'     =>  esc_html__( 'Post Types', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SELECT2,
                    'default'   =>  'video',
                    'multiple'  =>  true,
                    'options'   =>  $this->get_post_types()
                )
            );
        $this->end_controls_section();
    }

    protected function content_template() {
    }

    public function render_plain_content( $instance = array() ) {
    }

    protected function render(){

        if( ! function_exists( 'WPPL' ) ){
            return;
        }

        echo WPPL()->get()->public->the_liked_posts( $this->get_settings_for_display() );
    }
}

if( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION , '3.5.0', '<' ) ){
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Streamtube_Core_Shortcode_Liked_Posts_Elementor() );
}
else{
    \Elementor\Plugin::instance()->widgets_manager->register( new Streamtube_Core_Shortcode_Liked_Posts_Elementor() );
}