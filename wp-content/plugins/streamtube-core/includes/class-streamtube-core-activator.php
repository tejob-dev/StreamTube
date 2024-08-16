<?php

/**
 * Fired during plugin activation
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

class Streamtube_Core_Activator {
	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

        self::update_version();

        self::install_cron_hooks();

        self::flush_rewrite_rules();

        /**
         *
         * Fires on plugin activated
         * 
         */
        do_action( 'streamtube/core/activated' );
	}

    /**
     *
     * Update plugin version
     *
     * @since 1..0.9
     * 
     */
    private static function update_version(){
        update_option( 'streamtube_core_version', STREAMTUBE_CORE_VERSION );
    }

    /**
     *
     * flush_rewrite_rules()
     * 
     */
    private static function flush_rewrite_rules(){

        $post = new Streamtube_Core_Post();

        $post->cpt_video();

        $taxonomy = new Streamtube_Core_Taxonomy();

        $taxonomy->video_category();

        $taxonomy->video_tag();

        $advertising = new Streamtube_Core_Advertising();

        $advertising->ad_tag->post_type();

        $advertising->ad_schedule->post_type();

        $youtube_importer = new StreamTube_Core_Youtube_Importer_Post_Type();

        $youtube_importer->post_type();
        
        flush_rewrite_rules();
    }

    /**
     *
     * Add cron hooks
     *
     * @since 1.0.9
     * 
     */
    private static function install_cron_hooks(){
        $hooks = new Streamtube_Core_Cron();

        $hooks->add_hooks();
    }

}
