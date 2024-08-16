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

class Streamtube_Core_Widget_Posts_Elementor extends \Elementor\Widget_Base{

    public function get_name(){
        return 'streamtube_posts_elementor';
    }

    public function get_title(){
        return esc_html__( 'Post List', 'streamtube-core' );
    }

    public function get_icon(){
        return 'eicon-video-playlist';
    }

    public function get_keywords(){
        return array( 'streamtube', 'posts' );
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
                'title',
                array(
                    'label'     =>  esc_html__( 'Title', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::TEXT
                )
            );

            $this->add_control(
                '_id',
                array(
                    'label'     =>  esc_html__( 'Unique Widget ID', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::TEXT
                )
            ); 

            $this->add_control(
                'icon',
                array(
                    'label'     =>  esc_html__( 'Icon', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::TEXT,
                    'description'   =>  esc_html__( 'Enter an icon class name', 'streamtube-core' )
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
                    'options'   =>  Streamtube_Core_Widget_Posts::get_pagination_types()
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
                'more_link',
                array(
                    'label'     =>  esc_html__( 'Show view more link', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SWITCHER,
                    'default'   =>  ''
                )
            );

            $this->add_control(
                'more_link_url',
                array(
                    'label'     =>  esc_html__( 'Custom view more link', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::TEXT,
                    'default'   =>  '',
                    'description'   =>  esc_html__( 'e.g: http://domain.com/latest-posts', 'streamtube-core' ),
                    'condition' =>  array(
                        'more_link'  =>  'yes'
                    )                    
                )
            );

            $this->add_control(
                'show_post_date',
                array(
                    'label'     =>  esc_html__( 'Show post date', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SELECT,
                    'default'   =>  'normal',
                    'options'   =>  Streamtube_Core_Widget_Posts::get_date_formats()
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
                    'options'   =>  Streamtube_Core_Widget_Posts::get_avatar_sizes(),
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
                'thumbnail_size',
                array(
                    'label'     =>  esc_html__( 'Thumbnail Image Size', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SELECT,
                    'default'   =>  get_option( 'thumbnail_size', 'streamtube-image-medium' ),
                    'options'   =>  streamtube_core_get_thumbnail_sizes(),
                    'condition' =>  array(
                        'hide_thumbnail'    =>  ''
                    )   
                )
            );                        

            $this->add_control(
                'thumbnail_ratio',
                array(
                    'label'     =>  esc_html__( 'Thumbnail Image Ratio', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SELECT,
                    'default'   =>  get_option( 'thumbnail_ratio', '16x9' ),
                    'options'   =>  Streamtube_Core_Widget_Posts::get_image_ratio(),
                    'condition' =>  array(
                        'hide_thumbnail'    =>  ''
                    )   
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

           $this->add_control(
                'hide_duplicate_posts',
                array(
                    'label'     =>  esc_html__( 'Hide Duplicate Posts', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SWITCHER,
                    'default'   =>  '',
                    'description'   =>  esc_html__( 'Do not retrieve duplicate posts from other widgets', 'streamtube-core' )
                )
            );                 

        $this->end_controls_section();

        $this->start_controls_section(
            'section-visibility',
            array(
                'label'     =>  esc_html__( 'Visibility', 'streamtube-core' ),
                'tab'       =>  \Elementor\Controls_Manager::TAB_CONTENT
            )
        );

            $this->add_control(
                'hide_if_empty',
                array(
                    'label'     =>  esc_html__( 'Hide widget if no posts found', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SWITCHER,
                    'default'   =>  ''
                )
            );

            $this->add_control(
                'hide_if_not_logged_in',
                array(
                    'label'     =>  esc_html__( 'Hide widget if user is not logged in', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SWITCHER,
                    'default'   =>  ''
                )
            );

            $this->add_control(
                'hide_if_not_author',
                array(
                    'label'     =>  esc_html__( 'Hide widget if current logged in user is not post/profile owner', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SWITCHER,
                    'default'   =>  ''
                )
            );

            $this->add_control(
                'show_if_user_can_cap',
                array(
                    'label'     =>  esc_html__( 'Show widget if current user has specific capability', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::TEXT,
                    'default'   =>  ''
                )
            );

            $this->add_control(
                'show_if_user_in_roles',
                array(
                    'label'     =>  esc_html__( 'Show widget if current user is in specific roles', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SELECT2,
                    'default'   =>  '',
                    'multiple'  =>  true,
                    'options'   =>  streamtube_get_get_role_options()
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
                    'options'   =>  Streamtube_Core_Widget_Posts::get_layouts()
                )
            );

            $this->add_control(
                'index_number',
                array(
                    'label'     =>  esc_html__( 'Show Index Numbers', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SWITCHER,
                    'default'   =>  '',
                    'condition' =>  array(
                        'layout!'    =>  'grid'
                    )                    
                )
            );

            $this->add_control(
                'center_align_items',
                array(
                    'label'     =>  esc_html__( 'Align Items Center', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SWITCHER,
                    'default'   =>  '',
                    'condition' =>  array(
                        'layout!'    =>  'grid'
                    )                    
                )
            );            

            $this->add_control(
                'title_size',
                array(
                    'label'     =>  esc_html__( 'Title Size', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SELECT,
                    'default'   =>  '',
                    'options'   =>  array(
                        ''      =>  esc_html__( 'Default', 'streamtube-core' ),
                        'md'    =>  esc_html__( 'Medium', 'streamtube-core' ),
                        'lg'    =>  esc_html__( 'Large', 'streamtube-core' ),
                        'xl'    =>  esc_html__( 'Extra Large', 'streamtube-core' ),
                        'xxl'   =>  esc_html__( 'Extra Extra Large', 'streamtube-core' )
                    ),
                    'description'   =>  esc_html__( 'Post Title font size', 'streamtube-core' )
                )
            );             

            $this->add_control(
                'margin',
                array(
                    'label'     =>  esc_html__( 'Margin', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SWITCHER,
                    'default'   =>  'yes',
                    'description'   =>  esc_html__( 'Enable margin between items', 'streamtube-core' )
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
                'overlay',
                array(
                    'label'     =>  esc_html__( 'Overlay', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SWITCHER,
                    'default'   =>  '',
                    'condition' =>  array(
                        'layout'    =>  'grid'
                    )
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

        $this->start_controls_section(
            'section-slide',
            array(
                'label'     =>  esc_html__( 'Slide', 'streamtube-core' ),
                'tab'       =>  \Elementor\Controls_Manager::TAB_CONTENT
            )
        );  

            $this->add_control(
                'slide',
                array(
                    'label'     =>  esc_html__( 'Sliding', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SWITCHER,
                    'default'   =>  '',
                    'description'   =>  esc_html__( 'Enable sliding', 'streamtube-core' )
                )
            );

            $this->add_control(
                'slide_rows',
                array(
                    'label'     =>  esc_html__( 'Rows', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::NUMBER,
                    'default'   =>  '1'
                )
            );  

           $this->add_control(
                'slide_dots',
                array(
                    'label'     =>  esc_html__( 'Dots', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SWITCHER,
                    'default'   =>  '',
                    'description'   =>  esc_html__( 'Show dot indicators', 'streamtube-core' )
                )
            );

           $this->add_control(
                'slide_arrows',
                array(
                    'label'     =>  esc_html__( 'Arrows', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SWITCHER,
                    'default'   =>  '',
                    'description'   =>  esc_html__( 'Show Prev/Next Arrows', 'streamtube-core' )
                )
            );

           $this->add_control(
                'slide_center_mode',
                array(
                    'label'     =>  esc_html__( 'Center mode', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SWITCHER,
                    'default'   =>  '',
                    'description'   =>  esc_html__( 'Enables centered view with partial prev/next slides', 'streamtube-core' )
                )
            );

           $this->add_control(
                'slide_infinite',
                array(
                    'label'     =>  esc_html__( 'Infinite', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SWITCHER,
                    'default'   =>  '',
                    'description'   =>  esc_html__( 'Infinite Loop Sliding', 'streamtube-core' )
                )
            );           

           $this->add_control(
                'slide_speed',
                array(
                    'label'     =>  esc_html__( 'Speed', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::NUMBER,
                    'default'   =>  '2000',
                    'description'   =>  esc_html__( 'Slide Animation Speed', 'streamtube-core' )
                )
            );           

           $this->add_control(
                'slide_autoplay',
                array(
                    'label'     =>  esc_html__( 'Autoplay', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SWITCHER,
                    'default'   =>  '',
                    'description'   =>  esc_html__( 'Enables Autoplay', 'streamtube-core' )
                )
            );

           $this->add_control(
                'slide_autoplaySpeed',
                array(
                    'label'     =>  esc_html__( 'Autoplay Speed', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::NUMBER,
                    'default'   =>  '2000',
                    'description'   =>  esc_html__( 'Autoplay Speed in milliseconds', 'streamtube-core' )
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
                'upcoming_posts',
                array(
                    'label'     =>  esc_html__( 'Upcoming', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SWITCHER,
                    'default'   =>  '',
                    'description'   =>  esc_html__( 'Retrieve upcoming posts.', 'streamtube-core' )
                )
            );

           $this->add_control(
                'live_stream',
                array(
                    'label'     =>  esc_html__( 'Live Stream', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SWITCHER,
                    'default'   =>  '',
                    'description'   =>  esc_html__( 'Retrieve Live Streams', 'streamtube-core' )
                )
            );

           $this->add_control(
                'live_status',
                array(
                    'label'     =>  esc_html__( 'Live Status', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SELECT2,
                    'default'   =>  array( 'connected' ),
                    'condition' =>  array(
                        'live_stream' =>  'yes'
                    ),                    
                    'multiple'  =>  true,
                    'options'   =>  Streamtube_Core_Widget_Posts::get_live_statuses()
                )
            );                      

            $this->add_control(
                'post_type',
                array(
                    'label'     =>  esc_html__( 'Post Type', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SELECT,
                    'default'   =>  'video',
                    'options'   =>  Streamtube_Core_Widget_Posts::get_post_types()
                )
            );

            foreach( Streamtube_Core_Widget_Posts::get_post_types() as $post_type => $post_type_label ){
                if( is_post_type_viewable( $post_type )){
                    $taxonomies = get_object_taxonomies( $post_type, 'object' );

                    if( $taxonomies ){

                        foreach ( $taxonomies as $tax => $object ){

                            $terms = Streamtube_Core_Widget_Posts::get_terms( $tax );

                            if( $terms ){
                                $this->add_control(
                                    'tax_query_' . $tax,
                                    array(
                                        'label'     =>  sprintf( esc_html__( 'Inc %s', 'streamtube-core' ), $object->label ),
                                        'type'      =>  \Elementor\Controls_Manager::SELECT2,
                                        'multiple'  =>  true,
                                        'default'   =>  '',
                                        'condition' =>  array(
                                            'post_type' =>  $post_type
                                        ),
                                        'options'   =>  $this->get_term_options( $terms ),
                                        'description'   =>  sprintf( esc_html__( 'Include %s terms', 'streamtube-core' ), $object->label )
                                    )
                                );

                                $this->add_control(
                                    'exclude_tax_query_' . $tax,
                                    array(
                                        'label'     =>  sprintf( esc_html__( 'Exc %s', 'streamtube-core' ), $object->label ),
                                        'type'      =>  \Elementor\Controls_Manager::SELECT2,
                                        'multiple'  =>  true,
                                        'default'   =>  '',
                                        'condition' =>  array(
                                            'post_type' =>  $post_type
                                        ),
                                        'options'   =>  $this->get_term_options( $terms ),
                                        'description'   =>  sprintf( esc_html__( 'Exclude %s terms', 'streamtube-core' ), $object->label )
                                    )
                                );
                            }
                        }
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

            $this->add_control(
                'post_status',
                array(
                    'label'     =>  esc_html__( 'Status', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SELECT2,
                    'multiple'  =>  true,
                    'default'   =>  array( 'publish' ),
                    'options'   =>  Streamtube_Core_Widget_Posts::get_post_statuses()
                )
            );
          
        $this->end_controls_section();

        $this->start_controls_section(
            'section-comment',
            array(
                'label'     =>  esc_html__( 'Comment', 'streamtube-core' ),
                'tab'       =>  \Elementor\Controls_Manager::TAB_CONTENT
            )
        );

            $this->add_control(
                'comment_count',
                array(
                    'label'     =>  esc_html__( 'Comment Count', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::NUMBER,
                    'default'   =>  '',
                    'description'   =>  esc_html__( 'Retrieve posts with with given comment count', 'streamtube-core' )
                )
            );

            $this->add_control(
                'comment_compare',
                array(
                    'label'     =>  esc_html__( 'Comment Compare', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::TEXT,
                    'default'   =>  '',
                    'description'   =>  esc_html__( 'Possible values are ‘=’, ‘!=’, ‘>’, ‘>=’, ‘<‘, ‘<=’', 'streamtube-core' )
                )
            );            

        $this->end_controls_section();

        $this->start_controls_section(
            'section-date',
            array(
                'label'     =>  esc_html__( 'Date', 'streamtube-core' ),
                'tab'       =>  \Elementor\Controls_Manager::TAB_CONTENT
            )
        );

            $this->add_control(
                'date_after',
                array(
                    'label'     =>  esc_html__( 'Date After', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::TEXT,
                    'default'   =>  '',
                    'placeholder'   =>  esc_html__( 'e.g: 1 month ago', 'streamtube-core' ),
                    'description'   =>  esc_html__( 'Date to retrieve posts after', 'streamtube-core' )                    
                )
            );        

            $this->add_control(
                'date_before',
                array(
                    'label'         =>  esc_html__( 'Date Before', 'streamtube-core' ),
                    'type'          =>  \Elementor\Controls_Manager::TEXT,
                    'default'       =>  '',
                    'placeholder'   =>  esc_html__( 'e.g: 1 year ago', 'streamtube-core' ),
                    'description'   =>  esc_html__( 'Date to retrieve posts before', 'streamtube-core' )
                )
            );

            $this->add_control(
                'date',
                array(
                    'label'     =>  esc_html__( 'Date Range', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::TEXT,
                    'default'   =>  '',
                    'placeholder'   =>  esc_html__( 'e.g: this_week', 'streamtube-core' ),
                    'description'   =>  sprintf(
                    esc_html__( 'Specify the date range to retrieve posts from, defined values are: %s', 'streamtube-core' ),
                    '<strong>'. join(', ', array_keys( Streamtube_Core_Widget_Filter_Post_Date::get_options() ) ) .'</strong>'
                )               
                )
            );

            $this->add_control(
                'date_modified',
                array(
                    'label'     =>  esc_html__( 'Retrieve modified date', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SWITCHER,
                    'default'   =>  ''          
                )
            );

        $this->end_controls_section();

        $this->start_controls_section(
            'section-user',
            array(
                'label'     =>  esc_html__( 'User', 'streamtube-core' ),
                'tab'       =>  \Elementor\Controls_Manager::TAB_CONTENT
            )
        );

            $this->add_control(
                'groupby_author',
                array(
                    'label'     =>  esc_html__( 'Group By Author', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SWITCHER,
                    'default'   =>  ''
                )
            );        

            $this->add_control(
                'author__in',
                array(
                    'label'         =>  esc_html__( 'Include Users', 'streamtube-core' ),
                    'type'          =>  \Elementor\Controls_Manager::TEXT,
                    'default'       =>  '',
                    'placeholder'   =>  esc_html__( 'E.g: 2,5,7,10', 'streamtube-core' ),
                    'description'   =>  esc_html__( 'Retrieve posts from specific users, separated by commas', 'streamtube-core' )
                )
            );

            $this->add_control(
                'author__not_in',
                array(
                    'label'         =>  esc_html__( 'Exclude Users', 'streamtube-core' ),
                    'type'          =>  \Elementor\Controls_Manager::TEXT,
                    'default'       =>  '',
                    'placeholder'   =>  esc_html__( 'E.g: 1,3,6,8', 'streamtube-core' ),
                    'description'   =>  esc_html__( 'Exclude posts from specific users, separated by commas', 'streamtube-core' )
                )
            );             

            $this->add_control(
                'verified_users_only',
                array(
                    'label'     =>  esc_html__( 'Verified Users', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SWITCHER,
                    'default'   =>  '',
                    'description'   =>  esc_html__( 'Retrieve posts from verified users only', 'streamtube-core' )
                )
            );        

            $this->add_control(
                'current_logged_in',
                array(
                    'label'     =>  esc_html__( 'Current Logged In User', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SWITCHER,
                    'default'   =>  '',
                    'description'   =>  esc_html__( 'Retrieve posts from current logged in user', 'streamtube-core' )
                )
            );

            if( function_exists( 'WPPL' ) ){
                $this->add_control(
                    'current_logged_in_like',
                    array(
                        'label'     =>  esc_html__( 'Current Logged In User\'s Liked Posts', 'streamtube-core' ),
                        'type'      =>  \Elementor\Controls_Manager::SWITCHER,
                        'default'   =>  '',
                        'description'   =>  esc_html__( 'Retrieve liked posts from current logged in user', 'streamtube-core' )
                    )
                );
            }

            if( function_exists( 'run_wp_user_follow' ) ){
                $this->add_control(
                    'current_logged_in_following',
                    array(
                        'label'     =>  esc_html__( 'Current Logged-in User\'s Following', 'streamtube-core' ),
                        'type'      =>  \Elementor\Controls_Manager::SWITCHER,
                        'default'   =>  '',
                        'description'   =>  esc_html__( 'Retrieve posts from current logged-in user\'s following', 'streamtube-core' )
                    )
                );

                $this->add_control(
                    'current_logged_in_follower',
                    array(
                        'label'     =>  esc_html__( 'Current Logged-in User\'s Followers', 'streamtube-core' ),
                        'type'      =>  \Elementor\Controls_Manager::SWITCHER,
                        'default'   =>  '',
                        'description'   =>  esc_html__( 'Retrieve posts from current logged-in user\'s followers', 'streamtube-core' )
                    )
                );                 
            }

            $this->add_control(
                'current_logged_in_history',
                array(
                    'label'     =>  esc_html__( 'Current Logged In User\'s History', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SWITCHER,
                    'default'   =>  '',
                    'description'   =>  esc_html__( 'Retrieve posts from current logged in user\'s history', 'streamtube-core' )
                )
            );

            $this->add_control(
                'current_logged_in_watch_later',
                array(
                    'label'     =>  esc_html__( 'Current Logged In User\'s Watch Later', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SWITCHER,
                    'default'   =>  '',
                    'description'   =>  esc_html__( 'Retrieve posts from current logged in user\'s Watch Later', 'streamtube-core' )
                )
            );

            $this->add_control(
                'current_author',
                array(
                    'label'     =>  esc_html__( 'Current Author', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SWITCHER,
                    'default'   =>  '',
                    'description'   =>  esc_html__( 'Retrieve posts from current author', 'streamtube-core' )
                )
            );

            if( function_exists( 'WPPL' ) ){
                $this->add_control(
                    'current_author_like',
                    array(
                        'label'     =>  esc_html__( 'Current Author\'s Liked Posts', 'streamtube-core' ),
                        'type'      =>  \Elementor\Controls_Manager::SWITCHER,
                        'default'   =>  '',
                        'description'   =>  esc_html__( 'Retrieve liked posts from current author', 'streamtube-core' )
                    )
                );
            }            

            if( function_exists( 'run_wp_user_follow' ) ):

                $this->add_control(
                    'current_author_following',
                    array(
                        'label'     =>  esc_html__( 'Current Author\'s Following', 'streamtube-core' ),
                        'type'      =>  \Elementor\Controls_Manager::SWITCHER,
                        'default'   =>  '',
                        'description'   =>  esc_html__( 'Retrieve posts from current author\'s following', 'streamtube-core' )
                    )
                );

                $this->add_control(
                    'current_author_follower',
                    array(
                        'label'     =>  esc_html__( 'Current Author\'s Follower', 'streamtube-core' ),
                        'type'      =>  \Elementor\Controls_Manager::SWITCHER,
                        'default'   =>  '',
                        'description'   =>  esc_html__( 'Retrieve posts from current author\'s followers', 'streamtube-core' )
                    )
                );
            endif;

                $this->add_control(
                    'current_author_history',
                    array(
                        'label'     =>  esc_html__( 'Current Author\'s History', 'streamtube-core' ),
                        'type'      =>  \Elementor\Controls_Manager::SWITCHER,
                        'default'   =>  '',
                        'description'   =>  esc_html__( 'Retrieve posts from current author\'s History', 'streamtube-core' )
                    )
                );

                $this->add_control(
                    'current_author_watch_later',
                    array(
                        'label'     =>  esc_html__( 'Current Author\'s Watch Later', 'streamtube-core' ),
                        'type'      =>  \Elementor\Controls_Manager::SWITCHER,
                        'default'   =>  '',
                        'description'   =>  esc_html__( 'Retrieve posts from current author\'s Watch Later', 'streamtube-core' )
                    )
                );               
    
        $this->end_controls_section();

        $this->start_controls_section(
            'section-role',
            array(
                'label'     =>  esc_html__( 'Role', 'streamtube-core' ),
                'tab'       =>  \Elementor\Controls_Manager::TAB_CONTENT
            )
        );

            $this->add_control(
                'role__in',
                array(
                    'label'     =>  esc_html__( 'Retrieve posts from specific roles', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SELECT2,
                    'default'   =>  '',
                    'multiple'  =>  true,
                    'options'   =>  streamtube_get_get_role_options()
                )
            );

            $this->add_control(
                'role__not_in',
                array(
                    'label'     =>  esc_html__( 'Exclude posts from specific roles', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SELECT2,
                    'default'   =>  '',
                    'multiple'  =>  true,
                    'options'   =>  streamtube_get_get_role_options()
                )
            );             


        $this->end_controls_section();


        if( function_exists( 'WC' ) ){

            $_products  = array();
            $products   = streamtube_core_wc_get_products();

            if( $products ){

                $_products[1] = esc_html__( 'All', 'streamtube-core' );

                foreach ( $products as $product ) {
                    $_products[ $product->get_ID() ] = sprintf( 
                        '(#%1$s) %2$s (%3$s)',
                        esc_attr( $product->get_ID() ),
                        esc_html( $product->get_name() ),
                        esc_html( wp_strip_all_tags($product->get_price_html()) ) 
                    );
                }
            }

            $this->start_controls_section(
                'section-woocommerce',
                array(
                    'label'     =>  esc_html__( 'Woocommerce', 'streamtube-core' ),
                    'tab'       =>  \Elementor\Controls_Manager::TAB_CONTENT
                )
            );

                $this->add_control(
                    'ref_products',
                    array(
                        'label'     =>  esc_html__( 'Relevant Products', 'streamtube-core' ),
                        'type'      =>  \Elementor\Controls_Manager::SELECT2,
                        'default'   =>  '',
                        'multiple'  =>  true,
                        'options'   =>  $_products
                    )
                );       


            $this->end_controls_section();
        }

        if( function_exists( 'pmpro_activation' ) ){
            $this->start_controls_section(
                'section-pmp',
                array(
                    'label'     =>  esc_html__( 'Paid Memberships Pro', 'streamtube-core' ),
                    'tab'       =>  \Elementor\Controls_Manager::TAB_CONTENT
                )
            );

                $this->add_control(
                    'level_type',
                    array(
                        'label'     =>  esc_html__( 'Level Type', 'streamtube-core' ),
                        'type'      =>  \Elementor\Controls_Manager::SELECT2,
                        'default'   =>  '',
                        'multiple'  =>  false,
                        'options'   =>  array_merge( array(
                            ''  =>  esc_html__( 'None', 'streamtube-core' )
                        ), streamtube_core_get_pmp_level_type_options() )
                    )
                );

                $this->add_control(
                    'level__in',
                    array(
                        'label'     =>  esc_html__( 'Include Levels', 'streamtube-core' ),
                        'type'      =>  \Elementor\Controls_Manager::SELECT2,
                        'default'   =>  array(),
                        'multiple'  =>  true,
                        'options'   =>  streamtube_core_get_pmp_levels_options()
                    )
                );

                $this->add_control(
                    'level__not_in',
                    array(
                        'label'     =>  esc_html__( 'Exclude Levels', 'streamtube-core' ),
                        'type'      =>  \Elementor\Controls_Manager::SELECT2,
                        'default'   =>  array(),
                        'multiple'  =>  true,
                        'options'   =>  streamtube_core_get_pmp_levels_options()
                    )
                );                 


            $this->end_controls_section();
        }

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
                    'options'   =>  streamtube_core_get_orderby_options()
                )
            );

            $this->add_control(
                'order',
                array(
                    'label'     =>  esc_html__( 'Order', 'streamtube-core' ),
                    'type'      =>  \Elementor\Controls_Manager::SELECT,
                    'default'   =>  'DESC',
                    'options'   =>  Streamtube_Core_Widget_Posts::get_order()
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

        the_widget( 'Streamtube_Core_Widget_Posts', $instance, array(
            'before_widget' => '<div class="widget widget-elementor posts-widget streamtube-widget">',
            'after_widget'  => '</div>',
            'before_title'  => '<div class="widget-title-wrap"><h2 class="widget-title d-flex align-items-center">',
            'after_title'   => '</h2></div>'
        ) );
    }
}

if( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION , '3.5.0', '<' ) ){
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Streamtube_Core_Widget_Posts_Elementor() );
}
else{
    \Elementor\Plugin::instance()->widgets_manager->register( new Streamtube_Core_Widget_Posts_Elementor() );
}