<?php
if( ! defined('ABSPATH' ) ){
    exit;
}

global $streamtube;

$address = $is_membership = $is_live = $password_protected = $is_upcoming = false;

// Get all public-read statuses
$post_statuses 	= $streamtube->get()->post->get_post_statuses_for_read();

$post_status 	= isset( $_REQUEST['post_status'] ) ? wp_unslash( $_REQUEST['post_status'] ) : 'any';

if( is_array( $post_status ) ){
	$post_status = $post_status[0];
}

if( $post_status == 'membership' && function_exists( 'pmpro_activation' ) ){
	$is_membership = true;
}

if( $post_status == 'password_protected' ){
	$password_protected = true;
}

if( $post_status == 'live' ){
	$is_live = true;
}

if( $post_status == 'upcoming' ){
	$is_upcoming = true;
}

if( ! $post_status 
	|| $post_status == 'any' 
	|| ! array_key_exists( $post_status , $post_statuses ) 
	|| in_array( $post_status , array( 'live', 'password_protected', 'membership' )) ){
	
	$post_status = array_keys( $post_statuses );

	unset( $post_status[ array_search( 'any', $post_status ) ] );
	unset( $post_status[ array_search( 'trash', $post_status ) ] );
	unset( $post_status[ array_search( 'draft', $post_status ) ] );

	$post_status = array_values( $post_status );
}

/**
 *
 * Filter $post_status
 * 
 */
$post_status = apply_filters( 'streamtube/core/user/dashboard/post_table/pre_post_status', $post_status, $post_statuses );

$query_args = array(
	'author'			=>	get_queried_object_id(),
	'post_status'		=>	$post_status,
	'post_type'			=>	$args['post_type'],
	'order'				=>	get_query_var( 'order', 'DESC' ),
	'orderby'			=>	get_query_var( 'orderby', 'date' ),
	'paged'				=>	get_query_var( 'page', 1 ),
	'posts_per_page'	=>	isset( $_REQUEST['posts_per_page'] ) ? absint( $_REQUEST['posts_per_page'] ) : absint( get_option( 'posts_per_page' ) ),
	's'					=>	'',
	'meta_query'		=>	array()
);

if( ! $query_args['posts_per_page'] || absint( $query_args['posts_per_page'] ) > 999 ){
	$query_args['posts_per_page'] = absint( get_option( 'posts_per_page' ) );
}

if( $is_live ){
	$query_args['meta_query'][] = array(
		'key'		=>	'live_status',
		'compare'	=>	'IN',
		'value'		=>	array( 'connected', 'disconnected' )
	);
}

if( $is_upcoming ){
	$query_args['meta_query'][] = array(
		'key'		=>	'_upcoming_date',
		'compare'	=>	'>',
		'value'		=>	current_datetime()->format('Y-m-d H:i:s'),
		'type'		=>	'DATETIME'
	);	
}

if( $password_protected ){
	$query_args['has_password'] = true;
}

if( isset( $_REQUEST['search_query'] ) && is_string( $_REQUEST['search_query'] ) ){
	$query_args['s'] = sanitize_text_field( wp_unslash( $_REQUEST['search_query'] ) );
}

if( in_array( $query_args['orderby'], array( 'last_seen', 'post_view' ) ) ){

	$meta_key = '';

	if( $query_args['orderby'] == 'last_seen' ){
		$meta_key = '_last_seen';
	}

	if( $query_args['orderby'] == 'post_view' ){
		$type = get_option( 'sitekit_pageview_type', 'pageviews' );

		$types = array_keys( streamtube_core_get_post_view_types() );

		if( ! in_array( $type, $types ) ){
			$type = 'uniquepageviews';
		}

		$meta_key = '_' . $type;
	}

	$query_args['meta_query'][] = array(
		'key'		=>	$meta_key,
		'compare'	=>	'EXISTS'
	);

	if( $query_args['orderby'] == 'post_view' ){
		$query_args['orderby'] = 'meta_value_num';
	}
}

if( current_user_can( 'edit_others_posts' ) ){
	unset( $query_args['author'] );
}

if( isset( $_REQUEST['submit'] ) && is_string( $_REQUEST['submit'] ) && ! empty( $_REQUEST['submit'] ) ){

	$get = wp_parse_args( $_REQUEST, array(
		'submit'				=>	'',
		'search_query'			=>	'',
		'bulk_action'			=>	'',
		'bulk_action_top'		=>	'',
		'bulk_action_bottom'	=>	'',
		'entry_ids'				=>	array()
	) );

	switch ( $get['submit'] ) {	
		case 'bulk_action':

			$has_errors = false;

			if( $get['bulk_action_top'] ){
				$get['bulk_action'] = $get['bulk_action_top'];
			}

			if( $get['bulk_action_bottom'] ){
				$get['bulk_action'] = $get['bulk_action_bottom'];
			}			

			if( ! empty( $get['bulk_action'] ) ){

				$entry_ids = $get['entry_ids'];

				$results = array();

				if( is_array( $entry_ids ) && count( $entry_ids  ) > 0 ){
					for ( $i = 0; $i < count( $entry_ids ); $i++) {  
						$_results = streamtube_core()->get()->post->bulk_action( $entry_ids[$i], $get['bulk_action'] );

						if( ! is_wp_error( $_results ) ){
							$results[$entry_ids[$i]] = $_results;
						}else{
							$has_errors = $_results->get_error_messages();
						}
					}
				}

				if( $has_errors ):

					printf(
						'<div class="alert alert-warning p-2 px-3">%s</div>',
						join( '<br/>', $has_errors )
					);

				else:
					if( 0 < $entry_count = count( array_keys( $results )  ) ):
						echo '<div class="alert alert-success p-2 px-3">';
							printf(
								_n( '%s video', '%s videos', $entry_count, 'streamtube-core' ),
								number_format_i18n( $entry_count )
							);

							$_action = '';

							switch ( $get['bulk_action'] ) {
								
								case 'approve':
									$_action = esc_html__( 'approved', 'streamtube-core' );
								break;

								case 'reject':
									$_action = esc_html__( 'rejected', 'streamtube-core' );
								break;

								case 'pending':
									$_action = esc_html__( 'marked as pending', 'streamtube-core' );
								break;

								case 'restore':
									$_action = esc_html__( 'restored', 'streamtube-core' );
								break;							

								case 'trash':
									$_action = esc_html__( 'moved to trash', 'streamtube-core' );
								break;
							}

							if( $_action ){
								printf(
									'<span class="ms-1">%s</span>',
									$_action
								);
							}

						echo '</div>';
					endif;
				endif;
			}

			$address = remove_query_arg( array( 'bulk_action', 'entry_ids', 'submit' ), wp_get_referer() );
		break;
	}
}

/**
 * Filter query args
 */
$query_args = apply_filters( 'streamtube/core/user/dashboard/post_table/query_args', $query_args );

if( $is_membership ){
	add_filter( 'posts_join', function( $join, $query ){

		global $wpdb;

        $pmp_pages_table    = $wpdb->prefix . 'pmpro_memberships_pages';
        $pmp_levels_table   = $wpdb->prefix . 'pmpro_membership_levels';    		

        $join .= " INNER JOIN $pmp_pages_table AS pmp_pages ON pmp_pages.page_id = {$wpdb->prefix}posts.ID";
        $join .= " INNER JOIN $pmp_levels_table AS pmp_levels ON pmp_levels.id = pmp_pages.membership_id";

        return $join;
	}, 10, 2 );

	add_filter( 'posts_where', function( $where, $query ){

		$where .= " AND pmp_levels.allow_signups = 1";

		return $where;
	}, 10, 2 );

	add_filter( 'posts_distinct', function( $distinct, $query ){

		return 'DISTINCT';

	}, 10, 2 );
}

/**
 *
 * Fires before sending query
 * 
 */
do_action( 'streamtube/core/user/dashboard/post_table/before_query', $query_args );

$query_posts = new WP_Query( $query_args );

/**
 *
 * Fires after sending query
 * 
 */
do_action( 'streamtube/core/user/dashboard/post_table/after_query', $query_args );

$template_args = array(
	'query_args'	=>	$query_args,
	'query_posts'	=>	$query_posts
);

?>
<div class="widget manage-posts">
	<form method="get">

		<div class="tablenav top mb-4">

			<?php streamtube_core_load_template( 'post/table/top-bar.php', false, $template_args );?>

			<div class="justify-content-start align-items-center d-flex align-content-start flex-wrap gap-4">

				<?php streamtube_core_load_template( 'post/table/bulk_action.php', false, array(
					'position'	=>	'top'
				) );?>

				<div class="pagination pagination-sm ms-md-auto">
					<?php streamtube_core_load_template( 'post/table/pagination.php', false, array_merge(
						$template_args,
						array(
							'position'	=>	'top'
						)
					) );?>
				</div>

				<div class="per-page">
					<?php
					printf(
						'<input type="number" name="posts_per_page" value="%s" class="form-control" type="number" step="1" min="1" max="999" maxlength="3">',
						esc_attr( $query_args['posts_per_page'] )
					);
					?>
				</div>
			</div>

		</div>

		<?php if( $query_posts->found_posts && $query_args['s'] ): ?>
			<div class="alert alert-info px-3 py-2 rounded mb-4">
				<?php printf(
					esc_html__( '%s posts found.', 'streamtube-core' ),
					number_format_i18n( $query_posts->found_posts )
				);?>
			</div>
		<?php endif;?>

		<table class="table table-hover table-posts mb-4">

			<?php 
			/**
			 *
			 * Load the table header
			 *
			 * @since  1.0.0
			 * 
			 */
			streamtube_core_load_template( 'post/table/row-header.php', false, $template_args );
			?>

			<?php if( $query_posts->have_posts() ):?>

				<?php
				/**
				 *
				 * Fires before table body
				 *
				 * @param  WP_Query $query_posts
				 * @param  array $query_args
				 *
				 * @since  1.0.0
				 * 
				 */
				do_action( 'streamtube/core/user/dashboard/post_table/tbody/before', $query_posts, $query_args );
				?>			

				<tbody>

					<?php while( $query_posts->have_posts() ):?>

						<?php $query_posts->the_post(); ?>

						<?php streamtube_core_load_template( 'post/table/row-loop.php', false, $template_args ); ?>

					<?php endwhile;?>

				</tbody>

				<?php

				/**
				 *
				 * Fires after table body
				 *
				 * @param  WP_Query $query_posts
				 * @param  array $query_args
				 *
				 * @since  1.0.0
				 * 
				 */
				do_action( 'streamtube/core/user/dashboard/post_table/tbody/after', $query_posts, $query_args );
				?>

			<?php else:?>

				<?php 
				/**
				 * Load not found template if no posts found
				 *
				 * @since  1.0.0
				 * 
				 */
				streamtube_core_load_template( 'post/table/not-found.php', false, $template_args ); 
				?>

			<?php endif;?>

			<?php wp_reset_postdata(); ?>

			<?php 
			/**
			 *
			 * Load the table footer
			 *
			 * @since  1.0.0
			 * 
			 */
			streamtube_core_load_template( 'post/table/row-header.php', false, $template_args );
			?>			

		</table>

		<div class="tablenav bottom mb-4">

			<div class="justify-content-start align-items-center d-flex align-content-start flex-wrap gap-4">

				<?php streamtube_core_load_template( 'post/table/bulk_action.php', false, array(
					'position'	=>	'bottom'
				) );?>

				<div class="pagination pagination-sm ms-md-auto">
					<?php streamtube_core_load_template( 'post/table/pagination.php', false, array_merge(
						$template_args,
						array(
							'position'	=>	'bottom'
						)
					) );?>
				</div>

			</div>

		</div>

	</form>

	<?php
	if( current_user_can( 'edit_others_posts' ) ){
		streamtube_core_load_template( 'modal/approve-reject-message.php' );
	}

	streamtube_core_load_template( 'modal/delete-post.php' );
	?>
</div>

<?php if( $address ):?>
	<script type="text/javascript">
		window.history.pushState( null, null, "<?php echo $address;?>");
	</script>
<?php endif;?>