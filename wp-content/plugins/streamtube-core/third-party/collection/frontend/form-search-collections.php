<?php
/**
 * The Search Collections template file
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
?>
<form class="form-search-collections" autocomplete="off">
	<div class="mb-3">
		<?php printf(
			'<input id="search-collection-input" name="search_collection" type="text" class="form-control form-control-sm" placeholder="%s" onkeyup="searchCollections()" autocomplete="off" value="">',
			esc_attr__( 'Search collections ...', 'streamtube-core' )
		);?>
	</div>
</form>

<script type="text/javascript">
	function searchCollections() {

	    var filter, ul, text, found = 0;

	    filter 		= jQuery( "#search-collection-input" ).val().toUpperCase();

	    ul 			= jQuery("#collection-list-<?php echo get_current_user_id(); ?>");

	    ul.find( 'li.collection-item .form-check-label' ).each(function(i){
	    	text = jQuery( this ).text().toUpperCase().indexOf(filter);
	    	if ( text > -1 ) {
	    		jQuery(this).closest( 'li' ).css( 'display', 'block' );
	    		found++;
	    	}else{
	    		jQuery(this).closest( 'li' ).css( 'display', 'none' );
	    	}
	    });

	    if( found == 0 ){
	    	var notFound = '<li class="not-found p-5 text-center text-secondary"><?php esc_html_e( 'Not found', 'streamtube-core' )?></li>';

	    	if( ul.find( 'li.not-found' ).length == 0 ){
	    		ul.append( notFound );	
	    	}
	    }else{
	    	ul.find( 'li.not-found' ).remove();
	    }
	}
</script>