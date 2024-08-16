<?php
if( ! defined('ABSPATH' ) ){
    exit;
}

define( 'STREAMTUBE_CORE_IS_DASHBOARD_POSTS', true );

/**
 *
 * Fires before page header
 * 
 */
do_action( 'streamtube/core/user/dashboard/page_header/before' );

?>
<div class="page-head mb-3 d-flex gap-3 align-items-center">
	<h1 class="page-title h4">
		<?php esc_html_e( 'Posts', 'streamtube-core' );?>
	</h1>

	<a class="btn btn-danger text-white px-4" href="<?php echo esc_url( add_query_arg( array( 'view' => 'add-post' ) ) ); ?>">
		<span class="btn__icon icon-plus"></span>
		<span class="btn__text"><?php esc_html_e( 'Add new', 'streamtube-core' ); ?></span>
	</a>	
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
	do_action( 'streamtube/core/user/dashboard/post/post_table/before' );

	streamtube_core_load_template( 'post/table-posts.php', true, array(
		'post_type'	=>	'post'
	) );

	/**
	 *
	 * Fires after post table
	 * 
	 */
	do_action( 'streamtube/core/user/dashboard/post/post_table/after' );

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