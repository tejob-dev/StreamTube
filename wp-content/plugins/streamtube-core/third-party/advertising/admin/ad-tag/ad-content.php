<?php
/**
 *
 * The Ad Content metabox template file
 * 
 *
 * @link       https://1.envato.market/mgXE4y
 * @since      1.3
 *
 * @package    WordPress
 * @subpackage StreamTube
 * @author     phpface <nttoanbrvt@gmail.com>
 */
if( ! defined( 'ABSPATH' ) ){
    exit;
}

global $post;

$ad_tag 	= streamtube_core()->get()->advertising->ad_tag;
$options 	= $ad_tag->get_options( $post->ID );

?>
<div class="metabox-wrap">

	<!-- Ad Type -->
    <div class="field-group group-ad_server">
        <label for="ad_server"><?php esc_html_e( 'Ad Server', 'streamtube-core' ); ?></label>
        <select name="<?php echo esc_attr( $ad_tag->get_field( 'ad_server' ) )?>" id="ad_server" class="regular-text input-field">
        	<?php
        		foreach ( $ad_tag->ad_servers as $ad_server => $label ) {	
        			printf(
        				'<option %s value="%s">%s</option>',
        				selected( $options['ad_server'], $ad_server, false ),
        				esc_attr( $ad_server ),
        				esc_html( $label )
        			);
        		}
        	?>
        </select>   
    </div>

    <?php printf(
    	'<div class="groups-ad_server groups-ad_server-self_ad %s">',
    	! $options['ad_server'] || $options['ad_server'] != 'self_ad' ? 'd-none' : ''
    )?>

		<!-- Target URL -->
	    <div class="field-group">
	        <label for="ad_target_url"><?php esc_html_e( 'Target URL', 'streamtube-core' ); ?></label>
	        
	        <?php printf(
	            '<input type="url" name="%s" id="ad_target_url" class="regular-text input-field" value="%s">',
	            esc_attr( $ad_tag->get_field( 'ad_target_url' ) ),
	            esc_attr( $options['ad_target_url'] )
	        );?>
	    </div>

		<!-- Ad Type -->
	    <div class="field-group group-ad_type">
	        <label for="ad_type"><?php esc_html_e( 'Ad Type', 'streamtube-core' ); ?></label>
	        <select name="<?php echo esc_attr( $ad_tag->get_field( 'ad_type' ) )?>" id="ad_type" class="regular-text input-field">
	        	<?php
	        		foreach ( $ad_tag->ad_types as $ad_type => $label ) {	
	        			printf(
	        				'<option %s value="%s">%s</option>',
	        				selected( $options['ad_type'], $ad_type, false ),
	        				esc_attr( $ad_type ),
	        				esc_html( $label )
	        			);

	        		}
	        	?>
	        </select>
	    </div>

	    <?php printf(
	    	'<div class="field-groups groups-ad_type ad_type-nonlinear %s">',
	    	$options['ad_type'] != 'nonlinear' ? 'd-none' : ''
	    );?>
	    	<div class="field-group">
				<label for="ad_image_id"><?php esc_html_e( 'Image', 'streamtube-core' );?></label>
		        
		        <?php printf(
		            '<input type="text" name="%s" id="ad_image_id" class="regular-text input-field" value="%s">',
		            esc_attr( $ad_tag->get_field( 'ad_image_id' ) ),
		            esc_attr( $options['ad_image_id'] )
		        );?>

		        <button id="upload-file" type="button" class="button button-primary button-upload" data-media-type="image" data-media-source="id">
		            <?php esc_html_e( 'Upload an image', 'streamtube-core' );?>
		        </button>

	    	</div>

	    	<div class="field-group">
				<label for="ad_image_position"><?php esc_html_e( 'Image Position', 'streamtube-core' );?></label>
		        
		        <select name="<?php echo esc_attr( $ad_tag->get_field( 'ad_image_position' ) )?>" id="ad_image_position" class="regular-text input-field">
		        	<?php
		        		foreach ( $ad_tag->ad_image_positions as $position => $label ) {	
		        			printf(
		        				'<option %s value="%s">%s</option>',
		        				selected( $options['ad_image_position'], $position, false ),
		        				esc_attr( $position ),
		        				esc_html( $label )
		        			);

		        		}
		        	?>
		        </select>

	    	</div>	    	
	    </div>

		<!-- Duration -->
	    <div class="field-group">
	        <label for="ad_duration"><?php esc_html_e( 'Duration', 'streamtube-core' ); ?></label>
	        
	        <?php printf(
	            '<input type="text" name="%s" id="ad_duration" class="regular-text input-field" value="%s">',
	            esc_attr( $ad_tag->get_field( 'ad_duration' ) ),
	            esc_attr( $options['ad_duration'] )
	        );?>
	        <p class="description">
	        	<?php printf(
	        		esc_html__( 'Ad duration, e.g: %s', 'streamtube-core' ),
	        		'<strong>00:00:05</strong>'
	        	);?>
	        </p>
	    </div>	    

	    <div class="field-groups groups-ad_type ad_type-linear">    	

	    	<?php foreach ( $ad_tag->ad_video_res as $video_res => $res_text ): ?>

		    	<div class="field-group">
			        <?php printf(
			        	'<label for="%s">%s</label>',
			        	esc_attr( $video_res ),
			        	esc_html( $res_text )
			        );?>

			        <div class="field-block">
			        
				        <?php printf(
				            '<input type="text" name="%s" id="%s" class="regular-text input-field" value="%s">',
				            esc_attr( $ad_tag->get_field( 'ad_'. $video_res ) ),
				            esc_attr( $video_res ),
				            esc_attr( $options[ 'ad_'. $video_res ] )
				        );?>

				        <button id="upload-file" type="button" class="button button-primary button-upload" data-media-type="video" data-media-source="id">
				            <?php esc_html_e( 'Upload a video', 'streamtube-core' );?>
				        </button>				        

			    	</div>

			        <p class="description">
			            <?php printf(
			            	esc_html__( 'Upload a %s MP4 file, 10 MB (maximum), 30 FPS (maximum)', 'streamtube-core' ),
			            	'<strong>'. $res_text .'</strong>'
			            );?>
			        </p>

		    	</div>

	    	<?php endforeach ?>
	    </div>

		<!-- SkipOffset -->
	    <div class="field-group">
	        <label for="ad_skipoffset"><?php esc_html_e( 'Skippable', 'streamtube-core' ); ?></label>
	        
	        <?php printf(
	            '<input type="text" name="%s" id="ad_skipoffset" class="regular-text input-field" value="%s">',
	            esc_attr( $ad_tag->get_field( 'ad_skipoffset' ) ),
	            esc_attr( $ad_tag->verify_time_offset( $options['ad_skipoffset'] ) )
	        );?>
	        <p class="description">
	        	<?php printf(
	        		esc_html__( 'Set skippable offset, e.g: %s or %s, leave blank for non-skippable', 'streamtube-core' ),
	        		'<strong>00:00:05</strong>',
	        		'<strong>5</strong>'
	        	);?>
	        </p>
	    </div>	    

	</div><!--.type-embed-->

    <?php printf(
    	'<div class="groups-ad_server groups-ad_server-vast %s">',
    	$options['ad_server'] != 'vast' ? 'd-none' : ''
    )?>

		<!-- adTag URL -->
	    <div class="field-group">
	        <label for="ad_adtag_url"><?php esc_html_e( 'Ad Tag URL or VAST/VMAP XML Content', 'streamtube-core' ); ?></label>
	        
	        <?php printf(
	            '<textarea rows="10" name="%s" id="ad_adtag_url" class="regular-text input-field">%s</textarea>',
	            esc_attr( $ad_tag->get_field( 'ad_adtag_url' ) ),
	            esc_textarea( trim( $options['ad_adtag_url'] ) )
	        );?>
	        <p class="description">
	            <?php printf(
	            	esc_html__( 'Put the AdTag URL or VAST/VMAP XML content from Google Ad Manager, %s network, or any %s ad server.', 'streamtube-core' ),
	            	'<a target="_blank" href="https://www.google.com/adsense"><strong>'. esc_html__( 'Google AdSense', 'streamtube-core' ) .'</strong></a>',
	            	'<a target="_blank" href="https://www.iab.com/guidelines/digital-video-ad-serving-template-vast/"><strong>'. esc_html__( 'VAST-compliant', 'streamtube-core' ) .'</strong></a>'
	            );?>
	        </p>

	        <p>
		        <?php printf(
		        	'<button type="button" class="button button-primary w-100" id="import_vast" data-button-text="%1$s">%1$s</button>',
		        	esc_html__( 'Import Ad from AdTag URL', 'streamtube-core' )
		        );?>        
	    	</p>
	    </div>		
	</div>

	<?php wp_nonce_field( $ad_tag::NONCE, $ad_tag::NONCE ); ?>

</div>