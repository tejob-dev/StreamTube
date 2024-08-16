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

$advanced_search = is_active_sidebar( 'advanced-search' ) && get_option( 'search_filter_dropdown', 'on' ) ? true : false;

printf(
	'<form action="%s" class="search-form %s d-flex" method="get">',
	esc_url( home_url( '/' ) ),
	$advanced_search ? 'advanced-search' : ''
);
?>

	<button class="toggle-search btn btn-sm border-0 shadow-none d-block d-lg-none p-2" type="button">
		<span class="icon-left-open"></span>
	</button>
		
	<div class="input-group-wrap position-relative w-100">		

		<?php printf(
			'<input id="search-input" class="form-control shadow-none ps-4 search-input %s" autocomplete="off" aria-label="%s" name="s" placeholder="%s" type="text" value="%s">',
			get_option( 'search_autocomplete', 'on' ) ? 'autocomplete' : '',
			esc_attr__( 'Search', 'streamtube' ),
			esc_attr__( 'Search here...', 'streamtube' ),
			esc_attr( streamtube_get_search_query_value() )
		);?>

		<input type="hidden" name="search">

		<?php wp_nonce_field( '_wpnonce', '_wpnonce', false, true );?>

		<?php if( $advanced_search ){
			get_template_part( 'template-parts/search-filter' );
		}?>

		<button class="btn btn-outline-secondary px-4 btn-main shadow-none" type="submit">
			<span class="btn__icon icon-search"></span>
		</button>

	</div>
</form>