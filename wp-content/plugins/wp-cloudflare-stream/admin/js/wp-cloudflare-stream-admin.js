(function( $ ) {
	'use strict';

	/**
	 *
	 * Bulk Update Data button handler
	 * 
	 */
	$( document ).on( 'click', 'button#cloudflare-bulk-update-data', function(e){
		e.preventDefault();
		const button 	= $(this);
		const form 	= button.closest( 'form' );
		const list = $( '#updated-list' );

		button
		.html( '<span class="spinner is-active"></span>' + button.attr( 'data-text2' ) )
		.addClass( 'disabled' );

		$.post( wp_cloudflare_stream.ajax_url, {
			action: 'cloudflare_bulk_update_data',
			_wpnonce : wp_cloudflare_stream._wpnonce
		}, function( response ){

			list.removeClass( 'd-none' );

			if( response.success ){
				const data = response.data;

				$.each( data, function( key, value ) {
					if( value.uid ){

						var item = `<li><strong><a target="_blank" href="${value.parent_url}">${value.message}</a></strong></li>`;

						list
						.append(item)
						.animate({
						    scrollTop: list.get(0).scrollHeight
						}, 500);
					}
				});
				button.trigger( 'click' );
			}else{
				list.append( `<li class="end"><strong style="color: green">${response.data.message}</strong></li>` );

				button.html( button.attr( 'data-text1' ) ).removeClass( 'disabled' );
			}
		} );
	} );

	$( document ).on( 'click', 'button#cloudflare-revoke-tokens', function(e){
		e.preventDefault();

		const button = $(this);

		button.attr( 'disabled', 'disabled' ).next( '.alert' ).remove();;

		$.post( wp_cloudflare_stream.ajax_url, {
			action: 'cloudflare_revoke_tokens',
			_wpnonce : wp_cloudflare_stream._wpnonce
		}, function( response ){

			if( response.success == false ){
				button.after( `<p class="alert" style="color: red">${response.data[0].message}</p>` );
			}else{
				button.after( `<p class="alert" style="color: green">${response.data.message}</p>` );
			}

			button.removeAttr( 'disabled' );
		});
	} );

	/**
	 *
	 * Upload file button handler
	 * @since  1.0.0
	 * 
	 */
	$( document ).on( 'click', 'button.button-upload-image', function(e){
		e.preventDefault();

		var button 		= $(this);

		var frame;
		
		// If the media frame already exists, reopen it.
		if ( frame ) {
			frame.open();
			return;
	    }
		
		// Create a new media frame
		frame = wp.media({	
			library: { type: 'image' },
			multiple: false
		});	
		
		 // When an video is selected in the media frame...
	    frame.on( 'select', function() {
	    	var attachment = frame.state().get('selection').first().toJSON();
	    	var url				=	attachment.url;

	    	button
	    	.parent()
	    	.find( 'input' )
	    	.val( url );

	    });
	    
    	 // Finally, open the modal on click
        frame.open();		
	});

	/**
	 *
	 * @since  1.0.0
	 * 
	 */
	$( document ).on( 'click', 'button.button-cloudflare-sync', function(e){
		e.preventDefault();

		var button 			= $(this);
		var attachmentId 	= button.attr( 'data-attachment-id' );

		$.ajax( {
			url 		: 	wp_cloudflare_stream.ajax_url,
			method 		: 	'POST',
			beforeSend: function ( xhr ) {
				button.attr( 'disabled', 'disabled' );

				button.next( '.alert' ).remove();
			},
			data:{
				'action' : 'sync_cloudflare_upload',
				'attachment_id' : attachmentId
			}
		} ).done( function ( response ) {
			button.removeAttr( 'disabled' )

			if( ! response.success ){
				button.after( '<p class="alert" style="color: red">'+ response.data[0].message +'</p>' );
			}else{
				button.html( response.data.message );
			}
		} );
	});	

	/**
	 *
	 * Install Upload Webhook button handler
	 * @since  1.0.0
	 * 
	 */
	$( document ).on( 'click', 'button#cloudflare-install-upload-webhook', function(e){
		e.preventDefault();

		var button = $(this);

		$.ajax( {
			url 		: 	wp_cloudflare_stream.ajax_url,
			method 		: 	'POST',
			beforeSend: function ( xhr ) {
				button.attr( 'disabled', 'disabled' );

				button.next( '.alert' ).remove();
			},
			data:{
				'action' : 'install_cloudflare_upload_webhook'
			}
		} ).done( function ( response ) {
			button.removeAttr( 'disabled' )

			if( ! response.success ){
				button.after( '<p class="alert" style="color: red">'+ response.data[0].message +'</p>' );
			}else{
				button.html( response.data.message );
			}
		} );
	});		

	$( document ).on( 'click', 'button#start_live_stream, button#close-open-live', function(e){
		e.preventDefault();

		var button 	= $(this);
		var form 	= button.closest( 'form' );
		var postId 	= button.attr( 'data-post-id' );
		var action 	= button.attr( 'data-action' );
		var name 	= $( 'input[name=post_title]' ).val();

		if( ! name ){
			name = $( 'h1.wp-block-post-title' ).html();
		}

		$.ajax( {
			url 		: 	wp_cloudflare_stream.ajax_url,
			method 		: 	'POST',
			beforeSend: function ( xhr ) {
				button
				.attr( 'disabled', 'disabled' )
				.find( '.spinner' )
				.addClass( 'is-active' );

				button.next( '.alert' ).remove();

				form.append( '<div class="start-live-stream-overlay"><span class="spinner"></span></div>' );
			},
			data:{
				'action'  		: action,
				'name'	  		: name,
				'video_id' 		: postId,
				'live_status' 	: button.attr( 'data-status' )
			} 
		} ).done( function ( response ) {

			var message = '';

			button
			.removeAttr( 'disabled' )
			.find( '.spinner' )
			.removeClass( 'is-active' );

			if( ! response.success ){
				message = response.data[0].message;
			}
			else{
				message = response.data.message;
			}

			var msg = '<div class="alert alert-'+response.success+'">';
				msg += '<p>'+ message +'</p>';
				if( response.success ){
					msg += '<button data-url="'+response.data.edit_link+'" type="button" class="close button button-small button-primary button-'+response.success+'">Close</button>';	
				}
				else{
					msg += '<button type="button" class="close button button-small button-primary button-'+response.success+'">Close</button>';
				}
			msg += '</div>';
			form.find( '.start-live-stream-overlay' ).html(msg);
		} );		
	});

	$( document ).on( 'click', '.start-live-stream-overlay .button-false', function(e){
		$(this).closest( '.start-live-stream-overlay' ).remove();
	});

	$( document ).on( 'click', '.start-live-stream-overlay .button-true', function(e){
		window.location.href = $(this).attr( 'data-url' );
	});	

	$( document ).on( 'click', '.button-ad-server', function(e){

		e.preventDefault();

		const button 		= $(this);
		const button2 		= button.next(); 		
		const tr 			= button.closest( 'tr' );
		const service 		= tr.find( 'input[name=service]' ).val();
		const server 		= tr.find( 'select[name=server]' ).val();
		const streamkey 	= tr.find( 'input[name=streamkey]' ).val();
		var postId 			= button.closest( 'form' ).find( 'input[name=post_ID]' ).val();

		if( ! postId ){
			postId = $( 'form.metabox-base-form' ).find( 'input[name=post_ID]' ).val();
		}

		button.attr(  'disabled', 'disabled');

		$.post( wp_cloudflare_stream.ajax_url, {
			action 		: 'process_live_output',
			service 	: service,
			server 		: server,
			streamkey 	: streamkey,
			post_id 	: postId,
			_wpnonce	: wp_cloudflare_stream._wpnonce
		}, function( response ){
			if( response.success == false ){
				alert( response.data[0].message );
			}else{
				if( response.data.add_new ){
					button
					.html( response.data.button )
					.removeClass( 'button-secondary' )
					.addClass( 'button-primary' );

					button2
					.html( response.data.button2 )
					.attr( 'data-action', 'disable_live_output' );

					tr.addClass( 'is-added is-enabled' )
					.attr( 'data-service-uid', response.data.data.uid );

					tr.find( '.destination-status' ).replaceWith( '<div class="destination-status"><span class="spinner is-active"></span></div>' );

					tr.find( 'select[name=server]' ).attr( 'disabled', 'disabled' );
					tr.find( 'input[name=streamkey]' ).attr( 'disabled', 'disabled' );

				}else{
					button
					.html( response.data.button )
					.addClass( 'button-secondary' )
					.removeClass( 'button-primary' );			

					tr.removeClass( 'is-added is-enabled' ).removeAttr( 'data-service-uid' );

					tr.find( '.destination-status' ).replaceWith( '<div class="destination-status"><span class="spinner"></span></div>' );

					tr.find( 'select[name=server]' ).removeAttr( 'disabled' );
					tr.find( 'input[name=streamkey]' ).removeAttr( 'disabled' );							
				}
			}

			button.removeAttr( 'disabled' );
		} );
	});

	$( document ).on( 'click', '.button-ed-server', function(e){
		e.preventDefault();

		const button 		= $(this);
		const button1 		= button.prev(); 		
		const tr 			= button.closest( 'tr' );
		const service 		= tr.find( 'input[name=service]' ).val();
		const action 		= button.attr( 'data-action' );

		var postId 			= button.closest( 'form' ).find( 'input[name=post_ID]' ).val();

		if( ! postId ){
			postId = $( 'form.metabox-base-form' ).find( 'input[name=post_ID]' ).val();
		}		

		button.attr(  'disabled', 'disabled');

		$.post( wp_cloudflare_stream.ajax_url, {
			action 		: action,
			data 		: {
				service : service,
				post_id : postId
			},
			_wpnonce	: wp_cloudflare_stream._wpnonce
		}, function( response ){

			if( response.success == false ){
				alert( response.data[0].message );
			}else{
				button
				.html( response.data.button )
				.attr( 'data-action', response.data.action );

				tr.removeClass( 'is-enabled' );
			}

			button.removeAttr( 'disabled' );
		} );	
	});

})( jQuery );
