<?php
/**
 *
 * The Delete comment button template file
 * 
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

global $comment;

$button_params = array(
	'comment_id'	=>	$comment->comment_ID
);

$classes = array(
	'btn-hide-icon-active', 'btn', 'p-1', 'shadow-none', 'btn-edit-comment' ,'ajax-elm'
);

printf(
	'<button title="%s" type="button" class="%s" data-params="%s" data-action="%s">',
	esc_attr__( 'Edit this Comment', 'streamtube-core' ),
	esc_attr( join( ' ', $classes ) ),
	esc_attr( json_encode( $button_params ) ),
	'get_comment_to_edit'
);?>
	<span class="btn__icon icon-edit"></span>
</button>