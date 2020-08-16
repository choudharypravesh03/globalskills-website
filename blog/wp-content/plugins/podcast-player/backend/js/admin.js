/*global podcastplayerImageUploadText */
( function( $ ) {
	var fileFrame,
		$document = $( document );

	$document.on( 'click', '.podcast-player-widget-img-uploader', function( event ) {
		var _this = $( this );
		event.preventDefault();

		// Create the media frame.
		fileFrame = wp.media.frames.fileFrame = wp.media({
			title: podcastplayerImageUploadText.uploader_title,
			button: {
				text: podcastplayerImageUploadText.uploader_button_text
			},
			multiple: false  // Set to true to allow multiple files to be selected
		});

		// When an image is selected, run a callback.
		fileFrame.on( 'select', function() {

			// We set multiple to false so only get one image from the uploader
			var attachment  = fileFrame.state().get( 'selection' ).first().toJSON(),
				imgUrl      = attachment.url,
				imgId       = attachment.id,
				featuredImg = document.createElement( 'img' );

			featuredImg.src       = imgUrl;
			featuredImg.className = 'custom-widget-thumbnail';
			_this.html( featuredImg );
			_this.addClass( 'has-image' );
			_this.nextAll( '.podcast-player-widget-img-id' ).val( imgId ).trigger( 'change' );
			_this.nextAll( '.podcast-player-widget-img-instruct, .podcast-player-widget-img-remover' ).removeClass( 'podcast-player-hidden' );
		});

		// Finally, open the modal
		fileFrame.open();
	});

	$document.on( 'click', '.podcast-player-widget-img-remover', function( event ) {
		event.preventDefault();
		$( this ).prevAll( '.podcast-player-widget-img-uploader' ).html( podcastplayerImageUploadText.set_featured_img ).removeClass( 'has-image' );
		$( this ).prev( '.podcast-player-widget-img-instruct' ).addClass( 'podcast-player-hidden' );
		$( this ).next( '.podcast-player-widget-img-id' ).val( '' ).trigger( 'change' );
		$( this ).addClass( 'podcast-player-hidden' );
	});

	$document.on( 'click', '.pp-settings-toggle', function( event ) {
		var _this = $( this );
		event.preventDefault();
		_this.next( '.pp-settings-content' ).slideToggle('fast');
		_this.toggleClass( 'toggle-active' );
	});

	$(document).on( 'ready widget-added widget-updated', function(event, widget) {
		var params = { 
			change: function(e, ui) {
				$( e.target ).val( ui.color.toString() );
				$( e.target ).trigger('change'); // enable widget "Save" button
			},
		} 
		$('.pp-color-picker').not('[id*="__i__"]').wpColorPicker( params );
	});

	$('#widgets-right').on('change', 'select.podcast-player-pp-display-style', function() {
		var _this = $(this);
		var style = _this.val();
		var wrapper = _this.closest('.widget-content');
		var hdefault = wrapper.find('.pp_header_default');
		var excerpt = wrapper.find('.pp_excerpt_length');
		var gridCol = wrapper.find('.pp_grid_columns');
		var asRatio = wrapper.find('.pp_aspect_ratio');
		var cropMet = wrapper.find('.pp_crop_method');
		var excerptSupport = ['lv1', 'gv1'];
		var thumbSupport = ['lv1', 'lv2', 'gv1'];
		var gridSupport = ['gv1'];
		var aspectRatio;

		if ( style ) {
			hdefault.hide();
		} else {
			hdefault.show();
		}

		if (excerptSupport.includes(style)) {
			excerpt.show();
		} else {
			excerpt.hide();
		}

		if (gridSupport.includes(style)) {
			gridCol.show();
		} else {
			gridCol.hide();
		}

		if (thumbSupport.includes(style)) {
			asRatio.show();
			aspectRatio = wrapper.find('select.podcast-player-pp-aspect-ratio');
			if (aspectRatio.length && aspectRatio.val()) {
				cropMet.show();
			}
		} else {
			asRatio.hide();
			cropMet.hide();
		}
	});

	$('#widgets-right').on('change', 'select.podcast-player-pp-aspect-ratio', function() {
		var _this = $(this);
		var wrapper = _this.closest('.widget-content');
		var cropMet = wrapper.find('.pp_crop_method');
		if (_this.val()) {
			cropMet.show();
		} else {
			cropMet.hide();
		}
	});

	$('#widgets-right').on('change', 'select.podcast-player-pp-start-when', function() {
		var _this = $(this);
		var val = _this.val();
		var wrapper = _this.closest('.widget-content');
		var customTime = wrapper.find('.pp_start_time');
		if (val && 'custom' === val) {
			customTime.show();
		} else {
			customTime.hide();
		}
	});

	$('#widgets-right').on('change', '.pp_hide_header input[type="checkbox"]', function() {
		var _this = $(this);
		var parent = _this.parent();
		var sibs = parent.nextAll('.pp_hide_cover, .pp_hide_title, .pp_hide_description, .pp_hide_subscribe');
		if (_this.is(':checked')) {
			sibs.hide();
		} else {
			sibs.show();
		}
	});

	$('#widgets-right').on('change', 'select.podcast-player-pp-fetch-method', function() {
		var _this = $(this);
		var wrapper = _this.closest('.widget-content');
		var tax = wrapper.find('select.podcast-player-pp-taxonomy');
		if ('feed' === _this.val()) {
			wrapper.find('.feed_url').show();
			wrapper.find('.pp_post_type, .pp_taxonomy, .pp_terms').hide();
			wrapper.find('.pp_podtitle').hide();
			wrapper.find('.pp-options-wrapper').show();
			wrapper.find('.pp_hide_content').show();
			wrapper.find('.pp_audiosrc, .pp_audiotitle, .pp_audiolink, .pp_ahide_download, .pp_ahide_social').hide();
			tax.val('');
			wrapper.find('.pp_terms-checklist input:checkbox').removeAttr('checked');
		} else if ( 'post' === _this.val() ) {
			wrapper.find('.feed_url').hide();
			wrapper.find('.pp_post_type, .pp_taxonomy').show();
			wrapper.find('.pp-options-wrapper').show();
			wrapper.find('.pp_podtitle').show();
			wrapper.find('.pp_hide_content').hide();
			wrapper.find('.pp_audiosrc, .pp_audiotitle, .pp_audiolink, .pp_ahide_download, .pp_ahide_social').hide();
		} else if ( 'link' === _this.val() ) {
			wrapper.find('.feed_url').hide();
			wrapper.find('.pp_post_type, .pp_taxonomy, .pp_terms').hide();
			wrapper.find('.pp-options-wrapper').hide();
			wrapper.find('.pp_audiosrc, .pp_audiotitle, .pp_audiolink, .pp_ahide_download, .pp_ahide_social').show();
			tax.val('');
			wrapper.find('.pp_terms-checklist input:checkbox').removeAttr('checked');
		}
	});

	$('#widgets-right').on('change', 'select.podcast-player-pp-post-type', function() {
		var _this = $(this);
		var postType = _this.val();
		var wrapper = _this.closest('.widget-content');
		var taxonomy = wrapper.find('.podcast-player-pp-taxonomy');
		wrapper.find('.pp_terms').hide();
		wrapper.find('.pp_terms-checklist input:checkbox').removeAttr('checked');
		taxonomy.find( 'option' ).hide();
		taxonomy.find( '.' + postType ).show();
		taxonomy.find( '.always-visible' ).show();
		taxonomy.val('');
	});

	$('#widgets-right').on('change', 'select.podcast-player-pp-taxonomy', function() {
		var _this = $(this);
		var wrapper = _this.closest('.widget-content');
		var terms = wrapper.find('.pp_terms');
		wrapper.find('.pp_terms-checklist input:checkbox').removeAttr('checked');
		if ( _this.val() ) {
			terms.show();
			terms.find( '.pp_terms-checklist li' ).hide();
			terms.find( '.pp_terms-checklist .' + _this.val() ).show();
		} else {
			terms.hide();
		}
	});
} )( jQuery );
