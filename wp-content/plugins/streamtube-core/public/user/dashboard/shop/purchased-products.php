<?php
if( ! defined('ABSPATH' ) ){
    exit;
}

streamtube_core_the_search_form();

$search_query 	= isset( $_REQUEST['search_query'] ) ? wp_unslash( $_REQUEST['search_query'] ) : '';

$products_ids 	= streamtube_core_wc_get_purchased_products();

if( $products_ids ){

    /**
     *
     * Filter shortcode string
     * 
     */
    $shortcode = apply_filters( 
        'streamtube/core/woocommerce/purchased_products', 
        sprintf( 
        	'[products orderby="date" paginate="true" ids="%s" post_status="publish,unlist" search="%s"]', 
        	join(',', $products_ids ),
        	$search_query
        ), 
        $products_ids 
    );

    $output = do_shortcode( $shortcode );

    if( $output == '<div class="woocommerce columns-4 "></div>' && $search_query ){
		?>
			<div class="woocommerce-Message woocommerce-Message--info woocommerce-info">
				<p class="text-muted">
					<?php esc_html_e( 'Nothing matched your search terms.', 'streamtube-core' );?>
				</p>
			</div>
		<?php
    }else{
    	echo $output;
    }

}else{
	?>
		<div class="woocommerce-Message woocommerce-Message--info woocommerce-info">
			<?php printf(
				'<a class="woocommerce-Button btn btn-danger float-end" href="%s">%s</a>',
				apply_filters( 'streamtube/core/dashboard/woocommerce/purchased_products/browse', get_post_type_archive_link( 'product' ) ),
				esc_html__( 'Browse products', 'streamtube-core' )
			);?>
			<?php esc_html_e( 'You have not purchased any products yet', 'streamtube-core' )?>
		</div>

        <div class="clearfix"></div>
	<?php	
}