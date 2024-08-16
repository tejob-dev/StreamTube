<?php
if( ! defined('ABSPATH' ) ){
    exit;
}

global $post;

$args = array(
	'post_id'	=>	$post->ID
);

streamtube_core_load_template( 'comment/table-comments.php', true, $args );