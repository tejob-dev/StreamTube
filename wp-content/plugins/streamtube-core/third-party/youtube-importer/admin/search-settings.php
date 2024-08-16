<?php

global $post;

$importer = streamtube_core()->get()->yt_importer;

$settings = $importer->admin->get_settings( $post->ID );
?>
<table class="form-table yt-settings-table">
	
	<tbody>

		<tr>
			<th scope="row">
				<label for="enable">
					<?php esc_html_e( 'Enable', 'streamtube-core' );?>
				</label>
			</th>

			<td>
				<label for="enable">
					<?php printf(
						'<input name="yt_importer[enable]" type="checkbox" id="enable" %s>',
						checked( 'on', $settings['enable'], false )
					);?>
					<?php esc_html_e( 'Enable this importer', 'streamtube-core' );?>
				</label>
			</td>			
		</tr>

		<tr>
			<th scope="row">
				<label for="apikey">
					<?php esc_html_e( 'Youtube API Key', 'streamtube-core' );?>
				</label>
			</th>

			<td>
				<?php printf(
					'<input name="yt_importer[apikey]" type="text" id="apikey" value="%s" class="regular-text">',
					esc_attr( $settings['apikey'] )
				);?>
				<p class="description">
					<?php printf(
						esc_html__( 'Set a custom API key or use default key from %s.', 'streamtube-core' ),
						sprintf( 
							'<a href="%s">%s</a>',
							esc_url( admin_url( '/customize.php?autofocus[section]=youtube_importer' ) ),
							esc_html__( 'YouTube Importer', 'streamtube-core' )
						)
					);?>
				</p>				
			</td>			
		</tr>	
		
		<tr>
			<th scope="row">
				<label for="q">
					<?php esc_html_e( 'Search', 'streamtube-core' );?>
				</label>
			</th>

			<td>
				<?php printf(
					'<input name="yt_importer[q]" type="text" id="q" value="%s" class="regular-text">',
					esc_attr( $settings['q'] )
				);?>
				<p class="description">
					<?php esc_html_e( 'Search videos with keyword.', 'streamtube-core' );?>
				</p>
			</td>			
		</tr>

		<tr>
			<th scope="row">
				<label for="searchIn">
					<?php esc_html_e( 'Search In', 'streamtube-core' );?>
				</label>
			</th>

			<td>
				<select name="yt_importer[searchIn]" id="searchIn" class="regular-text">

					<?php foreach ( $importer->options->get_search_ins() as $key => $value ) {
						printf(
							'<option %s value="%s">%s</option>',
							selected( $key, $settings['searchIn'], false ),
							esc_attr( $key ),
							esc_html( $value )
						);
					}?>

				</select>

				<p class="description">
					<?php esc_html_e( 'Restricts a search to broadcast events, support Video type only.', 'streamtube-core' );?>
				</p>
			</td>			
		</tr>		

		<tr>
			<th scope="row">
				<label for="channelId">
					<?php esc_html_e( 'Channel/Playlist ID', 'streamtube-core' );?>
				</label>
			</th>

			<td>
				<?php printf(
					'<input name="yt_importer[channelId]" type="text" id="channelId" value="%s" class="regular-text">',
					esc_attr( $settings['channelId'] )
				);?>
			</td>			
		</tr>		

		<tr>
			<th scope="row">
				<label for="maxResults">
					<?php esc_html_e( 'Max Results', 'streamtube-core' );?>
				</label>
			</th>

			<td>
				<?php printf(
					'<input name="yt_importer[maxResults]" type="number" id="maxResults" value="%s" class="regular-text">',
					esc_attr( $settings['maxResults'] )
				);?>
				<p class="description">
					<?php esc_html_e( 'Max Results per request.', 'streamtube-core' );?>
				</p>
			</td>			
		</tr>

		<tr>
			<th scope="row">
				<label for="type">
					<?php esc_html_e( 'Type', 'streamtube-core' );?>
				</label>
			</th>

			<td>
				<select name="yt_importer[type]" id="type" class="regular-text">

					<?php foreach ( $importer->options->get_types() as $key => $value ) {
						printf(
							'<option %s value="%s">%s</option>',
							selected( $key, $settings['type'], false ),
							esc_attr( $key ),
							esc_html( $value )
						);
					}?>

				</select>
			</td>			
		</tr>

		<tr>
			<th scope="row">
				<label for="eventType">
					<?php esc_html_e( 'Event Type', 'streamtube-core' );?>
				</label>
			</th>

			<td>
				<select name="yt_importer[eventType]" id="eventType" class="regular-text">

					<?php foreach ( $importer->options->get_event_type() as $key => $value ) {
						printf(
							'<option %s value="%s">%s</option>',
							selected( $key, $settings['eventType'], false ),
							esc_attr( $key ),
							esc_html( $value )
						);
					}?>

				</select>

				<p class="description">
					<?php esc_html_e( 'Restricts a search to broadcast events, support Video type only.', 'streamtube-core' );?>
				</p>
			</td>			
		</tr>		

		<tr>
			<th scope="row">
				<label for="videoType">
					<?php esc_html_e( 'Video Type', 'streamtube-core' );?>
				</label>
			</th>

			<td>
				<select name="yt_importer[videoType]" id="videoType" class="regular-text">

					<?php foreach ( $importer->options->get_video_types() as $key => $value ) {
						printf(
							'<option %s value="%s">%s</option>',
							selected( $key, $settings['videoType'], false ),
							esc_attr( $key ),
							esc_html( $value )
						);
					}?>

				</select>
			</td>			
		</tr>

		<tr>
			<th scope="row">
				<label for="safeSearch">
					<?php esc_html_e( 'Safe Search', 'streamtube-core' );?>
				</label>
			</th>

			<td>
				<select name="yt_importer[safeSearch]" id="safeSearch" class="regular-text">

					<?php foreach ( $importer->options->get_safe_search() as $key => $value ) {
						printf(
							'<option %s value="%s">%s</option>',
							selected( $key, $settings['safeSearch'], false ),
							esc_attr( $key ),
							esc_html( $value )
						);
					}?>

				</select>
			</td>			
		</tr>

		<tr>
			<th scope="row">
				<label for="videoDefinition">
					<?php esc_html_e( 'Video Definition', 'streamtube-core' );?>
				</label>
			</th>

			<td>
				<select name="yt_importer[videoDefinition]" id="videoDefinition" class="regular-text">

					<?php foreach ( $importer->options->get_video_definition() as $key => $value ) {
						printf(
							'<option %s value="%s">%s</option>',
							selected( $key, $settings['videoDefinition'], false ),
							esc_attr( $key ),
							esc_html( $value )
						);
					}?>

				</select>
			</td>			
		</tr>

		<tr>
			<th scope="row">
				<label for="videoDimension">
					<?php esc_html_e( 'Video Dimension', 'streamtube-core' );?>
				</label>
			</th>

			<td>
				<select name="yt_importer[videoDimension]" id="videoDimension" class="regular-text">

					<?php foreach ( $importer->options->get_video_dimension() as $key => $value ) {
						printf(
							'<option %s value="%s">%s</option>',
							selected( $key, $settings['videoDimension'], false ),
							esc_attr( $key ),
							esc_html( $value )
						);
					}?>

				</select>
			</td>			
		</tr>

		<tr>
			<th scope="row">
				<label for="videoDuration">
					<?php esc_html_e( 'Video Duration', 'streamtube-core' );?>
				</label>
			</th>

			<td>
				<select name="yt_importer[videoDuration]" id="videoDuration" class="regular-text">

					<?php foreach ( $importer->options->get_video_duration() as $key => $value ) {
						printf(
							'<option %s value="%s">%s</option>',
							selected( $key, $settings['videoDuration'], false ),
							esc_attr( $key ),
							esc_html( $value )
						);
					}?>

				</select>
			</td>			
		</tr>

		<tr>
			<th scope="row">
				<label for="videoLicense">
					<?php esc_html_e( 'Video License', 'streamtube-core' );?>
				</label>
			</th>

			<td>
				<select name="yt_importer[videoLicense]" id="videoLicense" class="regular-text">

					<?php foreach ( $importer->options->get_video_license() as $key => $value ) {
						printf(
							'<option %s value="%s">%s</option>',
							selected( $key, $settings['videoLicense'], false ),
							esc_attr( $key ),
							esc_html( $value )
						);
					}?>

				</select>
			</td>			
		</tr>		

		<tr>
			<th scope="row">
				<label for="publishedAfter">
					<?php esc_html_e( 'Published After', 'streamtube-core' );?>
				</label>
			</th>

			<td>
				<?php printf(
					'<input name="yt_importer[publishedAfter]" type="datetime-local" id="publishedAfter" value="%s" class="regular-text">',
					esc_attr( $settings['publishedAfter'] )
				);?>
				<p class="description">
					<?php esc_html_e( 'Only contain resources created at or after the specified time.', 'streamtube-core' );?>
				</p>
			</td>			
		</tr>

		<tr>
			<th scope="row">
				<label for="publishedBefore">
					<?php esc_html_e( 'Published Before', 'streamtube-core' );?>
				</label>
			</th>

			<td>
				<?php printf(
					'<input name="yt_importer[publishedBefore]" type="datetime-local" id="publishedBefore" value="%s" class="regular-text">',
					esc_attr( $settings['publishedBefore'] )
				);?>
				<p class="description">
					<?php esc_html_e( 'Only contain resources created before or at the specified time.', 'streamtube-core' );?>
				</p>
			</td>			
		</tr>

		<tr>
			<th scope="row">
				<label for="regionCode">
					<?php esc_html_e( 'Region Code', 'streamtube-core' );?>
				</label>
			</th>

			<td>
				<?php printf(
					'<input name="yt_importer[regionCode]" type="text" id="regionCode" value="%s" class="regular-text">',
					esc_attr( $settings['regionCode'] )
				);?>
				<p class="description">
					<?php printf(
						esc_html__( 'Return search results for videos that can be viewed in the specified country. The parameter value is an %s country code.', 'streamtube-core' ),
						'<a target="_blank" href="http://www.iso.org/iso/country_codes/iso_3166_code_lists/country_names_and_code_elements.htm">ISO 3166-1 alpha-2</a>'
					);?>
				</p>
			</td>			
		</tr>

		<tr>
			<th scope="row">
				<label for="relevanceLanguage">
					<?php esc_html_e( 'Relevance Language', 'streamtube-core' );?>
				</label>
			</th>

			<td>
				<?php printf(
					'<input name="yt_importer[relevanceLanguage]" type="text" id="relevanceLanguage" value="%s" class="regular-text">',
					esc_attr( $settings['relevanceLanguage'] )
				);?>
				<p class="description">
					<?php printf(
						esc_html__( 'Return search results that are most relevant to the specified language. The parameter value is typically an %s language code.', 'streamtube-core' ),
						'<a target="_blank" href="http://www.loc.gov/standards/iso639-2/php/code_list.php">ISO 639-1 two-letter</a>'
					);?>
				</p>
			</td>			
		</tr>

		<tr>
			<th scope="row">
				<label for="order">
					<?php esc_html_e( 'Order', 'streamtube-core' );?>
				</label>
			</th>

			<td>
				<select name="yt_importer[order]" id="order" class="regular-text">

					<?php foreach ( $importer->options->get_orders() as $key => $value ) {
						printf(
							'<option %s value="%s">%s</option>',
							selected( $key, $settings['order'], false ),
							esc_attr( $key ),
							esc_html( $value )
						);
					}?>

				</select>
			</td>			
		</tr>			

	</tbody>

</table>