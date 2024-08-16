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
?>
<div class="alert alert-danger p-2 px-3 rounded-0">
	<?php
		printf(
			esc_html__( 'This %s is rejected.', 'streamtube' ),
			strtolower( streamtube_get_post_type_object()->labels->singular_name )
		);
	?>
</div>