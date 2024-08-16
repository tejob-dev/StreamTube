(function($) {
    "use strict";

    $( document.body ).on( 'update_post', function( event, data, textStatus, jqXHR, formData, form ){
        if( data.success && data.data.post.hasOwnProperty( 'product_id' ) ){
            form.find( '.metabox-wrap.select-product' ).remove();
        }

    } );

})(jQuery);