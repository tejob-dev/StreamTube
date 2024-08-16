(function($) {
    "use strict";

    var playerIds      = [];
    var isSeeked       = false;
    var percentage     = 0;

    $( document.body ).on( 'player_timeupdate', function( e ){

        var player      = e.detail.player;

        var setup       = $.parseJSON( player.getAttribute( 'data-settings' ) );

        var settings    = setup.jplugins.mycred_watch_video;

        var playerId    = player.id_;

        var postId      = player.tagAttributes['data-parent-post-id'];

        var currentTime = Math.ceil( player.currentTime(), 0 );
        var totalTime   = Math.ceil( player.duration(), 0 );

        var percentage  = Math.ceil( currentTime * 100 / totalTime );

        player.on( 'seeked', function(e){
            isSeeked = true;
        } );

        $.each( settings, function (i) {
            var hookInstance = settings[i];

            if( isSeeked == true && hookInstance.prevent_seeking ){
                percentage = 0;
            }

            if( percentage >= parseInt( hookInstance.percentage ) && 
                jQuery.inArray( playerId + '_' + hookInstance.ctype , playerIds ) === -1 
            ){
                
                playerIds.push( playerId + '_' + hookInstance.ctype );

                $.post( streamtube.ajaxUrl, {
                    action          : 'streamtube/mycred/hook/watch_video',
                    player_id       : playerId,
                    post_id         : postId,
                    total_time      : totalTime,
                    current_time    : currentTime,
                    _wpnonce        : hookInstance._wpnonce,
                    ctype           : hookInstance.ctype
                }, function( response ){

                    percentage  = 0;
                    isSeeked    = false;

                    if( response.success == true && hookInstance.success_message && ! hookInstance.is_embed ){
                        $.showToast( response.data.message, 'success', 5000, settings.success_icon );
                    }

                    if( response.success == false && hookInstance.warning_message && ! hookInstance.is_embed ){
                        $.showToast( response.data[0].message, 'danger', 5000 );    
                    }

                    $( document.body ).trigger( 
                        'streamtube_mycred_points_sent', 
                        [ postId, player, response, playerId, playerIds, hookInstance ] 
                    );
                } );

                $( document.body ).trigger( 
                    'streamtube_mycred_watch_video', 
                    [ postId, player, playerId, hookInstance ] 
                );
            }

        });

    } );

})(jQuery);    