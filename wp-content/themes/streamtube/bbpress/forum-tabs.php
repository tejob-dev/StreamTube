<div class="forum-tabs mb-4 bg-white border-bottom">
	<div class="container">
		<ul class="nav nav-tabs border-bottom-0 w-100">
			<li class="nav-item">
				<a class="nav-link text-body main-forum" href="<?php echo esc_url( home_url(bbp_get_root_slug()) );?>">
					<?php esc_html_e( 'Forums', 'streamtube' );?>
				</a>
			</li>		
			<?php foreach ( array_keys( bbp_get_views() ) as $view ) : ?>
				<li class="nav-item">
					<?php printf(
						'<a class="nav-link text-body %s %s" href="%s">%s</a>',
						( bbp_get_view_id() == $view || ( bbp_get_view_id() == "" && $view == 'main-forum' ) ) ? 'active' : '',
						esc_attr( $view ),
						esc_url( bbp_get_view_url( $view ) ),
						esc_html( bbp_get_view_title( $view ) )
					)?>
				</li>
			<?php endforeach;?>
		</ul>
	</div>
</div>