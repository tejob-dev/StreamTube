<?php
if( ! defined('ABSPATH' ) ){
    exit;
}

define( 'STREAMTUBE_CORE_IS_DASHBOARD_VIDEOS', true );

$GLOBALS['post_type_screen'] = 'video';

/**
 *
 * Fires before page header
 * 
 */
do_action( 'streamtube/core/user/dashboard/page_header/before' );

?>
<div class="page-head mb-3 d-flex gap-3 align-items-center">
	<h1 class="page-title h4">
		<?php esc_html_e( 'Videos', 'streamtube-core' );?>
	</h1>

	<div class="add-new dropdown">

		<button class="btn btn-danger text-white px-4" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
			<span class="btn__icon icon-plus"></span>
			<span class="btn__text"><?php esc_html_e( 'Add new', 'streamtube-core' ); ?></span>
		</button>

		<?php streamtube_core_load_template( 'misc/upload-dropdown.php', false )?>
	</div>	
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
	/**
	 *
	 * Fires before post table
	 * 
	 */
	do_action( 'streamtube/core/user/dashboard/post_table/before' );

	/**
	 *
	 * Fires before post table
	 * 
	 */
	do_action( "streamtube/core/user/dashboard/{$GLOBALS['post_type_screen']}/post_table/before" );

	streamtube_core_load_template( 'post/table-posts.php', true, array(
		'post_type'	=>	$GLOBALS['post_type_screen']
	) );

	/**
	 *
	 * Fires after post table
	 * 
	 */
	do_action( "streamtube/core/user/dashboard/{$GLOBALS['post_type_screen']}/post_table/after" );

	/**
	 *
	 * Fires after post table
	 * 
	 */
	do_action( 'streamtube/core/user/dashboard/post_table/after' );	
	?>
</div>
<?php

/**
 *
 * Fires after page content
 * 
 */
do_action( 'streamtube/core/user/dashboard/page_content/after' );