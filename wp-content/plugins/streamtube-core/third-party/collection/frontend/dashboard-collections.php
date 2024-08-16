<?php
if( ! defined('ABSPATH' ) ){
    exit;
}

/**
 *
 * Fires before page header
 * 
 */
do_action( 'streamtube/core/user/dashboard/page_header/before' );

$search = isset( $_GET['search_query'] ) ? sanitize_text_field( $_GET['search_query'] ) : '';
?>

<div class="page-head mb-3 d-flex gap-3 align-items-center">
	<h1 class="page-title h4">
		<?php esc_html_e( 'Collections', 'streamtube-core' );?>
	</h1>	

	<form class="search-form ms-auto">
		<div class="input-group">
			<?php printf(
				'<input class="form-control outline-none shadow-none rounded-1" name="search_query" type="text" placeholder="%1$s" aria-label="%1$s" value="%2$s">',
				esc_attr__( 'Search ...', 'streamtube-core' ),
				esc_attr( $search )
			);?>

			<button class="btn border-0 shadow-none btn-main text-muted" type="submit" name="submit" value="search">
				<span class="icon-search"></span>
			</button>
		</div>
	</form>
</div>	

<?php
/**
 *
 * Fires after page header
 * 
 */
do_action( 'streamtube/core/user/dashboard/page_header/after' );

/**
 *
 * Fires before page content
 * 
 */
do_action( 'streamtube/core/user/dashboard/page_content/before' );
?>

<div class="page-content">
	<?php

	$args = apply_filters( 'streamtube/core/user/dashboard/collections/args', array(
		'taxonomy'			=>	array( Streamtube_Core_Collection::TAX_COLLECTION ),
		'public_only'		=>	false,
		'exclude_builtin'	=>	false,
		'layout'			=>	'playlist',
		'user_id'			=>	get_queried_object_id(),
		'search'			=>	$search,
		'number'			=>	-1,
		'term_author'		=>	false,
		'term_status'		=>	true,
		'col_xxl'			=>	4,
		'col_xl'			=>	4,
		'col_lg'			=>	4,
		'col_md'			=>	2,
		'col_sm'			=>	2,
		'col'				=>	1
	) );

	the_widget( 'Streamtube_Core_Widget_Term_Grid', $args );
	?>
</div>

<?php

/**
 *
 * Fires after page content
 * 
 */
do_action( 'streamtube/core/user/dashboard/page_content/after' );