<?php
if( ! defined('ABSPATH' ) ){
    exit;
}?>

<div class="row-buttons invisible d-lg-flex gap-2 mt-auto">

	<a class="btn shadow-none p-1" href="<?php echo esc_url( streamtube_core_get_edit_post_url( get_the_ID() ) );?>">
		<span class="btn__icon text-muted icon-edit"></span>
		<span class="btn__text small text-secondary">
			<?php esc_html_e( 'Edit', 'streamtube-core' ); ?>
		</span>
	</a>	

	<a class="btn shadow-none p-1" href="<?php echo esc_url( streamtube_core_get_edit_post_url( get_the_ID(), 'comments' ) );?>">
		<span class="btn__icon text-muted icon-chat"></span>
		<span class="btn__text small text-secondary">
			<?php esc_html_e( 'Comments', 'streamtube-core' ); ?>
		</span>
	</a>

	<?php if( streamtube_core()->get()->googlesitekit->analytics->can_view( get_the_ID() ) ): ?>
		<?php
		$url = add_query_arg( array(
			'start_date'	=>	'all',
			'end_date'		=>	'today'
		), streamtube_core_get_edit_post_url( get_the_ID(), 'analytics' ) )
		?>
		<a class="btn shadow-none p-1" href="<?php echo esc_url( $url );?>">
			<span class="btn__icon text-muted icon-chart-area"></span>
			<span class="btn__text small text-secondary">
				<?php esc_html_e( 'Analytics', 'streamtube-core' ); ?>
			</span>
		</a>
	<?php endif;?>

	<?php
	/**
	 * @since 1.0.8
	 */
	do_action( 'streamtube/core/post/table/row_button' );
	?>
</div>