<?php
/**
 *
 * The Report comment button template file
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
?>
<?php printf(
	'<button title="%s" type="button" class="btn-hide-icon-active btn p-1 shadow-none btn-report-comment ajax-elm" data-params="%s" data-action="%s">',
	esc_attr__( 'Remove Report', 'streamtube-core' ),
	esc_attr( json_encode( $button_params ) ),
	'remove_comment_report'
);?>
	<span class="btn__icon icon-lock-open"></span>
</button>