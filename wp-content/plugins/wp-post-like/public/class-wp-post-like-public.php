<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://themeforest.net/user/phpface
 * @since      1.0.0
 *
 * @package    WP_Post_Like
 * @subpackage WP_Post_Like/public
 */

/**
 * The public-facing functionality of the plugin.
 * @package    WP_Post_Like
 * @subpackage WP_Post_Like/public
 * @author     phpface <nttoanbrvt@gmail.com>
 */

if( ! defined( 'ABSPATH' ) ){
	exit;
}

class WP_Post_Like_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	private $settings;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name 	= $plugin_name;
		$this->version 		= $version;

		$this->settings 	= WP_Post_Like_Customizer::get_options();

	}

	public function enqueue_styles(){

		if( ! $this->settings['hover_buttons'] ){
			wp_add_inline_style( 'bootstrap', '.post-thumbnail .post-meta__like{display:none!important}' );
		}
	}

	/**
	 *
	 * Load the like button
	 * 
	 * @since 1.0.0
	 */
	public function the_like_button(){
		load_template( plugin_dir_path( __FILE__ ) . 'partials/button-like.php' );
	}

	/**
	 *
	 * The login message template
	 * 
	 * @return [type] [description]
	 */
	public function the_login_message(){
		load_template( plugin_dir_path( __FILE__ ) . 'partials/login-message.php' );
	}

	/**
	 *
	 * The liked posts shortcode
	 * 
	 * @param  array $attrs
	 * @param  string $content
	 * @return HTML
	 *
	 * @since 1.0.0
	 * 
	 */
	public function the_liked_posts( $attrs, $content = '' ){

		$attrs = wp_parse_args( $attrs, array(
			'heading'		=>	esc_html__( 'Liked Posts', 'wp-post-like' ),
			'post_type'		=>	'video',
			'user_id'		=>	get_current_user_id(),
			'action'		=>	'like',
			'layout'		=>	'grid',
			'col_xxl'		=>	6,
			'col_xl'		=>	6,
			'col_lg'		=>	2,
			'col_md'		=>	2,
			'col_sm'		=>	1,
			'col'			=>	1,
			'posts_per_page'=>	18,
			'author_avatar'	=>	'on',
			'avatar_size'	=>	50,
			'pagination'	=>	'scroll'	
		) );

		ob_start();

		if( ! is_user_logged_in() ){
			load_template( plugin_dir_path( __FILE__ ) . 'partials/login.php', false, $attrs );
		}
		else{
			load_template( plugin_dir_path( __FILE__ ) . 'partials/liked-posts.php', false, $attrs );
		}

		return ob_get_clean();
	}

	/**
	 *
	 * Register [liked_posts] shortcode
	 *
	 * @since 1.0.0
	 */
	public function shortcodes( $attrs, $content = '' ){
		add_shortcode( 'liked_posts', array( $this , 'the_liked_posts' ) );
	}

	/**
	 *
	 * The Sortby Likes options
	 * 
	 */
	public function sort_by_likes( $options ){
		return array_merge( $options, array(
			'post_like'	=>	esc_html__( 'Likes', 'wp-post-like' )
		) );
	}

	/**
	 *
	 * The Sortby Filter Widget options
	 * 
	 */
	public function widget_sort_by_likes( $options ){
		return array_merge( $options, array(
			'post_like'	=>	esc_html__( 'Like Count', 'wp-post-like' )
		) );
	}	

	/**
	 *
	 * Remove "Liked Product" tab from Shopping page

	 */
	public function remove_liked_product_tab( $menu_items ){
		if( ! in_array( 'product', $this->settings['post_types'] ) ){
			if( array_key_exists( 'shop', $menu_items ) ){
				unset( $menu_items['shop']['submenu']['liked-products'] );
			}
		}

		return $menu_items;
	}
}
