(function($) {
    "use strict";

    $( document.body ).on( 'apply_become_seller', function( event, data, textStatus, jqXHR, formData, form ){
        if( data.success == false ){
            return $.showToast( data.data[0].message, 'danger' );
        }

       window.location.href = data.data;
    } );

})(jQuery);