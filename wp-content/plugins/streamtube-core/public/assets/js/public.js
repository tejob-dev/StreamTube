(function($) {
    "use strict";

    $(function() {

		// Prevent Bootstrap dialog from blocking focusin
		document.addEventListener('focusin', (e) => {
			if (e.target.closest(".mce-container, #wp-link-wrap") !== null) {
				e.stopImmediatePropagation();
			}
		});

	    $(document).on('mouseenter mouseleave', '.bp-user-list-widget.position-fixed:not(.no-collapsed)', function (event) {
	        $(this).toggleClass('collapsed');
	    });

		$('.drm-always-visible').on('click', function (e) {
		  e.stopPropagation();
		});

    	/**
    	 * Load cropperJS
    	 */
		$( '.cropper-img' ).each( function(e){
			var me = $(this);
			me[0].addEventListener('crop', function (e) {
				me.closest( 'form' ).find( 'input[name=image_data]' ).val( JSON.stringify(e.detail) );
			});
		} );

        /**
         *
         * Attach appear event to all .jsappear elements
         *
         * @since 1.0.0
         * 
         */
        $('.jsappear').scrolling();

        /**
         * Load autosize
         * @since 1.0.0
         */
        autosize($('.autosize'));

       /**
         *
         * Theme switcher button handler
         * @since 1.0.0
         */
        $( '#theme-switcher' ).on( 'click', function( event ){

        	var path = window.location.pathname;
        	var text = '';

            var theme = $( 'html' ).attr( 'data-theme' );

            if( theme == 'dark' ){
                theme = 'light';
                $( '.custom-logo-link img' ).attr( 'src', streamtube.light_logo );
                text = streamtube.dark_mode_text;
            }
            else{
                theme = 'dark';   
                $( '.custom-logo-link img' ).attr( 'src', streamtube.dark_logo );
                text = streamtube.light_mode_text;
            }

            document.cookie = 'theme_mode=' + theme + ';path=/';

            $( 'html' ).attr( 'data-theme', theme );

            $(this).find( 'span.menu-text' ).html( text );

            $( document.body ).trigger( 'theme_mode_changed', [ theme ] );

        } );

        /**
         * Turn off/on light button handler
         */
	    $( document ).on( 'click', '#turn-off-light', function( event ){

	    	var isOn = false;

	        $('body').toggleClass( 'has-light-off' );

	        if( $('body').hasClass( 'has-light-off' ) ){
	            $( 'body' ).append( '<div id="light-off"></div>' );
	            $(this).attr( 'title', $(this).attr( 'data-off-title' ) );
	            isOn = true;
	        }
	        else{
	            $( '#light-off' ).remove();
	            $(this).attr( 'title', $(this).attr( 'data-on-title' ) );
	        }
	        
	        $( document.body ).trigger( 'turn_off_on_light', [ isOn ] );
	    });

	    /**
	     *
	     * Auto Upnext button handler
	     * 
	     */
	    $( document ).on( 'click', '#btn-up-next', function( event ){
	    	var path 		= window.location.pathname;

	    	var button 		= $(this);
	    	var isEnabled 	= button.hasClass( 'auto-next' );

	    	if( ! isEnabled ){
	    		document.cookie = 'upnext=on;path=/';
	    		button
	    		.addClass( 'auto-next' )
	    		.attr( 'title', button.attr( 'data-off-title' ) );
	    		$( document.body ).trigger( 'turn_on_upnext' );
	    	}
	    	else{
	    		document.cookie = 'upnext=off;path=/';	
	    		button
	    		.removeClass( 'auto-next' )
	    		.attr( 'title', button.attr( 'data-on-title' ) );
	    		$( document.body ).trigger( 'turn_off_upnext' );
	    	}
	    });

	    var delaySearchInput = null;

	    /**
	     * Search Input autocomplete
	     */
	    $( document ).on( 'keyup', '.search-input.autocomplete', function( event ){

	    	var field 		= $(this);
	    	var form 		= field.closest( 'form' );
	    	var data 		= form.serialize();

	    	var search 		= field.val();
	    	var searchUrl 	= streamtube.ajaxUrl + '?action=search_autocomplete&' + data;

	    	if( search == '' ){
	    		form
				.removeClass( 'searching' )
				.find( '.spinner' )
				.remove();

				return false;
	    	}

			if ( delaySearchInput != null || form.hasClass( 'searching' ) ) {
				clearTimeout(delaySearchInput);
			}

			delaySearchInput = setTimeout(function() {

				form
				.addClass( 'searching' )
				.find( 'button[type=submit]' )
				.append( $.getSpinner(false) );

				$.get( searchUrl, function( response ){

					var output = '';

					form.find( '.autocomplete-results' ).remove();

					if( response.success ){

						output += '<div class="autocomplete-results position-absolute start-0 bg-white p-3 shadow w-100">';
							output += '<div class="pt-4">';
								output += response.data;
							output += '</div>';
						output += '</div>';

						field.after( output );
					}

					form
					.removeClass( 'searching' )
					.find( '.spinner' )
					.remove();
				} );
	
			}, 500 );
	    	
	    });

	    /**
	     * Remove the Autocomplete results
	     */
	    $( document ).on( 'click' , function (event) {
	    	if ( $(event.target).closest( '#site-search' ).length === 0) {
	    		$( '.autocomplete-results' ).remove();
	    	}
	    });

        /**
         *
         * Float menu collap
         * 
         */
        $( '#btn-menu-collap' ).on( 'click', function( event ){

        	var is_collapsed = $( '#sidebar-secondary' ).hasClass( 'sidebar-collapse' ) ? true : false;

        	document.cookie = 'is_float_collapsed=' + is_collapsed + ';path=/';
        } );

        $( '.login #loginform' ).find( '.input' ).addClass( 'form-control' );

        /**
         *
         * Load slick slider
         * @since 1.0.0
         * 
         */
        $(".js-slick").not('.slick-initialized').slick();
        
        /**
         * JS playlist widget
         * @since 1.0.0
         */
         $( '.widget-videos-playlist' ).playlistBlock();      

        /**
         * Playlist Auto Up Next
         */
		window.addEventListener( 'message' , (event) => {
			if( event.data == 'PLAYLIST_UPNEXT' ){
				var playListWdiget = $( '.widget-videos-playlist' );

				if( playListWdiget.hasClass( 'up-next' ) ){

					setTimeout(function () {

						var activePost = playListWdiget.find( '.post-item.active' );

						var nextPost = activePost.next();

						if( nextPost ){
							activePost.removeClass( 'active' );

							nextPost.addClass( 'active' );

							nextPost[0].scrollIntoView({
								behavior : 'smooth',
								block : 'nearest',
								inline : 'start'
							});

							var embedUrl = nextPost.find( 'article' ).attr( 'data-embed-url' ) + '?autoplay=1&logo=0';

							playListWdiget.find( 'iframe' ).attr( 'src', embedUrl );							
						}

					}, 3000 );

				}
			}
		}, false );

		if( streamtube.has_woocommerce ){
			$.getCartTotal();
		}

		fixPlayListContentWidget();

		initTagsInput();		
	});

	$( window ).resize(function() {
		$( '.widget-videos-playlist' ).playlistBlock();

		fixPlayListContentWidget();
	});

	$( '.js-slick' ).on( 'init', function(e){
		$(this).closest( '.widget' ).find( '.preplacehoder' ).remove();
	} );

	$( window ).on( 'elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/streamtube_posts_elementor.default', function($scope, $){
			$scope.find( '.preplacehoder' ).remove();
			$scope.find('.js-slick').not('.slick-initialized').slick();
		} );
	} );

	$( window ).on( 'elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/streamtube-tax-grid.default', function($scope, $){
			$scope.find( '.preplacehoder' ).remove();
			$scope.find('.js-slick').not('.slick-initialized').slick();
		} );
	} );

	$( window ).on( 'elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/streamtube-sliding-term-menu.default', function($scope, $){
			$scope.find('.js-slick').not('.slick-initialized').slick();
		} );
	} );

	$( window ).on( 'elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/streamtube-playlist.default', function($scope, $){
			$scope.find('.widget-videos-playlist').playlistBlock();
		} );
	} );	

	$( document.body )
	.on( 'upload_video_before_send', uploadVideoBeforeSend )
	.on( 'upload_video', uploadVideo )
	.on( 'upload_video_failed', uploadVideoFailed )
	.on( 'add_video', addVideo )
	.on( 'import_embed_before_send', importEmbedBeforeSend )
	.on( 'import_embed', importEmbed )
	.on( 'live_stream_before_send', liveStreamBeforeSend )
	.on( 'live_stream', liveStream )
	.on( 'add_post', addPost )
	.on( 'update_post_before_send', updatePostBeforeSend )
	.on( 'update_post', updatePost )
	.on( 'trash_post', trashPost )
	.on( 'approve_post', approvePost )
	.on( 'reject_post', rejectPost )
	.on( 'restore_post', restorePost )
	.on( 'process_live_stream', processLiveStream )
	.on( 'process_live_output', processLiveOutput )
	.on( 'disable_live_output', disableLiveOutput )
	.on( 'enable_live_output', enableLiveOutput )
	.on( 'report_video', reportVideo )
	.on( 'update_text_tracks', updateTextTracks )
	.on( 'update_altsources', updateAltSources )
	.on( 'update_embed_privacy', updateEmbedPrivacy )
	.on( 'file_encode_done', fileEncodeDone )
	.on( 'post_comment', postComment )
	.on( 'edit_comment', editComment )
	.on( 'report_comment', reportComment )
	.on( 'remove_comment_report', removeCommentReport )
	.on( 'get_comment_to_edit', editInlineComment )
	.on( 'get_comment_to_report', reportInlineComment )
	.on( 'moderate_comment', moderateComment )
	.on( 'trash_comment', trashComment )
	.on( 'spam_comment', spamComment )
	.on( 'load_more_comments', loadMoreComments )
	.on( 'load_comments', loadComments )
	.on( 'load_comments_before_send', loadCommentsBeforeSend )
	.on( 'update_advertising', updateAdvertising )
	.on( 'update_profile', updateProfile )
	.on( 'update_social_profiles', updateSocialProfiles )
	.on( 'update_user_photo', updateUserPhoto )
	.on( 'delete_user_photo', deleteUserPhoto )
	.on( 'deactivate_account', deactivateAccount )
	.on( 'reactivate_account', reactivateAccount )
	.on( 'widget_load_more_posts', widgetLoadMoreposts )
	.on( 'load_more_users', loadMoreUsers )
	.on( 'load_more_tax_terms', loadMoreTaxTerms )
	.on( 'post_like', postLike )
	.on( 'added_to_cart', addedToCart )
	.on( 'updated_cart_totals', updatedCartTotals )
	.on( 'removed_from_cart', removedFromCart )
	.on( 'join_us', joinUs )
	.on( 'transfers_points', transfersPoints )
	.on( 'send_private_message', newMessageThread )
	.on( 'create_collection', createCollection )
	.on( 'delete_collection', deleteCollection )
	.on( 'set_post_collection', setPostCollection )
	.on( 'set_post_watch_later', setPostWatchLater )
	.on( 'set_image_collection', setImageCollection )
	.on( 'upload_collection_thumbnail_image', uploadCollectionThumbnailImage )
	.on( 'set_collection_status', setCollectionStatus )
	.on( 'get_collection_term', getCollectionTerm )
	.on( 'set_collection_activity', setCollectionActivity )
	.on( 'clear_collection', clearCollection )
	.on( 'search_videos', searchVideos )
	.on( 'search_in_collection', searchInCollection )
	.on( 'player_ended', autoUpnext );

	/**
	 *
	 * Input Tags
	 * 
	 */
	function initTagsInput(){

        $('input[data-role=tags-input]').each( function(k,v){

        	var element 	= $(this);

        	var maxTags 		= parseInt( element.attr( 'data-max-tags' ) );
        	var maxChars 		= parseInt( element.attr( 'data-max-chars' ) );
        	var freeInput 		= parseInt( element.attr( 'data-free-input' ) );
        	var autosuggest 	= parseInt( element.attr( 'data-auto-suggest' ) );

        	var tax 		= element.attr( 'data-tax' );

        	element.tagsinput({
        		freeInput	: freeInput == 1 	? true 	: false,
        		maxTags 	: isNaN( maxTags ) 	? 10 	: maxTags,
        		maxChars	: isNaN( maxChars ) ? 100 	: maxChars,
				typeaheadjs: {
					source: function (query, process) {

						if( autosuggest != 1 ){
							return false;
						}

						return $.ajax({
							url : streamtube.ajaxUrl + '?action=search_terms&taxonomy='+tax+'&search=' + query + '&_wpnonce='+streamtube._wpnonce,
							method : 'GET',
							async : false,
							success: function ( response ) {
								let terms = response.data;

						    	if( ! terms ){
						    		return false;
						    	}

						    	let _terms = [];

						    	for ( let i = 0; i < terms.length; i++ ) {
									_terms.push( terms[i].name );
						    	}

						    	process( _terms );	
							}
						});
					}
				}
        	});
        } );
	}

	/**
	 *
	 * uploadVideoBeforeSend hander
	 *
	 * Reset the progress bar and show it.
	 * 
	 * @param  string event
	 * @param  object form
	 * @param  object formData
	 *
	 * @since  1.0.0
	 * 
	 */
	function uploadVideoBeforeSend( event, form, formData, file = null ){
		form.find( '.row-info' ).remove();

		if( file === null ){
			file = formData.get( 'video_file' );	
		}

		if( file === null ){
			return;
		}

		form.find( '.drag-drop-upload' ).addClass( 'active' )
		.find( '.progress-bar' )
		.css( 'width', '0%' )
		.attr( 'aria-valuenow', '0' )
		.html( '0%' )
		.closest( '.progress-wrap' )
		.find( '.file-name' ).html( file.name );
	}

	/**
	 *
	 * uploadVideo handler
	 * 
	 * @param  string event
	 * @param  object responseData
	 * @param  string textStatus
	 * @param  object jqXHR
	 * @param  object formData
	 * @param  object form
	 *
	 * @since  1.0.0
	 * 
	 */
	function uploadVideo( event, responseData, textStatus, jqXHR, formData, form  ){

		var data = responseData.data;

		if( responseData.success == false ){
			form
			.find( '.drag-drop-upload' )
			.removeClass( 'active' )
			.find( 'input[name=video_file]' )
			.val('');	

			return $.showToast( data.message, 'danger' );
		}

		form.find( '.upload-form__group' ).replaceWith( data.form );

		$.editorInit( '_post_content' );

		form.find( 'input[name=action]' ).val( 'update_post' );
		form.find( 'button[type=submit]' ).removeClass( 'd-none' );
		form.closest( '.modal-dialog' ).addClass( 'modal-xl' ).removeClass( 'modal-lg' );
		form.closest( '.modal-content' ).find( '.modal-footer' ).removeClass( 'd-none' );
		multipleCheckboxesAction();

		initTagsInput();
	}

	function uploadVideoFailed( event, message, jqXHR, form ){

		$.showToast( message , 'danger' );

		form.find( '.drag-drop-upload' )
		.removeClass( 'active' )
		.find( 'input[name=video_file]' )
		.val('');		
	}

	function addVideo( event, responseData, textStatus, jqXHR, formData, form ){
		return uploadVideo( event, responseData, textStatus, jqXHR, formData, form );
	}

	function liveStreamBeforeSend( event, form, data ){
		form.closest( '.modal-content' )
		.find( 'button[type=submit]' )
		.addClass( 'disabled' )
		.attr( 'disabled', 'disabled' )
		.append( $.getSpinner(false) );
	}

	/**
	 *
	 * Live Stream handler
	 * 
	 */
	function liveStream( event, responseData, textStatus, jqXHR, formData, form ){
		form.closest( '.modal-content' ).find( 'button[type=submit]' )
		.removeClass( 'disabled' )
		.removeAttr( 'disabled' )
		.find( '.spinner-border' ).remove();		

		$.showToast( responseData.data.message, responseData.success == true ? 'success' : 'danger' );

		return uploadVideo( event, responseData, textStatus, jqXHR, formData, form );
	}

	/**
	 *
	 * Before import embed handler
	 * 
	 */
	function importEmbedBeforeSend( event, form, data ){
		form.closest( '.modal-content' )
		.find( 'button[type=submit]' )
		.addClass( 'disabled' )
		.attr( 'disabled', 'disabled' )
		.append( $.getSpinner(false) );		
	}

	/**
	 *
	 * Import embed handler
	 * 
	 */
	function importEmbed( event, responseData, textStatus, jqXHR, formData, form ){

		form.closest( '.modal-content' ).find( 'button[type=submit]' )
		.removeClass( 'disabled' )
		.removeAttr( 'disabled' )
		.find( '.spinner-border' ).remove();		

		$.showToast( responseData.data.message, responseData.success == true ? 'success' : 'danger' );

		return uploadVideo( event, responseData, textStatus, jqXHR, formData, form );
	}

	/**
	 * addPost handler
	 * @since 1.0.0
	 */
	function addPost( event, responseData, textStatus, jqXHR, formData, form ){
		$.showToast( responseData.data.message, responseData.success == true ? 'success' : 'danger' );

		if( responseData.success == true ){
			window.location.href = responseData.data.redirect_url;	
		}
	}	

	/**
	 *
	 * Before update post handler
	 * 
	 */
	function updatePostBeforeSend( event, form, formdata ){

		form.closest( '.modal-content' ).find( 'button[type=submit]' )
		.addClass( 'disabled' )
		.attr( 'disabled', 'disabled' )
		.append( $.getSpinner(false) );		

	}

	/**
	 * updatePost handler
	 * @since 1.0.0
	 */
	function updatePost( event, responseData, textStatus, jqXHR, formData, form ){

		var data = responseData.data;

		var post = data.post;

		if( responseData.success == true ){

			$.showToast( responseData.data.message1, 'success' );

			if( data.quick_update == false ){
				var title_field = form.find( '.field-post_title' );

				$( 'h1.page-title' ).html( post.post_title );
				form.find( '#post_name' ).val( post.post_name );

				var statusAlert = title_field.prev( '.alert-post-status' );

				if( $.inArray( post.post_status, [ 'pending', 'private', 'unlist', 'draft', 'reject', 'trash', 'future' ] ) !== -1 && data.message2 ){

					if( statusAlert.length != 0 ){
						statusAlert.remove();
					}

					var alertClass = 'info';

					switch( post.post_status ){
						case 'pending':
						case 'draft':
							alertClass = 'warning';
						break;
						case 'reject':
						case 'trash':
							alertClass = 'danger';
						break;
					}

					title_field.before( '<p class="alert alert-post-status alert-'+alertClass+' p-2 px-3">'+ data.message2 +'</p>' );
				}else{
					statusAlert.remove();
				}

				form.find( 'input[name=featured-image]' ).val('');

				form.find( '.current-post-permalink' ).attr( 'href', post.post_link );
			}
			else{

				var output = '';

				output += '<div class="bg-light d-flex">';
					output += '<div class="post-thumbnail ratio ratio-16x9 rounded overflow-hidden bg-dark w-200px">';
						if( post.post_thumbnail ){
							output += '<a href="'+ post.post_link +'"><img src="'+ post.post_thumbnail +'"></a>';
						}
					output += '</div><!--.post-thumbnail-->';

					output += '<div class="post-meta ms-4">';

						output += '<h3><a href="'+ post.post_link +'" class="post-title post-title-md text-decoration-none text-body fw-bold">';
							output += post.post_title;
						output += '</a></h3>';

						output += '<div class="post-status mb-4">';
							output += '<span class="text-capitalize badge badge-'+post.post_status+'">'+post.post_status+'</span>';
						output += '</div>';

					output += '</div><!--.post-meta-->';

				output += '</div>';

				form.html( output )
				.closest( '.modal-content' )
				.find( '.modal-footer' ).remove();				

				form
				.closest( '.modal-dialog' )
				.removeClass( 'modal-xl' )
				.addClass( 'modal-lg' );

				var modalTitle = streamtube.pending_review;

				if( post.post_status == 'publish' ){
					modalTitle = streamtube.video_published;
				}

				form.closest( '.modal-content' )
				.find( '.modal-title' ).html( modalTitle );
			}

			if( post.post_status == 'auto-draft' || data.auto_draft == true ){

				if( window.location.href.search( post.ID ) !== -1 ){
					window.location.href = window.location.href;
				}
				else{
					window.location.href = window.location.origin + window.location.pathname + post.ID
				}
			}
		}

		if( responseData.success == false ){

			$.showToast( responseData.data[0].message, 'danger' );

			form.closest( '.modal-content' ).find( 'button[type=submit]' )
			.removeClass( 'disabled' )
			.removeAttr( 'disabled' )
			.find( '.spinner' ).remove();
		}
	}

	function reportVideo( event, responseData, textStatus, jqXHR, formData, form ){
		if( responseData.success == false ){
			$.showToast( responseData.data.message, 'danger' );
		}else{
			$.showToast( responseData.data.message, 'success' );
		}
	}

	function updateTextTracks( event, responseData, textStatus, jqXHR, formData, form ){
		if( responseData.success == false ){
			$.showToast( responseData.data[0].message, 'danger' );
		}else{
			$.showToast( responseData.data.message, 'success' );
		}
	}

	function updateAltSources( event, responseData, textStatus, jqXHR, formData, form ){
		if( responseData.success == false ){
			$.showToast( responseData.data[0].message, 'danger' );
		}else{
			$.showToast( responseData.data.message, 'success' );
		}
	}

	function updateEmbedPrivacy( event, responseData, textStatus, jqXHR, formData, form ){
		if( responseData.success == false ){
			$.showToast( responseData.data[0].message, 'danger' );
		}else{
			$.showToast( responseData.data.message, 'success' );
		}
	}

	/**
	 * trashPost handler
	 * @since 1.0.0
	 **/
	function trashPost( event, responseData, textStatus, jqXHR, formData, form ){

		if( responseData.success == false ){
			$.showToast( responseData.data.message, 'danger' );
		}
		else{

			var rowId = $( '.table-posts' ).find( 'tr#row-' + responseData.data.post.ID );

			if( rowId.length != 0 ){
				rowId.remove();
			}
			else{
				window.location.href = responseData.data.redirect_url;
			}

			$( '#deletePostModal' ).modal( 'hide' );

			$.showToast( responseData.data.message, 'success' );
		}
	}

	/**
	 *
	 * approvePost handler
	 * @since 1.0.0
	 * 
	 */
	function approvePost( event, responseData, textStatus, jqXHR, formData, form ){
		if( responseData.success == false ){
			$.showToast( responseData.data.message, 'danger' );
		}
		else{
			$( '.table-posts' ).find( 'tr#row-' + responseData.data.post_id ).remove();

			$( '#updatePostMessageModal' ).modal( 'hide' );
			
			$.showToast( responseData.data.message, 'success' );
		}
	}

	/**
	 *
	 * rejectPost handler
	 * @since 1.0.0
	 * 
	 */
	function rejectPost( event, responseData, textStatus, jqXHR, formData, form ){
		if( responseData.success == false ){
			$.showToast( responseData.data.message, 'danger' );
		}
		else{
			$( '.table-posts' ).find( 'tr#row-' + responseData.data.post_id ).remove();

			$( '#updatePostMessageModal' ).modal( 'hide' );
			
			$.showToast( responseData.data.message, 'success' );
		}
	}

	/**
	 *
	 * restorePost handler
	 * @since 1.0.0
	 * 
	 */
	function restorePost( event, responseData, textStatus, jqXHR, formData, form ){
		if( responseData.success == false ){
			$.showToast( responseData.data.message, 'danger' );
		}
		else{
			$( '.table-posts' ).find( 'tr#row-' + responseData.data.post.ID ).remove();
			$.showToast( responseData.data.message, 'success' );
		}
	}

	/**
	 *
	 * processLiveStream handler
	 * @since 1.0.0
	 * 
	 */
	function processLiveStream( event, responseData, textStatus, jqXHR, formData, form ){
		if( responseData.success == false ){
			$.showToast( responseData.data.message, 'danger' );
		}
		else{
			$.showToast( responseData.data.message, 'success' );
			window.location.reload();
		}
	}		

	function processLiveOutput( event, responseData, textStatus, jqXHR, formData, form ){
		if( responseData.success == false ){
			$.showToast( responseData.data[0].message, 'danger' );
		}
		else{

			$.showToast( responseData.data.message, 'success' );

			form.find( '.output-status' )
			.html( responseData.data.label );

			form.find( 'button[type=submit]' )
			.html( responseData.data.button );				

			if( responseData.data.add_new ){
				// Added new output
				form.closest( 'li.service-item' )
				.attr( 'data-service-uid', responseData.data.data.uid )
				.addClass( 'is-added' );

				if( responseData.data.data.enabled ){
					form.closest( 'li.service-item' ).addClass( 'is-enabled' );
				}

				form.find( '.btn-primary2' )
				.html( responseData.data.button2 )
				.attr( 'data-action', responseData.data.button2_action );

				form.find( '.output-meta' ).prepend( '<div class="destination-status spinner-grow text-danger spinner-grow-sm" role="status"></div>' );

				form.find( 'select[name=server]' ).attr( 'disabled', 'disabled' );
				form.find( 'input[name=streamkey]' ).attr( 'disabled', 'disabled' );
			}
			else{
				form.closest( 'li.service-item' )
				.removeAttr( 'data-service-uid' )
				.removeClass( 'is-added is-enabled' )
				.find( '.destination-status' ).remove();

				form.find( 'select[name=server]' ).removeAttr( 'disabled' );
				form.find( 'input[name=streamkey]' ).removeAttr( 'disabled' );				
			}
		}
	}

	function disableLiveOutput( event, responseData, textStatus, jqXHR, element ){
		if( responseData.success == false ){
			$.showToast( responseData.data[0].message, 'danger' );
		}else{
			$.showToast( responseData.data.message, 'success' );

			element
			.html( responseData.data.button )
			.attr( 'data-action', responseData.data.action )
			.removeClass( 'btn-dark' )
			.addClass( 'btn-secondary' );

			element.closest( 'li.service-item' ).removeClass( 'is-enabled is-disconnected' );
		}
	}

	function enableLiveOutput( event, responseData, textStatus, jqXHR, element ){
		if( responseData.success == false ){
			$.showToast( responseData.data[0].message, 'danger' );
		}else{
			$.showToast( responseData.data.message, 'success' );

			element
			.html( responseData.data.button )
			.attr( 'data-action', responseData.data.action )
			.addClass( 'btn-dark' )
			.removeClass( 'btn-secondary' );			

			element.closest( 'li.service-item' ).addClass( 'is-enabled' );
		}
	}

	/**
	 *
	 * fileEncodeDone handler
	 * @since  1.0.0
	 */
	function fileEncodeDone( event, attachment, textStatus, jqXHR ){

		if( attachment.parent_name ){

			var message = '<strong>'+ attachment.parent_name +'</strong>' + ' ' + streamtube.file_encode_done;

			if( attachment.parent_url ){
				message += '<a class="text-white ms-1" href="'+attachment.parent_url+'"><strong>'+streamtube.view_video +'</strong></a>';
			}
			
			$.showToast( message , 'success' );

			if( $( 'body' ).hasClass( 'single-video' ) ){
				setTimeout(function(){ 
					window.location.href = attachment.parent_url;
				}, 3000 );
			}
		}
	}

	/**
	 * postComment handler
	 * @since 1.0.0
	 */
	function postComment( event, responseData, textStatus, jqXHR, formData, form ){

		if( responseData.success == false ){
			$.showToast( responseData.data.message, 'danger' );
		}
		else{
			var commentList	= form.closest( '.comments-area' ).find( '.comments-list' );
			var comment 	= responseData.data.comment;
			var output		= responseData.data.comment_output;

			commentList.find( '.no-comments' ).remove();

			if( parseInt( comment.comment_parent ) == 0 ){
				if( commentList.hasClass( 'comment-form-position-top' ) ){
					commentList.prepend( output );	
				}
				else{
					commentList.append( output );
				}
			}
			else{

				var parent = $( 'li#comment-' + comment.comment_parent );

				if( parent.find( 'ul.children' ).length == 0 ){
					parent.append( '<ul class="children d-block">'+ output +'</ul>' );
				}
				else{
					parent.find( 'ul.children' ).addClass( 'd-block' ).append( output );
				}
			}

			// Update comments number
			$( '.comment-title .widget-title' ).html( responseData.data.comments_number );

			// Clear comment textarea
			$( '#commentform #comment' ).val('');

			/**
			$( '#div-comment-' + comment.comment_ID )
			.get(0)
			.scrollIntoView({ behavior: "smooth", block: "center", inline: "center" });
			**/

			$.showToast( responseData.data.message, 'success' );
		}
	}

	function editComment( event, responseData, textStatus, jqXHR, formData, form ){
		if( responseData.success == false ){
			return $.showToast( responseData.data[0].message, 'danger' );
		}

		var comment = responseData.data.comment;

		$( '#row-comment-' + comment.comment_ID )
		.find( '.comment-content' )
		.html( comment.comment_content_filtered );

		$( '#modal-edit-comment' ).modal( 'hide' );

		if( form.hasClass( 'edit-inline-comment' ) ){

			form.prev().css( 'height', 'auto' ).html( comment.comment_content_autop ).slideDown();

			form.remove();
		}

		return $.showToast( responseData.data.message, 'success' );
	}

	function reportComment( event, responseData, textStatus, jqXHR, formData, form ){

		if( responseData.success == false ){
			return $.showToast( responseData.data[0].message, 'danger' );
		}

		form.prev().slideDown().html( '<p class="text-muted fst-italic">'+ streamtube.comment_reviewed +'</p>' );
		form.closest( 'li.comment' )
		.addClass( 'has-reported' )
		.find( '.btn-report-comment' ).remove();
		form.remove();

		return $.showToast( responseData.data.message, 'success' );
	}

	function removeCommentReport(event, responseData, textStatus, jqXHR, element ){
		if( responseData.success == false ){
			return $.showToast( responseData.data[0].message, 'danger' );
		}

		element.closest( '.comment-content' )
		.find( '.comment-text' )
		.html( responseData.data.comment.comment_content_autop );

		element.remove();

		return $.showToast( responseData.data.message, 'success' );
	}

	function editInlineComment( event, responseData, textStatus, jqXHR, element ){

		var commentList = element.closest( '#comments-list' );

		commentList.find( '.edit-inline-comment' ).remove();

		if( commentList.length == 0 ){
			commentList = element.closest( '.table-comments' );
		}

		if( responseData.data.comment_editor == 'editor' ){
			$.editorRemove( '_comment_content' );
		}

		var parent = element.closest( '.comment-wrap' );

		var comment_content = responseData.data.comment_content.replace( '/\r?\n/g', '<br />');;

		var form = '';

		form += '<form class="form-ajax edit-comment edit-inline-comment">';
			form += '<div class="wp-editor-wrap">';
				form += '<textarea class="form-control autosize" id="_comment_content" name="comment_content">'+ comment_content +'</textarea>';
				form += '<div class="d-flex gap-3 mt-3">';
					form += '<button class="btn btn-secondary btn-sm btn-cancel" type="button">'+streamtube.cancel+'</button>';
					form += '<button class="btn btn-danger btn-sm" type="submit">'+streamtube.save+'</button>';
				form += '</div>';
				form += '<input type="hidden" name="action" value="edit_comment">';
				form += '<input type="hidden" name="comment_ID" value="'+ responseData.data.comment_ID +'">';
			form += '</div>';
		form += '</form>';

		parent.find( '.comment-text > div:first-child' ).slideUp();

		parent.find( '.comment-text' ).append( form );

		if( responseData.data.comment_editor == 'editor' ){
			$.editorInit( '_comment_content' );
		}

		parent.find( 'textarea[name=comment_content]' ).focus();
	}

	function reportInlineComment( event, responseData, textStatus, jqXHR, element ){
		editInlineComment( event, responseData, textStatus, jqXHR, element );

		$( '.edit-inline-comment' )
		.find( 'textarea[name=comment_content]' ).val('');		

		$( '.edit-inline-comment' )
		.find( 'button[type=submit]' ).html( streamtube.report );

		$( '.edit-inline-comment' )
		.find( 'input[name=action]' ).val( 'report_comment' );		
	}

	function moderateComment( event, responseData, textStatus, jqXHR, element ){

		if( responseData.success == false ){
			return $.showToast( responseData.data[0].message, 'danger' );
		}

		var approve = responseData.data.comment_approved;

		if( approve == '1' ){
			element
			.removeClass( 'text-success' )
			.addClass( 'text-warning' )
			.html( responseData.data.status )
		}
		else{
			element
			.removeClass( 'text-warning' )
			.addClass( 'text-success' )
			.html( responseData.data.status )
		}

		element.closest( 'tr' ).toggleClass( 'table-warning' );
	}

	function trashComment( event, responseData, textStatus, jqXHR, element ){

		if( responseData.success == false ){
			return $.showToast( responseData.data[0].message, 'danger' );
		}

		element.closest( 'tr#row-comment-' + responseData.data.comment_id ).remove();
		element.closest( 'li#comment-' + responseData.data.comment_id ).remove();

		return $.showToast( responseData.data.message, 'success' );
	}

	function spamComment( event, responseData, textStatus, jqXHR, element ){

		if( responseData.success == false ){
			return $.showToast( responseData.data[0].message, 'danger' );
		}

		element.closest( 'tr#row-comment-' + responseData.data.comment_id ).remove();

		return $.showToast( responseData.data.message, 'success' );
	}	

    /**
     *
     * load_comments event
     * 
     * @param  string event   [description]
     * @param  object data    [description]
     * @param  DOM object element [description]
     * @param  int postId  [description]
     * @param  int page    [description]
     *
     * @since  1.0.0
     * 
     */
    function loadMoreComments( event, responseData, textStatus, jqXHR, element ){
    	element.removeClass( 'active' );
        if( responseData.data.output ){
            element.closest( 'li' ).before( responseData.data.output );

            element.attr( 'data-params', responseData.data.data );    
        }
        else{
            element.closest( 'li' ).remove();
        }
    }

    function loadCommentsBeforeSend( event, element, formData ){

    	var commentsList = $( 'ul#comments-list' );

    	if( commentsList.find( 'li.load-more-comments-wrap' ).length != 0 ){
    		commentsList.find( 'li:not(:last-child)' ).remove();
    		commentsList.find( 'li' ).addClass( 'd-none' ).before( '<li class="spinner">'+ $.getSpinner() +'</li>' );
    	}
    	else{
    		commentsList.html( '<li class="spinner">'+ $.getSpinner() +'</li>' );
    	}
    }

    /**
     *
     * Reload comments
     *
     * @since  1.0.0
     * 
     */
    function loadComments( event, responseData, textStatus, jqXHR, element ){

    	var commentsList = $( 'ul#comments-list' );

    	setTimeout(function (){

	    	if( responseData.success == true ){
	    		commentsList
	    		.find( '.spinner' )
	    		.replaceWith( responseData.data.output );

	    		commentsList
	    		.find( 'li.load-more-comments-wrap' )
	    		.removeClass( 'd-none' )
	    		.find( 'button' )
	    		.attr( 'data-params', element.attr( 'data-params' ) );
	    		
	    	}else{
	    		$.showToast( responseData.data.message, 'danger' );
	    	}

	        element
	        .addClass( 'active' )
	        .closest( '.dropdown-menu' )
	        .find( '.dropdown-item' )
	        .removeClass( 'active waiting' );

	        element
	        .closest( '.dropdown-menu' )
	        .prev()
	        .html( element.html() );

        }, 300 );
    }

    function updateAdvertising( event, responseData, textStatus, jqXHR, formData, form ){
    	$.showToast( responseData.data.message, responseData.success == true ? 'success' : 'danger' );
    }

	/**
	 * updateProfile handler
	 * @since 1.0.0
	 */
	function updateProfile( event, responseData, textStatus, jqXHR, formData, form ){
		$.showToast( responseData.data.message, responseData.success == true ? 'success' : 'danger' );
	}

	/**
	 * updateSocialProfiles handler
	 * @since 2.2.1
	 */
	function updateSocialProfiles( event, responseData, textStatus, jqXHR, formData, form ){
		$.showToast( responseData.data.message, responseData.success == true ? 'success' : 'danger' );
	}	

	/**
	 * 
	 * Update user photo
	 * @param  string event
	 * @param  object data 
	 * @param  string textStatus
	 * @param  object jqXHR
	 * @param  object formData
	 * @since 1.0.0
	 */
	function updateUserPhoto( event, responseData, textStatus, jqXHR, formData, form ){

		if( responseData.success == true ){
			if( responseData.data.field == 'avatar' ){
				$( '.header-user__dropdown .user-avatar img' ).replaceWith( responseData.data.output );

				$( '.profile-header__avatar .user-avatar img' ).replaceWith( responseData.data.output );
			}
			else{
				$( '.profile-header__photo' ).html( responseData.data.output );
			}
		}

		$.showToast( responseData.data.message, responseData.success == true ? 'success' : 'danger' );
	}

	/**
	 *
	 * deleteUserPhoto handler
	 * 
	 */
	function deleteUserPhoto( event, responseData, textStatus, jqXHR, element ){
		if( responseData.success == true ){
			window.location.href = window.location.href;
		}else{
			$.showToast( responseData.data.message, 'danger' );
		}
	}

	/**
	 *
	 * deactivateAccount handler
	 * 
	 */
	function deactivateAccount( event, responseData, textStatus, jqXHR, formData, form ){
		if( responseData.success == true ){

			if( responseData.data.did_action == 'deactivated' ){
				window.location = window.location;	
			}else{
				window.location = streamtube.home_url;
			}
		}else{
			$.showToast( responseData.data[0].message, 'danger' );
		}
	}

	/**
	 *
	 * reactivateAccount handler
	 * 
	 */
	function reactivateAccount( event, responseData, textStatus, jqXHR, formData, form ){
		if( responseData.success == true ){
			window.location = window.location
		}else{
			$.showToast( responseData.data[0].message, 'danger' );
		}
	}	

	/**
	 *
	 * AJAX load more posts of the Posts widget
	 *
	 * @since  1.0.0
	 * 
	 */
	function widgetLoadMoreposts( event, responseData, textStatus, jqXHR, element  ){

		element.next().remove();

		if( responseData.success == true ){

			var output = responseData.data.output;
			var count_post = $( output ).find( '.post-item' ).length;
			var dataJson = $.parseJSON( responseData.data.data );

			if( output != "" ){
				element
				.attr( 'data-params', responseData.data.data )
				.removeClass( 'd-none active waiting' )
				.parent()
				.before( output );

				element.parent()
				.prev().fadeIn('slow');

				if( parseInt( count_post ) < parseInt( dataJson.posts_per_page ) ){
					element.parent().remove();
				}else{
					element.find( '.spinner-border' ).remove();
				}
			}
			else{
				element.parent().remove();
			}
		}
	}

	function loadMoreUsers( event, responseData, textStatus, jqXHR, element ){

		if( responseData.success == false ){
			$.showToast( responseData.data.message, 'danger' );
		}
		else{
			if( responseData.data.output != "" ){
				element
				.attr( 'data-params', responseData.data.data )
				.removeClass( 'active waiting d-none' )
				.parent()
				.before( responseData.data.output )
				.find( '.spinner-border' )
				.remove();
			}
			else{
				element.parent().remove();
			}
		}
	}

	function loadMoreTaxTerms( event, responseData, textStatus, jqXHR, element ){

		if( responseData.success == false ){
			$.showToast( responseData.data[0].message, 'danger' );
		}

		var output 		= responseData.data.output;
		var instance 	= responseData.data.instance;

		if( output != "" ){
			element
			.attr( 'data-params', instance )
			.removeClass( 'd-none active waiting' )
			.parent()
			.before( output );

			element.parent()
			.prev().fadeIn('slow');
		}
		else{
			element.parent().remove();
		}
	}

	/**
	 *
	 * uploadVideo handler
	 * 
	 * @param  string event
	 * @param  object responseData
	 * @param  string textStatus
	 * @param  object jqXHR
	 * @param  object formData
	 * @param  object form
	 *
	 * @since  1.0.0
	 * 
	 */
	function postLike( event, responseData, textStatus, jqXHR, formData, form  ){

		if( responseData.success == false ){
			return $.showToast( responseData.data.message, 'danger' );
		}		

		var data = responseData.data;

		var didAction = data.did_action;

		var wrapper = form.closest( '.wppl-button-wrap' );

		wrapper.find( 'button' ).removeClass( 'active' );

		if( didAction == 'like' ){
			wrapper.find( '.wppl-like-button' ).addClass( 'active' );
		}

		if( didAction == 'dislike' ){
			wrapper.find( '.wppl-dislike-button' ).addClass( 'active' );
		}

		wrapper.find( '.wppl-like-button .badge' ).html( data.results.like_formatted );
		wrapper.find( '.wppl-dislike-button .badge' ).html( data.results.dislike_formatted );

		wrapper
		.find( '.progress-bar' )
		.css( 'width', data.results.progress + '%' );

		$( document.body ).trigger( 'post_like_progress', [ form, data.results.progress, data ] );
	}

	/**
	 *
	 * Woocommerce added to cart event
	 * 
	 */
	function addedToCart( event, fragment, hash, button ){

		var productTitle = '';
		var product = button.closest('.product');

		if( product.find( '.woocommerce-loop-product__title' ).length !== 0 ){
			productTitle = product.find( '.woocommerce-loop-product__title' ).text();
		}

		if( product.find( '.post-title' ).length !== 0 ){
			productTitle = product.find( '.post-title a' ).text();
		}

		if( productTitle != "" ){
			var text = streamtube.added_to_cart.replace( '%s', '<strong>' + productTitle + '</strong>' );
		}else{
			var text = streamtube.added_to_cart_no_name;
		}

		text += ', <a class="text-white" href="'+streamtube.cart_url+'">'+ streamtube.view_cart +'</a>';

		$.getCartTotal();

		$.showToast( text, 'success' );
	}

	/**
	 *
	 * Updated cart event
	 * 
	 */
	function updatedCartTotals( event, data ){
		$.getCartTotal();
	}

	/**
	 *
	 * Removed from cart event
	 * 
	 */
	function removedFromCart( event ){
		$.getCartTotal();
	}

	/**
	 *
	 * Auto Upnext
	 */
	function autoUpnext( event ){
		var upNextButton	= $( '#btn-up-next' );
		var nextButton 		= $( '#next-post-link' );
		var listContent  	= $( '.playlist-content-widget' );
		var nextUrl			= '';
		var requestUrl 		= '';
		let player 			= event.detail.player;
		let count 			= streamtube.upnext_time ? parseInt( streamtube.upnext_time ) : 5;

		if( ! upNextButton.hasClass( 'auto-next' ) ){
			return;
		}

		if( listContent.length == 0 && nextButton.length == 0 ){
			return;
		}

		if( listContent.length != 0 ){
			nextUrl = listContent.find( '.playlist-item.active' ).next().find( '.post-permalink' ).attr( 'href' );

		}else{
			nextUrl = nextButton.attr( 'href' );
		}

		if( nextUrl ){
			nextUrl = encodeURIComponent(nextUrl);
		}

		player.addClass( 'vjs-has-upnext' );
		
		requestUrl 			= streamtube.ajaxUrl + '?action=get_post_by_url&url=' + nextUrl + '&_wpnonce=' + streamtube._wpnonce;

		$.ajax({
			type 	: 'GET',
			url 	: requestUrl,
			async 	: false
		}).done( function( data, textStatus, jqXHR ){

			if( ! data.success ){
				return;
			}

			var data = data.data;
			var output = '';

			var next = document.createElement('div');
			next.className = 'streamtube-plugin streamtube-next-post w-100 h-100 start-0 top-0';

			output += '<div class="next-post-wrap type-video top-50 start-50 translate-middle position-absolute rounded p-3">';

				output += '<a href="'+data.permalink+'">';
					output += '<div class="post-thumbnail ratio ratio-16x9 rounded overflow-hidden bg-dark w-100">';
						output += '<img src="'+ data.thumbnail +'">';

						if( data.length ){
							output += '<div class="video-length badge bg-danger">'+ data.length +'</div>';
						}
					output += '</div>';
				output += '</a>';

				output += '<div class="d-flex flex-column">';
			
					output += '<div class="post-meta my-3">';
						output += '<h3 class="post-meta__title post-title">';
							output += '<a href="'+data.permalink+'">'+ data.title +'</a>';
						output += '</h3>';
						output += '<div class="post-meta__author">';
							output += '<a href="'+data.author.url+'">'+data.author.name+'</a>'
						output += '</div>';
					output += '</div>';

					output += '<div class="d-flex gap-3 justify-content-center align-items-start">';
						output += '<a class="btn px-3 rounded-1 w-100 btn-secondary" id="cancel-upnext">'+streamtube.cancel+'</a>';
						output += '<a href="'+data.permalink+'" class="btn px-3 rounded-1 w-100 btn-danger d-flex gap-2 justify-content-center align-items-start">';
							output += '<span>'+ streamtube.play_now +'</span>';
							output += '<span class="up-countdown">('+ count +')</span>';
						output += '</a>';
					output += '</div>';

				output += '</div>';

			output += '</div>';

			next.innerHTML = output;

			player.el().appendChild( next );

			var interval = setInterval( function(){
				count--;
				$(next).find( '.up-countdown' ).html( '('+ count +')' );

				if( count <= 0 ){
					clearInterval( interval );
					$(next).find( '.up-countdown' ).remove();
					window.location.href = data.permalink;
				}
			}, count*1000/count );

			$( document ).on( 'click','#cancel-upnext', function( e ){
				$(this).closest( '.streamtube-plugin' ).remove();
				clearInterval( interval );
			} );
			
		});
		
	}

	function joinUs( event, responseData, textStatus, jqXHR, formData, form ){

		if( responseData.success == false ){
			return $.showToast( responseData.data[0].message, 'danger' );
		}

		$('#modal-join-us').modal('hide');

		return $.showToast( responseData.data.message, 'success' );
	}

	function transfersPoints( event, responseData, textStatus, jqXHR, formData, form ){
		if( responseData.success == false ){
			return $.showToast( responseData.data[0].message, 'danger' );
		}

		$('#modal-donate')
		.modal('hide')
		.find( 'form' )
		.removeClass( 'form-step-final' )
		[0].reset();
		
		$( responseData.data.data.css ).html( responseData.data.data.balance );

		return $.showToast( responseData.data.message, 'success' );
	}

	/**
	 *
	 * New Message Thread handler
	 *
	 * Better Messages
	 * 
	 */
	function newMessageThread( event, responseData, textStatus, jqXHR, formData, form ){

		if( ! responseData.result ){
			if( responseData.errors.restrictNewThreadsMessage ){
				return $.showToast( responseData.errors.restrictNewThreadsMessage, 'danger' );	
			}
			
			if( responseData.errors[0] ){
				return $.showToast( responseData.errors[0], 'danger' );	
			}			
		}

		$('#modal-private-message').modal('hide');

		return $.showToast( streamtube.bp_message_sent, 'success' );
	}

	function searchVideos(  event, responseData, textStatus, jqXHR, formData, form ){
		if( responseData.success == false ){
			return $.showToast( responseData.data[0].message, 'danger' );
		}

		form.find( 'div#video-list>div' ).html( responseData.data );
	}

	function searchInCollection( event, responseData, textStatus, jqXHR, formData, form ){
		if( responseData.success == false ){
			return $.showToast( responseData.data[0].message, 'danger' );
		}

		form
		.closest( '.playlist-content-widget' )
		.find( '.playlist-items' )
		.replaceWith( responseData.data );	
	}

	/**
	 * Create Collection event
	 */
	function createCollection( event, responseData, textStatus, jqXHR, formData, form ){

		if( responseData.success == false ){
			return $.showToast( responseData.data[0].message, 'danger' );
		}

		if( responseData.data.list_id ){
			$( '#' + responseData.data.list_id ).replaceWith( responseData.data.list );	
		}

		$( '#create-collection-form' ).collapse( 'hide' );

		return $.showToast( responseData.data.message, 'success' );
	}

	/**
	 * Delete Collection event
	 */
	function deleteCollection(event, responseData, textStatus, jqXHR, element ){

		if( responseData.success == false ){
			return $.showToast( responseData.data[0].message, 'danger' );
		}

		if( responseData.data.redirect_url ){
			return window.location.href = responseData.data.redirect_url;
		}		

		var collectionItem = element.closest( '.collection-item' );

		if( collectionItem.length != 0 ){
			collectionItem.remove();
		}

		var collectionWidget = element.closest( '.playlist-content-widget' );

		if( collectionWidget.length != 0 ){
			collectionWidget.remove();
		}		
	}	

	/**
	 * Set Post Collection event
	 */
	function setPostCollection( event, responseData, textStatus, jqXHR, element ){

		if( responseData.success == false ){
			return $.showToast( responseData.data[0].message, 'danger' );
		}

		if( element.hasClass( 'dropdown-item' ) ){
			element.closest( '.playlist-item' ).remove();
		}

		var collectionItem = element.closest( '.collection-item' );

		if( collectionItem.length != 0 ){
			collectionItem.replaceWith( responseData.data.output );
		}

		if( element.hasClass( 'btn-add-to-term' ) ){
			element.replaceWith( responseData.data.output );
		}

		$.showToast( responseData.data.message, 'success' );
	}

	function setPostWatchLater( event, responseData, textStatus, jqXHR, element ){
		if( responseData.success == false ){
			return $.showToast( responseData.data[0].message, 'danger' );
		}

		element.parent().replaceWith( responseData.data.output );	
		
		return $.showToast( responseData.data.message, 'success', 1000 );
	}

	function setImageCollection( event, responseData, textStatus, jqXHR, element ){
		if( responseData.success == false ){
			return $.showToast( responseData.data[0].message, 'danger' );
		}

		var img = '<img src="'+ responseData.data.thumbnail_url +'">';

		$( '.widget-term-featured-image .thumbnail-group .post-thumbnail' ).html( img );

		return $.showToast( responseData.data.message, 'success' );
	}

	/**
	 * uploadCollectionThumbnailImage
	 */
	function uploadCollectionThumbnailImage( event, responseData, textStatus, jqXHR, formData, form ){

		if( responseData.success == false ){
			return $.showToast( responseData.data[0].message, 'danger' );
		}

		form.find( 'button[type=submit]' ).addClass( 'd-none' );

		$.showToast( responseData.data, 'success' );
	}

	/**
	 * Set Collection status
	 */
	function setCollectionStatus( event, responseData, textStatus, jqXHR, element ){

		if( responseData.success == false ){
			return $.showToast( responseData.data[0].message, 'danger' );
		}

		element.replaceWith( responseData.data );
	}

	/**
	 * Get Collection Term
	 */
	function getCollectionTerm( event, responseData, textStatus, jqXHR, element ){

		if( responseData.success == false ){
			return $.showToast( responseData.data[0].message, 'danger' );
		}

		var data = responseData.data;

		var form 	= $( '#create-collection-form' );

		form.collapse( 'show' );		

		form.find( 'input[name=name]' ).val( data.name_formatted );
		form.find( 'textarea[name=description]' ).val( data.description );

		form.find( 'select[name=status]' ).val( data.status );

		form.find( 'input[name=term_id]' ).val( data.term_id );

		if( data.searchable == 'on' ){
			form.find( 'input[name=searchable]' ).attr( 'checked', 'checked' );
		}else{
			form.find( 'input[name=searchable]' ).removeAttr( 'checked' );
		}
	}

	function setCollectionActivity( event, responseData, textStatus, jqXHR, element ){
		if( responseData.success == false ){
			return $.showToast( responseData.data[0].message, 'danger' );
		}

		element.replaceWith( responseData.data );
	}

	function clearCollection( event, responseData, textStatus, jqXHR, element ){
		if( responseData.success == false ){
			return $.showToast( responseData.data[0].message, 'danger' );
		}

		element.replaceWith( responseData.data );

		location.reload();
	}

	function fixPlayListContentWidget(){
		
		var playList = $( '.playlist-content-widget' );

		if( playList.length == 0 ){
			return;
		}

		var w = $(window).width();

		if( w <= 992 ){
			playList.addClass( 'mb-0' ).removeClass( 'shadow-sm' ).prependTo( '#post-bottom' );
		}else{
			playList.removeClass( 'mb-0' ).addClass( 'shadow-sm' ).prependTo( '#sidebar-primary' );
		}
	}

	/**
	 * Upload Video controller
	 */
	function uploadVideoController( files, form ){

		var chunkUpload		= streamtube.chunkUpload;
		
		var extensions 		= streamtube.video_extensions;
		var max_upload_size = parseInt( streamtube.max_upload_size );

		var input 			= form.find( 'input[name=video_file]' );

		var file 			= files[0];

		if( ! file ){
			return;
		}

		//var form  			= input.closest( 'form' );

		var parts 			= file.name.split('.');
		var ext 			= parts[parts.length - 1].toLowerCase();

		var error 			= false;

		// Check file extension
		if( $.inArray( ext, extensions ) == -1 ){
			error = streamtube.invalid_file_format;
		}

		// Check file size
		if( file.size > max_upload_size ){
			error = streamtube.exceeds_file_size.replace( '{size}', Math.round( file.size /1048576 ) );
		}

		if( error !== false ){
			return $.showToast( error, 'danger' );
		}
	
		if( chunkUpload == 'on' ){

			var sliceSize = parseInt( streamtube.sliceSize );

			if( sliceSize == 0 ){
				sliceSize = 10240;
			}

			$( document.body ).trigger( 'upload_video_before_send', [ form, new FormData(form[0]), file ] );

			return $.uploadBigFile( file, sliceSize * 1024, form );
		}
		else{
			
			return form.trigger( 'submit' );
		}		
	}

	function _multipleCheckboxesAction( parent ){
        parent.find( 'input[type=checkbox]' ).each( function( k,v ){
            var checkbox = $(this);
            var childList = checkbox.parent().next( '.children' );
            var isChecked = checkbox.is( ':checked' );

            if( childList.length !== 0 ){
                if( isChecked ){
                    childList.addClass( 'd-block' );
                }
                else{
                    childList.addClass( 'd-none' );
                }

                checkbox.closest( 'li' ).addClass( 'has-child' );

                if( isChecked ){
                    checkbox.closest( 'li' ).addClass( 'child-expanded' );
                }
            }
        });
	}

	function multipleCheckboxesAction(){
		$( 'ul.categorychecklist' ).each(function( index, element ) {
			var list 		= $(this);

			if( list.hasClass( 'checkboxes-rendered' ) ){
				return;
			}

			var checkedCk 	= 0;
			var maxItems	= parseInt( list.attr( 'data-max-items' ) );

			if( maxItems == 0 ){
				return;
			}

			list.addClass( 'checkboxes-rendered' );

			_multipleCheckboxesAction( list );

			list.find( 'input[type=checkbox]' ).each(function () {
				if( $(this).is(':checked') ){
					checkedCk++;
				}
			});

			if( checkedCk >= maxItems ){
				list.find( 'input[type=checkbox]' ).each(function () {
					if( ! $(this).is(':checked') ){
						$(this).attr('disabled', 'disabled');
					}				
				});
			}else{
				list.find( 'input[type=checkbox]' ).each(function () {
					if( ! $(this).is(':checked') ){
						$(this).removeAttr('disabled');
					}				
				});
			}
		} );		
	}

	$( document ).on( 'show.bs.modal', '#modal-edit-collection', function(e){
		$(this).find( '.modal-body' ).append( $.getSpinner() );
		$(this).find( 'form' ).addClass( 'd-none' );
	});

	$( document ).on( 'shown.bs.modal', '#modal-edit-collection', function(e){
		var modal 	= $(this);
		var termId 	= e.relatedTarget.getAttribute( 'data-term-id' );
		var data 	= {
			'action' 	: 'get_collection_term',
			'data' 		: termId,
			'_wpnonce' 	: streamtube._wpnonce
		}

		$.post( streamtube.ajaxUrl, data, function( response ){
			if( ! response.success ){
				return $.showToast( response.data[0].message, 'danger' );
			}else{
				modal.find( 'input[name=name]' ).val( response.data.name_formatted );
				modal.find( 'textarea[name=description]' ).val( response.data.description );
				modal.find( 'select[name=status]' ).val( response.data.status );
				modal.find( 'input[name=term_id]' ).val( termId );
				modal.find( 'input[name=post_id]' ).val( '' );

				if( response.data.searchable == 'on' ){
					modal.find( 'input[name=searchable]' ).attr( 'checked', 'checked' );
				}else{
					modal.find( 'input[name=searchable]' ).removeAttr( 'checked' );
				}				
			}

			modal.find( '.spinner-wrap' ).remove();
			modal.find( 'form' ).removeClass( 'd-none' );
		} );
	});	

	$( document ).on( 'show.bs.collapse', '#create-collection-form', function(e){
		$( '.collection-list' ).slideUp();
		$( '.form-search-collections' ).slideUp();
	});

	$( document ).on( 'hidden.bs.collapse', '#create-collection-form', function(e){
		$(this).trigger( 'reset' );
		$(this).find( 'input[name=term_id]' ).val('0');
		$( '.collection-list' ).slideDown();
		$( '.form-search-collections' ).slideDown();
	});

	/**
	 * Cancel Delete Collection button handler
	 */
	$( document ).on( 'click', '.btn-collection-action-cancel', function(e){
		$(this).closest( '.alert' ).remove();
	});

	/**
	 * Delete Collection button handler
	 */
	$( document ).on( 'click', '.btn-collection-delete', function(e){
		var button 	= $(this);
		var termId 	= button.attr( 'data-term-id' );
		var title 	= button.attr( 'title' );
		var li 		= button.closest( '.collection-item' );
		var message = '';

		message += '<div class="alert alert-warning position-absolute start-0 top-0 w-100 h-100">';
			message += '<button type="button" class="btn btn-sm btn-danger ajax-elm" ';
				message += 'data-action="delete_collection"';
				message += 'data-params="'+ termId +'">';
				message += title;
			message += '</button>';
			message += '<button class="btn btn-sm btn-secondary btn-collection-action-cancel ms-3">'+ streamtube.cancel +'</button>';
		message += '</div>';

		li.append( message );
	});

	$( document ).on( 'show.bs.modal', '#modal-delete-collection', function(e){
		var termId = e.relatedTarget.getAttribute( 'data-term-id' );

		$(this).find( 'input[name=data]' ).val( termId );
	});

	$( document ).on( 'show.bs.modal', '#modal-search-videos', function(e){

		var termId = e.relatedTarget.getAttribute( 'data-term-id' );

		$(this).find( 'input[name=term_id]' ).val( termId );
	});	

	/**
	 * AJAX regular form handler
	 */
	$( document ).on( 'submit', '.form-ajax', $.ajaxFormRequest );

	$( document ).on( 'click', '.ajax-elm', $.ajaxElementOnEventRequest );	

	/**
	 *
	 * Generate thumbnail image
	 * 
	 */
	$( document ).on( 'click', '#button-generate-thumb-image', function(e){
		e.preventDefault();
		var button 		= $(this);
		var form 		= button.closest( 'form' );
		var mediaId 	= form.find('input[name=source]' ).val();
		var postId 		= form.find( 'input[name=post_ID]' ).val();

		$.ajax( {
			url: streamtube.rest_url + '/generate-image',
			method: 'POST',
			beforeSend: function ( xhr ) {
				xhr.setRequestHeader( 'X-WP-Nonce', streamtube.nonce );

				button.attr( 'disabled', 'disabled' ).append( $.getSpinner(false) );;
			},
			data:{
				'mediaid'	: mediaId,
				'parent'	: postId,
				'type'		: 'image'
			}
		} ).done( function ( response ) {

			if( response.success == false ){
				$.showToast(  response.data[0].message, 'danger' );
			}

			if( response.success == true ){
				var img = '<img src="'+ response.data.thumbnail_url +'">';

				button.closest( '.thumbnail-group' )
				.find( '.post-thumbnail' ).html( img );

				button.remove();
			}

			button
			.removeAttr( 'disabled' )
			.find( '.spinner' )
			.remove();

		} );
	});	

	/**
	 * WP media button handler
	 */
    $( document ).on( 'click', '.btn-wpmedia', function(e){

        var button 		= $(this);
        var mediaType 	= button.attr( 'data-media-type' );

        var frame;
        
        // If the media frame already exists, reopen it.
        if ( frame ) {
            frame.open();
            return;
        }

        // Create a new media frame
        frame = wp.media({  
            library: { type: mediaType },
            multiple: false
        });

         // Finally, open the modal on click
        frame.open();

         // When an video is selected in the media frame...
        frame.on( 'select', function() {
            
            // Get media attachment details from the frame state
            var attachment = frame.state().get('selection').first().toJSON();

            var attachment_id   =   attachment.id;

            button.closest( '.field-group' ).find( '.input-field' ).val( attachment_id );
        });

    } );	

	/**
	 * Cropper
	 *
	 * @since 1.0.0
	 * 
	 */
    $( document ).on( 'change', '.cropper-input', function(e){
    	var input 				=	$(this);
    	var form 				=	input.closest( 'form' );
    	var opts 				=	JSON.parse(form.find( '.cropper-img' ).attr( 'data-option' ));
    	var cropper 			=	form.find( '.cropper-img' ).cropper(opts);
        
        var URL 				=	window.URL || window.webkitURL;
        var imageName 			=	'';
        var imageType 			=	'';
        var imageURL 			=	'';
        var files 				=	this.files;
        var file;

        if (URL) {

            if ( ! cropper.data('cropper') ) {
            	console.log('no data');
                return;
            }

            if (files && files.length) {
                file = files[0];

                if (/^image\/\w+$/.test(file.type)) {
                    imageName = file.name;
                    imageType = file.type;
                    imageURL = URL.createObjectURL(file);

					cropper.cropper('destroy').attr('src', imageURL).cropper(opts);

                }else{
                	input.attr( 'value', '' );
                    
                    $.showToast( streamtube.incorrect_image, 'danger' );
                }
            }
        }
    });

    /**
     * The featured image handler
     * 
     * @since 1.0.0
     * 
     */
    $( document ).on( 'change', 'input[name=featured-image]', function(e){
    	var input 				=	$(this);
        var URL 				=	window.URL || window.webkitURL;
        var imageURL 			=	'';
        var files 				=	this.files;
        var file;

        if (URL) {

            if (files && files.length) {
                file = files[0];

                if (/^image\/\w+$/.test(file.type)) {
                    imageURL = URL.createObjectURL(file);

                    var imgTag = '<img class="wp-post-image" src="'+imageURL+'">';

                    $(this).closest( '.thumbnail-group' )
                    .find( '.post-thumbnail' )
                    .html( imgTag );

                    $(this).closest( 'form' )
                    .find( 'button[type=submit]' )
                    .removeClass( 'd-none' );
                }else{
                	input.attr( 'value', '' );
                    
                    $.showToast( streamtube.incorrect_image, 'danger' );
                }
            }
        }
    });

    /**
     *
     * AJAX load comments handler
     *
     * @since 1.0.0
     * 
     */
    $( document ).on( 'scrollin click', '.btn-load-more-terms.load-on-scroll', $.ajaxElementOnEventRequest );    

    /**
     *
     * AJAX load comments handler
     *
     * @since 1.0.0
     * 
     */
    $( document ).on( 'scrollin click', '.load-comments.load-on-scroll', $.ajaxElementOnEventRequest );

    /**
     *
     * Used for single video 1
     *
     * Catch the scroll end event of the comment list
     * 
     * @since  1.0.0
     * 
     */
	$(  '.comments-fixed #comments-list' ).on( 'scroll', function( event ) {
		$(this).trigger( 'resize' );
	});

	/**
	 *
	 * Load more comments on click event
	 *
	 * @since  1.0.0
	 * 
	 */
    $( document ).on( 'click', '.load-comments.load-on-click', $.ajaxElementOnEventRequest );

	/**
	 *
	 * Load more posts
	 *
	 * @since  1.0.0
	 * 
	 */
	$( document ).on( 'click', '.widget-load-more-posts', $.ajaxElementOnEventRequest );

	$( document ).on( 'scrollin', '.widget-load-more-posts.load-on-scroll', $.ajaxElementOnEventRequest );

	$( document ).on( 'scrollin', '.load-users', $.ajaxElementOnEventRequest );
	$( document ).on( 'click', '.load-users', $.ajaxElementOnEventRequest );

	$( document ).on( 'scrollout', '.player-wrapper.jsappear', function( event = null, $elements ){
		$(this).find( '.player-container' ).addClass( 'animate slideIn rounded sticky-player shadow' );
	} );

	$( document ).on( 'scrollin', '.player-wrapper.jsappear', function( event = null, $elements ){
		$(this).find( '.player-container' ).removeClass( 'animate slideIn rounded sticky-player shadow' );
	} );

	$( document ).on( 'click', '.player-wrapper .player-header button', function( event ){
		$(this)
		.closest( '.player-container' )
		.removeClass( 'rounded sticky-player shadow' )
		.parent().removeClass( 'jsappear' );
	} );

	/**
	 *
	 * scroll trigger
	 * @since  1.0.0
	 */
	$( '.single-video__body' ).on( 'scroll', function( event ) {

		$(this).trigger( 'resize' );
	});

	/**
	 *
	 * Row IDs auto select handler
	 *
	 * @since  1.0.0
	 * 
	 */
	$( document ).on( 'change', 'input[name=row_id]', function( event ){

		var input 		= $(this);
		var isChecked	= input.is( ':checked' );
		var table 		= input.closest( 'table' );

		table.find( '.row-id-input' ).each( function( k, v ){
			console.log(isChecked);
			if( isChecked ){
				$(this).prop('checked', true );
			}
			else{
				$(this).prop('checked', false );
			}
		} );
	} )

	/**
	 *
	 * updatePostMessageModal handler
	 * @since  1.0.0
	 */
	$( '#updatePostMessageModal' ).on( "show.bs.modal", function ( event ) {

		var modal = $(this);

		var clickedBtn = $(event.relatedTarget);

		modal.find( 'button[type=submit]' ).html( clickedBtn.html() );
		modal.find( 'input[name=action]' ).val( clickedBtn.attr( 'data-action' ) );
		modal.find( 'input[name=post_id]' ).val( clickedBtn.attr( 'data-post-id' ) );

		var thumbnail = clickedBtn.closest( 'tr' ).find('.post-title').clone();

		modal.find( '.modal-body' ).find( '.post-title' ).remove();
		modal.find( '.modal-body' ).prepend( thumbnail );
	});

	/**
	 *
	 * deletePostModal handler
	 * @since  1.0.0
	 */
	$( '#deletePostModal' ).on( "show.bs.modal", function ( event ) {

		var modal = $(this);

		var clickedBtn = $(event.relatedTarget);

		modal.find( 'input[name=post_id]' ).val( clickedBtn.attr( 'data-post-id' ) );

		var title = clickedBtn.closest( 'tr' ).find('.post-title').clone();
		var thumbnail = clickedBtn.closest( 'tr' ).find('.post-thumbnail').clone();

		if( thumbnail.length != 0 ){
			modal.find( '.post-list-wrap' ).html( thumbnail ).append(title).removeClass('d-none');
		}
	});

	/**
	 *
	 * Upload video file on changing
	 *
	 * @since 1.0.0
	 * 
	 */
	$( document ).on( 'change', 'input[name=video_file]', function( event ){
		var files = event.target.files || event.dataTransfer.files;
		return uploadVideoController( files, $(this).closest( 'form' ) );
	} );

	/**
	 * Drag drop upload
	 *
	 * @since 1.0.0
	 * 
	 */
	var dragDropContainer = document.querySelector( '.upload-form__label' );

	if( dragDropContainer !== null && dragDropContainer.length != 0 ){
		/**
		 *
		 * Add dragover class
		 *
		 * @since 1.0.0
		 * 
		 */
		dragDropContainer.addEventListener( 'dragover', function(event) {
			event.preventDefault();
			event.stopPropagation();			
			$(this).addClass( 'drag-over' );
		} , false );

		/**
		 *
		 * Add dragleave class
		 *
		 * @since 1.0.0
		 * 
		 */
		dragDropContainer.addEventListener( 'dragleave', function(event) {
			$(this).removeClass( 'drag-over' );
		});

		/**
		 *
		 * do upload on droping
		 *
		 * @since 1.0.0
		 * 
		 */
		dragDropContainer.addEventListener( 'drop' , function(event) {
			event.preventDefault();
			event.stopPropagation();
			$(this).removeClass( 'drag-over' );
			var files = event.target.files || event.dataTransfer.files;

			return uploadVideoController( files, $(this).closest( 'form' ));
		});
	}

	/**
	 *
	 * Show animation image on hovering
	 *
	 * @since 1.0.6
	 * 
	 */
	$( document ).on( 'mouseover touchstart', '.type-video .post-thumbnail', function(e){
		var thumbnail 			= $(this);

		var parent 				= thumbnail.closest( '.type-video' );

		var thumbnailImage2Url 	= parent.attr( 'data-thumbnail-image-2' );

		if( thumbnailImage2Url !== undefined ){
			var imageTag = '<img class="thumbnail-image-2" src="'+thumbnailImage2Url+'">';

			if( thumbnail.find( '.thumbnail-image-2' ).length != 0 ){
				thumbnail.find( '.thumbnail-image-2' )
				.attr( 'src', thumbnailImage2Url )
				.show();
			}
			else{
				thumbnail.append( imageTag );
			}
		}
	});

	$( document ).on( 'mouseout touchend', '.type-video .post-thumbnail', function(e){
		$(this).find( '.thumbnail-image-2' ).attr( 'src', '' ).hide();
	});

	/**
	 *
	 * Edit Comment modal on show event handler
	 *
	 * @since 1.0.8
	 * 
	 */
	$( '#modal-edit-comment' ).on( 'shown.bs.modal', function( event ){

		var modal 		= $(this);
		var button 		= event.relatedTarget;
		var comment 	= $.parseJSON( $( button ).attr( 'data-params' ) );

		modal.find( 'input[name=comment_ID]' ).val( comment.comment_id );

		var requestUrl = streamtube.ajaxUrl + '?action=get_comment&comment_id=' + comment.comment_id + '&_wpnonce='+streamtube._wpnonce;

		$.get( requestUrl, function( response ){

			//$.editorRemove( '_comment_content' );

			var content = response.data.comment_content;

			if( content != "" ){
				content = content.replace( '/\r?\n/g', '<br />');
			}

			modal.find( '#_comment_content' ).val( content );

			$.editorInit( '_comment_content' );

			modal
			.find( '.spinner-wrap' )
			.addClass( 'd-none' )
			.next()
			.removeClass( 'd-none' );
		} );
	} );

	/**
	 *
	 * Edit Comment modal on hidden event handler
	 *
	 * @since 1.0.8
	 * 
	 */
	$( '#modal-edit-comment' ).on( 'hidden.bs.modal', function( event ){
		var modal = $(this);
		modal.find( 'input[name=comment_ID]' ).val('0');
		modal
		.find( '.spinner-wrap' )
		.removeClass( 'd-none' )
		.next()
		.addClass( 'd-none' );		

		$.editorRemove( '_comment_content' );
	});

	/**
	 *
	 * Private Message modal on show event handler
	 *
	 * @since 1.1.5
	 * 
	 */
	$( '#modal-private-message' ).on( 'show.bs.modal', function( event ){
		var modal 			= $(this);
		var button 			= event.relatedTarget;
		var recipient_id 	= $( button ).attr( 'data-recipient-id' );
		var queryParams 	= '?action=get_recipient_info&recipient_id=' +recipient_id + '&_wpnonce='+streamtube._wpnonce;
		var requestUrl 		= streamtube.ajaxUrl + queryParams;

		$.get( requestUrl, function( response ){

			if( response.success == false ){
				$.showToast( response.data[0].message, 'danger' );
			}else{

				var avatar = '<div class="avatar-wrap m-4">';
					avatar += response.data.avatar;
				avatar += '</div>';

				modal.find( 'form' ).prepend( avatar );

				modal.find( '#recipients' ).val( recipient_id );
			}

			modal
			.find( '.spinner-wrap' )
			.addClass( 'd-none' )
			.removeClass( 'd-block' );
		} );
	} );

	/**
	 *
	 * Private Message modal on hidden event handler
	 *
	 * @since 1.1.5
	 * 
	 */
	$( '#modal-private-message' ).on( 'hidden.bs.modal', function( event ){
		var modal = $(this);
		modal.find( '#recipients' ).val('');
		modal.find( '#subject' ).val('');
		modal.find( '#message' ).val('');
		modal
		.find( '.spinner-wrap' )
		.removeClass( 'd-block' )
		.addClass( 'd-none' );

		modal.find( '.avatar-wrap' ).remove();
	});

	$( document ).on( 'click', '.edit-inline-comment .btn-cancel', function( event ){
		var form = $(this).closest( 'form' );
		form.prev().slideDown();
		form.remove();
	} );

	$( window ).on( 'load', multipleCheckboxesAction );

	$( document ).on( 'click', 'ul.categorychecklist input', function(e){
		var clickedCb 	= $(this);
		var isChecked 	= clickedCb.is( ':checked' );
		var list 		= clickedCb.closest( 'ul.categorychecklist' );
		var checkedCk 	= 0;
		var max			= parseInt( list.attr( 'data-max-items' ) );

		if( max == 0 ){
			return;
		}

		var childList = clickedCb.parent().next( '.children' );

        if( childList.length !== 0 ){
            if( isChecked ){
                childList.addClass( 'd-block' ).removeClass( 'd-none' );
                clickedCb.closest( 'li' ).addClass( 'child-expanded' );
            }
            else{
                childList.addClass( 'd-none' ).removeClass( 'd-block' );
                clickedCb.closest( 'li' ).removeClass( 'child-expanded' );
                // Find and clear all childs
                childList.find( 'input' ).each( function( k,v ){
                    $(this).prop( 'checked', false );
                } );
            }
        }

		list.find( 'input[type=checkbox]' ).each(function () {
			if( $( this ).is( ':checked' ) ){
				checkedCk++;
			}
		});

		if( checkedCk >= max ){
			list.find( 'input[type=checkbox]' ).each(function () {
				if( ! $( this ).is( ':checked' ) ){
					$(this).attr('disabled', 'disabled');
				}				
			});
		}else{
			list.find( 'input[type=checkbox]' ).each(function () {
				if( ! $( this ).is( ':checked' ) ){
					$(this).removeAttr('disabled');
				}				
			});			
		}
		
	} );

	/**
	 * Dashboard Category metabox handler
	 */
	$( document ).on( 'click', '.categorydiv .category-tabs a', function(e){
		e.preventDefault();
		var current = $(this);
		var href = current.attr( 'href' );
		var list = current.closest('ul');
		var container = list.parent();

		list.find( 'li' ).removeClass( 'tabs' );
		current.parent().addClass( 'tabs' );

		container.find( '.tabs-panel' ).css( 'display', 'none' );
		container.find( href ).css( 'display', 'block' );
	} );

	$( document ).on( 'click', '.categorydiv .taxonomy-add-new', function(e){
		e.preventDefault();

		$(this).parent()
		.toggleClass( 'wp-hidden-children' )
		.find( 'input[type=text]' ).val('').focus();
	});

	$( document ).on( 'click', '.categorydiv input[data-wp-lists]', function(e){
		e.preventDefault();

		var button 								= $(this);
		var dataList 							= button.attr( 'data-wp-lists' ).split(':');
		var taxonomy 							= dataList[2].split('-');

		var container 							= button.parent();

		var newcategories 						= container.find( 'input[type=text]' );

		if( newcategories.val() == "" ){
			return newcategories.focus();
		}

		var newcategories_parent 				= container.find( 'select' ).val();
		var nonce 								= container.find( 'input[name=_ajax_nonce-add-'+ taxonomy[0] +']' ).val();

		var list 								= $( 'ul#'+ taxonomy[0] +'checklist'  );

		var taxInput 							= [];

		list.find( '.selectit input' ).each(function( index ) {
			if( $( this ).is( ':checked' ) ){
				taxInput.push( $( this ).attr( 'value' ) );
			}
		});

		button.addClass( 'disabled' );

		$.post( streamtube.ajaxUrl, {
			action 								: 'add-' + taxonomy[0],
			["new" + taxonomy[0]]				: newcategories.val(),
			['new'+taxonomy[0]+'_parent']		: newcategories_parent,
			["_ajax_nonce-add-" + taxonomy[0]]	: nonce,
			_ajax_nonce 						: 0,
			['tax_input' + '[' + taxonomy[0]] 	: taxInput
		}, function( response ){

			var wpResponse = wpAjax.parseAjaxResponse( response, 'ajax-response');

			var listItem = wpResponse.responses[0].data;

			if( listItem ){

				if( parseInt( newcategories_parent ) == -1 ){
					list.append( listItem );
				}else{

					var parentId = wpResponse.responses[0].id;

					$( 'li#'+ taxonomy[0] + '-' + parentId ).replaceWith( listItem );

					var parent = $( 'li#'+ taxonomy[0] + '-' + parentId );

					if( parent.find( '>ul.children' ).length == 0 ){
						parent.append( '<ul class="children"></ul>' );
					}

					parent
					.prev()
					.appendTo( parent.find( '>ul.children' ) );
				}

				container.find( 'select' ).replaceWith( wpResponse.responses[0].supplemental.newcat_parent );

				for ( var i = 0; i < taxInput.length; i++ ) {
					list.find( '.selectit input' ).each(function( index ) {
						if( parseInt( $(this).val() ) == parseInt( taxInput[i]) ){
							$(this).attr( 'checked', 'checked' );
						}
					});
				}
			}

			button.removeClass( 'disabled' );
			newcategories.val('');
		} );
	});

	/**
	 *
	 * Chapter Permalink on Click event
	 * 
	 */
	$( document ).on( 'click', '.timestamp-url', function(e){
		e.preventDefault();

		var me 		= $(this);
		var a 		= '';

		if( me.is( 'li' ) ){
			a = me.find( 'a' );
		}else{
			a = me;
		}

		var time 	= parseInt( a.attr( 'data-total-seconds' ) );

		if( $( 'video-js' ).length == 0 ){
			return;
		}		

		var player  = videojs( "player_" + $( 'video-js' ).attr( 'data-player-id' ) );

		player.play();
		player.currentTime(time);

		player.el_.scrollIntoView({behavior: "smooth", block: "end", inline: "nearest"});
	});

	$( document ).on( 'click', 'form.form-transfer-point button[type=button]', function(e){
		var button = $(this);
		var form = button.closest( 'form' );
		form.toggleClass( 'form-step-final' );

		form.find( '.total-amount' ).html( form.find( 'input[name=amount]' ).val() );
	} );

	$( document ).on( 'click', 'button.mycred-buy-link', function(e){
		e.preventDefault();
		var button = $(this);
		var widget = button.closest( 'div.widget' );
		widget.find( 'input[name=amount]' ).val( button.attr( 'data-amount' ) );
		widget.find( 'button.btn-submit' ).removeClass( 'disabled' );
	} );

	$( document ).on( 'change', 'input[name=amount]', function(e){
		if( $(this).val() != "" ){
			$(this).closest( 'form' ).find( 'button.btn-submit' ).removeClass( 'disabled' );	
		}else{
			$(this).closest( 'form' ).find( 'button.btn-submit' ).addClass( 'disabled' );
		}
		
	});

})(jQuery);
