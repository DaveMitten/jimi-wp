jQuery( document ).ready(function() {

	// ---------------------------------------------------------
	// Back to Top
	// ---------------------------------------------------------
	jQuery( window ).scroll(function() {

		if ( jQuery( this ).scrollTop() > 100 ) {
			jQuery( '#back-top' ).addClass( 'show-totop' );
		} else {
			jQuery( '#back-top' ).removeClass( 'show-totop' );
		}
	});

	jQuery( '#back-top a' ).click(function() {
		jQuery( 'body,html' ).stop( false, false ).animate({
			scrollTop: 0
		}, 800 );
		return false;
	});
});
