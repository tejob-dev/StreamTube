<?php

if( ! defined('ABSPATH' ) ){
    exit;
}

$tsp_path = get_option( 'system_tsp_path', '/usr/bin/tsp' );

$task_id = isset( $_GET['task_id'] ) ? (int)$_GET['task_id'] : -1;

if( isset( $_GET['action'] ) && current_user_can( 'administrator' ) ){
	switch ( $_GET['action'] ) {

		case 'clear_finished_jobs':
			Streamtube_Core_Task_Spooler::clear_finished_tasks( $tsp_path );
		break;

		case 'delete':
			Streamtube_Core_Task_Spooler::delete_task( $tsp_path, $task_id );
		break;
	}
}

?>
<div class="wrap">

	<h1><?php esc_html_e( 'Task Spooler', 'streamtube-core' );?></h1>

	<div class="notice notice-info">
		<p>
			<?php printf(
				esc_html__( 'Task Spooler is a simple unix batch system, %s.', 'streamtube-core' ),
				'<a target="_blank" href="https://manpages.ubuntu.com/manpages/xenial/man1/tsp.1.html">'. esc_html__( 'read more', 'streamtube-core' ) .'</a>'
			);?>
		</p>
	</div>

	<form method="get">
		<input type="hidden" name="page" value="task-spooler">
		<ul class="subsubsub">
			<li>
				<?php printf(
					'<a href="%s" class="button button-primary">%s</a>',
					esc_url( add_query_arg( array(
						'action'	=>	'clear_finished_jobs'
					) ) ),
					esc_html__( 'Clear Finished Jobs', 'streamtube-core' )
				);?>
			</li>
		</ul>

		<?php if( ! function_exists( 'exec' ) || ! function_exists( 'shell_exec' ) ){
			?>
			<div class="notice notice-warning">
				<p>
					<?php esc_html_e( 'EXEC and|or SHELL_EXEC function is not enabled.', 'streamtube-core' );?>
				</p>
			</div>
			<?php
		}else{
			$table = new Streamtube_Core_Task_Spooler_Table();

			$table->prepare_items( $tsp_path  );

			$table->search_box('search', 'search_id');
			
			$table->display();
		}?>

	</form>
</div>