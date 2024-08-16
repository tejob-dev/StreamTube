<?php
/**
 * The Collection List template file
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

global $streamtube;

$collections = $streamtube->get()->collection->_get_user_terms();

printf(
	'<ul id="collection-list-%s" class="list-unstyled collection-list border">',
	get_current_user_id()
);

	if( $collections ){
		for ( $i=0;  $i < count( $collections );  $i++) { 
			load_template( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'collection-item.php', false, $collections[$i] );
		}
	}

echo '</ul>';