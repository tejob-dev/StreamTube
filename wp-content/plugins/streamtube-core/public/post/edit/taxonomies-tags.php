<?php
if( ! defined('ABSPATH' ) ){
    exit;
}

global $post, $post_type_screen;

if( is_post_type_viewable( $post_type_screen )):

	$taxonomies = get_object_taxonomies( $post_type_screen, 'object' );

	if( $taxonomies ):

		foreach ( $taxonomies as $tax => $object ):	

			if( ! is_taxonomy_hierarchical( $tax ) && ! in_array( $tax, Streamtube_Core_Taxonomy::get_edit_post_exclude_taxonomies() ) ):

				$is_enabled 	= wp_validate_boolean( get_option( $post_type_screen . '_taxonomy_' . $tax, 'on' ) );
				$max_items 		= (int)get_option( $post_type_screen . '_taxonomy_' . $tax . '_max_items', 0 );
				$free_input 	= wp_validate_boolean( get_option( $post_type_screen . '_taxonomy_' . $tax . '_freeinput', 'on' ) );
				$auto_suggest 	= wp_validate_boolean( get_option( $post_type_screen . '_taxonomy_' . $tax . '_autosuggest', 'on' ) );
				$max_chars 		= 100;

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

				/**
				 *
				 * Filter the tax options
				 * 
				 */
				$tax_options = apply_filters( "streamtube/core/post/edit/tax/options", compact(
					'is_enabled', 
					'max_items', 
					'free_input', 
					'auto_suggest', 
					'max_chars' 
				), $object, $tax );

				extract( $tax_options );

				if( $is_enabled ):

					/**
					 * Fires before tax
					 *
					 * @since 2.0
					 */
					do_action( 'streamtube/core/post/edit/tax/before', $object, $tax );

					/**
					 * @since 2.0
					 */
					do_action( "streamtube/core/post/edit/{$tax}/before", $object );					

					$title  = sprintf(
						esc_html__( '%s (separated by commas)', 'streamtube-core' ),
						$tax == 'video_tag' ? esc_html__( 'Tags', 'streamtube-core' ) : $object->label
					);

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

				    if( $max_items > 0 ){
				        $title  = sprintf(
				            esc_html__( '%s (separated by commas, maximum of %s tags)', 'streamtube-core' ),
				            $tax == 'video_tag' ? esc_html__( 'Tags', 'streamtube-core' ) : $object->label,
				            $max_items
				        );
				    }

			    	/**
			    	 *
			    	 * Filter the widget title
			    	 * 
			    	 * @since 1.3
			    	 */
			    	$title = apply_filters( 'streamtube/core/post/edit/tax/title', $title, $object, $tax );

                    $tag_items = is_object( $post ) ? wp_get_post_terms( $post->ID, $tax ) : array();

                    $tag_input_attrs = array(
	                    'data-role' 			=>  'tags-input',
	                    'data-max-tags' 		=>  $max_items,
			            'data-max-chars'    	=>  $max_chars,
			            'data-tax'          	=>  $tax,
			            'data-free-input'		=>	$free_input,
			            'data-auto-suggest'		=>	$auto_suggest
                    );

					/**
					 *
					 * Filter tag_input_attrs can be checked of the taxonomy
					 * 
					 * @param int $max_items
					 * @param object taxonomy $object
					 * @param string $tax
					 *
					 * @since 2.0
					 * 
					 */
					$tag_input_attrs = apply_filters( 'streamtube/core/post/edit/tax/tag_input_attrs', $tag_input_attrs, $object, $tax ); 

                    streamtube_core_the_field_control( array(
                		'label'			=>	$title,
                		'name'			=>	sprintf( 'tax_input[%s]', $tax ),
                		'value'			=>	is_array( $tag_items ) ? join(',', wp_list_pluck( $tag_items, 'name' ) ) : '',
                        'data'          =>  $tag_input_attrs,
                		'wrap_class'	=>	'taginput-wrap'
                	) );

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

				endif;

			endif;

		endforeach;

	endif;

endif;