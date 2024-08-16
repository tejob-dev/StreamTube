(function($) {
    "use strict";

    $.ajaxSetup({
        headers : {
            'X-WP-Nonce' : streamtube.nonce
        }
    });

    $( document.body ).on( 'load_analytics_overview_done', loadAnalyticsOverviewTabs );

    $( document.body ).on( 'analytics_overview_tabs_loaded', loadAnalyticsOverviewCharts );

    $( document.body ).on( 'load_analytics_overview_video_done', loadAnalyticsOverviewVideoTabs );

        $( document.body ).on( 'analytics_overview_video_tabs_loaded', loadAnalyticsVideoCharts );
  
    $( document ).on( 'shown.bs.tab', '.analytics-tab[data-bs-toggle="tab"]', function( event ){

        var tab     = event.target.getAttribute( 'data-bs-target' ).replace( '#', '' );
        var params  = $.parseJSON( $( '#analytics-overview' ).attr( 'data-params' ) );
        var name    = 'test';

        tabSwitcher( tab, params );
    } );

     /**
     *
     * Tab switcher
     * @since 1.0.8
     */
    function tabSwitcher( tab, params ){

        switch(tab){
            case 'analytics-content-eventCount':
                return draweventCountReports( params , tab );
            break

            case 'analytics-content-screenPageViews':
                return drawPageViewsReports( params , tab );
            break;                   

            case 'analytics-content-totalUsers':
                return drawtotalUsersReports( params , tab );
            break;

            case 'analytics-content-newUsers':
                return drawnewUsersReports( params , tab );
            break;            

            case 'analytics-content-sessions':
                return drawSessionsReports( params , tab );
            break;

            case 'analytics-content-bouncerate':
                return drawBounceRateReports( params , tab );
            break;

            case 'analytics-content-userEngagementDuration':
                return drawuserEngagementDurationReports( params , tab );
            break;

            case 'analytics-video-content-videoviews':
                return drawTotalVideoViewsReports( params , tab );
            break;

            case 'analytics-video-content-uniquevideoviews':
                return drawUniqueVideoViewsReports( params , tab );
            break;
        }
    }

    // Returns an array of dates between the two dates
    function getDateRange (startDate, endDate) {
        const dates = []
        let currentDate = startDate
        const addDays = function (days) {
            const date = new Date(this.valueOf())
            date.setDate(date.getDate() + days)
            return date;
        }
        while (currentDate <= endDate) {
            dates.push(currentDate)
            currentDate = addDays.call(currentDate, 1);
        }
        return dates
    }

    function dateToString( date ){
        var year = date.getFullYear().toString();
        var month = date.getMonth() + 1;
        month = month.toString();
        var day = date.getDate().toString();

        if( parseInt( month ) < 10 ){
            month = '0' + month.toString();
        }

        if( parseInt( day ) < 10 ){
            day = '0' + day.toString();
        }

        return year + month + day;
    }

    function showError( message, elementObject ){
        var output = '<div class="position-absolute top-50 start-50 translate-middle">';
            output += '<div class="msg text-muted">'+ message +'</div>';
        output += '</div>'
        return elementObject.html( output);
    }

    /**
     *
     * Convert given number to Session Duration format
     */
    function convertNumberToSessionDuration( value ){
        const sec = parseInt(value, 10);
        let hours   = Math.floor(sec / 3600);
        let minutes = Math.floor((sec - (hours * 3600)) / 60);
        let seconds = sec - (hours * 3600) - (minutes * 60);
    
        if( hours == 0 ){
            if( minutes > 0 ){
                return minutes + analytics.minute + ' ' + seconds + analytics.second;
            }
            else{
                return seconds + analytics.second;
            }
        }

        return hours + analytics.hour + ' ' + minutes + analytics.minute + ' ' + seconds + analytics.second;
    }

    /**
     *
     * Get  google search console with given keyword and date range
     *
     * @return string
     * 
     * @since 1.0.8
     */
    function getGoogleSearchConsoleUrl( keyword, start_date = '', end_date = '' ){
        var url = 'https://search.google.com/search-console/performance/search-analytics';
        url += '?start_date=' + start_date.replace(/([-])+/g, '' );
        url += '&end_date=' + end_date.replace(/([-])+/g, '' );
        url += '&query=!' + keyword;
        url += '&resource_id=' + analytics.home_url;
        return url;
    }

    /**
     *
     * format date
     * 
     * @param string date yyymmdd
     */
    function formatDate( date = '' ){
        return date.replace( /(\d{4})(\d{2})(\d{2})/g, "$1-$2-$3" ).split("-");
    }

    function getSessionStorage( dataPoint = '', params = [] ){

        if( window.sessionStorage == undefined || ! analytics.session_storage ){
            return false;
        }

        params.dataPoint    = dataPoint;
        params.userid       = analytics.user_id;

        var sessionId       = 'ga4' + btoa(JSON.stringify( params ));

        var session         = sessionStorage.getItem( sessionId );

        if( session ){
            return $.parseJSON( session );
        }

        return false;
    }

    function setSessionStorage( data, dataPoint = '', params = [] ){

        if( window.sessionStorage == undefined || ! analytics.session_storage ){
            return false;
        }

        params.dataPoint    = dataPoint;
        params.userid       = analytics.user_id;

        var sessionId       = 'ga4' + btoa(JSON.stringify( params ));

        return sessionStorage.setItem( sessionId, JSON.stringify(data) );
    }

    function getToolTipHTML( dateRanges0, dateRanges1, dataTable, i = 0, name = '', requestId ){
        var output = '';

        var date0 = dateRanges0[i].toDateString();
        var date1 = dateRanges1[i].toDateString();


        var last        = dataTable.getValue( i, 2 );
        var previous    = dataTable.getValue( i, 3 );
        var percent     = 100;

        if( last == 0 && previous == 0 ){
            percent = 0;
        }else{
            if( previous == 0 ){
                percent = 100;
            }
            else{
                percent = last*100/previous - 100;    
            }
        }

        if( $.isNumeric( percent ) ){
            percent = Math.floor( percent, 2 );
        }
        else{
            percent = 0;
        }
        
        var percentText = '';

        var downUp = percent < 0 ? 'down' : 'up';

        percentText += '<div class="d-flex percent percent-icon-'+downUp+'">';

            percentText += '<span class="icon-'+downUp+'"></span>';

            percentText += '<span class="fw-bold">'+ percent.toString() +'%</span>';

        percentText += '</div>';

        if( requestId == 'userEngagementDuration' ){
            last = convertNumberToSessionDuration( last );
        }        

        output += '<div class="custom-tooltip p-2">';
            output += '<div class="tooltip-head d-block">'
                output += '<span class="fw-bold">'+ date0 +'</span>';
                    output += '<span class="px-1 text-muted">vs</span>';
                output += '<span class="fw-bold">'+ date1 +'</span>';
            output += '<div>';

            output += '<div class="tooltip-body d-flex align-items-center gap-3 mt-2">';

                output += '<div class="fw-bold">'+ name +'</div>';

                output += '<div class="fw-bold">'+ last +'</div>';

                output += percentText;

            output += '<div>';
        output += '<div>';

        return output;
    }

    /**
     *
     * Draw LineChart from given Rest Response
     * 
     * @param  array response
     * @param  string elementId
     * @return Draw the line chart
     * 
     */
    function drawLineChart( response, elementId ){

        var name        = response.data.headers;
        var rows        = response.data.response.rows;
        var requestId   = response.data.requestId;

        google.charts.load( 'current', {
                'packages':['corechart'], 
                'language' : streamtube.language
            }
        );

          google.charts.setOnLoadCallback(_drawLineChart);

          function _drawLineChart() {

            var dataTable = new google.visualization.DataTable();

            dataTable.addColumn( 'date', 'Date' );
            dataTable.addColumn( {'type': 'string', 'role': 'tooltip', 'p': {'html': true}} );
            dataTable.addColumn( 'number', name );
            dataTable.addColumn( 'number', analytics.previous_period );

            var rowsLength = rows.length;

            var range0 = [], range1 = [], dates = [];

            for ( var i = 0; i < rowsLength; i++ ) {

                if( rows[i].dimensionValues[1].value == 'date_range_0' ){

                    if( parseInt( rows[i].metricValues[0].value ) != 0 ){
                        range0[rows[i].dimensionValues[0].value] = rows[i].metricValues[0].value;
                    }
                    
                }

                if( rows[i].dimensionValues[1].value == 'date_range_1' ){
                    if( parseInt( rows[i].metricValues[0].value ) != 0 ){
                        range1[rows[i].dimensionValues[0].value] = rows[i].metricValues[0].value;
                    }  
                }
            }

            var startDate0 = response.data.params.dateRanges[0].startDate;
            var endDate0 = response.data.params.dateRanges[0].endDate;

            const dateRanges0 = getDateRange( new Date( startDate0 ), new Date( endDate0 ) );


            var startDate1 = response.data.params.dateRanges[1].startDate;
            var endDate1 = response.data.params.dateRanges[1].endDate;

            const dateRanges1 = getDateRange( new Date( startDate1 ), new Date( endDate1 ) );

            for ( var i = 0; i < dateRanges0.length; i++ ) {
                var dateInRange0 = dateToString(dateRanges0[i]);
                dataTable.addRow( [
                        new Date( formatDate(dateToString( dateRanges0[i] )) ),
                        '',
                        0,
                        0
                    ]
                );

                if( range0.hasOwnProperty( dateInRange0 )  ){
                    dataTable.setCell( i, 2, parseInt( range0[dateInRange0] ) );
                }else{
                    dataTable.setCell( i, 2, 0 );
                }
            }

            for ( var i = 0; i < dateRanges1.length; i++ ) {
                var dateInRange1 = dateToString(dateRanges1[i]);

                if( range1.hasOwnProperty( dateInRange1 )  ){
                    dataTable.setCell( i, 3, parseInt( range1[dateInRange1] ) );
                }else{
                    dataTable.setCell( i, 3, 0 );
                }
            }

            for ( var i = 0; i < dateRanges0.length; i++ ) {
                //dataTable.setCell( i, 3, previous + percentText );
                dataTable.setCell( i, 1, getToolTipHTML( dateRanges0, dateRanges1, dataTable, i, name, requestId ) );
            }

            /**
            if( requestId == 'bouncerate' ){
                var formatter = new google.visualization.NumberFormat( {
                    fractionDigits: 0,
                    suffix:'%'
                } );

                formatter.format( dataTable, 1 );
            }
            **/

            if( requestId == 'userEngagementDuration' ){
                var formatter = new google.visualization.NumberFormat();
                formatter.format( dataTable, 1 );
            }

            var chart = new google.visualization.LineChart(document.getElementById( elementId ));

            chart.draw( dataTable , $.parseJSON( $( '#googlesitekit-reports' ).attr( 'data-linechart-options' ) ) );
        }
    }

    /**
     *
     * eventCount reports
     * 
     */
    function draweventCountReports( params, elementId ){
        var dataPoint = 'eventCount';

        var session = getSessionStorage( dataPoint, params );

        if( session ){
            return drawLineChart( session, elementId ); 
        }

        // Get pageViews reports
        $.get( analytics.rest_url + '/analytics/reports/'+dataPoint+'?' + $.param( params ),function(response){
            
            if( response.success == false ){
                return showError( response.data.message, $( '#' + elementId ) );
            }

            setSessionStorage( response, dataPoint, params );

            return drawLineChart( response, elementId  );
        } );
    }

    /**
     *
     * Draw pageViews report
     * 
     * @param  array params
     * 
     */
    function drawPageViewsReports( params, elementId ){

        var dataPoint = 'screenPageViews';

        var session = getSessionStorage( dataPoint, params );

        if( session ){
            return drawLineChart( session, elementId ); 
        }

        // Get pageViews reports
        $.get( analytics.rest_url + '/googlesitekit/'+dataPoint+'?' + $.param( params ),function(response){
            
            if( response.success == false ){
                return showError( response.data.message, $( '#' + elementId ) );
            }

            setSessionStorage( response, dataPoint, params );

            return drawLineChart( response, elementId  );
        } );
    }

    /**
     *
     * Draw Users reports chart
     * 
     * @param  array params
     * 
     */
    function drawtotalUsersReports( params, elementId ){
        // Get users reports
        
        var dataPoint = 'totalUsers';

        var session = getSessionStorage( dataPoint, params );

        if( session ){
            return drawLineChart( session, elementId ); 
        }

        $.get( analytics.rest_url + '/googlesitekit/'+dataPoint+'?' + $.param( params ),function(response){
            if( response.success == false ){
                return showError( response.data.message, $( '#' + elementId ) );
            }

            setSessionStorage( response, dataPoint, params );

            return drawLineChart( response, elementId );
        } );        
    }

    /**
     *
     * Draw newUsers reports chart
     * 
     * @param  array params
     * 
     */
    function drawnewUsersReports( params, elementId ){
        // Get users reports
        
        var dataPoint = 'newUsers';

        var session = getSessionStorage( dataPoint, params );

        if( session ){
            return drawLineChart( session, elementId ); 
        }

        $.get( analytics.rest_url + '/googlesitekit/'+dataPoint+'?' + $.param( params ),function(response){
            if( response.success == false ){
                return showError( response.data.message, $( '#' + elementId ) );
            }

            setSessionStorage( response, dataPoint, params );

            return drawLineChart( response, elementId );
        } );
    }    

    /**
     *
     * Draw sessions reports chart
     * 
     * @param  array params
     * 
     */
    function drawSessionsReports( params, elementId ){

        var dataPoint = 'sessions';

        var session = getSessionStorage( dataPoint, params );

        if( session ){
            return drawLineChart( session, elementId ); 
        }

        $.get( analytics.rest_url + '/googlesitekit/'+dataPoint+'?' + $.param( params ),function(response){
            if( response.success == false ){
                return showError( response.data.message, $( '#' + elementId ) );
            }            

            setSessionStorage( response, dataPoint, params );

            return drawLineChart( response, elementId );      
        } );
    }

    /**
     *
     * Draw Session Duration reports chart
     * 
     * @param  array params
     * 
     */
    function drawuserEngagementDurationReports( params, elementId ){

        var dataPoint = 'userEngagementDuration';

        var session = getSessionStorage( dataPoint, params );

        if( session ){
            return drawLineChart( session, elementId ); 
        }

        $.get( analytics.rest_url + '/googlesitekit/'+dataPoint+'?' + $.param( params ),function(response){
            if( response.success == false ){
                return showError( response.data.message, $( '#' + elementId ) );
            }            

            setSessionStorage( response, dataPoint, params );

            return drawLineChart( response, elementId );      
        } );
    }

    function drawTotalVideoViewsReports( params, elementId ){
        
        var dataPoint = 'videoviews';

        var session = getSessionStorage( dataPoint, params );

        if( session ){
            return drawLineChart( session, elementId ); 
        }        

        $.get( analytics.rest_url + '/googlesitekit/'+dataPoint+'?' + $.param( params ),function(response){
            if( response.success == false ){
                return showError( response.data.message, $( '#' + elementId ) );
            }
            setSessionStorage( response, dataPoint, params );

            return drawLineChart( response, elementId );
        } );        
    }

    /**
     *
     * Get overview handler
     * 
     */
    function loadAnalyticsOverviewTabs( event, response, params, wrapper, textStatus, jqXHR ){

        if( response.success == false ){
            return showError( response.data.message, wrapper );
        }

        var tabs            = '';
        var tabsContent     = '';        

        var metrics         = $.parseJSON( $( '#googlesitekit-reports' ).attr( 'data-overview-metrics' ) );

        var data            = response.data.response.totals;
        var columnHeader    = response.data.headers;

        if( ! data[0].metricValues ){

            data[0].metricValues = [];

            for ( var i = 0; i < columnHeader.length; i++ ) {
                data[0].metricValues.push( [ { 'value' : 0 } ] );
            }
        }

        if( ! data[1].metricValues ){

            data[1].metricValues = [];

            for ( var i = 0; i < columnHeader.length; i++ ) {
                data[1].metricValues.push( [ { 'value' : 0 } ] );
            }
        }        

        for ( var i = 0; i < data[0].metricValues.length; i++ ) {

            var active = '';

            if( i == 0 ){
                active = 'active show';
            }

            var number1 = data[0].metricValues[i]['value'] !== undefined ? parseInt( data[0].metricValues[i]['value'] ) : 0;

            var number2 = data[1].metricValues[i]['value'] !== undefined ? parseInt( data[1].metricValues[i]['value'] ) : 0;

            var percent = Math.abs(100-( number1*100/number2 ));

            if( ! $.isNumeric( percent ) ){
                percent = 0;
            }

            var icon = number1 > number2 ? 'icon-up' : 'icon-down';

            if( percent == 0 ){
                icon = '';
            }

            var tabId = Object.keys(metrics)[i];

            if( tabId == 'userEngagementDuration' ){
                number1 = convertNumberToSessionDuration( number1 );
            }            

            // Tab selector
            tabs += '<li class="nav-item flex-fill" role="presentation">';
                tabs += '<div class="nav-link '+active+' analytics-tab" id="analytics-tab-'+tabId+'" data-bs-toggle="tab" data-bs-target="#analytics-content-'+tabId+'">';
                    tabs += '<h3 class="analytics-tab__label text-secondary">'+ columnHeader[i] +'</h3>';
                    tabs += '<div class="analytics-tab__number total-number text-body"><h3>'+ number1.toLocaleString() +'</h3></div>';
                    tabs += '<div class="analytics-tab__percent percent percent-'+icon+'">';
                        tabs += '<span class="analytics-tab__icon '+icon+'"></span>';
                        tabs += '<span class="analytics-tab__percent-number percent-number">'+ Math.floor( percent,2 ) +'%</span>';
                    tabs += '</div>';
                tabs += '</div>';
            tabs += '</li>'; 

            // Tab content
            tabsContent += '<div class="tab-pane fade '+ active +'" id="analytics-content-'+tabId+'" role="tabpanel">';
                tabsContent += '<div class="position-relative" id="analytics-chart-'+i+'" style="width: 100%; height: 400px;">';
                    tabsContent += '<div class="position-absolute top-50 start-50 translate-middle">';
                        tabsContent += '<div class="spinner-border text-secondary" role="status">';
                            tabsContent += '<span class="visually-hidden">Loading...</span>';
                        tabsContent += '</div>';
                    tabsContent += '</div>';
                tabsContent += '</div>';
            tabsContent += '</div>';

            $( document.body ).trigger( 'analytics_overview_tab_'+tabId+'_loaded', [ response, params, wrapper, textStatus, jqXHR ] );
        }
        // end for;

        tabs = '<ul class="nav nav-tabs analytics-tabs" id="analytics-overview-report-tabs" role="tablist">'+ tabs +'</ul>';

        tabsContent = '<div class="tab-content mt-5" id="analytics-tabs-content">'+ tabsContent +'</div>';

        wrapper.html( tabs + tabsContent );

        $( document.body ).trigger( 'analytics_overview_tabs_loaded', [ response, params, wrapper, textStatus, jqXHR ] );
    }

    /**
     * Load overview charts
     */
    function loadAnalyticsOverviewCharts( event, response, params, wrapper, textStatus, jqXHR ){
        // Load first chart.
        var tab = wrapper.find( 'ul#analytics-overview-report-tabs li:first-child .nav-link' ).attr( 'data-bs-target' );

        return tabSwitcher( tab.replace( '#', '' ), params );
    }

    /**
     *
     * Fires after overview tabs loaded
     * 
     */
    function loadAnalyticsVideoOverview( wrapper ){

        if( wrapper.length == 0 ){
            return;
        }     

        var action      =   'load_analytics_overview_video';

        var params = $.parseJSON( wrapper.attr( 'data-params' ) );

        var dataPoint = 'videooverview';

        var session = getSessionStorage( dataPoint, params );

        if( session ){
            return $( document.body ).trigger( action + '_done', [ session, params, wrapper ] );
        }

        $.get( analytics.rest_url + '/googlesitekit/'+dataPoint+'?' + $.param( params ),function(response){

            if( response.success == false ){
                return $( '#analytics-overview-videos .spinner-wrap' ).html( '<div class="msg text-muted">' + response.data.message + '</div>' );
            }

            setSessionStorage( response, dataPoint, params );

            $( document.body ).trigger( action + '_done', [ response, params, wrapper ] );
        } );
    }

    function loadAnalyticsOverviewVideoTabs( event, response, params, wrapper ){
        var tabs = '';
        var tabsContent = '';

        var metrics = $.parseJSON( $( '#googlesitekit-reports' ).attr( 'data-overview-video-metrics' ) );

        var data            = response.data.response.totals;
        var columnHeader    = response.data.headers;

        if( ! data[0].metricValues ){

            data[0].metricValues = [];

            for ( var i = 0; i < columnHeader.length; i++ ) {
                data[0].metricValues.push( [ { 'value' : 0 } ] );
            }
        }

        if( ! data[1].metricValues ){

            data[1].metricValues = [];

            for ( var i = 0; i < columnHeader.length; i++ ) {
                data[1].metricValues.push( [ { 'value' : 0 } ] );
            }
        }         

        for ( var i = 0; i < data[0].metricValues.length; i++ ) {

            var active = '';

            if( i == 0 ){
                active = 'active show';
            }

            var number1 = data[0].metricValues[i]['value'] !== undefined ? parseInt( data[0].metricValues[i]['value'] ) : 0;

            var number2 = data[1].metricValues[i]['value'] !== undefined ? parseInt( data[1].metricValues[i]['value'] ) : 0;

            var percent = Math.abs(100-( number1*100/number2 ));

            if( ! $.isNumeric( percent ) ){
                percent = 0;
            }

            var icon = number1 > number2 ? 'icon-up' : 'icon-down';

            var tabId = Object.keys(metrics)[i];

            // Tab selector
            tabs += '<li class="nav-item flex-fill" role="presentation">';
                tabs += '<div class="nav-link '+active+' analytics-tab" id="analytics-video-tab-'+tabId+'" data-bs-toggle="tab" data-bs-target="#analytics-video-content-'+tabId+'">';
                    tabs += '<h3 class="analytics-tab__label text-secondary">'+ columnHeader[i] +'</h3>';
                    tabs += '<div class="analytics-tab__number total-number text-body"><h3>'+ number1.toLocaleString() +'</h3></div>';
                    tabs += '<div class="analytics-tab__percent percent percent-'+icon+'">';
                        tabs += '<span class="analytics-tab__icon '+icon+'"></span>';
                        tabs += '<span class="analytics-tab__percent-number percent-number">'+ Math.floor( percent,2 ) +'%</span>';
                    tabs += '</div>';
                tabs += '</div>';
            tabs += '</li>'; 

            // Tab content
            tabsContent += '<div class="tab-pane fade '+ active +'" id="analytics-video-content-'+tabId+'" role="tabpanel">';
                tabsContent += '<div class="position-relative" id="analytics-chart-'+i+'" style="width: 100%; height: 400px;">';
                    tabsContent += '<div class="position-absolute top-50 start-50 translate-middle">';
                        tabsContent += '<div class="spinner-border text-secondary" role="status">';
                            tabsContent += '<span class="visually-hidden">Loading...</span>';
                        tabsContent += '</div>';
                    tabsContent += '</div>';
                tabsContent += '</div>';
            tabsContent += '</div>';
        }

        tabs = '<ul class="nav nav-tabs analytics-tabs" id="google-analytics-video-contents" role="tablist">'+ tabs +'</ul>';

        tabsContent = '<div class="tab-content mt-5" id="analytics-video-tabs-contents-wrap">'+ tabsContent +'</div>';

        var output = '<div class="analytics-section__content analytics-section-videoviews position-relative border-top pt-4">'
            output += tabs + tabsContent;
        output += '</div>';

        wrapper.html( output );

        $( document.body ).trigger( 'analytics_overview_video_tabs_loaded', [ response, params, wrapper ] );
    }

    function loadAnalyticsVideoCharts( event, response, params, wrapper ){
        // Load pageViews first.
        drawTotalVideoViewsReports( params, 'analytics-video-content-eventCount' );
    }

    /**
     *
     * Loads top pageViews
     * 
     */
    function loadAnalyticsTopContent( wrapper ){

        if( wrapper.length == 0 ){
            return;
        }        

        var params = $.parseJSON( wrapper.attr( 'data-params' ) );

        var dataPoint = 'topcontent';

        var session = getSessionStorage( dataPoint, params );

        if( session ){
            return _loadAnalyticsTopContent( session );
        }        

        $.get( analytics.rest_url + '/googlesitekit/'+dataPoint+'?' + $.param( params ),function( response ){

            if( response.success == false ){
                return showError( response.data.message, wrapper.find( '#analytics-top-topcontent' ) );
            }

            setSessionStorage( response, dataPoint, params );

            return _loadAnalyticsTopContent( response );
        } );

        function _loadAnalyticsTopContent( response ){

            var rows            = response.data.response.rows;
            var columnHeader    = response.data.headers;

            if( rows == undefined ){
                return showError( analytics.data_not_available, wrapper.find( '#analytics-top-topcontent' ) );
            }

            var table = '';

            table += '<table class="table">';

                table += '<thead>';

                    table += '<tr>';

                        table += '<th scope="col">'+ analytics.title +'</th>';

                        for ( var i = 0; i < columnHeader.length; i++ ) {
                            table += '<th scope="col">'+ columnHeader[i] +'</th>';
                        }
       
                    table += '</tr>';

                table += '<thead><!--thead-->';

                table += '<tbody>';

                    for ( var i = 0; i < rows.length; i++ ) {
                        var pagePath        = rows[i].dimensionValues[0].value;
                        var permalink       = analytics.hosturl + pagePath;
                        var pageTitle       = rows[i].dimensionValues[1].value;
                        var iamgeUrl        = rows[i].dimensionValues[2].value;
                        var pageViews       = parseInt( rows[i].metricValues[0].value );
                        var totalUsers      = parseInt( rows[i].metricValues[1].value );
                        var newUsers        = parseInt( rows[i].metricValues[2].value );

                        table += '<tr>';                 

                            table += '<th scope="row">';
                                table += '<div class="d-flex">';
                                    table += '<div class="post-thumbnail ratio ratio-16x9 rounded overflow-hidden bg-dark me-3">';
                                        if( iamgeUrl ){
                                            table += '<a href="'+ permalink +'" target="_blank">';
                                                table += '<img src="'+iamgeUrl+'">';;
                                            table += '</a>';
                                        }
                                    table += '</div>';
                                    table += '<div>';
                                        table += '<div class="item-permalink"><a href="'+ permalink +'" target="_blank">'+ pageTitle +'</a></div>';
                                        table += '<div class="item-pagePath"><a class="small text-muted" href="'+ permalink +'" target="_blank">'+ pagePath +'</a></div>';
                                    table += '</div>';    
                                table += '</div>';
                            table += '</th>';

                            table += '<td data-title="'+ columnHeader[0] +'">'+ pageViews.toLocaleString() +'</td>';
                            table += '<td data-title="'+ columnHeader[1] +'">'+ totalUsers.toLocaleString() +'</td>';
                            table += '<td data-title="'+ columnHeader[2] +'">'+ newUsers.toLocaleString() +'</td>';

                        table += '</tr>';
                    }

                table += '</tbody>';

            table += '</table>';

            wrapper.find( '#analytics-top-topcontent' ).html( table );
        }
    }

    function loadAnalyticsChannels( wrapper ){

        if( wrapper.length == 0 ){
            return;
        }        

        var dataPoint = 'topchannels';

        var params = $.parseJSON( wrapper.attr( 'data-params' ) );

        var session = getSessionStorage( dataPoint, params );

        if( session ){
            return _loadAnalyticsChannels( session );
        }        

        $.get( analytics.rest_url + '/googlesitekit/'+dataPoint+'?' + $.param( params ),function( response ){
            if( response.success == false ){
                return showError( response.data.message, wrapper.find( '#analytics-channels' ) );
            }

            setSessionStorage( response, dataPoint, params );

            return _loadAnalyticsChannels( response );
        });

        function _loadAnalyticsChannels( response ){
            var rowTable        = [];
            var columnHeader    = response.data.headers; 
            var rows            = response.data.response.rows;
            var totals          = response.data.response.totals;
            var totalsNumber    = 0;

            if( rows == undefined ){
                return showError( analytics.data_not_available, wrapper.find( '#analytics-channels' ) );
            }            

            for (var i = 0; i < totals.length; i++) {
                totalsNumber += parseInt( totals[i].metricValues[0].value );
                totalsNumber += parseInt( totals[i].metricValues[1].value );
                totalsNumber += parseInt( totals[i].metricValues[2].value );
            }

            var table           = '';

            table += '<table class="table">';

                table += '<thead>';

                    table += '<tr>';

                        table += '<th scope="col">'+ analytics.channel +'</th>';

                        for ( var i = 0; i < columnHeader.length; i++ ) {
                            table += '<th scope="col">'+ columnHeader[i] +'</th>';
                        }

                        table += '<th scope="col">'+ analytics.percentage +'</th>';
       
                    table += '</tr>';

                table += '<thead><!--thead-->';

                table += '<tbody>';

                for ( var i = 0; i < rows.length; i++ ) {  

                    var channel    = rows[i].dimensionValues[0]['value'];
                    var users      = parseInt( rows[i].metricValues[0].value );
                    var newUsers   = parseInt( rows[i].metricValues[1].value);
                    var sessions   = parseInt( rows[i].metricValues[2].value );
                    var total      = users+newUsers+sessions;
                    var percentage = total*100/totalsNumber;

                    rowTable.push( [ channel, percentage ] );

                    table += '<tr>';
                        table += '<th scope="row">'+ channel +'</th>';
                        table += '<td data-title="'+columnHeader[0]+'">'+ users.toLocaleString() +'</td>';
                        table += '<td data-title="'+columnHeader[1]+'">'+ newUsers.toLocaleString() +'</td>';
                        table += '<td data-title="'+columnHeader[2]+'">'+ sessions.toLocaleString() +'</td>';
                        table += '<td data-title="'+analytics.percentage+'">'+ percentage.toFixed(2) +'%</td>';
                    table += '</tr>';
                }

                table += '</tbody>';

            table += '</table>';

            $( '#analytics-channel-table' ).html( table );

            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(_drawChart);

            function _drawChart() {

                var dataTable = new google.visualization.DataTable();

                dataTable.addColumn( 'string', analytics.channel );
                dataTable.addColumn( 'number', analytics.percentage );

                for ( var i = 0; i < rowTable.length; i ++ ) {
                    dataTable.addRow( [ rowTable[i][0], rowTable[i][1] ] );
                }

                var chart = new google.visualization.PieChart(document.getElementById('analytics-channel-charts'));

                chart.draw( dataTable, $.parseJSON( wrapper.find( '#analytics-channel-charts' ).attr( 'data-chart-options' ) ) );
            }

            wrapper.find( '#analytics-channels .spinner-wrap' ).hide();
        }
    }

    function loadAnalyticsTopCountries( wrapper ){

        if( wrapper.length == 0 ){
            return;
        }        

        var dataPoint = 'topcountries';

        var params = $.parseJSON( wrapper.attr( 'data-params' ) );

        var session = getSessionStorage( dataPoint, params );

        if( session ){
            return _loadAnalyticsTopCountries( session );
        }

        $.get( analytics.rest_url + '/googlesitekit/'+dataPoint+'?' + $.param( params ),function( response ){

            if( response.success == false ){
                return showError( response.data.message, wrapper.find( '#analytics-countries' ) );
            }

            setSessionStorage( response, dataPoint, params );

            return _loadAnalyticsTopCountries( response );
        });

        function _loadAnalyticsTopCountries( response ){
            var rows      = response.data.response.rows;

            if( rows == undefined ){
                return showError( analytics.data_not_available, wrapper.find( '#analytics-countries' ) );
            }            

            google.charts.load('current', {
                'packages':['geochart'],
                'mapsApiKey': analytics.mapapikey
            });

            google.charts.setOnLoadCallback( drawGeoChart );

            function drawGeoChart(){
                var dataTable = new google.visualization.DataTable();

                dataTable.addColumn( 'string', analytics.country );
                dataTable.addColumn( 'number', analytics.users );

                for ( var i = 0; i < rows.length; i++ ) {
                    var country = rows[i].dimensionValues[0].value;
                    var users   = parseInt( rows[i].metricValues[0].value );
                    dataTable.addRow( [ country, users ] );
                }

                var options = $.parseJSON( $( '#analytics-countries-geo-chart' ).attr( 'data-chart-options' ) );

                var chart = new google.visualization.GeoChart(document.getElementById('analytics-countries-geo-chart'));

                chart.draw( dataTable, options );                
            }

            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback( drawPiechart );

            function drawPiechart(){
                var dataTable = new google.visualization.DataTable();

                dataTable.addColumn( 'string', analytics.country );
                dataTable.addColumn( 'number', analytics.users );

                for ( var i = 0; i < rows.length; i++ ) {
                    var country = rows[i].dimensionValues[0].value;
                    var users   = parseInt( rows[i].metricValues[0].value );
                    dataTable.addRow( [ country, users ] );
                }                
                var options = $.parseJSON( wrapper.find( '#analytics-countries-pie-chart' ).attr( 'data-chart-options' ) );

                var chart = new google.visualization.PieChart(document.getElementById('analytics-countries-pie-chart'));

                chart.draw( dataTable, options );                
            }
        }
    }

    function loadSearchQueriesKeywords( wrapper ){

        if( wrapper.length == 0 ){
            return;
        }

        var dataPoint = 'topsearch';

        var params = $.parseJSON( wrapper.attr( 'data-params' ) );

        var session = getSessionStorage( dataPoint, params );

        if( session ){
            return _loadSearchQueriesKeywords( session );
        }        

        $.get( analytics.rest_url + '/googlesitekit/'+dataPoint+'?' + $.param( params ),function( response ){
            if( response.success == false ){
                return showError( response.data.message, wrapper.find( '#analytics-search-queries' ) );
            }

            setSessionStorage( response, dataPoint, params );

            return _loadSearchQueriesKeywords( response );
        });

        function _loadSearchQueriesKeywords( response ){
            var data = response.data.response;
            var params = response.data.params;
            var table = '';

            table += '<table class="table">';
                table += '<thead><tr>';
                    table += '<th scope="col">' + analytics.keyword + '</th>';
                    table += '<th scope="col">' + analytics.clicks + '</th>';
                    table += '<th scope="col">' + analytics.impressions + '</th>';
                    table += '<th scope="col">' + analytics.ctr + '</th>';
                    table += '<th scope="col">' + analytics.position + '</th>';
                table += '</tr></thead>';

                table += '<tbody>';
                    if( data.length > 0 ){
                        for ( var i = 0; i < data.length ; i++ ) {
                            var url = getGoogleSearchConsoleUrl( data[i].keys, params.startDate, params.endDate );
                            table += '<tr>';
                                table += '<th scope="row">';
                                    table += '<a class="fw-bold" href="'+ url +'" target="_blank">'+ data[i].keys +'</a>';  
                                table += '</td>';
                                table += '<td data-title="'+ analytics.clicks +'">'+ data[i].clicks.toLocaleString() +'</td>';
                                table += '<td data-title="'+ analytics.impressions +'">'+ data[i].impressions.toLocaleString() +'</td>';
                                table += '<td data-title="'+ analytics.ctr +'">'+ parseFloat(data[i].ctr).toFixed(2) +'</td>';
                                table += '<td data-title="'+ analytics.position +'">'+ Math.floor( data[i].position, 2 ) +'</td>';
                            table += '</tr>';
                        }
                    }
                    else{
                        table += '<tr>';
                            table += '<td colspan="5">';
                                table += '<p class="text-muted">'+ analytics.no_keywords_found +'</p>';
                            table += '</td>';
                        table += '</tr>';                        
                    }
                table += '</tbody>';

            table += '</table>';

            wrapper.find( '#analytics-search-queries' ).html( table );
        }
    }

    /**
     *
     * Analytics Overview Loader
     * 
     */
    function loadAnalyticsOverview( wrapper ){

        if( wrapper.length == 0 ){
            return;
        }

        var params      =   $.parseJSON( wrapper.attr( 'data-params' ) );

        var action      =   'load_analytics_overview';

        var dataPoint = 'overview';

        var session = getSessionStorage( dataPoint, params );

        if( session ){
            return $( document.body ).trigger( action + '_done', [ session, params, wrapper ] );
        }

        var jqxhr = $.ajax({
            url             : analytics.rest_url + '/googlesitekit/overview?' + $.param( params ),
            type            : 'GET',       
            beforeSend      : function( jqXHR ) {
                jqXHR.setRequestHeader( 'X-WP-Nonce', streamtube.nonce );
            }
        })
        .fail( function( jqXHR, textStatus, errorThrown ){
            $( document.body ).trigger( action + '_failed', [ jqXHR, textStatus, errorThrown ] );

            if( jqXHR.responseJSON.message !== undefined ){
                $.showToast( jqXHR.responseJSON.message , 'danger' );
            }
            else if( jqXHR.responseJSON.data[0].message ){
                $.showToast( jqXHR.responseJSON.data[0].message , 'danger' );
            }
            else{
                $.showToast( errorThrown, 'danger' );
            }
        })
        .done( function( response, textStatus, jqXHR ){

            if( response.success == true ){

                setSessionStorage( response, dataPoint, params );

                $( document.body ).trigger( action + '_done', [ response, params, wrapper, textStatus, jqXHR ] );
            }else{
                wrapper.find( '.spinner-wrap' ).html( response.data.message );
            }
            
        })
        .always( function( jqXHR, textStatus ){
            $( document.body ).trigger( action + '_completed', [ jqXHR, textStatus ] );
        });
    };

    /**
     * chartInit
     */
    function siteKitAnalyticsInit(){
        loadAnalyticsOverview( $( '#analytics-overview' ) );
        loadAnalyticsVideoOverview( $( '#analytics-overview-videos' ) );

        loadAnalyticsTopContent( $( '#section-report-topcontent' ) );
        loadAnalyticsChannels( $( '#section-report-channels' ) );
        loadAnalyticsTopCountries( $( '#section-report-countries' ) );
        loadSearchQueriesKeywords( $( '#section-report-search-queries' ) );        
    }

    // Run the loader
    siteKitAnalyticsInit();

    $( window ).resize(function() {
        loadAnalyticsOverview( $( '#analytics-overview' ) );
        loadAnalyticsVideoOverview( $( '#analytics-overview-videos' ) );

        loadAnalyticsTopContent( $( '#section-report-toppageviews' ) );
        loadAnalyticsChannels( $( '#section-report-channels' ) );
        loadAnalyticsTopCountries( $( '#section-report-countries' ) );
        loadSearchQueriesKeywords( $( '#section-report-search-queries' ) );
    });
})(jQuery);