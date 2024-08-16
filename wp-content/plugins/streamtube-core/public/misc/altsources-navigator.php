<?php
/**
 *
 * The Alt Sources Navigator
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

global $streamtube;

$url = get_permalink();

$sources = $streamtube->get()->post->get_altsources( get_the_ID() );

if( ! $sources ){
	return;
}

$current = isset( $_GET['source_index'] ) ? (int)$_GET['source_index'] : 0;
?>
<div id="sources-navigator" class="sources-navigator">
	<button 
		class="btn shadow-none d-flex align-items-center" 
		type="button" 
		data-bs-toggle="modal" 
		data-bs-target="#sources-navigator-modal">
		<span class="btn__icon icon-server"></span>
		<span class="btn__text small text-secondary">
			<?php esc_html_e( 'Sources', 'streamtube-core' );?>
		</span>
	</button>		
	<div class="modal fade" id="sources-navigator-modal" tabindex="-1" aria-labelledby="sources-navigator-modal-label" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="sources-navigator-modal-label">
						<?php esc_html_e( 'Choose a source', 'streamtube-core' ); ?>
					</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<ul class="list-unstyled navbar-nav multi-source-list">
						
						<?php for ( $i = 0; $i < count( $sources ); $i++ ) : ?>

							<?php

							$label = $sources[$i]['label'];

							if( empty( $label ) ){
								$label = sprintf( esc_html__( '#Label %s', 'streamtube-core' ), $i );
							}

							/**
							 * Filter the label
							 */
							$label = apply_filters( 'streamtube/core/altsource/label', $label, $sources[$i], $i );

							if( strpos( $label , '%s' ) ){
								$label = sprintf( $label, $i );
							}

							if( $i > 0 ){
								$url = add_query_arg( array(
									'source_index'	=>	$i
								), $url );
							}

							/**
							 * Filter the url
							 */
							$url = apply_filters( 'streamtube/core/altsource/url', $url, $sources[$i], $i );

							if( isset( $_GET['list'] ) ){
								$url = add_query_arg( array(
									'list'	=>	$_GET['list']
								), $url );
							}

							$_source_args = array(
								'url'	=> $url,
								'label'	=> $label
							);

							/**
							 * Filter the _source_args
							 */
							$_source_args = apply_filters( 'streamtube/core/altsource/source_args', $_source_args, $i );
							?>
							
							<li class="position-relative">
								<?php printf(
									'<a class="%s position-relative p-2 px-3 nav-link text-body" href="%s"><span class="icon dot"></span><span class="source-label">%s</span></a>',
									$current == $i ? 'active' : '',
									$_source_args['url'],
									$_source_args['label']
								);?>
							</li>

						<?php endfor; ?>

					</ul>
				</div>
			</div>
		</div>
	</div>	
</div>
<?php

//print_r( $sources );
