<?php
global $post;

$post_id = is_object( $post ) ? $post->ID : 0;

if( is_taxonomy_hierarchical( $args['tax'] ) ):
	?>
	<ul class="checklist">
		<?php
			wp_terms_checklist( $post_id, array(
				'taxonomy'		=>	$args['tax'],
				'checked_ontop'	=>	false
			) );
		?>
	</ul>
	<?php
else:

	$terms = wp_get_post_terms( $post_id, $args['tax'] );

	?>
	<?php printf(
		'<select name="tax_input[%s][]" class="select2 tax-select2 regular-text input-field w-100" multiple="multiple">',
		esc_attr( $args['tax'] )
	);?>

		<?php if( $terms ){
			for ( $i=0; $i < count( $terms ); $i++) { 
				printf(
					'<option selected value="%s">%s</option>',
					$terms[$i]->slug,
					$terms[$i]->name
				);
			}
		}?>

	</select>

	<script type="text/javascript">
	    jQuery(function () {
	        jQuery( '.tax-select2' ).select2({
	        	allowClear: true,
	        	minimumInputLength : 1,
	        	ajax:{
	        		url : '<?php echo esc_url( admin_url( 'admin-ajax.php' ) )?>',
	        		delay: 250,
	        		dataType: 'json',
					data: function ( params ) {
						var query = {
							search  	: params.term,
							action 		: "get_yt_importer_tax_terms",
							tax 		: "<?php echo $args['tax'] ?>",
							_wpnonce	: "<?php echo wp_create_nonce( '_wpnonce' );?>"
						}
						return query;
					},
					processResults: function ( data, params ) {
					    params.page = params.page || 1;
					    return {
					        results: data.data.results
					    };
					}
	        	}
	        });
	    });
	</script>
	<?php

endif;