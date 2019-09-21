/* global ajaxurl, magazinNUX */
( function( wp, $ ) {
	'use strict';

	if ( ! wp ) {
		return;
	}

	$( function() {
		// Dismiss notice
		$( document ).on( 'click', '.sf-notice-nux .notice-dismiss', function() {
			$.ajax({
				type:     'POST',
				url:      ajaxurl,
				data:     { nonce: magazinNUX.nonce, action: 'magazin_dismiss_notice' },
				dataType: 'json'
			});
		});
	});
})( window.wp, jQuery );