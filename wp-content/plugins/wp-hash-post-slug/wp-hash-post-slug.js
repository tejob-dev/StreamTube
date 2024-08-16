(function( $ ) {
	'use strict';

	if( $( 'body' ).hasClass( 'post-type-video' ) ){
		var samplePermalink = $( '#sample-permalink a' );
		var link 			= samplePermalink.attr( 'href' );

		samplePermalink.html( link ).css( 'font-weight', '700' );

		$( '#edit-slug-buttons' ).remove();

		$( '#edit-slug-box' ).show();
	}

})( jQuery );	