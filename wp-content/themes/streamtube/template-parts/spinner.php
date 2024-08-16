<?php
/**
 * The spinner template
 *
 * @link       https://1.envato.market/mgXE4y
 * @since      1.0.8
 *
 * @package    WordPress
 * @subpackage StreamTube
 * @author     phpface <nttoanbrvt@gmail.com>
 */
if( ! defined( 'ABSPATH' ) ){
    exit;
}

$args = wp_parse_args( $args, array(
	'type'	=>	'dark',
    'text'  =>  esc_html__( 'Loading...', 'streamtube' )
) );

?>
<div class="spinner-border text-<?php echo sanitize_html_class( $args['type'] ); ?>" role="status">
    <?php printf(
        '<span class="visually-hidden">%s</span>',
        $args['text']
    )?>
</div>