<?php
/**
 * DB
 *
 * @link       https://themeforest.net/user/phpface
 * @since      1.0.0
 *
 * @package    WP_User_Follow
 * @subpackage WP_User_Follow/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    WP_User_Follow
 * @subpackage WP_User_Follow/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */

class WP_User_Follow_Query {

	/**
	 *
	 * Hold the table name
	 * 
	 * @var string
	 *
	 * @since  1.0.0
	 * 
	 */
	protected $table			=	'user_follow';

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
			follower_id mediumint(9) NOT NULL,
			following_id mediumint(9) NOT NULL,
			feed BOOLEAN DEFAULT '1' NOT NULL,
			date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			date_gmt datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			PRIMARY KEY  ( id, follower_id, following_id )
		) {$this->get_charset_collate()};";

		maybe_create_table( $this->get_table(), $sql );
		
	}

	/**
	 *
	 * Add new row
	 *
	 * $args{
	 * @param int $follower_id
	 * @param int $following_id
	 * @param string $date
	 * @param string $date_gmt
	 * }
	 *
	 * @return  $wpdb->insert()
	 *
	 *
	 * @since  1.0.0
	 * 
	 */
	private function add( $args = array() ){

		global $wpdb;

		$args = wp_parse_args( $args, array(
			'follower_id'	=>	0,
			'following_id'	=>	0,
			'feed'			=>	'1',
			'date'			=>	'',
			'date_gmt'		=>	''
		) );

		extract( $args );

		return $wpdb->insert(
			$this->get_table(),
			compact( 'follower_id', 'following_id', 'feed', 'date', 'date_gmt' ),
			array( '%d', '%d', '%s', '%s', '%s' )
		);
	}

	/**
	 *
	 * Delete row by row ID
	 * 
	 * @param  int $id row ID
	 * @return $wpdb->delete()
	 *
	 * @since  1.0.0
	 * 
	 */
	private function delete_by_id( $id ){
		global $wpdb;

		return $wpdb->delete(
			$this->get_table(),
			compact( 'id' ),
			array( '%d' )
		);
	}


	/**
	 *
	 * Delete rows by follower id
	 * 
	 * @param  int $follower_id
	 * @return $wpdb->delete()
	 *
	 * @since  1.0.0
	 * 
	 */
	private function delete_by_follower_id( $follower_id ){
		global $wpdb;

		return $wpdb->delete(
			$this->get_table(),
			compact( 'follower_id' ),
			array( '%d' )
		);
	}


	/**
	 *
	 * Delete rows by following_id id
	 * 
	 * @param  int $following_id
	 * @return $wpdb->delete()
	 *
	 * @since  1.0.0
	 * 
	 */
	private function delete_by_following_id( $following_id ){
		global $wpdb;

		return $wpdb->delete(
			$this->get_table(),
			compact( 'following_id' ),
			array( '%d' )
		);
	}


	/**
	 *
	 * Get rows
	 * 
	 * @param  array $args 
	 * @return $wpdb->get_results() or get_var()
	 *
	 * @since  1.0.0
	 * 
	 */
	public function get( $args ){

		global $wpdb;

		$limit = '';

		$where = array( 'WHERE 1' );

		$args = wp_parse_args( $args, array(
			'id'			=>	0,
			'follower_id'	=>	0,
			'following_id'	=>	0,
			'feed'			=>	'',
			'date'			=>	'',
			'date_gmt'		=>	'',
			'limit'			=>	0,
			'count'			=>	false
		) );

		if( $args['id'] ){
			$where[] = $wpdb->prepare( 'id=%d', $args['id'] );
		}

		if( $args['follower_id'] ){
			$where[] = $wpdb->prepare( 'follower_id=%d', $args['follower_id'] );
		}

		if( $args['following_id'] ){
			$where[] = $wpdb->prepare( 'following_id=%d', $args['following_id'] );
		}

		if( $args['feed'] ){
			$where[] = $wpdb->prepare( 'feed=%s', $args['feed'] );
		}

		if( $args['date'] ){
			$where[] = $wpdb->prepare( 'date=%s', $args['date'] );
		}

		if( $args['date_gmt'] ){
			$where[] = $wpdb->prepare( 'date_gmt=%s', $args['date_gmt'] );
		}

		$where = join( ' AND ', $where );

		if( (int)$args['limit'] > 0 ){
			$limit = $wpdb->prepare( 'limit %d', $args['limit'] );
		}

		if( ! $args['count'] ){
			return $wpdb->get_results(
				"SELECT * FROM {$this->get_table()} {$where} {$limit}",
				OBJECT
			);
		}
		else{
			return $wpdb->get_var(
				"SELECT COUNT(id) FROM {$this->get_table()} {$where} {$limit}"
			);
		}
	}


	/**
	 *
	 * Check if given follower_id is following given following_id
	 *
	 * 
	 * @param  integer $follower_id
	 * @param  integer $following_id
	 * @return true if following, otherwise is false
	 *
	 *
	 * @since  1.0.0
	 * 
	 */
	public function is_following( $follower_id = 0, $following_id = 0 ){

		if( $follower_id == 0 || $following_id == 0 ){
			return false;
		}

		return $this->get( compact( 'follower_id', 'following_id' ) );

	}

	/**
	 *
	 * Get following count of given user id
	 * 
	 * @param  integer $user_id
	 * @return int
	 *
	 * @since  1.0.0
	 * 
	 */
	public function get_following_count( $user_id = 0 ){

		if( $user_id == 0 ){
			return $user_id;
		}

		$count = $this->get( array(
			'following_id'	=>	$user_id,
			'count'			=>	true
		) );

		$count = (int)$count;

		if( ! $count ){
			$count = 0;
		}

		update_user_meta( $user_id, 'following_count', $count );

		return $count;
	}

	/**
	 *
	 * Get follower count of given user id
	 * 
	 * @param  integer $user_id
	 * @return int
	 *
	 * @since  1.0.0
	 * 
	 */
	public function get_follower_count( $user_id = 0 ){

		if( $user_id == 0 ){
			return $user_id;
		}

		$count = $this->get( array(
			'follower_id'	=>	$user_id,
			'count'			=>	true
		) );

		$count = (int)$count;

		if( ! $count ){
			$count = 0;
		}

		update_user_meta( $user_id, 'follower_count', $count );

		return $count;
	}	

	/**
	 *
	 * Do update Follow, remove if exists, otheriwse add new row.
	 * 
	 * @param  integer $follower_id 
	 * @param  integer $following_id
	 * @return array|WP_Error
	 *
	 * @since  1.0.0
	 * 
	 */
	public function _follow( $follower_id = 0, $following_id = 0 ){

		$follower_id 		= (int)$follower_id;
		$following_id 		= (int)$following_id;

		$date 				= current_time( 'mysql' );
		$date_gmt 			= current_time( 'mysql', 1 );

		$errors 				= new WP_Error();

		if( $follower_id > 0 && $follower_id == $following_id ){
			$errors->add(
				'follower_equal_following',
				esc_html__( 'You can not follow yourself.', 'wp-user-follow' )
			);
		}

		if( ! get_userdata( $follower_id ) ){
			$errors->add(
				'follower_not_exist',
				esc_html__( 'Follower user does not exist.', 'wp-user-follow' )
			);
		}

		if( ! get_userdata( $following_id ) ){
			$errors->add(
				'following_not_exist',
				esc_html__( 'Following user does not exist.', 'wp-user-follow' )
			);
		}

		/**
		 *
		 * Filter the errors
		 * 
		 * @param WP_Error $errors
		 *
		 * @since  1.0.0
		 * 
		 */
		$errors = apply_filters( 'wp_user_follow/follow/errors', $errors, $follower_id, $following_id );		

		if( $errors->get_error_code() ){
			return $errors;
		}		

		$is_following = $this->is_following( $follower_id, $following_id );

		if( $is_following ){
			/**
			 *
			 * Fires after unfollowing
			 *
			 * @param int $follower_id
			 * @param int $following_id
			 *
			 * @since 1.0.3
			 * 
			 */
			do_action( 'wp_user_unfollowed', $follower_id, $following_id );

			return array(
				'did_action'	=>	'unfollowed',
				'id'			=>	$this->delete_by_id( $is_following[0]->id ),
				'message'		=>	sprintf(
					esc_html__( 'You have unfollowed %s', 'wp-user-follow' ),
					'<strong>'.get_userdata( $following_id )->display_name.'</strong>'
				)
			);
		}

		/**
		 *
		 * Fires after following
		 *
		 * @param int $follower_id
		 * @param int $following_id
		 *
		 * @since 1.0.3
		 * 
		 */
		do_action( 'wp_user_followed', $follower_id, $following_id );

		return array(
			'did_action'		=>	'followed',
			'id'				=>	$this->add( compact( 'follower_id', 'following_id', 'date', 'date_gmt' ) ),
			'message'		=>	sprintf(
				esc_html__( 'You have followed %s', 'wp-user-follow' ),
				'<strong>'.get_userdata( $following_id )->display_name.'</strong>'
			)			
		);
	}

	/**
	 * Delete follow on delete user action
	 *
	 *
	 * @param int      $id       ID of the deleted user.
	 * @param int|null $reassign ID of the user to reassign posts and links to.
	 *                           Default null, for no reassignment.
	 * @param WP_User  $user     WP_User object of the deleted user.
	 *
	 * @since  1.0.0
	 * 
	 */
	public function deleted_user( $id, $reassign, $user ){
		if( $reassign !== null ){
			global $wpdb;

			$wpdb->update(
				$this->get_table(),
				array(
					'follower_id'	=>	$reassign
				),
				array(
					'follower_id'	=>	$id
				),
				array( '%d' ),
				array( '%d' )
			);

			$wpdb->update(
				$this->get_table(),
				array(
					'following_id'	=>	$reassign
				),
				array(
					'following_id'	=>	$id
				),
				array( '%d' ),
				array( '%d' )
			);			

		}
		else{
			$this->delete_by_follower_id( $id );

			$this->delete_by_following_id( $id );
		}
	}	
}