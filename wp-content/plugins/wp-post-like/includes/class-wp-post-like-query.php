<?php

/**
 * DB
 *
 * @link       https://themeforest.net/user/phpface
 * @since      1.0.0
 *
 * @package    WP_Post_Like
 * @subpackage WP_Post_Like/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    WP_Post_Like
 * @subpackage WP_Post_Like/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */
if( ! defined( 'ABSPATH' ) ){
	exit;
}

class WP_Post_Like_Query {

	/**
	 *
	 * Holds the table name
	 * 
	 * @var string
	 *
	 * @since  1.0.0
	 * 
	 */
	const DB_TABLE			=	'post_like';

	/**
	 *
	 * Holds the data version
	 * 
	 * @var string
	 *
	 * @since  1.0.0
	 * 
	 */
	const DB_VERSION		=	'1.1';

	/**
	 *
	 * Holds the data version name
	 * 
	 * @var string
	 *
	 * @since  1.0.0
	 * 
	 */
	const DB_VERSION_NAME	=	'wp_post_like_db_version';

	/**
	 *
	 * Get the table name include WP prefix.
	 * 
	 * @return string
	 *
	 * @since  1.0.0
	 * 
	 */
	public static function get_table(){
		global $wpdb;

		return $wpdb->prefix . self::DB_TABLE;
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
	public static function get_charset_collate(){
		global $wpdb;
		return $wpdb->get_charset_collate();
	}

	/**
	 *
	 * 	Update db version
	 * 
	 * @return update_option()
	 *
	 * @since 1.2
	 * 
	 */
	public static function update_db_version(){
		return update_option( self::DB_VERSION_NAME, self::DB_VERSION );
	}

	/**
	 *
	 * 	Get db version
	 * 
	 * @return update_option()
	 *
	 * @since 1.2
	 * 
	 */
	public static function get_db_version(){
		return get_option( self::DB_VERSION_NAME );
	}

	/**
	 *
	 * 	delete db version
	 * 
	 * @return update_option()
	 *
	 * @since 1.2
	 * 
	 */
	public static function delete_db_version(){
		return delete_option( self::DB_VERSION_NAME );
	}

	/**
	 *
	 * Create table
	 *
	 * @return  maybe_create_table();
	 * 
	 * @since 1.0.0
	 */
	public static function install_db(){

		$current_db_version = self::get_db_version();

        if( ! $current_db_version ){
            // first time activate the plugin, install latest db version
            
            return self::_install_db();
        }

        if( version_compare( $current_db_version, self::DB_VERSION, '<' ) ){
            return self::_upgrade_db();
        }
	}

	/**
	 *
	 * Install latest db
	 * 
	 * @since 1.2
	 */
	private static function _install_db(){
		if( ! function_exists( 'maybe_create_table' ) ){
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		}

		$table = self::get_table();
		$charset = self::get_charset_collate();

		$sql = "CREATE TABLE {$table}(
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			user_id mediumint(9) NOT NULL,
			post_id mediumint(9) NOT NULL,
			action varchar(255) NOT NULL,
			date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			date_gmt datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			PRIMARY KEY  ( id, user_id, post_id )
		) {$charset};";

		$results = maybe_create_table( $table, $sql );

		if( $results ){
			return self::update_db_version();
		}
	}

	/**
	 *
	 * Upgrade db from the old version
	 * 
	 * @since 1.2
	 */
	public static function _upgrade_db(){

		global $wpdb;

		$table = self::get_table();

        $results = $wpdb->query( "ALTER TABLE {$table} ADD action varchar(255) NOT NULL DEFAULT 'like'" );

        if( $results ){
            return self::update_db_version();
        }
	}

	/**
	 *
	 * Update all post like and dislike count
	 */
	public function _update_posts_count(){
		global $wpdb;
		$table = self::get_table();

		$results = $wpdb->get_results( "SELECT post_id FROM $table GROUP BY post_id" );

		if( $results ){
			$post_ids = wp_list_pluck( $results, 'post_id' );

			for ( $i=0;  $i < count( $post_ids );  $i++) { 
				$this->update_post_count( $post_ids[$i] );
			}
		}
	}

	/**
	 *
	 * Insert item
	 * 
	 */
	public function insert( $args ){

		global $wpdb;

		$args = wp_parse_args( $args, array(
			'user_id'			=>	0,
			'post_id'			=>	0,
			'action'			=>	'like',
			'date'				=>	current_time( 'mysql' ),
			'date_gmt'			=>	get_gmt_from_date( current_time( 'mysql', 1 ) )
		) );

		extract( $args );

		$results = $wpdb->insert(
			self::get_table(),
			compact( 
				'user_id', 
				'post_id', 
				'action',
				'date', 
				'date_gmt'
			),
			array( '%d', '%d', '%s', '%s', '%s' )
		);

		if( $results ){
			return $this->update_post_count( $post_id );
		}

		return $results;
	}

	public function update( $args = array(0) ){

		$args = wp_parse_args( $args, array(
			'user_id'			=>	0,
			'post_id'			=>	0,
			'action'			=>	'like',
			'date'				=>	current_time( 'mysql' ),
			'date_gmt'			=>	get_gmt_from_date( current_time( 'mysql', 1 ) )
		) );

		extract( $args );

		global $wpdb;

		$results = $wpdb->update(
			self::get_table(),
			compact( 'action', 'date', 'date_gmt' ),
			compact( 'user_id', 'post_id' ),
			array( '%s', '%s', '%s' ),
			array( '%d', '%d' )
		);

		if( $results ){
			return $this->update_post_count( $post_id );
		}

		return $results;
	}

	public function delete( $user_id, $post_id ){
		global $wpdb;

		$results = $wpdb->delete(
			self::get_table(),
			compact( 'user_id', 'post_id' ),
			array( '%d', '%d' )
		);

		if( $results ){
			return $this->update_post_count( $post_id );
		}

		return $results;
	}

	public function delete_by_id( $id ){
		global $wpdb;

		return $wpdb->delete(
			self::get_table(),
			compact( 'id' ),
			array( '%d' )
		);
	}

	public function delete_by_user( $user_id ){
		global $wpdb;

		return $wpdb->delete(
			self::get_table(),
			compact( 'user_id' ),
			array( '%d' )
		);
	}

	public function delete_by_post_id( $post_id ){
		global $wpdb;

		return $wpdb->delete(
			self::get_table(),
			compact( 'post_id' ),
			array( '%d' )
		);
	}

	public function get( $args ){

		global $wpdb;

		$table = self::get_table();

		$limit = '';

		$where = array( 'WHERE 1' );

		$args = wp_parse_args( $args, array(
			'id'				=>	0,
			'user_id'			=>	0,
			'post_id'			=>	0,
			'action'			=>	'',
			'date'				=>	'',
			'date_gmt'			=>	'',
			'limit'				=>	0,
			'count'				=>	false
		) );

		if( $args['id'] ){
			$where[] = $wpdb->prepare( 'id=%d', $args['id'] );
		}		

		if( $args['user_id'] ){
			$where[] = $wpdb->prepare( 'user_id=%d', $args['user_id'] );
		}

		if( $args['post_id'] ){
			$where[] = $wpdb->prepare( 'post_id=%d', $args['post_id'] );
		}

		if( $args['action'] ){
			$where[] = $wpdb->prepare( 'action=%s', $args['action'] );
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
				"SELECT * FROM {$table} {$where} {$limit}",
				OBJECT
			);
		}
		else{
			return $wpdb->get_var(
				"SELECT COUNT(id) FROM {$table} {$where} {$limit}"
			);
		}
	}

	/**
	 *
	 * Get count
	 * 
	 * @param  int $post_id
	 * @return int
	 *
	 * @since 1.0.0
	 * 
	 */
	public function get_count( $post_id, $action = 'like' ){

		if( false !== $count = wp_cache_get( "{$action}_count_{$post_id}" ) ){
			return $count;
		}

		$count = true;

		$_count = $this->get( compact( 'post_id', 'action', 'count' ) );

		wp_cache_set( "{$action}_count_{$post_id}", $_count );

		return $_count;
	}

	/**
	 *
	 * @since  1.0.0
	 * 
	 */
	public function is_liked( $post_id = 0, $user_id = 0 ){

		if( ! $post_id || ! $user_id ){
			return false;
		}

		$query = $this->get( compact( 'user_id', 'post_id' ) );

		if( ! $query ){
			return false;
		}

		return $query[0]->action == 'like' ? true : false;
	}

	/**
	 *
	 * @since  1.0.0
	 * 
	 */
	public function is_disliked( $post_id = 0, $user_id = 0 ){

		if( ! $post_id || ! $user_id ){
			return false;
		}

		$query = $this->get( compact( 'user_id', 'post_id' ) );

		if( ! $query ){
			return false;
		}		

		return $query[0]->action == 'dislike' ? true : false;
	}

	/**
	 * @since 1.2
	 */
	public function has_reacted( $post_id = 0, $user_id = 0 ){
		if( ! $post_id || ! $user_id ){
			return false;
		}

		return $this->get( compact( 'user_id', 'post_id' ) );
	}

	/**
	 * Update post count
	 */
	public function update_post_count( $post_id ){

		$like 				= $this->get_count( $post_id, 'like' );
		$like_formatted 	= number_format_i18n( $like );
		$dislike 			= $this->get_count( $post_id, 'dislike' );
		$dislike_formatted	= number_format_i18n( $dislike );

		update_post_meta( $post_id, '_like_count', $like );
		update_post_meta( $post_id, '_dislike_count', $dislike );

		return compact( 'like', 'dislike', 'like_formatted', 'dislike_formatted' );
	}

	public function get_progress( $post_id ){
		$like 		= (int)get_post_meta( $post_id, '_like_count', true );
		$dislike 	= (int)get_post_meta( $post_id, '_dislike_count', true );

		if( $dislike == 0 ){
			return 100;
		}

		return ceil( $like*100/( $like + $dislike ) );
	}

	/**
	 *
	 * Get posts
	 * 
	 * @param  array $args
	 * @return $wpdb->get_results()
	 *
	 * @since 1.0.0
	 * 
	 */
	public function get_posts( $args ){
		global $wpdb;

		$args = wp_parse_args( $args, array(
        	'user_id'		=>	get_current_user_id(),
            'post_type'     =>  'video',
            'post_status'   =>  'publish',
            'action'		=>	'like'
		) );

		extract( $args );

        $sql = "SELECT p.ID";

        $sql .= " FROM {$wpdb->prefix}post_like AS pl";
        $sql .= " INNER JOIN";
        $sql .= " {$wpdb->prefix}posts AS p ON pl.post_id = p.ID";
        $sql .= $wpdb->prepare( 
        	" WHERE p.post_status = %s AND p.post_type = %s AND pl.user_id=%d AND pl.action=%s", 
        	$post_status, 
        	$post_type,
        	$user_id,
        	$action
        );
        $sql .= " ORDER BY pl.id DESC";

        return $wpdb->get_results( $sql );
	}

	/**
	 *
	 * Query sortby Likes
	 * 
	 */
	public function default_query_sortby_likes( $query ){

		if( isset( $_GET['orderby'] ) && $_GET['orderby'] == 'post_like' && $query->is_main_query() ){

			$meta_query = $query->get( 'meta_query' );

			if( ! $meta_query ){
				$meta_query = array();
			}

			$meta_query['relation'] = 'AND';

			$meta_query[] = array(
				'order_by_like'	=>	array(
					'key'		=>	'_like_count',
					'value'		=>	0,
					'compare'	=>	'>'
				)
			);

			$query->set( 'meta_query', $meta_query );

			$query->set( 'orderby', array(
				'order_by_like'	=>	'DESC'
			) );		
		}

		return $query;
	}

	public function delete_posts( $postid, $post ){
		return $this->delete_by_post_id( $postid );
	}

    public function delete_users( $user_id, $reassign = null, $user = null ){
		return $this->delete_by_user( $user_id );
    }
}