<?php

$settings = WP_Post_Location_Customizer::get_settings();

global $post;

$post_id = $post->ID;

$_post_location = WP_Post_Location_Post::get_location( $post_id );

echo WP_Post_Location_Shortcode::_the_map( array(
	'height'			=>	'400px',
	'search_location'	=>	true,
	'edit_mode'			=>	true,
	'locations'			=>  WP_Post_Location_Post::get_post_locations( compact( 'post_id' ) )
) );

?>
<table class="form-table table mb-0">

	<tbody>

		<tr>
			<th style="width: 120px" scope="col"><?php esc_html_e( 'Longitude', 'wp-post-location' )?></th>
			<td>
				<span id="field-longitude"><?php echo $_post_location['lng']; ?></span>
			</td>
		</tr>

		<tr>
			<th scope="col"><?php esc_html_e( 'Latitude', 'wp-post-location' )?></th>
			<td>
				<span id="field-latitude"><?php echo $_post_location['lat']; ?></span>
			</td>
		</tr>

		<tr>
			<th scope="col"><?php esc_html_e( 'Zoom Level', 'wp-post-location' )?></th>
			<td>
				<span id="field-zoom"><?php echo $_post_location['zoom']; ?></span>
			</td>
		</tr>

		<tr>
			<th scope="col"><?php esc_html_e( 'Address', 'wp-post-location' )?></th>
			<td>
				<span id="field-address"><?php echo $_post_location['address']; ?></span>
			</td>
		</tr>

		<?php if( $settings['map_provider'] == 'googlemap' ): ?>
		<tr>
			<th scope="col"><?php esc_html_e( 'North', 'wp-post-location' )?></th>
			<td>
				<span id="field-north"></span>
			</td>
		</tr>

		<tr>
			<th scope="col"><?php esc_html_e( 'South', 'wp-post-location' )?></th>
			<td>
				<span id="field-south"></span>
			</td>
		</tr>

		<tr>
			<th scope="col"><?php esc_html_e( 'East', 'wp-post-location' )?></th>
			<td>
				<span id="field-east"></span>
			</td>
		</tr>

		<tr>
			<th scope="col"><?php esc_html_e( 'West', 'wp-post-location' )?></th>
			<td>
				<span id="field-west"></span>
			</td>
		</tr>
		<?php endif;?>			

	</tbody>

</table>		
<?php