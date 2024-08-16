(function($) {
    "use strict";

    $(function() {

        $( '.pmpro_payment-expiration .pmpro_asterisk' ).remove();
        $( '.pmpro_payment-expiration' ).addClass( 'd-flex align-items-start gap-3' );

        if( $( '#pmpro_level_cost' ).length !== 0 ){
            $( '#pmpro_level_cost' ).html( $( '#pmpro_level_cost' ).html().replace( '. ' , '' ) );    
        }

    });

})(jQuery);    