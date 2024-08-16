<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * Define the schedule functionality.
 *
 * @since      1.0.0
 * @package    WP_Video_Encoder
 * @subpackage WP_Video_Encoder/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */
class WP_Video_Encoder_Schedule {

	/**
	 *
	 * Holds default events
	 * 
	 * @var array
	 *
	 * @since  1.0.0
	 * 
	 */
	protected $hooks = array(
		'wpve_check_queue_items'	=>	'5_minutes'
	);

	/**
	 *
	 * Add custom cron interval
	 * 
	 * @param array $schedules
	 *
	 * @since  1.0.0
	 * 
	 */
	public function add_cron_interval( $schedules ){
		$schedules['5_minutes'] = array(
			'interval' => 1*60*5,
			'display'  => esc_html__( '5 minutes', 'wp-video-encoder' )
		);
		return $schedules;
	}

	/**
	 *
	 * Add schedules
	 *
	 * @since 1.0.0
	 * 
	 */
	public function add_schedules(){

		foreach ( $this->hooks as $hook => $interval ) {

			if ( ! wp_next_scheduled( $hook ) ) {
				wp_schedule_event( time(), $interval, $hook );
			}

		}

	}

	/**
	 *
	 * Remove schedules
	 *
	 * @since 1.0.0
	 * 
	 */
	public function remove_schedules(){

		foreach ( $this->hooks as $hook => $interval ) {

			 $timestamp = wp_next_scheduled( $hook );

			 wp_unschedule_event( $timestamp, $hook );
		}
	}

}