(function( $ ) {
	'use strict';

	$(function() {

		if( $('body' ).hasClass( 'post-type-video' ) ){
			/**
			 * Auto insert generate image button
			 */
			var button = '';

			button = '<div class="metabox-wrap">';
				button += '<button type="button" id="button-generate-thumb-image" class="button button-primary">';
					button += streamtube.generate;
					button += '<span class="spinner"></span>';
				button += '</button>';
			button += '</div>';
			$( '#postimagediv' ).append( button );
		}
	});

	/**
	 *
	 * The Verify button handler
	 * 
	 */
	$( document ).on( 'click', 'button.button-verification', function(e){
		e.preventDefault();
		var button = $(this);
		var userId = button.attr( 'data-user-id' );

		button.attr( 'disabled', 'disabled' );

		$.post( streamtube.ajax_url, {
			action 		: 'verify_user',
			user_id		: userId,
			_wpnonce  	: streamtube.ajax_nonce
		}, function( response ){
			if( response.success === true ){
				button.replaceWith( response.data.button );
			}
			button.removeAttr( 'disabled' );
		} );
	});

	/**
	 *
	 * The Deactivate/Reactivate button handler
	 * 
	 */
	$( document ).on( 'click', 'button.button-deactivate', function(e){
		e.preventDefault();
		var button = $(this);
		var userId = button.attr( 'data-user-id' );

		button.attr( 'disabled', 'disabled' );

		$.post( streamtube.ajax_url, {
			action 		: 'admin_deactivate_user',
			user_id		: userId,
			_wpnonce  	: streamtube.ajax_nonce
		}, function( response ){
			if( response.success === true ){
				button.replaceWith( response.data.button );				
			}else{
				alert( response.data[0].message );
			}
			button.removeAttr( 'disabled' );
		} );
	});	
	
	/**
	 *
	 * Widget TabJS handler
	 *
	 * @since  1.0.0
	 * 
	 */
	$( document ).on( 'click', '.widget-content .widget-tabs a', function(e){

		e.preventDefault();

		var parent	=	$(this).closest( '.widget-tabs' );
		var href 	=	$(this).attr( 'href' );

		parent.find( '.nav-link' ).removeClass( 'active' );

		$(this).addClass( 'active' );
		

		parent.next().find( '.tab-pane' ).removeClass( 'active' );

		parent.next().find( href ).addClass( 'active' );

		parent.next().find( '.current-tab' ).val( href.replace( '#', '' ) );
	});

	/**
	 * Post Type selector
	 *
	 * @since  1.0.0
	 * 
	 */
	$( document ).on( 'change', '.widget-content select.post-type', function(e){

		var postType 	=	$(this).val();
		var parent 		=	$(this).closest( 'div.field-control' );

		parent.next().find( '.taxonomy' ).removeClass( 'active' );
		parent.next().find( '.taxonomy-' + postType ).addClass( 'active' );
	});

	/**
	 *
	 * Remove error class of the fields
	 *
	 * @sice 1.0.6
	 * 
	 */
	$( document ).on( 'change', '.metabox-wrap .regular-text', function(e){
		$(this).removeClass( 'error' );
	});

	/**
	 *
	 * Upload file button handler
	 * @since  1.0.0
	 * 
	 */
	$( document ).on( 'click', 'button.button-upload', function(e){
		e.preventDefault();

		var button 		= $(this);
		var mediaType 	= button.attr( 'data-media-type' );
		var mediaSource = button.attr( 'data-media-source' );

		var frame;
		
		// If the media frame already exists, reopen it.
		if ( frame ) {
			frame.open();
			return;
	    }
		
		// Create a new media frame
		frame = wp.media({	
			library: { type: mediaType },
			multiple: false  // Set to true to allow multiple files to be selected
		});	
		
		 // When an video is selected in the media frame...
	    frame.on( 'select', function() {
	    	
	    	// Get media attachment details from the frame state
	    	var attachment = frame.state().get('selection').first().toJSON();

	    	var attachment_id	=	attachment.id;
	    	var mime			=	attachment.mime;
	    	var subtype			=	attachment.subtype; // known as techorder.
	    	var url				=	attachment.url;

	    	var mediaId = '';

	    	if( mediaSource == 'url' ){
	    		mediaId = url;
	    	}
	    	else{
	    		mediaId = attachment_id;	
	    	}

	    	if( mediaType == 'image' ){

	    		var imgWrap = button
	    		.closest( '.field-group' )
				.find( '.placeholder-image' );
				imgWrap.removeClass( 'no-image' ).append( '<img src="'+mediaId+'">' );
	    	}

	    	button
	    	.closest( '.field-group' )
	    	.find( '.input-field' )
	    	.val( mediaId )
	    	.removeClass( 'error' );

	    });
	    
    	 // Finally, open the modal on click
        frame.open();		
	});

	$( document ).on( 'click', '.field-group .button-delete', function(e){
		var fieldGroup = $(this).closest( '.field-group' );
		fieldGroup.find( '.placeholder-image' ).addClass( 'no-image' );
		fieldGroup.find( 'img' ).remove();
		fieldGroup.find( '.input-field' ).val('');
	});

	/**
	 *
	 * Generate webp image
	 * 
	 */
	$( document ).on( 'click', '#button-generate-webp-image', function(e){
		e.preventDefault();

		var button = $(this);
		var mediaId = parseInt( $( '#streamtube-video-main-source' ).find( 'textarea[name=video_url]' ).val() );
		var postId = button.closest( 'form' ).find( 'input[name=post_ID]' ).val();

		if( isNaN( mediaId ) ){
			button.closest( 'form' ).find( 'textarea[name=video_url]' ).addClass( 'error' ).focus();
			button.closest( '.field-group' ).find( '.alert' ).remove();

            $('html, body').animate({
                scrollTop: $( '#streamtube-video-main-source' ).offset().top
            }, 1000 );

            button.after( '<div class="alert error">'+streamtube.cannot_generate_image+'</div>' );

			return false;
		}

		$.ajax( {
			url: streamtube.rest_url + 'streamtube/v1/generate-image',
			method: 'POST',
			beforeSend: function ( xhr ) {
				xhr.setRequestHeader( 'X-WP-Nonce', streamtube.nonce );

				button
				.attr( 'disabled', 'disabled' )
				.find( '.spinner' )
				.addClass( 'is-active' );

				button
				.closest( '.field-group' )
				.find( '.alert' )
				.remove();
			},
			data:{
				'mediaid' 		: mediaId,
				'parent'		: postId,
				'type'			: 'animated_image'
			}
		} ).done( function ( response ) {

			if( response.success == false ){
				button.after( '<div class="alert error">'+response.data[0].message+'</div>' );
			}else{
				button
				.closest( '.field-group' )
				.find( 'input[name=thumbnail_image_url_2]' )
				.val( response.data.thumbnail_url );

				button
				.closest( '.field-group' )
				.find( '.placeholder-image' )
				.append( '<img src="'+response.data.thumbnail_url+'">' );

				button
				.closest( '.field-group' )
				.find( '.placeholder-image' )
				.removeClass( 'no-image' );
			}

			button
			.removeAttr( 'disabled' )
			.find( '.spinner' )
			.removeClass( 'is-active' );

		} );

	} );

	/**
	 *
	 * Generate thumbnail image
	 * 
	 */
	$( document ).on( 'click', '#button-generate-thumb-image', function(e){
		e.preventDefault();
		var button = $(this);
		var wrapper = button.closest( '#postimagediv' );
		var mediaId = $( '#streamtube-video-main-source' ).find( 'textarea[name=video_url]' ).val();
		var postId = button.closest( 'form' ).find( 'input[name=post_ID]' ).val();

		if( mediaId == "" ){
			
			button.closest( 'form' ).find( 'textarea[name=video_url]' ).addClass( 'error' ).focus();

            $('html, body').animate({
                scrollTop: $( '#video-data' ).offset().top
            }, 1000 );

			return false;
		}

		$.ajax( {
			url: streamtube.rest_url + 'streamtube/v1/generate-image',
			method: 'POST',
			beforeSend: function ( xhr ) {
				xhr.setRequestHeader( 'X-WP-Nonce', streamtube.nonce );

				button
				.attr( 'disabled', 'disabled' )
				.find( '.spinner' )
				.addClass( 'is-active' );

				button
				.closest( '.metabox-wrap' )
				.find( '.alert' )
				.remove();				
			},
			data:{
				'mediaid'	: mediaId,
				'parent'	: postId,
				'type'		: 'image'
			}
		} ).done( function ( response ) {

			if( response.success == false ){
				var output = '';
				output += '<div class="alert error">';
					output += '<strong>'+response.data[0].code+': </strong>';
					output += response.data[0].message;
				output += '</div>';
				button.after( output );
			}

			if( response.success == true ){
				var imgTag = '<p class="hide-if-no-js"><a href="'+streamtube.admin_url+'media-upload.php?post_id='+postId+'&type=image&TB_iframe=1" id="set-post-thumbnail" class="thickbox">';
					imgTag += '<img src="'+ response.data.thumbnail_url +'">';
				imgTag += '</a></p>';

				imgTag += '<p class="hide-if-no-js"><a href="#" id="remove-post-thumbnail">'+streamtube.remove_featured_image+'</a></p>';

				wrapper.find( '.inside .hide-if-no-js' ).remove();
				wrapper.find( '.inside' ).prepend( imgTag );

				wrapper.find( '#_thumbnail_id' ).val( response.data.thumbnail_id );

			}

			button
			.removeAttr( 'disabled' )
			.find( '.spinner' )
			.removeClass( 'is-active' );
		} );
	});	

	$( document ).on( 'change', '.restrict-content-wrap #restrict_content_for', function(e){
		var value = $(this).val();

		var td = $(this).closest( 'td' );

		td.find( '.section-apply-for' ).hide();

		td.find( '#section-' + value ).show();

		if( $.inArray( value, [ 'roles', 'capabilities' ] ) !== -1 ){
			$( '#section-operator' ).show();
		}else{
			$( '#section-operator' ).hide();
		}
	});

	/**
	 *
	 * Ad Server select controler
	 * 
	 */
	$( document ).on( 'change', 'select#ad_server', function(e){
		var server = $(this).val();

		$( '.groups-ad_server' ).addClass( 'd-none' );

		$( '.groups-ad_server-' + server ).removeClass( 'd-none' );
	} );

	/**
	 *
	 * Ad Type select controler
	 * 
	 */
	$( document ).on( 'change', 'select#ad_type', function(e){
		var type = $(this).val();

		if( type == 'nonlinear' ){
			$( '.ad_type-nonlinear' ).removeClass( 'd-none' );
		}else{
			$( '.ad_type-nonlinear' ).addClass( 'd-none' );
		}

	} );

	/**
	 * ThickBox Add Ad_Tag button handler
	 */
	$( document ).on( 'click', '#button-add-ad_tag', function(e){
		e.preventDefault();

		var button 		= $(this);

		var wrapper 	= button.closest( '#add-ad-tags-wrap' );

		var adTagField 	= wrapper.find( 'select#ad_tag' );
		var adPlacement = wrapper.find( 'select#ad_placement' );
		var adPosition 	= wrapper.find( 'input#ad_position' );

		if( adTagField.val() == "" ){
			adTagField.focus();
			return false;
		}

		var adTagText 			= adTagField.find( 'option:selected' ).text();
		var adTagId 			= adTagField.val();
		var adTagType			= adTagField.find( 'option:selected' ).attr( 'data-ad-type' );

		var adPlacementText 	= adPlacement.find( 'option:selected' ).text();
		var adPlacementId 		= adPlacement.val();

		var adPositionVal 		= adPosition.val();

		var tagRow = '';

		tagRow += '<tr class="ad_tag_row" id="ad_tag_row'+adTagId+'">';

			tagRow += '<td class="ad_tag_index">#</td>';

			tagRow += '<td class="ad_tag_text">';
				tagRow += '<strong>'+adTagText+'</strong>';
				tagRow += '<input type="hidden" name="ad_schedule[ad_tags][id][]" value="'+ adTagId +'">';
				tagRow += '<input type="hidden" name="ad_schedule[ad_tags][placement][]" value="'+ adPlacementId +'">';
			tagRow += '</td>';

			tagRow += '<td class="ad_tag_type">';
				tagRow += adTagType;

				if( adPlacementId == 'preroll' ){
					tagRow += ' <strong>(start)</strong>';
				}

				if( adPlacementId == 'midroll' && adPositionVal ){
					tagRow += ' <strong>(' + adPositionVal + ')</strong>';
				}
				if( adPlacementId == 'postroll' ){
					tagRow += ' <strong>(end)</strong>';
				}

				tagRow += '<input type="text" class="regular-text field-ad_tag_position" name="ad_schedule[ad_tags][position][]" value="'+ adPositionVal +'">';				
			tagRow += '</td>';

			tagRow += '<td class="ad_tag_button">';
				tagRow += '<button type="button" class="button button-small button-delete">';
					tagRow += '<span class="dashicons dashicons-trash"></span>';
				tagRow += '</button>';
			tagRow += '</td>';
			
		tagRow += '</tr>';

		$( 'table#table-ad_tags-' + adPlacementId + ' tbody' ).append( tagRow );

		button.html( button.attr( 'data-text-added' ) );

	});

	$( document ).on( 'change', '#add-ad-tags-wrap select#ad_placement', function(e){
		var select 		= $(this);
		var adPlacement = select.val();

		if( adPlacement == 'midroll' ){
			select.closest( '.field-group' ).next().removeClass( 'd-none' );
		}else{
			select.closest( '.field-group' ).next().addClass( 'd-none' );
		}
	});

	/**
	 *
	 * Delete Ad Tag button handler
	 * 
	 */
	$( document ).on( 'click', '.table-ad_tags button.button-delete', function(e){
		if( confirm( streamtube.confirm_remove_ad ) === true ){
			$(this).closest( 'tr.ad_tag_row' ).remove();	
		}
	});

	/**
	 *
	 * VAST Importer button handler
	 *
	 */
	$( document ).on( 'click', 'button#import_vast', function(e){

		e.preventDefault();
		var button = $(this);

		$.ajax({
			url: streamtube.ajax_url,
			method: 'POST',
            data: {
                'url': $( '#ad_adtag_url' ).val(),
                'post_id' : $( '#post_ID' ).val(),
                'action': 'import_vast'
            },
            beforeSend: function() {
                button.addClass('disabled').attr( 'disabled', 'disabled');
            },            
			success: function( response ){
				if( response.success == true ){
					$( '#ad_adtag_url' ).val( response.data.ad_content );
					button.text( response.data.button );
				}else{
					alert( response.data );
					button.text( button.attr( 'data-button-text' ) );
				}

				button.removeClass('disabled').removeAttr( 'disabled' );
			}
		});
	});	

	$( document ).on( 'click', '.button-search-youtube', function(e){
		var button 		= $(this);
		var isLoadMore 	= button.hasClass( 'button-yt-next-page' ) ? true : false;
		var form 		= button.closest( 'form' );
		var formData 	= new FormData(form[0]);
		formData.append( 'action', 'youtube_search' );

		if( isLoadMore ){
			formData.append( 'next_page_token', button.attr( 'data-next-page-token' ) );
		}

		var jqxhr = $.ajax({
			url 			: streamtube.ajax_url,
			data 			: formData,
			processData 	: false,
			contentType 	: false,
			type 			: 'POST',
			beforeSend: function( jqXHR ) {
                button.addClass('disabled').attr('disabled', 'disabled');

	        	if( ! isLoadMore ){
	        		$( '#yt-search-results-container' ).html('');
	        	}
			}
		})

		.fail( function( jqXHR, textStatus, errorThrown ){
			alert( errorThrown );
		})

		.done( function( response, textStatus, jqXHR ){
			if( response.success == true ){
	        	if( isLoadMore ){
	        		$( '#yt-search-results-container' ).append( response.data );

	        		button.remove();
	        	}else{
	        		$( '#yt-search-results-container' ).html( response.data );
	        	}
        	}else{
        		$( '#yt-search-results-container' ).html( '<p class="api-error">'+ response.data[0].message +'</p>' );
        	}
		})

		.always( function( jqXHR, textStatus ){
			button.removeClass('disabled').removeAttr('disabled');
		});
	});

	$( document ).on( 'click', 'button.button-yt-import', function( e ) {
		var button 		= $(this);
		var itemId 		= button.attr( 'data-item-id' );
		var importerId 	= button.attr( 'data-importer-id' );

		var formData 	= new FormData();

		formData.append( 'action', 'youtube_import' );
		formData.append( 'item_id', itemId );
		formData.append( 'importer_id', importerId );

		var jqxhr = $.ajax({
			url 			: streamtube.ajax_url,
			data 			: formData,
			processData 	: false,
			contentType 	: false,
			type 			: 'POST',
			beforeSend: function( jqXHR ) {
                button.addClass('disabled running').attr('disabled', 'disabled');
			}
		})

		.fail( function( jqXHR, textStatus, errorThrown ){
			alert( errorThrown );
		})

		.done( function( response, textStatus, jqXHR ){
			if( response.success == false ){
				alert( response.data[0].message );
				button.removeClass('disabled').removeAttr('disabled');
			}else{

				if( response.data.posts ){
					var posts = $.map( response.data.posts, function(element,index) {return index});

					for ( var i = 0; i < posts.length; i ++ ) {
						$( '#yt-search-results-container ul' )
						.find( 'input[data-item-id="'+posts[i]+'"]' )
						.prop( 'checked', true )
						.addClass('disabled').attr('disabled', 'disabled')
						.attr( 'readonly', 'readonly' );

						$( '#yt-search-results-container ul' )
						.find( 'button[data-item-id="'+posts[i]+'"]' )
						.addClass('disabled').attr('disabled', 'disabled')
						.attr( 'readonly', 'readonly' )
						.html( response.data.message );
					}
				}
			}
		})

		.always( function( jqXHR, textStatus ){
			button.removeClass('running');
		});		
	});

	$( document ).on( 'click', 'button.button-imported-checked-item', function( e ) {
		var button 			= $(this);
		var form 			= button.closest( 'form' );
		var formData 		= new FormData(form[0]);
		var searchWrapper	=	$( '#yt-search-results' );

		if( confirm( streamtube.confirm_import_yt ) !== true ){
			return false;
		}

		formData.append( 'action', 'youtube_bulk_import' );

		var jqxhr = $.ajax({
			url 			: streamtube.ajax_url,
			data 			: formData,
			processData 	: false,
			contentType 	: false,
			type 			: 'POST',
			beforeSend: function( jqXHR ) {
                button.addClass('disabled').attr('disabled', 'disabled');
                searchWrapper.addClass( 'searching' );
                searchWrapper.find( '.notice' ).remove();
			}
		})

		.fail( function( jqXHR, textStatus, errorThrown ){
			searchWrapper.prepend( errorThrown );
		})

		.done( function( response, textStatus, jqXHR ){
			if( response.success == false ){
				searchWrapper.prepend( '<div class="notice notice-warning"><p>'+ response.data[0].message +'</p></div>' );
				button.removeClass('disabled').removeAttr('disabled');
			}else{

				if( response.data.posts ){
					var posts = $.map( response.data.posts, function(element,index) {return index});

					for ( var i = 0; i < posts.length; i ++ ) {
						$( '#yt-search-results-container ul' )
						.find( 'input[data-item-id="'+posts[i]+'"]' )
						.prop( 'checked', true )
						.addClass('disabled').attr('disabled', 'disabled')
						.attr( 'readonly', 'readonly' );

						$( '#yt-search-results-container ul' )
						.find( 'button[data-item-id="'+posts[i]+'"]' )
						.addClass('disabled').attr('disabled', 'disabled')
						.attr( 'readonly', 'readonly' )
						.html( response.data.message );
					}

					var text = streamtube.number_posts_imported;

					searchWrapper.prepend( '<div class="notice notice-success"><p>'+ text.replace( '%s', posts.length ) +'</p></div>' );
				}
			}
		})

		.always( function( jqXHR, textStatus ){
			button.removeClass('disabled').removeAttr('disabled');
			searchWrapper.removeClass( 'searching' );
		});		
	});	

	$( document ).on( 'click', 'button.button-check-all', function(e){
		$( '#yt-search-results-container li input[type=checkbox]' ).each( function( key, value ){
			if( ! $(this).attr( 'disabled' ) ){
				$(this).prop( 'checked', true );	
			}
		} );
	});	

	$( document ).on( 'click', 'button.button-uncheck-all', function(e){
		$( '#yt-search-results-container li input[type=checkbox]' ).each( function( key, value ){
			if( ! $(this).attr( 'disabled' ) ){
				$(this).prop( 'checked', false );	
			}
		} );
	});

	$( document ).on( 'click', 'button.button-yt-bulk-import', function(e){
		var button 			= $(this);
		var importerId 		= button.attr( 'data-importer-id' );
		var key 			= button.attr( 'data-key' );

		var formData 		= new FormData();

		formData.append( 'importer_id', importerId );
		formData.append( 'key', key );
		formData.append( 'action', 'youtube_cron_bulk_import' );

		var jqxhr = $.ajax({
			url 			: streamtube.ajax_url,
			data 			: formData,
			processData 	: false,
			contentType 	: false,
			type 			: 'POST',
			beforeSend: function( jqXHR ) {
                button.addClass('disabled').attr('disabled', 'disabled').html( button.attr( 'data-text-running' ) );
                button.next().remove();
			}
		})

		.fail( function( jqXHR, textStatus, errorThrown ){
			alert( errorThrown )
		})

		.done( function( response, textStatus, jqXHR ){
			if( response.success == false ){
				button.after( '<p class="alert alert-danger">'+ response.data[0].message +'</p>' );
			}else{
				button.after( '<p class="alert alert-success">'+ response.data.message +'</p>' );
				button
				.closest( '#the-list' )
				.find( 'td.last_check' )
				.html( response.data.last_check );
			}
		})

		.always( function( jqXHR, textStatus ){
			button.removeClass('disabled').removeAttr('disabled').html( button.attr( 'data-text-run' ) );
		});
	});

	$( document ).on( 'click', 'button.button-bunnycdn-sync', function(e){

		e.preventDefault();

		var button 			= $(this);
		var attachmentId 	= button.attr( 'data-attachment-id' );

		var formData 		= new FormData();

		formData.append( 'attachment_id', attachmentId );
		formData.append( 'action', 'bunnycdn_sync' );

		var jqxhr = $.ajax({
			url 			: streamtube.ajax_url,
			data 			: formData,
			processData 	: false,
			contentType 	: false,
			type 			: 'POST',
			beforeSend: function( jqXHR ) {
                button.addClass('disabled').attr('disabled', 'disabled');

                button.next( '.alert' ).remove();
			}
		})

		.fail( function( jqXHR, textStatus, errorThrown ){
			alert( errorThrown )
		})

		.done( function( response, textStatus, jqXHR ){
			if( response.success == false ){
				button.removeClass('disabled').removeAttr('disabled');
				//button.after( '<p class="alert alert-danger">'+ response.data[0].message +'</p>' );
				alert( response.data[0].message );
			}else{
				button.html( response.data.message );
			}
		});
	});

	$( document ).on( 'click', 'button.button-bunnycdn-retry', function(e){

		e.preventDefault();

		var button 			= $(this);
		var attachmentId 	= button.attr( 'data-attachment-id' );

		var formData 		= new FormData();

		formData.append( 'attachment_id', attachmentId );
		formData.append( 'action', 'bunnycdn_retry_sync' );

		var jqxhr = $.ajax({
			url 			: streamtube.ajax_url,
			data 			: formData,
			processData 	: false,
			contentType 	: false,
			type 			: 'POST',
			beforeSend: function( jqXHR ) {
                button.addClass('disabled').attr('disabled', 'disabled');
                button.next( '.alert' ).remove();
			}
		})

		.fail( function( jqXHR, textStatus, errorThrown ){
			alert( errorThrown )
		})

		.done( function( response, textStatus, jqXHR ){
			if( response.success == false ){
				button.removeClass('disabled').removeAttr('disabled');
				button.after( '<p class="alert alert-danger">'+ response.data[0].message +'</p>' );
			}else{
				button.html( response.data.message );
			}
		});
	});	

})( jQuery );
