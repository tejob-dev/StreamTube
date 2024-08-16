<?php
/**
 * Define the Permission functionality
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

class Streamtube_Core_Permission{

    /**
     *
     * Define deactivate role
     * 
     */
    const ROLE_DEACTIVATE       =   'role_deactivated';

    /**
     *
     * Define verify role
     * 
     */
    const ROLE_VERIFY           =   'role_verified';

    /**
     *
     * Define spam role
     * 
     */
    const ROLE_SPAMMER          =   'role_spammer';

    /**
     *
     * Publish Posts Cap
     *
     * @since 2.2.3
     * 
     */
    const CAP_EDIT_OTHER_POSTS  =   'edit_others_posts';// editor or higher    

    /**
     *
     * Publish Posts Cap
     *
     * @since 2.2.3
     * 
     */
    const CAP_PUBLISH_POSTS     =   'publish_posts';// author or higher

    /**
     *
     * Edit Posts Cap
     *
     * @since 2.2.3
     * 
     */
    const CAP_EDIT_POSTS        =   'edit_posts';// Contributor or higher

    /**
     *
     * Edit Post Cap
     *
     * @since 2.2.3
     * 
     */
    const CAP_EDIT_POST         =   'edit_post';// Contributor or higher

    /**
     *
     * Delete Post Cap
     *
     * @since 2.2.3
     * 
     */
    const CAP_DELETE_POST       =   'delete_post';// Contributor or higher    

    /**
     *
     * Delete Post Cap
     *
     * @since 2.2.3
     * 
     */
    const CAP_ADD_VAST_TAG      =   'manage_vast_tag';

    /**
     *
     * Add custom roles
     * 
     */
    public static function add_roles(){

        add_role( 
            self::ROLE_VERIFY, 
            esc_html__( 'Verified', 'streamtube-core' ), 
            array(
                'read'  =>  true
            ) 
        );

        add_role( 
            self::ROLE_DEACTIVATE, 
            esc_html__( 'Deactivated', 'streamtube-core' ), 
            array(
                'read'  =>  true
            ) 
        );

        add_role( 
            self::ROLE_SPAMMER, 
            esc_html__( 'Spammer', 'streamtube-core' ), 
            array(
                'read'  =>  true
            ) 
        );         
    }

    /**
     *
     * Adds role to user.
     * 
     */
    public static function add_user_role( $user_id, $role ){
        return get_user_by( 'ID', $user_id )->add_role( $role );
    }

    /**
     *
     * Remove user role
     * 
     */
    public static function remove_user_role( $user_id, $role ){
        return get_user_by( 'ID', $user_id )->remove_role( $role );
    }    

    /**
     *
     * Do verify user
     * 
     */
    public static function verify_user( $user_id ){
        return self::add_user_role( $user_id, self::ROLE_VERIFY );
    }

    /**
     *
     * Revoke verify role for given user
     * 
     */
    public static function unverify_user( $user_id ){
        return self::remove_user_role( $user_id, self::ROLE_VERIFY );
    }

    /**
     *
     * Check if user is verified
     * 
     */
    public static function is_verified( $user_id ){
        return user_can( $user_id, self::ROLE_VERIFY );
    }

    /**
     *
     * Check if given user is administrator
     * 
     */
    public static function can_admin( $user_id = 0 ){
        if( ! $user_id ){
            $user_id = get_current_user_id();
        }

        return user_can( $user_id, 'administrator' ) ? true : false;
    }

    /**
     *
     * Check if current user can moderate posts
     * 
     * @return true|false
     */
    public static function moderate_posts( $user_id = 0 ){

        if( ! $user_id ){
            $user_id = get_current_user_id();
        }

        if( user_can( $user_id, 'administrator' ) || user_can( $user_id, 'editor' ) || user_can( $user_id, self::CAP_EDIT_OTHER_POSTS ) ){
            return true;
        }

        return false;
    }

    /**
     *
     * Check if current user can edit post, requires Contributor or higher
     * 
     * @return true|false
     */
    public static function can_edit_posts( $user_id = 0 ){

        if( ! $user_id ){
            $user_id = get_current_user_id();
        }

        return user_can( $user_id, self::CAP_EDIT_POSTS );
    }

    /**
     *
     * Check if current user can edit given post, requires Contributor or higher
     * 
     * @return true|false
     */
    public static function can_edit_post( $post_id = 0, $user_id = 0 ){

        if( ! $user_id ){
            $user_id = get_current_user_id();
        }

        return user_can( $user_id, self::CAP_EDIT_POST, $post_id );
    }

    /**
     *
     * Check if current user can delete given post, requires Contributor or higher
     * 
     * @return true|false
     */
    public static function can_delete_post( $post_id = 0 , $user_id = 0 ){

        if( ! $user_id ){
            $user_id = get_current_user_id();
        }

        return user_can( $user_id, self::CAP_DELETE_POST, $post_id );
    }     

    /**
     *
     * Check if current user can upload
     * 
     * @return true|false
     */
    public static function can_upload( $user_id = 0 ){

        if( ! $user_id ){
            $user_id = get_current_user_id();
        }

        return self::can_edit_posts( $user_id ) && user_can( $user_id, 'upload_files' ) ? true : false;
    }

    /**
     *
     * Check if current user can embed
     * 
     * @return true|false
     */
    public static function can_embed( $user_id = 0 ){

        if( ! $user_id ){
            $user_id = get_current_user_id();
        }

        return self::can_edit_posts( $user_id );
    }

    /**
     *
     * Ask if given user is onwer of given post
     * 
     * @return boolean
     */
    public static function is_post_owner( $post = null, $user_id = 0 ){

        if( ! $user_id ){
            $user_id = get_current_user_id();
        }

        if( ! $post || ! $user_id ){
            return false;
        }

        if( is_int( $post ) ){
            $post = get_post( $post );
        }

        if( ! is_object( $post ) ){
            return false;
        }

        return $post->post_author == $user_id ? true : false;
    }

    /**
     *
     * Check if current user can manage given taxonomy
     *
     * @param string $taxonomy
     * @param int $user_id
     *
     * @return boolean
     * 
     */
    public static function can_manage_term( $taxonomy, $user_id = 0 ){

        if( ! taxonomy_exists( $taxonomy ) ){
            return false;
        }

        if( ! $user_id ){
            $user_id = get_current_user_id();
        }

        if( self::moderate_posts( $user_id ) ){
            return true;
        }

        return user_can( $user_id, get_taxonomy( $taxonomy )->cap->manage_terms );
    }

    public static function can_manage_vast_tag( $user_id = 0 ){

        if( ! $user_id ){
            $user_id = get_current_user_id();
        }

        if( self::moderate_posts( $user_id ) ){
            return true;
        }

        return user_can( $user_id, self::CAP_ADD_VAST_TAG );
    }

    /**
     *
     * Check if current user can moderate posts
     * 
     * @return true|false
     */
    public static function moderate_cdn_sync( $user_id = 0 ){

        if( ! $user_id ){
            $user_id = get_current_user_id();
        }

        return user_can( $user_id, 'administrator' );
    }
}