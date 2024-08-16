<?php
/**
 *
 * The Text Track template file
 * 
 */
if( ! defined('ABSPATH' ) ){
    exit;
}

$languages 	= streamtube_core_get_language_options();
;
$track 		= $args;
?>
<td data-title="#">
	<span class="badge bg-secondary count">
		
	</span>
</td>

<td data-title="<?php esc_attr_e( 'Language', 'streamtube-core' )?>">
	<select class="regular-text text_tracks_language select-select2" name="text_tracks[languages][]">
		
		<option value=""><?php esc_html_e( 'Select', 'streamtube-core' );?></option>

		<?php if( is_array( $languages ) ): ?>

			<?php foreach ( $languages as $language => $text ): ?>
				
				<?php printf(
					'<option value="%s" %s>%s</option>',
					esc_attr( strtolower( $language ) ),
					$language == $track['language'] ? 'selected' : '',
					esc_html( $text )
				);?>

			<?php endforeach; ?>

		<?php endif;?>

	</select>
</td>
<td data-title="<?php esc_attr_e( 'Source', 'streamtube-core' )?>">
	<div class="field-group">
		<div class="input-group">
			
			<?php printf(
				'<input class="input-field regular-text form-control text-track-field" type="text" name="text_tracks[sources][]" value="%s" placeholder="%s">',
				esc_attr( $track['source'] ),
				esc_attr__( 'Upload a VTT (WebVTT) file', 'streamtube-core' )
			);?>

			<?php if( is_admin() ): ?>
				<button 
					type="button" 
					class="btn btn-secondary button button-secondary button-upload" 
					data-media-type="text" 
					data-media-source="url">
					<span class="dashicons dashicons-upload"></span>
				</button>
			<?php else:	?>
				<label class="btn btn-secondary btn-sm">
					<input name="text_track_file" type="file" accept=".vtt" class="d-none">
					<span class="icon-upload"></span>
				</label>
			<?php endif;?>
		</div>
	</div>
</td>

<td data-title="<?php esc_attr_e( 'Action', 'streamtube-core' )?>">
	<div class="d-flex gap-3">
		<button type="button" class="btn btn-danger btn-sm track_remove p-1">
			<span class="dashicons dashicons-minus icon-minus"></span>
		</button>					
		<button type="button" class="btn btn-primary button-primary btn-sm track_add p-1">
			<span class="dashicons dashicons-plus icon-plus"></span>
		</button>
	</div>
</td>