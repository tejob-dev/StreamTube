(function($) {
    "use strict";

    $( document ).on( 'click', '.notice-verify-purchase button.notice-dismiss', function(e){
        $.post( streamtube_admin.ajaxurl + '?action=dismiss_verify_purchase', function( data, status ){
            console.log( status );
        } );

    } );


})(jQuery);    