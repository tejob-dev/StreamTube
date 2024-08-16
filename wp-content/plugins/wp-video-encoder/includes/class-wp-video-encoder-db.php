<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * Define the custom table functionality.
 *
 * @since      1.0.0
 * @package    WP_Video_Encoder
 * @subpackage WP_Video_Encoder/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */
class WP_Video_Encoder_DB {

	/**
	 *
	 * Hold the table name
	 * 
	 * @var string
	 *
	 * @since  1.0.0
	 * 
	 */
	protected $table			=	'video_encoder';

	/**
	 *
	 * Hold the data version
	 * 
	 * @var string
	 *
	 * @since  1.0.0
	 * 
	 */
	protected $_db_version		=	'1.0.0';

	/**
	 *
	 * Get the table name include WP prefix.
	 * 
	 * @return string
	 *
	 * @since  1.0.0
	 * 
	 */
	public function get_table( $name = '' ){
		global $wpdb;

		if( empty( $name ) ){
			return $wpdb->prefix . $this->table;
		}

		return $wpdb->prefix . $this->table . '_' . $name;
	}

	/**
	 *
	 * Get charset collate
	 * 
	 * @return string
	 *
	 * @since  1.0.0
	 * 
	 */
	protected function get_charset_collate(){
		global $wpdb;
		return $wpdb->get_charset_collate();
	}

	/**
	 *
	 * Create reaction table
	 *
	 * @return  maybe_create_table();
	 * 
	 * @since 1.0.0
	 */
	public function install_db(){

		if( ! function_exists( 'maybe_create_table' ) ){
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		}

		$sql = "CREATE TABLE {$this->get_table()}(
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			attachment_id mediumint(9) NOT NULL,
			pid mediumint(9) DEFAULT 0,
			format tinytext NOT NULL,
			status tinytext NOT NULL,
			date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			date_gmt datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			date_modified datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			date_modified_gmt datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			PRIMARY KEY  ( id, attachment_id, pid )
		) {$this->get_charset_collate()};";

		return maybe_create_table( $this->get_table(), $sql );
	}

	protected function _insert( $args ){

		global $wpdb;

		$args = wp_parse_args( $args, array(
			'attachment_id'		=>	0,
			'pid'				=>	0,
			'format'			=>	'',
			'status'			=>	'waiting',
			'date'				=>	current_time( 'mysql' ),
			'date_gmt'			=>	current_time( 'mysql', 1 ),
			'date_modified'		=>	current_time( 'mysql' ),
			'date_modified_gmt'	=>	current_time( 'mysql', 1 )			
		) );

		extract( $args );

		return $wpdb->insert(
			$this->get_table(),
			compact( 
				'attachment_id', 
				'pid', 
				'format',
				'status',
				'date', 
				'date_gmt', 
				'date_modified',
				'date_modified_gmt'
			),
			array( '%d', '%d', '%s', '%s', '%s', '%s', '%s', '%s' )
		);
	}

	protected function _update( $args ){
		$args = wp_parse_args( $args, array(
			'attachment_id'		=>	0,
			'pid'				=>	0,
			'format'			=>	'',
			'status'			=>	'',
			'date_modified'		=>	current_time( 'mysql' ),
			'date_modified_gmt'	=>	current_time( 'mysql', 1 )			
		) );

		extract( $args );

		global $wpdb;

		return $wpdb->update(
			$this->get_table(),
			compact( 'pid', 'format', 'status', 'date_modified', 'date_modified_gmt' ),
			compact( 'attachment_id' ),
			array( '%d', '%s', '%s', '%s', '%s' ),
			array( '%d' )
		);		
	}

	protected function _delete( $attachment_id = 0 ){
		global $wpdb;

		return $wpdb->delete(
			$this->get_table(),
			compact( 'attachment_id' ),
			array( '%d' )
		);		
	}
}