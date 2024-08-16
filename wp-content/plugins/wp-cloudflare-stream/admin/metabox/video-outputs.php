<?php
if( ! defined('ABSPATH' ) ){
    exit;
}

$row_count = 0;

$post_id = get_the_ID();

if( get_post_type() == 'video' ){
	$post_id   = get_post_meta( get_the_ID(), 'video_url', true );	
}

?>

<div class="metabox-wrap">

	<p class="field-group search-services">
		<?php printf(
			'<input type="text" name="search_services" class="regular-text w-100" placeholder="%s" onkeyup="searchServices()">',
			esc_attr__( 'Search services ...', 'wp-cloudflare-stream' )
		);?>
	</p>

	<p class="field-group filters d-flex gap-4">
		<label>
			<input class="search-filter" type="checkbox" data-filter="is-added" id="search-added">
			<?php esc_html_e( 'Added Services', 'wp-cloudflare-stream' );?>
		</label>

		<label>
			<input class="search-filter" type="checkbox" data-filter="is-enabled" id="search-enabled">
			<?php esc_html_e( 'Enabled Services', 'wp-cloudflare-stream' );?>
		</label>	
	</p>

</div>

<table id="list-services" class="wp-list-table striped form-table list-services">

	<thead>
		<th class="col-count">#</th>
		<td class="col-service"><?php esc_html_e( 'Service', 'wp-cloudflare-stream' );?></td>
		<td class="col-server"><?php esc_html_e( 'Server', 'wp-cloudflare-stream' );?></td>
		<td class="col-streamkey"><?php esc_html_e( 'Stream Key', 'wp-cloudflare-stream' );?></td>
		<td class="col-status"><?php esc_html_e( 'Status', 'wp-cloudflare-stream' );?></td>
		<td class="col-action"><?php esc_html_e( 'Action', 'wp-cloudflare-stream' );?></td>
	</thead>
	
	<tbody>
		
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

			$li_classes = array( 'service-item' );

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
				'<tr class="%s" data-service="%s" data-service-uid="%s">', 
				esc_attr( join( ' ', $li_classes ) ),
				esc_attr( $service['name'] ),
				esc_attr( $data['uid'] )				
			); ?>
				<th class="col-count"><?php echo $row_count; ?></th>

				<td class="col-service">
					<?php printf(
					'<p id="service-%s">%s</p>',
					sanitize_html_class( $service_name ),
					$service['name']
					);?>

					<?php printf(
						'<input type="hidden" name="service" value="%s">',
						esc_attr( $service_name )
					);?>

				</td>

				<td class="col-server">
					<?php printf(
						'<select class="regular-text" name="server" %s>',
						$data['uid'] ? 'disabled' : ''
					);?>
						<?php foreach ( WP_Cloudflare_Stream_Service::get_server_options($service['servers']) as $key => $value): ?>
							<?php printf(
								'<option value="%s" %s>%s</option>',
								esc_attr( $key ),
								selected( $data['url'], $key, false ),
								esc_html( $value )
							);?>
						<?php endforeach; ?>
					</select>
				</td>

				<td class="col-streamkey">
					<?php printf(
						'<input type="text" name="streamkey" value="%s" class="regular-text" %s>',
						esc_attr( $data['streamkey'] ),
						$data['uid'] ? 'disabled' : ''
					);?>
				</td>

				<td class="col-status">
					<?php printf(
						'<div class="destination-status"><span class="spinner %s"></span></div>',
						$data['uid'] ? 'is-active' : ''
					) ?>
				</td>

				<td class="col-action">
					<div class="buttons d-flex gap-4">

						<?php printf(
							'<button type="button" class="button button-%s button-small button-ad-server">%s</button>',
							$data['uid'] ? 'primary' : 'secondary',
							$data['uid'] ? esc_html__( 'Delete', 'wp-cloudflare-stream' ) : esc_html__( 'Add', 'wp-cloudflare-stream' )
						);?>

						<?php printf(
							'<button type="button" class="button button-secondary button-small button-ed-server" data-action="%s">%s</button>',
							$data['enabled'] ? 'disable_live_output' : 'enable_live_output',
							$data['enabled'] ? esc_html__( 'Disable', 'wp-cloudflare-stream' ) : esc_html__( 'Enable', 'wp-cloudflare-stream' )
						);?>

					</div>
				</td>
			</tr>
		<?php endforeach;?>

	</tbody>

</table>

<script type="text/javascript">
	function searchServices() {
	    var filter = jQuery("input[name=search_services]").val().toUpperCase();
	    var table = jQuery("#list-services");
	    var found = 0;

	    table.find('tr.service-item').each(function() {
	        var tr = jQuery(this);
	        var text = tr.attr('data-service').toUpperCase().includes(filter);
	        tr.css('display', text ? '' : 'none');
	        found += text ? 1 : 0;
	    });

	    var notFound = `<tr class="not-found text-center"><td colspan=6><h4><?php esc_html_e('No services match your search term.', 'wp-cloudflare-stream'); ?></h4></td></tr>`;
	    table.find('tr.not-found').length === 0 ? table.append(found === 0 ? notFound : '') : null;
	    if( found !== 0 ){
	    	table.find( 'tr.not-found' ).remove();
	    }    
	}

    function searchFilter() {
        jQuery(document).on('change', 'input.search-filter', function() {
            var isChecked 	= jQuery(this).is(':checked');
            var filter 		= jQuery(this).attr( 'data-filter' );
            jQuery("#list-services tr.service-item").each(function() {
                var tr = jQuery(this);
                tr.toggleClass('d-none', isChecked && !tr.hasClass(filter));
            });
        });
    }

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
                post_id: '<?php echo esc_js( $post_id  ); ?>',
                _wpnonce: '<?php echo esc_js( wp_create_nonce( '_wpnonce' ) )?>'
            }, function(response) {
                if (response.success === false) {
                    jQuery('#list-services .destination-status').remove();
                    clearInterval(interval);
                } else {
                    response.data.forEach(function(item) {
                        var uid = item.uid;
                        var statusText = item.status ? item.status.current.state : '<?php esc_html_e('N/A', 'wp-cloudflare-stream'); ?>';
                        var badgeClass = getBadgeClass(statusText);
                        var badge = `<div class="destination-status text-capitalize badge ${badgeClass}">${statusText}</div>`;
                        jQuery(`tr[data-service-uid=${uid}] .destination-status`).replaceWith(badge);
                    });
                }
            });
        }, 5000);
    }    

    searchFilter();	
    ajaxPollOutputsStatus();
</script>