<?php
/**
 *
 * Collection
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

class Streamtube_Core_Collection{

    /**
     *
     * Define the collection taxonomy
     * 
     */
    const TAX_COLLECTION    = 'video_collection';

    public function __construct(){
        require_once plugin_dir_path( __FILE__ ) . 'functions.php';
    }

    /**
     *
     * collection taxonomy
     * 
     * @since 1.0.0
     * 
     */
    public function register_taxonomy(){

        $labels = array(
            "name"                                  => esc_html__( "Video Collections", "streamtube-core" ),
            "singular_name"                         => esc_html__( "Video Collection", "streamtube-core" ),
        );

        $args = array(
            "label"                                 => esc_html__( "Video Collection", "streamtube-core" ),
            "labels"                                => $labels,
            "public"                                => false,
            "publicly_queryable"                    => true,
            "hierarchical"                          => true,
            "show_ui"                               => true,
            "show_in_menu"                          => true,
            "show_in_nav_menus"                     => true,
            "query_var"                             => true,
            "rewrite"                               => array(
                'slug'          => sanitize_key( get_option( 'taxonomy_' . self::TAX_COLLECTION . '_slug', self::TAX_COLLECTION ) ), 
                'with_front'    => true,  
                'hierarchical'  => true
            ),
            "show_admin_column"                     => false,
            "show_in_rest"                          => true,
            "rest_base"                             => self::TAX_COLLECTION,
            "rest_controller_class"                 => "WP_REST_Terms_Controller",
            "show_in_quick_edit"                    => true,
            'show_tagcloud'                         => true
        );

        register_taxonomy( self::TAX_COLLECTION, array( 'video' ), $args );
    }

    /**
     *
     * Get global collection settings;
     * 
     * @param  string $key
     * 
     */
    public function get_settings( $key = '' ){
        $settings = get_option( 'collection' );

        if( ! $settings ){
            $settings = array();
        }

        $settings = wp_parse_args( $settings, array(
            'visibility'        =>  'anyone',
            'roles'             =>  '',
            'number'            =>  0,
            'number_of_videos'  =>  100,
            'icon'              =>  'icon-folder-open'
        ) );

        if( is_string( $settings['roles'] ) && ! empty( $settings['roles'] ) ){
            $settings['roles'] = array_map( 'trim' , explode(',', $settings['roles'] ));
        }

        $settings['number']             = (int)$settings['number'];
        $settings['number_of_videos']   = (int)$settings['number_of_videos'];

        if( empty( $settings['icon'] ) ){
            $settings['icon'] = 'icon-folder-open';
        }

        /**
         *
         * Filter collection settings
         * 
         * @param array $settings
         * 
         */
        $settings = apply_filters( 'streamtube/core/collection/settings', $settings );

        if( $key && array_key_exists( $key, $settings ) ){
            return $settings[ $key ];
        }

        return $settings;
    }

    /**
     *
     * Get built-in terms
     * 
     * @return array
     * 
     */
    public function _get_builtin_terms(){
        $array = array(
            'watch_later'   =>  array(
                'name'      =>  esc_html__( 'Watch Later', 'streamtube-core' ),
                'deletable' =>  false,
                'show_ui'   =>  true
            ),
            'history'       =>  array(
                'name'      =>  esc_html__( 'History', 'streamtube-core' ),
                'deletable' =>  false,
                'show_ui'   =>  false
            )
        );

        /**
         *
         * Filter $array
         *
         * @param array $array
         * 
         */
        return apply_filters( 'streamtube/core/collection/builtin_terms', $array );
    }    

    /**
     *
     * Check if given term is builtin
     * 
     * @param  integer|WP_Term $term
     * @return boolean
     * 
     */
    public function _is_builtin_term( $term ){

        $term_id = 0;

        if( is_object( $term ) && $term instanceof WP_Term ){
            $term_id = $term->term_id;
        }

        if( is_int( $term ) || is_string( $term ) ){
            $term_id = (int)$term;
        }

        $type = get_term_meta( $term_id, 'type', true );

        if( array_key_exists( $type , $this->_get_builtin_terms() ) ){
            return $type;
        }

        return false;
    }    

    /**
     *
     * Get status options
     * 
     * @return array
     */
    public function _get_statuses(){
        return array(
            'public'        =>  esc_html__( 'Public', 'streamtube-core' ),
            'private'       =>  esc_html__( 'Private', 'streamtube-core' )
        );
    }

    /**
     *
     * Check if given status is valid
     * 
     * @param  string  $status
     * @return boolean
     * 
     */
    private function is_valid_status( $status = '' ){
        if( array_key_exists( $status , $this->_get_statuses() ) ){
            return $status;
        }

        return false;
    }

    /**
     *
     * Filter term name
     * 
     * @param  string $name
     * @param  WP_User $user
     * @return string $name
     * 
     */
    private function _pre_filter_name( $name, $user ){

        if( empty( $name ) ){
            return $name;
        }

        $name_formatted = sprintf( '%s - %s', $user->user_login, $name );

        /**
         * Filter the name
         *
         * @param $name_formatted
         * @param string $name
         * @param WP_User $user 
         * 
         */
        return apply_filters( 'streamtube/core/collection/pre_term_name', $name_formatted, $name, $user );        
    }

    /**
     *
     * Format collection name
     * 
     * @param  string $name
     * @param  int $term_id
     * @return string formatted name
     * 
     */
    public function _format_term_name( $name = '', $term_id = 0 ){

        $user_id = get_term_meta( $term_id, 'user_id', true );

        $name_formatted = $name;

        if( $user_id ){

            $user = get_userdata( $user_id );

            if( $user ){
                $name_formatted = str_replace( sprintf( '%s - ', $user->user_login ), '', $name_formatted );
            }
        }

        return apply_filters( 'streamtube/core/collection/term/name_formatted', $name_formatted, $name, $term_id );
    }    

    /**
     *
     * Add term with given data
     * 
     * @param array $args
     *
     * @return array|WP_Error
     */
    private function _add_term( $args = array() ){
        
        $args = wp_parse_args( $args, array(
            'user'          =>  wp_get_current_user(),
            'name'          =>  '',
            'status'        =>  '',
            'searchable'    =>  null,
            'parent'        =>  0,
            'description'   =>  '',
            'type'          =>  'collection'
        ) );

        extract( $args );

        $name = $this->_pre_filter_name( $name, $user );

        $term = wp_insert_term( $name, self::TAX_COLLECTION, compact( 'description', 'parent' ) );

        if( is_wp_error( $term ) ){
            return $term;
        }

        update_term_meta( $term['term_id'], 'user_id', $user->ID );

        if( $type ){
            update_term_meta( $term['term_id'], 'type', $type );

            update_user_meta( $user->ID, "collection_{$type}", $term['term_id'] );
        }
        
        if( ! $status || ! in_array( $status , array_keys( $this->_get_statuses() ) ) ){
            $status = 'private';
        }

        update_term_meta( $term['term_id'], 'status', $status );

        if( $searchable === true ){
            update_term_meta( $term['term_id'], 'searchable', 'on' );
            
        }

        /**
         *
         * Fires after creating collection
         *
         * @param array $term
         * @param array $args
         * 
         */
        do_action( 'streamtube/core/collection/term_created', $term, $args );

        return $term;
    }

    /**
     *
     * Update Term
     * 
     * @param  array $args
     * @return WP_Error|wp_update_term()
     * 
     */
    private function _update_term( $term_id, $args ){

        $args = wp_parse_args( $args, array(
            'name'          =>  '',
            'user_id'       =>  0,
            'description'   =>  '',
            'status'        =>  '',
            'searchable'    =>  null
        ) );

        extract( $args );

        if( ! $user_id ){
            $user_id = get_current_user_id();
        }

        $user = get_userdata( $user_id );

        if( ! $user ){
            return new WP_Error(
                'user_id_not_found',
                esc_html__( 'User ID was not found', 'streamtube-core' )
            );
        }        

        $name = $this->_pre_filter_name( $name, $user );

        $results = wp_update_term( $term_id, self::TAX_COLLECTION, compact( 'name', 'description' ) );

        if( ! is_wp_error( $results ) ){

            if( $this->is_valid_status( $status ) ){
                update_term_meta( $term_id, 'status', $status );
            }

            if( ! is_null( $searchable ) ){
                if( $searchable === true ){
                    update_term_meta( $term_id, 'searchable', 'on' );        
                }
                else{
                    delete_term_meta( $term_id, 'searchable' );
                }
            }
        }

        return $results;
    }

    /**
     *
     * Delete term
     * 
     * @param  int $term_id
     * @return wp_delete_term()
     * 
     */
    public function _delete_term( $term_id ){
        return wp_delete_term( $term_id, self::TAX_COLLECTION );
    }

    /**
     *
     * Delete all terms of given user
     * 
     * @param  int $user_id
     * 
     */
    public function _delete_term_of_user( $user_id = 0 ){
        $terms = get_terms( array(
            'taxonomy'      =>  self::TAX_COLLECTION,
            'hide_empty'    =>  false,
            'meta_query'    =>  array(
                'key'   =>  'user_id',
                'value' =>  $user_id
            )
        ) );

        if( $terms ){
            foreach( $terms as $term ) {
                $this->_delete_term( $term->term_id );
            }
        }
    }

    /**
     *
     * Move term to given user ID
     * 
     * @param  integer $term_id
     * @param  integer $user_id
     * @return update_term_meta()
     * 
     */
    public function _move_term( $term_id = 0, $user_id = 0 ){
        return update_term_meta( $term_id, 'user_id', $user_id );
    }

    /**
     *
     * Get term status
     * 
     * @param  integer $term_id
     * @return string
     */
    public function _get_term_status( $term_id = 0 ){
        $status = get_term_meta( $term_id, 'status', true );

        if( $this->is_valid_status( $status ) ){
            return $status;
        }

        return 'public';
    }

    /**
     *
     * Get term status
     * 
     * @param  integer $term_id
     * @return string
     */
    public function _get_term_searchable( $term_id = 0 ){
        return get_term_meta( $term_id, 'searchable', true );
    }    

    /**
     *
     * Get term user 
     * 
     * @param  integer $term_id
     * @return user_id
     */
    public function _get_term_user( $term_id = 0 ){
        return (int)get_term_meta( $term_id, 'user_id', true );
    }

    /**
     *
     * Check if onwer
     * 
     * @param  integer $term_id
     * @param  integer $user_id
     * @return boolean
     * 
     */
    public function _is_owner( $term_id = 0, $user_id = 0 ){

        $user_id = (int)$user_id;

        if( ! $user_id ){
            $user_id = get_current_user_id();
        }

        if( ! $user_id ){
            return false;
        }

        $term_user_id = $this->_get_term_user( $term_id );

        if( $term_user_id && $term_user_id == $user_id ){
            return true;
        }

        return false;
    }

    /**
     *
     * Check if given user can manage collection
     * 
     * @param  integer $user_id
     * @return boolean
     * 
     */
    public function _can_manage( $user_id = 0 ){

        if( ! $user_id ){
            $user_id = get_current_user_id();
        }

        if( ! $user_id ){
            return false;
        }

        if( user_can( $user_id, 'administrator' ) ){
            return true;
        }

        $settings = $this->get_settings();

        switch ( $settings['visibility'] ) {
            case 'anyone':
                return true;
            break;
            
            case 'noone':
                return false;
            break;

            case 'capability':
                return Streamtube_Core_Permission::can_manage_term( self::TAX_COLLECTION, $user_id );
            break;

            case 'roles':

                $user_roles = get_userdata( $user_id )->roles;

                if( ! $settings['roles'] || ! $user_roles ){
                    return false;
                }

                if( array_intersect( $user_roles, $settings['roles'] ) ){
                    return true;
                }

                return false;
            break;
        }

        return false;
    }

    /**
     *
     * Check if collection is shortable
     * 
     */
    public function _is_sortable( $user_id, $taxonomy = '' ){

        if( ! $this->_can_manage( $user_id ) ){
            return false;
        }

        if( ! $taxonomy ){
            $taxonomy = self::TAX_COLLECTION;
        }

        $sortable_taxonomies[] = self::TAX_COLLECTION;

        /**
         *
         * Filter the sortable taxonomies
         * 
         * @param array $sortable_taxonomies
         * 
         */
        $sortable_taxonomies = apply_filters( 
            'streamtube/core/collection/sortable_taxonomies', 
            $sortable_taxonomies
        );

        if( in_array( $taxonomy , $sortable_taxonomies ) ){
            return true;
        }

        return false;
    }

    /**
     *
     * Check if taxonomy is editable on frontend, supports for video_collection
     * 
     */
    public function _is_editable( $user_id, $taxonomy = '' ){

        if( ! $this->_can_manage( $user_id ) ){
            return false;
        }

        if( ! $taxonomy ){
            $taxonomy = self::TAX_COLLECTION;
        }

        return $taxonomy == self::TAX_COLLECTION ? true : false;        
    }

    /**
     *
     * Retrieve all collections of given user
     * 
     */
    public function _get_created_collections( $user_id = 0 ){

        if( ! $user_id ){
            $user_id = get_current_user_id();
        }

        if( ! $user_id ){
            return false;
        }

        return get_terms( array(
            'taxonomy'      =>  self::TAX_COLLECTION,
            'count'         =>  true,
            'hide_empty'    =>  false,
            'number'        =>  0,
            'meta_key'      =>  'user_id',
            'meta_value'    =>  $user_id,
            'fields'        =>  'count'
        ) );
    }

    /**
     *
     * Check if can view given term
     * 
     * @param  integer $term_id
     * @return WP_Error|true
     * 
     */
    public function _can_view( $term_id = 0 ){

        $errors = new WP_Error();

        $status = $this->_get_term_status( $term_id );

        if( $status == 'private' && ! $this->_is_owner( $term_id ) ){
            $errors->add(
                'no_permission',
                esc_html__( 'This collection is private', 'streamtube-core' )
            );
        }

        if( ! $this->_get_term_posts( $term_id ) ){
            $errors->add(
                'empty_posts',
                esc_html__( 'This collection is empty', 'streamtube-core' )
            );
        }

        return $errors->get_error_code() ? $errors : true;
    }

    /**
     *
     * Check if given term exists
     * 
     * @param  int $term_id
     * @return term_exists()
     */
    public function _term_exists( $term, $taxonomy = '' ){

        if( ! $taxonomy ){
            $taxonomy = self::TAX_COLLECTION;
        }

        return term_exists( $term, $taxonomy );
    }

    /**
     *
     * Get all posts of given term
     * 
     * @param  WP_Term|Term ID $term
     */
    public function _get_term_posts( $term, $args = array(), $taxonomy = '' ){

        if( ! $taxonomy ){
            $taxonomy = self::TAX_COLLECTION;
        }

        $term_id = 0;

        if( is_object( $term ) ){
            $term_id = $term->term_id;
        }else{
            $term_id = $term;
        }

        /**
         *
         * Filter max allowed post items within the list
         * 
         */
        $posts_per_page = apply_filters( 'streamtube/core/collection/max_post_items', 1000, $term, $args, $taxonomy );

        if( $taxonomy == self::TAX_COLLECTION ){
            $posts_per_page = $this->get_settings( 'number_of_videos' );
        }

        $args = wp_parse_args( $args, array(
            'post_type'         =>  Streamtube_Core_Post::CPT_VIDEO,
            'post_status'       =>  array( 'publish' ),
            'posts_per_page'    =>  $posts_per_page,
            'orderby'           =>  'title',
            'order'             =>  'ASC',
            'tax_query'         =>  array(
                array(
                    'taxonomy'          =>  $taxonomy,
                    'field'             =>  'term_id',
                    'terms'             =>  $term_id,
                    'include_children'  =>  false
                )
            )
        ) );

        if( $this->_is_owner( $term_id ) ){
            $args['post_status'] = array_merge( $args['post_status'], array(
                'pending',
                'private',
                'unlist'
            ) );
        }

        if( get_term_meta( $term_id, 'is_indexed', true ) ){
            $args = array_merge( $args, array(
                'meta_key'          =>  "_term_{$term_id}_order",
                'orderby'           =>  "meta_value_num",
                'order'             =>  'ASC'
            ) );
        }

        if( get_option( 'verified_users_only' ) && in_array( $taxonomy, Streamtube_Core_Taxonomy::get_builtin_taxonomies() ) ){
            $args['author__in'] = Streamtube_Core_User::get_users( array(
                'meta_query'    =>  array(
                    array(
                        'key'       =>  '_verification',
                        'value'     =>  'on',
                        'compare'   =>  '='
                    )
                )
            ) );            
        }

        /**
         *
         * Filter $args;
         * 
         */
        $args = apply_filters( 'streamtube/core/collection/query_post_args', $args, $term, $taxonomy );

        $query_posts = get_posts( $args );

        if( $query_posts && $args['orderby'] == 'title' ){
            uasort( $query_posts, function ( $item1, $item2 ) {
                return strnatcmp( $item1->post_title, $item2->post_title );
            });
        }

        return $query_posts;
    }

    /**
     *
     * Delete all term posts
     * 
     * @param  integer $term_id
     * 
     */
    public function _delete_term_posts( $term_id = 0 ){
        $posts = get_objects_in_term( $term_id, self::TAX_COLLECTION );

        if( $posts ){
            for ( $i=0; $i < count( $posts ); $i++ ) { 
                $this->_remove_post( $posts[$i], $term_id );
            }
        }
    }

    /**
     *
     * Get term link
     * 
     * @param  WP_Term|Term ID $term
     * @return get_term_link()
     * 
     */
    public function get_term_link( $term ){
        return get_term_link( $term, self::TAX_COLLECTION );
    }

    /**
     *
     * Prepare play all params
     * 
     * @return array
     */
    public function _prepare_play_all_params( $term_id = 0 ){

        $term_id = (int)$term_id;

        $params = array(
            'list'  =>  apply_filters( 'maybe_encode_string', $term_id )
        );

        /**
         *
         * Filter the params
         * 
         */
        return apply_filters( 'streamtube/core/collection/play_all_params', $params, $term_id );
    }

    /**
     *  Create play all URL
     */
    public function _create_play_all_url( $post_id, $term_id ){

        $url = add_query_arg( $this->_prepare_play_all_params( $term_id ), get_permalink( $post_id ) );        

        return apply_filters( 'streamtube/core/collection/play_all_url', $url, $post_id, $term_id );
    }

    /**
     *
     * Get term play all link
     * 
     * @param  WP_Term|Term ID $term
     * 
     */
    public function get_play_all_link( $term, $taxonomy = '' ){

        if( is_int( $term ) ){
            $term = $this->_get_term( $term, $taxonomy );
        }

        $posts = $this->_get_term_posts( $term, array(), $taxonomy );

        if( ! $posts ){
            return false;
        }

        return $this->_create_play_all_url( $posts[0]->ID, $term->term_id );
    }

    /**
     *
     * Create builtin term of given User
     * 
     * @param  WP_User $user
     * 
     */
    public function _create_builtin_user_terms( $user ){

        $terms = array();

        if( is_int( $user ) ){
            $user = get_userdata( $user );
        }

        $builtin_terms = $this->_get_builtin_terms();

        if( ! $builtin_terms ){
            return false;
        }

        foreach ( $builtin_terms as $key => $value ) {
            $maybe_term_id = get_user_meta( $user->ID, "collection_{$key}", true );

            if( ! $maybe_term_id || ! $this->_term_exists( $maybe_term_id ) ){
                $terms[] = $this->_add_term( array(
                    'user'          =>  $user,
                    'name'          =>  $value['name'],
                    'status'        =>  'private',
                    'type'          =>  $key
                ) );
            }
        }

        return $terms;
    }

    /**
     *
     * Filter term
     * 
     * @param  WP_Term $term
     * @return WP_Term $term
     */
    public function _filter_term( $term = null, $taxonomy = null ){

        if( is_null( $taxonomy ) || $taxonomy != self::TAX_COLLECTION  ){
            return $term;
        }

        $term->name_formatted   = $this->_format_term_name( $term->name, $term->term_id );
        $term->status           = $this->_get_term_status( $term->term_id );
        $term->user             = $this->_get_term_user( $term->term_id );
        $term->searchable       = $this->_get_term_searchable( $term->term_id );

        return $term;
    }

    /**
     *
     * Get term
     * 
     * @param  integer $term_id
     * @return WP_Term
     * 
     */
    public function _get_term( $term_id = 0, $taxonomy = '' ){

        if( ! $taxonomy ){
            $taxonomy = self::TAX_COLLECTION;
        }

        return get_term( $term_id, $taxonomy );
    }

    /**
     *
     * Get term activity
     * 
     * @param  integer $term_id
     * 
     */
    public function _get_term_activity( $term_id = 0 ){

        $term_id = (int)$term_id;

        $activity = get_term_meta( $term_id, 'activity', true );

        if( ! $activity ){
            $activity = 'open';
        }
        return $activity;
    }

    /**
     *
     * Check if term open
     * 
     * @param  integer $term_id
     * @return boolean
     * 
     */
    public function _is_term_activity_open( $term_id = 0 ){
        return $this->_get_term_activity( $term_id ) == 'open' ? true : false;
    }

    /**
     *
     * Get user terms
     * 
     * @param  int|WP_User $user
     * @return get_terms()
     * 
     */
    public function _get_user_terms( $user = null, $args = array(), $exclude_builtin = true ){

        $exclude_terms = array();

        if( is_int( $user ) ){
            $user = get_userdata( $user );
        }

        if( is_null( $user ) ){
            $user = wp_get_current_user();
        }

        if( $exclude_builtin ){
            $builtin_terms = $this->_get_builtin_terms();

            if( $builtin_terms ){
                foreach ( $builtin_terms as $type => $value ) {
                    if( $value['show_ui'] === false ){
                        $exclude_terms[] = get_user_meta( $user->ID, "collection_{$type}", true );
                    }
                }
            }
        }

        $args = wp_parse_args( $args, array(
            'taxonomy'      =>  self::TAX_COLLECTION,
            'orderby'       => 'name',
            'order'         => 'ASC',
            'hide_empty'    =>  false,
            'exclude'       =>  $exclude_terms,
            'meta_query'    =>  array(
                'relation'  =>  'AND',
                array(
                    'key'       =>  'user_id',
                    'compare'   =>  '=',
                    'value'     =>  $user->ID,
                    'type'      =>  'NUMERIC'
                )
            )
        ) );

        /**
         *
         * Filter $args
         *
         * @param array $args
         * @param int $user_id
         * 
         */
        $args = apply_filters( 'streamtube/core/collection/get_user_collections', $args, $user );

        $terms = get_terms( $args );

        if( $terms ){
            for ( $i=0;  $i < count( $terms );  $i++) { 
                $terms[$i] = $terms[$i];
            }
        }

        return $terms;
    }

    /**
     *
     * Get request list (term) ID
     * 
     * @return false|int|array
     */
    public function _get_request_term_id(){

        if( ! isset( $_GET['list'] ) || empty( $_GET['list'] ) ){
            return false;
        }

        $list = is_string( $_GET['list'] ) ? trim( $_GET['list'] ) : false;

        $maybe_term_id = (int)$list;

        if( $maybe_term_id && strlen( $maybe_term_id ) == strlen( $list ) && term_exists( $maybe_term_id ) ){
            return $maybe_term_id;
        }

        $decoded_id = apply_filters( 'maybe_encoded_string', $list );

        if( ! $decoded_id ){
            $decoded_id = $list;
        }

        return term_exists( $decoded_id ) ? compact( 'list', 'decoded_id' ) : false;
    }

    /**
     *
     * Add post to given term
     * 
     * @param integer $post_id
     * @param integer $term_id
     * @param boolean $append
     *
     * @return wp_set_post_terms();
     */
    public function _add_post( $post_id = 0, $term_id = 0, $append = true ){
        return wp_set_post_terms( $post_id, $term_id, self::TAX_COLLECTION, $append );
    }

    /**
     *
     * Remove post from given term
     * 
     * @param  integer $post_id
     * @param  integer $term_id
     * @return wp_remove_object_terms()
     */
    public function _remove_post( $post_id = 0, $term_id = 0 ){
        return wp_remove_object_terms( $post_id, $term_id, self::TAX_COLLECTION );
    }

    /**
     *
     * Add "_term_{term_id}_order" meta
     * Hooked into "added_term_relationship"
     * 
     */
    public function _add_term_order( $object_id, $tt_id, $taxonomy ){
        if( $taxonomy == self::TAX_COLLECTION ){
            $index = get_term( $tt_id, self::TAX_COLLECTION )->count;
            return update_post_meta( $object_id, "_term_{$tt_id}_order", $index );
        }
    }

    /**
     *
     * delete "_term_{term_id}_order" meta
     * Hooked into "deleted_term_relationships"
     * 
     */
    public function _remove_term_order( $object_id, $tt_ids, $taxonomy ){

        if( $taxonomy == self::TAX_COLLECTION ){
            for ( $i=0; $i < count( $tt_ids ); $i++) { 
                delete_post_meta( $object_id, '_term_'. $tt_ids[$i] .'_order' );
            }
        }
    }    

    /**
     *
     * Hooked into "deleted_term_taxonomy"
     * 
     */
    public function _clean_term_orders( $term_id ){
        global $wpdb;
        $wpdb->query(
            $wpdb->prepare(
                "DELETE FROM {$wpdb->postmeta} WHERE `meta_key` = %s",
                "_term_{$term_id}_order"
            )
        );
    }

    /**
     *
     * Get Watch Later term ID
     * 
     * @param  integer $user_id
     * @return int
     * 
     */
    public function _get_watch_later_term_id( $user_id = 0 ){
        if( ! $user_id ){
            $user_id = get_current_user_id();
        }

        if( ! $user_id ){
            return false;
        }

        $term_id = (int)get_user_meta( $user_id, 'collection_watch_later', true );

        if( $term_id && $this->_term_exists( $term_id ) ){
            return $term_id;
        }

        return false;
    }

    /**
     *
     * Check if has term
     * 
     * @param  integer $post_id
     * @param  integer $term_id
     * @return boolean
     * 
     */
    public function _has_term( $post_id = 0, $term_id = 0, $taxonomy = '' ){

        if( ! $taxonomy ){
            $taxonomy = self::TAX_COLLECTION;
        }

        if( has_term( $term_id, $taxonomy, $post_id ) ){
            return true;
        }
        return false;
    }

    /**
     *
     * Auto create user builtin terms after logged in
     * Hooked into wp_login action
     * 
     */
    public function auto_create_user_terms( $user_login, $user ){

        if( ! $this->_can_manage( $user->ID ) ){
            return;
        }

        return $this->_create_builtin_user_terms( $user );
    }

    /**
     *
     * Add thumbnail field
     * 
     * @param string $taxonomy
     *
     * @since 2.2.1
     * 
     */
    public function admin_add_term_meta_field( $taxonomy, $term = null ){
        ?>
        <div class="form-field term-user_id-wrap">
            <label for="tag-user_id">
                <?php esc_html_e( 'User ID', 'streamtube-core' );?>
            </label>
            
            <?php printf(
                '<input name="collection_meta[user_id]" id="tag-user_id" type="number" value="%s">',
                get_current_user_id()
            );?>

            <p>
                <?php esc_html_e( 'Set collection owner', 'streamtube-core' );?>
            </p>
        </div>

        <div class="form-field term-status-wrap">
            <label for="tag-status">
                <?php esc_html_e( 'Status', 'streamtube-core' );?>
            </label>
            
            <select name="collection_meta[status]" id="tag-status" class="regular-text">

                <?php foreach ( $this->_get_statuses() as $key => $value ) {
                    
                    printf(
                        '<option value="%s">%s</option>',
                        esc_attr( $key ),
                        esc_html( $value )
                    );

                }?>

            </select>
        </div>
        <div class="form-field term-searchable-wrap">
            <label for="tag-searchable">
                <?php esc_html_e( 'Searchable', 'streamtube-core' );?>
            </label>
            
            <input name="collection_meta[searchable]" id="tag-searchable" type="checkbox">
        </div>        
        <?php   

        wp_nonce_field( 'update_term_fields', 'update_term_fields' );
    }

    /**
     *
     * Add thumbnail field
     * 
     * @param string $taxonomy
     *
     * @since 2.2.1
     * 
     */
    public function admin_edit_term_meta_field( $term, $taxonomy ){
        ?>
        <tr class="form-field term-user_id-wrap">
            <th scope="row">
                <label for="tag-user_id">
                    <?php esc_html_e( 'User ID', 'streamtube-core' ); ?>
                </label>
            </th>
            <td>
                <?php printf(
                    '<input name="collection_meta[user_id]" id="tag-user_id" type="number" value="%s">',
                    esc_attr( get_term_meta( $term->term_id, 'user_id', true ) )
                );?>
            </td>
        </tr>

        <tr class="form-field term-status-wrap">
            <th scope="row">
                <label for="tag-status">
                    <?php esc_html_e( 'Status', 'streamtube-core' ); ?>
                </label>
            </th>
            <td>
            <select name="collection_meta[status]" id="tag-status" class="regular-text">

                <?php foreach ( $this->_get_statuses() as $key => $value ) {
                    
                    printf(
                        '<option %s value="%s">%s</option>',
                        selected( get_term_meta( $term->term_id, 'status', true ), $key, false ),
                        esc_attr( $key ),
                        esc_html( $value ),
                    );

                }?>

            </select>
            </td>
        </tr>

        <tr class="form-field term-searchable-wrap">
            <th scope="row">
                <label for="tag-searchable">
                    <?php esc_html_e( 'Searchable', 'streamtube-core' ); ?>
                </label>
            </th>
            <td>
                <?php printf(
                    '<input name="collection_meta[searchable]" id="tag-searchable" type="checkbox" %s>',
                    checked( get_term_meta( $term->term_id, 'searchable', true ), 'on', false )
                );?>
            </td>
        </tr>
        <?php
        wp_nonce_field( 'update_term_fields', 'update_term_fields' );
    }   

    /**
     *
     * Update thumbnail image
     * 
     * @param  int $term
     * @param  string $taxonomy
     *
     * @since 2.2.1
     * 
     */
    public function admin_update_term_meta_fields( $term ){

        if( ! current_user_can( 'administrator' ) ){
            return;
        }

        if( ! isset( $_POST['update_term_fields'] ) ){
            return;
        }

        if( isset( $_POST['collection_meta'] ) ){

            $tax_meta = wp_unslash( $_POST['collection_meta'] );

            $tax_meta = wp_parse_args( $tax_meta, array(
                'searchable'    =>  ''
            ) );   

            foreach ( $tax_meta as $key => $value ) {
                if( $value ){
                    update_term_meta( $term, $key, sanitize_text_field( $value ) );    
                }
                else{
                    delete_term_meta( $term, $key );
                }
            }
        }

        if( ! get_term_meta( $term, 'type', true ) ){
            update_term_meta( $term, 'type', 'collection' );
        }
    }

    /**
     *
     * Add Thumbnail column
     * 
     * @since 2.2.1
     */
    public function admin_add_term_meta_field_columns( $columns ){
        return array_merge( $columns, array(
            'user_id'       =>  esc_html__( 'User', 'streamtube-core' ),
            'status'        =>  esc_html__( 'Status', 'streamtube-core' ),
            'type'          =>  esc_html__( 'Type', 'streamtube-core' ),
            'searchable'    =>  esc_html__( 'Searchable', 'streamtube-core' )
        ) );
    }

    /**
     *
     * Add Thumbnail content
     * 
     * @since 2.2.1
     */
    public function admin_add_term_meta_field_content_content( $content, $column_name, $term_id ){

        switch ( $column_name ) {
            case 'user_id':

                $user_id = get_term_meta( $term_id,$column_name, true );

                if( "" != $user_data = get_userdata( $user_id ) ){
                    $content = sprintf(
                        '%s (#%s)',
                        $user_data->display_name,
                        $user_id
                    );
                }
                
            break;

            case 'status':

                $statuses = $this->_get_statuses();

                $status = get_term_meta( $term_id,$column_name, true );

                if( $status && array_key_exists( $status, $statuses ) ){
                    $content = sprintf(
                        '<span class="badge bg-%s">%s</span>',
                        $status,
                        $statuses[ $status ]
                    );
                }
            break;

            case 'type':

                if( $this->_is_builtin_term( $term_id ) ){
                    $content = sprintf(
                        '<span class="badge bg-secondary">%s</span>',
                        esc_html__( 'Builtin', 'streamtube-core' )
                    );                    
                }else{
                    $content = sprintf(
                        '<span class="badge bg-info">%s</span>',
                        esc_html__( 'Collection', 'streamtube-core' )
                    );
                }

            break;

            case 'searchable':
                $searchable = get_term_meta( $term_id, 'searchable', true );

                if( $searchable ){
                    $content = sprintf(
                        '<span class="badge bg-success">%s</span>',
                        esc_html__( 'On', 'streamtube-core' )
                    );
                }else{
                    $content = sprintf(
                        '<span class="badge bg-warning">%s</span>',
                        esc_html__( 'Off', 'streamtube-core' )
                    );                    
                }
            break;            
        }

        return $content;
    }

    /**
     *
     * Delete/reassign collections while delete user
     * 
     */
    public function admin_delete_user_collections( $user_id, $reassign = null, $user = null ){

        // Get all user terms
        $terms = $this->_get_user_terms( $user_id, array(), false );

        if( ! $terms ){
            return;
        }

        for ( $i = 0;  $i < count( $terms );  $i++) { 
            if( $this->_is_builtin_term( $terms[$i]->term_id ) ){
                $this->_delete_term( $terms[$i]->term_id );
            }else{
                if( $reassign ){
                    $this->_move_term( $terms[$i]->term_id, $reassign );
                }else{
                    $this->_delete_term( $terms[$i]->term_id );
                }
            }
        }

        return $terms;
    }

    /**
     *
     * Load the Watch Later button
     * 
     */
    public function frontend_the_watch_later_button( $args = array() ){

        if( ! $this->_can_manage() ){
            return;
        }        

        $args = wp_parse_args( $args, array(
            'post_id'   =>  get_the_ID(),
            'term_id'   =>  $this->_get_watch_later_term_id(),
            'icon'      =>  'icon-clock',
            'classes'   =>  array( 'ajax-elm', 'btn', 'btn-hide-icon-active', 'shadow-none', 'p-0', 'rounded-1' )
        ) );

        if( get_post_type( $args['post_id'] ) == 'video' && $args['term_id'] ){

            if( $args['term_id'] && $this->_has_term( $args['post_id'], $args['term_id'] ) ){
                $args['icon']       = 'icon-ok';
            }

            load_template( 
                trailingslashit( plugin_dir_path( __FILE__ ) ) . 'frontend/button-watch-later.php', 
                false,
                $args
            );
        }
    }

    /**
     *
     * Load the collection button
     * 
     */
    public function frontend_the_collection_button(){

        if( ! $this->_can_manage() ){
            return;
        }

        load_template( 
            trailingslashit( plugin_dir_path( __FILE__ ) ) . 'frontend/button-collection.php',
            true,
            $this->get_settings()
        );
    }

    /**
     *
     * Load the collection modal
     * 
     */
    public function frontend_the_collection_modal(){

        if( ! $this->_can_manage() ){
            return;
        }        

        load_template( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'frontend/modal-collection.php' );
        load_template( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'frontend/modal-search-videos.php' );

        load_template( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'frontend/modal-delete-collection.php' );
        load_template( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'frontend/modal-edit-collection.php' );
    }

    /**
     *
     * Add post to history of current logged in user
     * 
     */
    public function frontend_add_post_history(){

        if( is_admin() || ! is_singular( 'video' ) || ! $this->_can_manage() ){
            return;
        }

        $user_id = get_current_user_id();

        $post_id = get_the_ID();

        $term_id = (int)get_user_meta( $user_id, 'collection_history', true );

        if( $this->_term_exists( $term_id ) && $this->_is_term_activity_open( $term_id ) ){
            return $this->_add_post( $post_id, $term_id );
        }
    }

    /**
     *
     * Add Collections Box to Frontend Form
     * 
     * 
     * @param  WP_Post $post
     * 
     */
    public function frontend_post_form_collections_box( $post = null ){
        if( is_object( $post ) && $post->post_type =='video' && ! wp_doing_ajax() ){
            ?>
            <div class="mb-4">
                <?php load_template( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'frontend/button-collection.php', false, array(
                    'classes'   =>  'border px-3 bg-light',
                    'label'     =>  esc_html__( 'Add to Collections', 'streamtube-core' )
                ) );?>
            </div>
            <?php
        }
    }

    public function frontend_dashboard_menu( $items ){   

        if( ! $this->_can_manage() ){
            return $items;
        }         

        $items['collections'] = array(
            'title'     =>  esc_html__( 'Collections', 'streamtube-core' ),
            'desc'      =>  esc_html__( 'All Collections', 'streamtube-core' ),
            'icon'      =>  'icon-folder-open',
            'callback'  =>  function(){
                load_template( plugin_dir_path( __FILE__ ) . 'frontend/dashboard-collections.php' );
            },
            'parent'    =>  'dashboard',
            'cap'       =>  'read',
            'priority'  =>  25
        );

        return $items;
    }    

    public function frontend_profile_menu( $items ){

        if( ! $this->_can_manage() ){
            return $items;
        }

        $items[ 'collections' ]  = array(
            'title'         =>  esc_html__( 'Collections', 'streamtube-core' ),
            'icon'          =>  'icon-folder-open',
            'callback'      =>  function(){
                load_template( plugin_dir_path( __FILE__ ) . 'frontend/profile-collections.php' );
            },
            'widgetizer'    =>  true,
            'priority'      =>  50
        );

        $history_id = (int)get_user_meta( get_current_user_id(), 'collection_history', true );

        if( $history_id ){
            $items[ 'history' ]  = array(
                'title'         =>  esc_html__( 'History', 'streamtube-core' ),
                'icon'          =>  'icon-history',
                'url'           =>  $this->get_term_link( $history_id ),
                'private'       =>  true,
                'priority'      =>  50
            );
        }

        $watch_later_id = (int)get_user_meta( get_current_user_id(), 'collection_watch_later', true );

        if( $watch_later_id ){
            $items[ 'watch_later' ]  = array(
                'title'         =>  esc_html__( 'Watch Later', 'streamtube-core' ),
                'icon'          =>  'icon-bookmark',
                'url'           =>  $this->get_term_link( $watch_later_id ),
                'private'       =>  true,
                'priority'      =>  50
            );
        }        

        return $items;
    }

    /**
     *
     * Show the play all button
     * 
     */
    public function frontend_the_button_play_all(){

        if( is_post_type_archive( 'video' ) || ! get_option( 'archive_playall', 'on' ) ){
            return;
        }

        $queried_object = get_queried_object();
        $play_all_url   = $this->get_play_all_link( $queried_object->term_id, $queried_object->taxonomy );

        if( ! $play_all_url ){
            return;
        }

        ?>
        <div class="play-all ms-auto">
            <a href="<?php echo esc_url( $play_all_url );?>" class="btn btn-sm btn-danger px-2 rounded-1">
                <span class="icon-play"></span>
                <span class="text-white d-none d-sm-inline-block"><?php esc_html_e( 'Play All', 'streamtube-core' )?></span>
            </a>
        </div>
        <?php
    }

    /**
     *
     * The Pause button
     * 
     * @param  integer $term_id
     * 
     */
    public function the_button_pause( $args = array() ){

        $args = wp_parse_args( $args, array(
            'term_id'   =>  0,
            'classes'   =>  'btn w-100 rounded-1 border bg-light ajax-elm d-flex mb-4 align-items-center',
            'icon'      =>  'icon-pause',
            'text'      =>  esc_html__( 'Pause Watch History', 'streamtube-core' ),
            'echo'      =>  true
        ) );

        if( ! $this->_is_term_activity_open( $args['term_id'] ) ){
            $args['icon'] = 'icon-play-circled2';

            $args = array_merge( $args, array(
                'icon'  =>  'icon-play-circled2',
                'text'  =>  esc_html__( 'Turn on Watch History', 'streamtube-core' )
            ) );
        }

        ob_start();

        load_template( plugin_dir_path( __FILE__ ) . 'frontend/button-pause.php', true, $args );

        $output = ob_get_clean();

        if( $args['echo'] ){
            echo $output;
        }else{
            return $output;
        }
    }

    /**
     *
     * The Clear All button
     * 
     * @param  integer $term_id
     * 
     */
    public function the_button_clear_all( $args = array() ){
        $args = wp_parse_args( $args, array(
            'term_id'   =>  0,
            'classes'   =>  'btn w-100 rounded-1 border bg-light ajax-elm d-flex mb-4 align-items-center',
            'icon'      =>  'icon-trash',
            'text'      =>  esc_html__( 'Clear All Watch History', 'streamtube-core' ),
            'echo'      =>  true
        ) );

        if( ! $this->_get_term_posts( $args['term_id'] ) ){
            $args['icon'] = 'icon-play-circled2';

            $args = array_merge( $args, array(
                'icon'  =>  'icon-trash-empty',
                'text'  =>  esc_html__( 'Empty Collection', 'streamtube-core' )
            ) );
        }

        ob_start();

        load_template( plugin_dir_path( __FILE__ ) . 'frontend/button-clear-all.php', true, $args );

        $output = ob_get_clean();

        if( $args['echo'] ){
            echo $output;
        }else{
            return $output;
        }
    }    

    /**
     *
     * Hooked into saved_term action
     * 
     */
    public function saved_term( $term_id, $tt_id, $taxonomy ){
        if( $taxonomy == self::TAX_COLLECTION ){
            if( ! $this->_get_term_user( $term_id ) ){
                update_term_meta( $term_id, 'user_id', get_current_user_id() );
            }

            if( ! get_term_meta( $term_id, 'status', true ) ){
                update_term_meta( $term_id, 'status', 'public' );
            }
        }
    }

    /**
     *
     * Create collection
     * 
     */
    public function create_collection( $args = array() ){

        $errors = new WP_Error();

        $args = wp_parse_args( $args, array(
            'name'          =>  '',
            'status'        =>  '',
            'post_id'       =>  0,
            'term_id'       =>  0
        ) );

        $args['type'] = 'collection';

        $args['name'] = wp_strip_all_tags( $args['name'] );

        if( ! $this->_can_manage() ){
            $errors->add(
                'no_permission',
                esc_html__( 'You do not have permission to manage collection.', 'streamtube-core' )
            );            
        }

        if( ! current_user_can( 'administrator' ) ){
            $allowed_number = $this->get_settings( 'number' );

            if( $allowed_number && $allowed_number <= $this->_get_created_collections() ){
                $errors->add(
                    'max_allowed_collection',
                    esc_html__( 'You have reached maximum number of allowed collections.', 'streamtube-core' )
                );
            }
        }

        if( empty( $args['name'] ) ){
            $errors->add(
                'empty_name',
                esc_html__( 'Name is required', 'streamtube-core' )
            );
        }

        if( $args['term_id'] && $this->_is_builtin_term( $args['term_id'] ) ){
            $errors->add(
                'builtin_collection',
                esc_html__( 'You cannot edit a built-in collection', 'streamtube-core' )
            );
        }

        /**
         *
         * Filter the errors
         * 
         * @var WP_Error
         *
         * @since  1.0.0
         * 
         */
        $errors = apply_filters( 'streamtube/core/collection/create', $errors , $args );

        if( $errors->get_error_code() ){
            return $errors;
        }

        if( $args['term_id'] ){
            $term = $this->_update_term( $args['term_id'], $args );
        }
        else{
            $term = $this->_add_term( $args );
        }

        if( is_wp_error( $term ) ){
            return $term;
        }

        if( is_array( $term ) ){
            if( get_post_type( $args['post_id'] ) == 'video' ){
                $this->_add_post( $args['post_id'], $term['term_id'] );
            }
        }

        return $this->_get_term( $term['term_id'] );
    }

    /**
     *
     * AJAX create collection
     * 
     */
    public function ajax_create_collection(){

        check_ajax_referer( '_wpnonce' );     

        $args = wp_parse_args( $_POST, array(
            'post_id'       =>  0,
            'term_id'       =>  0
        ) );

        if( Streamtube_Core_Permission::can_manage_term( self::TAX_COLLECTION ) ){
            if( array_key_exists( 'searchable', $args ) ){
                $args['searchable'] = true;
            }else{
                $args['searchable'] = false;
            }
        }

        $results = $this->create_collection( $args );

        if( is_wp_error( $results ) ){
            wp_send_json_error( $results );
        }

        $message = esc_html__( 'Collection has been created successfully', 'streamtube-core' );

        if( $args['term_id'] ){
            $message = esc_html__( 'Collection has been updated successfully', 'streamtube-core' );
        }        

        if( get_post_type( $args['post_id'] ) == 'video' ){

            $list_id = 'collection-list-' . get_current_user_id();

            $post = get_post( $args['post_id'] );

            setup_postdata( $GLOBALS['post'] =& $post );

            ob_start();

            load_template( 
                trailingslashit( plugin_dir_path( __FILE__ ) )  . 'frontend/collection-list.php' 
            );

            $list = ob_get_clean();

            wp_send_json_success( compact( 'results', 'list', 'list_id', 'message' ) );
        }

        wp_send_json_success( compact( 'results', 'message' ) );
    }

    /**
     *
     * Delete collection
     * 
     * @param  integer $term_id
     * 
     */
    public function delete_collection( $term_id = 0 ){

        $errors = new WP_Error();

        if( ! $this->_is_owner( $term_id ) || ! $this->_can_manage() ){
            $errors->add(
                'no_permission',
                esc_html__( 'You do not have permission to delete this collection', 'streamtube-core' )
            );
        }

        if( $this->_is_builtin_term( $term_id ) ){
            $errors->add(
                'builtin_collection',
                esc_html__( 'You cannot delete a built-in collection', 'streamtube-core' )
            );
        }

        /**
         *
         * Filter the errors
         * 
         * @var WP_Error
         *
         * @since  1.0.0
         * 
         */
        $errors = apply_filters( 'streamtube/core/collection/delete', $errors, $term_id );

        if( $errors->get_error_code() ){
            return $errors;
        }

        return $this->_delete_term( $term_id );

    }

    /**
     *
     * AJAX delete collection
     * 
     */
    public function ajax_delete_collection(){

        check_ajax_referer( '_wpnonce' );

        $http_post = wp_parse_args( $_POST, array(
            'data'          =>  0,
            'redirect_url'  =>  ''
        ) );

        extract( $http_post );

        if( empty( $data ) ){
            wp_send_json_error( new WP_Error(
                'invalid_requested',
                esc_html__( 'Invalid Requested', 'streamtube-core' )
            ) );
        }

        $results = $this->delete_collection( (int)$data );

        if( is_wp_error( $results ) ){
            wp_send_json_error( $results );
        }

        wp_send_json_success( compact( 'results', 'redirect_url' ) );
    }

    /**
     *
     * AJAX Set Collection status
     *
     * @return WP_Error|update_term_meta()
     * 
     */
    public function set_collection_status( $term_id = 0 ){

        $term_id = (int)$term_id;
        
        $errors = new WP_Error();

        if( ! $this->_is_owner( $term_id ) || ! $this->_can_manage() ){
            $errors->add(
                'no_permission',
                esc_html__( 'You do not have permission to update this collection', 'streamtube-core' )
            );
        }        

        if( ! $this->_term_exists( $term_id ) ){
            $errors->add(
                'collection_not_exists',
                esc_html__( 'The collection does not exist', 'streamtube-core' )
            );
        }

        if( $this->_is_builtin_term( $term_id ) ){
            $errors->add(
                'builtin_collection',
                esc_html__( 'You cannot update status for a built-in collection', 'streamtube-core' )
            );            
        }

        /**
         *
         * Filter the errors
         * 
         * @var WP_Error
         *
         * @since  1.0.0
         * 
         */
        $errors = apply_filters( 'streamtube/core/collection/set_status', $errors, $term_id );

        if( $errors->get_error_code() ){
            return $errors;
        }        

        $status = get_term_meta( $term_id, 'status', true );

        if( $status == 'public' ){
            $status = 'private';
        }else{
            $status = 'public';
        }

        return update_term_meta( $term_id, 'status', $status );
    }    

    /**
     *
     * AAJX set collection status
     * 
     */
    public function ajax_set_collection_status(){
        check_ajax_referer( '_wpnonce' );

        if( ! isset( $_POST['data'] ) ){
            wp_send_json_error( new WP_Error(
                'invalid_requested',
                esc_html__( 'Invalid Requested', 'streamtube-core' )
            ) );
        }

        $results = $this->set_collection_status( $_POST['data'] );

        if( is_wp_error( $results ) ){
            wp_send_json_error( $results );
        }

        if( ! $results ){
            wp_send_json_error( new WP_Error(
                'undefined_error',
                esc_html__( 'Undefined Error', 'streamtube-core' )
            ) );
        }

        wp_send_json_success( streamtube_core_collection_button_privacy( $_POST['data'] ) );
    }

    /**
     *
     * Get collection detail
     * 
     * @param  int $term_id
     * @return WP_Error|WP_Term object
     * 
     */
    public function get_collection_term( $term_id = 0 ){

        $term_id = (int)$term_id;
        
        $errors = new WP_Error();

        if( ! $this->_is_owner( $term_id ) || ! $this->_can_manage() ){
            $errors->add(
                'no_permission',
                esc_html__( 'You do not have permission to retrieve this collection posts', 'streamtube-core' )
            );
        }        

        if( ! $this->_term_exists( $term_id ) ){
            $errors->add(
                'collection_not_exists',
                esc_html__( 'The collection does not exist', 'streamtube-core' )
            );
        }

        /**
         *
         * Filter the errors
         * 
         * @var WP_Error
         *
         * @since  1.0.0
         * 
         */
        $errors = apply_filters( 'streamtube/core/collection/get_content', $errors, $term_id );

        if( $errors->get_error_code() ){
            return $errors;
        }

        return $this->_get_term( $term_id );
    }

    /**
     *
     * AJAX get collection term
     * 
     */
    public function ajax_get_collection_term(){
        check_ajax_referer( '_wpnonce' );

        if( ! isset( $_POST['data'] ) ){
            wp_send_json_error( new WP_Error(
                'invalid_requested',
                esc_html__( 'Invalid Requested', 'streamtube-core' )
            ) );
        }

        $term = $this->get_collection_term( $_POST['data'] );

        if( is_wp_error( $term ) ){
            wp_send_json_error( $term );
        }

        wp_send_json_success( $term );
    }

    /**
     *
     * Set Post collection term
     * 
     * @param array $args
     *
     * @return WP_Error|bool
     * 
     */
    public function set_post_collection( $args = array() ){

        $limit = $this->get_settings( 'number_of_videos' );

        $args = wp_parse_args( $args, array(
            'post_id'   =>  0,
            'term_id'   =>  0
        ) );

        extract( $args );

        $post_id = (int)$post_id;
        $term_id = (int)$term_id;

        $errors = new WP_Error();

        if( ! $this->_is_owner( $term_id ) || ! $this->_can_manage() ){
            $errors->add(
                'no_permission',
                esc_html__( 'You do not have permission to add post to this collection', 'streamtube-core' )
            );
        }

        if( ! Streamtube_Core_Permission::moderate_posts() && $limit > 0 && $this->_get_term( $term_id )->count >= $limit ){
            $errors->add(
                'max_number_reached',
                esc_html__( 'This collection has reached the maximum number of allowed videos', 'streamtube-core' )
            );
        }

        /**
         *
         * Filter the errors
         * 
         * @var WP_Error
         *
         * @since  1.0.0
         * 
         */
        $errors = apply_filters( 'streamtube/core/collection/set_post', $errors , $args );

        if( $errors->get_error_code() ){
            return $errors;
        }

        if( $this->_has_term( $post_id, $term_id ) ){
            return $this->_remove_post( $post_id, $term_id );

        }else{
            return $this->_add_post( $post_id, $term_id );
        }
    }

    /**
     *
     * AJAX set post collection
     * 
     */
    public function ajax_set_post_collection(){

        check_ajax_referer( '_wpnonce' );

        $args = isset( $_POST['data'] ) ? json_decode( wp_unslash( $_POST['data'] ), true ) : false;

        if( ! $args || ! is_array( $args ) ){
            wp_send_json_error( new WP_Error(
                'invalid_requested',
                esc_html__( 'Invalid Requested', 'streamtube-core' )
            ) );
        }

        $args = wp_parse_args( $args, array(
            'post_id'   =>  0,
            'term_id'   =>  0,
            'from'      =>  'save_to'
        ) );

        extract( $args );

        $results = $this->set_post_collection( compact( 'post_id', 'term_id' ) );

        if( is_wp_error( $results ) ){
            wp_send_json_error( $results );
        }

        $post = get_post( $post_id );

        setup_postdata( $GLOBALS['post'] =& $post );

        ob_start();

        if( $from == 'save_to' ){
            load_template( 
                trailingslashit( plugin_dir_path( __FILE__ ) )  . 'frontend/collection-item.php', 
                true, 
                $this->_get_term( $args['term_id'] )
            );
        }else{
            echo streamtube_core_collection_add_post_to( $post->ID, $args['term_id'] );
        }

        $term = $this->_get_term( $term_id );

        $output = ob_get_clean();

        if( $this->_has_term( $post_id, $term_id ) ){
            $message = sprintf(
                esc_html__( 'Saved to %s', 'streamtube-core' ),
                $term->name_formatted
            );
        }else{
            $message = sprintf(
                esc_html__( 'Removed from %s', 'streamtube-core' ),
                $term->name_formatted
            );            
        }

        wp_send_json_success( compact( 'output', 'message' ) );
    }

    /**
     * Update term index
     */
    public function update_collection_item_index( $term_id, $post_ids = array() ){

        $post_ids = array_unique( array_map( 'intval', $post_ids ) );

        if( ! $post_ids ){
            return false;
        }

        for ( $i = 0;  $i < count( $post_ids );  $i++) {            
            update_post_meta( $post_ids[$i], "_term_{$term_id}_order" , $i );
        }

        update_term_meta( $term_id, 'is_indexed', 'on' );

        /**
         *
         * Fires after updated item index
         *
         * @param int $term_id
         * @param array $post_ids
         * 
         */
        do_action( 'streamtube/core/collection/updated_item_index', $term_id, $post_ids );

        return compact( 'term_id', 'post_ids' );
    }

    /**
     *
     * AJAX update term index
     * 
     */
    public function ajax_update_collection_item_index(){
        check_ajax_referer( '_wpnonce' );

        $http_data = wp_parse_args( $_REQUEST, array(
            'term_id'   =>  0,
            'post_ids'  =>  array()
        ) );

        extract( $http_data );

        $term_id = (int)$term_id;

        if( ( $this->_is_owner( $term_id ) && $this->_can_manage() ) || Streamtube_Core_Permission::can_admin() ){
            wp_send_json_success( $this->update_collection_item_index( $term_id, $post_ids ) ); 
        }

        wp_send_json_error( esc_html__( 'You do not have permission to update this collection', 'streamtube-core' ));
    }

    /**
     *
     * AJAX add post to watch later list
     * 
     */
    public function ajax_set_post_watch_later(){

        check_ajax_referer( '_wpnonce' );

        $args = isset( $_POST['data'] ) ? json_decode( wp_unslash( $_POST['data'] ), true ) : false;

        if( ! $args || ! is_array( $args ) ){
            wp_send_json_error( new WP_Error(
                'invalid_requested',
                esc_html__( 'Invalid Requested', 'streamtube-core' )
            ) );
        }

        $args = wp_parse_args( $args, array(
            'post_id'   =>  0,
            'term_id'   =>  0
        ) );

        extract( $args );

        $results = $this->set_post_collection( compact( 'post_id', 'term_id' ) );

        if( is_wp_error( $results ) ){
            wp_send_json_error( $results );
        }

        ob_start();

        $this->frontend_the_watch_later_button( $args );

        $output = ob_get_clean();

        $term = $this->_get_term( $term_id );

        if( $this->_has_term( $post_id, $term_id ) ){
            $message = sprintf(
                esc_html__( 'Saved to %s', 'streamtube-core' ),
                $term->name_formatted
            );
        }else{
            $message = sprintf(
                esc_html__( 'Removed from %s', 'streamtube-core' ),
                $term->name_formatted
            );            
        }       

        wp_send_json_success( compact( 'output', 'message' ) );
    }

    public function set_image_collection( $args = array() ){
        $errors = new WP_Error();

        $args = wp_parse_args( $args, array(
            'post_id'   =>   0,
            'term_id'   =>  0
        ) );

        extract( $args );

        $post_id = (int)$post_id;
        $term_id = (int)$term_id;

        if( ! $this->_is_owner( $term_id ) ){
            $errors->add(
                'no_permission',
                esc_html__( 'You do not have permission to set thumbnail image for this collection', 'streamtube-core' )
            );
        }

        if( get_post_type( $post_id ) != 'video' ){
            $errors->add(
                'invalid_post',
                esc_html__( 'Invalid Post Type', 'streamtube-core' )
            );
        }

        if( ! has_post_thumbnail( $post_id ) ){
            $errors->add(
                'no_thumbnail',
                esc_html__( 'Thumbnail Image was not found', 'streamtube-core' )
            );
        }

        /**
         *
         * Filter the errors
         * 
         * @var WP_Error
         *
         * @since  1.0.0
         * 
         */
        $errors = apply_filters( 'streamtube/core/collection/set_thumbnail', $errors , $args );

        if( $errors->get_error_code() ){
            return $errors;
        }

        $thumbnail_id   = get_post_thumbnail_id( $post_id );
        $thumbnail_url  = wp_get_attachment_image_url( $thumbnail_id, 'large' );

        $results = update_term_meta( $term_id, 'thumbnail_id', $thumbnail_id );

        if( is_wp_error( $results ) || ! $results ){
            return $results;
        }

        return compact( 'thumbnail_id', 'thumbnail_url', 'results' );
    }

    public function ajax_set_image_collection(){

        check_ajax_referer( '_wpnonce' );

        $http_post = isset( $_POST['data'] ) ? json_decode( wp_unslash( $_POST['data'] ), true ) : array();

        $results = $this->set_image_collection( $http_post );

        if( is_wp_error( $results ) ){
            wp_send_json_error( $results );
        }

        if( ! $results ){
            wp_send_json_error(
                new WP_Error(
                    'error',
                    esc_html__( 'It seems you tried to set up a same thumbnail image, please choose another one', 'streamtube-core' )
                )
            );
        }

        $message = esc_html__( 'Thumbnail Image has been set up successfully', 'streamtube-core' );

        wp_send_json_success( array_merge( $results, compact( 'message' ) ) );
    }

    /**
     *
     * Upload collection image
     * 
     * @param  integer $term_id
     * 
     */
    public function upload_collection_image( $term_id = 0 ){

        $errors = new WP_Error();

        $term_id = (int)$term_id;

        if( ! $this->_is_owner( $term_id ) ){
            $errors->add(
                'no_permission',
                esc_html__( 'You do not have permission to upload thumbnail image for this collection', 'streamtube-core' )
            );
        }

        if( ! isset( $_FILES[ 'featured-image' ] ) ){
            $errors->add(
                'file_not_found',
                esc_html__( 'File was not found', 'streamtube-core' )
            );
        }

        $file = $_FILES[ 'featured-image' ];

        if( $file['error'] != 0 ){
            $errors->add(
                'file_broken',
                esc_html__( 'File was not found or broken', 'streamtube-core' )
            );            
        }

        $type = array_key_exists( 'type' , $file ) ? $file['type'] : '';

        if ( 0 !== strpos( $type, 'image/' ) ) {
            $errors->add( 
                'file_not_accepted', 
                esc_html__( 'File format is not accepted.', 'streamtube-core' )
            );
        }

        $max_size = streamtube_core_get_max_upload_image_size();

        if( $file['size'] > $max_size ){
            $errors->add( 
                'file_size_not_allowed',
                sprintf(
                    esc_html__( 'File size has to be smaller than %s', 'streamtube-core' ),
                    size_format( $max_size )
                )
            );                    
        }

        /**
         *
         * Filter the errors
         * 
         * @var WP_Error
         *
         * @since  1.0.0
         * 
         */
        $errors = apply_filters( 'streamtube/core/collection/upload_thumbnail', $errors , $term_id );

        if( $errors->get_error_code() ){
            return $errors;
        }        

        $attachment_id = media_handle_upload( 'featured-image', 0, array( '' ), array( 'test_form' => false ) );

        if( ! is_wp_error( $attachment_id ) ){
            update_term_meta( $term_id, 'thumbnail_id', $attachment_id );
        }

        return $attachment_id;
    }

    public function ajax_upload_collection_thumbnail_image(){
        check_ajax_referer( '_wpnonce' );

        if( ! isset( $_POST['term_id'] ) || (int)$_POST['term_id'] == 0 ){
            wp_send_json_error( new WP_Error(
                'term_id_not_found',
                esc_html__( 'Collection ID was not found', 'streamtube-core' )
            ) );
        }

        $results = $this->upload_collection_image( $_POST['term_id'] );

        if( is_wp_error( $results ) ){
            wp_send_json_error( $results );    
        }

        wp_send_json_success( esc_html__( 'Thumbnail Image has been uploaded successfully', 'streamtube-core' ) );
    }

    /**
     *
     * Pause history
     * 
     * @param  array  $args
     * 
     */
    public function set_collection_activity( $args = array() ){

        $errors = new WP_Error();

        $args = wp_parse_args( $args, array(
            'term_id'   =>  0,
            'user_id'   =>  get_current_user_id()
        ) );

        $args['term_id'] = (int)$args['term_id'];

        extract( $args );

        if( ! $this->_is_owner( $term_id, $user_id ) ){
            $errors->add(
                'no_permission',
                esc_html__( 'You do not have permission to edit this collection', 'streamtube-core' )
            );
        }

        /**
         *
         * Filter the errors
         * 
         * @var WP_Error
         *
         * @since  1.0.0
         * 
         */
        $errors = apply_filters( 'streamtube/core/collection/set_activity', $errors , $args );

        if( $errors->get_error_code() ){
            return $errors;
        }

        $activity = $this->_get_term_activity( $term_id );

        if( $activity == 'closed' ){
            $results = update_term_meta( $term_id, 'activity', 'open' );
        }
        else{
            $results = update_term_meta( $term_id, 'activity', 'closed' );
        }

        return compact( 'activity',  'results' );
    }

    /**
     *
     * AJAX Pause history
     * 
     * @param  array  $args
     * 
     */
    public function ajax_set_collection_activity( $args = array() ){
        check_ajax_referer( '_wpnonce' );

        if( ! isset( $_POST['data'] ) ){
            wp_send_json_error( new WP_Error(
                'invalid_requested',
                esc_html__( 'Invalid Requested', 'streamtube-core' )
            ) );
        }

        $term_id = (int)$_POST['data'];

        $results = $this->set_collection_activity( compact( 'term_id' ) );

        if( is_wp_error( $results ) ){
            wp_send_json_error( $results );
        }

        wp_send_json_success( $this->the_button_pause( array_merge( compact( 'term_id' ), array(
            'echo'  =>  false
        ) ) ) );
    }    

    /**
     *
     * Clear collection
     * 
     * @param  array $args
     * 
     */
    public function clear_collection( $args = array() ){
        $errors = new WP_Error();

        $args = wp_parse_args( $args, array(
            'term_id'   =>  0,
            'user_id'   =>  get_current_user_id()
        ) );

        $args['term_id'] = (int)$args['term_id'];

        extract( $args );

        if( ! $this->_is_owner( $term_id, $user_id ) || ! $this->_can_manage() ){
            $errors->add(
                'no_permission',
                esc_html__( 'You do not have permission to clear this collection', 'streamtube-core' )
            );
        }

        /**
         *
         * Filter the errors
         * 
         * @var WP_Error
         *
         * @since  1.0.0
         * 
         */
        $errors = apply_filters( 'streamtube/core/collection/clear_collection', $errors , $args );

        if( $errors->get_error_code() ){
            return $errors;
        }

        return $this->_delete_term_posts( $term_id );   
    }

    public function ajax_clear_collection(){
        check_ajax_referer( '_wpnonce' );

        if( ! isset( $_POST['data'] ) ){
            wp_send_json_error( new WP_Error(
                'invalid_requested',
                esc_html__( 'Invalid Requested', 'streamtube-core' )
            ) );
        }

        $term_id = (int)$_POST['data'];

        $results = $this->clear_collection( compact( 'term_id' ) );

        if( is_wp_error( $results ) ){
            wp_send_json_error( $results );
        }                

        wp_send_json_success( $this->the_button_clear_all(  array_merge( compact( 'term_id' ), array(
            'echo'  =>  false
        ) ) ) );
    }

    /**
     *
     * Search videos
     * 
     * @param  string $search
     * @return get_posts()
     * 
     */
    public function search_videos( $args = array() ){

        $args = wp_parse_args( $args, array(
            'search'    =>  '',
            'term_id'   =>  0
        ) );

        extract( $args );

        $query_args = array(
            'post_type'         =>  'video',
            'post_status'       =>  'publish',
            'posts_per_page'    =>  50,
            'orderby'           =>  'date',
            'order'             =>  'DESC',
            's'                 =>  $search,
            'tax_query'         =>  array(),
            'meta_query'        =>  array(
                'relation'      =>  'AND',
                array(
                    'key'       =>  '_thumbnail_id',
                    'compare'   =>  'EXISTS'
                ),
                array(
                    'key'       =>  'video_url',
                    'compare'   =>  'EXISTS'
                )
            )
        );

        if( $term_id ){
            $query_args['tax_query'][] = array(
                'taxonomy'  =>  self::TAX_COLLECTION,
                'field'     =>  'term_id',
                'terms'     =>  (int)$term_id
            );
        }

        return get_posts( apply_filters( 'streamtube/core/collection/search_video_args', $query_args, $args ) );
    }

    /**
     *
     * AJAX Search videos
     * 
     * @param  string $search
     * @return search_videos()
     * 
     */
    public function ajax_search_videos(){

        check_ajax_referer( '_wpnonce' );

        $http_post = wp_parse_args( $_POST, array(
            'search'    =>  '',
            'term_id'   =>  0
        ) );

        extract( $http_post );

        if( empty( $search ) ){
            wp_send_json_error( new WP_Error(
                'keywords_not_found',
                esc_html__( 'Keywords were not found', 'streamtube-core' )
            ) );
        }

        if( empty( $term_id ) ){
            wp_send_json_error( new WP_Error(
                'term_not_found',
                esc_html__( 'Collection was not found', 'streamtube-core' )
            ) );
        }

        $results = $this->search_videos( compact( 'search' ) );

        if( $results ){

            ob_start();

            for ( $i = 0; $i < count( $results ) ; $i++ ) { 

                global $post;

                $post = $results[$i];
           
                setup_postdata( $post );

                load_template( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'frontend/search-video-item.php', false, array(
                    'term_id'   =>  (int)$term_id
                ) );

                wp_reset_postdata();

            }

            $results = ob_get_clean();
        }else{
            $results = sprintf(
                '<p class="not-found text-center p-4">%s</p>',
                esc_html__( 'Nothing matched your search terms', 'streamtube-core' )
            );
        }

       wp_send_json_success( $results );
    } 

    /**
     *
     * Filter player setup
     * 
     * @param  array $setup
     * @param  string $source
     * @return array $setup
     */
    public function filter_player_setup( $setup, $source ){

        $user = $list   = $term_id = false;

        $autoplay       = 1;

        $list_items     = $_posts = array();

        $current_post   = get_the_ID();

        $list = $this->_get_request_term_id();

        if( ! $list  ){
            return $setup;
        }

        if( is_int( $list ) ){
            $term_id = $list;
        }

        if( is_array( $list ) ){
            $term_id = (int)$list['decoded_id'];

            // List is hashed string
            $list    = $list['list'];
        }

        $term = get_term( $term_id );

        if( ! $this->_has_term( $setup['mediaid'], $term_id, $term->taxonomy ) ){
            return $setup;
        }

        if( is_embed() ){

            if( $term->user ){
                $user = $term->user;
            } 

            $_posts = $this->_get_term_posts( $term_id, array(), $term->taxonomy );

            $extra_params = compact( 'autoplay', 'list' );

            for ( $i = 0;  $i < count( $_posts );  $i++) {

                /**
                 * Filter the link params
                 */
                $extra_params = apply_filters( 'streamtube/core/player/collection/link_params', $extra_params, $_posts, $setup, $source );

                $list_items[] = array(
                    'id'                =>  $_posts[$i]->ID,
                    'title'             =>  $_posts[$i]->post_title,
                    'thumbnail'         =>  get_the_post_thumbnail_url( $_posts[$i], 'small' ),
                    'permalink'         =>  add_query_arg( $extra_params, get_permalink( $_posts[$i]->ID ) ),
                    'permalink_embed'   =>  add_query_arg( $extra_params, get_post_embed_url( $_posts[$i]->ID ) ),
                    'author'            =>  array(
                        'display_name'  =>  get_the_author_meta( 'display_name', $_posts[$i]->post_author ),
                        'link'          =>  get_author_posts_url( $_posts[$i]->post_author )
                    ),
                    'length'            =>  $GLOBALS['streamtube']->get()->post->get_length( $_posts[$i]->ID, true )
                );
            }

            $setup['plugins']['playerCollectionContent'] = array_merge( compact( 'list', 'list_items', 'current_post' ), array(
                'name'      =>  $term->name_formatted ? $term->name_formatted : $term->name,
                'author'    =>  $user ? array(
                    'display_name'  =>  get_the_author_meta( 'display_name', $user ),
                    'link'          =>  get_author_posts_url($user)
                ) : false,
                'total'     =>  $term->count,
                'index'     =>  (int)array_search( $current_post , wp_list_pluck( $_posts, 'ID' ) )  + 1,
                'upnext'    =>  true
            ) );

        }

        if( array_key_exists( 'playerShareBox', $setup['plugins'] ) ){
            $setup['plugins']['playerShareBox']['url'] = add_query_arg(
                compact( 'list' ),
                $setup['plugins']['playerShareBox']['url']
            );
            $setup['plugins']['playerShareBox']['embed_url'] = add_query_arg(
                compact( 'list' ),
                $setup['plugins']['playerShareBox']['embed_url']
            );
        }

        return $setup;
    }

    /**
     *
     * Filter Share URL
     * 
     */
    public function filter_share_links( $url, $post_id ){

        $list = $term_id = false;

        $list = $this->_get_request_term_id();

        if( ! $list  ){
            return $url;
        }

        if( is_int( $list ) ){
            $term_id = $list;
        }

        if( is_array( $list ) ){
            $term_id = $list['decoded_id'];

            // List is hashed string
            $list    = $list['list'];
        }

        $term = get_term( $term_id );

        if( ! $this->_has_term( $post_id, $term_id, $term->taxonomy ) ){
            return $url;
        }        

        return add_query_arg( compact( 'list' ), $url );
    }    

    /**
     *
     * Filter embed URL
     * 
     */
    public function filer_embed_url( $embed_url, $post ){

        $list = $term_id = false;

        $list = $this->_get_request_term_id();

        if( ! $list  ){
            return $embed_url;
        }

        if( is_int( $list ) ){
            $term_id = $list;
        }

        if( is_array( $list ) ){
            $term_id = $list['decoded_id'];

            // List is hashed string
            $list    = $list['list'];
        }

        $term = get_term( $term_id );

        if( ! $this->_has_term( $post->ID, $term_id, $term->taxonomy ) ){
            return $embed_url;
        }

        return add_query_arg( compact( 'list' ), $embed_url );  
    }  
}