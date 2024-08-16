<?php
$statuses = array_merge( array(
	'all'	=>	esc_html__( 'All', 'streamtube-core' )
), get_comment_statuses() );

$statuses = array_merge( $statuses, array(
	'reported'	=>	esc_html__('Reported', 'streamtube-core' )
) );
?>
<div class="d-sm-flex align-items-center">
	<div class="badge-filters my-3">
		<?php foreach( $statuses as $status => $label ):?>
			<div class="entry-status badge-<?php echo $status; ?> mb-2">
				<?php 

				$url = add_query_arg( array(
					'comment_status'	=>	$status
				) );

				printf(
					'<a class="badge %s text-decoration-none text-white" href="%s">%s</a>',
					$args['status'] == $status ? 'bg-danger' : 'bg-secondary',
					esc_url( $url ),
					$label
				);?>
			</div>
		<?php endforeach;?>
	</div>
	<div class="search-form-wrap ms-auto">
		<?php streamtube_core_load_template( 'comment/table/search-form.php' );?>
	</div>
</div>

