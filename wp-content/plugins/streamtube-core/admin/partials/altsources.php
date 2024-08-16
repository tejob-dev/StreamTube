<?php
if( ! defined('ABSPATH' ) ){
    exit;
}

global $post;

wp_enqueue_script( 'jquery-ui-sortable' );

$sources 		= streamtube_core()->get()->post->get_altsources( $post->ID, false, false );
$source_count 	= 1;

if( $sources ){
	$source_count = count( $sources );
}

?>
<table id="table-altsources" class="form-table metadata-table table-altsources">
	<thead>
		<th style="width: 5%"><?php esc_html_e( '#', 'streamtube-core' );?></th>
		<th style="width: 20%"><?php esc_html_e( 'Label (required)', 'streamtube-core' );?></th>
		<th style="width: 60%"><?php esc_html_e( 'Source (required)', 'streamtube-core' );?></th>
		<th style="width: 15%"><?php esc_html_e( 'Action', 'streamtube-core' );?></th>
	</thead>

	<tbody>
		<?php for ( $i = 0;  $i < $source_count;  $i++ ) {
			$source = array(
				'label'		=>	esc_html__( 'Label %s', 'streamtube-core' ),
				'source'	=>	''
			);

			if( $sources ){
				$source = $sources[$i];
			};

			printf(
				'<tr class="source-row" id="source-row-%s">',
				$i
			);

			load_template( plugin_dir_path( __FILE__ ) . 'altsource.php', false, $source );

			printf(
				'</tr>'
			);	
		}?>
	</tbody>
</table>
<style type="text/css">
	.table-altsources .dashicons{
		font-size: 1rem;
	}

	.table-altsources tbody tr:first-child .source_remove{
		display: none;
	}
</style>

<script type="text/javascript">

	function updateSourceRowIndex(){
		jQuery(  '#table-altsources tbody tr').each( function( index ){
			jQuery( this ).find( 'td .badge.count' ).html( index+1 );
		});
	}

	jQuery( document ).ready(function() {

		updateSourceRowIndex();

		jQuery( document ).on( 'click', 'button.source_add', function(){

			var tr = jQuery(this).closest( 'tr' );
			var trClone = tr.clone();

			jQuery( trClone ).find( '.input-field' ).val('');

			tr.after( trClone );

			updateSourceRowIndex();
		} );

		jQuery( document ).on( 'click', 'button.source_remove', function(){
			jQuery(this).closest( 'tr' ).remove();

			updateSourceRowIndex();
		} );

		jQuery( "#table-altsources tbody" ).sortable({
			update: function( event, ui ) {
				updateSourceRowIndex();
			}
		});

	});

</script>