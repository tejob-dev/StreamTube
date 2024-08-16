<?php
/**
 *
 * The User Library shortcode template file
 *
 * @link       https://1.envato.market/mgXE4y
 * @since      1.0.0
 *
 * @package    WordPress
 * @subpackage StreamTube
 * @author     phpface <nttoanbrvt@gmail.com>
 */
if( ! defined( 'ABSPATH' ) ){
    exit;
}

extract( $args );

$widget_args = array(
	'before_widget' => '<div class="widget widget-elementor %1$s">',
	'after_widget'  => '</div>',
	'before_title'  => '<div class="widget-title-wrap"><h2 class="widget-title d-flex align-items-center">',
	'after_title'   => '</h2></div>',	
);
?>
<div class="user-history position-relative">

	<?php if( $user_id ): ?>

		<div class="user-history-main py-4">

			<?php

			/**
			 *
			 * Fires before library
			 *
			 * @param array $args
			 * 
			 */
			do_action( 'streamtube/core/user/library/before', $args );

			$history_term_id = (int)get_user_meta( $user_id, 'collection_history', true );

			if( $history_term_id ):

				the_widget( 'Streamtube_Core_Widget_Posts', array(
					'title'					=>	esc_html__( 'History', 'streamtube-core' ),
					'icon'					=>	'icon-history',
					'posts_per_page'		=>	(int)$posts_per_column * (int)$rows_per_page,
					'orderby'				=>	'relevance',
					'order'					=>	'DESC',
			        'show_post_date'        =>  true,
			        'show_post_comment'     =>  false,
			        'hide_empty_thumbnail'  =>  true,
			        'col_xxl'               =>  (int)$posts_per_column,
			        'col_xl'                =>  (int)$col_xl,
			        'col_lg'                =>  (int)$col_lg,
			        'col_md'                =>  (int)$col_md,
			        'col_sm'                =>  (int)$col_sm,
			        'col'                   =>  (int)$col,
			        'tax_query'				=>	array(
			        	array(
			        		'taxonomy'		=>	Streamtube_Core_Collection::TAX_COLLECTION,
			        		'field'			=>	'term_id',
			        		'terms'			=>	$history_term_id
			        	)
			        ),
			        'more_link'				=>	esc_html__( 'View All', 'streamtube-core' ),
			        'more_link_url'			=>	get_term_link( $history_term_id, Streamtube_Core_Collection::TAX_COLLECTION ),
			        'pagination'			=>	$pagination,
			        'not_found_text'		=>	esc_html__( 'History is empty', 'streamtube-core' )
				), $widget_args );

			endif;
			?>

			<?php

			$watch_later_term_id = (int)get_user_meta( $user_id, 'collection_watch_later', true );

			if( $watch_later_term_id ):

				the_widget( 'Streamtube_Core_Widget_Posts', array(
					'title'					=>	esc_html__( 'Watch Later', 'streamtube-core' ),
					'icon'					=>	'icon-clock',
					'posts_per_page'		=>	(int)$posts_per_column * (int)$rows_per_page,
					'orderby'				=>	'relevance',
					'order'					=>	'DESC',
			        'show_post_date'        =>  true,
			        'show_post_comment'     =>  false,
			        'hide_empty_thumbnail'  =>  true,
			        'col_xxl'               =>  (int)$posts_per_column,
			        'col_xl'                =>  (int)$col_xl,
			        'col_lg'                =>  (int)$col_lg,
			        'col_md'                =>  (int)$col_md,
			        'col_sm'                =>  (int)$col_sm,
			        'col'                   =>  (int)$col,
			        'tax_query'				=>	array(
			        	array(
			        		'taxonomy'		=>	Streamtube_Core_Collection::TAX_COLLECTION,
			        		'field'			=>	'term_id',
			        		'terms'			=>	$watch_later_term_id
			        	)
			        ),
			        'more_link'				=>	esc_html__( 'View All', 'streamtube-core' ),
			        'more_link_url'			=>	get_term_link( $watch_later_term_id, Streamtube_Core_Collection::TAX_COLLECTION ),
			        'pagination'			=>	$pagination,
			        'not_found_text'		=>	esc_html__( 'You have not added any Videos yet', 'streamtube-core' )
				), $widget_args );

			endif;
			?>

			<?php
			the_widget( 'Streamtube_Core_Widget_Term_Grid', array(
				'title'						=>	esc_html__( 'Playlists', 'streamtube-core' ),
				'icon'						=>	'icon-indent-right',
				'taxonomy'					=>	array( Streamtube_Core_Collection::TAX_COLLECTION ),
				'public_only'				=>	true,
				'layout'					=>	'playlist',
				'user_id'					=>	$user_id,
				'number'					=>	(int)$posts_per_column * (int)$rows_per_page,
				'term_author'				=>	false,
		        'col_xxl'       			=>  (int)$posts_per_column,
		        'col_xl'        			=>  (int)$col_xl,
		        'col_lg'        			=>  (int)$col_lg,
		        'col_md'                	=>  (int)$col_md,
		        'col_sm'                	=>  (int)$col_sm,
		        'col'                   	=>  (int)$col,
		        'pagination'				=>	$pagination
			), $widget_args );

			/**
			 *
			 * Fires after library
			 *
			 * @param array $args
			 * 
			 */
			do_action( 'streamtube/core/user/library/after', $args );

			?>

		</div>

	<?php else:?>	

		<div class="liked-login login-wrap position-relative">
			<div class="top-50 start-50 translate-middle position-absolute text-center">
				<h5 class="text-muted h5 mb-4">
					<?php echo $not_login_message; ?>
				</h5>
				<?php printf(
					'<a class="btn btn-primary btn-login text-white px-3" href="%s">',
					esc_url( wp_login_url() )
				);?>
					<?php printf(
						'<span class="menu-icon %s me-0 me-sm-1"></span>',
						esc_attr( $btn_login_icon )
					);?>
					<span class="menu-text small menu-text small">
						<?php echo $btn_login_text; ?>
					</span>
				</a>
			</div>
		</div>
	<?php endif; ?>			

</div>