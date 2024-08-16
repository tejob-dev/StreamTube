<?php
if( ! defined('ABSPATH' ) ){
    exit;
}

global $post;

$embed_privacy = $post ? get_post_meta( $post->ID, Streamtube_Core_Post::EMBED_PRIVACY, true ) : 'anywhere';

if( empty( $embed_privacy ) ){
	$embed_privacy = 'anywhere';
}

$selection = array(
	'anywhere'			=>	esc_html__( 'Anywhere', 'streamtube-core' ),
	'nowhere'			=>	esc_html__( 'Nowhere', 'streamtube-core' )
);

?>
<div class="metabox-wrap">
	<div class="field-group">
		<label>
			<?php esc_html_e( 'Where can the video be embedded?', 'streamtube-core' )?>
		</label>

		<select class="regular-text input-field" name="embed_privacy" id="embed_privacy">
			<?php foreach ( $selection as $key => $value ) {
				printf(
					'<option value="%s" %s>%s</option>',
					esc_attr( $key ),
					selected( $key, $embed_privacy, false ),
					esc_html( $value )
				);
			}?>
		</select>
	</div>

	<?php printf(
		'<div class="field-group group-allowed-domains %s">',
		$embed_privacy == 'anywhere' ? 'd-none' : ''
	);?>
		<label>
			<?php esc_html_e( 'Allowed Domains', 'streamtube-core' )?>
		</label>

		<?php printf(
			'<textarea class="regular-text input-field" name="%1$s" id="%1$s">%2$s</textarea>',
			'embed_allowed_domains',
			esc_textarea( get_post_meta( $post->ID, Streamtube_Core_Post::EMBED_PRIVACY_ALLOWED_DOMAINS, true ) )
		);?>

		<p class="description">
			<?php esc_html_e( 'The list of domains that will be allowed from embedding the videos, separated by a line break.', 'streamtube-core' ); ?>
		</p>

		<p class="description">
			<?php esc_html_e( 'The current domain is always allowed without settings', 'streamtube-core' ); ?>
		</p>
	</div>

	<?php printf(
		'<div class="field-group group-blocked-domains %s">',
		$embed_privacy == 'nowhere' ? 'd-none' : ''
	);?>
		<label>
			<?php esc_html_e( 'Blocked Domains', 'streamtube-core' )?>
		</label>

		<?php printf(
			'<textarea class="regular-text input-field" name="%1$s" id="%1$s">%2$s</textarea>',
			'embed_blocked_domains',
			esc_textarea( get_post_meta( $post->ID, Streamtube_Core_Post::EMBED_PRIVACY_BLOCKED_DOMAINS, true ) )
		);?>

		<p class="description">
			<?php esc_html_e( 'The list of domains that will be blocked from embedding the videos, separated by a line break.', 'streamtube-core' ); ?>
		</p>		
	</div>
</div>

<script type="text/javascript">
	jQuery( '#embed_privacy' ).on( 'change', function(e){
		var selector 	= jQuery(this);
		var form 		= selector.closest( 'form' );
		var value 		= selector.val();

		if( value == 'anywhere' ){
			form.find( '.group-allowed-domains' ).addClass( 'd-none' );
			form.find( '.group-blocked-domains' ).removeClass( 'd-none' );
		}else{
			form.find( '.group-allowed-domains' ).removeClass( 'd-none' );
			form.find( '.group-blocked-domains' ).addClass( 'd-none' );
		}
	} );
</script>
<?php