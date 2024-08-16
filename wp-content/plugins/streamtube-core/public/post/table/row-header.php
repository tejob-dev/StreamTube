<?php
if( ! defined('ABSPATH' ) ){
    exit;
}
?>
<thead>
	<tr>
		<th scope="col" class="col-id">
			<input class="form-check-input mt-0" type="checkbox" name="row_id" value="">
		</th>		
		<th scope="col" class="col-title">
			<?php printf(
				'<a class="text-body text-decoration-none" href="%s">%s</a>',
				esc_url( add_query_arg( array(
					'orderby'	=>	$args['query_args']['orderby'] == 'title' ? 'date' : 'title'
				) ) ),
				esc_html__( 'Title', 'streamtube-core' )
			);?>
		</th>

		<?php if( current_user_can( 'delete_others_posts' ) ):?>
			<th scope="col" class="col-author">
				<?php esc_html_e( 'Author', 'streamtube-core' ); ?>
			</th>
		<?php endif;?>

		<th scope="col" class="col-visibility">
			<?php esc_html_e( 'Visibility', 'streamtube-core' ); ?>
		</th>

		<?php if( streamtube_core()->get()->googlesitekit->analytics->is_active() ): ?>

			<th scope="col" class="col-view-count">
				<?php printf(
					'<a class="text-body text-decoration-none" href="%s">%s</a>',
					esc_url( add_query_arg( array(
						'orderby'	=>	'post_view',
						'order'	=>	$args['query_args']['order'] == 'ASC' ? 'DESC' : 'ASC'
					) ) ),
					esc_html__( 'Views', 'streamtube-core' )
				);?>					
			</th>

		<?php endif;?>

		<th scope="col" class="col-comments-count">
			<?php printf(
				'<a class="text-body text-decoration-none" href="%s">%s</a>',
				esc_url( add_query_arg( array(
					'orderby'	=>	'comment_count',
					'order'	=>	$args['query_args']['order'] == 'ASC' ? 'DESC' : 'ASC'
				) ) ),
				esc_html__( 'Comments', 'streamtube-core' )
			);?>					
		</th>

		<th scope="col" class="col-date">
			<?php printf(
				'<a class="text-body text-decoration-none" href="%s">%s</a>',
				esc_url( add_query_arg( array(
					'orderby'	=>	'date',
					'order'	=>	$args['query_args']['order'] == 'ASC' ? 'DESC' : 'ASC'
				) ) ),
				esc_html__( 'Date', 'streamtube-core' )
			);?>
		</th>

		<th scope="col" class="last-seen">
			<?php printf(
				'<a class="text-body text-decoration-none" href="%s">%s</a>',
				esc_url( add_query_arg( array(
					'orderby'	=>	'last_seen',
					'order'	=>	$args['query_args']['order'] == 'ASC' ? 'DESC' : 'ASC'
				) ) ),
				esc_html__( 'Last Seen', 'streamtube-core' )
			);?>
		</th>		

		<th scope="col" class="col-action">
			<?php esc_html_e( 'Action', 'streamtube-core' ); ?>
		</th>
		
	</tr>
</thead>