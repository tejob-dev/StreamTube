<?php
/**
* @link              https://themeforest.net/user/phpface
* @since             1.0.0
* @package           WP_Hash_Post_Slug
*
* @wordpress-plugin
* Plugin Name:       WP Hash Post Slug
* Plugin URI:        https://themeforest.net/user/phpface
* Description:       Hash everything in WordPress including custom Post Types and Taxonomies, made for StreamTube theme
* Version:           1.18
* Requires at least: 5.3
* Tested up to:      5.8
* Requires PHP:      5.6
* Author:            phpface
* Author URI:        https://themeforest.net/user/phpface
* License:           Themeforest Licence
* License URI:       http://themeforest.net/licenses
* Text Domain:       wp-hash-post-slug
* Domain Path:       /languages
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if( ! class_exists( 'WP_Hash_Post_Slug' ) ){

	class WP_Hash_Post_Slug{

		const PLUGIN_NAME = 'WP Hash Post Slug';

		/**
		 *
		 * Holds plugin settings
		 * 
		 * @var array
		 *
		 * @since 1.0.0
		 */
		protected $settings = array();

		/**
		 *
		 * Default padding
		 * 
		 */
		const PADDING = 10;

		/**
		 * Class contructor
		 *
		 * @since 1.0.0
		 * 
		 */
		public function __construct(){

			if( ! $this->is_verified() ){
				return;
			}

			$this->load_dependencies();

			$this->settings = $this->get_settings();

			add_action( 'init', array( $this , 'lock_post_id_request' ) );

			add_filter( 'maybe_encode_string', array( $this, 'encode_string' ), 10, 1 );

			add_filter( 'maybe_encoded_string', array( $this, 'decode_string' ), 10, 1 );

			add_filter( 'post_link', array( $this, 'post_link' ), 100, 2 );

			add_filter( '_get_page_link', array( $this , 'post_link' ), 100, 2 );

			add_filter( 'post_type_link', array( $this ,'post_link' ), 100, 2 );

			add_filter( 'get_shortlink', array( $this ,'post_link' ), 100, 4 );

			add_filter( 'author_link', array( $this , 'author_link' ), 100, 3 );

			add_filter( 'url_to_postid', array( $this , 'url_to_postid' ), 10, 1 );

			add_filter( 'parse_request', array( $this 	,'parse_page_request' ), 10, 1 );

			add_filter( 'parse_request', array( $this 	,'parse_post_request' ), 20, 1 );

			add_filter( 'parse_request', array( $this 	,'parse_post_type_request' ), 30, 1 );

			add_filter( 'parse_request', array( $this 	,'parse_shortlink_request' ), 35, 1 );

			add_filter( 'parse_request', array( $this 	,'parse_taxonomy_request' ), 40, 1 );

			add_filter( 'parse_request', array( $this 	,'parse_author_request' ), 50, 1 );			

			// Hide the post slug field
			add_filter( 'streamtube/core/post/edit/slug', '__return_false', 10, 1 );

			add_filter( 'streamtube_get_share_embed_permalink', array( $this , 'filter_share_embed_permalink' ), 10, 2 );

			add_filter( 'pre_term_link', array( $this, 'pre_term_link' ), 10, 2 );

			add_filter( 'streamtube/core/widget/playlist_content/instance', array( $this, 'parse_playlist_instance' ), 10, 1 );

			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

			add_action( 'admin_head', array( $this , 'admin_head' ) );

			add_action( 'admin_notices', array( $this , 'admin_notices' ) );

			add_action( 'customize_register', array( $this , 'customize_register' ) );
		}

		private function is_verified(){

			if( ! class_exists( 'Streamtube_Core_License' ) || ! wp_cache_get( 'streamtube:license' ) ){
				return false;
			}

			return true;
		}

		/**
		 *
		 * Include file in WP environment
		 * 
		 * @param  string $file
		 *
		 * @since 1.0.0
		 * 
		 */
		private function include_file( $file ){
			require_once trailingslashit( plugin_dir_path( __FILE__ ) ) . $file;
		}

		/**
		 *
		 * Load the required dependencies for this plugin.
		 * 
		 * @since 1.0.0
		 * 
		 */
		private function load_dependencies(){
			$this->include_file( 'Hashids/HashGenerator.php' );
			$this->include_file( 'Hashids/Hashids.php' );
		}

		/**
		 *
		 * Get plugin settings
		 * 
		 * @return array
		 *
		 * @since 1.0.0
		 * 
		 */
		public function get_settings(){
			$defaults = array(
				'project'		=>	'',
				'padding'		=>	self::PADDING,
				'lock_p'		=>	'',
				'lock_p_error'	=>	'',
				'taxonomies'	=>	array(),
				'post_types'	=>	array(),
				'user'			=>	'',
				'shortlink'		=>	''
			);

			$settings = wp_parse_args( (array)get_option( 'wp_hash_post_slug' ), $defaults );

			if( ! array_key_exists( 'project', $settings ) ){
				$settings['project'] = '';
			}else{
				$settings['project'] = sanitize_key( strtolower( $settings['project'] ) );
			}

			return $settings;
		}

		/**
		 *
		 * Get Hashids() instance
		 * 
		 * @since 1.0.0
		 * 
		 */
		public function get_hashids( $project = '', $padding = 10 ){

			$padding = (int)$padding;

			if( ! $padding || $padding == 0 ){
				$padding = 10;
			}

			return new Hashids\Hashids( $project, $padding );
		}

		/**
		 *
		 * Encode input string
		 * 
		 * @param  string|int $input
		 * @return encoded string
		 *
		 * @since 1.0.0
		 * 
		 */
		public function encode( $input ){
			return $this->get_hashids( $this->settings['project'], $this->settings['padding'] )->encode( $input );
		}

		/**
		 *
		 * Decode input string
		 * 
		 * @param  string|int $input
		 * @return decoded string
		 *
		 * @since 1.0.0
		 * 
		 */
		public function decode( $input ){
			return $this->get_hashids( $this->settings['project'], $this->settings['padding'] )->decode( $input );
		}		

		/**
		 *
		 * Exclude Post Types
		 * 
		 * @return array
		 */
		public function get_exclude_post_types(){
			$post_types = array(
				'attachment',
				'e-landing-page',
				'elementor_library',
				'ad_tag',
				'ad_schedule'
			);

			return apply_filters( 'wp_hash_post_slug_exclude_post_types', $post_types );
		}

		/**
		 * 
		 * Get supported object types
		 * 
		 * @return array
		 *
		 * @since 1.0.0
		 * 
		 */
		public function get_supported_object_types(){

			$default = array( 'video' );

			$post_types = (array)$this->settings['post_types'];

			if( is_array( $post_types ) && count( $post_types ) > 0 ){
				foreach ( $post_types as $post_type => $value ) {
					if( wp_validate_boolean( $post_types[ $post_type ] ) ){
						$default[] = $post_type;
					}
				}
			}

			return array_unique( $default );
		}

		/**
		 * 
		 * Get supported taxonomy types
		 * 
		 * @return array
		 *
		 * @since 1.0.0
		 * 
		 */
		public function get_supported_taxonomy_types(){

			$default = array();

			$taxonomies = (array)$this->settings['taxonomies'];

			if( is_array( $taxonomies ) && count( $taxonomies ) > 0 ){
				foreach ( $taxonomies as $taxonomy => $value ) {
					if( wp_validate_boolean( $taxonomies[ $taxonomy ] ) ){
						$default[] = $taxonomy;
					}
				}
			}

			return array_unique( $default );
		}

		/**
		 *
		 * Supported post statuses
		 * 
		 * @return array
		 * 
		 */
		private function get_supported_statuses(){
			$statuses = array( 'private', 'publish', 'private', 'unlist' );

			return apply_filters( 'wp_hash_post_slug/supported_statuses', $statuses );
		}

		/**
		 *
		 * Get shortlink param, p is default
		 * 
		 * @return string
		 */
		private function get_shortlink_param(){
			return apply_filters( 'wp_hash_post_slug/shortlink_param', 'p' );
		}

		/**
		 *
		 * Lock forcing "p" parameter
		 * 
		 */
		public function lock_post_id_request(){

			global $wp_query;

			if( $this->settings['lock_p'] && isset( $_REQUEST['p'] ) ){

				$post_id 		= (int)$_REQUEST['p'];
				$post_status 	= get_post_status( $post_id );

				if( $post_status && ! in_array( $post_status, array( 'draft', 'pending' ) ) ){

					$page_id = $this->settings['lock_p_error'];

					if( $page_id && get_post_status( $page_id ) == 'publish' ){
						wp_redirect( get_permalink( $page_id ) );
						exit;
					}else{
						wp_redirect( home_url( 'not-found' ) );
						exit;
					}
				}
			}
		}

		/**
		 *
		 * Encode string
		 * 
		 */
		public function encode_string( $string = '' ){
			return $this->encode( $string );
		}

		/**
		 *
		 * Decode given string
		 * 
		 * @param  string $string
		 * @return int|string
		 */
		public function decode_string( $string = '' ){

			$decoded = $this->decode( $string );

			if( is_array( $decoded ) && count( $decoded ) > 0 ){
				return (int)$decoded[0];
			}

			return false;
		}

		/**
		 *
		 * Filter default post type permalink
		 * 
		 */
		public function post_link( $permalink, $post ){

			if( ! get_option( 'permalink_structure' ) ){
				return $permalink;
			}

			if( is_int( $post ) ){
				$post = get_post( $post );
			}

			if( ! is_object( $post ) ){
				return $permalink;
			}

			if( $post->ID == (int)get_option( 'page_for_posts' ) ){
				return $permalink;
			}

			if( in_array( $post->post_type , $this->get_exclude_post_types() ) ){
				return $permalink;
			}

			if( in_array( $post->post_status , $this->get_supported_statuses() ) && 
				in_array( $post->post_type, $this->get_supported_object_types() ) ){

				$find 			= '/' . untrailingslashit( $post->post_name );
				$replaceWith 	= '/' . $this->encode( $post->ID );

				$permalink = str_replace( $find, $replaceWith, $permalink );

				if( $this->settings['shortlink'] ){
					$permalink = str_replace( '/?p=' . $post->ID, "/{$this->get_shortlink_param()}" . $replaceWith , $permalink );
				}
			}

			return $permalink;
		}

		/**
		 *
		 * Filter author link
		 * 
		 */
		public function author_link( $link, $author_id, $author_nicename ){

			if( ! get_option( 'permalink_structure' ) ){
				return $link;
			}			

			if( ! $this->settings['user'] ){
				return $link;
			}

			return str_replace( $author_nicename, $this->encode( $author_id ), $link );
		}

		/**
		 *
		 * Filter URL before parsed
		 * 
		 * @param  string $url
		 * @return string $url
		 * 
		 */
		public function url_to_postid( $url ){

			$_url = $url;

			$hashed = false;

			$permalink = get_option( 'permalink_structure' );

			if( $permalink ){
				$hashed = basename( parse_url( $_url, PHP_URL_PATH ) );
			}
			else{
				$components = parse_url( $_url );

				if( $components && array_key_exists( 'query', $components ) ){
					parse_str( $components['query'], $results );

					$video_slug = sanitize_key( strtolower( get_option( 'video_slug', 'video' ) ) );

					if( $results[ $video_slug ] ){
						$hashed =  $results[ $video_slug ];
					}
				}
			}
			
			if( $hashed ){

				$hashed = untrailingslashit( trim( $hashed ) );

				$maybe_post_id = $this->decode( $hashed );

				if( $maybe_post_id && is_array( $maybe_post_id ) ){
					$_post = get_post( $maybe_post_id[0] );
					$url = add_query_arg( array(
						'p'			=>	$_post->ID,
						'post_type'	=>	$_post->post_type
					), home_url() );
				}
			}

			return $url;
		}

		/**
		 *
		 * Filter PAGE parse request
		 * 
		 * @param $query
		 *
		 * @since 1.0.0
		 * 
		 */
		public function parse_page_request( $query ){

			$post_types = $this->get_supported_object_types();

			if( ! in_array( 'page', $post_types ) ){
				return $query;
			}

			if( ! array_key_exists( 'page', $query->query_vars ) ){
				return $query;
			}

			$name = '';

			$is_pagename = false;

			if( array_key_exists( 'pagename', $query->query_vars ) ){
				$name = $query->query_vars['pagename'];
				$is_pagename = true;
			}

			if( array_key_exists( 'name', $query->query_vars ) ){
				$name = $query->query_vars['name'];
			}			

			$decoded = $this->decode( $name );

			if( $decoded && count( $decoded ) > 0 ){
				if( is_array( $decoded ) ){
					$decoded = $decoded[0];
				}

				$_page = get_page( $decoded );

				if( $_page ){
					if( $is_pagename ){	
						$query->query_vars[ 'pagename' ] = $_page ? $_page->post_name : $name;
					}else{
						$query->query_vars[ 'name' ] = $_page ? $_page->post_name : $name;	
					}
				}
			}				

			return $query;
		}

		public function parse_post_request( $query ){
			$post_types = $this->get_supported_object_types();

			if( ! in_array( 'post', $post_types ) ){
				return $query;
			}

			if( ! array_key_exists( 'name', $query->query_vars ) ){
				return $query;
			}

			$decoded = $this->decode( $query->query_vars[ 'name' ]);

			if( $decoded && count( $decoded ) > 0 ){
				if( is_array( $decoded ) ){
					$decoded = $decoded[0];
				}

				$_post = get_post( $decoded );

				if( $_post ){
					$query->query_vars[ 'name' ] = $_post->post_name;
				}
			}	

			return $query;		
		}

		/**
		 *
		 * Filter Post Type parse request
		 * 
		 * @param $query
		 *
		 * @since 1.0.0
		 * 
		 */
		public function parse_post_type_request( $query ){

			$post_types = $this->get_supported_object_types();

			if( ! array_key_exists( 'post_type', $query->query_vars ) ){
				return $query;
			}

			if( ! in_array( $query->query_vars['post_type'], $post_types ) ){
				return $query;
			}

			for ( $i = 0; $i < count( $post_types ); $i++) { 
				if( array_key_exists( $post_types[$i], $query->query_vars ) ){

					$decoded = $this->decode( $query->query_vars[ $post_types[$i] ]);

					if( $decoded && count( $decoded ) > 0 ){
						if( is_array( $decoded ) ){
							$decoded = $decoded[0];
						}

						$_post = get_post( $decoded );

						if( $_post ){							
							$query->query_vars[ 'post_type' ] 		= $_post->post_type;
							$query->query_vars[ $post_types[$i] ] 	= $_post->post_name;
						}
					}
				}				
			}

			return $query;
		}

		/**
		 *
		 * Parse shortlink request
		 * 
		 */
		public function parse_shortlink_request( $query ){

			if( ! $this->settings['shortlink'] ){
				return $query;
			}

			$request = explode('/', $query->request );

			if( ! is_array( $request ) || count( $request ) != 2 || $request[0] != $this->get_shortlink_param() ){
				return $query;
			}

			$decoded = $this->decode( $request[1] );

			if( $decoded && count( $decoded ) > 0 ){
				$decoded = $decoded[0];

				$_post = get_post( $decoded );

				if( $_post ){

					if( array_key_exists( 'attachment', $query->query_vars ) ){
						unset( $query->query_vars['attachment'] );
					}

					$query->query_vars = array_merge( $query->query_vars, array(
						'post_type'	=>	$_post->post_type,
						'name'		=>	$_post->post_name
					) );
					
				}
			}

			return $query;
		}

		/**
		 *
		 * Parse taxonomy request
		 * 
		 */
		public function parse_taxonomy_request( $query ){

			$taxonomies = $this->get_supported_taxonomy_types();

			if( ! $taxonomies ){
				return $query;
			}

			for ( $i = 0; $i < count( $taxonomies ); $i++) {

				$taxonomy = get_taxonomy( $taxonomies[$i] );

				if( $taxonomy && $taxonomy instanceof WP_Taxonomy ){

					if( array_key_exists( (string)$taxonomy->query_var, $query->query_vars ) ){

						$decoded = $this->decode( $query->query_vars[$taxonomy->query_var]);

						if( $decoded && count( $decoded ) > 0 ){
							if( is_array( $decoded ) ){
								$decoded = (int)$decoded[0];
							}

							$_term = get_term( $decoded, $taxonomies[$i] );

							if( $_term ){
								$query->query_vars[ $taxonomy->query_var ] = $_term->slug;
							}
						}
					}
				}

			}

			return $query;
		}

		/**
		 *
		 * Parse Author request
		 * 
		 */
		public function parse_author_request( $query ){

			if( ! $this->settings['user'] ){
				return $query;
			}

			if( ! array_key_exists( 'author_name', $query->query_vars ) ){
				return $query;
			}

			$decoded = $this->decode( $query->query_vars['author_name']);

			if( $decoded && count( $decoded ) > 0 ){
				$_user = get_userdata( (int)$decoded[0] );

				if( $_user ){
					$query->query_vars[ 'author_name' ] = $_user->user_nicename;
				}
			}

			return $query;		
		}

		/**
		 *
		 * Filter the Share Embed Permalink
		 * 
		 * @param  string $url
		 * @param  int $post_id
		 * @return string $url
		 *
		 * @since 1.0.0
		 * 
		 */
		public function filter_share_embed_permalink( $url, $post_id ){
			return get_permalink( $post_id );
		}

		/**
		 *
		 * Filter term link
		 * 
		 */
		public function pre_term_link( $termlink, $term ){

			$taxonomies = $this->get_supported_taxonomy_types();

			if( ! $taxonomies ){
				return $termlink;
			}			

			if( ! in_array( $term->taxonomy , $taxonomies ) ){
				return $termlink;
			}

			for ( $i = 0;  $i < count( $taxonomies );  $i++) { 
				$termlink = str_replace( "%{$taxonomies[$i]}%" , $this->encode( $term->term_id ), $termlink );
			}

			return $termlink;
		}

		/**
		 *
		 * Filter the PlayList Content instance
		 * 
		 */
		public function parse_playlist_instance( $instance ){
			
			if( $instance['term_id'] ){
				$decoded = $this->decode( $instance['term_id'] );

				if( is_array( $decoded ) && count( $decoded ) > 0 ){

					$decoded = (int)$decoded[0];

					$_term = get_term( $decoded, 'video_collection' );

					if( $_term ){
						$instance['term_id'] = $_term->term_id;
					}
				}
			}

			return $instance;
		}

		/**
		 * Register the JavaScript for the admin area.
		 *
		 * @since    1.0.1
		 */
		public function admin_enqueue_scripts() {
			wp_enqueue_script(
				'wp-hash-post-slug',
				plugin_dir_url( __FILE__ ) . 'wp-hash-post-slug.js',
				array( 'jquery' ),
				filemtime( plugin_dir_path( __FILE__ ) . 'wp-hash-post-slug.js' ),
				true
			);
		}

		/**
		 *
		 * Hide the slug box
		 * 
		 * @since    1.0.1
		 */
		public function admin_head(){
			?>
			<style type="text/css">
				body.wp-admin.post-type-video #edit-slug-box{
					display: none;
				}
			</style>
			<?php
		}

		/**
		 *
		 * Admin notice
		 * 
		 */
		public function admin_notices(){

			if( ! get_option( 'permalink_structure' ) ):
				$deactivate_url = add_query_arg( array(
					's'				=>	self::PLUGIN_NAME,
					'plugin_status'	=>	'active'
				), admin_url( 'plugins.php' ) );
			?>
				<div class="notice notice-error">
				<p><?php printf(
					esc_html__( '%1$s plugin only support %2$s, please enable %2$s or deactivate %3$s.', 'wp-hash-post-slug' ),
					'<strong>'. self::PLUGIN_NAME .'</strong>',
					'<a href="'. esc_url(admin_url( 'options-permalink.php' )) .'">'. esc_html__( 'Pretty Permalinks', 'wp-hash-post-slug' ) .'</a>',
					'<a href="'. esc_url( $deactivate_url ) .'">'. esc_html__( 'the plugin', 'wp-hash-post-slug' ) .'</a>'
				); ?></p>
				</div>
			<?php
			endif;
		}

		/**
		 *
		 * WP Customizer
		 * 
		 * @param  object $customizer
		 * @since 1.0.0
		 * 
		 */
		public function customize_register( $customizer ){

			global $wp_post_types;

	        $customizer->add_panel( 'wp_hash_post_slug', array(
	            'title'             =>  esc_html__( 'WP Hash Post Slug', 'wp-hash-post-slug' ),
	            'priority'          =>  100
	        ) );

		        $customizer->add_section( 'wp_hash_post_slug_general', array(
		            'title'             =>  esc_html__( 'General', 'wp-hash-post-slug' ),
		            'panel'				=>	'wp_hash_post_slug',
		            'priority'          =>  1
		        ) );

		            $customizer->add_setting( 'wp_hash_post_slug[project]', array(
		                'default'           =>  '',
		                'type'              =>  'option',
		                'capability'        =>  'edit_theme_options',
		                'sanitize_callback' =>  'sanitize_text_field'
		            ) );

		            $customizer->add_control( 'wp_hash_post_slug[project]', array(
		                'label'             =>  esc_html__( 'Unique project ID', 'wp-hash-post-slug' ),
		                'type'              =>  'text',
		                'section'           =>  'wp_hash_post_slug_general',
		                'placeholder'		=>	esc_html__( 'E.g: my-hash-project', 'wp-hash-post-slug' ),
		                'description'		=>	esc_html__( 'Caution: Changing ID will change all existing URLs', 'wp-hash-post-slug' )
		            ) );			        

		            $customizer->add_setting( 'wp_hash_post_slug[padding]', array(
		                'default'           =>  self::PADDING,
		                'type'              =>  'option',
		                'capability'        =>  'edit_theme_options',
		                'sanitize_callback' =>  'sanitize_text_field'
		            ) );

		            $customizer->add_control( 'wp_hash_post_slug[padding]', array(
		                'label'             =>  esc_html__( 'Padding', 'wp-hash-post-slug' ),
		                'type'              =>  'number',
		                'section'           =>  'wp_hash_post_slug_general',
		                'description'		=>	esc_html__( 'Length of hashed slug', 'wp-hash-post-slug' )
		            ) );

		            $customizer->add_setting( 'wp_hash_post_slug[shortlink]', array(
		                'default'           =>  '',
		                'type'              =>  'option',
		                'capability'        =>  'edit_theme_options',
		                'sanitize_callback' =>  'sanitize_text_field'
		            ) );

		            $customizer->add_control( 'wp_hash_post_slug[shortlink]', array(
		                'label'             =>  esc_html__( 'Hash Shortlink', 'wp-hash-post-slug' ),
		                'type'              =>  'checkbox',
		                'section'           =>  'wp_hash_post_slug_general'
		            ) );		            

		            $customizer->add_setting( 'wp_hash_post_slug[lock_p]', array(
		                'default'           =>  '',
		                'type'              =>  'option',
		                'capability'        =>  'edit_theme_options',
		                'sanitize_callback' =>  'sanitize_text_field'
		            ) );

		            $customizer->add_control( 'wp_hash_post_slug[lock_p]', array(
		                'label'             =>  esc_html__( 'Lock forcing p parameter', 'wp-hash-post-slug' ),
		                'type'              =>  'checkbox',
		                'section'           =>  'wp_hash_post_slug_general',
		                'description'		=>	sprintf(
		                	esc_html__( 'Do not allow visitors to access individual posts by forcing the %s parameter', 'wp-hash-post-slug' ),
		                	'<strong>?p=</strong>'
		                )
		            ) );

		            $customizer->add_setting( 'wp_hash_post_slug[lock_p_error]', array(
		                'default'           =>  '',
		                'type'              =>  'option',
		                'capability'        =>  'edit_theme_options',
		                'sanitize_callback' =>  'sanitize_text_field'
		            ) );

		            $customizer->add_control( 'wp_hash_post_slug[lock_p_error]', array(
		                'label'             =>  esc_html__( 'Error Page', 'wp-hash-post-slug' ),
		                'type'              =>  'dropdown-pages',
		                'section'           =>  'wp_hash_post_slug_general',
		                'description'		=>	sprintf(
		                	esc_html__( 'Redirect visitors to this page if they are forcing the %s parameter', 'wp-hash-post-slug' ),
		                	'<strong>?p=</strong>'
		                )
		            ) );		            

		        $customizer->add_section( 'wp_hash_post_slug_user', array(
		            'title'             =>  esc_html__( 'Users', 'wp-hash-post-slug' ),
		            'panel'				=>	'wp_hash_post_slug',
		            'priority'          =>  1
		        ) );

		            $customizer->add_setting( 'wp_hash_post_slug[user]', array(
		                'default'           =>  '',
		                'type'              =>  'option',
		                'capability'        =>  'edit_theme_options',
		                'sanitize_callback' =>  'sanitize_text_field'
		            ) );

		            $customizer->add_control( 'wp_hash_post_slug[user]', array(
		                'label'             =>  esc_html__( 'Users', 'wp-hash-post-slug' ),
		                'type'              =>  'checkbox',
		                'section'           =>  'wp_hash_post_slug_user',
		                'description'		=>	esc_html__( 'Hash User Slugs', 'wp-hash-post-slug' )
		            ) );		        

	        	if( $wp_post_types ){
	        		foreach ( $wp_post_types as $post_type => $object ) {

	        			if( $object->public ){

	        				if( ! in_array( $post_type, $this->get_exclude_post_types() ) ){
						        $customizer->add_section( "wp_hash_post_slug_{$post_type}", array(
						            'title'             =>  $object->label,
						            'panel'				=>	'wp_hash_post_slug',
						            'priority'          =>  2
						        ) );

						            $customizer->add_setting( 'wp_hash_post_slug[post_types]['.$post_type.']', array(
						                'default'           =>  $post_type == 'video' ? '1' : '',
						                'type'              =>  'option',
						                'capability'        =>  'edit_theme_options',
						                'sanitize_callback' =>  'sanitize_text_field'
						            ) );

						            $customizer->add_control( 'wp_hash_post_slug[post_types]['.$post_type.']', array(
						                'label'             =>  sprintf(
						                	esc_html__( 'Hash %s Slugs', 'wp_hash_post_slug' ),
						                	$object->labels->singular_name
						                ),
						                'type'              =>  'checkbox',
						                'description'		=>	$post_type == 'video' ? sprintf( esc_html__( '%s Post Type is always supported', 'streamtube-core' ), $object->label ) : '',
						                'section'           =>  "wp_hash_post_slug_{$post_type}"
						            ) );				        

						        $taxonomies = get_object_taxonomies( $post_type, 'object' );

						        if( $taxonomies ){
						        	foreach ( $taxonomies as $taxonomy => $object ) {
							            $customizer->add_setting( 'wp_hash_post_slug[taxonomies]['.$taxonomy.']', array(
							                'default'           =>  '',
							                'type'              =>  'option',
							                'capability'        =>  'edit_theme_options',
							                'sanitize_callback' =>  'sanitize_text_field'
							            ) );

							            $customizer->add_control( 'wp_hash_post_slug[taxonomies]['.$taxonomy.']', array(
							                'label'             =>  sprintf(
							                	esc_html__( 'Hash %s Slugs', 'wp_hash_post_slug' ),
							                	$object->labels->singular_name
							                ),
							                'type'              =>  'checkbox',
							                'section'           =>  "wp_hash_post_slug_{$post_type}"
							            ) );		        		
						        	}
						        }
					    	}
				    	}
	        		}
	        	}

		}
	}

	/**
	 * Run
	 */
	function WPHPL(){
		global $WPHPL;

		if( ! $WPHPL instanceof WP_Hash_Post_Slug ){
			$WPHPL = new WP_Hash_Post_Slug();
		}

		return $WPHPL;
	}

	WPHPL();
}