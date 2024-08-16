<div class="pagination-wrap d-flex gap-2 align-items-center">
	<?php 
	$total_comments = get_comments( array_merge( $args, array(
		'count'		=>	true,
		'number'	=>	''
	) ) );

	if( $total_comments > 0 ):
		$pages = ceil( $total_comments/$args['number'] );

		if( $total_comments > 0 ):

			printf(
				'<span class="total-comments text-muted">%s</span>',
				sprintf(
					_n( '%s comment', '%s comments', $total_comments, 'streamtube-core' ),
					number_format_i18n( $total_comments )
				)
			);

		endif;

		echo paginate_links( array(
			'format' 		=> '?page=%#%',
			'total'			=>	$pages,
			'current'		=>	$args['paged'],
			'prev_next'		=> true,
			'prev_text'		=> '<span class="icon-left-open"></span>',
			'next_text'		=> '<span class="icon-right-open"></span>',
			'type'			=> 'list'
		) );
	endif;
	?>
</div>