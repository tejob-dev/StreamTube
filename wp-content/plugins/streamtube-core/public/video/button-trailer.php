<?php
/**
 *
 * The trailer button
 * 
 */
if( ! defined('ABSPATH' ) ){
    exit;
}
?>
<div class="button-group button-group-trailer">

	<?php if( ! isset( $_GET['view_trailer'] ) ): ?>

	<?php printf(
		'<a class="btn shadow-none px-1" title="%s" href="%s">',
		esc_html__( 'Watch Trailer' ),
		esc_url( add_query_arg( array( 'view_trailer' => '1', 'autoplay' => '1' ) ) )
	)?>
		<span class="btn__icon icon-video text-secondary"></span>
	<?php else:?>
		<?php printf(
			'<a class="btn shadow-none px-1" title="%s" href="%s">',
			esc_html__( 'Watch Video' ),
			esc_url( remove_query_arg( array( 'view_trailer', 'autoplay' ) ) )
		)?>

			<span class="btn__icon icon-play text-secondary"></span>
	<?php endif;?>
	</a>
</div>