<?php
/**
 * Define the Youtube Importer functionality
 *
 *
 * @link       https://themeforest.net/user/phpface
 * @since      2.0
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

class StreamTube_Core_Youtube_Importer_Post_Type{
    /**
     *
     * Define advertising admin menu slug
     *
     * @since 2.0
     * 
     */
    const POST_TYPE   = 'youtube_importer';

    /**
     *
     * Register post type
     * 
     */
    public function post_type(){
        $labels = array(
            'name'                                  => esc_html__( 'YouTube Importers', 'streamtube-core' ),
            'singular_name'                         => esc_html__( 'YouTube Importer', 'streamtube-core' ) 
        );

        $args = array(
            'label'                                 => esc_html__( 'YouTube Importer', 'streamtube-core' ),
            'labels'                                => $labels,
            'description'                           => '',
            'public'                                => false,
            'publicly_queryable'                    => true,
            'show_ui'                               => true,
            'show_in_rest'                          => false,
            'rest_base'                             => '',
            'rest_controller_class'                 => 'WP_REST_Posts_Controller',
            'has_archive'                           => false,
            'show_in_menu'                          => 'edit.php?post_type=video',
            'show_in_nav_menus'                     => false,
            'delete_with_user'                      => false,
            'exclude_from_search'                   => false,
            'capability_type'                       => 'post',
            'map_meta_cap'                          => true,
            'hierarchical'                          => false,
            'rewrite'                               => array( 
                'slug'          =>  self::POST_TYPE, 
                'with_front'    =>  true 
            ),
            'query_var'                             => true,
            'supports'                              =>  array( 
                'title'
            )
        );

        register_post_type( self::POST_TYPE, $args );
    }
}