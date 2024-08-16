(function( $ ) {
	'use strict';

	var mapId 		= 'wp_post_location_map';
	var mapObject 	= document.getElementById( mapId );
	var mapJson 	= {};
	var openMap 	= null;

	var markers 	= [];

    if( mapObject != null ){
    	openMap = L.map( mapId );
    	mapJson = $.parseJSON( mapObject.getAttribute( 'data-setup' ) );

    	initMap( mapJson );

    	if( mapJson.edit_mode == true ){
    		openMap.on( 'click', function(event){

    			removeMarkers();

    			addMarker( event.latlng.lng, event.latlng.lat );

    			updateLocationFields( event.latlng.lng, event.latlng.lat, event.sourceTarget._zoom );
    		} );

    		openMap.on( 'zoomend', function( event ){
    			var zoom = event.target.getZoom();

				$( '#post-zoom' ).val( zoom );
				$( '#field-zoom' ).html( zoom );    
    		} )
    	}
    }

    /**
     *
     * Add Marker
     * 
     * @since 1.0.0
     */
    function addMarker( lng, lat, post_data = null, icon = null ){

    	var args = {
    		post_data : post_data
    	}

    	if( icon ){
    		args.icon = L.icon({
    			iconUrl : icon
    		});
    	}

        var marker = L.marker( [ lat, lng ], args ).addTo( openMap );

        marker.on( 'click', function( e ){

        	if( post_data == null ){
        		return;
        	}

    		var popup 	= L.popup();

    		var content = '<div class="map-popup d-flex flex-column bg-light">';

	    		content += '<a class="post-permalink" href="'+ post_data.permalink +'">';
	        		content += '<div class="post-thumbnail ratio ratio-16x9 rounded overflow-hidden bg-dark">';
	        			content += '<img src="'+ post_data.thumbnail +'">';

	        			if( post_data.post_type == 'video' ){
	        				content += '<div class="video-hover">';
							    content += '<span class="icon-play top-50 start-50 translate-middle position-absolute"></span>';
							content += '</div>'
	        			}
	        		content += '</div>';
	    		content += '</a>';

	    		content += '<div class="post-meta p-3">';

                    content += '<h3 class="post-title post-title-md">';
                        content += '<a class="post-permalink" href="'+ post_data.permalink +'">' + post_data.title + '</a>';
                    content += '</h3>';

	        		content += '<div class="post-author">';
		        		content += '<span class="fw-bold author-by me-1">'+ wp_post_location.i18n.by +'</span>';
		        		content += '<a href="'+ post_data.author.link +'">';
		        			content += '<span class="author-name text-body fw-bold">'+ post_data.author.name+'</span>';
	        			content += '</a>';
	        		content += '</div>';

	        		content += '<div class="border-bottom my-2"></div>';

	        		content += '<ol class="post-address list-unstyled">';

	        			if( post_data.address ){
		        			content += '<li class="small d-flex">';
		        				content += '<span class="fw-bold me-2">'+ wp_post_location.i18n.address +'</span>';
			        			content += '<span>'+ post_data.address +'</span>';
		        			content += '</li>';
	        			}

	        			content += '<li class="small d-flex">';
	        				content += '<span class="fw-bold me-2">'+ wp_post_location.i18n.latitude +'</span>'
	        				content += '<span>'+ post_data.lat +'</span>';
	        			content += '</li>';

	        			content += '<li class="small d-flex">';
	        				content += '<span class="fw-bold me-2">'+ wp_post_location.i18n.longitude +'</span>';
	        				content += '<span>'+ post_data.lng +'</span>';
	        			content += '</li>';
	        		content += '</ol>';

    		content += '</div>';

            popup.setLatLng(e.latlng)
            .setContent( content )
            .openOn(openMap);
        } );

        markers.push( marker );
    }

    /**
     *
     * Remove Markers
     * 
     * @since 1.0.0
     */
    function removeMarkers(){
        for ( var i=0; i<markers.length; i++) {
            openMap.removeLayer( markers[i]);
        }

        markers = [];    	
    }

    /**
     *
     * Set view
     * 
	 * @since 1.0.0
     */
    function setView( lng, lat, zoom = 15 ) {
    	openMap.setView( [ lat, lng ] , zoom );
    }

    /**
     *
     * @since 1.0.0
     */
    function initMap( data ){ 

    	var _locations = data.locations;

    	if( $.isArray( _locations ) && _locations.length > 0 ){
    		for ( var i = 0; i < _locations.length; i ++  ) {
                var icon = '';

                if( _locations[i].type == 'video' ){
                    icon = mapJson.video_marker;
                }

                if( _locations[i].type == 'post' ){
                    icon = mapJson.post_marker;
                }        			
				addMarker( _locations[i].lng, _locations[i].lat, _locations[i], icon );
    		}

    		setView( _locations[0].lng , _locations[0].lat, _locations[0].zoom );

    	}else{

    		if ( typeof _locations == 'string' && _locations == 'all' ) {
    			loadAllLocations();
    		}
    		else{
    			setView( data.center.lng , data.center.lat, data.zoom );	
    		}
    	}

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom   : data.max_zoom,
            className : data.dark_class
        }).addTo( openMap );
    }

    /**
     *
     * Update location field values
     * 
     * @param  string lat
     * @param  string lng
     * @since 1.0.0
     */
    function updateLocationFields( lng, lat, zoom ){
    	$( '#post-longitude' ).val( lng );
    	$( '#post-latitude' ).val( lat );
    	$( '#post-zoom' ).val( zoom );

    	$( '#field-longitude' ).html( lng );
    	$( '#field-latitude' ).html( lat );
    	$( '#field-zoom' ).html( zoom );    

    	$( '.widget-location-details' ).addClass( 'loading' );

    	var params = {
    		'format' 	: 'json',
    		'lon'	 	: lng,
    		'lat'	 	: lat
    	}

    	var request_url = 'https://nominatim.openstreetmap.org/reverse' + '?' + $.param( params );

    	$.get( request_url, function( response ){

    		$( '.widget-location-details' ).removeClass( 'loading' );

    		if( response.error ){
    			return $( '#field-address' ).html( '<span class="text-danger">'+ response.error +'</span>' );
    		}

    		$( '#post-address' ).val( response.display_name );

    		$( '#post-country' ).val( response.address.country );

    		$( '#post-country-code' ).val( response.address.country_code );

    		return $( '#field-address' ).html( response.display_name );
    	} );
    }

	/**
	 *
	 * Find My Location handler
	 * 
	 * @since  1.0.0
	 */
	function findMyLocation(){
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition( function( position ){
		        var data = {
		            'lng' : position.coords.longitude,
		            'lat' : position.coords.latitude,
		            'zoom' : 15
		        }
		        setView( data.lng, data.lat , data.zoom );

		        if( mapJson.edit_mode == true ){
			        removeMarkers();

			        addMarker( data.lng, data.lat );

			        updateLocationFields( data.lng, data.lat, data.zoom );
		    	}
            } );
        } else { 
            $.showToast( wp_post_location.geo_not_supported );
        }

	}

	function searchLocations(){

		var button = $(this);
		var search = button.prev().val();

        var zoom    = $( '#post-zoom' ).val();

        if( ! zoom || zoom == undefined ){
            zoom = 5;
        }		

		button.attr( 'disabled', 'disabled' );

		if( search == "" ){
			return button.removeAttr( 'disabled' );
		}

		var params = {
			'q' 		: search,
			'format' 	: 'json'
		}

		var request_url = 'https://nominatim.openstreetmap.org' + '?' + $.param( params );

		$.get( request_url, function( response ){

			button.removeAttr( 'disabled' );

			$( document.body ).trigger( 'wp_searched_locations', [ response ] );

			if( response.length == 0 ){
                if( wp_post_location.is_admin == true ){
                    return alert( wp_post_location.i18n.search_location_empty );
                }else{
                    return $.showToast( wp_post_location.i18n.search_location_empty , 'danger' );
                }
			}

            setView( response[0].lon, response[0].lat , zoom );

            updateLocationFields( response[0].lon, response[0].lat , zoom );

		} );
	}  	

	/**
	 *
	 * updateLocation handler
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
	function updateLocation( event, responseData, textStatus, jqXHR, formData, form  ){

		if( responseData.success === false ){
			$.showToast( responseData.data[0].message , 'danger' );
		}else{
			$.showToast( responseData.data.message , 'success' );
		}
	}

	function resetLocation( event, responseData, textStatus, jqXHR, formData, form ){
		if( responseData.success === false ){
			$.showToast( responseData.data[0].message , 'danger' );
		}else{
			window.location.href = window.location.href;
		}
	}

	/**
	 *
	 * Load all locations
	 * 
	 * @param  {Number} page
	 * @param  {Number} perPage
	 *
	 * @since 1.0.0
	 * 
	 */
	function loadAllLocations( page = 1, perPage = -1 ){

		var params = {
			'action' 		 : 'get_all_locations',
			'posts_per_page' : perPage,
			'page'	 		 : page,
			'_wpnonce'		 : wp_post_location._wpnonce
		}

		var request_url = wp_post_location.ajax_url + '?' + $.param( params );

		$.get( request_url, function( response ){

			$( document.body ).trigger( 'wp_loaded_all_locations', [ response ] );

			var _locations = response.data;

    		for ( var i = 0; i < _locations.length; i ++  ) {
                var icon = '';

                if( _locations[i].type == 'video' ){
                    icon = mapJson.video_marker;
                }

                if( _locations[i].type == 'post' ){
                    icon = mapJson.post_marker;
                }       			
				addMarker( _locations[i].lng, _locations[i].lat, _locations[i], icon );
    		}

    		setView( 0, 0, 2 );
		} );
	}

	/**
	 *
	 * Find my location click event
	 *
	 * @since  1.0.0
	 * 
	 */
	$( document ).on( 'click', 'button#find-my-location', findMyLocation );

	$( document ).on( 'click', 'button#search-locations', searchLocations );	

	/**
	 *
	 * Update Location event
	 *
	 * @since 1.0.0
	 * 
	 */
	$( document.body ).on( 'update_location', updateLocation );

	$( document.body ).on( 'reset_location', resetLocation );

})( jQuery );