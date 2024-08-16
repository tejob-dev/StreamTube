<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

global $post;

$settings = WP_Post_Location_Customizer::get_settings();

?>
<div class="row mt-4">
	<div class="col-lg-9">

		<?php
		$post_id 		= $post->ID;
		$post_location 	= WP_Post_Location_Post::get_post_locations( compact( 'post_id' ) );
		$_post_location = WP_Post_Location_Post::get_location( $post_id );

		$map_args = array(
			'edit_mode'			=>	true,
			'search_location'	=>	true,
			'locations'			=>  $post_location
		);

		echo WP_Post_Location_Shortcode::_the_map( $map_args );
		?>
	</div>

	<div class="col-lg-3">
		<div class="widget widget-location-details shadow-sm rounded bg-white border">
			<div class="widget-title-wrap d-flex m-0 p-3 bg-light">
				<h2 class="widget-title no-after m-0">
					<?php esc_html_e( 'Location', 'wp-post-location' );?>
				</h2>
			</div>

			<div class="widget-content">

				<table class="table mb-0">

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

				<div class="spinner position-absolute top-0 left-0 w-100 h-100 bg-white">
					<div class="position-absolute top-50 start-50 translate-middle">
						<div class="spinner-border" role="status">
							<span class="visually-hidden"><?php esc_html_e( 'Loading...', 'wp-post-location' );?></span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>	
</div>
<?php