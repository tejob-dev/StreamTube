<?php
/**
 * The Collection Item template file
 *
 * @link       https://1.envato.market/mgXE4y
 * @since      1.0.0
 *
 * @package    WordPress
 * @subpackage StreamTube
 * @author     phpface <nttoanbrvt@gmail.com>
 */
if( ! defined( 'ABSPATH' ) ){
    exit;
}

global $post, $streamtube;

$Collection = $streamtube->get()->collection;

$term = $args;

$name = $term->name_formatted;

$params = array(
	'post_id'	=>	$post ? $post->ID : 0,
	'term_id'	=>	$term->term_id,
	'status'	=>	$term->status,
	'name'		=>	$name
);

printf(
	'<li id="collection-%s" class="p-2 collection-item border-bottom position-relative">',
	esc_attr( $term->term_id )
);?>
	<?php 
	/**
	 *
	 * Fires before collection item
	 *
	 * @param array $collection
	 * 
	 */
	do_action( 'streamtube/core/collection/item/before', $term );
	?>	
	<div class="collection-item__left collection-item-details p-2">
		<div class="form-check">

			<div class="term-checkbox">
			<?php printf(
				'<input class="ajax-elm form-check-input me-3" type="checkbox" id="collection-input-%1$s" data-params="%2$s" data-action="%3$s" %4$s>',
				esc_attr( $term->term_id ),
				esc_attr( json_encode( $params ) ),
				'set_post_collection',
				$post && $Collection->_has_term( $post->ID, $term->term_id ) ? 'checked' : ''
			);?>
			</div>

			<div class="term-body d-flex">

				<label class="form-check-label" for="collection-input-<?php echo esc_attr( $term->term_id ); ?>">
					<div class="collection-image ratio ratio-16x9 rounded overflow-hidden bg-dark me-3">
						<?php if( "" != $featured_image = get_term_meta( $term->term_id, 'thumbnail_id', true ) ){
							echo wp_get_attachment_image( $featured_image, 'small' );
						}?>
					</div>
				</label>

				<div class="collection-content">
					<p class="fw-bold term-name text-body mb-1">
					<?php printf(
						'<label class="form-check-label" for="collection-input-%s">%s</label>',
						esc_attr( $term->term_id ),
						esc_html( $name )
					);?>
					</p>
					<?php 
					/**
					 * @param WP_Term $term
					 */
					do_action( 'streamtube/core/collection/item/term_meta/before', $term );
					?>	
					<div class="term-meta">

						<div class="term-count small text-secondary mb-0">
							<?php printf( _n( '%s video', '%s videos', $term->count, 'streamtube-core' ), number_format_i18n( $term->count ) ); ?>
						</div>

						<div class="term-view-all small mb-0">
							<?php printf(
								'<a class="text-secondary text-decoration-none" href="%s" title="%s">%s</a>',
								$Collection->get_term_link( $term ),
								esc_attr( $term->name_formatted ),
								esc_html__( 'View All', 'streamtube-core' )
							);?>
						</div>

						<?php if( "" != $play_all_url = $Collection->get_play_all_link( $term ) ) : ?>

							<div class="term-play-all small mb-0">
								<?php printf(
									'<a class="text-secondary text-decoration-none" href="%s" title="%s">%s</a>',
									esc_url( $play_all_url ),
									esc_attr( $term->name_formatted ),
									esc_html__( 'Play All', 'streamtube-core' )
								);?>
							</div>

						<?php endif;?>

						<?php 
						/**
						 * @param WP_Term $term
						 */
						do_action( 'streamtube/core/collection/item/term_meta', $term );
						?>	
					</div>
					<?php 
					/**
					 * @param WP_Term $term
					 */
					do_action( 'streamtube/core/collection/item/term_meta/after', $term );
					?>				
				</div>
			</div>			
		</div>

	</div>

	<?php if( ! $Collection->_is_builtin_term( $term ) ): ?>
		<div class="collection-item__right collection-control-buttons">

			<?php 
			/**
			 *
			 * Fires before control buttons
			 *
			 * @param $term
			 * 
			 */
			do_action( 'streamtube/core/collection/control_buttons/before', $term );
			?>

			<?php printf(
				'<button type="button" data-term-id="%s" class="btn btn-collection-delete shadow-none p-1" title="%s">%s</button>',
				esc_attr( $term->term_id ),
				esc_attr__( 'Delete Collection?', 'streamtube-core' ),
				'<span class="btn__icon icon-cancel-circled text-danger"></span>'
			);?>

			<?php printf(
				'<button type="button" data-action="get_collection_term" data-params="%s" class="ajax-elm btn btn-collection-control shadow-none p-1" title="%s">%s</button>',
				esc_attr( $term->term_id ),
				esc_attr__( 'Edit Collection?', 'streamtube-core' ),
				'<span class="btn__icon icon-edit"></span>'
			);?>

			<?php echo streamtube_core_collection_button_privacy( $term->term_id ); ?>

			<?php 
			/**
			 *
			 * Fires after control buttons
			 *
			 * @param $term
			 * 
			 */
			do_action( 'streamtube/core/collection/control_buttons/after', $term );
			?>
		</div>
	<?php endif;?>

	<?php 
	/**
	 *
	 * Fires after collection item
	 *
	 * @param array $collection
	 * 
	 */
	do_action( 'streamtube/core/collection/item/after', $term );
	?>	
</li>
<?php