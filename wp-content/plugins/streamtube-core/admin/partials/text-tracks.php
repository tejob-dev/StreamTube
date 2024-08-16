<?php
/**
 *
 * The Text Tracks template file
 * 
 */
if( ! defined('ABSPATH' ) ){
    exit;
}

global $post;

$extensions = Streamtube_Core_Post::get_text_track_format();

wp_enqueue_style( 'select2' );
wp_enqueue_script( 'select2' );
wp_enqueue_script( 'jquery-ui-sortable' );

global $post;

$tracks 		= streamtube_core()->get()->post->get_text_tracks( $post->ID );
$track_count 	= 1;

if( $tracks ){
	$track_count = count( $tracks );
}

?>
<div class="metabox">

	<table class="form-table metadata-table table-text-tracks" id="table-text-tracks">

		<thead>
			<th style="width: 5%"><?php esc_html_e( '#', 'streamtube-core' );?></th>
			<th style="width: 20%"><?php esc_html_e( 'Language', 'streamtube-core' );?></th>
			<th style="width: 60%"><?php esc_html_e( 'Source', 'streamtube-core' );?></th>
			<th style="width: 15%"><?php esc_html_e( 'Action', 'streamtube-core' );?></th>
		</thead>

		<tbody>

			<?php 
			for ( $i = 0;  $i < $track_count;  $i++ ) {

				$track = array(
					'language'		=>	'',
					'source'		=>	''
				);

				if( $tracks ){
					$track = $tracks[$i];
				};

				printf(
					'<tr class="track-row" id="track-row-%s">',
					$i
				);

				load_template( plugin_dir_path( __FILE__ ) . 'text-track.php', false, $track );

				printf(
					'</tr>'
				);				
			}?>

		</tbody>
	</table>
</div>

<style type="text/css">
	.table-text-tracks .dashicons{
		font-size: 1rem;
	}

	.table-text-tracks tbody tr:first-child .track_remove{
		display: none;
	}
</style>

<script type="text/javascript">

	function updateTrackRowIndex(){
		jQuery(  '#table-text-tracks tbody tr').each( function( index ){
			jQuery( this ).find( 'td .badge.count' ).html( index+1 );
		});
	}

	jQuery( document ).ready(function() {

		updateTrackRowIndex();

		jQuery( '.text_tracks_language' ).select2();

		jQuery( document ).on( 'click', 'button.track_add', function(){

			jQuery( '.text_tracks_language' ).select2( 'destroy' );

			var tr = jQuery(this).closest( 'tr' );
			var trClone = tr.clone();

			jQuery( trClone ).find( '.input-field' ).val('');

			tr.after( trClone );

			jQuery( '.text_tracks_language' ).select2();

			updateTrackRowIndex();
		} );	

		jQuery( document ).on( 'click', 'button.track_remove', function(){
			jQuery(this).closest( 'tr' ).remove();

			updateTrackRowIndex();
		} );

		jQuery( "#table-text-tracks tbody" ).sortable({
			update: function( event, ui ) {
				updateTrackRowIndex();
			}
		});

		jQuery( document ).on( 'change', 'input[name=text_track_file]', function( e ){

			var me  		= jQuery(this);
			var parentDiv	= me.closest( '.field-group' );
			var button 		= me.parent();
			var error 		= false;
			var extensions	= <?php echo json_encode( $extensions )?>;
			var postId 		= <?php echo $post->ID; ?>;
			var files 		= e.target.files || e.dataTransfer.files;

			var file 		= files[0];

			if( ! file ){
				return;
			}			

			var formData 	= new FormData();
			var jqXHR 		= new XMLHttpRequest();

			formData.append( 'action', 		'upload_text_track' );
			formData.append( 'post_ID', 	postId );
			formData.append( 'file', 		file );
			formData.append( '_wpnonce', 	streamtube._wpnonce );

			var parts 			= file.name.split('.');
			var ext 			= parts[parts.length - 1].toLowerCase();

			var error 			= false;

			// Check file extension
			if( jQuery.inArray( ext, extensions ) == -1 ){
				error = streamtube.invalid_file_format;
			}

			if( error !== false ){
				return jQuery.showToast( error, 'danger' );
			}

			button.addClass( 'disabled' );

			jqXHR.onload = function() {
				if( jqXHR.readyState === 4 && jqXHR.responseText ){
					var response = jQuery.parseJSON( jqXHR.responseText );

					if( response.success == false ){
						return jQuery.showToast( response.data[0].message, 'danger' );
					}

					parentDiv.find( '.text-track-field' ).val( response.data );

					button.removeClass( 'disabled' );
				}
			}

			jqXHR.open( 'POST', '<?php echo admin_url( 'admin-ajax.php' ); ?>', true );

			jqXHR.send( formData );

		} );
	});

</script>