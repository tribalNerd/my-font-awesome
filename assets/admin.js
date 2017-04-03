( function( root, $, undefined ) {
	"use strict";

	$( function () {
	    tinymce.PluginManager.add( "myfa_button", function( ed, url ) {
	        ed.addButton( "myfa", {
	            title 	: "Easy Font Awesome",
	            cmd 	: "myfa_command",
	            text	: '[myfa]'
	        });

	        ed.addCommand( "myfa_command", function() {
	            ed.execCommand( "mceInsertContent", false, '[myfa name="" size="" class=""]' );
	            return;
	        } );
		} );
	} );

} ( this, jQuery ) );
