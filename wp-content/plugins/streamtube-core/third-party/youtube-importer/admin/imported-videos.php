<?php

global $post;

$imported_videos = streamtube_core()->get()->yt_importer->get_imported_videos( $post->ID );

if( $imported_videos ){
	?>
	<div id="yt-imported-videos">
		<ul class="yt-video-list"><?php
		for ( $i=0; $i < count( $imported_videos ); $i++) { 
			load_template( plugin_dir_path( __FILE__ ) . 'imported-video.php', false, $imported_videos[$i] );
		}
		?>
		</ul>
	</div>
	<?php

	printf(
		'<a class="button button-primary button-block d-block w-100 text-center" href="%s">%s</a>',
		esc_url( add_query_arg( array(
			'post_type'		=>	'video',
			'importer_id'	=>	$post->ID
		), admin_url( 'edit.php' ) ) ),
		esc_html__( 'View all', 'streamtube-core' )
	);
}