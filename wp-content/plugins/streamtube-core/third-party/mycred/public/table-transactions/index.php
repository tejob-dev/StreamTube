<?php
if( ! defined('ABSPATH' ) ){
    exit;
}

$endpoint = $GLOBALS['wp_query']->query_vars['dashboard'];

$point_types = streamtube_core_get_mycred_public_point_types();

/**
 * Filter the public point types
 */
$point_types = apply_filters( 'streamtube/core/mycred/table_logs/public_point_types', $point_types );

$args = array(
	'number'	=>	get_option( 'posts_per_page' ),
	'paged'		=> 	isset( $_GET['page'] ) ? (int)$_GET['page'] : 1,
	'user_id'	=>	get_queried_object_id(),
	'orderby'	=>	'time',
	'order'		=>	isset( $_GET['order'] )	? $_GET['order'] : 'DESC',
	'ctype'		=>	array(
		'ids'		=>	array_keys( $point_types ),
		'compare'	=>	'IN'
	)
);

if ( preg_match ( '/points\/page\/([0-9]+)/', $endpoint, $matches ) ){
	$args['paged']	=	$matches[1];
}

if( isset( $_GET['ref'] ) ){
	$args['ref']  = sanitize_key($_GET['ref']);
}

if( isset( $_REQUEST['ctype'] ) && array_key_exists( $_REQUEST['ctype'], mycred_get_types() ) ){
	$args['ctype'] = wp_unslash( $_REQUEST['ctype'] );
}

if( current_user_can( 'administrator' ) ){
	unset( $args['user_id'] );

	if( isset( $_GET['user'] ) ){
		$user = get_user_by( 'login', $_GET['user'] );

		if( $user instanceof WP_User ){
			$args['user_id'] = $user->ID;
		}
	}
}

$args = apply_filters( 'streamtube/core/user/dashboard/mycred/table_logs/args', $args );

// The Query
$logs = new myCRED_Query_Log( $args );

/**
 *
 * Fires before page header
 * 
 */
do_action( 'streamtube/core/user/dashboard/page_header/before' );
?>
<div class="page-head mb-3 d-flex gap-3 align-items-center">

	<?php
	/**
	 *
	 * Fires after heading
	 *
	 * @since 1.1.7.2
	 * 
	 */
	do_action( 'streamtube/core/dashboard/transactions/heading/before' );
	?>

	<h1 class="page-title h4">
		<?php esc_html_e( 'Transactions', 'streamtube-core' );?>
	</h1>

	<?php if( $buy_points_url = streamtube_core()->get()->myCRED->get_buy_points_page() ){

		printf(
			'<a class="btn btn-danger text-white" href="%s"><span class="icon-dollar me-1"></span>%s</a>',
			esc_url( $buy_points_url ),
			esc_html__( 'Buy Points', 'streamtube-core' )
		);
	}?>

	<?php
	/**
	 *
	 * Fires after heading
	 *
	 * @since 1.1.7.2
	 * 
	 */
	do_action( 'streamtube/core/dashboard/transactions/heading/after' );
	?>

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
	<div class="widget transactions-points">

		<?php
		/**
		 *
		 * Fires before table
		 *
		 * 
		 */
		do_action( 'streamtube/core/dashboard/transactions/table/before' );
		?>	

		<form method="get">

			<?php include( 'bar-top.php' ) ?>

			<?php if ( $logs->have_entries() ):?>

				<?php $logs->display(); ?>

			<?php else:?>

				<?php
					printf(
						'<div class="not-found p-3 text-center text-muted fw-normal h6"><p>%s</p></div>',
						esc_html__( 'No transactions were found.', 'streamtube-core' )
					);
				?>

			<?php endif;?>

			<?php include( 'bar-bottom.php' ) ?>
		</form>

		<?php
		/**
		 *
		 * Fires before table
		 *
		 * 
		 */
		do_action( 'streamtube/core/dashboard/transactions/table/after' );
		?>		
	</div>
</div>

<?php

/**
 *
 * Fires after page content
 * 
 */
do_action( 'streamtube/core/user/dashboard/page_content/after' );