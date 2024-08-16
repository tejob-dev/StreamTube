<?php

$taxonomy = get_option( 'search_taxonomy', 'categories' );

if( empty( $taxonomy ) || ! taxonomy_exists( $taxonomy ) ){
	return;
}

$dropdown_args = array(
	'class'				=>	'form-control post-type-select search-type-select',
	'taxonomy'			=>	$taxonomy,
	'value_field'		=>	'slug',
	'name'				=>	'term_slug',
	'hide_empty'		=>	true,
	'hierarchical'		=>	true,
	'selected'			=>	isset( $_GET['term_slug'] ) ? sanitize_key( $_GET['term_slug'] ) : '',
	'show_option_all'	=>	esc_html__( 'All', 'streamtube' ),
	'echo'				=>	true
);

/**
 * @since 2.1
 */
$dropdown_args = apply_filters( 'streamtube/searchform/taxonomy_args', $dropdown_args );

wp_dropdown_categories( $dropdown_args );