<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://themeforest.net/user/phpface
 * @since      1.0.0
 *
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */
if( ! defined('ABSPATH' ) ){
    exit;
}

class Streamtube_Core {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Streamtube_Core_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	protected $plugin_setting_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	protected $plugin;

	protected $license = null;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		if ( defined( 'STREAMTUBE_CORE_VERSION' ) ) {
			$this->version = STREAMTUBE_CORE_VERSION;
		} else {
			$this->version = '2.0';
		}

		$this->plugin_name = 'StreamTube Core';

		$this->plugin = new stdClass();

		$this->load_dependencies();
		$this->request_license();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_core_hooks();
		$this->define_ads_hooks();
		$this->define_post_hooks();
		$this->define_video_hooks();
		$this->define_player_hooks();
		$this->define_timestamp_hooks();
		$this->define_comment_hooks();
		$this->define_user_hooks();
		$this->define_rest_hooks();

		$this->define_collection_hooks();

		$this->define_mobile_bottom_bar();

		$this->define_googlesitekit_hooks();

		$this->define_mycred_hooks();

		$this->define_buddypress_hooks();

		$this->define_better_messages_hooks();

		$this->define_bbpress();

		$this->define_youtube_importer();

		$this->define_bunnycdn();

		$this->define_pmpro();

		$this->define_woocommerce();

		$this->define_dokan();

		$this->define_woothanks();

		$this->define_real_cookie_banner();

		$this->define_open_graph();		

		$this->update_checker();

		$this->action_links();		
	}

	/**
	 *
	 * Include file in WP environment
	 * 
	 * @param  string $file
	 *
	 * @since 1.0.9
	 * 
	 */
	private function include_file( $file ){
		require_once trailingslashit( plugin_dir_path( dirname( __FILE__ ) ) ) . $file;
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Streamtube_Core_Loader. Orchestrates the hooks of the plugin.
	 * - Streamtube_Core_i18n. Defines internationalization functionality.
	 * - Streamtube_Core_Admin. Defines all hooks for the admin area.
	 * - Streamtube_Core_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		/**
		 * The class responsible for defining license functionality
		 * of the plugin.
		 */
		$this->include_file( 'includes/class-streamtube-core-license.php' );

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		$this->include_file( 'includes/class-streamtube-core-loader.php' );

		$this->loader = new Streamtube_Core_Loader();

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		$this->include_file( 'includes/class-streamtube-core-i18n.php' );

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		$this->include_file( 'includes/class-streamtube-core-cron.php' );

		/**
		 * HTTP Request
		 */
		$this->include_file( 'includes/class-streamtube-core-http-request.php' );		

		/**
		 * Misc
		 */
		$this->include_file( 'includes/class-streamtube-core-misc.php' );		

		/**
		 * System permission
		 */
		$this->include_file( 'includes/class-streamtube-core-permission.php' );

		/**
		 * The class responsible for defining oEmbed functionality
		 * of the plugin.
		 */
		$this->include_file( 'includes/class-streamtube-core-oembed.php' );

		/**
		 * The class responsible for defining endpoint functionality
		 * of the plugin.
		 */
		$this->include_file( 'includes/class-streamtube-core-endpoint.php' );	

		/**
		 * The class responsible for defining custom query vars functionality
		 * of the plugin.
		 */
		$this->include_file( 'includes/class-streamtube-core-menu.php' );	

		/**
		 * The class responsible for defining all post functionality
		 */
		$this->include_file( 'includes/class-streamtube-core-post.php' );	

		/**
		 * The class responsible for defining all video functionality
		 */
		$this->include_file( 'includes/class-streamtube-core-video.php' );

		/**
		 * The class responsible for defining all player functionality
		 */
		$this->include_file( 'includes/class-streamtube-core-player.php' );				

		/**
		 * The class responsible for defining all download functionality
		 */
		$this->include_file( 'includes/class-streamtube-core-download-files.php' );			

		/**
		 * The class responsible for defining all comment functionality
		 */
		$this->include_file( 'includes/class-streamtube-core-comment.php' );	

		/**
		 * The class responsible for defining all custom taxonomies
		 */
		$this->include_file( 'includes/class-streamtube-core-taxonomy.php' );	

		/**
		 * The class responsible for defining sidebar
		 */
		$this->include_file( 'includes/class-streamtube-core-sidebar.php' );	

		$this->include_file( 'includes/widgets/class-streamtube-core-widget-live-ajax-search.php' );

		/**
		 * The class responsible for defining custom posts widget
		 */
		$this->include_file( 'includes/widgets/class-streamtube-core-widget-posts.php' );

		/**
		 * The class responsible for defining custom taxonomy widget
		 */
		$this->include_file( 'includes/widgets/class-streamtube-core-widget-term-grid.php' );		

		/**
		 * The class responsible for defining custom posts widget
		 */
		$this->include_file( 'includes/widgets/class-streamtube-core-widget-comments.php' );

		/**
		 * The class responsible for defining custom posts widget
		 */
		$this->include_file( 'includes/widgets/class-streamtube-core-widget-comments-template.php' );

		/**
		 * The class responsible for defining custom user list widget
		 */
		$this->include_file( 'includes/widgets/class-streamtube-core-widget-user-list.php' );

		/**
		 * The class responsible for defining custom user grid widget
		 */
		$this->include_file( 'includes/widgets/class-streamtube-core-widget-user-grid.php' );		

		/**
		 * The class responsible for defining custom taxonomy widget
		 */
		$this->include_file( 'includes/widgets/class-streamtube-core-widget-video-categories.php' );

		/**
		 * The class responsible for defining custom taxonomy widget
		 */
		$this->include_file( 'includes/widgets/class-streamtube-core-widget-chatroom.php' );

		/**
		 * The class responsible for defining recorded videos widget
		 */
		$this->include_file( 'includes/widgets/class-streamtube-core-widget-recorded-videos.php' );

		/**
		 * The class responsible for defining playlist content widget
		 */
		$this->include_file( 'includes/widgets/class-streamtube-core-widget-playlist-content.php' );

		/**
		 * The class responsible for defining Content Type Filter widget
		 */
		$this->include_file( 'includes/widgets/class-streamtube-core-widget-filter-content-type.php' );	

		/**
		 * The class responsible for defining Content Cost Filter widget
		 */
		$this->include_file( 'includes/widgets/class-streamtube-core-widget-filter-content-cost.php' );

		/**
		 * The class responsible for defining Submit Date Filter widget
		 */
		$this->include_file( 'includes/widgets/class-streamtube-core-widget-filter-post-date.php' );

		/**
		 * The class responsible for defining Sortby Filter widget
		 */
		$this->include_file( 'includes/widgets/class-streamtube-core-widget-filter-sortby.php' );

		/**
		 * The class responsible for defining Taxonomy Filter widget
		 */
		$this->include_file( 'includes/widgets/class-streamtube-core-widget-filter-taxonomy.php' );

		/**
		 * The class responsible for defining PMP Filter widget
		 */
		$this->include_file( 'includes/widgets/class-streamtube-core-widget-filter-paid-membership-pro.php' );

		/**
		 * The class responsible for defining custom elementor widgets
		 */
		$this->include_file( 'includes/class-streamtube-core-elementor.php' );		

		/**
		 * The class responsible for defining user profile page
		 */
		$this->include_file( 'includes/class-streamtube-core-user.php' );

		/**
		 * The class responsible for defining user privacy
		 */
		$this->include_file( 'includes/class-streamtube-core-user-privacy.php' );	

		/**
		 * The class responsible for defining user profile page
		 */
		$this->include_file( 'includes/class-streamtube-core-user-profile.php' );	

		/**
		 * The class responsible for defining user profile page
		 */
		$this->include_file( 'includes/class-streamtube-core-user-dashboard.php' );	

		/**
		 * The class responsible for defining shortcodes.
		 */
		$this->include_file( 'includes/class-streamtube-core-shortcode.php' );	

		/**
		 * The class responsible for defining restrict conte t
		 */
		$this->include_file( 'includes/class-streamtube-core-restrict-content.php' );	

		$this->plugin->restrict_content = new Streamtube_Core_Restrict_Content();

		$this->include_file( 'includes/class-streamtube-core-update.php' );	

		/**
		 * The class responsible for defining rest.
		 */
		$this->include_file( 'includes/rest_api/class-streamtube-core-rest-api.php' );

		/**
		 * The class responsible for defining generate image rest API
		 */
		$this->include_file( 'includes/rest_api/class-streamtube-core-generate-image-rest-controller.php' );	

		/**
		 * The class responsible for defining user rest API
		 */
		$this->include_file( 'includes/rest_api/class-streamtube-core-user-rest-controller.php' );		

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		$this->include_file( 'admin/class-streamtube-core-admin.php' );	

		$this->include_file( 'admin/class-streamtube-core-task-spooler.php' );	
		
		$this->include_file( 'admin/class-streamtube-core-admin-user.php' );

		$this->include_file( 'admin/class-streamtube-core-metabox.php' );

		$this->include_file( 'admin/class-streamtube-core-customizer.php' );

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		$this->include_file( 'public/class-streamtube-core-public.php' );	

		/**
		 * The function responsible for defining post functions
		 */		
		$this->include_file( 'includes/function-users.php' );	

		/**
		 * The function responsible for defining post functions
		 */		
		$this->include_file( 'includes/function-posts.php' );	

		/**
		 * The function responsible for defining post functions
		 */		
		$this->include_file( 'includes/function-comments.php' );

		/**
		 * The function responsible for defining user functions
		 */		
		$this->include_file( 'includes/function-templates.php' );

		/**
		 * The template tags
		 */		
		$this->include_file( 'includes/template-tags.php' );		

		/**
		 * The function responsible for defining email functions
		 */		
		$this->include_file( 'includes/function-notify.php' );

		/**
		 * The function responsible for defining options functions
		 */		
		$this->include_file( 'includes/function-options.php' );

		/**
		 * The function responsible for defining filters
		 */		
		$this->include_file( 'includes/function-filters.php' );
	}

	/**
	 *
	 * Request License
	 * 
	 */
	private function request_license(){

		$license = new Streamtube_Core_License();

		$this->license = $license->is_verified();

		if( is_wp_error( $this->license ) || ! is_array( $this->license ) ){
			$this->license = false;
		}

		wp_cache_set( "streamtube:license", $this->license );
	}

	/**
	 * Get license
	 */
	private function get_license(){
		return wp_cache_get( "streamtube:license" );
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Streamtube_Core_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Streamtube_Core_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$this->plugin->customizer = new Streamtube_Core_Customizer();

		$this->loader->add_action(
			'customize_register',
			$this->plugin->customizer,
			'register'
		);	

		$this->plugin->admin = new Streamtube_Core_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 
			'admin_enqueue_scripts', 
			$this->plugin->admin, 
			'enqueue_styles' 
		);

		$this->loader->add_action( 
			'admin_enqueue_scripts', 
			$this->plugin->admin, 
			'enqueue_scripts' 
		);


		$this->loader->add_action( 
			'admin_notices', 
			$this->plugin->admin, 
			'notices' 
		);		

		$this->plugin->admin_user = new Streamtube_Core_Admin_User();

		$this->loader->add_filter(
			'manage_users_columns',
			$this->plugin->admin_user,
			'user_table',
			10,
			1
		);

		$this->loader->add_filter(
			'manage_users_custom_column',
			$this->plugin->admin_user,
			'user_table_columns',
			20,
			3
		);		

		$this->plugin->metabox = new Streamtube_Core_MetaBox();

		$this->loader->add_action( 
			'add_meta_boxes', 
			$this->plugin->metabox, 
			'add_meta_boxes',
			1
		);

		$this->loader->add_action( 
			'save_post_video', 
			$this->plugin->metabox, 
			'video_data_save',
			10,
			1 
		);

		$this->loader->add_action( 
			'save_post', 
			$this->plugin->metabox, 
			'template_options_save',
			10,
			1 
		);		

		$this->loader->add_action(
			'add_meta_boxes', 
			$this->plugin->restrict_content, 
			'metaboxes' 
		);

		$this->loader->add_action( 
			'save_post_video', 
			$this->plugin->restrict_content, 
			'save_data',
			10,
			1 
		);

		$this->loader->add_action( 
			'streamtube/player/file/output', 
			$this->plugin->restrict_content, 
			'filter_player_output',
			20,
			2 
		);

		$this->loader->add_action( 
			'streamtube/player/embed/output', 
			$this->plugin->restrict_content, 
			'filter_player_embed_output',
			20,
			2 
		);		

		$this->loader->add_action( 
			'streamtube/core/video/can_user_download', 
			$this->plugin->restrict_content, 
			'filter_download_permission',
			10,
			1 
		);

		$this->loader->add_action( 
			'wp_ajax_join_us', 
			$this->plugin->restrict_content, 
			'ajax_request_join_us',
			10,
			1 
		);

		$this->loader->add_action( 
			'wp_footer', 
			$this->plugin->restrict_content, 
			'load_modal_join_us',
			10,
			1 
		);		

		$this->loader->add_filter(
			'manage_video_posts_columns',
			$this->plugin->restrict_content, 
			'filter_post_table',
			10,
			1
		);

		$this->loader->add_action(
			'manage_video_posts_custom_column',
			$this->plugin->restrict_content, 
			'filter_post_table_columns',
			10,
			2
		);

		$this->plugin->task_spooler = new Streamtube_Core_Task_Spooler();

		$this->loader->add_action( 
			'admin_menu', 
			$this->plugin->task_spooler,
			'admin_menu'
		);			
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$this->plugin->public = new Streamtube_Core_Public();

		$this->loader->add_action(
			 'wp_enqueue_scripts', 
			 $this->plugin->public, 
			 'enqueue_styles',
			 20
		);

		$this->loader->add_action( 
			'wp_enqueue_scripts', 
			$this->plugin->public, 
			'enqueue_scripts' 
		);

		$this->loader->add_action( 
			'login_enqueue_scripts', 
			$this->plugin->public, 
			'enqueue_scripts' 
		);

		$this->loader->add_action( 
			'enqueue_embed_scripts', 
			$this->plugin->public, 
			'enqueue_embed_scripts' 
		);

		$this->loader->add_action( 
			'streamtube/header/profile/before', 
			$this->plugin->public,
			'the_upload_button'
		);		

		$this->loader->add_action( 
			'wp_footer', 
			$this->plugin->public, 
			'load_modals' 
		);


		$this->loader->add_filter( 
			'search_template', 
			$this->plugin->public, 
			'load_search_template' 
		);

		$this->loader->add_action(
			'wp_head',
			$this,
			'generator'
		);

	}

	/**
	 * Register all of the hooks related to the core functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_core_hooks(){	

		$this->loader->add_filter(
			'http_request_args',
			'Streamtube_Core_HTTP_Request',
			'filter_request_args',
			10,
			2
		);

		$this->plugin->cron = new Streamtube_Core_Cron();

		$this->loader->add_filter(
			'cron_schedules',
			$this->plugin->cron,
			'add_schedules',
			10,
			1
		);	

		$this->loader->add_action( 
			'init', 
			'Streamtube_Core_Endpoint', 
			'add_endpoints' 
		);

		$this->plugin->taxonomy = new Streamtube_Core_Taxonomy();

		$this->loader->add_action( 
			'init', 
			$this->plugin->taxonomy, 
			'video_category'
		);			

		$this->loader->add_action( 
			'init', 
			$this->plugin->taxonomy, 
			'video_tag' 
		);	

		$this->loader->add_action( 
			'init', 
			$this->plugin->taxonomy, 
			'report_category' 
		);

		$this->loader->add_action( 
			'init', 
			$this->plugin->taxonomy, 
			'hand_pick' 
		);		

		$this->loader->add_action( 
			'wp_ajax_search_terms',
			$this->plugin->taxonomy, 
			'search_terms'
		);

		$this->loader->add_action( 
			'wp_ajax_get_video_tag_terms',
			$this->plugin->taxonomy, 
			'ajax_get_video_tag_terms'
		);		

		$this->loader->add_action( 
			'categories_add_form_fields',
			$this->plugin->taxonomy, 
			'add_thumbnail_field',
			10,
			1
		);

		$this->loader->add_action( 
			'categories_edit_form_fields',
			$this->plugin->taxonomy, 
			'edit_thumbnail_field',
			10,
			2
		);

		$this->loader->add_action( 
			'video_tag_add_form_fields',
			$this->plugin->taxonomy, 
			'add_thumbnail_field',
			10,
			1
		);

		$this->loader->add_action( 
			'video_tag_edit_form_fields',
			$this->plugin->taxonomy, 
			'edit_thumbnail_field',
			10,
			2
		);		

		$this->loader->add_action( 
			'category_add_form_fields',
			$this->plugin->taxonomy, 
			'add_thumbnail_field',
			10,
			1
		);

		$this->loader->add_action( 
			'category_edit_form_fields',
			$this->plugin->taxonomy, 
			'edit_thumbnail_field',
			10,
			2
		);		

		$this->loader->add_action( 
			'created_categories',
			$this->plugin->taxonomy, 
			'update_thumbnail_field',
			10,
			1
		);			

		$this->loader->add_action( 
			'edited_categories',
			$this->plugin->taxonomy, 
			'update_thumbnail_field',
			10,
			1
		);

		$this->loader->add_action( 
			'created_video_tag',
			$this->plugin->taxonomy, 
			'update_thumbnail_field',
			10,
			1
		);			

		$this->loader->add_action( 
			'edited_video_tag',
			$this->plugin->taxonomy, 
			'update_thumbnail_field',
			10,
			1
		);		

		$this->loader->add_action( 
			'created_category',
			$this->plugin->taxonomy, 
			'update_thumbnail_field',
			10,
			1
		);

		$this->loader->add_action( 
			'edited_category',
			$this->plugin->taxonomy, 
			'update_thumbnail_field',
			10,
			1
		);		

		$this->loader->add_filter( 
			'manage_edit-categories_columns',
			$this->plugin->taxonomy, 
			'add_thumbnail_column',
			10,
			1
		);

		$this->loader->add_filter( 
			'manage_categories_custom_column',
			$this->plugin->taxonomy, 
			'add_thumbnail_column_content',
			10,
			3
		);

		$this->loader->add_filter( 
			'manage_edit-video_tag_columns',
			$this->plugin->taxonomy, 
			'add_thumbnail_column',
			10,
			1
		);

		$this->loader->add_filter( 
			'manage_video_tag_custom_column',
			$this->plugin->taxonomy, 
			'add_thumbnail_column_content',
			10,
			3
		);			

		$this->loader->add_filter( 
			'manage_edit-category_columns',
			$this->plugin->taxonomy, 
			'add_thumbnail_column',
			10,
			1
		);

		$this->loader->add_filter( 
			'manage_category_custom_column',
			$this->plugin->taxonomy, 
			'add_thumbnail_column_content',
			10,
			3
		);		

		$this->plugin->sidebar = new Streamtube_Core_Sidebar();

		$this->loader->add_action( 
			'widgets_init', 
			$this->plugin->sidebar, 
			'widgets_init'
		);

		$this->loader->add_action( 
			'widgets_init', 
			'Streamtube_Core_Widget_Filter_Content_Type', 
			'register'
		);		

		$this->loader->add_action( 
			'widgets_init', 
			'Streamtube_Core_Widget_Filter_Content_Cost', 
			'register'
		);		

		$this->loader->add_action( 
			'widgets_init', 
			'Streamtube_Core_Widget_Filter_Taxonomy', 
			'register'
		);		

		$this->loader->add_action( 
			'widgets_init', 
			'Streamtube_Core_Widget_Filter_Post_Date', 
			'register'
		);

		$this->loader->add_action( 
			'widgets_init', 
			'Streamtube_Core_Widget_Filter_Sortby', 
			'register'
		);

		$this->loader->add_action( 
			'widgets_init', 
			'Streamtube_Core_Widget_Filter_Paid_Membership_Pro', 
			'register'
		);

		$this->loader->add_action( 
			'widgets_init', 
			'Streamtube_Core_Widget_Live_Ajax_Search', 
			'register'
		);

		$this->loader->add_action( 
			'widgets_init', 
			'Streamtube_Core_Widget_User_List', 
			'register'
		);

		$this->loader->add_action( 
			'widgets_init', 
			'Streamtube_Core_Widget_User_Grid', 
			'register'
		);

		$this->loader->add_action(
			'wp_ajax_load_more_users',
			'Streamtube_Core_Widget_User_Grid', 
			'ajax_load_more_users'
		);

		$this->loader->add_action(
			'wp_ajax_nopriv_load_more_users',
			'Streamtube_Core_Widget_User_Grid', 
			'ajax_load_more_users'
		);		

		$this->loader->add_action( 
			'widgets_init', 
			'Streamtube_Core_Widget_Posts', 
			'register'
		);

		$this->loader->add_action( 
			'widgets_init', 
			'Streamtube_Core_Widget_Term_Grid', 
			'register'
		);

		$this->loader->add_action( 
			'wp_ajax_load_more_tax_terms', 
			'Streamtube_Core_Widget_Term_Grid', 
			'load_more_terms'
		);

		$this->loader->add_action( 
			'wp_ajax_nopriv_load_more_tax_terms', 
			'Streamtube_Core_Widget_Term_Grid', 
			'load_more_terms'
		);

		$this->loader->add_action( 
			'widgets_init', 
			'Streamtube_Core_Widget_Video_Category', 
			'register'
		);		

		$this->loader->add_action( 
			'widgets_init', 
			'Streamtube_Core_Widget_Recorded_Videos', 
			'register'
		);		

		$this->loader->add_action( 
			'widgets_init', 
			'Streamtube_Core_Widget_Comments', 
			'register'
		);

		$this->loader->add_action( 
			'widgets_init', 
			'Streamtube_Core_Widget_Comments_Template', 
			'register'
		);	

		$this->loader->add_action( 
			'widgets_init', 
			'Streamtube_Core_Widget_Playlist_Content', 
			'register'
		);		

		$this->loader->add_action( 
			"wp_ajax_search_in_collection",
			'Streamtube_Core_Widget_Playlist_Content', 
			'ajax_search_in_collection'
		);

		$this->loader->add_action( 
			"wp_ajax_nopriv_search_in_collection",
			'Streamtube_Core_Widget_Playlist_Content', 
			'ajax_search_in_collection'
		);		

		$this->loader->add_action( 
			'wp_ajax_nopriv_widget_load_more_posts', 
			'Streamtube_Core_Widget_Posts', 
			'ajax_load_more_posts' 
		);

		$this->loader->add_action( 
			'wp_ajax_widget_load_more_posts', 
			'Streamtube_Core_Widget_Posts', 
			'ajax_load_more_posts' 
		);

		$this->loader->add_action( 
			'wp_ajax_ajax_download_widget_json', 
			'Streamtube_Core_Widget_Posts', 
			'ajax_download_json' 
		);			

		/** Elementor  */
		$this->plugin->elementor = new Streamtube_Core_Elementor();

		$this->loader->add_action(
			'init',
			$this->plugin->elementor,
			'init'
		);

		$this->plugin->shortcode = new Streamtube_Core_ShortCode();

		$this->loader->add_action( 
			'init', 
			$this->plugin->shortcode, 
			'is_logged_in' 
		);

		$this->loader->add_action( 
			'init', 
			$this->plugin->shortcode, 
			'is_not_logged_in' 
		);

		$this->loader->add_action( 
			'init', 
			$this->plugin->shortcode, 
			'can_upload' 
		);

		$this->loader->add_action( 
			'init', 
			$this->plugin->shortcode, 
			'can_not_upload' 
		);

		$this->loader->add_action( 
			'init', 
			$this->plugin->shortcode, 
			'user_name' 
		);

		$this->loader->add_action(
			'init', 
			$this->plugin->shortcode, 
			'user_avatar' 
		);

		$this->loader->add_action(
			'init', 
			$this->plugin->shortcode, 
			'user_data' 
		);

		$this->loader->add_action(
			'init', 
			$this->plugin->shortcode, 
			'user_grid' 
		);

		$this->loader->add_action(
			'init', 
			$this->plugin->shortcode, 
			'post_grid' 
		);

		$this->loader->add_action(
			'init', 
			$this->plugin->shortcode, 
			'playlist' 
		);

		$this->loader->add_action(
			'init', 
			$this->plugin->shortcode, 
			'player' 
		);

		$this->loader->add_action(
			'init', 
			$this->plugin->shortcode, 
			'embed_media' 
		);		

		$this->loader->add_action(
			'init', 
			$this->plugin->shortcode, 
			'button_upload' 
		);

		$this->loader->add_action(
			'init', 
			$this->plugin->shortcode, 
			'form_upload' 
		);

		$this->loader->add_action(
			'init', 
			$this->plugin->shortcode, 
			'form_embed' 
		);		

		$this->loader->add_action(
			'wp', 
			$this->plugin->shortcode, 
			'redirect_nonlogged_to_login_on_upload' 
		);				

		$this->loader->add_action(
			'init', 
			$this->plugin->shortcode, 
			'term_grid' 
		);

		$this->loader->add_action(
			'init', 
			$this->plugin->shortcode, 
			'user_library' 
		);

		$this->loader->add_action(
			'init', 
			$this->plugin->shortcode, 
			'user_dashboard_url' 
		);			

		$this->loader->add_action(
			'init', 
			$this->plugin->shortcode, 
			'chapter' 
		);

		$this->loader->add_action(
			'init', 
			$this->plugin->shortcode, 
			'term_menu' 
		);

		$this->loader->add_action(
			'init', 
			$this->plugin->shortcode, 
			'the_term_menu' 
		);

		$this->loader->add_action(
			'init', 
			$this->plugin->shortcode, 
			'term_play_all_url' 
		);

		$this->plugin->oembed = new Streamtube_Core_oEmbed();

		$this->loader->add_action(
			'init', 
			$this->plugin->oembed, 
			'add_providers' 
		);

		$this->loader->add_filter(
			'embed_oembed_html', 
			$this->plugin->oembed, 
			'filter_embed_oembed_html',
			10,
			4
		);			

		$this->loader->add_action(
			'streamtube/player/embed/output', 
			$this->plugin->oembed, 
			'force_balance_tags_embed_html',
			5,
			2
		);		

		$this->plugin->misc = new Streamtube_Core_Misc();

		$this->loader->add_filter(
			'register_post_type_args', 
			$this->plugin->misc, 
			'exclude_page_from_search',
			10,
			2
		);		

		$this->loader->add_filter(
			'login_url', 
			$this->plugin->misc, 
			'filter_login_url',
			9999,
			3
		);

		$this->loader->add_filter(
			'register_url', 
			$this->plugin->misc, 
			'filter_register_url',
			9999,
			1
		);

		$this->loader->add_filter(
			'show_admin_bar', 
			$this->plugin->misc, 
			'hide_admin_bar',
			9999,
			1
		);

		$this->loader->add_action(
			'admin_init',
			$this->plugin->misc, 
			'block_admin_access'
		);

		$this->loader->add_filter(
			'wp_editor_settings',
			$this->plugin->misc, 
			'wp_editor_style',
			10,
			2
		);		

		$this->loader->add_action( 
			'admin_init', 
			'Streamtube_Core_Update',
			'add_roles'
		);

		$this->loader->add_action( 
			'admin_init', 
			'Streamtube_Core_Update', 
			'add_default_widgets' 
		);

		$this->loader->add_action( 
			'admin_init', 
			'Streamtube_Core_Update', 
			'fix_user_verify_role' 
		);

		$this->loader->add_action( 
			'admin_init', 
			'Streamtube_Core_Update', 
			'fix_taxonomy_capabilities' 
		);			
	}

	/**
	 * Register all of the hooks related to the ads functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_ads_hooks(){
		$this->include_file( 'third-party/advertising/class-streamtube-core-advertising.php' );

		$this->plugin->advertising = new Streamtube_Core_Advertising();

		if( ! $this->get_license() ){
			$this->loader->add_action( 
				'admin_menu', 
				$this->plugin->advertising->admin,
				'admin_menu_unregistered'
			);
		}
		else{

			$this->loader->add_action( 
				'admin_init', 
				$this->plugin->advertising,
				'update_htaccess'
			);

			$this->loader->add_action( 
				'admin_menu', 
				$this->plugin->advertising->admin,
				'admin_menu'
			);
			$this->loader->add_action( 
				'init', 
				$this->plugin->advertising->ad_tag,
				'post_type'
			);

			$this->loader->add_action( 
				'add_meta_boxes', 
				$this->plugin->advertising->ad_tag,
				'add_meta_boxes'
			);

			$this->loader->add_action( 
				'save_post', 
				$this->plugin->advertising->ad_tag,
				'save_ad_content_box',
				10,
				1 
			);

			$this->loader->add_action( 
				'wp_ajax_import_vast', 
				$this->plugin->advertising->ad_tag,
				'ajax_import_vast'
			);

			$this->loader->add_action( 
				'template_redirect', 
				$this->plugin->advertising->ad_tag,
				'template_redirect'
			);		

			$this->loader->add_filter(
				'manage_ad_tag_posts_columns',
				$this->plugin->advertising->ad_tag,
				'admin_post_table',
				10,
				1
			);		

			$this->loader->add_action(
				'manage_ad_tag_posts_custom_column',
				$this->plugin->advertising->ad_tag,
				'admin_post_table_columns',
				10,
				2
			);

			$this->loader->add_action( 
				'init', 
				$this->plugin->advertising->ad_schedule,
				'post_type'
			);		

			$this->loader->add_action( 
				'add_meta_boxes', 
				$this->plugin->advertising->ad_schedule,
				'add_meta_boxes'
			);

			$this->loader->add_action( 
				'save_post', 
				$this->plugin->advertising->ad_schedule,
				'save_ad_tags_box',
				10,
				1
			);

			$this->loader->add_action( 
				'wp_ajax_get_schedule_tax_terms', 
				$this->plugin->advertising->ad_schedule,
				'ajax_get_tax_terms'
			);

			$this->loader->add_action( 
				'save_post', 
				$this->plugin->advertising->ad_schedule,
				'clear_cache',
				100,
				1
			);

			$this->loader->add_action( 
				'template_redirect', 
				$this->plugin->advertising->ad_schedule,
				'load_vmap_template'
			);

			$this->loader->add_action( 
				'wp_ajax_search_ads', 
				$this->plugin->advertising->ad_schedule,
				'ajax_search_ads'
			);

			$this->loader->add_filter(
				'manage_ad_schedule_posts_columns',
				$this->plugin->advertising->ad_schedule,
				'admin_post_table',
				10,
				1
			);		

			$this->loader->add_action(
				'manage_ad_schedule_posts_custom_column',
				$this->plugin->advertising->ad_schedule,
				'admin_post_table_columns',
				10,
				2
			);							

			$this->loader->add_filter(
				'streamtube/player/file/setup',
				$this->plugin->advertising,
				'request_ads',
				5,
				2
			);

			$this->loader->add_filter(
				'streamtube/core/advertising/vast_tag_url',
				$this->plugin->advertising,
				'filter_ads_visibility',
				5,
				3
			);
		}
	}

	/**
	 * Register all of the hooks related to the post functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_post_hooks(){
		$this->plugin->post = new Streamtube_Core_Post();

		$this->loader->add_action( 
			'init', 
			$this->plugin->post, 
			'cpt_video'
		);

		$this->loader->add_action( 
			'init', 
			$this->plugin->post, 
			'new_post_statuses'
		);		

		$this->loader->add_action( 
			'streamtube/core/post/update', 
			$this->plugin->post, 
			'update_post_meta'
		);

		$this->loader->add_action( 
			'save_post_video', 
			$this->plugin->post, 
			'sync_post_attachment',
			10,
			2
		);		

		$this->loader->add_action( 
			'wp_ajax_add_post', 
			$this->plugin->post, 
			'ajax_add_post'
		);

		$this->loader->add_action( 
			'wp_ajax_import_embed', 
			$this->plugin->post, 
			'ajax_import_embed'
		);

		$this->loader->add_action( 
			'wp_ajax_add_video', 
			$this->plugin->post, 
			'ajax_add_video'
		);
		
		$this->loader->add_action( 
			'wp_ajax_upload_video', 
			$this->plugin->post, 
			'ajax_upload_video'
		);

		$this->loader->add_action(
			'wp_ajax_upload_video_chunk',
			$this->plugin->post, 
			'ajax_upload_video_chunk'
		);			

		$this->loader->add_action( 
			'wp_ajax_upload_video_chunks', 
			$this->plugin->post, 
			'ajax_upload_video_chunks'
		);		

		$this->loader->add_action( 
			'wp_ajax_update_post', 
			$this->plugin->post, 
			'ajax_update_post'
		);		

		$this->loader->add_action( 
			'wp_ajax_trash_post', 
			$this->plugin->post, 
			'ajax_trash_post'
		);

		$this->loader->add_action( 
			'wp_ajax_approve_post', 
			$this->plugin->post, 
			'ajax_approve_post'
		);

		$this->loader->add_action( 
			'wp_ajax_reject_post', 
			$this->plugin->post, 
			'ajax_reject_post'
		);

		$this->loader->add_action( 
			'wp_ajax_restore_post', 
			$this->plugin->post, 
			'ajax_restore_post'
		);

		$this->loader->add_action( 
			'wp_ajax_search_posts', 
			$this->plugin->post, 
			'ajax_search_posts'
		);

		$this->loader->add_action( 
			'wp_ajax_report_video', 
			$this->plugin->post, 
			'ajax_report_video'
		);

		$this->loader->add_action( 
			'wp_ajax_upload_text_track', 
			$this->plugin->post, 
			'ajax_upload_text_track'
		);		

		$this->loader->add_action( 
			'wp_ajax_update_text_tracks', 
			$this->plugin->post, 
			'ajax_update_text_tracks'
		);

		$this->loader->add_action( 
			'wp_ajax_update_altsources', 
			$this->plugin->post, 
			'ajax_update_altsources'
		);

		$this->loader->add_action( 
			'wp_ajax_update_embed_privacy', 
			$this->plugin->post, 
			'ajax_update_embed_privacy'
		);		

		$this->loader->add_action( 
			'wp_ajax_get_post_thumbnail', 
			$this->plugin->post, 
			'ajax_get_post_thumbnail'
		);

		$this->loader->add_action( 
			'wp_ajax_get_post_by_url', 
			$this->plugin->post, 
			'ajax_get_post_by_url'
		);

		$this->loader->add_action( 
			'wp_ajax_nopriv_get_post_by_url', 
			$this->plugin->post, 
			'ajax_get_post_by_url'
		);

		$this->loader->add_action( 
			'wp_ajax_search_autocomplete', 
			$this->plugin->post, 
			'ajax_search_autocomplete'
		);

		$this->loader->add_action( 
			'wp_ajax_nopriv_search_autocomplete', 
			$this->plugin->post, 
			'ajax_search_autocomplete'
		);

		$this->loader->add_action( 
			'streamtube/core/post/edit/metaboxes', 
			$this->plugin->post, 
			'load_thumbnail_metabox',
			10
		);

		$this->loader->add_action( 
			'streamtube/core/post/edit/metaboxes', 
			$this->plugin->post, 
			'load_taxonomies_metabox',
			50
		);

		$this->loader->add_action( 
			'streamtube/core/post/edit/content/after', 
			$this->plugin->post, 
			'load_taxonomies_tags_metabox',
			50
		);			

		$this->loader->add_action( 
			'template_redirect', 
			$this->plugin->post, 
			'load_edit_template'
		);

		$this->loader->add_action( 
			'wp', 
			$this->plugin->post, 
			'redirect_to_edit_page'
		);

		$this->loader->add_action( 
			'wp_head', 
			$this->plugin->post, 
			'load_video_schema',
			1
		);

		$this->loader->add_action( 
			'ajax_query_attachments_args', 
			$this->plugin->post, 
			'filter_ajax_query_attachments_args',
			10,
			1
		);

		$this->loader->add_action( 
			'wp_insert_post', 
			$this->plugin->post, 
			'wp_insert_post',
			10,
			3
		);		

		$this->loader->add_action( 
			'wp', 
			$this->plugin->post, 
			'update_last_seen'
		);

		$this->loader->add_action( 
			'before_delete_post', 
			$this->plugin->post, 
			'delete_attached_files',
			10,
			2
		);

		$this->loader->add_action( 
			'delete_attachment', 
			$this->plugin->post, 
			'delete_attached_files',
			10,
			2
		);

		$this->loader->add_action( 
			'template_redirect', 
			$this->plugin->post, 
			'attachment_template_redirect',
			10,
			1
		);

		$this->loader->add_filter( 
			'streamtube_pre_player_args', 
			$this->plugin->post, 
			'filter_altsource',
			10,
			1
		);		

		$this->loader->add_action( 
			'streamtube/single/video/control', 
			$this->plugin->post, 
			'the_trailer_button',
			10
		);		

		$this->loader->add_action( 
			'streamtube/single/video/control', 
			$this->plugin->post, 
			'the_altsources_navigator',
			500
		);

		$this->loader->add_filter( 
			'streamtube/player/file/setup', 
			$this->plugin->post, 
			'filter_player_setup_text_tracks',
			20,
			2
		);

		$this->loader->add_filter( 
			'streamtube/player/file/output', 
			$this->plugin->post, 
			'filter_player_output',
			10,
			2
		);

		$this->loader->add_filter( 
			'streamtube/player/embed/output', 
			$this->plugin->post, 
			'filter_player_output',
			10,
			2
		);

		$this->loader->add_filter( 
			'streamtube/player/file/output', 
			$this->plugin->post, 
			'display_embed_privacy_notice',
			100,
			2
		);

		$this->loader->add_filter( 
			'streamtube/player/embed/output', 
			$this->plugin->post, 
			'display_embed_privacy_notice',
			100,
			2
		);

		$this->loader->add_filter( 
			'streamtube/player/file/output', 
			$this->plugin->post, 
			'display_upcoming_notice',
			200,
			2
		);

		$this->loader->add_filter( 
			'streamtube/player/embed/output', 
			$this->plugin->post, 
			'display_upcoming_notice',
			200,
			2
		);

		$this->loader->add_filter( 
			'streamtube/core/player/upcoming/countdown/before', 
			$this->plugin->post, 
			'display_upcoming_notice_heading',
			10,
			3
		);				

		$this->loader->add_filter(
			'manage_video_posts_columns',
			$this->plugin->post, 
			'filter_post_table',
			10,
			1
		);

		$this->loader->add_action(
			'manage_video_posts_custom_column',
			$this->plugin->post, 
			'filter_post_table_columns',
			10,
			2
		);		
	}

	/**
	 * Register all of the hooks related to the video functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_video_hooks(){
		$this->plugin->video = new Streamtube_Core_Video();

		$this->loader->add_action(
			'post_embed_url',
			$this->plugin->video,
			'filer_embed_url',
			10,
			2
		);			

		$this->loader->add_action(
			'embed_html',
			$this->plugin->video,
			'filter_embed_html',
			100,
			4
		);

		$this->loader->add_action(
			'oembed_response_data',
			$this->plugin->video,
			'filter_embed_type',
			100,
			4
		);

		$this->loader->add_action(
			'streamtube/single/video/control',
			$this->plugin->video,
			'load_button_share',
			100
		);

		$this->loader->add_action(
			'wp_footer',
			$this->plugin->video,
			'load_modal_share'
		);

		$this->loader->add_action(
			'streamtube/single/video/control',
			$this->plugin->video,
			'load_button_report',
			200
		);

		$this->loader->add_action(
			'wp_footer',
			$this->plugin->video,
			'load_modal_report'
		);		

		$this->loader->add_action(
			'streamtube/single/video/meta',
			$this->plugin->video,
			'load_single_post_date'
		);

		$this->loader->add_action(
			'streamtube/single/video/meta',
			$this->plugin->video,
			'load_single_post_comment_count'
		);

		$this->loader->add_action(
			'streamtube/single/video/meta',
			$this->plugin->video,
			'load_single_post_terms'
		);

		$this->loader->add_action(
			'streamtube/archive/video/page_header/before',
			$this->plugin->video,
			'load_the_archive_term_menu'
		);

		$this->loader->add_filter(
			'streamtube/archive/video/query_args',
			$this->plugin->video,
			'load_portrait_video_tags',
			10,
			1
		);

		$this->plugin->download_file = new StreamTube_Core_Download_File();

		$this->loader->add_action( 
			'streamtube/single/video/control', 
			$this->plugin->download_file,
			'button_download',
			5
		);

		$this->loader->add_action( 
			'template_redirect', 
			$this->plugin->download_file,
			'process_download'
		);

	}

	/**
	 * Register all of the hooks related to the player functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function  define_player_hooks(){
		$this->plugin->player = new Streamtube_Core_Player();

		$this->loader->add_filter(
			'streamtube/player/file/setup',
			$this->plugin->player, 
			"set_builtin_events",
			10,
			2
		);

		$this->loader->add_filter(
			'streamtube/player/file/setup',
			$this->plugin->player, 
			"set_topbar",
			10,
			2
		);		

		$this->loader->add_filter(
			'streamtube/player/file/setup',
			$this->plugin->player, 
			"set_share_box",
			10,
			2
		);						

		$this->loader->add_filter(
			'streamtube/player/file/setup',
			$this->plugin->player, 
			"set_skin",
			10,
			2
		);	

		$this->loader->add_filter(
			'streamtube/player/file/setup',
			$this->plugin->player, 
			"set_inactivity_timeout",
			10,
			2
		);	

		$this->loader->add_filter(
			'streamtube/player/file/setup',
			$this->plugin->player, 
			"set_language",
			10,
			2
		);	

		$this->loader->add_filter(
			'streamtube/player/file/setup',
			$this->plugin->player, 
			"set_watermark",
			10,
			2
		);	

		$this->loader->add_filter(
			'streamtube/player/file/setup',
			$this->plugin->player, 
			"set_controlbar_watermark",
			10,
			2
		);	

		$this->loader->add_filter(
			'streamtube/player/file/setup',
			$this->plugin->player, 
			"set_vr",
			10,
			2
		);		

		$this->loader->add_filter(
			'streamtube/player/file/setup',
			$this->plugin->player, 
			"set_right_click_blocker",
			10,
			2
		);	

		$this->loader->add_filter(
			'streamtube/player/file/setup',
			$this->plugin->player, 
			"set_playback_rates",
			10,
			2
		);

		$this->loader->add_filter(
			'streamtube/player/file/setup',
			$this->plugin->player, 
			"set_landscape_mode",
			10,
			2
		);

		$this->loader->add_filter(
			'streamtube/player/file/setup',
			$this->plugin->player, 
			"set_hotkeys",
			10,
			2
		);

		$this->loader->add_filter(
			'streamtube/player/file/setup',
			$this->plugin->player, 
			"set_start_at",
			10,
			2
		);

		$this->loader->add_filter(
			'streamtube/player/file/setup',
			$this->plugin->player, 
			"set_volume",
			10,
			2
		);

		$this->loader->add_filter(
			'streamtube/player/file/setup',
			$this->plugin->player, 
			"set_pause_simultaneous",
			10,
			2
		);

		$this->loader->add_filter( 
			'wp_video_shortcode', 
			$this->plugin->player, 
			'override_wp_video_shortcode',
			10,
			4
		);

		$this->loader->add_filter( 
			'render_block', 
			$this->plugin->player, 
			'override_wp_video_block',
			10,
			2
		);

		$this->loader->add_filter( 
			'render_block', 
			$this->plugin->player, 
			'override_wp_youtube_block',
			10,
			2
		);		

		$this->loader->add_filter(
			'the_content',
			$this->plugin->player, 
			"convert_youtube_to_videojs",
			5,
			1
		);

		$this->loader->add_action(
			'wp_ajax_load_video_source',
			$this->plugin->player, 
			"ajax_load_video_source"
		);

		$this->loader->add_action(
			'wp_ajax_nopriv_load_video_source',
			$this->plugin->player, 
			"ajax_load_video_source"
		);						
	}

	/**
	 * Register all of the hooks related to the content timestemp functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_timestamp_hooks(){

		if( ! $this->get_license() ){
			return false;
		}

		$this->include_file( 'third-party/content-timestamp/class-streamtube-core-content-timestamp.php' );

		$this->plugin->timestamp = new StreamTube_Core_Content_TimesTamp();

		$this->loader->add_filter(
			'the_content',
			$this->plugin->timestamp, 
			'filter_content',
			10,
			1
		);

		$this->loader->add_filter(
			'comment_text',
			$this->plugin->timestamp, 
			'filter_content',
			10,
			1
		);

		$this->loader->add_filter( 
			'streamtube/player/file/setup', 
			$this->plugin->timestamp, 
			'filter_player_setup',
			30,
			2
		);		
	}

	private function define_collection_hooks(){

		$this->include_file( 'third-party/collection/class-streamtube-core-collection.php' );

		$this->plugin->collection = new Streamtube_Core_Collection();

		$taxonomy = $this->plugin->collection::TAX_COLLECTION;

		$this->loader->add_action(
			'init',
			$this->plugin->collection, 
			'register_taxonomy'
		);

		$this->loader->add_filter(
			'get_term',
			$this->plugin->collection, 
			'_filter_term',
			10,
			2
		);		

		$this->loader->add_action( 
			"{$taxonomy}_add_form_fields",
			$this->plugin->taxonomy, 
			'add_thumbnail_field',
			10,
			1
		);

		$this->loader->add_action( 
			"{$taxonomy}_edit_form_fields",
			$this->plugin->taxonomy, 
			'edit_thumbnail_field',
			10,
			2
		);

		$this->loader->add_action( 
			"created_{$taxonomy}",
			$this->plugin->taxonomy, 
			'update_thumbnail_field',
			10,
			1
		);			

		$this->loader->add_action( 
			"edited_{$taxonomy}",
			$this->plugin->taxonomy, 
			'update_thumbnail_field',
			10,
			1
		);

		$this->loader->add_filter( 
			"manage_edit-{$taxonomy}_columns",
			$this->plugin->taxonomy, 
			'add_thumbnail_column',
			10,
			1
		);

		$this->loader->add_filter( 
			"manage_{$taxonomy}_custom_column",
			$this->plugin->taxonomy, 
			'add_thumbnail_column_content',
			10,
			3
		);		

		// meta fields.
		$this->loader->add_action( 
			"{$taxonomy}_add_form_fields",
			$this->plugin->collection,
			'admin_add_term_meta_field',
			10,
			1
		);

		$this->loader->add_action( 
			"{$taxonomy}_edit_form_fields",
			$this->plugin->collection,
			'admin_edit_term_meta_field',
			10,
			2
		);		

		$this->loader->add_action( 
			"created_{$taxonomy}",
			$this->plugin->collection,
			'admin_update_term_meta_fields',
			10,
			1
		);

		$this->loader->add_action( 
			"edited_{$taxonomy}",
			$this->plugin->collection,
			'admin_update_term_meta_fields',
			10,
			1
		);

		$this->loader->add_filter( 
			"manage_edit-{$taxonomy}_columns",
			$this->plugin->collection,
			'admin_add_term_meta_field_columns',
			10,
			1
		);

		$this->loader->add_filter( 
			"manage_{$taxonomy}_custom_column",
			$this->plugin->collection,
			'admin_add_term_meta_field_content_content',
			10,
			3
		);

		$this->loader->add_action( 
			'delete_user', 
			$this->plugin->collection,
			'admin_delete_user_collections',
			10,
			3
		);

		$this->loader->add_action( 
			'streamtube/post/thumbnail/after', 
			$this->plugin->collection,
			'frontend_the_watch_later_button',
			10
		);

		$this->loader->add_action( 
			'streamtube/flat_post/item', 
			$this->plugin->collection,
			'frontend_the_watch_later_button',
			10
		);

		if( $this->get_license() ){
			$this->loader->add_action( 
				'streamtube/single/video/control', 
				$this->plugin->collection,
				'frontend_the_collection_button' 
			);
		}

		$this->loader->add_action( 
			'wp_footer', 
			$this->plugin->collection,
			'frontend_the_collection_modal' 
		);

		$this->loader->add_action( 
			"wp",
			$this->plugin->collection, 
			'frontend_add_post_history'
		);

		$this->loader->add_filter( 
			"streamtube/core/post/edit/content/after",
			$this->plugin->collection, 
			'frontend_post_form_collections_box',
			10,
			1
		);		

		$this->loader->add_filter( 
			"streamtube/core/user/dashboard/menu/items",
			$this->plugin->collection, 
			'frontend_dashboard_menu',
			10,
			1
		);

		$this->loader->add_filter( 
			"streamtube/core/user/profile/menu/items",
			$this->plugin->collection, 
			'frontend_profile_menu',
			10,
			1
		);	

		$this->loader->add_action( 
			"streamtube/archive/video/page_title/after",
			$this->plugin->collection, 
			'frontend_the_button_play_all',
			10
		);

		$this->loader->add_action( 
			"added_term_relationship",
			$this->plugin->collection, 
			'_add_term_order',
			10,
			3
		);

		$this->loader->add_action( 
			"deleted_term_relationships",
			$this->plugin->collection, 
			'_remove_term_order',
			10,
			3
		);

		$this->loader->add_action( 
			"deleted_term_taxonomy",
			$this->plugin->collection, 
			'_clean_term_orders',
			10,
			1
		);		

		$this->loader->add_action( 
			"wp_login",
			$this->plugin->collection, 
			'auto_create_user_terms',
			10,
			2
		);		

		$this->loader->add_action( 
			"saved_term",
			$this->plugin->collection, 
			'saved_term',
			10,
			3
		);

		$this->loader->add_action( 
			"wp_ajax_create_collection",
			$this->plugin->collection, 
			'ajax_create_collection'
		);

		$this->loader->add_action( 
			"wp_ajax_delete_collection",
			$this->plugin->collection, 
			'ajax_delete_collection'
		);

		$this->loader->add_action( 
			"wp_ajax_get_collection_term",
			$this->plugin->collection, 
			'ajax_get_collection_term'
		);			

		$this->loader->add_action( 
			"wp_ajax_set_post_collection",
			$this->plugin->collection, 
			'ajax_set_post_collection'
		);

		$this->loader->add_action( 
			"wp_ajax_update_collection_item_index",
			$this->plugin->collection, 
			'ajax_update_collection_item_index'
		);		

		$this->loader->add_action( 
			"wp_ajax_set_post_watch_later",
			$this->plugin->collection, 
			'ajax_set_post_watch_later'
		);		

		$this->loader->add_action( 
			"wp_ajax_set_image_collection",
			$this->plugin->collection, 
			'ajax_set_image_collection'
		);		

		$this->loader->add_action( 
			"wp_ajax_upload_collection_thumbnail_image",
			$this->plugin->collection, 
			'ajax_upload_collection_thumbnail_image'
		);		

		$this->loader->add_action( 
			"wp_ajax_set_collection_status",
			$this->plugin->collection, 
			'ajax_set_collection_status'
		);

		$this->loader->add_action( 
			"wp_ajax_set_collection_activity",
			$this->plugin->collection, 
			'ajax_set_collection_activity'
		);

		$this->loader->add_action( 
			"wp_ajax_clear_collection",
			$this->plugin->collection, 
			'ajax_clear_collection'
		);

		$this->loader->add_action( 
			"wp_ajax_search_videos",
			$this->plugin->collection, 
			'ajax_search_videos'
		);

		$this->loader->add_filter( 
			'streamtube/player/file/setup', 
			$this->plugin->collection, 
			'filter_player_setup',
			30,
			2
		);

		$this->loader->add_filter( 
			'streamtube_get_share_embed_permalink', 
			$this->plugin->collection, 
			'filter_share_links',
			100,
			2
		);

		$this->loader->add_filter( 
			'streamtube/core/share/permalink', 
			$this->plugin->collection, 
			'filter_share_links',
			100,
			2
		);

		$this->loader->add_action(
			'post_embed_url',
			$this->plugin->collection,
			'filer_embed_url',
			10,
			2
		);
	}

	private function define_mobile_bottom_bar(){

		if( ! $this->get_license() ){
			return false;
		}

		$this->include_file( 'third-party/mobile-bottom-bar/class-streamtube-core-mobile-bottom-bar.php' );		

		$this->plugin->mobile_bottom_bar = new StreamTube_Core_Mobile_Bottom_Bar();

		$this->loader->add_filter( 
			'body_class', 
			$this->plugin->mobile_bottom_bar,
			'filter_body_classes'
		);		

		$this->loader->add_filter( 
			'login_body_class', 
			$this->plugin->mobile_bottom_bar,
			'filter_body_classes'
		);

		$this->loader->add_action( 
			'after_setup_theme', 
			$this->plugin->mobile_bottom_bar,
			'register_nav_menus'
		);

		$this->loader->add_action( 
			'streamtube/footer/after', 
			$this->plugin->mobile_bottom_bar,
			'the_bar'
		);		
	}

	/**
	 * Register all of the hooks related to the comment functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_comment_hooks(){
		$this->plugin->comment = new Streamtube_Core_Comment();

		$this->loader->add_action( 
			'streamtube/comment_list/comment/meta/right', 
			$this->plugin->comment,
			'load_control_buttons',
			10,
			3
		);		

		$this->loader->add_action(
			'wp_ajax_nopriv_post_comment',
			$this->plugin->comment, 
			'ajax_post_comment'
		);		

		$this->loader->add_action(
			'wp_ajax_post_comment',
			$this->plugin->comment, 
			'ajax_post_comment'
		);

		$this->loader->add_action(
			'wp_ajax_get_comment',
			$this->plugin->comment, 
			'ajax_get_comment'
		);

		$this->loader->add_action(
			'wp_ajax_get_comment_to_edit',
			$this->plugin->comment, 
			'ajax_get_comment'
		);

		$this->loader->add_action(
			'wp_ajax_get_comment_to_report',
			$this->plugin->comment, 
			'ajax_get_comment_to_report'
		);

		$this->loader->add_action(
			'wp_ajax_edit_comment',
			$this->plugin->comment, 
			'ajax_edit_comment'
		);

		$this->loader->add_action(
			'wp_ajax_report_comment',
			$this->plugin->comment, 
			'ajax_report_comment'
		);

		$this->loader->add_action(
			'streamtube/core/comment/reported',
			$this->plugin->comment, 
			'report_comment_notify',
			10,
			2
		);		

		$this->loader->add_action(
			'wp_ajax_remove_comment_report',
			$this->plugin->comment, 
			'ajax_remove_comment_report'
		);

		$this->loader->add_action(
			'wp_ajax_moderate_comment',
			$this->plugin->comment, 
			'ajax_moderate_comment'
		);

		$this->loader->add_action(
			'wp_ajax_trash_comment',
			$this->plugin->comment, 
			'ajax_trash_comment'
		);

		$this->loader->add_action(
			'wp_ajax_spam_comment',
			$this->plugin->comment, 
			'ajax_spam_comment'
		);		

		$this->loader->add_action(
			'wp_ajax_load_more_comments',
			$this->plugin->comment, 
			'ajax_load_more_comments'
		);

		$this->loader->add_action(
			'wp_ajax_nopriv_load_more_comments',
			$this->plugin->comment, 
			'ajax_load_more_comments'
		);	

		$this->loader->add_action(
			'wp_ajax_load_comments',
			$this->plugin->comment, 
			'ajax_load_comments'
		);

		$this->loader->add_action(
			'wp_ajax_nopriv_load_comments',
			$this->plugin->comment, 
			'ajax_load_comments'
		);

		$this->loader->add_filter(
			'comment_text',
			$this->plugin->comment, 
			'filter_reported_comment_content',
			1,
			3
		);			

		$this->loader->add_filter(
			'streamtube/comment/form_args',
			$this->plugin->comment, 
			'filter_comment_form_args'
		);

		$this->loader->add_filter(
			'comment_class',
			$this->plugin->comment, 
			'filter_comment_classes',
			10,
			5
		);

		$this->loader->add_filter(
			'comments_template',
			$this->plugin->comment, 
			'load_ajax_comments_template'
		);
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_user_hooks(){

		$this->plugin->user = new Streamtube_Core_User();

		$this->loader->add_filter( 
			'get_avatar_url', 
			$this->plugin->user, 
			'get_avatar_url',
			10,
			3 
		);

		$this->loader->add_action( 
			'register_form', 
			$this->plugin->user, 
			'build_form_registration' 
		);

		$this->loader->add_action( 
			'registration_errors', 
			$this->plugin->user, 
			'verify_registration_data',
			10,
			1
		);		

		$this->loader->add_action( 
			'register_new_user', 
			$this->plugin->user, 
			'save_form_registration',
			9999,
			1
		);

		$this->loader->add_action( 
			'wp_ajax_verify_user', 
			$this->plugin->user, 
			'ajax_verify_user'
		);	

		$this->loader->add_action( 
			'wp_ajax_delete_user_photo', 
			$this->plugin->user, 
			'ajax_delete_user_photo'
		);		

		$this->loader->add_action( 
			'wp_ajax_update_advertising', 
			$this->plugin->user, 
			'ajax_update_advertising'
		);

		$this->loader->add_action( 
			'streamtube/core/advertising/vast_tag_url', 
			$this->plugin->user, 
			'load_vast_tag_url',
			10,
			3
		);	

		$this->plugin->user_profile = new Streamtube_Core_User_Profile();

		$this->loader->add_action( 
			'init', 
			$this->plugin->user_profile, 
			'widgets_init',
			50
		);

		$this->loader->add_action( 
			'init', 
			$this->plugin->user_profile, 
			'add_endpoints', 
			100
		);

		$this->loader->add_action( 
			'streamtube/core/user/header', 
			$this->plugin->user_profile, 
			'the_header', 
			10 
		);

		$this->loader->add_action( 
			'streamtube/core/user/header', 
			$this->plugin->user_profile, 
			'the_navigation', 20 
		);

		$this->loader->add_action( 
			'streamtube/core/user/main', 
			$this->plugin->user_profile, 
			'the_main' 
		);

		$this->loader->add_filter( 
			'template_include', 
			$this->plugin->user_profile, 
			'the_index',
			10,
			1
		);

		$this->loader->add_action( 
			'streamtube/core/user/header/display_name/after', 
			$this->plugin->user_profile, 
			'the_action_buttons'
		);

		$this->loader->add_action(
			'streamtube/single/video/author/after',
			$this->plugin->user_profile,
			'the_action_buttons'			
		);		

		$this->loader->add_action( 
			'streamtube/core/user/profile/about/bio', 
			$this->plugin->user_profile, 
			'format_user_bio_content',
			10,
			1
		);

		$this->loader->add_action( 
			'wp_enqueue_scripts', 
			$this->plugin->user_profile, 
			'enqueue_scripts',
			20
		);		

		$this->plugin->user_dashboard = new Streamtube_Core_User_Dashboard();

		$this->loader->add_action( 
			'init', 
			$this->plugin->user_dashboard, 
			'add_endpoints',
			100
		);		

		$this->loader->add_action( 
			'template_redirect', 
			$this->plugin->user_dashboard, 
			'the_index',
			15
		);

		$this->loader->add_action( 
			'login_redirect', 
			$this->plugin->user_dashboard, 
			'login_redirect',
			10,
			3
		);

		$this->loader->add_action( 
			'wp', 
			$this->plugin->user_dashboard, 
			'dashboard_redirect',
			9999
		);

		$this->plugin->user_privacy = new Streamtube_Core_User_Privacy();

		$this->loader->add_action( 
			'wp_ajax_deactivate_account', 
			$this->plugin->user_privacy, 
			'ajax_deactivate'
		);

		$this->loader->add_action( 
			'wp_ajax_reactivate_account', 
			$this->plugin->user_privacy, 
			'ajax_reactivate'
		);

		$this->loader->add_action( 
			'wp_ajax_admin_deactivate_user', 
			$this->plugin->user_privacy, 
			'ajax_admin_deactivate_user'
		);

		$this->loader->add_action( 
			'delete_expired_transients', 
			$this->plugin->user_privacy, 
			'schedule_delete_users'
		);			

		$this->loader->add_filter( 
			'streamtube/core/user/dashboard/menu/items', 
			$this->plugin->user_privacy, 
			'add_dashboard_settings_menu'
		);

		$this->loader->add_filter( 
			'streamtube/core/user/dashboard/menu/items', 
			$this->plugin->user_privacy, 
			'filter_dashboard_menu_item',
			9999
		);

		$this->loader->add_filter( 
			'streamtube/core/user/profile/menu/items', 
			$this->plugin->user_privacy, 
			'filter_profile_menu_item',
			9999
		);			

		$this->loader->add_filter( 
			'streamtube/core/user/dashboard/page_header/before', 
			$this->plugin->user_privacy, 
			'display_notices'
		);
	}

	/**
	 *
	 * Define rest hooks
	 * 
	 * @since 1.0.0
	 */
	private function define_rest_hooks(){

		$this->plugin->rest_api = array();

		$this->plugin->rest_api['generate_image'] 	= new StreamTube_Core_Generate_Image_Rest_Controller();
		$this->plugin->rest_api['user'] 			= new StreamTube_Core_User_Rest_Controller();

		foreach (  $this->plugin->rest_api as $rest => $object ) {
			$this->loader->add_action( 
				'rest_api_init',
				$object,
				'rest_api_init'
			);
		}
	}

	private function define_googlesitekit_hooks(){
		/**
		 * The class responsible for defining Google Sitekit.
		 */
		$this->include_file( 'third-party/googlesitekit/class-streamtube-core-googlesitekit.php' );

		/**
		 * The class responsible for defining Analytics module of Google Sitekit.
		 */
		$this->include_file( 'third-party/googlesitekit/class-streamtube-core-googlesitekit-analytics.php' );

		/**
		 * The class responsible for defining Tag Manager module of Google Sitekit.
		 */
		$this->include_file( 'third-party/googlesitekit/class-streamtube-core-googlesitekit-tag-manager.php' );

		/**
		 * The class responsible for defining Search Console module of Google Sitekit.
		 */
		$this->include_file( 'third-party/googlesitekit/class-streamtube-core-googlesitekit-search-console.php' );		

		/**
		 * The class responsible for defining sitekit rest API
		 */
		$this->include_file( 'third-party/googlesitekit/class-streamtube-core-rest-googlesitekit-controller.php' );		

		$this->plugin->googlesitekit = new stdClass();

		$this->plugin->googlesitekit->analytics = new Streamtube_Core_GoogleSiteKit_Analytics();

		if( $this->plugin->googlesitekit->analytics->is_connected() && $this->get_license() ){

			$this->loader->add_action( 
				'wp_enqueue_scripts', 
				$this->plugin->googlesitekit->analytics,
				'enqueue_scripts' 
			);				

			$this->loader->add_action( 
				'enqueue_embed_scripts', 
				$this->plugin->googlesitekit->analytics,
				'enqueue_embed_scripts' 
			);				

			$this->loader->add_action( 
				'streamtube/core/user/dashboard/dashboard/after', 
				$this->plugin->googlesitekit->analytics, 
				'dashboard'
			);

			$this->loader->add_action(
				'streamtube_check_pageviews',
				$this->plugin->googlesitekit->analytics,
				'cron_update_post_list_pageviews',
				10
			);

			$this->loader->add_action(
				'streamtube_check_videoviews',
				$this->plugin->googlesitekit->analytics,
				'cron_update_post_list_videoviews',
				10
			);	

			if( get_option( 'sitekit_heartbeat_tick', 'on' ) ){
				$this->loader->add_filter(
					'heartbeat_send',
					$this->plugin->googlesitekit->analytics,
					'heartbeat_tick',
					10,
					2
				);
			}

			$this->loader->add_action(
				'streamtube/single/video/manage/control',
				$this->plugin->googlesitekit->analytics,
				'button_analytics',
				100
			);

			$this->loader->add_filter(
				'streamtube_core_get_edit_post_nav_items',
				$this->plugin->googlesitekit->analytics,
				'add_post_nav_item',
				10,
				1
			);	

			$this->loader->add_action(
				'streamtube/single/video/meta',
				$this->plugin->googlesitekit->analytics,
				'load_single_post_views'
			);

			$this->loader->add_filter(
				'manage_video_posts_columns',
				$this->plugin->googlesitekit->analytics,
				'filter_post_table',
				10,
				1
			);

			$this->loader->add_action(
				'manage_video_posts_custom_column',
				$this->plugin->googlesitekit->analytics,
				'filter_post_table_columns',
				10,
				2
			);

			/**
			 * The class responsible for defining analytics rest API
			 */
			$this->include_file( 'third-party/googlesitekit/class-streamtube-core-rest-googlesitekit-analytics-controller.php' );

			$this->plugin->googlesitekit->analytics_rest_api = new StreamTube_Core_GoogleSiteKit_Analytics_Rest_Controller();

			$this->loader->add_action( 
				'rest_api_init',
				$this->plugin->googlesitekit->analytics_rest_api,
				'rest_api_init'
			);
		}

		$this->plugin->googlesitekit->tag_manager = new Streamtube_Core_GoogleSiteKit_Tag_Manager();

		if( $this->plugin->googlesitekit->tag_manager->is_connected() ){

			$this->loader->add_action( 
				'enqueue_embed_scripts', 
				$this->plugin->googlesitekit->tag_manager,
				'enqueue_embed_scripts' 
			);			

			$this->loader->add_filter(
				'streamtube/player/file/setup',
				$this->plugin->googlesitekit->tag_manager,
				'player_tracker',
				10,
				2
			);

		}

		$this->plugin->googlesitekit->search_console = new Streamtube_Core_GoogleSiteKit_Search_Console();
	}

	/**
	 *
	 * myCred Hooks
	 * 
	 * @since 1.0.9
	 */
	private function define_mycred_hooks(){
		/**
		 * The class responsible for defining myCred functions
		 */
		$this->include_file( 'third-party/mycred/class-streamtube-core-mycred.php' );

		$this->plugin->myCRED = new Streamtube_Core_myCRED();

		if( $this->plugin->myCRED->is_activated() && $this->get_license() ){		

			$this->loader->add_action( 
				'widgets_init', 
				'Streamtube_Core_myCRED_Widget_Buy_Points', 
				'register'
			);

			$this->loader->add_action(
				'mycred_log_row_classes',
				$this->plugin->myCRED,
				'filter_log_row_classes',
				10,
				2
			);

			$this->loader->add_action(
				'mycred_log_username',
				$this->plugin->myCRED,
				'filter_mycred_log_username',
				100,
				3
			);

			$this->loader->add_action(
				'mycred_log_entry',
				$this->plugin->myCRED,
				'filter_mycred_log_entry',
				10,
				3
			);			

			$this->loader->add_action(
				'streamtube/user/profile_dropdown/avatar/after',
				$this->plugin->myCRED,
				'show_user_dropdown_profile_balances'
			);		

			$this->loader->add_action(
				'streamtube/core/dashboard/transactions/table/before',
				$this->plugin->myCRED,
				'show_user_balances'
			);

			$this->loader->add_action(
				'streamtube/core/dashboard/withdraw/table/before',
				$this->plugin->myCRED,
				'show_user_balances'
			);

			$this->loader->add_filter(
				'streamtube/core/user/dashboard/menu/items',
				$this->plugin->myCRED,
				'add_dashboard_menu',
				10,
				1
			);

			$this->loader->add_filter(
				'streamtube/core/user/profile/menu/items',
				$this->plugin->myCRED,
				'add_profile_menu',
				10,
				1
			);	

			$this->loader->add_action(
				'streamtube/core/elementor/widgets_registered',
				$this->plugin->myCRED,
				'widgets_registered',
				10,
				1
			);	

			$this->loader->add_action(
				'wp',
				$this->plugin->myCRED,
				'redirect_buy_points_page'			
			);			

			$this->loader->add_filter(
				'mycred_buycred_checkout_cancel',
				$this->plugin->myCRED,
				'filter_cancel_checkout',
				10,
				1				
			);

			$this->loader->add_filter(
				'streamtube/core/advertising/vast_tag_url',
				$this->plugin->myCRED->sell_content,
				'filter_advertisements',
				10,
				3				
			);			

			$this->loader->add_filter(
				'streamtube/player/file/output',
				$this->plugin->myCRED->sell_content,
				'filter_player_output',
				50,
				2				
			);

			$this->loader->add_filter(
				'streamtube/player/embed/output',
				$this->plugin->myCRED->sell_content,
				'filter_player_embed_output',
				50,
				2				
			);					

			$this->loader->add_filter(
				'streamtube/core/video/can_user_download',
				$this->plugin->myCRED->sell_content,
				'filter_download_permission',
				10,
				1				
			);			
	
			$this->loader->add_action(
				'streamtube/core/post/added',
				$this->plugin->myCRED->sell_content,
				'update_price',
				10,
				1
			);

			$this->loader->add_action(
				'streamtube/core/post/updated',
				$this->plugin->myCRED->sell_content,
				'update_price',
				10,
				1
			);			

			$this->loader->add_action(
				'streamtube/core/post/edit/metaboxes',
				$this->plugin->myCRED->sell_content,
				'load_metabox_price',
				20
			);
		
			$this->loader->add_action(
				'wp_ajax_transfers_points',
				$this->plugin->myCRED->transfers,
				'ajax_transfers_points',
				10
			);

			$this->loader->add_action(
				'streamtube/core/user/header/action_buttons',
				$this->plugin->myCRED->transfers,
				'button_donate',
				10
			);

			$this->loader->add_action(
				'wp_footer',
				$this->plugin->myCRED->transfers,
				'modal_donate',
				10
			);			

			$this->loader->add_action( 
				'streamtube/single/video/control', 
				$this->plugin->myCRED->gifts,
				'button_gift' 
			);

			$this->loader->add_action(
				'streamtube/core/mycred/modal/gift/widget',
				$this->plugin->myCRED->gifts,
				'gift_widget_content',
				10
			);

			$this->loader->add_action(
				'wp_footer',
				$this->plugin->myCRED->gifts,
				'modal_gift',
				10
			);

			$this->loader->add_action(
				'mycred_pre_process_cashcred',
				$this->plugin->myCRED->cash_cred,
				'fix_withdrawal_404'
			);

			$this->loader->add_filter(
				'mycred_setup_hooks',
				'Streamtube_Core_myCRED_Hook_Watch_Video',
				'register',
				10,
				2
			);

			$this->loader->add_filter(
				'mycred_setup_hooks',
				'Streamtube_Core_myCRED_Hook_Like_Post',
				'register',
				10,
				2
			);

			$this->loader->add_filter(
				'mycred_all_references',
				$this->plugin->myCRED,
				'filter_references',
				10
			);
		}
	}

	/**
	 *
	 * buddyPress hooks
	 * 
	 */
	private function define_buddypress_hooks(){

		$this->include_file( 'third-party/buddypress/class-streamtube-core-buddypress.php' );

		$this->plugin->buddypress = new StreamTube_Core_buddyPress();

		if( ! $this->plugin->buddypress->is_active() || ! $this->get_license()  ){
			return;
		}

		$this->loader->add_action( 
			'wp_enqueue_scripts', 
			$this->plugin->buddypress,
			'enqueue_scripts'
		);

		$this->loader->add_action( 
			'widgets_init', 
			$this->plugin->buddypress,
			'register_sidebar'
		);	

		$this->loader->add_action( 
			'widgets_init', 
			'StreamTube_Core_buddyPress_Widget_User_List', 
			'register'
		);

		$this->loader->add_filter( 
			'body_class', 
			$this->plugin->buddypress,
			'filter_body_class'
		);		

		$this->loader->add_filter( 
			'is_buddypress', 
			$this->plugin->buddypress,
			'filter_is_buddypress'
		);

		$this->loader->add_filter( 
			'bp_members_get_user_url', 
			$this->plugin->buddypress,
			'filter_bp_members_get_user_url',
			10,
			4
		);

		$this->loader->add_action( 
			'wp', 
			$this->plugin->buddypress,
			'_set_displayed_user_id',
			10
		);

		$this->loader->add_action( 
			'init', 
			$this->plugin->buddypress,
			'set_displayed_user_id'
		);

		$this->loader->add_filter( 
			'bp_get_loggedin_user_avatar', 
			$this->plugin->buddypress,
			'filter_bp_get_loggedin_user_avatar',
			10,
			3
		);			

		$this->loader->add_filter( 
			'bp_get_add_friend_button', 
			$this->plugin->buddypress,
			'filter_bp_button'
		);

		$this->loader->add_filter( 
			'bp_get_send_message_button_args', 
			$this->plugin->buddypress,
			'filter_bp_button'
		);

		$this->loader->add_filter( 
			'bp_follow_get_add_follow_button', 
			$this->plugin->buddypress,
			'filter_bp_button'
		);

		$this->loader->add_filter( 
			'bp_get_button', 
			$this->plugin->buddypress,
			'filter_bp_get_button',
			10,
			3
		);

		$this->loader->add_filter( 
			'bp_embed_oembed_html', 
			$this->plugin->buddypress,
			'filter_bp_embed_oembed_html',
			10,
			4
		);					

		$this->loader->add_action(
			'bp_activity_post_type_published',
			$this->plugin->buddypress,
			'notify_followers_of_new_activity',
			10,
			3
		);

		$this->loader->add_action(
			'publish_video',
			$this->plugin->buddypress,
			'notify_followers_of_new_submit',
			10,
			3
		);

		$this->loader->add_action(
			'publish_post',
			$this->plugin->buddypress,
			'notify_followers_of_new_submit',
			10,
			3
		);		

		$this->loader->add_action(
			'streamtube/core/user/card/name/after',
			$this->plugin->buddypress,
			'display_primary_button'
		);

		$this->loader->add_action(
			'streamtube/buddypress/activity_loop/before',
			$this->plugin->buddypress,
			'display_featured_activities'			
		);

		$this->loader->add_action(
			'streamtube/core/user/dashboard/page_header/before',
			$this->plugin->buddypress,
			'display_notices'			
		);		

		$this->loader->add_action(
			'wp_body_open',
			$this->plugin->buddypress,
			'display_float_user_list'			
		);	

		$this->loader->add_action(
			'admin_enqueue_scripts',
			$this->plugin->buddypress,
			'admin_enqueue_scripts'			
		);							

		/******************** Groups ********************/

		$this->loader->add_filter( 
			'bp_get_group_join_button', 
			$this->plugin->buddypress->groups,
			'filter_bp_get_group_join_button',
			10,
			2
		);

		$this->loader->add_filter( 
			'bp_get_group_description', 
			$this->plugin->buddypress->groups,
			'filter_bp_get_group_description',
			10,
			2
		);			

		$this->loader->add_filter( 
			'streamtube/core/user/profile/menu/items', 
			$this->plugin->buddypress->groups,
			'display_profile_menu'
		);

		/******************** Members ********************/

		$this->loader->add_action(
			'streamtube/core/user/card/name/after',
			$this->plugin->buddypress->members,
			'display_last_active_time'
		);		
		
		$this->loader->add_filter( 
			'bp_get_member_avatar', 
			$this->plugin->buddypress->members,
			'filter_bp_get_member_avatar',
			10,
			2
		);		

		$this->loader->add_filter( 
			'bp_get_members_invitations_send_invites_permalink', 
			$this->plugin->buddypress->members,
			'filter_bp_get_members_invitations_send_invites_permalink',
			10,
			2
		);

		$this->loader->add_filter( 
			'bp_get_members_invitations_list_invites_permalink', 
			$this->plugin->buddypress->members,
			'filter_bp_get_members_invitations_list_invites_permalink',
			10,
			2
		);			

		$this->loader->add_filter( 
			'streamtube/core/user/dashboard/menu/items', 
			$this->plugin->buddypress->members,
			'display_dashboard_menu_item'
		);

		/******************** Friends ********************/
		$this->loader->add_filter( 
			'bp_ajax_querystring', 
			$this->plugin->buddypress->friends,
			'filter_bp_ajax_querystring',
			40,
			2
		);		

		$this->loader->add_action(
			'streamtube/core/user/header/action_buttons',
			$this->plugin->buddypress->friends,
			'display_the_single_add_friend_button'
		);

		$this->loader->add_filter( 
			'streamtube/core/user/profile/menu/items', 
			$this->plugin->buddypress->friends,
			'display_profile_menu'
		);			

		/******************** Messages ********************/
		$this->loader->add_filter(
			'bp_get_message_css_class', 
			$this->plugin->buddypress->messages,
			'filter_bp_get_message_css_class'
		);

		$this->loader->add_filter( 
			'streamtube_filter_wp_menu_item_title_wpmi', 
			$this->plugin->buddypress->messages,
			'filter_message_icon',
			10,
			4
		);		

		$this->loader->add_action(
			'streamtube/core/user/header/action_buttons',
			$this->plugin->buddypress->messages,
			'display_send_message_button'			
		);			

		$this->loader->add_filter( 
			'streamtube_filter_wp_menu_item_title', 
			$this->plugin->buddypress->messages,
			'display_unread_messages_badge',
			10,
			5
		);	

		$this->loader->add_action(
			'streamtube/core/user/dashboard/page_header/before',
			$this->plugin->buddypress->messages,
			'display_global_notice'			
		);		

		$this->loader->add_filter( 
			'streamtube/core/user/dashboard/menu/items', 
			$this->plugin->buddypress->messages,
			'display_dashboard_menu_item'
		);

		/******************** Notifications ********************/
		$this->loader->add_filter( 
			'bp_notifications_get_registered_components', 
			$this->plugin->buddypress->notifications,
			'filter_bp_notifications_get_registered_components',
			10,
			2
		);		

		$this->loader->add_filter( 
			'bp_get_the_notification_description', 
			$this->plugin->buddypress->notifications,
			'filter_notification_description',
			10,
			2
		);

		$this->loader->add_action( 
			'streamtube_core_post_approved', 
			$this->plugin->buddypress->notifications,
			'notify_author_post_moderated',
			10,
			3
		);

		$this->loader->add_action( 
			'streamtube_core_post_rejected', 
			$this->plugin->buddypress->notifications,
			'notify_author_post_moderated',
			10,
			3
		);

		$this->loader->add_action( 
			'streamtube/header/profile/before', 
			$this->plugin->buddypress->notifications,
			'display_header_notification_button'
		);

		$this->loader->add_filter( 
			'streamtube_filter_wp_menu_item_title', 
			$this->plugin->buddypress->notifications,
			'display_unread_notifications_badge',
			10,
			5
		);

		$this->loader->add_filter( 
			'streamtube_filter_wp_menu_item_title_wpmi', 
			$this->plugin->buddypress->notifications,
			'filter_notification_icon',
			10,
			4
		);

		$this->loader->add_filter( 
			'streamtube/core/user/dashboard/menu/items', 
			$this->plugin->buddypress->notifications,
			'display_dashboard_menu_item'
		);

		/******************** Activity ********************/
		$this->loader->add_filter( 
			'bp_ajax_querystring', 
			$this->plugin->buddypress->activity,
			'filter_bp_ajax_querystring',
			50,
			2
		);

		$this->loader->add_filter( 
			'bp_get_activity_css_class', 
			$this->plugin->buddypress->activity,
			'filter_bp_get_activity_css_class'
		);		

		$this->loader->add_filter( 
			'bp_get_activity_delete_link', 
			$this->plugin->buddypress->activity,
			'filter_bp_get_activity_delete_link'
		);	

		$this->loader->add_filter( 
			'bp_activity_entry_content', 
			$this->plugin->buddypress->activity,
			'display_the_player'
		);		

		$this->loader->add_filter( 
			'streamtube/core/user/profile/menu/items', 
			$this->plugin->buddypress->activity,
			'display_profile_menu'
		);

		$this->loader->add_action( 
			'wp_ajax_dismiss_migrate_activity', 
			$this->plugin->buddypress->activity,
			'ajax_dismiss_migrate_activity'
		);		

		$this->loader->add_action( 
			'wp_ajax_migrate_activity', 
			$this->plugin->buddypress->activity,
			'ajax_migrate_activity'
		);			

		$this->loader->add_action( 
			'admin_notices', 
			$this->plugin->buddypress->activity,
			'display_admin_notices'
		);		

		/******************** Follow ********************/
		if( $this->plugin->buddypress->follow->is_active() ){
			$this->loader->add_action( 
				'init', 
				$this->plugin->buddypress->follow,
				'remove_hooks'
			);

			$this->loader->add_action( 
				'admin_init', 
				$this->plugin->buddypress->follow,
				'migrate_wpuf'
			);
		
			$this->loader->add_filter( 
				'bp_ajax_querystring', 
				$this->plugin->buddypress->follow,
				'filter_bp_ajax_querystring',
				30,
				2
			);

			$this->loader->add_filter( 
				'bp_has_members', 
				$this->plugin->buddypress->follow,
				'filter_bp_has_members_template',
				10,
				3
			);

			$this->loader->add_action(
				'streamtube/core/user/header/action_buttons',
				$this->plugin->buddypress->follow,
				'display_the_single_follow_button'
			);

			$this->loader->add_filter( 
				'streamtube/core/user/profile/menu/items', 
				$this->plugin->buddypress->follow,
				'display_profile_menu'
			);

			$this->loader->add_action( 
				'admin_notices', 
				$this->plugin->buddypress->follow,
				'display_admin_notices'
			);
		}
	}

	/**
	 *
	 * Better Messages Hooks
	 * 
	 * @since 1.1.3
	 */
	private function define_better_messages_hooks(){
		$this->include_file( 'third-party/better-messages/class-streamtube-core-better-messages.php' );

		$this->plugin->better_messages = new StreamTube_Core_Better_Messages();

		if( ! $this->plugin->better_messages->is_active() || ! $this->get_license() ){
			return;
		}

		if( ! $this->get_license() ){
			$this->loader->add_action( 
				'add_meta_boxes', 
				$this->plugin->better_messages->admin,
				'unregistered_meta_boxes'
			);	
		}else{

			$this->loader->add_action( 
				'add_meta_boxes', 
				$this->plugin->better_messages,
				'add_meta_boxes'
			);		

			$this->loader->add_action( 
				'save_post', 
				$this->plugin->better_messages,
				'update_post_settings',
				10,
				1
			);

			$this->loader->add_action(
				'wp_ajax_get_recipient_info',
				$this->plugin->better_messages,
				'get_recipient_info'
			);

			$this->loader->add_action(
				'wp_ajax_nopriv_get_recipient_info',
				$this->plugin->better_messages,
				'get_recipient_info'
			);

			$this->loader->add_action(
				'streamtube/avatar_dropdown/after',
				$this->plugin->better_messages,
				'show_unread_threads_badge_on_avatar'
			);			

			$this->loader->add_filter(
				'streamtube/core/user/profile/menu/items',
				$this->plugin->better_messages,
				'add_profile_menu',
				10,
				1
			);			

			$this->loader->add_filter(
				'streamtube/core/user/dashboard/menu/items',
				$this->plugin->better_messages,
				'add_dashboard_menu',
				10,
				1
			);

			$this->loader->add_action(
				'streamtube/core/user/header/action_buttons',
				$this->plugin->better_messages,
				'button_private_message',
				20
			);

			$this->loader->add_action(
				'streamtube/core/user/card/name/after',
				$this->plugin->better_messages,
				'user_list_button_private_message',
				20
			);

			$this->loader->add_action(
				'wp_footer',
				$this->plugin->better_messages,
				'modal_private_message',
				10
			);	

			$this->loader->add_action(
				'wp',
				$this->plugin->better_messages,
				'goto_inbox',
				10
			);

			$this->loader->add_filter(
				'streamtube_core_get_edit_post_nav_items',
				$this->plugin->better_messages,
				'add_post_nav_item',
				10,
				1
			);

			$this->loader->add_filter(
				'body_class',
				$this->plugin->better_messages,
				'filter_body_class',
				10,
				1
			);				

			$this->loader->add_filter(
				'streamtube_has_post_comments',
				$this->plugin->better_messages,
				'filter_has_post_comments',
				10,
				1
			);				
			
			$this->loader->add_filter(
				'comments_template',
				$this->plugin->better_messages,
				'filter_comments_template',
				100,
				1
			);

			$this->loader->add_action(
				'streamtube/post/thumbnail/after',
				$this->plugin->better_messages,
				'add_post_thumbnail_livechat_icon'
			);

			$this->loader->add_action(
				'streamtube/flat_post/item',
				$this->plugin->better_messages,
				'add_post_thumbnail_livechat_icon'
			);

			$this->loader->add_filter(
				'bp_better_messages_can_send_message',
				$this->plugin->better_messages,
				'filter_disable_reply',
				100,
				3
			);

			$this->loader->add_filter(
				'better_messages_get_thread_type',
				$this->plugin->better_messages,
				'filter_thread_type',
				100,
				2
			);

			$this->loader->add_filter(
				'better_messages_rest_user_item',
				$this->plugin->better_messages,
				'filter_rest_user_item',
				100,
				3
			);				

			$this->loader->add_action(
				'streamtube_core_post_approved',
				$this->plugin->better_messages,
				'send_pm_after_post_moderated',
				10,
				3
			);

			$this->loader->add_action(
				'streamtube_core_post_rejected',
				$this->plugin->better_messages,
				'send_pm_after_post_moderated',
				10,
				3					
			);				

			$this->loader->add_action( 
				'widgets_init', 
				'Streamtube_Core_Widget_LiveChat', 
				'register'
			);
		}
	}

	/**
	 *
	 * bbPress Hooks
	 * 
	 * @since 1.1.9
	 */
	private function define_bbpress(){
		$this->include_file( 'third-party/bbpress/class-streamtube-core-bbpress.php' );

		$this->plugin->bbpress = new StreamTube_Core_bbPress();

		if( $this->plugin->bbpress->is_activated() ){

			$this->loader->add_action(
				'init',
				$this->plugin->bbpress,
				'add_forum_thumbnail'
			);
						
			$this->loader->add_action(
				'init',
				$this->plugin->bbpress,
				'redirect_search_page'
			);			
		}
	}

	/**
	 *
	 * Youtube Importer Hooks
	 * 
	 * @since 1.1.9
	 */
	private function define_youtube_importer(){

		$this->include_file( 'third-party/youtube-importer/class-streamtube-core-youtube-importer.php' );

		$this->plugin->yt_importer = new StreamTube_Core_Youtube_Importer();		

		if( ! $this->get_license() ){

			$this->loader->add_action( 
				'admin_menu', 
				$this->plugin->yt_importer->admin,
				'unregistered'
			);			

		}else{

			$this->loader->add_action( 
				'wp_ajax_youtube_search', 
				$this->plugin->yt_importer,
				'ajax_search_content'
			);

			$this->loader->add_action( 
				'wp_ajax_youtube_import', 
				$this->plugin->yt_importer,
				'ajax_import_content'
			);

			$this->loader->add_action( 
				'wp_ajax_youtube_bulk_import', 
				$this->plugin->yt_importer,
				'ajax_bulk_import_content'
			);

			$this->loader->add_action( 
				'wp_ajax_youtube_cron_bulk_import', 
				$this->plugin->yt_importer,
				'ajax_run_bulk_import_content'
			);

			$this->loader->add_filter( 
				'template_include', 
				$this->plugin->yt_importer,
				'template_run_bulk_import_content',
				10,
				1
			);

			$this->loader->add_action( 
				'wp_ajax_get_yt_importer_tax_terms', 
				$this->plugin->yt_importer,
				'ajax_get_tax_terms'
			);

			$this->loader->add_action( 
				'streamtube/core/embed/imported', 
				$this->plugin->yt_importer,
				'import_youtube_embed',
				10,
				2
			);			

			$this->loader->add_action( 
				'init', 
				$this->plugin->yt_importer->post_type,
				'post_type'
			);

			$this->loader->add_action( 
				'add_meta_boxes', 
				$this->plugin->yt_importer->admin,
				'add_meta_boxes'
			);

			$this->loader->add_action( 
				'save_post', 
				$this->plugin->yt_importer->admin, 
				'save_settings',
				10,
				1 
			);		

			$this->loader->add_filter(
				'manage_youtube_importer_posts_columns',
				$this->plugin->yt_importer->admin,
				'post_table',
				10,
				1
			);

			$this->loader->add_action(
				'manage_youtube_importer_posts_custom_column',
				$this->plugin->yt_importer->admin,
				'post_table_columns',
				10,
				2
			);

			$this->loader->add_action(
				'pre_get_posts',
				$this->plugin->yt_importer->admin,
				'pre_get_posts'
			);			
		}
	}

	/**
	 *
	 * Bunny Stream Hooks
	 * 
	 */
	private function define_bunnycdn(){

		$this->include_file( 'third-party/bunnycdn/class-streamtube-core-bunnycdn.php' );

		$this->plugin->bunnycdn = new Streamtube_Core_BunnyCDN();

		if( ! $this->get_license() ){
			$this->loader->add_action( 
				'admin_menu', 
				$this->plugin->bunnycdn->admin,
				'unregistered'
			);
		}else{

			$this->loader->add_action( 
				'admin_menu', 
				$this->plugin->bunnycdn->admin,
				'registered'
			);				

			$this->loader->add_action(
				'add_attachment',
				$this->plugin->bunnycdn,
				'add_attachment',
				10,
				1
			);

			$this->loader->add_action(
				'attachment_updated',
				$this->plugin->bunnycdn,
				'attachment_updated',
				10,
				1
			);

			$this->loader->add_action(
				'delete_attachment',
				$this->plugin->bunnycdn,
				'delete_attachment',
				10,
				1
			);

			$this->loader->add_action(
				'wp_after_insert_post',
				$this->plugin->bunnycdn,
				'fetch_external_video',
				20,
				1
			);

			$this->loader->add_action(
				'post_updated',
				$this->plugin->bunnycdn,
				'post_updated_fetch_external_video',
				20,
				1
			);

			$this->loader->add_action(
				'streamtube/core/embed/imported',
				$this->plugin->bunnycdn,
				'fetch_external_video_embed',
				20,
				2
			);					

			$this->loader->add_action(
				'wp_get_attachment_url',
				$this->plugin->bunnycdn,
				'filter_wp_get_attachment_url',
				1000,
				2
			);

			$this->loader->add_filter(
				'streamtube/player/file/setup',
				$this->plugin->bunnycdn,
				'filter_player_setup',
				10,
				2
			);		
			
			$this->loader->add_filter(
				'streamtube/core/player/load_video_source',
				$this->plugin->bunnycdn,
				'filter_player_load_source',
				10,
				3
			);				

			$this->loader->add_filter(
				'streamtube/player/file/output',
				$this->plugin->bunnycdn,
				'filter_player_output',
				50,
				3
			);
		
			$this->loader->add_filter(
				'streamtube/core/video/download_file_url',
				$this->plugin->bunnycdn,
				'filter_download_file_url',
				10,
				2
			);			

			$this->loader->add_action(
				'streamtube/core/post/edit/thumbnail_content',
				$this->plugin->bunnycdn,
				'thumbnail_notice',
				10,
				1
			);

			$this->loader->add_action(
				'wp_ajax_get_bunnycdn_video_status',
				$this->plugin->bunnycdn,
				'ajax_get_video_status'
			);
			
			$this->loader->add_action(
				'wp_ajax_refresh_bunny_data',
				$this->plugin->bunnycdn,
				'ajax_refresh_bunny_data'
			);			

			$this->loader->add_action(
				'wp_ajax_bunnycdn_sync',
				$this->plugin->bunnycdn,
				'ajax_sync'
			);

			$this->loader->add_action(
				'wp_ajax_bunnycdn_retry_sync',
				$this->plugin->bunnycdn,
				'ajax_retry_sync'
			);

			$this->loader->add_action(
				'edit_post_video',
				$this->plugin->bunnycdn,
				'refresh_bunny_data'
			);				

			$this->loader->add_action(
				'init',
				$this->plugin->bunnycdn,
				'webhook_callback'
			);

			$this->loader->add_action(
				'streamtube/core/bunny/webhook/update',
				$this->plugin->bunnycdn,
				'update_thumbnail_images',
				10,
				3
			);

			$this->loader->add_action(
				'streamtube/core/bunny/webhook/update',
				$this->plugin->bunnycdn,
				'delete_original_file',
				10,
				3
			);			

			$this->loader->add_action(
				'streamtube/core/bunny/webhook/update',
				$this->plugin->bunnycdn,
				'auto_publish_after_success_encoding',
				20,
				3
			);

			$this->loader->add_action(
				'streamtube/core/bunny/webhook/update',
				$this->plugin->bunnycdn,
				'notify_author_after_encoding_failed',
				20,
				3
			);	

			$this->loader->add_filter(
				'streamtube/core/video/thumbnail_url_2',
				$this->plugin->bunnycdn,
				'filter_thumbnail_image_2',
				20,
				3
			);				

			$this->loader->add_action(
				'profile_update',
				$this->plugin->bunnycdn,
				'update_user_collection',
				10,
				3
			);

			$this->loader->add_filter( 
				'bulk_actions-upload', 
				$this->plugin->bunnycdn, 
				'admin_bulk_actions',
				10,
				2
			);			

			$this->loader->add_action(
				'handle_bulk_actions-edit-video',
				$this->plugin->bunnycdn,
				'admin_handle_bulk_actions',
				10,
				3
			);

			$this->loader->add_action(
				'handle_bulk_actions-upload',
				$this->plugin->bunnycdn,
				'admin_handle_bulk_actions',
				10,
				3
			);

			$this->loader->add_action(
				'admin_notices',
				$this->plugin->bunnycdn,
				'admin_handle_bulk_admin_notices',
				10
			);
			

			$this->loader->add_filter( 
				'manage_media_columns',
				$this->plugin->bunnycdn,
				'admin_media_table'
			);

			$this->loader->add_action( 
				'manage_media_custom_column',
				$this->plugin->bunnycdn,
				'admin_media_table_columns',
				10,
				2
			);					

			$this->loader->add_filter(
				'manage_video_posts_columns',
				$this->plugin->bunnycdn,
				'admin_post_table',
				10,
				1
			);

			$this->loader->add_action(
				'manage_video_posts_custom_column',
				$this->plugin->bunnycdn,
				'admin_post_table_columns',
				10,
				2
			);

			$this->loader->add_action(
				'add_meta_boxes',
				$this->plugin->bunnycdn->admin,
				'add_meta_boxes'
			);	

			$this->loader->add_filter( 
				'bulk_actions-edit-video', 
				$this->plugin->bunnycdn, 
				'admin_bulk_actions',
				10,
				2
			);


			$this->loader->add_filter(
				'manage_users_columns',
				$this->plugin->bunnycdn,
				'admin_user_table',
				10,
				1
			);

			$this->loader->add_filter(
				'manage_users_custom_column',
				$this->plugin->bunnycdn,
				'admin_user_table_columns',
				10,
				3
			);					

			$this->loader->add_action(
				'wp_ajax_check_videos_progress',
				$this->plugin->bunnycdn->admin,
				'ajax_check_videos_progress',
				10
			);			

			$this->loader->add_action(
				'admin_footer',
				$this->plugin->bunnycdn->admin,
				'interval_check_videos_progress',
				10
			);

			$this->loader->add_action(
				'admin_notices',
				$this->plugin->bunnycdn->admin,
				'notices',
				10
			);	

			$this->loader->add_action(
				'wp_ajax_read_file_log_content',
				$this->plugin->bunnycdn,
				'ajax_read_log_content'
			);	

			$this->loader->add_action(
				'wp_ajax_read_task_log_content',
				$this->plugin->bunnycdn,
				'ajax_read_task_log_content'
			);

			$this->loader->add_filter(
				'wp_video_extensions',
				$this->plugin->bunnycdn,
				'filter_allow_formats',
				9999,
				1
			);

			$this->loader->add_filter(
				'streamtube/core/generate_image_from_file',
				$this->plugin->bunnycdn,
				'rest_generate_thumbnail_image',
				10,
				2
			);

			$this->loader->add_filter(
				'better_messages_rest_message_meta',
				$this->plugin->bunnycdn,
				'filter_better_messages_rest_message_meta',
				100,
				4
			);	
		}
	}

	/**
	 *
	 * PMP Hooks
	 * 
	 */
	private function define_pmpro(){

		$this->include_file( 'third-party/pmpro/short-functions.php' );
		$this->include_file( 'third-party/pmpro/class-streamtube-core-pmpro.php' );

		$this->plugin->pmpro = new StreamTube_Core_PMPro();

		if( ! $this->plugin->pmpro->is_activated() || ! $this->get_license() ){
			return;
		}

		$this->loader->add_action(
			'add_meta_boxes',
			$this->plugin->pmpro->admin,
			'add_meta_boxes',
			10
		);	

		$this->loader->add_action(
			 'wp_enqueue_scripts', 
			 $this->plugin->pmpro, 
			 'enqueue_scripts' 
		);	

		$this->loader->add_action( 
			'init', 
			$this->plugin->pmpro, 
			'shortcode_membership_levels'
		);

		$this->loader->add_action( 
			'wp_ajax_get_pmpro_invoice_detail', 
			$this->plugin->pmpro, 
			'ajax_get_invoice_detail'
		);

		$this->loader->add_action( 
			'wp', 
			$this->plugin->pmpro, 
			'redirect_default_pages'
		);

		$this->loader->add_action(
			'pmpro_membership_level_after_other_settings',
			$this->plugin->pmpro,
			'add_level_settings_box'
		);

		$this->loader->add_action(
			'pmpro_save_membership_level',
			$this->plugin->pmpro,
			'update_level_settings'
		);								

		$this->loader->add_action(
			'streamtube/flat_post/item',
			$this->plugin->pmpro,
			'add_thumbnail_paid_badge'
		);		

		$this->loader->add_action( 
			'streamtube/post/thumbnail/after', 
			$this->plugin->pmpro, 
			'add_thumbnail_paid_badge',
			10,
			1 
		);

		$this->loader->add_action( 
			'init', 
			$this->plugin->pmpro, 
			'disable_comments_filter',
			10
		);		

		$this->loader->add_filter( 
			'streamtube/core/advertising/vast_tag_url', 
			$this->plugin->pmpro, 
			'filter_advertisements',
			10,
			3
		);

		$this->loader->add_filter( 
			'streamtube/player/file/output', 
			$this->plugin->pmpro, 
			'filter_player_output',
			50,
			2
		);

		$this->loader->add_filter( 
			'streamtube/player/embed/output', 
			$this->plugin->pmpro, 
			'filter_player_embed_output',
			50,
			2 
		);

		$this->loader->add_filter( 
			'streamtube/core/video/can_user_download', 
			$this->plugin->pmpro, 
			'filter_download_permission',
			10,
			1 
		);

		$this->loader->add_filter( 
			'streamtube/core/widget/posts/posts_where', 
			$this->plugin->pmpro, 
			'filter_widget_posts_where',
			10,
			2
		);

		$this->loader->add_filter( 
			'streamtube/core/widget/posts/posts_join', 
			$this->plugin->pmpro, 
			'filter_widget_posts_join',
			10,
			2
		);

		$this->loader->add_filter( 
			'streamtube/core/widget/posts/posts_distinct', 
			$this->plugin->pmpro, 
			'filter_widget_posts_distinct',
			10,
			2
		);	

		$this->loader->add_filter( 
			'streamtube/core/user/dashboard/menu/items', 
			$this->plugin->pmpro, 
			'add_dashboard_menu',
			10,
			1
		);

		$this->loader->add_filter( 
			'streamtube/core/user/profile/menu/items', 
			$this->plugin->pmpro, 
			'add_profile_menu',
			10,
			1
		);

		$this->loader->add_action( 
			'streamtube/core/post/edit/metaboxes', 
			$this->plugin->pmpro, 
			'add_membership_levels_widget',
			10
		);

		$this->loader->add_action( 
			'init', 
			$this->plugin->pmpro, 
			'save_membership_levels_widget'
		);		

		$this->loader->add_action( 
			'streamtube/core/elementor/widgets_registered', 
			$this->plugin->pmpro, 
			'elementor_widgets_registered'
		);	
	}

	/**
	 *
	 * Define Woocommerce Hooks
	 * 
	 */
	private function define_woocommerce(){

		$this->include_file( 'third-party/woocommerce/short-functions.php' );

		if( ! $this->get_license() ){
			return;
		}

		$this->include_file( 'third-party/woocommerce/class-streamtube-core-woocommerce.php' );

		$this->plugin->woocommerce = new Streamtube_Core_Woocommerce();
		
		$this->loader->add_action(
			'init',
			$this->plugin->woocommerce,
			'remove_default'
		);

		$this->loader->add_action(
			'woocommerce_before_shop_loop_item_title',
			$this->plugin->woocommerce,
			'display_template_loop_product_thumbnail'
		);

		$this->loader->add_action(
			'woocommerce_shop_loop_item_title',
			$this->plugin->woocommerce,
			'display_template_loop_product_title'
		);		

		$this->loader->add_action(
			'wp_ajax_get_cart_total',
			$this->plugin->woocommerce,
			'ajax_get_cart_total'
		);

		$this->loader->add_action(
			'wp_ajax_nopriv_get_cart_total',
			$this->plugin->woocommerce,
			'ajax_get_cart_total'
		);

		$this->loader->add_action( 
			'streamtube/header/profile/before', 
			$this->plugin->woocommerce,
			'the_cart_button'
		);

		$this->loader->add_filter( 
			'woocommerce_order_is_paid_statuses', 
			$this->plugin->woocommerce,
			'filter_order_is_paid_statuses'
		);			

		$this->loader->add_filter( 
			'woocommerce_is_account_page', 
			$this->plugin->woocommerce,
			'filter_is_account_page'
		);	

		$this->loader->add_filter( 
			'woocommerce_is_purchasable', 
			$this->plugin->woocommerce,
			'filter_is_purchasable',
			10,
			2
		);				

		$this->loader->add_filter( 
			'streamtube_filter_wp_menu_item_title', 
			$this->plugin->woocommerce,
			'filter_wp_menu_item_title',
			10,
			5
		);

		$this->loader->add_filter( 
			'shortcode_atts_products', 
			$this->plugin->woocommerce,
			'filter_shortcode_atts_products',
			10,
			4
		);

		$this->loader->add_filter( 
			'woocommerce_shortcode_products_query', 
			$this->plugin->woocommerce,
			'filter_shortcode_products_query',
			10,
			3
		);

		$this->loader->add_action( 
			'woocommerce_product_is_visible', 
			$this->plugin->woocommerce,
			'filter_product_is_visible'
		);		

		$this->loader->add_action( 
			'streamtube/woocommerce/product_single_title/after', 
			$this->plugin->woocommerce,
			'display_single_product_rating'
		);

		$this->loader->add_filter( 
			'woocommerce_single_product_image_thumbnail_html', 
			$this->plugin->woocommerce,
			'filter_single_product_image_thumbnail_html',
			10,
			2
		);		

		$this->loader->add_filter( 
			'woocommerce_product_tabs', 
			$this->plugin->woocommerce,
			'display_product_gallery_tab'
		);		

		$this->loader->add_filter( 
			'single_product_archive_thumbnail_size', 
			$this->plugin->woocommerce,
			'filter_thumbnail_image_size'
		);						

		$this->loader->add_action( 
			'streamtube/core/user/dashboard/page_header/before', 
			$this->plugin->woocommerce,
			'display_dashboard_notices'
		);				

		if( $this->plugin->woocommerce->sell_content->is_active() ){

			$this->loader->add_action(
				'add_meta_boxes',
				$this->plugin->woocommerce->sell_content,
				'add_meta_boxes',
				10
			);			

			$this->loader->add_action( 
				'save_post_video', 
				$this->plugin->woocommerce->sell_content,
				'update_relevant_product',
				10,
				1 
			);

			$this->loader->add_action(
				'save_post_video',
				$this->plugin->woocommerce->sell_content,
				'do_add_builtin_product',
				20,
				3
			);

			$this->loader->add_action(
				'streamtube_core_post_approved',
				$this->plugin->woocommerce->sell_content,
				'do_update_builtin_product',
				10
			);

			$this->loader->add_action(
				'streamtube_core_post_rejected',
				$this->plugin->woocommerce->sell_content,
				'do_update_builtin_product',
				10
			);

			$this->loader->add_action(
				'streamtube_core_post_pending',
				$this->plugin->woocommerce->sell_content,
				'do_update_builtin_product',
				10
			);

			$this->loader->add_action(
				'streamtube_core_post_trashed',
				$this->plugin->woocommerce->sell_content,
				'do_update_builtin_product',
				10
			);

			$this->loader->add_action(
				'streamtube_core_post_restored',
				$this->plugin->woocommerce->sell_content,
				'do_update_builtin_product',
				10
			);							

			$this->loader->add_action(
				'before_delete_post',
				$this->plugin->woocommerce->sell_content,
				'do_delete_builtin_product',
				20,
				2
			);		

			$this->loader->add_filter( 
				'streamtube/core/woocommerce/sell_content/ref_product', 
				$this->plugin->woocommerce->sell_content,
				'set_default_relevant_product',
				10,
				2
			);

			$this->loader->add_filter( 
				'streamtube/core/woocommerce/sell_content/ref_product', 
				$this->plugin->woocommerce->sell_content,
				'set_builtin_product',
				20,
				2
			);

			$this->loader->add_filter( 
				'streamtube/core/advertising/vast_tag_url', 
				$this->plugin->woocommerce->sell_content,
				'filter_advertisements',
				10,
				3
			);						

			$this->loader->add_filter( 
				'streamtube/player/file/output', 
				$this->plugin->woocommerce->sell_content,
				'filter_player_output',
				40,
				2
			);

			$this->loader->add_filter( 
				'streamtube/player/embed/output', 
				$this->plugin->woocommerce->sell_content,
				'filter_player_embed_output',
				40,
				2
			);

			$this->loader->add_filter( 
				'post_class', 
				$this->plugin->woocommerce->sell_content,
				'filter_post_classes',
				10,
				1
			);

			$this->loader->add_filter( 
				'streamtube/core/video/can_user_download', 
				$this->plugin->woocommerce->sell_content,
				'filter_download_permission',
				10,
				1
			);

			$this->loader->add_filter( 
				'streamtube/core/widget/posts/query_args', 
				$this->plugin->woocommerce->sell_content,
				'filter_widget_post_list_query',
				10,
				2
			);				

			$this->loader->add_action( 
				'streamtube/post/meta/item/before', 
				$this->plugin->woocommerce->sell_content,
				'add_price_badge',
				10,
				1 
			);

			$this->loader->add_action(
				'streamtube/core/post/edit/content/after',
				$this->plugin->woocommerce->sell_content,
				'display_metabox_sell_content',
				30
			);

			$this->loader->add_action(
				'wp_enqueue_scripts',
				$this->plugin->woocommerce->sell_content,
				'enqueue_scripts'
			);				

			$this->loader->add_filter(
				'streamtube/core/get_full_post_data',
				$this->plugin->woocommerce->sell_content,
				'filter_get_full_post_data',
				10,
				3
			);			

			$this->loader->add_action(
				'after_delete_post',
				$this->plugin->woocommerce->sell_content,
				'delete_product_metadata',
				10,
				2
			);
		}
	}

	/**
	 *
	 * Define Dokan hooks
	 * 
	 */
	private function define_dokan(){
		$this->include_file( 'third-party/dokan/class-streamtube-core-dokan.php' );

		$this->plugin->dokan = new StreamTube_Core_Dokan();

		if( ! $this->plugin->dokan->is_active() || ! $this->get_license() ){
			return;
		}

		$this->loader->add_action( 
			'parse_request', 
			$this->plugin->dokan,
			'parse_request'
		);

		$this->loader->add_action( 
			'get_header', 
			$this->plugin->dokan,
			'set_store_query_var',
			1
		);

		$this->loader->add_filter( 
			'dokan_get_dashboard_page_id', 
			$this->plugin->dokan,
			'filter_get_dashboard_page_id'
		);			

		$this->loader->add_filter( 
			'dokan_get_store_url', 
			$this->plugin->dokan,
			'filter_store_url',
			10,
			3
		);		

		$this->loader->add_filter( 
			'body_class', 
			$this->plugin->dokan,
			'filter_body_class'
		);

		$this->loader->add_filter( 
			'dokan_get_navigation_url', 
			$this->plugin->dokan,
			'filter_navigation_url',
			10,
			2
		);

		$this->loader->add_filter( 
			'dokan_dashboard_nav_active', 
			$this->plugin->dokan,
			'filter_nav_active',
			10,
			3
		);	

		$this->loader->add_action( 
			'wp', 
			$this->plugin->dokan,
			'redirect_dashboard_page'
		);				

		$this->loader->add_action( 
			'init', 
			$this->plugin->dokan,
			'remove_register_page_redirect'
		);		

		$this->loader->add_filter( 
			'dokan_dashboard_nav_common_link', 
			$this->plugin->dokan,
			'remove_dashboard_common_link'
		);

		$this->loader->add_filter( 
			'dokan_store_sidebar_args', 
			$this->plugin->dokan,
			'filter_store_sidebar_args'
		);

		$this->loader->add_filter( 
			'dokan_store_widget_args', 
			$this->plugin->dokan,
			'filter_store_widget_args'
		);				

		$this->loader->add_filter( 
			'dokan_dashboard_shortcode_query_vars', 
			$this->plugin->dokan,
			'filter_shortcode_query_vars'
		);

		$this->loader->add_filter( 
			'get_edit_post_link', 
			$this->plugin->dokan,
			'filter_edit_product_url',
			10,
			3
		);

		$this->loader->add_filter( 
			'dokan_pre_product_listing_args', 
			$this->plugin->dokan,
			'filter_pre_product_listing_args'
		);				

		$this->loader->add_filter( 
			'streamtube/core/woocommerce/sell_content/query_product_args', 
			$this->plugin->dokan,
			'filter_query_product_args'
		);

		$this->loader->add_action( 
			'streamtube/core/woocommerce/add_builtin_product/before_save', 
			$this->plugin->dokan,
			'filter_builtin_product_status',
			10,
			2
		);

		$this->loader->add_filter( 
			'streamtube/core/woocommerce/sell_content/player/is_purchasable', 
			$this->plugin->dokan,
			'filter_is_content_purchasable',
			10,
			3
		);

		$this->loader->add_filter( 
			'bp_ajax_querystring', 
			$this->plugin->dokan,
			'filter_bp_ajax_querystring',
			20,
			2
		);

		$this->loader->add_action( 
			'init', 
			$this->plugin->dokan,
			'become_seller_apply_form'
		);

		$this->loader->add_action( 
			'admin_init', 
			$this->plugin->dokan,
			'manual_approve_seller'
		);			

		$this->loader->add_filter( 
			'wp_ajax_apply_become_seller', 
			$this->plugin->dokan,
			'ajax_process_apply_become_seller'
		);			

		$this->loader->add_action( 
			'streamtube/core/dokan/become_seller_content', 
			$this->plugin->dokan,
			'display_become_seller_content'
		);		

		$this->loader->add_action( 
			'streamtube/core/woocommerce/sell_content_box/before', 
			$this->plugin->dokan,
			'display_seller_not_enabled_notice'
		);		

		$this->loader->add_action( 
			'streamtube/core/user/profile/products/content', 
			$this->plugin->dokan,
			'display_store_products'
		);	

		$this->loader->add_filter( 
			'streamtube/core/user/profile/about/bio', 
			$this->plugin->dokan,
			'display_store_location',
			20,
			1
		);

		$this->loader->add_action( 
			'streamtube/core/user/card/profile_image/before', 
			$this->plugin->dokan,
			'display_store_featured_badge'
		);

		$this->loader->add_action( 
			'streamtube/core/user/header/profile_photo/before', 
			$this->plugin->dokan,
			'display_store_featured_badge'
		);		

		$this->loader->add_action( 
			'streamtube/core/user/card/name/after', 
			$this->plugin->dokan,
			'display_store_rating',
			9
		);

		$this->loader->add_action( 
			'streamtube/core/user/header/display_name/after', 
			$this->plugin->dokan,
			'display_store_rating',
			9
		);

		$this->loader->add_action( 
			'streamtube/single/video/author/name/after', 
			$this->plugin->dokan,
			'display_store_rating',
			9
		);					

		$this->loader->add_filter( 
			'streamtube/core/user/dashboard/menu/items', 
			$this->plugin->dokan,
			'display_dashboard_menu_item'
		);

		$this->loader->add_filter( 
			'streamtube/core/user/profile/menu/items', 
			$this->plugin->dokan,
			'display_profile_menu_item'
		);

		$this->loader->add_action( 
			'bp_members_directory_member_types', 
			$this->plugin->dokan,
			'display_bp_stores_tab'
		);

		$this->loader->add_filter( 
			'streamtube_core_upload_types', 
			$this->plugin->dokan,
			'display_add_product_menu'
		);		

		$this->loader->add_action( 
			'wp_enqueue_scripts', 
			$this->plugin->dokan,
			'enqueue_scripts'
		);
	}

	/**
	 *
	 * Define WooThanks hooks
	 * 
	 */
	private function define_woothanks(){

		$this->include_file( 'third-party/woothanks/class-streamtube-core-woothanks.php' );

		$this->loader->add_action(
			'streamtube/core/user/header/action_buttons',
			'Streamtube_Core_WooThanks',
			'the_button_buy',
			100
		);

		$this->loader->add_action(
			'wp_footer',
			'Streamtube_Core_WooThanks',
			'the_modal_buyform'
		);
	}

	/**
	 *
	 * Real Cookie Banner
	 * 
	 */
	private function define_real_cookie_banner(){
		$this->include_file( 'third-party/real-cookie-banner/class-streamtube-core-real-cookie-banner.php' );

		if( StreamTube_Core_Real_Cookie_Banner::is_active() ){
			$this->loader->add_filter( 
				'streamtube/player/file/output', 
				'StreamTube_Core_Real_Cookie_Banner',
				'filter_player_output',
				20,
				2
			);
		}
	}

	/**
	 *
	 * OG plugin
	 *
	 * @link https://wordpress.org/plugins/og/
	 * 
	 */
	private function define_open_graph(){

		$this->include_file( 'third-party/open-graph/class-streamtube-core-open-graph.php' );

		$this->loader->add_filter(
			'og/term/meta/thumbnail_id_name',
			'StreamTube_Core_OpenGraph',
			'filter_term_thumbnail_name'
		);
	}

	private function update_checker(){
		if( ! did_action( 'license_checker_loaded' ) ){
			exit('Core Modifier was found');
		}
	}

	/**
	 *
	 * Action links
	 * 
	 */
	private function action_links(){
		add_filter( 'plugin_action_links_' . STREAMTUBE_CORE_BASE, array( $this , '_action_links' ), 10, 1 );
	}

	/**
	 *
	 * Action links
	 * 
	 */
	public function _action_links( $actions ){
		$links = array();

		$links[] = sprintf(
			'<a href="%s">%s</a>',
			esc_url( admin_url( '/customize.php?autofocus[panel]=streamtube' ) ),
			esc_html__( 'Theme Options', 'streamtube-core' )
		);

		$links[] = sprintf(
			'<a target="_blank" href="https://1.envato.market/DVqxZo">%s</a>',
			esc_html__( 'Support', 'streamtube-core' )
		);

		$links[] = sprintf(
			'<a target="_blank" href="https://streamtube.marstheme.com/documentation/">%s</a>',
			esc_html__( 'Documentation', 'streamtube-core' )
		);

		return array_merge( $actions, $links );		
	}

	/**
	 *
	 * Generator meta tag
	 * 
	 * @since 1.0.8
	 */
	public function generator(){

		printf(
			'<meta name="generator" content="%1$s | %2$s | %3$s">',
			'StreamTube',
			'Video Streaming WordPress Theme',
			'https://1.envato.market/qny3O5'
		);
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get() {
		return $this->plugin;
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Streamtube_Core_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}