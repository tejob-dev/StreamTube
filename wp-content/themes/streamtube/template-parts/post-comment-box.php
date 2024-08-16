<?php
/**
 * The comment box template file
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

if( ! have_comments() && ! comments_open() ){
	return;
}

printf(
	'<div class="post-meta__comment"><a class="comment-link text-decoration-none" data-bs-toggle="tooltip" href="%s" title="%s"><div class="comment-box">%s</div></a></div>',
	esc_url( get_comments_link() ),
	esc_attr( get_comments_number_text() ),
	number_format_i18n( get_comments_number() )
);