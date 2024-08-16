<?php
/**
 *
 * @link       https://1.envato.market/mgXE4y
 * @since      1.0.0
 *
 * @package    WordPress
 * @subpackage StreamTube
 * @author     phpface <nttoanbrvt@gmail.com>
 */
if( ! defined( 'ABSPATH' ) ){
	exit;
}

/**
 *
 * The custom logo
 * 
 * @return HTML
 *
 * @since 1.0.0
 * 
 */
function streamtube_the_custom_logo(){

	$output = get_custom_logo();

	$default_mode = get_option( 'theme_mode', 'light' );

	if( isset( $_COOKIE['theme_mode'] ) && in_array( $_COOKIE['theme_mode'], array( 'light', 'dark' ) ) ){
		$default_mode = $_COOKIE['theme_mode'];
	}

	if( $default_mode == 'dark' ){
		$dark_logo = get_option( 'dark_logo' );

		if( ! empty( $dark_logo ) ){
			$output = preg_replace(  '/<img(.*?)src=/is' , '<img$1src="' . esc_attr( $dark_logo ) . '" data-light-src=' , $output );
		}
	}

	if( ! empty( $output ) ){
		$output = sprintf( '<div class="custom-logo-wrap">%s</div>', $output );
	}

	echo wp_kses_post( $output );
}

/**
 *
 * The breadcrumbs
 * 
 */
function streamtube_breadcrumbs(){
	do_action( 'streamtube_breadcrumbs' );
}

/**
 *
 * The posts navigation
 * 
 * @param  array $args
 * @param  boolean $echo
 * @return HTML
 */
function streamtube_posts_navigation( $args = array(), $echo = true ){

	$args = wp_parse_args( $args, array(
		'prev_text'          => esc_html__( 'Older posts', 'streamtube' ) . '<span class="icon-angle-right"></span>',
		'next_text'          => '<span class="icon-angle-left"></span>' . esc_html__( 'Newer posts', 'streamtube' )
	) );

	$output = get_the_posts_navigation( $args );

	if( $output ){
		$output = sprintf(
			'<div class="navigation-wrap">%s</div>',
			$output
		);
	}

	if( $echo ){
		echo wp_kses_post( $output );
	}
	else{
		return wp_kses_post( $output );
	}
}

/**
 *
 * The posts pag
 * 
 * @param  array $args
 * @param  boolean $echo
 * @return HTML
 *
 * @since 1.0.0
 * 
 */
function streamtube_posts_pagination( $args = array(), $echo = true ){

	$args = wp_parse_args( $args, array(
		'type'		=>	'list',
		'prev_text' => sprintf(
			'<span class="icon-angle-%s"></span>',
			! is_rtl() ? 'left' : 'right'
		),
		'next_text' => sprintf(
			'<span class="icon-angle-%s"></span>',
			! is_rtl() ? 'right' : 'left'	
		),
		'echo'		=>	false,
		'el_class'	=>	''
	) );

	$output = get_the_posts_pagination( $args );

	if( $output ){
		$output = sprintf(
			'<div class="navigation-wrap %s">%s</div>',
			sanitize_html_class( $args['el_class'] ),
			$output
		);
	}

	if( $echo ){
		echo wp_kses_post( $output );
	}
	else{
		return wp_kses_post( $output );
	}
}

/**
 *
 * The comments pagination
 * 
 * @param  array   $args
 * @param  boolean $echo
 * @return HTML
 *
 * @since 1.0.0
 * 
 */
function streamtube_comments_pagination( $args = array(), $echo = true ){
	$output = get_the_comments_pagination( $args );

	if( $output ){
		$output = sprintf(
			'<div class="navigation-wrap">%s</div>',
			$output
		);
	}

	if( $echo ){
		echo wp_kses_post( $output );
	}
	else{
		return wp_kses_post( $output );
	}	
}

/**
 *
 * The comments navigation
 * 
 * @param  array   $args
 * @param  boolean $echo
 * @return HTML
 *
 * @since 1.0.0
 * 
 */
function streamtube_comments_navigation( $args = array(), $echo = true ){
	$output = get_the_comments_navigation( $args );

	if( $output ){
		$output = sprintf(
			'<div class="navigation-wrap p-3 mb-3">%s</div>',
			$output
		);
	}

	if( $echo ){
		echo wp_kses_post( $output );
	}
	else{
		return wp_kses_post( $output );
	}	
}

/**
 *
 * Get current user roles
 * 
 * @return string
 *
 * @since 1.0.0
 * 
 */
function streamtube_user_roles( $user_id = 0, $exclude_roles = array() ){

	global $wp_roles;

	if( ! $exclude_roles || ! is_array( $exclude_roles ) ){
		$exclude_roles = array();
	}

	$output = '';

	if( ! $user_id ){
		$user_id = get_current_user_id();
	}

	if( $user_id ){
		$roles = get_userdata( $user_id )->roles;
	}

	if( $roles ){
		for ( $i=0;  $i < count( $roles );  $i++) {

			if( ! in_array( $roles[$i] , $exclude_roles ) && array_key_exists( $roles[$i], $wp_roles->roles ) ){

				$name = $wp_roles->roles[ $roles[$i] ]['name'];

				/**
				 *
				 * Filter the role name
				 * 
				 * @param string $formatted_role_name
				 * @param string role ID
				 * @param int $user_id
				 * 
				 */
				$name = apply_filters( 'streamtube/role/display_name', $name, $roles[$i], $user_id );

				$output .= sprintf(
					'<span class="user-role role-%s badge bg-secondary mb-1">%s</span>',
					esc_attr( $roles[$i] ),
					$name 
				);
			}
		}
	}

	if( ! empty( $output ) ){
		printf(
			'<div class="user-roles d-block justify-content-center gap-2">%s</div>',
			$output
		);
	}
}
add_action( 'streamtube/user/profile_dropdown/avatar/after', 'streamtube_user_roles', 5, 1 );

/**
 *
 * Display user post count
 * Video post type is default
 * 
 */
function streamtube_display_user_post_count( $user ){

	/**
	 *
	 * Filter post type
	 * 
	 */
	$post_type = apply_filters( 'streamtube/user/count_post_type', 'video', $user );

	$user_id = 0;

	if( is_int( $user ) ){
		$user_id = $user;
	}

	if( is_object( $user ) ){
		$user_id = $user->ID;
	}

	if( ! post_type_exists( $post_type ) || ! $user_id ){
		return;
		
	}

	$count = count_user_posts( $user_id, $post_type );

	?>
    <div class="member-info__item flex-fill">
        <div class="member-info__item__count">
            <?php echo number_format_i18n( $count ); ?>
        </div>
        <div class="member-info__item__label">
            <?php 
            if( $count > 1 || $count == 0 ){
                echo get_post_type_object( $post_type )->label;    
            }
            else{
                echo get_post_type_object( $post_type )->labels->singular_name;
            }?>
        </div>
    </div>
	<?php
}
add_action( 'streamtube/core/user/card/info/item', 'streamtube_display_user_post_count' );