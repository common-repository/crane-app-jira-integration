jQuery( '.ca-ji-more-content' ).dialog({
    autoOpen: false,
    buttons: [
        {
            text: ca_ji.close_text,
            click: function() {
                jQuery( this ).dialog( 'close' );
            }
        }
    ],
    closeText: ca_ji.close_text,
    draggable: false,
    hide: {
        effect: 'fade',
        duration: 400
    },
    modal: true,
    show: {
        effect: 'fade',
        duration: 400
    }
});

jQuery( '.ca-ji-more-open' ).click( function( event ) {
    // Preventing default actions of event
    event.preventDefault();
    // Opening dialog
    jQuery( '#' + jQuery( this ).attr( 'issue' ) ).dialog( 'open' );
});