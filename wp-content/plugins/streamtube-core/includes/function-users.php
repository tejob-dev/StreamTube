<?php
if( ! defined('ABSPATH' ) ){
    exit;
}

/**
 *
 * Get all available roles
 * 
 * @return array
 */
function streamtube_core_get_roles(){
	global $wp_roles;

	return $wp_roles->roles;
}

/**
 *
 * Check if current author is mine
 *
 * 
 * @return true|false
 *
 * @since  1.0.0
 * 
 */
function streamtube_core_is_my_profile(){
	return streamtube_core()->get()->user_profile->is_my_profile();

}

/**
 *
 * Get user dashboard URL
 * 
 * @param  integer $user_id
 * @param  string  $endpoint
 * @return string
 *
 * @since  1.0.0
 * 
 */
function streamtube_core_get_user_dashboard_url( $user_id = 0, $endpoint = '' ){
	return streamtube_core()->get()->user_dashboard->get_endpoint( $user_id, $endpoint );
}

/**
 *
 * The user main nav
 * 
 * @return print or return HTML
 *
 * @since  1.0.0
 * 
 */
function streamtube_core_the_user_profile_menu( $args = array() ){
	return streamtube_core()->get()->user_profile->the_menu( $args );
}

/**
 *
 * Get the user avatar
 *
 * @param  array $args{
 *
 * 		@var int $user_id
 * 		@var int $image_size
 * 		@var string $wrap_size
 * 		@var boolean $link link to user page
 * 		@var string $before before name
 * 		@var string $after after name
 * 		@var boolean $echo print or return the result
 * 
 * }
 *
 * @since  1.0.0
 * 
 */
function streamtube_core_get_user_avatar( $args = array() ){
	return streamtube_core()->get()->user_profile->get_avatar( $args );	
}

/**
 *
 * Get the user profile photo
 *
 * @param  array $args{
 *
 * 		@var int $user_id
 * 		@var boolean $link link to user page
 * 		@var boolean $echo print or return the result
 * 
 * }
 *
 * @since  1.0.0
 * 
 */
function streamtube_core_get_user_photo( $args ){
	return streamtube_core()->get()->user_profile->get_profile_photo( $args );
}

/**
 *
 * Get the user name
 *
 * @param  array $args{
 *
 * 		@var int $user_id
 * 		@var boolean $link link to user page
 * 		@var string $before before name
 * 		@var string $after after name
 * 		@var boolean $echo print or return the result
 * 
 * }
 *
 * @since  1.0.0
 * 
 */
function streamtube_core_get_user_name( $args = array() ){
	$args = wp_parse_args( $args, array(
		'user_id'	=>	get_current_user_id(),
		'link'		=>	true,
		'before'	=>	'',
		'after'		=>	'',
		'echo'		=>	true
	) );

	if( ! $args['user_id'] ){
		return;
	}

	$data = get_user_by( 'ID', $args['user_id'] );

	if( ! $data ){
		return;
	}

	if( $args['link'] ){
		$output = sprintf(
			'<a class="text-body fw-bold text-decoration-none" title="%s" href="%s">%s</a>',
			esc_attr( $data->display_name ),
			get_author_posts_url( $args['user_id'] ),
			esc_html( $data->display_name )
		);
	}
	else{
		$output = esc_html( $data->display_name );
	}

	$output = $args['before'] . $output . $args['after'];

	if( $args['echo'] ){
		echo wp_kses_post( $output );
	}
	else{
		return wp_kses_post( $output );	
	}
}

/**
 *
 * Get user sortby options
 * 
 * @return array
 *
 * @since  1.0.0
 * 
 */
function streamtube_core_get_user_sortby_options(){
	$opts = array(
		'name'		=>	esc_html__( 'Name', 'streamtube-core' ),
		'popular'	=>	esc_html__( 'Popular', 'streamtube-core' ),
		'newest'	=>	esc_html__( 'Newest', 'streamtube-core' ),
		'oldest'	=>	esc_html__( 'Oldest', 'streamtube-core' )
	);

	return apply_filters( 'streamtube_core_get_user_sortby_options', $opts );
}

/**
 *
 * Check if current user can moderate comments
 * 
 * @param  integer $comment_id
 * @return true|false
 *
 * @since  1.0.0
 * 
 */
function streamtube_core_can_user_moderate_comments( $comment_id = 0 ){
	global $streamtube;

	return $streamtube->get()->comment->can_moderate_comments( (int)$comment_id );
}

/**
 *
 * Get user data from referer
 * 
 * @return false|WP_User
 */
function streamtube_core_get_referer_data(){

    if( ! array_key_exists( 'HTTP_REFERER', $_SERVER ) || ! wp_doing_ajax() ){
        return false;
    }

    global $wp_roles;

    $user_slug = $action = $scope = false;    

    $referer   = $_SERVER['HTTP_REFERER'];
    $pattern   = '#(?:/|)('.implode('|', array_keys( $wp_roles->roles )).')(?:/|)([^/]+)(?:/|)([^/]+)(?:/|)(\w+)?(?:/|)#';

    if( strpos( $referer, home_url('/') ) !== -1 ){

        $uri = str_replace( home_url('/'), '', $referer );

        if( preg_match( $pattern, $uri, $matches ) ){
            $user_slug = $matches[2];
            if( isset( $matches[3] ) ){
                $action = $matches[3];
            }
            if( isset( $matches[4] ) ){
                $scope = $matches[4];
            }
        }
    }

    if( $user_slug ){
        return apply_filters( 'streamtube_core_get_referer_data', compact( 'user_slug', 'action', 'scope' ) );    
    }

    return false;
}