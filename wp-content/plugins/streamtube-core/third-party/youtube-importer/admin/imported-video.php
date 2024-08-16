<?php
	$post_id = $args->post_id;
?>
<li class="d-flex">
	<a href="<?php the_permalink( $post_id )?>" target="_blank">
		<div class="yt-thumbnail">
			<?php echo get_the_post_thumbnail( $post_id, 'large' );?>
		</div>
	</a>

	<div class="yt-content">
        <?php printf(
        	'<h3 class="yt-title"><a target="_blank" href="%s">%s</a></h3>',
        	esc_url( get_permalink( $post_id ) ),
        	get_the_title( $post_id )
        );?>

        <p class="yt-date">
	        <?php printf(
	            esc_html__( '%s ago', 'streamtube-core' ),
	            '<time datetime="'. get_the_date( 'Y-m-d H:i:s', $post_id ) .'" class="date">'. human_time_diff( get_the_time( 'U', $post_id ), current_time('timestamp') ) .'</time>'
	        );?>
        </p>

    </div>	
</li>