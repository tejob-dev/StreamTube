<?php

/**
 *
 * The collection privacy button
 * 
 * @param  int $term_id
 * @return string
 * 
 */
function streamtube_core_collection_button_privacy( $term_id ){

	$status = get_term_meta( $term_id, 'status', true );

	$output = sprintf(
		'<button type="button" data-action="%1$s" data-params="%2$s" class="ajax-elm btn-collection-control btn btn-collection-%3$s shadow-none p-1" title="%4$s">',
		'set_collection_status',
		esc_attr( $term_id ),
		$status == 'public' ? 'lock' : 'unlock',
		$status == 'public' ? esc_attr__( 'Lock Collection?', 'streamtube-core' ) : esc_attr__( 'Unlock Collection?', 'streamtube-core' )
	);

		$output .= sprintf(
			'<span class="btn__icon icon-%1$s"></span>',
			$status == 'public' ? 'globe' : 'lock',
		);

	$output .= '</button>';

	return apply_filters( 'streamtube_core_collection_button_privacy', $output, $term_id );
}

/**
 *
 * The Add Video To collection button
 * 
 * @param  integer $post_id
 * @param  integer $term_id
 * @return string
 */
function streamtube_core_collection_add_post_to( $post_id = 0, $term_id = 0 ){

	$exists = has_term( $term_id, Streamtube_Core_Collection::TAX_COLLECTION, $post_id );

	$el_classes = array(
		'btn',
		'btn-sm',
		'p-2',
		'ajax-elm',
		'btn-hide-icon-active',
		'btn-add-to-term'
	);

	return sprintf(
        '<button data-action="set_post_collection" data-params="%s" type="button" class="%s"><span class="btn__icon %s h6"></span></button>',
        esc_attr( json_encode( array(
            'post_id'   =>  (int)$post_id,
            'term_id'   =>  (int)$term_id,
            'from'		=>	'add_to'
        ) ) ),
        esc_attr( join( ' ', $el_classes ) ),
        ! $exists ? 'icon-plus text-secondary' : 'icon-check text-success'
    );
}