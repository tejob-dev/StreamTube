<?php
if( ! defined('ABSPATH' ) ){
    exit;
}

if( $args['query_posts']->found_posts == 0 ){
	return;
}
?>

<div class="pagination-wrap d-flex gap-2 align-items-center">
	<?php 

	printf(
		'<span class="total-posts text-muted">%s</span>',
		sprintf(
			_n( '%s post', '%s posts', $args['query_posts']->found_posts, 'streamtube-core' ),
			number_format_i18n( $args['query_posts']->found_posts )
		)
	);

	echo paginate_links( array(
		'format' 		=> '?page=%#%',
		'total'			=>	ceil( $args['query_posts']->found_posts/$args['query_args']['posts_per_page'] ),
		'current'		=>	$args['query_args']['paged'],
		'prev_next'		=> true,
		'prev_text'		=> '<span class="icon-left-open"></span>',
		'next_text'		=> '<span class="icon-right-open"></span>',
		'type'			=> 'list'
	) );
	?>
</div>