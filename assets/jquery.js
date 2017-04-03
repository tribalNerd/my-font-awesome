( function( root, $, undefined ) {
	"use strict";

	$( function () {
		// Remove Selected Radio
		$("input[type=radio]").each( function() {
			$(this).mousedown( function(e) {
				e.preventDefault();
				if ( $(this).attr('checked') ) {
					$(this).removeAttr('checked');
					alert('Selection Removed!');
				}
			} );
	    } );
	} );

} ( this, jQuery ) );
