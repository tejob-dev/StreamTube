<?php
$post_types = streamtube_get_search_post_types();

/**
 *
 * Filter the post types
 * 
 * @var array
 *
 * @since 1.0.5
 * 
 */
$post_types = apply_filters( 'streamtube/searchform/post_types', $post_types );

if( is_array( $post_types ) && count( $post_types ) > 1 ): ?>

	<select class="form-control post-type-select search-type-select" name="post_type">
		<?php foreach ( $post_types as $post_type => $label ) : ?>
			<?php 

			if( streamtube_is_bbp_search() ){
				$_GET['post_type'] = 'topic';
			}

			$selected = isset( $_GET['post_type'] ) && $_GET['post_type'] == $post_type ? 'selected' : '';

			printf(
				'<option %s value="%s">%s</option>',
				$selected,
				esc_attr( $post_type ),
				esc_html( $label )
			);?>
		<?php endforeach; ?>
	</select>

<?php endif;