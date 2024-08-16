<?php
if( ! defined('ABSPATH' ) ){
    exit;
}

if( ! function_exists( 'wp_terms_checklist' ) ){
    include ABSPATH . 'wp-admin/includes/template.php';
}       
if( ! function_exists( 'post_categories_meta_box' ) ){
    include ABSPATH . 'wp-admin/includes/meta-boxes.php';
}

global $post, $post_type_screen;

if( is_post_type_viewable( $post_type_screen )):

	$taxonomies = get_object_taxonomies( $post_type_screen, 'object' );

	if( $taxonomies ):

		foreach ( $taxonomies as $tax => $object ):

			if( is_taxonomy_hierarchical( $tax ) && ! in_array( $tax, Streamtube_Core_Taxonomy::get_edit_post_exclude_taxonomies() ) ):

				$is_enabled = wp_validate_boolean( get_option( $post_type_screen . '_taxonomy_' . $tax, 'on' ) );

				/**
				 *
				 * Filter the taxonomy visibility
				 * 
				 * @param string $is_enabled
				 * @param object $taxonomy_object
				 * @param string $taxonomy
				 * 
				 */
				$is_enabled = apply_filters( "streamtube/core/post/edit/tax/visibility", $is_enabled, $object, $tax );				

				if( $is_enabled ):					

					printf(
						'<div class="widget widget-taxonomy widget-%1$s tax-%1$s shadow-sm rounded bg-white border" id="widget-%1$s">',
						esc_attr( $tax )
					);
					?>
						<div class="widget-title-wrap m-0 p-3 bg-light">
						    <h2 class="widget-title no-after m-0">
						    	<?php

						    	$title = $object->label;

						    	if( $tax == 'categories' ){
						    		$title = esc_html__( 'Categories', 'streamtube-core' );
						    	}

						    	/**
						    	 *
						    	 * Filter the widget title
						    	 * 
						    	 * @since 1.3
						    	 */
						    	$title = apply_filters( 'streamtube/core/post/edit/tax/title', $title, $object, $tax );

						    	echo $title;
						    	?>
						    </h2>
						</div>	

						<div class="widget-content p-3">

							<?php 
							/**
							 * Fires before content
							 *
							 * @since 2.0
							 */
							do_action( 'streamtube/core/post/edit/tax/before', $object, $tax );

							/**
							 * @since 2.0
							 */
							do_action( "streamtube/core/post/edit/{$tax}/before", $object );							
							?>

							<?php if( current_user_can( $object->cap->edit_terms ) ) : ?>

								<?php

								add_filter( 'wp_terms_checklist_args', 'streamtube_core_filter_wp_terms_checklist' );

								$meta_box_args = array(
									'id'		=>	sprintf( '%s-metabox', $tax ),
									'title'		=>	$object->label,
									'args'		=>	array(
										'taxonomy'	=>	$tax
									)
								);

								/**
								 *
								 * Filter the meta box args
								 * 
								 * @param array $meta_box_args
								 * @param object $taxonomy_object
								 * 
								 */
								$meta_box_args = apply_filters( "streamtube/core/post/edit/{$tax}/meta_box_args", $meta_box_args, $object );

								post_categories_meta_box( $post, $meta_box_args );

								remove_filter( 'wp_terms_checklist_args', 'streamtube_core_filter_wp_terms_checklist' );

								?>

							<?php else: ?>

								<?php

								$checklist_args = array(
									'taxonomy'		=>	$tax,
									'checked_ontop'	=>	false,
									'echo'			=>	false
								);

								/**
								 *
								 * Filter the checklist args
								 * 
								 * @param string $tax
								 * @param object taxonomy $object
								 * 
								 * @since 1.3
								 * 
								 */
								$checklist_args = apply_filters( 'streamtube/core/post/edit/tax/checklist_args', $checklist_args, $object, $tax );

								$checklist = wp_terms_checklist( $post ? $post->ID : 0, $checklist_args );								

								if( ! empty( $checklist ) ) :

									$max_items = (int)get_option( $post_type_screen . '_taxonomy_' . $tax . '_max_items', 0 );

									/**
									 *
									 * Filter max items can be checked of the taxonomy
									 * 
									 * @param int $max_items
									 * @param object taxonomy $object
									 * @param string $tax
									 *
									 * @since 2.0
									 * 
									 */
									$max_items = apply_filters( 'streamtube/core/post/edit/tax/max_items', $max_items, $object, $tax );

									?>
									<ul class="categorychecklist" data-max-items="<?php echo esc_attr( $max_items ); ?>">
										<?php echo $checklist; ?>
									</ul>

								<?php endif;?>

							<?php endif;?>

							<?php 
							/**
							 * Fires after content
							 *
							 * @since 2.0
							 */
							do_action( 'streamtube/core/post/edit/tax/after', $object, $tax );

							/**
							 * @since 2.0
							 */
							do_action( "streamtube/core/post/edit/{$tax}/after", $object );							
							?>

						</div>		

					</div>
					<?php

				endif;

			endif;

		endforeach;

	endif;

endif;