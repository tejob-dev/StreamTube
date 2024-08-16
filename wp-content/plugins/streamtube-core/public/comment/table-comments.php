<?php

$args = wp_parse_args( $args, array(
	'post_id'	=>	0
) );

$address = false;

$comment_status = isset( $_GET['comment_status'] ) ? sanitize_key( $_GET['comment_status'] ) : 'all';

$query_args = array(
	'order'			=>	isset( $_GET['order'] ) && in_array( $_GET['order'], array( 'DESC', 'ASC' ) ) ? $_GET['order'] : 'DESC',
	'status'		=>	$comment_status,
	'number'		=>	get_option( 'posts_per_page' ),
	'post_id'		=>	$args['post_id'],
	'paged'			=>	isset( $_GET['page'] ) ? (int)$_GET['page'] : 1,
	'type'			=>	array( 'comment' )
);

if( ! streamtube_core_can_user_moderate_comments() ){
	$query_args['post_author'] = get_queried_object_id();
}

if( isset( $_GET['comment_status'] ) && $_GET['comment_status'] == 'reported' ){
	$query_args['status'] = 'all';
	$query_args['meta_query'][] = array(
		'key'		=>	'report_content',
		'compare'	=>	'EXISTS'
	);
}

if( isset( $_GET['submit'] ) && ! empty( $_GET['submit'] ) ){

	$get = wp_parse_args( $_GET, array(
		'submit'				=>	'',
		'search_query'			=>	'',
		'bulk_action'			=>	'',
		'bulk_action_top'		=>	'',
		'bulk_action_bottom'	=>	'',
		'entry_ids'				=>	array()
	) );

	switch ( $get['submit'] ) {
		case 'search':
			if( ! empty( $get['search_query'] ) ){
				$query_args['search'] = trim( sanitize_text_field( $get['search_query'] ) );
			}
		break;
		
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
						$_results = streamtube_core()->get()->comment->bulk_action( $entry_ids[$i], $get['bulk_action'] );

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

					if( count( $entry_ids  ) > 0 ):
						echo '<div class="alert alert-success p-2 px-3">';
							printf(
								_n( '%s comment', '%s comments', count( $entry_ids ) , 'streamtube-core' ),
								number_format_i18n( count( $entry_ids ) )
							);

							$_action = '';

							switch ( $get['bulk_action'] ) {
								case 'unapprove':
									$_action = esc_html__( 'unapproved', 'streamtube-core' );
								break;
								
								case 'approve':
									$_action = esc_html__( 'approved', 'streamtube-core' );
								break;

								case 'spam':
									$_action = esc_html__( 'marked as spam', 'streamtube-core' );
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

$comments = get_comments( $query_args );

$table_classes = array( 'table', 'table-hover', 'w-100', 'table-comments' );

if( $query_args['post_id'] ){
	$table_classes[] = 'table-comments__post';
}

/**
 *
 * Fires before page header
 * 
 */
do_action( 'streamtube/core/user/dashboard/page_header/before' );

?>
<div class="page-head mb-3 d-flex gap-3 align-items-center">
	<h1 class="page-title h4">
		<?php esc_html_e( 'Comments', 'streamtube-core' );?>
	</h1>
</div>

<?php
/**
 *
 * Fires after page header
 * 
 */
do_action( 'streamtube/core/user/dashboard/page_header/after' );

/**
 *
 * Fires before page content
 * 
 */
do_action( 'streamtube/core/user/dashboard/page_content/before' );
?>

<div class="page-content">
	<div class="widget manage-comments">

		<div class="table-responsive">

			<form method="get">

				<div class="tablenav top mb-4">

					<?php streamtube_core_load_template( 'comment/table/top-bar.php', false, array_merge( $query_args, array(
						'status'	=>	$comment_status
					) ) );?>

					<div class="justify-content-start align-items-center d-flex align-content-start flex-wrap gap-4 my-3">

						<?php streamtube_core_load_template( 'comment/table/bulk_action.php', false, array(
							'position'	=>	'top'
						) );?>

						<div class="pagination pagination-sm ms-md-auto">
							<?php streamtube_core_load_template( 'comment/table/pagination.php', false, $query_args );?>
						</div>

					</div>

				</div>

				<?php
				printf(
					'<table class="%s">',
					esc_attr( join( ' ', $table_classes ) )
				);

					/**
					 *
					 * Load the table header
					 *
					 * @since  1.0.0
					 * 
					 */
					streamtube_core_load_template( 'comment/table/row-header.php', false, $query_args );	

					if( $comments ):

						echo '<tbody>';

						foreach ( $comments as $comment ):

							streamtube_core_load_template( 'comment/table/row-loop.php', false, $comment );

						endforeach;

						echo '</tbody>';

					else:

						streamtube_core_load_template( 'comment/table/not-found.php', false, $query_args ); 

					endif;

					/**
					 *
					 * Load the table footer
					 *
					 * @since  1.0.0
					 * 
					 */
					streamtube_core_load_template( 'comment/table/row-header.php', false, $query_args );
				?>					

				</table>

				<div class="tablenav bottom">

					<div class="justify-content-start align-items-center d-flex align-content-start flex-wrap gap-4">

						<?php streamtube_core_load_template( 'comment/table/bulk_action.php', false, array(
							'position'	=>	'bottom'
						) );?>

						<div class="pagination pagination-sm ms-md-auto">
							<?php streamtube_core_load_template( 'comment/table/pagination.php', false, $query_args );?>
						</div>

					</div>

				</div>

			</form>

		</div><!--.table-responsive-->

		<?php streamtube_core_load_template( 'modal/edit-comment.php', false );?>
	</div>

	<?php if( $address ):?>
		<script type="text/javascript">
			window.history.pushState( null, null, "<?php echo $address;?>");
		</script>
	<?php endif; ?>
</div>

<?php

/**
 *
 * Fires after page content
 * 
 */
do_action( 'streamtube/core/user/dashboard/page_content/after' );