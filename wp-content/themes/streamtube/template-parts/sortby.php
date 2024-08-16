<?php
/**
 *
 * @link       https://1.envato.market/mgXE4y
 * @since      1.0.0
 *
 * @package    WordPress
 * @subpackage StreamTube
 * @author     phpface <nttoanbrvt@gmail.com>
 */
if( ! defined( 'ABSPATH' ) ){
	exit;
}

$options = streamtube_option_sortby();

$current = isset( $_GET['orderby'] ) ? sanitize_key( $_GET['orderby'] ) : 'date';

if( empty( $current ) || ! array_key_exists( $current , $options ) ){
	$current = 'date';
}
?>

<div class="sortby dropdown">
	<button class="btn shadow-none dropdown-toggle text-secondary" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
		<?php printf(
			esc_html__( 'Sort by %s', 'streamtube' ),
			'<strong>'.$options[ $current ].'</strong>'
		);?>
	</button>
	<ul class="dropdown-menu dropdown-menu-end animate slideIn">

		<?php foreach( $options as $key => $value ): ?>

			<?php printf(
				'<li><a class="dropdown-item small %s" href="%s">%s</a></li>',
				$current == $key ? 'active' : '',
				esc_url( add_query_arg( array( 
					'orderby'	=>	$key,
					'order'		=>	$key == 'title' ? 'ASC' : 'DESC'
				) ) ),
				esc_html( $value )
			);?>
		
		<?php endforeach;?>
	</ul>
</div>