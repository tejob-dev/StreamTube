<?php
if( ! defined('ABSPATH' ) ){
    exit;
}

$row_count 	= 0;

$post_id 	= get_post_meta( streamtube_core()->get()->post->get_edit_post_id(), 'video_url', true );
?>

<div class="widget-title-wrap d-flex bg-white sticky-top border p-3">

	<div class="d-none d-sm-block group-title flex-grow-1">
		<?php
		printf(
			'<h2 class="page-title">%s</h2>',
			esc_html__( 'Live Outputs', 'wp-cloudflare-stream' )
		);
		?>        	
	</div>
	<div class="ms-md-auto search-form d-flex align-items-center gap-4">

		<div class="form-check">
			<input class="form-check-input search-filter" type="checkbox" data-filter="is-added" id="search-added">

			<label class="form-check-label" for="search-added">
				<?php esc_html_e( 'Added', 'wp-cloudflare-stream' );?>
			</label>
		</div>		

		<div class="form-check">
			<input class="form-check-input search-filter" type="checkbox" data-filter="is-enabled" id="search-enabled">

			<label class="form-check-label" for="search-enabled">
				<?php esc_html_e( 'Enabled', 'wp-cloudflare-stream' );?>
			</label>
		</div>

		<?php printf(
			'<input class="form-control outline-none shadow-none rounded-1" name="search_services" type="text" placeholder="%1$s" aria-label="%1$s" onkeyup="searchServices()">',
			esc_attr__( 'Search services ...', 'wp-cloudflare-stream'  )
		);?>
	</div>

</div>

<ul id="list-services" class="list-group list-group-flush list-unstyled list-services mb-4">

	<?php foreach ( WP_Cloudflare_Stream_Service::get_services() as $service ):  ?>

		<?php
			$row_count++;

			$service_name 	= WP_Cloudflare_Stream_Service::sanitize_service_name( $service['name'] );
			$data 			= (array)get_post_meta( $post_id, "live_output_{$service_name}", true );

			$data 			= wp_parse_args( $data, array(
				'uid'		=>	'',
				'url'		=>	'',
				'enabled'	=>	false,
				'status'	=>	'',
				'service'	=>	'',
				'streamkey'	=>	''
			) );

			$li_classes = array( 'border-bottom', 'bg-white', 'p-3', 'service-item' );

			$li_classes[] = 'service-' . sanitize_html_class( $service_name );

			if( array_key_exists( 'common', $service ) ){
				$li_classes[] = 'is-common';
			}

			if( $data['uid'] ){
				$li_classes[] = 'is-added';
			}

			if( $data['enabled'] ){
				$li_classes[] = 'is-enabled';
			}

			if( $data['status'] ){
				$li_classes[] = 'is-' . sanitize_html_class( strtolower( $data['status'] ) );
			}
		?>

		<?php printf(
			'<li class="%s" data-service="%s" data-service-uid="%s">',
			esc_attr( join( ' ', $li_classes ) ),
			esc_attr( $service['name'] ),
			esc_attr( $data['uid'] )
		)?>

			<form class="form-ajax">

				<?php
				/**
				 *
				 * Fires before service
				 *
				 * @param array $service
				 * @param string $service_name
				 * @param array $data
				 * 
				 */
				do_action( 'wp_cloudflare_stream/service/before', $service, $service_name, $data );
				?>

				<div class="collapse-head d-flex justify-content-between align-items-center py-2 gap-4">

					<span class="badge bg-secondary me-2 count">
						<?php echo $row_count; ?>
					</span>

					<?php printf(
						'<a class="h6 text-decoration-none text-body m-0 flex-grow-1" data-bs-toggle="collapse" href="#collapse-%1$s" role="button" aria-expanded="false" aria-controls="collapse-%1$s">%2$s</a>',
						sanitize_html_class( $service_name ),
						$service['name']
					);?>
			
					<div class="output-meta d-flex gap-3 ms-auto">

						<?php 
						if( $data['uid'] ){
							?>
							<div class="destination-status spinner-grow text-danger spinner-grow-sm" role="status"></div>
							<?php
						}?>

						<?php printf(
							'<div class="badge output-status">%s</div>',
							$data['uid'] ? esc_html__( 'Added', 'wp-cloudflare-stream' ) : esc_html__( 'Not added', 'wp-cloudflare-stream' )
						);?>

						<?php if( array_key_exists( 'stream_key_link' , $service ) ){
							printf(
								'<a class="go-live badge bg-danger text-decoration-none text-white" target="_blank" href="%s">%s</a>',
								esc_url( $service['stream_key_link'] ),
								esc_html__( 'Go Live', 'wp-cloudflare-stream' )
							);
						}?>

					</div>

				</div>

				<?php printf(
					'<div class="collapse-body collapse" id="collapse-%s">',
					sanitize_html_class( $service_name )
				);?>	

					<div class="mt-4">		

						<div class="row row-cols-12 row-cols-lg-6">

							<div class="flex-grow-1">
								<?php
								if( array_key_exists( 'servers', $service ) ){
									streamtube_core_the_field_control( array(
										'label'			=>	esc_html__( 'Server', 'wp-cloudflare-stream' ),
										'type'			=>	'select',
										'name'			=>	'server',
										'id'			=>	'server-' . $service_name,
										'options'		=>	WP_Cloudflare_Stream_Service::get_server_options($service['servers']),
										'current'		=>	$data['url'],
										'disabled'		=>	$data['uid'] ? true : false
									) );
								}
								?>	
							</div>

							<div class="flex-grow-1">
								<?php
								streamtube_core_the_field_control( array(
									'label'			=>	esc_html__( 'Stream key', 'wp-cloudflare-stream' ),
									'type'			=>	'password',
									'name'			=>	'streamkey',
									'id'			=>	'streamkey-' . $service_name,
									'value'			=>	$data['streamkey'],
									'disabled'		=>	$data['uid'] ? true : false
								) );
								?>	
							</div>		

						</div>

						<div class="form-submit">
							
							<input type="hidden" name="action" value="process_live_output">

							<?php printf(
								'<input type="hidden" name="service" value="%s">',
								esc_attr( $service_name )
							)?>

							<?php printf(
								'<input type="hidden" name="post_id" value="%s">',
								esc_attr( $post_id )
							)?>

							<div class="d-flex gap-4">

								<?php printf(
									'<button type="submit" class="btn-primary1 btn btn-sm btn-secondary">%s</button>',
									$data['uid'] ? esc_html__( 'Delete', 'wp-cloudflare-stream' ) : esc_html__( 'Add', 'wp-cloudflare-stream' )
								);?>

								<?php printf(
									'<button type="button" class="btn-primary2 btn btn-sm btn-%s ajax-elm" data-action="%s" data-params="%s">%s</button>',
									$data['enabled'] ? 'dark' : 'secondary',
									$data['enabled'] ? 'disable_live_output' : 'enable_live_output',
									esc_attr( json_encode( array_merge( compact( 'post_id' ), array(
										'service'	=>	$service_name
									) ) ) ),
									$data['enabled'] ? esc_html__( 'Disable', 'wp-cloudflare-stream' ) : esc_html__( 'Enable', 'wp-cloudflare-stream' )
								);?>

							</div>		

						</div>
					</div>

				</div>
				<?php
				/**
				 *
				 * Fires after service
				 *
				 * @param array $service
				 * @param string $service_name
				 * @param array $data
				 * 
				 */
				do_action( 'wp_cloudflare_stream/service/after', $service, $service_name, $data );
				?>				
			</form>
		</li>
	<?php endforeach ?>

</ul>

<script type="text/javascript">
    function getBadgeClass(statusText) {
        var badgeClass = '';
        switch (statusText) {
            case 'disconnected':
                badgeClass = 'bg-danger';
                break;
            case 'connecting':
                badgeClass = 'bg-info';
                break;
            case 'connected':
                badgeClass = 'bg-success';
                break;
            default:
                badgeClass = 'bg-secondary';
                break;
        }
        return badgeClass;
    }
    	
    function ajaxPollOutputsStatus() {
        const interval = setInterval(function() {
            jQuery.get('<?php echo esc_url(admin_url('admin-ajax.php')); ?>', {
                action: 'poll_outputs_status',
                post_id: '<?php echo esc_js($post_id); ?>',
                _wpnonce: '<?php echo esc_js( wp_create_nonce( '_wpnonce' ) )?>'
            }, function(response) {
                if (response.success === false) {
                    jQuery('#list-services .destination-status').remove();
                    jQuery.showToast(response.data[0].message, 'danger');
                    clearInterval(interval);
                } else {
                    response.data.forEach(function(item) {
                        var uid = item.uid;
                        var statusText = item.status ? item.status.current.state : '<?php esc_html_e('N/A', 'wp-cloudflare-stream'); ?>';
                        var badgeClass = getBadgeClass(statusText);
                        var badge = `<div class="destination-status text-capitalize badge ${badgeClass}">${statusText}</div>`;
                        jQuery(`li[data-service-uid=${uid}] .destination-status`).replaceWith(badge);
                    });
                }
            });
        }, 5000);
    }

    function searchServices() {
        var filter = jQuery("input[name=search_services]").val().toUpperCase();
        var ul = jQuery("#list-services");
        var found = 0;

        ul.find('li.service-item').each(function() {
            var li = jQuery(this);
            var text = li.attr('data-service').toUpperCase().includes(filter);
            li.css('display', text ? 'block' : 'none');
            found += text ? 1 : 0;
        });

        var notFound = `<li class="not-found text-center"><h6 class="text-muted p-4 fw-normal"><?php esc_html_e('No services match your search term.', 'wp-cloudflare-stream'); ?></h6></li>`;
        ul.find('li.not-found').length === 0 ? ul.append(found === 0 ? notFound : '') : null;

        if( found !== 0 ){
        	ul.find( 'li.not-found' ).remove();
        }
    }

    function searchFilter() {
        jQuery(document).on('change', 'input.search-filter', function() {
            var isChecked 	= jQuery(this).is(':checked');
            var filter 		= jQuery(this).attr( 'data-filter' );
            jQuery("#list-services li.service-item").each(function() {
                var li = jQuery(this);
                li.toggleClass('d-none', isChecked && !li.hasClass(filter));
            });
        });
    }

    searchFilter();
    ajaxPollOutputsStatus();
</script>
