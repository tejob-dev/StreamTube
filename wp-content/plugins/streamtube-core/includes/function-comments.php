<?php
if( ! defined('ABSPATH' ) ){
    exit;
}

/**
*
* Get comment sortby options
* 
* @return array
*
* @since  1.0.0
* 
*/
function streamtube_core_comment_sortby_options(){
	$items = array();

	$items['desc'] 	= array(
		'title'			=>	esc_html__( 'Newest', 'streamtube-core' ),
		'priority'		=>	10
	);

	$items['asc'] 	= array(
		'title'			=>	esc_html__( 'Oldest', 'streamtube-core' ),
		'priority'		=>	20
	);

	/**
	 * filter items
	 *
	 * @since 1.0.0
	 */
	$items = apply_filters( 'streamtube_core_comment_sortby_options', $items );

	uasort( $items, function( $item1,$item2 ){
		return $item1['priority'] <=> $item2['priority'];
	} );

	return $items;	
}

/**
 * 
 *
 * Get current comment sortby
 * 
 * @return string
 *
 * @since  1.0.0
 * 
 */
function streamtube_core_get_current_comment_sortby(){

	$options = streamtube_core_comment_sortby_options();

	$comment_order = get_option( 'comment_order' );

	if( isset( $_GET['comment_order'] ) && array_key_exists( $_GET['comment_order'] , $options ) ){
		$comment_order = $_GET['comment_order'];
	}

	return $comment_order;
}

/**
 *
 * The comments list
 * 
 * @param  $args
 * @return HTML
 *
 * @since  1.0.0
 * 
 */
function streamtube_core_list_comments( $args ){

	$args = wp_parse_args( $args, array(
		'hierarchical'	=>	true,
		'number'		=>	get_option( 'comments_per_page' ),
		'order'			=>	get_option( 'comment_order' ),
		'type'			=>	array( 'comment', 'pings' ),
		'paged'			=>	1,
		'status'		=>	'approve'
	) );

	$comments = get_comments( apply_filters( 'streamtube_core_list_comments', $args ) );

	$list_args = array(
		'avatar_size'		=>	96,
		'style'				=>	'ul',
		'short_ping'		=>	true
	);

	if( function_exists( 'streamtube_comment_list_args' ) ){
		$list_args = streamtube_comment_list_args();
	}	

	?>
	<?php echo wp_list_comments( $list_args, $comments );?>

	<?php
	$pages_count = get_comment_pages_count();

	if( $pages_count > 1  ):

		$data = array(
			'post_id'		=>	$args['post_id'],
			'paged'			=>	$args['paged'],
			'totalpages'	=>	$pages_count,
			'order'			=>	$args['order']
		);

		/**
		 *
		 * Filter the load type
		 * 
		 * @param string scroll by default or click
		 *
		 * @since  1.0.0
		 * 
		 */
		$load_type = apply_filters( 'streamtube/core/comments/load_type', 'scroll' );

		$button_classes = array( 'btn', 'load-comments', 'outline-none', 'shadow-none' );

		if( $load_type == 'scroll' ){
			$button_classes[] = 'jsappear load-on-scroll';
		}
		else{
			$button_classes[] = 'btn-outline-secondary load-on-click';	
		}

		?>
		<li class="p-3 load-more-comments-wrap">
			<div class="d-grid">
				<?php printf(
					'<button class="%s" data-params="%s" data-action="load_more_comments">',
					esc_attr( join( ' ', $button_classes ) ),
					esc_attr( json_encode( $data ) )
				);?>

					<?php if( $load_type == 'scroll' ): ?>

						<div class="spinner-border text-info" role="status">
							<span class="visually-hidden"><?php esc_html_e( 'Loading...', 'streamtube-core' ); ?></span>
						</div>

					<?php else:?>

						<?php esc_html_e( 'Load more comments', 'streamtube-core' ); ?>

					<?php endif;?>

				</button>
			</div>
		</li>
		<?php

	endif;
}
