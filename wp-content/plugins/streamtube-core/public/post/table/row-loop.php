<?php
if( ! defined('ABSPATH' ) ){
    exit;
}

global $post, $streamtube;

?>

<tr <?php post_class( 'bg-white' ); ?> id="row-<?php the_ID(); ?>">

	<th scope="row" class="col-id">
		<?php printf(
			'<input class="form-check-input row-id-input mt-0" type="checkbox" name="entry_ids[]" value="%s">',
			esc_attr( get_the_ID() )
		);?>
	</th>	

	<td scope="row" class="col-title" data-title="<?php esc_attr_e( '#', 'streamtube-core' ); ?>">
		<div class="d-sm-flex gap-3">

			<a title="<?php echo esc_attr( wp_strip_all_tags(get_the_title()) ); ?>" href="<?php the_permalink() ?>">
	            <div class="post-thumbnail ratio ratio-16x9 rounded overflow-hidden bg-dark  mb-2 mb-sm-0">
                    <?php the_post_thumbnail( 'size-560-315', array(
                        'class' =>  'img-fluid'
                    ) );?>
	            </div>
        	</a>

            <div class="d-flex flex-column">

            	<?php
            	/**
            	 *
            	 * Fires before title
            	 *
            	 * @since  1.0.0
            	 * 
            	 */
            	do_action( 'streamtube/core/post/row_loop/title/before' );
            	?>

            	<?php the_title( 
            		'<h2 class="post-title"><a title="'. esc_attr( wp_strip_all_tags(get_the_title()) ) .'" class="text-body" href="'. esc_url( get_permalink() ) .'">',
            		'</a></h2>' 
            		); 
            	?>

            	<?php
            	/**
            	 *
            	 * Fires after title
            	 *
            	 * @since  1.0.0
            	 * 
            	 */
            	do_action( 'streamtube/core/post/row_loop/title/after' );
            	?>

				<?php
				/**
				 * Show the encode status
				 *
				 * @since 1.0.0
				 */
				if( function_exists( 'wp_video_encoder' ) ):
					load_template( WP_VIDEO_ENCODER_PATH . 'admin/partials/encode-status.php', false, array(
						'post_id'	=>	streamtube_core()->get()->post->get_source( $post->ID )
					) );
				endif;
				?>

            	<?php 
            	if( $post->post_status != 'trash' ):
            		streamtube_core_load_template( 'post/table/row-buttons.php', false );
            	endif; 
            	?>
           	</div>
    	</div> 	
	</td>

	<?php if( current_user_can( 'delete_others_posts' ) ):?>
		<td class="col-author" data-title="<?php esc_attr_e( 'Author', 'streamtube-core' ); ?>">
			<div class="comment-author d-flex align-items-center">
				<?php streamtube_core_get_user_avatar( array(
					'user_id'	=>	$post->post_author,
					'name'		=>	true
				) );?>
			</div>
		</td>
	<?php endif;?>

	<td class="col-visibility" data-title="<?php esc_attr_e( 'Visibility', 'streamtube-core' ); ?>">
		<?php 
		printf(
			'<span class="text-capitalize badge bg-secondary badge-%1$s">%2$s</span>',
			$post->post_status,
			$streamtube->get()->post->get_post_statuses_for_read( $post->post_status )
		);?>
	</td>

	<?php if( streamtube_core()->get()->googlesitekit->analytics->is_active() ): ?>
		<td class="col-comments-count" data-title="<?php esc_attr_e( 'Views', 'streamtube-core' ); ?>">
			<?php 
			echo streamtube_core_format_page_views(streamtube_core()->get()->post->get_post_views());
			?>
		</td>		
	<?php endif;?>

	<td class="col-comments-count" data-title="<?php esc_attr_e( 'Comments', 'streamtube-core' ); ?>">
		<?php get_template_part( 'template-parts/post-comment-box' ); ?>
	</td>

	<td class="col-date" data-title="<?php esc_attr_e( 'Date', 'streamtube-core' ); ?>">
		<?php echo get_the_date() ;?>
	</td>

	<td class="col-date last-seen" data-title="<?php esc_attr_e( 'Last Seen', 'streamtube-core' ); ?>">
		<?php 

		$last_seen = streamtube_core()->get()->post->get_last_seen( null, true );

		if( $last_seen > 0 ){
			printf(
				esc_html__( '%s ago', 'streamtube-core' ),
				human_time_diff( 
					$last_seen, 
					current_time( 'timestamp' )
				)						
			);
		}?>
	</td>	

	<td class="col-action" data-title="<?php esc_attr_e( 'Action', 'streamtube-core' ); ?>">
		<?php streamtube_core_load_template( 'post/table/controls.php', false ); ?>
	</td>

</tr>