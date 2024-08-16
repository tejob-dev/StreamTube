<?php

global $post;

$importer = streamtube_core()->get()->yt_importer;

$settings = $importer->admin->get_settings( $post->ID );
?>
<p><?php printf(
	esc_html__( 'Try to add a %s videos every task', 'streamtube-core' ),
	sprintf(
		'<input type="number" name="yt_importer[update_number]" class="regular-text video-number" value="%s">',
		$settings['update_number']
	)
);?></p>

<p>
<label for="cron_tag_url"><?php esc_html_e( 'CronTab URL', 'streamtube-core' );?></label>
<?php printf(
	'<input onclick="javascript:this.select()" readonly type="text" id="cron_tag_url" value="%s" class="regular-text w-100">',
	esc_attr( add_query_arg( array(
		'key'	=>	$settings['cron_tag_key']
	), get_permalink( $post->ID ) ) )
);?>
<?php printf(
	'<input type="hidden" name="yt_importer[cron_tag_key]" id="cron_tag_key" value="%s" class="regular-text">',
	esc_attr( $settings['cron_tag_key'] )
);?>
</p>				
<p class="description">
	<?php esc_html_e( 'Open this URL to import content automatically.', 'streamtube-core' );?>
</p>