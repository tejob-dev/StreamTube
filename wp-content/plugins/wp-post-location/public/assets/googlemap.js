(function($) {
    'use strict';

    var mapId       = 'wp_post_location_map';
    var mapObject   = document.getElementById( mapId );
    var mapJson     = {};
    var googleMap   = null;

    var markers     = [];

    var infoWindows = [];

    function initGoogleMap() {

        mapJson = $.parseJSON( mapObject.getAttribute( 'data-setup' ) );

        var _locations = mapJson.locations;

        googleMap = new google.maps.Map( mapObject, mapJson );

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
            googleMap.setCenter( { 
                lng : parseFloat( _locations[0].lng ), 
                lat : parseFloat( _locations[0].lat )
             } );  

        }else{

            if ( typeof _locations == 'string' && _locations == 'all' ) {
                loadAllLocations();
            }
            else{
                googleMap.setCenter( { 
                    lng : parseFloat( mapJson.center.lng ), 
                    lat : parseFloat( mapJson.center.lat )
                 } );
            } 
        }        

        if( mapJson.edit_mode == true ){

            googleMap.addListener( 'click', function(event){

                var latLng = event.latLng.toJSON();

                removeMarkers();

                addMarker( latLng.lng, latLng.lat );

                updateLocationFields( latLng.lng, latLng.lat, googleMap.getZoom() );

            });
        }

        if( mapJson.search_field != "" ){
            autoComplete( mapJson.search_field );
        }

        google.maps.event.addListener( googleMap, 'zoom_changed', function() {
            var zoomLevel = googleMap.getZoom();

            $( '#post-zoom' ).val( zoomLevel );
            $( '#field-zoom' ).html( zoomLevel ); 
        });

        google.maps.event.addListener( googleMap, 'bounds_changed', updateBounds );
    }

    initGoogleMap();

    /**
     *
     * Auto Completes search field
     *
     * @since 1.0.0
     * 
     */
    function autoComplete( fieldId = 'search-input' ){
        var input = document.getElementById( fieldId );
        const searchBox = new google.maps.places.SearchBox(input);

        searchBox.addListener("places_changed", function(e){
            const places = searchBox.getPlaces();

            if ( places.length == 0) {
                return;
            }

            const bounds = new google.maps.LatLngBounds();

            for ( var i = 0; i < places.length; i++ ) {

                if ( places[i].geometry.location && mapJson.edit_mode == true ) {
                    addMarker( places[i].geometry.location.lng(), places[i].geometry.location.lat() );
                }

                if ( places[i].geometry.viewport ) {
                    bounds.union( places[i].geometry.viewport );
                } else {
                    bounds.extend( places[i].geometry.location );
                }                
            }

            if( places.length == 1 ){
                updateLocationFields( places[0].geometry.location.lng(), places[0].geometry.location.lat(), 19 );
            }

            googleMap.fitBounds(bounds);
        });
    }

    function updateBounds(){
        var bounds = googleMap.getBounds();
        var north  =   bounds.getNorthEast().lat();   
        var east   =   bounds.getNorthEast().lng();
        var south  =   bounds.getSouthWest().lat();   
        var west   =   bounds.getSouthWest().lng();         

        $( '#field-north' ).html( north );
        $( '#field-south' ).html( south );
        $( '#field-east' ).html( east );
        $( '#field-west' ).html( west );
    }

    /**
     *
     * Add Marker
     * 
     * @since 1.0.0
     */    
    function addMarker( lng, lat, post_data = null, icon = null ){

        var markerIcon = '';

        var infoWindow = new google.maps.InfoWindow({
            maxWidth: 300
        });

        var args = {
            position: {
            'lng' : parseFloat( lng ),
            'lat' : parseFloat( lat )
            },
            title : post_data != null ? post_data.title : '',
            map: googleMap,
            icon : icon
        }

        var marker = new google.maps.Marker(args);

        if( post_data != null ){
            marker.addListener( 'click', function( event ){

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

                closeInfoWindows();
                infoWindow.setContent(content);
                infoWindow.open(marker.getMap(), marker );
            } );
        }

        markers.push(marker);

        infoWindows.push( infoWindow );
    }

    /**
     *
     * Remove Markers
     * 
     * @since 1.0.0
     */
    function removeMarkers(){
        for ( var i=0; i< markers.length; i++) {
             markers[i].setMap(null);
        }

        markers = [];
    }

    function closeInfoWindows(){

        if( infoWindows.length == 0 ){
            return;
        }

        for ( var i=0; i < infoWindows.length; i++) {
             infoWindows[i].close();
        }        
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

        if( mapJson.ost_geocoding_api ){
            var params = {
                'format'    : 'json',
                'lon'       : lng,
                'lat'       : lat
            }

            var request_url = 'https://nominatim.openstreetmap.org/reverse' + '?' + $.param( params );

            $.get( request_url, function( response ){

                $( '.widget-location-details' ).removeClass( 'loading' );

                if( response.error ){
                    return $( '#field-address' ).html( '<span class="text-danger">'+ response.error +'</span>' );
                }

                $( '#post-address' ).val( response.display_name );

                return $( '#field-address' ).html( response.display_name );
            } );            
        }else{

            var geocoder = new google.maps.Geocoder();

            var latlng = {
                lat: lat,
                lng: lng
            }

            geocoder.geocode({ location: latlng }).then((response) => {

                var error_message = '';

                $( '.widget-location-details' ).removeClass( 'loading' );

                if( response.status != 'OK' ){
                    error_message = response.status;
                }

                if( error_message ){
                    return $( '#field-address' ).html( '<span class="text-danger">'+ error_message +'</span>' );
                }

                $( '#post-address' ).val( response.results[0].formatted_address );

                return $( '#field-address' ).html( response.results[0].formatted_address );
            })
            .catch((e) => window.alert("Geocoder failed due to: " + e));
        }
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

                googleMap.setCenter( { 
                    lng : data.lng, 
                    lat : data.lat
                 } );

                googleMap.setZoom( data.zoom );

                if( mapJson.edit_mode == true ){
                    //removeMarkers();

                    addMarker( data.lng, data.lat );

                    updateLocationFields( data.lng, data.lat, data.zoom );
                }
            } );
        } else { 
            $.showToast( wp_post_location.geo_not_supported );
        }
    }

    function searchLocations( event ){
        event.preventDefault();

        var button  = $(this);

        var search  = button.prev().val();
        var zoom    = $( '#post-zoom' ).val();

        if( ! zoom || zoom == undefined ){
            zoom = 5;
        }
        
        button.attr( 'disabled', 'disabled' );

        if( search == "" ){
            return button.removeAttr( 'disabled' );
        }

        if( mapJson.ost_geocoding_api ){
            var params = {
                'q'         : search,
                'format'    : 'json'
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
                googleMap.setCenter( { 
                    lng : parseFloat( response[0].lon ), 
                    lat : parseFloat( response[0].lat )
                 } );     

                googleMap.setZoom( parseInt( zoom ) );

                updateLocationFields( response[0].lon, response[0].lat, parseInt( zoom ) );
            } );           
        }else{

            var params  = {
                'address'   : search,
                'language'  : wp_post_location.language,
                'key'       : wp_post_location.google_map_api
            }

            $.get( 'https://maps.googleapis.com/maps/api/geocode/json?' + $.param( params ), function( response ){

                button.removeAttr( 'disabled' );

                $( document.body ).trigger( 'wp_searched_locations', [ response ] );

                if( response.status != 'OK' ){
                    if( wp_post_location.is_admin == true ){
                        return alert( response.status );
                    }else{
                        return $.showToast( response.status , 'danger' );
                    }
                }

                const lat = response.results[0].geometry.location.lat;
                const lng = response.results[0].geometry.location.lng;

                if( mapJson.edit_mode == true ){
                    removeMarkers();

                    addMarker( lng, lat );
                }

                googleMap.setCenter( { 
                    lng : lng, 
                    lat : lat
                 } );     

                googleMap.setZoom( parseInt( zoom ) );

                updateLocationFields( lng, lat, parseInt( zoom ) );
            } );
        }
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
            'action'         : 'get_all_locations',
            'posts_per_page' : perPage,
            'page'           : page,
            '_wpnonce'       : wp_post_location._wpnonce
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

            googleMap.setCenter( { 
                lng : 0, 
                lat : 0
             } );
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

    $( document.body ).on( 'theme_mode_changed', function( event, themeMode ){
        if( themeMode == 'light' ){
            googleMap.setOptions({
                styles : mapJson.default_style_json
            })
        }
        if( themeMode == 'dark' ){
            googleMap.setOptions({
                styles : mapJson.dark_style_json
            })
        }
    } )

})(jQuery);