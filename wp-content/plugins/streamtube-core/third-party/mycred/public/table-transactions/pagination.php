<div class="pagination pagination-sm">
	<div class="pagination-wrap d-flex gap-2 align-items-center">
		<?php 

		if( $logs->max_num_pages > 0 ):
			$pages = ceil( $logs->num_rows/$args['number'] );

			if( $logs->num_rows > 0 ):

				printf(
					'<span class="total-entries text-muted">%s</span>',
					sprintf(
						_n( '%s entry', '%s entries', $logs->num_rows, 'streamtube-core' ),
						number_format_i18n( $logs->num_rows )
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
</div>