<?php
if( ! defined('ABSPATH' ) ){
    exit;
}

$embed_privacy = $post ? get_post_meta( $post->ID, Streamtube_Core_Post::EMBED_PRIVACY, true ) : 'anywhere';

if( empty( $embed_privacy ) ){
	$embed_privacy = 'anywhere';
}
?>

<form class="form-ajax">

    <div class="widget-title-wrap d-flex bg-white sticky-top border p-3">

        <div class="d-none d-sm-block group-title flex-grow-1">
            <h2 class="page-title">
                <?php esc_html_e( 'Embedding Privacy', 'streamtube-core' ); ?>
            </h2>
        </div>

        <div class="ms-md-auto">
            <button type="submit" name="update" class="btn btn-primary px-3">
                <span class="btn__icon icon-floppy"></span>
                <span class="btn__text">
                    <?php esc_html_e( 'Update', 'streamtube-core' ); ?>
                </span>
            </button>
        </div>
    </div>    

    <input type="hidden" name="action" value="update_embed_privacy">

    <?php printf(
        '<input type="hidden" name="post_ID" value="%s">',
        $post ? $post->ID : '0'
    );?>

	<?php
	streamtube_core_the_field_control( array(
	    'label'         =>  esc_html__( 'Where can the video be embedded?', 'streamtube-core' ),
	    'type'          =>  'select',
	    'name'          =>  'embed_privacy',
	    'current'       =>  $embed_privacy,
	    'options'		=>	array(
			'anywhere'			=>	esc_html__( 'Anywhere', 'streamtube-core' ),
			'nowhere'			=>	esc_html__( 'Nowhere', 'streamtube-core' )
	    )
	) );

	streamtube_core_the_field_control( array(
	    'label'         =>  esc_html__( 'Allowed Domains', 'streamtube-core' ),
	    'field_class'	=>	'form-control input-field textarea-lg',
	    'wrap_class'	=>	$embed_privacy == 'anywhere' ? 'd-none' : '',
	    'type'          =>  'textarea',
	    'name'          =>  'embed_allowed_domains',
	    'value'			=>  $post ? get_post_meta( $post->ID, Streamtube_Core_Post::EMBED_PRIVACY_ALLOWED_DOMAINS, true ) : '',
	    'description'	=>	esc_html__( 'The list of domains that will be allowed from embedding the videos, separated by a line break.', 'streamtube-core' )
	) );

	streamtube_core_the_field_control( array(
	    'label'         =>  esc_html__( 'Blocked Domains', 'streamtube-core' ),
	    'field_class'	=>	'form-control input-field textarea-lg',
	    'wrap_class'	=>	$embed_privacy == 'nowhere' ? 'd-none' : '',
	    'type'          =>  'textarea',
	    'name'          =>  'embed_blocked_domains',
	    'value'			=>  $post ? get_post_meta( $post->ID, Streamtube_Core_Post::EMBED_PRIVACY_BLOCKED_DOMAINS, true ) : '',
	    'description'	=>	esc_html__( 'The list of domains that will be blocked from embedding the videos, separated by a line break.', 'streamtube-core' )
	) );	
	?>

</form>
<script type="text/javascript">
	jQuery( '#embed_privacy' ).on( 'change', function(e){
		var selector 	= jQuery(this);
		var form 		= selector.closest( 'form' );
		var value 		= selector.val();

		if( value == 'anywhere' ){
			form.find( '.field-embed_allowed_domains' ).addClass( 'd-none' );
			form.find( '.field-embed_blocked_domains' ).removeClass( 'd-none' );
		}else{
			form.find( '.field-embed_allowed_domains' ).removeClass( 'd-none' );
			form.find( '.field-embed_blocked_domains' ).addClass( 'd-none' );
		}
	} );
</script>
<?php