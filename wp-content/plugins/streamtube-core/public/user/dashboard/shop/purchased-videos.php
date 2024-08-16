<?php
if( ! defined('ABSPATH' ) ){
    exit;
}

streamtube_core_the_search_form();

$search_query 	= isset( $_REQUEST['search_query'] ) ? wp_unslash( $_REQUEST['search_query'] ) : '';

$videos 		= streamtube_core_wc_get_purchased_videos( get_current_user_id(), $search_query );

if( $videos ){

	$widget_instance = apply_filters( 'streamtube/core/dashboard/woocommerce/purchased_videos/widget_instance', array(
		'post_type'				=>	'video',
		'post_status'			=>	array( 'publish', 'unlist' ),		
		'post__in'				=>	array_unique( $videos ),
		'layout'				=>	'list_lg',
        'col_xxl'               =>  1,
        'col_xl'                =>  1,
        'col_lg'                =>  1,
        'col_md'                =>  1,
        'col_sm'                =>  1,
        'col'                   =>  1,
        'show_post_date'		=>	false,
        'post_excerpt_length'	=>	50,
        'posts_per_page'		=>	get_option( 'posts_per_page' ),
        'pagination'			=>	'click',
	) );

	the_widget( 'Streamtube_Core_Widget_Posts', $widget_instance, array() );
}else{

	?>
		<div class="woocommerce-Message woocommerce-Message--info woocommerce-info">

			<?php if( ! $search_query ): ?>

				<?php printf(
					'<a class="woocommerce-Button btn btn-danger float-end" href="%s">%s</a>',
					apply_filters( 'streamtube/core/dashboard/woocommerce/purchased_videos/browse', get_post_type_archive_link( 'video' ) ),
					esc_html__( 'Browse videos', 'streamtube-core' )
				);?>
				<?php esc_html_e( 'You have not purchased any videos yet', 'streamtube-core' )?>

			<?php else:?>
				<p class="text-muted">
					<?php esc_html_e( 'Nothing matched your search terms.', 'streamtube-core' );?>
				</p>
			<?php endif;?>

		</div>
		<div class="clearfix"></div>
	<?php
}
