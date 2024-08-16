(function( $ ) {
	'use strict';

    var ajaxCheckQueueInterval = parseInt( wpve.queue_interval );

    if( ajaxCheckQueueInterval > 0 ){

        if( wpve.is_logged_in ){

            /**
             *
             * Query the encode queue
             *
             * @since  1.0.0
             * 
             */
            setInterval( function(){

                var parent = 0;

                if( ! $( 'body' ).hasClass( 'dashboard-video' ) ){
                    return;
                }

                var requestUrl = wpve.rest_url + '/encoding';

                if( wpve.enable_admin_ajax ){
                    requestUrl = wpve.admin_ajax_url + '?action=check_encode_queue';
                }

                var jqxhr = $.ajax({
                    url: requestUrl,
                    type: 'GET',
                    beforeSend: function( jqXHR ) {
                        jqXHR.setRequestHeader('X-WP-Nonce', wpve.rest_nonce );              
                    }
                })
                .done( function( responseData, textStatus, jqXHR ){
                    /**
                     *
                     * Action trigger
                     *
                     * @since 1.0.0
                     * 
                     */
                    $( document.body ).trigger( 'check_encoding_queue', [ responseData, textStatus, jqXHR ] );
                    
                    if( responseData ){

                        var attachments = responseData;

                        if( wpve.enable_admin_ajax ){
                            attachments = $.parseJSON( attachments );
                        }

                        for ( var i = 0; i < attachments.length; ++i ) {

                            var el = $( '[data-attachment-id='+attachments[i].ID+']:not(.button-reencode)' );

                            var percentage = parseInt( attachments[i].percentage );

                            if( percentage < 100 ){

                                if( el.hasClass( 'progress' ) ){
                                    el.find( '.progress-bar' )
                                    .css( 'width', attachments[i].percentage + '%' )
                                    .html(  attachments[i].percentage + '% ' + wpve.encoding );
                                }
                                else{
                                    var progress = '';

                                    progress += '<div style="height: 20px;" data-attachment-id="'+attachments[i].ID+'" data-parent-post="'+attachments[i].parent+'" class="progress bg-dark wpve-progress">';
                                        progress += '<div class="progress-bar progress-bar-striped progress-bar-animated bg-success px-2" style="width: '+attachments[i].percentage+'%">';

                                            progress += attachments[i].percentage + '% ' + wpve.encoding;

                                        progress += '</div>';
                                    progress += '</div>';

                                    el.replaceWith( progress );
                                }
                            }
                            else{

                                el.replaceWith( '<span class="badge bg-success badge-encoded">'+ wpve.encoded +'</span>' );

                                $( document.body ).trigger( 'file_encode_done', [ attachments[i], textStatus, jqXHR ] );
                                
                            }
                        }
                    }
                    
                });

            }, ajaxCheckQueueInterval );

        }

    }

    /**
     *
     * Single video encode button handler
     *
     * @since  1.0.0
     * 
     */
    $( document ).on( 'click', '.button-encode', function( event ){

        event.preventDefault();

        var button = $(this);

        var jqxhr = $.ajax({
            url: wpve.rest_url + '/queue',
            data: {
                attachment_id : button.attr( 'data-attachment-id' ),
                parent: button.attr( 'data-parent-post' )                
            },
            type: 'POST',
            beforeSend: function( jqXHR ) {
                jqXHR.setRequestHeader( 'X-WP-Nonce', wpve.rest_nonce );              
            }
        })

        .done( function( responseData, textStatus, jqXHR ){

            var badge = '<span data-attachment-id="'+button.attr( 'data-attachment-id' )+'" data-parent-post="'+button.attr( 'data-parent-post' )+'" class="badge bg-info badge-%3$s">';
                badge += wpve.waiting;
            badge += '<span>';

            button.replaceWith( badge );

            $( document.body ).trigger( 'add_queue_item', [ responseData, textStatus, jqXHR ] );

        });
    } );    

})( jQuery );
