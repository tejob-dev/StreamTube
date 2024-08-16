<?php
/**
 * Define the custom posts elementor widget functionality
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

class Streamtube_Core_Video_Playlist_Elementor extends \Elementor\Widget_Base{

    public function get_name(){
        return 'streamtube-playlist';
    }

    public function get_title(){
        return esc_html__( 'Playlist', 'streamtube-core' );
    }

    public function get_icon(){
        return 'eicon-video-playlist';
    }

    public function get_keywords(){
        return array( 'streamtube', 'playlist' );
    }

    public function get_categories(){
        return array( 'streamtube' );
    }

    private function get_term_options( $terms ){
        $options = array();

        if( ! $terms ){
            return $options;
        }

        foreach( $terms as $term ){
            $options[ $term->slug ] = $term->name;
        }

        return $options;
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
                'style',
                array(
                    'label'     =>  esc_html__( 'Style', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SELECT,
                    'default'   =>  'light',
                    'options'   =>  array(
                        'dark'  =>  esc_html__( 'Dark', 'streamtube-core' ),
                        'light'  =>  esc_html__( 'Light', 'streamtube-core' )
                    )
                )
            );            

            $this->add_control(
                'ratio',
                array(
                    'label'     =>  esc_html__( 'Player Aspect Ratio', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SELECT,
                    'default'   =>  '16x9',
                    'options'       =>  array(
                        '21x9'  =>  esc_html__( '21x9', 'streamtube-core' ),
                        '16x9'  =>  esc_html__( '16x9', 'streamtube-core' ),
                        '4x3'   =>  esc_html__( '4x3', 'streamtube-core' ),
                        '1x1'   =>  esc_html__( '1x1', 'streamtube-core' )
                    )
                )
            );                 

            $this->add_control(
                'posts_per_page',
                array(
                    'label'     =>  esc_html__( 'Posts Per List', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::NUMBER,
                    'default'   =>  10
                )
            );  

            $this->add_control(
                'author_name',
                array(
                    'label'     =>  esc_html__( 'Author Name', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SWITCHER,
                    'default'   =>  'yes',
                    'description'   =>  esc_html__( 'Show post author name', 'streamtube-core' )
                )
            ); 

            $this->add_control(
                'post_date',
                array(
                    'label'     =>  esc_html__( 'Post Date', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SWITCHER,
                    'default'   =>  '',
                    'description'   =>  esc_html__( 'Show post date', 'streamtube-core' )
                )
            );

            $this->add_control(
                'post_comment',
                array(
                    'label'     =>  esc_html__( 'Post Comment', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SWITCHER,
                    'default'   =>  '',
                    'description'   =>  esc_html__( 'Show post comment', 'streamtube-core' )
                )
            );            

            $this->add_control(
                'upnext',
                array(
                    'label'     =>  esc_html__( 'Auto Up Next', 'streamtube-core' ),
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
                    'label'     =>  esc_html__( 'List Layout', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SELECT,
                    'default'   =>  'list_md',
                    'options'   =>  array(
                        'grid'      =>  esc_html__( 'Grid', 'streamtube-core' ),
                        'list_xs'   =>  esc_html__( 'List Extra Small', 'streamtube-core' ),
                        'list_sm'   =>  esc_html__( 'List Small', 'streamtube-core' ),
                        'list_md'   =>  esc_html__( 'List Medium', 'streamtube-core' )
                    )
                )
            );

            $this->add_control(
                'container',
                array(
                    'label'     =>  esc_html__( 'Container', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SELECT,
                    'default'   =>  'container',
                    'options'   =>  array(
                        'container'                 =>  esc_html__( 'Boxed', 'streamtube-core' ),
                        'container container-wide'  =>  esc_html__( 'Wide', 'streamtube-core' ),
                        'container-fluid'           =>  esc_html__( 'Fullwidth', 'streamtube-core' )
                    )
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

            if( is_post_type_viewable( 'video' )){
                $taxonomies = get_object_taxonomies( 'video', 'object' );

                if( $taxonomies ){

                    foreach ( $taxonomies as $tax => $object ){

                        $terms = get_terms( array(
                            'taxonomy'      =>  $tax,
                            'hide_empty'    =>  false
                        ) );                            

                        $this->add_control(
                            'tax_query_' . $tax,
                            array(
                                'label'     =>  $object->label,
                                'type'      =>  \Elementor\Controls_Manager::SELECT2,
                                'multiple'  =>  true,
                                'default'   =>  '',
                                'options'   =>  $this->get_term_options( $terms )
                            )
                        );

                    }
                }
            }        

            $this->add_control(
                'search',
                array(
                    'label'     =>  esc_html__( 'Keyword', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::TEXT,
                    'default'   =>  '',
                    'description'   =>  esc_html__( 'Show posts based on a keyword search', 'streamtube' )
                )
            );            

        $this->end_controls_section();

        $this->start_controls_section(
            'section-order',
            array(
                'label'     =>  esc_html__( 'Order', 'streamtube-core' ),
                'tab'       =>  \Elementor\Controls_Manager::TAB_CONTENT
            )
        );

            $this->add_control(
                'orderby',
                array(
                    'label'     =>  esc_html__( 'Order by', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SELECT,
                    'default'   =>  'date',
                    'options'   =>  array(
                        'none'              =>  esc_html__( 'None', 'streamtube-core' ),
                        'ID'                =>  esc_html__( 'Order by post id.', 'streamtube-core' ),
                        'author'            =>  esc_html__( 'Order by author', 'streamtube-core' ),
                        'title'             =>  esc_html__( 'Order by post title', 'streamtube-core' ),
                        'name'              =>  esc_html__( 'Order by post slug', 'streamtube-core' ),
                        'date'              =>  esc_html__( 'Order by date (default)', 'streamtube-core' ),
                        'modified'          =>  esc_html__( 'Order by last modified date.', 'streamtube-core' ),
                        'rand'              =>  esc_html__( 'Random order', 'streamtube-core' ),
                        'comment_count'     =>  esc_html__( 'Order by number of comments', 'streamtube-core' ),
                        'relevance'         =>  esc_html__( 'Relevance', 'streamtube-core' )
                    )
                )
            );

            $this->add_control(
                'order',
                array(
                    'label'     =>  esc_html__( 'Order', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SELECT,
                    'default'   =>  'DESC',
                    'options'   =>  array(
                        'ASC'               =>  esc_html__( 'Ascending', 'streamtube-core' ),
                        'DESC'              =>  esc_html__( 'Descending (default).', 'streamtube-core' )
                    )
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

        echo streamtube_core()->get()->shortcode->_playlist( $settings );
    }    
}

if( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION , '3.5.0', '<' ) ){
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Streamtube_Core_Video_Playlist_Elementor() );
}
else{
    \Elementor\Plugin::instance()->widgets_manager->register( new Streamtube_Core_Video_Playlist_Elementor() );
}