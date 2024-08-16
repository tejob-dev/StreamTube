<?php
if( ! defined('ABSPATH' ) ){
    exit;
}

$source = $args;
?>

<td data-title="#">
	<span class="badge bg-secondary count">
		
	</span>
</td>

<td data-title="<?php esc_attr_e( 'Label', 'streamtube-core' )?>">
	<?php printf(
		'<input type="text" class="regular-text" name="altsources[labels][]" value="%s">',
		esc_attr( $source['label'] )
	);?>
</td>

<td data-title="<?php esc_attr_e( 'Source', 'streamtube-core' )?>">
	<div class="field-group">
		<div class="input-group">
			<?php printf(
				'<input class="input-field regular-text form-control text-track-field" type="text" name="altsources[sources][]" value="%s">',
				esc_attr( $source['source'] )
			);?>

			<?php if( current_user_can( 'administrator' ) || apply_filters( 'streamtube/core/altsource/wpmedia', false ) === true ): ?>
				<button 
					type="button" 
					class="btn btn-secondary button button-secondary button-upload" 
					data-media-type="video" 
					data-media-source="id">
					<span class="dashicons dashicons-upload"></span>
				</button>
			<?php endif; ?>
		</div>
	</div>
</td>

<td data-title="<?php esc_attr_e( 'Action', 'streamtube-core' )?>">
	<div class="d-flex gap-3">
		<button type="button" class="btn btn-danger btn-sm source_remove p-1">
			<span class="dashicons dashicons-minus icon-minus"></span>
		</button>					
		<button type="button" class="btn btn-primary button-primary btn-sm source_add p-1">
			<span class="dashicons dashicons-plus icon-plus"></span>
		</button>
	</div>
</td>	
