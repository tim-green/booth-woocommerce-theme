var booth_woo_repeating_sortable_init = function( selector ) {
	if ( typeof selector === 'undefined' ) {
		jQuery('.tg-repeating-fields .inner').sortable({ placeholder: 'ui-state-highlight' });
	} else {
		jQuery('.tg-repeating-fields .inner', selector).sortable({ placeholder: 'ui-state-highlight' });
	}
};

var booth_woo_repeating_colorpicker_init = function( selector ) {
	if ( selector === undefined ) {
		var tgColorPicker = jQuery( '#widgets-right .booth-woo-color-picker, #wp_inactive_widgets .booth-woo-color-picker' ).filter( function() {
			return !jQuery( this ).parents( '.field-prototype' ).length;
		} );

		tgColorPicker.wpColorPicker();
	} else {
		jQuery( '.booth-woo-color-picker', selector ).wpColorPicker();
	}
};

jQuery(document).ready(function($) {
	"use strict";
	var $body = $( 'body' );

	// Repeating fields
	booth_woo_repeating_sortable_init();

	$body.on( 'click', '.tg-repeating-add-field', function( e ) {
		var repeatable_area = $( this ).siblings( '.inner' );
		var fields = repeatable_area.children( '.field-prototype' ).clone( true ).removeClass( 'field-prototype' ).removeAttr( 'style' ).appendTo( repeatable_area );
		booth_woo_repeating_sortable_init();
		booth_woo_repeating_colorpicker_init();
		e.preventDefault();
	} );


	$body.on( 'click', '.tg-repeating-remove-field', function( e ) {
		var field = $(this).parents('.post-field');
		field.trigger( 'change' ).remove();
		e.preventDefault();
	});
});
