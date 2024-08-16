<?php
/**
 * The Alert template file
 *
 *
 * @link       https://1.envato.market/mgXE4y
 * @since      1.0.0
 *
 * @package    Wp_Cloudflare_Stream
 * @subpackage Wp_Cloudflare_Stream/admin/settings
 */

$args = wp_parse_args( $args, array(
	'type'		=>	'info',
	'message'	=>	''
) );

if( ! $args['message'] ){
	return;
}

printf(
	'<div class="notice notice-%s"><p>%s</p></div>',
	esc_attr( $args['type'] ),
	is_array( $args['message'] ) ? explode( '<br/>', $args['message'] ) : $args['message']
);