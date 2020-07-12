jQuery( document ).ready( function( $ ) {
	"use strict";

	$( '.tg-cf-wrap .booth-woo-color-picker' ).each( function() {
		$( this ).wpColorPicker();
	} );

	$( '.tg-cf-wrap .booth-woo-alpha-color-picker' ).alphaColorPicker();


	// Metabox tabs
	var wrap = $( '.tg-cf-wrap' );

	wrap.each( function() {

		var sectionsLen = $( this ).find( '.tg-cf-section' ).length;

		if ( sectionsLen > 1 ) {

			var root = $( this );
			var sections = root.find( '.tg-cf-section' );

			sections.not( ':first' ).hide();

			var tabs = $( '<ul class="tg-cf-tabs"></ul>' ).prependTo( root );

			sections.each( function() {
				var sectionTitle = $( this ).find( '.tg-cf-title' ).text();
				var tab = $( '<li class="tg-cf-tab"></li>' ).html( sectionTitle );
				var tabs = $( '.tg-cf-tabs', root );
				tabs.append( tab );
			} );

			var tab = $( '.tg-cf-tab', root );

			tab.first().addClass( 'tg-cf-tab-active' );

			tab.on( 'click', function( e ) {
				$( this ).addClass( 'tg-cf-tab-active' ).siblings( '.tg-cf-tab' ).removeClass( 'tg-cf-tab-active' );

				var idx = $( this ).index();
				var section = $( this ).parents( '.tg-cf-wrap' ).children( '.tg-cf-section' ).get( idx );

				$( section ).show().siblings( '.tg-cf-section' ).hide();

				if ( typeof google === 'object' && typeof google.maps === 'object' ) {
					if ( $( section ).find( '.gllpLatlonPicker' ).length > 0 ) {
						google.maps.event.trigger( window, 'resize', {} );
					}
				}

				e.preventDefault();
			});
		}
	});


	// Media Manager links
	$( 'body' ).on( 'click', '.tg-media-button', function( e ) {
		e.preventDefault();

		var tgButton = $( this );

		var target_id      = tgButton.siblings( '.tg-uploaded-id' );
		var target_url     = tgButton.siblings( '.tg-uploaded-url' );
		var target_preview = tgButton.siblings( '.upload-preview' );

		var bMulti = tgButton.data( 'multi' ); // Although data-multi="true" works, it's not handled.
		var bFrame = tgButton.data( 'frame' );

		if( typeof bMulti == 'undefined' ) {
			bMulti = false;
		}
		if( typeof bFrame == 'undefined' ) {
			bFrame = 'select';
		}

		var tgMediaUpload = wp.media( {
			frame: bFrame, // Only 'post' and 'select' seem to work with the set of options below.
			title: bMulti == true ? booth_woo_plugin_PostMeta.tSelectFiles : booth_woo_plugin_PostMeta.tSelectFile,
			button: {
				text: bMulti == true ? booth_woo_plugin_PostMeta.tUseTheseFiles : booth_woo_plugin_PostMeta.tUseThisFile
			},
			multiple: bMulti
		} ).on( 'select', function(){
			// grab the selected images object
			var selection = tgMediaUpload.state().get( 'selection' );

			// grab object properties for each image
			selection.map( function( attachment ){
				var attachment = attachment.toJSON();
				/*
				// Properties exposed
				alt: "",
				author: "2",
				authorName: "Anastis",
				caption: "",
				date: 1441717373000,
				dateFormatted: "September 8, 2015",
				editLink:"http://.../wp-admin/post.php?post=181&action=edit",
				filename: "las-erinias-fotoviajero.jpg",
				filesizeHumanReadable: "63 kB",
				filesizeInBytes: 64881,
				height: 600,
				icon:"http://.../wp-includes/images/media/default.png",
				id: 181,
				link: "http://.../las-erinias-fotoviajero/",
				menuOrder: 0,
				meta: false,
				modified: 1441717373000,
				name: "las-erinias-fotoviajero",
				orientation: "portrait",
				sizes:Object {
					full:Object {
						height:600,
						orientation:"portrait",
						url:"http://.../las-erinias-fotoviajero.jpg",
						width:504
					},
					medium:Object {
						height:300,
						orientation:"portrait",
						url:"http://.../las-erinias-fotoviajero-252x300.jpg"
						width:252
					},
					...
				},
				status:"inherit",
				subtype:"jpeg",
				title:"las-erinias-fotoviajero",
				type:"image",
				uploadedTo:66,
				uploadedToLink:"http://.../wp-admin/post.php?post=66&action=edit",
				uploadedToTitle:"Manchester City needs huge performance against Barcelona",
				uploading:false,
				url:"http://.../las-erinias-fotoviajero.jpg",
				width:504,
				*/

				if( bMulti == false ) {
					if( target_id.length > 0 ) {
						target_id.val( attachment.id ).trigger( 'change' );
					}
					if( target_url.length > 0 ) {
						target_url.val( attachment.url ).trigger( 'change' );
					}
					if( target_preview.length > 0 ) {
						// For some reason, attachment.sizes doesn't include additional image sizes.
						// Only 'thumbnail', 'medium' and 'full' are exposed, so we use 'thumbnail' instead of 'booth_woo_plugin_featgal_small_thumb'
						var html = '<img src="' + attachment.sizes.thumbnail.url + '" /><a href="#" class="close media-modal-icon" title="' + booth_woo_plugin_PostMeta.tRemoveImage + '"></a>';
						target_preview.html( html );
					}
				}
			});
		} ).open();


	}); // on click


	$( 'body' ).on( 'click', '.tg-upload-preview a.close', function( e ) {
		e.preventDefault();
		$( this ).parent().html( '' ).siblings('.tg-uploaded-id' ).val('');
	} );

	// Featured Galleries
	$( 'body' ).on( 'click', '.tg-upload-to-gallery', function( e ) {
		e.preventDefault();

		var button = $( this );
		var target_parent = button.parents( '.tg-media-manager-gallery' );
		var target_ids = button.siblings( '.tg-upload-to-gallery-ids' );
		var target_rand = button.siblings( 'p' ).find( '.tg-upload-to-gallery-random > input[type="checkbox"]' );

		var tgMediaGallery = wp.media( {
			frame: 'select',
			title: booth_woo_plugin_PostMeta.tSelectFiles,
			button: {
				text: booth_woo_plugin_PostMeta.tUseTheseFiles
			},
			multiple: true
		} ).on( 'select', function( attachments ){
			// grab the selected images object
			var selection = tgMediaGallery.state().get( 'selection' );

			var ids = target_ids.val();

			var ids_arr = [];

			// If string is empty, String.split() returns an array with one empty element instead of an empty array. Let's avoid that.
			if ( ids.length > 0 ) {
				ids_arr = ids.split( ',' );
			}

			// grab object properties for each image
			selection.map( function( attachment ) {
				var attachment = attachment.toJSON();
				ids_arr.push( attachment.id );
			} );

			// Create a csv list of image IDs into the hidden input.
			target_ids.val( ids_arr.join( ',' ) );

			// Update the preview area.
			booth_woo_featgal_AJAXUpdate( target_parent );

		} ).open();

	} );

	// Constructs a comma separated list of image IDs, from the currently visible images within the preview area.
	// Also updates the hidden input with the list.
	// Useful for when the user removes or re-arranges images.
	function booth_woo_featgal_UpdateIDs( preview_element ) {
		var ids = [];
		$( preview_element ).children( '.thumb' ).children( 'img' ).each( function() {
			ids.push( $( this ).data( 'img-id' ) );
		} );

		preview_element.siblings( '.tg-upload-to-gallery-ids' ).val( ids.join( ',' ) );
	}

	// Retrieves a JSON list of IDs and URLs via AJAX, and updates the preview area of the passed gallery container element.
	function booth_woo_featgal_AJAXUpdate( gallery_container ) {
		var target_ids     = gallery_container.children( '.tg-upload-to-gallery-ids' );
		var target_preview = gallery_container.children( '.tg-upload-to-gallery-preview' );

		$.ajax( {
			type: 'post',
			url: booth_woo_plugin_PostMeta.ajaxurl,
			data: {
				action: 'booth_woo_plugin_featgal_AJAXPreview',
				ids: target_ids.val()
			},
			dataType: 'text',
			beforeSend: function() {
				target_preview.empty().html( '<p>' + booth_woo_plugin_PostMeta.tLoading + '</p>' );
			},
			success: function( response ) {
				if ( response == 'FAIL' ) {
					target_preview.empty().html( '<p>' + booth_woo_plugin_PostMeta.tPreviewUnavailable + '</p>' );
				} else {
					// The response is an object whose properties are key-value pairs.
					// Since JSON doesn't support named keys in arrays, we can't get an
					// array whose keys are IDs and values are URLS.
					// If we do, the keys are sorted numerically and original ordering is lost.
					response = $.parseJSON( response );

					target_preview.empty();
					$.each( response, function( key, value ) {
						$( '<div class="thumb"><img src="' + value.url + '" data-img-id="' + value.id + '"><a href="#" class="close media-modal-icon" title="' + booth_woo_plugin_PostMeta.tRemoveFromGallery + '"></a></div>' ).appendTo( target_preview );
					} );
				}
			}//success
		});//ajax

	}

	// Handle removal of images from the preview area.
	$( 'body' ).on( 'click', '.tg-media-manager-gallery .thumb a.close', function( event ) {
		event.preventDefault();

		// Store a reference to .tg-media-manager-gallery as we'll not be able to find it later and since the deletion of the parent .thumb and, then are be able to traverse upwards.
		var container = $( this ).parent().parent();

		$( this ).parent().remove();

		booth_woo_featgal_UpdateIDs( container );
	} );

	// Handle re-arranging of images in preview areas.
	var preview_areas = $( '.tg-upload-to-gallery-preview' );
	if ( preview_areas.length > 0 ) {
		preview_areas.sortable( {
			update: function( event, ui ) {
				booth_woo_featgal_UpdateIDs( $( this ) );
			}
		} );
	}

}); // document.ready
