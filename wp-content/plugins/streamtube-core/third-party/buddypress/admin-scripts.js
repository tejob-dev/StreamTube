(function($) {
    "use strict";

    var totalUntrackedPosts = 0;
    var trackedPosts 		= 0;
    var page 				= 1;

    $( document ).on( 'submit', 'form#activity-migration', function( event ){
    	event.preventDefault();

    	var form 		= $(this);
    	var button 		= form.find( 'button[type=submit]' );
    	var formData 	= new FormData(form[0]);
    	formData.append( 'page', page );
    	var progress 	= form.find( '.progress-wrap' );

    	return $.ajax({
    		url 			: streamtube.ajax_url,
			data 			: formData,
			processData 	: false,
			contentType 	: false,
			type 			: 'POST',
    		beforeSend: function( jqXHR ) {
    			progress.removeClass( 'd-none' );
    			button.attr( 'disabled', 'disabled' );
    		}
    	}).done( function( response, textStatus, jqXHR ){

    		if( response.success !== true ){
    			form.html( '<p>'+ response.data.message +'</p>' );
    			form.parent().removeClass( 'notice-info' ).addClass( 'notice-success' );
    			return;
    		}

    		totalUntrackedPosts = parseInt( response.data.total );

    		trackedPosts 		+= parseInt( response.data.count ); 
    		page++;

    		var percent 	 	= Math.ceil( trackedPosts*100/totalUntrackedPosts );

    		progress.find( '.progress-bar' )
    		.css( 'width', percent + '%' )
    		.html( `<span style="padding: 0 1rem;">${percent}%</span>` );

    		form.trigger( 'submit' );
    	} );
    } );

    $( document ).on( 'click', '#migrate-activity-notice .notice-dismiss', function(){
        $.post( streamtube.ajax_url,{
            action : 'dismiss_migrate_activity'
        }, function( response ){

        } );
    } );

})(jQuery);    