<p class="submit">

	<?php printf(
		'<button type="button" class="button button-primary button-check-all">%s</button>',
		esc_html__( 'Check All', 'streamtube-core' )
	);?>

	<?php printf(
		'<button type="button" class="button button-primary button-uncheck-all">%s</button>',
		esc_html__( 'Uncheck All', 'streamtube-core' )
	);?>

	<?php printf(
		'<button type="button" class="button button-primary button-imported-checked-item">%s</button>',
		esc_html__( 'Import Checked Items', 'streamtube-core' )
	);?>	

	<?php printf(
		'<button type="button" class="button button-primary button-search-youtube">%s</button>',
		esc_html__( 'Search', 'streamtube-core' )
	);?>		
</p>

<div id="yt-search-results">
	<p>
		<?php printf(
			esc_html__( 'Hit the %s button to search Youtube content and import videos manually.', 'streamtube-core' ),
			'<strong>'. esc_html__( 'Search', 'streamtube-core' ) .'</strong>'
		);?>
	</p>

	<div id="yt-search-results-container">

	</div>
	<span class="spinner"></span>
</div>