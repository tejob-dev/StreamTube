<?php
/**
 * Define the user profile functionality
 *
 *
 * @link       https://themeforest.net/user/phpface
 * @since      1.0.0
 *
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 */

/**
 * Define the profile functionality
 *
 * @since      1.0.0
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */
if( ! defined('ABSPATH' ) ){
    exit;
}

class Streamtube_Core_User_Profile extends Streamtube_Core_User {
	/**
	 * 
	 * Get menu items
	 * 
	 * @return array
	 *
	 * @since  1.0.0
	 * 
	 */
	public function get_menu_items(){

		$items = array();

		$items[ 'home' ] 	= array(
			'title'			=>	esc_html__( 'Home', 'streamtube-core' ),
			'icon'			=>	'icon-home',
			'callback'		=>	function(){
				streamtube_core_load_template( 'user/profile/home.php' );
			},
			'widgetizer'	=>	true,
			'priority'		=>	10
		);		

		$items['settings'] = array(
			'title'			=>	esc_html__( 'Settings', 'streamtube-core' ),
			'icon'			=>	'icon-cog',
			'url'			=>	trailingslashit( get_author_posts_url( get_current_user_id() ) ) . 'dashboard/settings',
			'priority'		=>	10,
			'private'		=>	true
		);

		$items[ 'videos' ] 	= array(
			'title'			=>	esc_html__( 'Videos', 'streamtube-core' ),
			'icon'			=>	'icon-videocam',
			'callback'		=>	function(){
				streamtube_core_load_template( 'user/profile/videos.php' );
			},
			'cap'			=>	'publish_posts',
			'widgetizer'	=>	true,
			'priority'		=>	20
		);

		$items[ 'shorts' ] 	= array(
			'title'			=>	esc_html__( 'Shorts', 'streamtube-core' ),
			'icon'			=>	'icon-flash',
			'callback'		=>	function(){
				streamtube_core_load_template( 'user/profile/shorts.php' );
			},
			'cap'			=>	'publish_posts',
			'priority'		=>	30,
			'widgetizer'	=>	true
		);			

		$items[ 'post' ] 	= array(
			'title'			=>	esc_html__( 'Blog', 'streamtube-core' ),
			'icon'			=>	'icon-pencil',
			'callback'		=>	function(){
				streamtube_core_load_template( 'user/profile/posts.php' );
			},
			'cap'			=>	'edit_posts',
			'widgetizer'	=>	true,
			'priority'		=>	40
		);			

		if( function_exists( 'WPPL' ) ){
			$items['liked'] = array(
				'title'		=>	esc_html__( 'Liked', 'streamtube-core' ),
				'icon'		=>	'icon-thumbs-up',
				'callback'	=>	function(){
					streamtube_core_load_template( 'user/profile/liked.php' );
				},		
				'widgetizer'=>	true,		
				'priority'	=>	50
			);
		}			

		$items['profile'] 	= array(
			'title'			=>	esc_html__( 'Profile', 'streamtube-core' ),
			'icon'			=>	'icon-user-circle-o',
			'callback'		=>	function(){
				streamtube_core_load_template( 'user/profile/profile.php' );
			},
			'priority'		=>	60
		);

		if( function_exists( 'run_wp_user_follow' ) ){
			$items['following'] = array(
				'title'			=>	esc_html__( 'Following', 'streamtube-core' ),
				'icon'			=>	'icon-user-plus',
				'callback'		=>	function(){
					streamtube_core_load_template( 'user/profile/following.php' );
				},				
				'widgetizer'	=>	true,
				'priority'		=>	70
			);

			$items['followers'] = array(
				'title'			=>	esc_html__( 'Followers', 'streamtube-core' ),
				'icon'			=>	'icon-users',
				'callback'		=>	function(){
					streamtube_core_load_template( 'user/profile/followers.php' );
				},				
				'widgetizer'	=>	true,
				'priority'		=>	80
			);
		}

		if( function_exists( 'bbpress' ) ){
			$items['forums'] = array(
				'title'			=>	esc_html__( 'Forums', 'streamtube-core' ),
				'icon'			=>	'icon-chat-empty',
				'callback'		=>	function(){
					streamtube_core_load_template( 'user/profile/forum.php' );
				},				
				'priority'		=>	90
			);
		}

		if( function_exists( 'WC' ) && get_option( 'woocommerce_enable', 'on' ) ){
			$items['shop'] 	= array(
				'title'			=>	esc_html__( 'Shopping', 'streamtube-core' ),
				'icon'			=>	'icon-th-list',
				'url'			=>	trailingslashit( get_author_posts_url( get_current_user_id() ) ) . 'dashboard/shop',
				'priority'		=>	100,
				'private'		=>	true
			);

			if( $cart_url = wc_get_cart_url() ){
				$items['cart'] 	= array(
					'title'			=>	esc_html__( 'Cart', 'streamtube-core' ),
					'icon'			=>	'icon-cart-plus',
					'url'			=>	$cart_url,
					'priority'		=>	101,
					'private'		=>	true
				);
			}
		}

		/**
		 *
		 * @since 1.0.8
		 * 
		 */
		$items = apply_filters( 'streamtube/core/user/profile/menu/items', $items );

		uasort( $items, function( $item1, $item2 ){
			return $item1['priority'] <=> $item2['priority'];
		} );		

		return $items;		
	}

	/**
	 * @since 1.0.8
	 */
	public function pre_get_menu_items(){
		$menu_items = $this->get_menu_items();

		$enabled_pages = get_option( 'user_profile_pages' );

		if( ! $enabled_pages || ! is_array( $enabled_pages ) ){
			return $menu_items;
		}

		foreach ( $menu_items as $key => $value ) {
			if( array_key_exists( $key, $enabled_pages ) && ! wp_validate_boolean( $enabled_pages[$key] ) ){
				unset( $menu_items[ $key ] );
			}

			if( array_key_exists( "{$key}_icon", $enabled_pages ) && ! empty( $enabled_pages[ "{$key}_icon" ] ) ){
				$menu_items[ $key ]['icon'] = $enabled_pages[ "{$key}_icon" ];
			}

			if( array_key_exists( "{$key}_icon_color", $enabled_pages ) && ! empty( $enabled_pages[ "{$key}_icon_color" ] ) ){
				$menu_items[ $key ]['icon_color'] = $enabled_pages[ "{$key}_icon_color" ];
			}			

			if( array_key_exists( "{$key}_priority", $enabled_pages ) && (int)$enabled_pages[ "{$key}_priority" ] ){
				$menu_items[ $key ]['priority'] = $enabled_pages[ "{$key}_priority" ];
			}			
		}

		uasort( $menu_items, function( $item1, $item2 ){
			return $item1['priority'] <=> $item2['priority'];
		} );

		return $menu_items;	
	}

	/**
	 *
	 * @since 1.0.8
	 * 
	 */
	public function get_current_menu_item(){

		$current = '';

		$menu_items = $this->pre_get_menu_items();

		if( ! $menu_items || count( $menu_items ) == 0 ){
			return false;
		}

		foreach ( $menu_items as $menu_id => $menu ) {

			$menu = wp_parse_args( $menu, array(
				'cap'	=>	'read'
			) );

			if( ( is_string( $menu['cap'] ) && ! user_can( get_queried_object_id(), $menu['cap'] ) || 
				is_callable( $menu['cap'] ) && call_user_func( $menu['cap'], get_queried_object_id() ) !== true ) ){
				unset( $menu_items[ $menu_id ] );
			}

			if( isset( $GLOBALS['wp_query']->query_vars[$menu_id] ) ){
				$current = $menu_id;
			}
		}

		if( array_key_exists( 'dashboard', $GLOBALS['wp_query']->query_vars ) ){
			return false;
		}

		return $current ? $current : array_keys( $menu_items )[0];
	}

	/**
	 *
	 * Add all profile menu items as endpoints
	 *
	 * @since 1.0.0
	 * 
	 */
	public function add_endpoints(){
		$menu_items = array_keys($this->get_menu_items());

		for ( $i=0; $i < count( $menu_items ); $i++) { 
			add_rewrite_endpoint( $menu_items[$i], EP_AUTHORS );
		}
	}

	/**
	 *
	 * Register profile sidebars
	 * 
	 */
	public function widgets_init(){

		$menu_items = $this->get_menu_items();

		foreach ( $menu_items as $menu_id => $menu_attr ) {
			$menu_attr = wp_parse_args(  $menu_attr, array(
				'widgetizer'	=>	false
			) );

			if( $menu_attr['widgetizer'] ){
				register_sidebar(
					array(
						'name'          => sprintf(
							esc_html__( 'User Profile - %s', 'streamtube-core' ),
							$menu_attr['title']
						),
						'id'            => 'sidebar-profile-' . sanitize_key( strtolower( $menu_id ) ),
						'description'   => sprintf(
							esc_html__( 'Add widgets here to appear in Profile %s sidebar.', 'streamtube-core' ),
							$menu_attr['title']
						),
						'before_widget' => '<div id="%1$s" class="widget widget-primary %2$s">',
						'after_widget'  => '</div>',
						'before_title'  => '<div class="widget-title-wrap"><h2 class="widget-title d-flex align-items-center no-after">',
						'after_title'   => '</h2></div>'
					)
				);
			}
		}
	}

	/**
	 *
	 * The profile menu
	 * 
	 * @param  array  $args
	 * 
	 */
	public function the_menu( $args = array() ){

		$args = wp_parse_args( $args, array(
			'menu_classes'	=>	'navbar-nav me-auto mb-2 mb-lg-0',
			'location'		=>	'',// or dropdown
			'icon'			=>	false,
			'icon_position'	=>	'left'
		) );

		if( isset( $args['user_id'] ) ){
			$args['base_url'] = get_author_posts_url( $args['user_id'] );
		}

		$args = array_merge( $args, array(
			'menu_items'	=>	$this->pre_get_menu_items(),
			'current'		=>	$args['user_id'] == get_queried_object_id() ? $this->get_current_menu_item() : '',
			'user_id'		=>	$args['user_id']
		) );

		/**
		 *
		 * Filter the menu args
		 * 
		 */
		$menu = new Streamtube_Core_Menu( apply_filters( 'streamtube/core/user/profile/menu', $args ) );

		return $menu->the_menu();
	}

	/**
	 *
	 * Load the profile header
	 * 
	 * @since 1.0.0
	 * 
	 */
	public function the_header(){
		streamtube_core_load_template( 'user/profile/header.php' );
	}

	/**
	 *
	 * Load the profile nav
	 * 
	 * @since 1.0.0
	 * 
	 */
	public function the_navigation(){
		streamtube_core_load_template( 'user/profile/navigation.php' );
	}	

	/**
	 *
	 * Load the author's main content template
	 * 
	 * @since 1.0.0
	 * 
	 */
	public function the_main(){

		$menu_items = $this->pre_get_menu_items();

		$current = $this->get_current_menu_item();

		if( ! $current || ! array_key_exists( $current, $menu_items ) ){
			$current = array_keys( $menu_items )[0];
			if( is_callable( $menu_items[ $current ]['callback'] ) ){
				return call_user_func( $menu_items[ $current ]['callback'] );	
			}
		}

		if( count( $menu_items ) == 0 
			|| ! array_key_exists( $current , $menu_items ) 
			|| ! array_key_exists( 'callback' , $menu_items[ $current ] ) 
			|| ! is_callable( $menu_items[ $current ]['callback'] ) ){
			// If no menu items found, we load videos template instead of empty space.
			return streamtube_core_load_template( 'user/profile/videos.php' );
		}

		return call_user_func( $menu_items[ $current ]['callback'] );	
	}

	/**
	 *
	 * Load the custom author template
	 * 
	 * @since 1.0.0
	 * 
	 */
	public function the_index( $template ){
		if( is_author() ){
			$template = streamtube_core_get_template( 'user/profile/index.php' );			
		}

		return $template;
	}

	/**
	 *
	 * User profile action buttons
	 * 
	 */
	public function the_action_buttons(){
		printf(
			'<div id="item-buttons" class="author-buttons justify-content-%s align-items-center d-flex align-content-start flex-wrap gap-3">',
			is_singular() ? 'start' : 'center'
		);
            /**
             * Fires in the member header actions section.
             *
             */
            do_action( 'streamtube/core/user/header/action_buttons' ); 
            ?>

        </div><!-- #item-buttons -->
        <?php
	}

	/**
	 *
	 * Remove user bio html tags
	 * 
	 * @param  string $content
	 * @return formmatted string
	 *
	 * @since 1.0.9
	 * 
	 */
	public function format_user_bio_content( $content ){

		$allowed_tags = array();

		$tags = get_option( 'user_profile_bio_html_tags', 'strong,em,code,blockquote,p,div,span,ul,li' );

		if( empty( $tags ) ){
			return $content;
		}

		$tags = array_map( 'trim', explode(',', $tags ));

		for ( $i =0;  $i < count( $tags );  $i++ ) {

			switch ( $tags[$i] ) {
				case 'a':
					$allowed_tags[ $tags[$i] ] = array(
						'href'		=>	array(),
						'title'		=>	array(),
						'style'		=>	array()
					);
				break;

				case 'img':
					$allowed_tags[ $tags[$i] ] = array(
						'alt'		=>	array(),
						'src'		=>	array(),
						'width'		=>	array(),
						'height'	=>	array(),						
						'style'		=>	array()
					);
				break;

				case 'p':
				case 'span':
				case 'ul':
				case 'ol':
				case 'li':
					$allowed_tags[ $tags[$i] ] = array(
						'style'	=>	array()
					);
				break;

				case 'iframe':
					$allowed_tags[ $tags[$i] ] = array(
						'src'				=>	array(),
						'width'				=>	array(),
						'height'			=>	array(),
						'style'				=>	array(),
						'allowfullscreen'	=>	array(),
						'loading'			=>	array(),
						'referrerpolicy'	=>	array()
					);
				break;				
				
				default:
					/**
					 *
					 * Filter tag array
					 * 
					 */
					$allowed_tags[ $tags[$i] ] = apply_filters( 'streamtube/core/user/format_bio_content_tag', array(), $tags[$i] );
				break;
			}	
		}

		$formatted_content = wpautop( force_balance_tags( wp_kses( $content, $allowed_tags ) ) );

		if( user_can( get_queried_object_id(), 'administrator' ) ){
			$formatted_content = do_shortcode( $formatted_content );
		}

		return apply_filters( 'streamtube/core/user/bio_content', $formatted_content, $content, $allowed_tags );

	}

	/**
	 *
	 * Load custom css, menu background ... etc
	 * 
	 */
	public function enqueue_scripts(){

		$inline_css = '';

		if( "" != $css = get_option( 'user_profile_menu_bg' )  ){
			$inline_css .= sprintf(
				'#profile-nav{background-color:%s!important}',
				esc_attr( $css )
			);
		}

		if( "" != $css = get_option( 'user_profile_menu_toggler' ) ){
			$inline_css .= sprintf(
				'#profile-nav .navbar-toggler .btn__icon{color:%s}',
				esc_attr( $css )
			);
		}		

		if( "" != $css = get_option( 'user_profile_menu_color' ) ){
			$inline_css .= sprintf(
				'#profile-nav .nav-link {color:%s}',
				esc_attr( $css )
			);
		}

		if( $inline_css ){
			wp_add_inline_style( 'bootstrap', $inline_css );
		}
	}
}