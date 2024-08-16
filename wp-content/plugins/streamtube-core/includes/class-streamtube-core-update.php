<?php

/**
 *
 * @link       https://themeforest.net/user/phpface
 * @since      1.0.0
 *
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */

if( ! defined('ABSPATH' ) ){
    exit;
}

class Streamtube_Core_Update {

    /**
     *
     * Add default widgets
     * 
     */
    public static function add_default_widgets(){

        self::add_advanced_search_widgets();

        self::add_profile_home_widgets();

        self::add_profile_videos_widgets();

        self::add_profile_shorts_widgets();

        self::add_profile_collections_widgets();

        self::add_profile_post_widgets();

        self::add_profile_liked_widgets();

        self::add_profile_following_widgets();

        self::add_profile_followers_widgets();
    }

    public static function add_advanced_search_widgets(){

        if( is_active_sidebar( 'advanced-search' ) || ! wp_get_sidebar( 'advanced-search' ) ){
            return;
        }

        $widgets    = array();

        $widgets[]  = array(
            'id'        =>  'filter-content-type-widget',
            'data'      =>  array(
                'content_types'     =>  array(
                    'video', 'post', 'collection', 'user'
                ),
                'fullwidth'         =>  'on',
                'list_type'         =>  'cloud'
            )
        );

        $widgets[]  = array(
            'id'        =>  'filter-content-cost-widget',
            'data'      =>  array(
                'fullwidth' =>  'on',
                'list_type' =>  'cloud'
            )
        );

        $widgets[]  = array(
            'id'        =>  'filter-pmp-widget',
            'data'      =>  array(
                'fullwidth' =>  'on',
                'list_type' =>  'cloud'
            )
        );

        $widgets[]  = array(
            'id'        =>  'filter-taxonomy-widget',
            'data'      =>  array(
                'title'     =>  esc_html__( 'Video Categories', 'streamtube-core' ),
                'taxonomy'  =>  'categories',
                'fullwidth' =>  'on',
                'list_type' =>  'cloud',
                'multiple'  =>  'on'
            )
        );

        $widgets[]  = array(
            'id'        =>  'filter-taxonomy-widget',
            'data'      =>  array(
                'title'     =>  esc_html__( 'Video Tags', 'streamtube-core' ),
                'taxonomy'  =>  'video_tag',
                'number'    =>  20,
                'count'     =>  'on',
                'fullwidth' =>  'on',
                'list_type' =>  'cloud',
                'multiple'  =>  'on'
            )
        );        

        $widgets[]  = array(
            'id'        =>  'filter-taxonomy-widget',
            'data'      =>  array(
                'title'     =>  esc_html__( 'Blog Categories', 'streamtube-core' ),
                'taxonomy'  =>  'category',
                'fullwidth' =>  'on',
                'list_type' =>  'cloud',
                'multiple'  =>  'on'
            )
        );

        $widgets[]  = array(
            'id'        =>  'filter-taxonomy-widget',
            'data'      =>  array(
                'title'     =>  esc_html__( 'Blog Tags', 'streamtube-core' ),
                'taxonomy'  =>  'post_tag',
                'number'    =>  20,
                'count'     =>  'on',
                'fullwidth' =>  'on',
                'list_type' =>  'cloud',
                'multiple'  =>  'on'
            )
        );

        $widgets[]  = array(
            'id'        =>  'filter-post-date-widget',
            'data'      =>  array(
                'list_type' =>  'list',
                'options'   =>  array(
                    'today', '7daysago', '30daysago', 'this_month', 'this_year'
                )
            )
        );

        $widgets[]  = array(
            'id'        =>  'filter-sortby-widget',
            'data'      =>  array(
                'list_type' =>  'list'
            )
        );

        for ( $i = 0;  $i < count( $widgets );  $i++) { 

            $widget_id      = $widgets[$i]['id'];
            $widget_data    = $widgets[$i]['data'];

            streamtube_core_insert_widget_in_sidebar(
                $widget_id,
                $widget_data,
                'advanced-search'
            );
        }
    }

    public static function add_profile_home_widgets(){

        if( is_active_sidebar( 'sidebar-profile-home' ) || ! wp_get_sidebar( 'sidebar-profile-home' ) ){
            return;
        }

        streamtube_core_insert_widget_in_sidebar( 'posts-widget', array(
            'title'                 =>  esc_html__( 'Latest Uploads', 'streamtube-core' ),
            'post_type'             =>  'video',
            'current_author'        =>  'on',
            'layout'                =>  'grid',
            'hide_empty_thumbnail'  =>  'on',
            'hide_if_empty'         =>  '',
            'not_found_text'        =>  esc_html__( 'No content available', 'streamtube-core' ),
            'pagination'            =>  '',
            'posts_per_page'        =>  8,
            'custom_orderby'        =>  'on',
            'col_xxl'               =>  get_option( 'user_col_xxl', 4 ),
            'col_xl'                =>  get_option( 'user_col_xl', 4 ),
            'col_lg'                =>  get_option( 'user_col_lg', 4 ),
            'col_md'                =>  get_option( 'user_col_md', 2 ),
            'col_sm'                =>  get_option( 'user_col_sm', 2 ),
            'col'                   =>  get_option( 'user_col_xs', 1 )
        ), 'sidebar-profile-home' );

        streamtube_core_insert_widget_in_sidebar( 'term-grid-widget', array(
            'title'                 =>  esc_html__( 'Collections', 'streamtube-core' ),
            'post_type'             =>  'post',
            'public_only'           =>  'on',
            'current_author'        =>  'on',
            'layout'                =>  'playlist',
            'hide_empty_thumbnail'  =>  'on',
            'hide_if_empty'         =>  'on',
            'taxonomy'              =>  'video_collection',
            'number'                =>  4,
            'col_xxl'               =>  get_option( 'user_col_xxl', 4 ),
            'col_xl'                =>  get_option( 'user_col_xl', 4 ),
            'col_lg'                =>  get_option( 'user_col_lg', 4 ),
            'col_md'                =>  get_option( 'user_col_md', 2 ),
            'col_sm'                =>  get_option( 'user_col_sm', 2 ),
            'col'                   =>  get_option( 'user_col_xs', 1 )            
        ), 'sidebar-profile-home' );             

        streamtube_core_insert_widget_in_sidebar( 'posts-widget', array(
            'title'                 =>  esc_html__( 'Blogs', 'streamtube-core' ),
            'post_type'             =>  'post',
            'current_author'        =>  'on',
            'layout'                =>  'grid',
            'hide_empty_thumbnail'  =>  'on',
            'hide_if_empty'         =>  'on',
            'pagination'            =>  '',
            'posts_per_page'        =>  8,
            'custom_orderby'        =>  'on',
            'col_xxl'               =>  get_option( 'user_col_xxl', 4 ),
            'col_xl'                =>  get_option( 'user_col_xl', 4 ),
            'col_lg'                =>  get_option( 'user_col_lg', 4 ),
            'col_md'                =>  get_option( 'user_col_md', 2 ),
            'col_sm'                =>  get_option( 'user_col_sm', 2 ),
            'col'                   =>  get_option( 'user_col_xs', 1 )            
        ), 'sidebar-profile-home' );
    }

    public static function add_profile_videos_widgets(){
  
        if( is_active_sidebar( 'sidebar-profile-videos' ) || ! wp_get_sidebar( 'sidebar-profile-videos' ) ){
            return;
        }

        streamtube_core_insert_widget_in_sidebar( 'posts-widget', array(
            'title'                 =>  '',
            'post_type'             =>  'video',
            'current_author'        =>  'on',
            'layout'                =>  'grid',
            'hide_empty_thumbnail'  =>  'on',
            'hide_if_empty'         =>  'on',
            'pagination'            =>  'click',
            'posts_per_page'        =>  12,
            'custom_orderby'        =>  'on',
            'col_xxl'               =>  get_option( 'user_col_xxl', 4 ),
            'col_xl'                =>  get_option( 'user_col_xl', 4 ),
            'col_lg'                =>  get_option( 'user_col_lg', 4 ),
            'col_md'                =>  get_option( 'user_col_md', 2 ),
            'col_sm'                =>  get_option( 'user_col_sm', 2 ),
            'col'                   =>  get_option( 'user_col_xs', 1 )            
        ), 'sidebar-profile-videos' );
    }

    public static function add_profile_shorts_widgets(){

        if( is_active_sidebar( 'sidebar-profile-shorts' ) || ! wp_get_sidebar( 'sidebar-profile-shorts' ) ){
            return;
        }

        streamtube_core_insert_widget_in_sidebar( 'posts-widget', array(
            'title'                 =>  '',
            'post_type'             =>  'video',
            'current_author'        =>  'on',
            'layout'                =>  'grid',
            'hide_empty_thumbnail'  =>  'on',
            'thumbnail_ratio'       =>  '9x16',
            'thumbnail_size'        =>  'full',
            'hide_if_empty'         =>  'on',
            'pagination'            =>  'click',
            'posts_per_page'        =>  12,
            'custom_orderby'        =>  'on',
            'tax_query_video_tag'   =>  get_option( 'archive_portrait_video_terms', 'short,portrait' ),
            'col_xxl'               =>  get_option( 'user_col_xxl', 4 ),
            'col_xl'                =>  get_option( 'user_col_xl', 4 ),
            'col_lg'                =>  get_option( 'user_col_lg', 4 ),
            'col_md'                =>  get_option( 'user_col_md', 2 ),
            'col_sm'                =>  get_option( 'user_col_sm', 2 ),
            'col'                   =>  get_option( 'user_col_xs', 1 )            
        ), 'sidebar-profile-shorts' );
    }

    public static function add_profile_collections_widgets(){

        if( is_active_sidebar( 'sidebar-profile-collections' ) || ! wp_get_sidebar( 'sidebar-profile-collections' ) ){
            return;
        }

        streamtube_core_insert_widget_in_sidebar( 'term-grid-widget', array(
            'title'                 =>  '',
            'post_type'             =>  'post',
            'current_author'        =>  'on',
            'public_only'           =>  'yes',
            'layout'                =>  'playlist',
            'hide_empty_thumbnail'  =>  'on',
            'hide_if_empty'         =>  'on',
            'taxonomy'              =>  'video_collection',
            'number'                =>  12,
            'pagination'            =>  'click',
            'col_xxl'               =>  get_option( 'user_col_xxl', 4 ),
            'col_xl'                =>  get_option( 'user_col_xl', 4 ),
            'col_lg'                =>  get_option( 'user_col_lg', 4 ),
            'col_md'                =>  get_option( 'user_col_md', 2 ),
            'col_sm'                =>  get_option( 'user_col_sm', 2 ),
            'col'                   =>  get_option( 'user_col_xs', 1 )            
        ), 'sidebar-profile-collections' );
    }

    public static function add_profile_post_widgets(){

        if( is_active_sidebar( 'sidebar-profile-post' ) || ! wp_get_sidebar( 'sidebar-profile-post' ) ){
            return;
        }

        streamtube_core_insert_widget_in_sidebar( 'posts-widget', array(
            'title'                 =>  '',
            'post_type'             =>  'post',
            'current_author'        =>  'on',
            'layout'                =>  'grid',
            'hide_empty_thumbnail'  =>  'on',
            'hide_if_empty'         =>  'on',
            'pagination'            =>  'click',
            'custom_orderby'        =>  'on',
            'posts_per_page'        =>  12,
            'col_xxl'               =>  get_option( 'user_col_xxl', 4 ),
            'col_xl'                =>  get_option( 'user_col_xl', 4 ),
            'col_lg'                =>  get_option( 'user_col_lg', 4 ),
            'col_md'                =>  get_option( 'user_col_md', 2 ),
            'col_sm'                =>  get_option( 'user_col_sm', 2 ),
            'col'                   =>  get_option( 'user_col_xs', 1 )            
        ), 'sidebar-profile-post' );        
    }

    public static function add_profile_liked_widgets(){

        if( is_active_sidebar( 'sidebar-profile-liked' ) || ! wp_get_sidebar( 'sidebar-profile-liked' ) ){
            return;
        }

        streamtube_core_insert_widget_in_sidebar( 'posts-widget', array(
            'title'                 =>  '',
            'post_type'             =>  'video',
            'layout'                =>  'grid',
            'hide_empty_thumbnail'  =>  'on',
            'hide_if_empty'         =>  'on',
            'pagination'            =>  'click',
            'custom_orderby'        =>  'on',
            'current_author_like'   =>  'on',
            'posts_per_page'        =>  12,
            'col_xxl'               =>  get_option( 'user_col_xxl', 4 ),
            'col_xl'                =>  get_option( 'user_col_xl', 4 ),
            'col_lg'                =>  get_option( 'user_col_lg', 4 ),
            'col_md'                =>  get_option( 'user_col_md', 2 ),
            'col_sm'                =>  get_option( 'user_col_sm', 2 ),
            'col'                   =>  get_option( 'user_col_xs', 1 )            
        ), 'sidebar-profile-liked' );        
    }    

    public static function add_profile_following_widgets(){

        if( is_active_sidebar( 'sidebar-profile-following' ) || ! wp_get_sidebar( 'sidebar-profile-following' ) ){
            return;
        }

        streamtube_core_insert_widget_in_sidebar( 'user-grid-widget', array(
            'title'                 =>  esc_html__( 'Following', 'streamtube-core' ),
            'follow_type'           =>  'current_author_following',
            'number'                =>  12,
            'col_xxl'               =>  get_option( 'user_col_xxl', 4 ),
            'col_xl'                =>  get_option( 'user_col_xl', 4 ),
            'col_lg'                =>  get_option( 'user_col_lg', 4 ),
            'col_md'                =>  get_option( 'user_col_md', 2 ),
            'col_sm'                =>  get_option( 'user_col_sm', 2 ),
            'col'                   =>  get_option( 'user_col_xs', 1 )            
        ), 'sidebar-profile-following' );        
    }  

    public static function add_profile_followers_widgets(){

        if( is_active_sidebar( 'sidebar-profile-followers' ) || ! wp_get_sidebar( 'sidebar-profile-followers' ) ){
            return;
        }

        streamtube_core_insert_widget_in_sidebar( 'user-grid-widget', array(
            'title'                 =>  esc_html__( 'Followers', 'streamtube-core' ),
            'follow_type'           =>  'current_author_follower',
            'number'                =>  12,
            'col_xxl'               =>  get_option( 'user_col_xxl', 4 ),
            'col_xl'                =>  get_option( 'user_col_xl', 4 ),
            'col_lg'                =>  get_option( 'user_col_lg', 4 ),
            'col_md'                =>  get_option( 'user_col_md', 2 ),
            'col_sm'                =>  get_option( 'user_col_sm', 2 ),
            'col'                   =>  get_option( 'user_col_xs', 1 )            
        ), 'sidebar-profile-followers' );        
    }

    /**
     *
     * Add custom role
     * 
     */
    public static function add_roles(){

        if( ! get_option( '_add_custom_roles' ) ){
            Streamtube_Core_Permission::add_roles();

            update_option( '_add_custom_roles', 'on' );
        }
    }

    public static function fix_user_verify_role(){

        if( get_option( '_fixed_verify_role' ) ){
            return;
        }

        global $wpdb;

        $results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}usermeta where meta_key = '_verification'" );

        if( ! $results ){
            return;
        }

        $user_ids = wp_list_pluck( $results, 'user_id' );

        if( $user_ids ){
            for ( $i =0;  $i < count( $user_ids );  $i++ ) { 

                delete_user_meta( $user_ids[$i], '_verification' );

                Streamtube_Core_Permission::verify_user( $user_ids[$i] );
            }
        }

        if( $i == count( $user_ids ) ){
            update_option( '_fixed_verify_role', 'on' );
        }
    }

    /**
     *
     * Fix taxonomy caps
     * 
     */
    public static function fix_taxonomy_capabilities(){

        if( get_option( '_fix_taxonomy_capabilities' ) ){
            return;
        }

        // Default moderator roles
        $roles = array( 'administrator', 'editor' );

        // All built-in taxonomies
        $taxonomies = Streamtube_Core_Taxonomy::get_builtin_taxonomies();

        $done = false;

        for ( $i = 0;  $i < count( $roles );  $i++ ) {
            $role = get_role( $roles[$i] );

            for ( $j=0;  $j < count( $taxonomies );  $j++) { 
                if( $taxonomies[$j] == Streamtube_Core_Taxonomy::TAX_CATEGORY ){
                    $role->add_cap( 'manage_video_' . $taxonomies[$j],  true );
                    $role->add_cap( 'edit_video_'   . $taxonomies[$j],  true );
                    $role->add_cap( 'delete_video_' . $taxonomies[$j],  true );
                }else{
                    $role->add_cap( "manage_{$taxonomies[$j]}",        true );
                    $role->add_cap( "edit_{$taxonomies[$j]}",          true );
                    $role->add_cap( "delete_{$taxonomies[$j]}",        true );                    
                }

                $done = true;
            }
        }

        if( $done ){
            update_option( '_fix_taxonomy_capabilities', 'on' );
        }
    }
}