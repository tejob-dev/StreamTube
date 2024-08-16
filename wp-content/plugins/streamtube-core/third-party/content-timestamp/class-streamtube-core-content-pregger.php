<?php
/**
 * Define the Content Preg functionality
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

class StreamTube_Core_Content_Pregger{

	/**
	 *
	 * Holds the pattern
	 * 
	 * @var string
	 */

	private $pattern 			= '/(\d+):(\d+)(?::(\d+))?\s([^,.\n\r]+)/';

	/**
	 *
	 * Remove all HTML tags 
	 * 
	 * @var boolean
	 */
	public $remove_html_tags 	= false;

	/**
	 *
	 * Class construct
	 * 
	 * @param string $indicator
	 */
	public function __construct(){
	}

	/**
	 * Remove all html tags
	 */
	public function remove_html_tags( $remove = true ){
		$this->remove_html_tags = $remove;
	}

	/**
	 *
	 * Preg Matches callback
	 * 
	 * @param  array $matches
	 * 
	 */
	public function preg_callback( $matches ){
		$hours = $minutes = $seconds = false;

		if( empty( $matches[3] ) ){
			$minutes 	= $matches[1];
			$seconds 	= $matches[2];
		}else{
			$hours 		= $matches[1];
			$minutes 	= $matches[2];
			$seconds 	= $matches[3];
		}

		$text = $matches[4];

		if( $this->remove_html_tags ){
			$text = wp_trim_words( wp_strip_all_tags( $text ), 20 );
		}

		$total_seconds 	= $hours * 3600 + $minutes * 60 + $seconds;

		$time = implode( ':' , array_filter( compact( 'hours', 'minutes', 'seconds' ) ));

		return compact( 'time', 'hours', 'minutes', 'seconds', 'total_seconds', 'text' );	
	}

	/**
	 *
	 * Extract the text
	 * 
	 * @param  string $text
	 * @return array
	 */
	public function extract( $text ){
		preg_match_all( $this->pattern, $text, $matches, PREG_SET_ORDER );

		$times = [];
		foreach ( $matches as $match ) {
			$times[] = $this->preg_callback( $match );
		}

		return $times;
	}

	/**
	 *
	 * ReplaceWith matches
	 * 
	 * @param  string $text
	 * @return replaced text
	 * 
	 */
	public function replace( $text ){
		return preg_replace_callback( $this->pattern, function ( $matches ) {
			$matches = $this->preg_callback( $matches );

			extract( $matches );

			return sprintf(
				'<a class="timestamp-tag timestamp-url" href="%s" data-total-seconds="%s">%s</a> %s',
				esc_url( add_query_arg(
					array(
						't'	=>	$total_seconds
					)
				) ),
				esc_attr( $total_seconds ),
				esc_attr( $time ),
				$text
			);

		}, $text );
	}
}