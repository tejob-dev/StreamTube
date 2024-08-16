<?php
if( ! defined('ABSPATH' ) ){
    exit;
}

$status = isset( $_GET['post_status'] ) ? sanitize_key( $_GET['post_status'] ) : 'any';
?>
<div class="badge-filters my-3">

	<?php foreach ( $args as $key => $text ) {

		$url = add_query_arg( array(
			'post_status'	=>	$key
		) );

		$url = remove_query_arg( array( 'bulk_action', 'entry_ids', 'submit' ), $url );

		printf(
			'<div class="entry-status status-%s mb-2"><a class="badge %s text-decoration-none text-white" href="%s">%s</a></div>',
			sanitize_html_class( $key ),
			$status == $key ? 'bg-danger' : 'bg-secondary',
			esc_url( $url ),
			$text
		);
	} ?>

</div>