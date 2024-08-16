<?php
/**
 * Define the Content TimesTamp functionality
 *
 *
 * @link       https://themeforest.net/user/phpface
 * @since      2.2
 *
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 */

/**
 *
 * @since      2.2
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */

if( ! defined('ABSPATH' ) ){
    exit;
}

class StreamTube_Core_Content_TimesTamp{

	protected $Pregger;

	/**
	 *
	 * Class construct
	 * 
	 */
	public function __construct(){

		$this->load_dependencies();

		$this->Pregger = new StreamTube_Core_Content_Pregger();
	}

	/**
	 *
	 * Load dependencies
	 * 
	 */
	private function load_dependencies(){
		require_once trailingslashit( plugin_dir_path( __FILE__ ) ) . 'class-streamtube-core-content-pregger.php';
	}

	/**
	 *
	 * Filter given text
	 * 
	 */
	public function filter_content( $content ){

		if( is_singular( 'video' ) && is_main_query() ){
			$this->Pregger->remove_html_tags( false );
			$content = $this->Pregger->replace( $content );
		}

		return $content;
	}

	/**
	 *
	 * Filter player setup, add Chapters if found
	 * 
	 * @param  array $setup
	 * @param  string $source
	 * @return array $setup
	 */
	public function filter_player_setup( $setup, $source ){

		$mediaid = $setup['mediaid'];

		if( get_post_type( $mediaid ) == 'video' ){

			$this->Pregger->remove_html_tags( true );

			$times = $this->Pregger->extract( wp_strip_all_tags(get_post( $mediaid )->post_content ));

			if( $times ){

				$times = array_unique( $times, SORT_REGULAR );
				
				usort( $times, function($a, $b ) {
				    return $a['total_seconds'] - $b['total_seconds'];
				});

				/**
				 *
				 * Filter the times
				 * 
				 */
				$times = apply_filters( 'streamtube/core/player/timestamps', $times, $setup, $source );

				$setup['components']['playerChapter']['times'] = $times;
			}
		}

		return $setup;

	}
}