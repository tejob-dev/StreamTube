(function($) {
    "use strict";

    function addAutoResizeCommentField( element ){
        /**
        * Load autosize
        * @since 1.0.0
        */
        autosize(element);
    }

    addAutoResizeCommentField( $( '#buddypress .activity-comments form .ac-input' ) );
    addAutoResizeCommentField( $( '#buddypress #whats-new' ) );

    function setupMultiVideoObserver(targetElementIds, videoSelector, initCallback) {
        targetElementIds.forEach(function(targetElementId) {
            var targetNode = document.getElementById(targetElementId);

            if (targetNode) {
                var observer = new MutationObserver(function(mutations) {
                    mutations.forEach(function(mutation) {
                        if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {

                            addAutoResizeCommentField( $( '#buddypress .activity-comments form .ac-input' ) );

                            var nodes = Array.from(mutation.addedNodes);
                            nodes.forEach(function(node) {
                                var isVideoNode = node instanceof Element && node.classList.contains('new_video');
                                var isActivityStreams = mutation.target.id === 'activity-streams';

                                if (isVideoNode || isActivityStreams) {

                                    if( isActivityStreams ){
                                        var players = videojs.getPlayers();
                                        if (players) {
                                            for (var playerId in players) {
                                                if( players[playerId] && $( '#'+playerId ).length == 0 ){
                                                    if( ! players[playerId].isDisposed() ){
                                                        players[playerId].dispose();
                                                    }  
                                                }
                                            }
                                        }
                                    }   

                                    var videoElements = $(node).find(videoSelector).toArray();
                                    
                                    videoElements.forEach(function( videoElement ) {
                                        initCallback( videoElement );
                                    });

                                    $(document.body).trigger('bp_activity_player_loaded', [node]);

                                    if (isActivityStreams) {
                                        return setupMultiVideoObserver(['activity-stream'], videoSelector, initCallback );
                                    }
                                }
                            });
                        }
                    });
                });

                var config = { childList: true };
                observer.observe(targetNode, config);
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Usage
        setupMultiVideoObserver(['activity-streams', 'activity-stream'], 'video-js[data-player-id]', _videoJSplayerInit );
    });

    function playVideo($elements) {
        var videoId = $elements.find('video-js[data-player-id]').attr('id');
        if (videoId) {
            videojs.getPlayer(videoId).play();
        }
    }

    function pauseVideo($elements) {
        var videoId = $elements.find('video-js[data-player-id]').attr('id');
        if (videoId) {
            videojs.getPlayer(videoId).pause();
        }
    }    

    $(document).on('scrollin', '.activity.new_video.jsappear', function (event = null, $elements) {
        playVideo($elements);
    });

    $(document).on('scrollout', '.activity.new_video.jsappear', function (event = null, $elements) {
        pauseVideo($elements);
    });

    $( document.body ).on( 'bp_activity_player_loaded', function( event, node ){

        addAutoResizeCommentField( $(node).find('.activity-comments form .ac-input') );

        var nodeId = $(node).attr( 'id' );

        if( nodeId ){
            if( nodeId.match(/^activity-\d+$/) ){
                $( 'li#' + nodeId ).scrolling();
            }

            if( nodeId == 'activity-stream' ){
                $( 'ul#activity-stream li.activity.new_video' ).each(function(){
                    $(this).scrolling();
                });
            }

            $(node).find( '.countdown.upcoming' ).initCountDown();
        }
    } );

    /**
     *
     * Custom AJAX handler for Groups Accept and Reject buttons
     * 
     */
    $( document ).on( 'click', 'a.btn-group-action', function( event ){
        event.preventDefault();
        const button    = $(this);

        const action    = button.hasClass( 'accept' ) ? 'accept' : 'reject';
        const group     = button.attr( 'data-group-id' );

        button.addClass( 'disabled' ).attr( 'disabled', 'disabled' );

        $.post( streamtube.ajaxUrl,{
            action      : "bp_group_" + action + "_invite",
            group       : group,
            _wpnonce    : streamtube._wpnonce
        }, function( response ){
            location.reload();
        } );
    } );

})(jQuery);