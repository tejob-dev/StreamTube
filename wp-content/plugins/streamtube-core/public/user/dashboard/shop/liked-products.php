<?php
if( ! defined('ABSPATH' ) ){
    exit;
}

if( ! function_exists( 'WPPL' ) ){
	return;
}


$template = streamtube_core_get_user_template_settings();

extract( $template );                    

$widget_instance = array(
    'post_type'             =>  'product',
    'hide_empty_thumbnail'  =>  true,
    'posts_per_page'        =>  (int)$posts_per_column * (int)$rows_per_page,
    'orderby'               =>  isset( $_GET['orderby'] ) ? $_GET['orderby'] : 'date',
    'order'                 =>  isset( $_GET['order'] ) ? $_GET['order'] : 'DESC',
    'paged'                 =>  get_query_var( 'page' ),
    'grid'                  =>  'on',
    'col_xxl'               =>  (int)$posts_per_column,
    'col_xl'                =>  (int)$col_xl,
    'col_lg'                =>  (int)$col_lg,
    'col_md'                =>  (int)$col_md,
    'col_sm'                =>  (int)$col_sm,
    'col'                   =>  (int)$col,
    'current_logged_in_like'=>  true,
    'hide_if_empty'         =>  true,
    'grid'                  =>  'on',
    'pagination'            =>  $pagination
);

$widget_instance = apply_filters( 
    'streamtube/core/dashboard/woocommerce/liked_products/widget_instance',
    $widget_instance
);

ob_start();

the_widget( 'Streamtube_Core_Widget_Posts', $widget_instance , array() );

$output = trim( ob_get_clean() );

if( ! empty( $output ) ){
    echo $output;
}
else{
	?>
		<div class="woocommerce-Message woocommerce-Message--info woocommerce-info">
			<?php printf(
				'<a class="woocommerce-Button button" href="%s">%s</a>',
				apply_filters( 'streamtube/core/dashboard/woocommerce/liked_products/browse', get_post_type_archive_link( 'product' ) ),
				esc_html__( 'Browse products', 'streamtube-core' )
			);?>
			<?php esc_html_e( 'You have not liked any products yet', 'streamtube-core' )?>
		</div>
	<?php	
}