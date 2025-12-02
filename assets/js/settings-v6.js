(function($){
	var old_major_version		= false,
		selected_major_version	= false;
	function evaluate_field_visibility() {
		selected_major_version = $('#acffa_major_version').val();

		if ( selected_major_version == 6 || selected_major_version == 7 ) {
			$('.acffa_row.api_key').show();
			$('.acffa_row.kit').show();
			$('.acffa_row.v5_compatibility_mode').show();
			$('.acffa_row.pro_icons').hide();
			$('.button.button-primary').val( ACFFA.save_settings );
		} else if ( selected_major_version == 5 ) {
			$('.acffa_row.api_key').hide();
			$('.acffa_row.kit').hide();
			$('.acffa_row.v5_compatibility_mode').hide();
			$('.acffa_row.pro_icons').show();
			$('.button.button-primary').val( ACFFA.save_refresh_settings );
		} else {
			$('.acffa_row.api_key').hide();
			$('.acffa_row.kit').hide();
			$('.acffa_row.pro_icons').hide();
			$('#pro_icons').prop( 'checked', false );
			$('.button.button-primary').val( ACFFA.save_refresh_settings );
		}
	}

	function build_kits_table() {
		if ( ACFFA.kits.length ) {
			ACFFA.kits.sort((a, b) => (a.name > b.name) ? 1 : -1);

			var kitTmpl = wp.template( 'fa-kit' ),
				hasPro	= false;

			$.each( ACFFA.kits, function( index, kit ) {
				if ( ! hasPro && 'pro' == kit.licenseSelected ) {
					hasPro = true;
				}
				kit.checked = ACFFA.acffa_kit == kit.token ? 'checked' : '';
				kit.customIconCount = kit.iconUploads.length;
				var newKit = kitTmpl( kit );
				$('#available_kits tbody').append( newKit );
			});

			if ( hasPro ) {
				$('#acffa_kit_has_pro').val( 1 );
			}
		} else {
			$('.no_kits_found').show();
		}
	}

	function set_token_status() {
		var value				= $('#acffa_api_key').val(),
			validationLabels	= $('.acffa_row.api_key .validation-result');

		$( 'span', validationLabels ).hide();

		if ( ! value ) {
			$( '.empty', validationLabels ).show();
		} else if ( 'error' == ACFFA.api_key_status ) {
			$( '.error', validationLabels ).show();
		} else if ( 'success' == ACFFA.api_key_status ) {
			$( '.success', validationLabels ).show();
		} else {
			$( '.save', validationLabels ).show();
		}
	}

	$( document ).ready( function() {
		old_major_version = $('#acffa_major_version').val();
		evaluate_field_visibility();
		set_token_status();
		build_kits_table();
	});

	$('#acffa_api_key').on( 'keyup', function() {
		$('.acffa_row.api_key .validation-result span').hide();
		if ( $( this ).val() ) {
			$('.acffa_row.api_key .validation-result span.save').show();
		} else {
			$('.acffa_row.api_key .validation-result span.empty').show();
		}
	});

	$('#acffa_major_version').on( 'change', function() {
		evaluate_field_visibility();

		var $iconSetBuilder = $('.custom-icon-set');

		if ( old_major_version !== selected_major_version ) {
			$iconSetBuilder.hide();
			$('.icon-builder-complete-changes-notice').show();
		} else {
			$iconSetBuilder.show();
			$('.icon-builder-complete-changes-notice').hide();
		}
	});

	function strEscape( string ) {
		var htmlEscapes = {
			'&' : '&amp;',
			'<' : '&lt;',
			'>' : '&gt;',
			'"' : '&quot;',
			"'" : '&#39;'
		};
		return ('' + string).replace(/[&<>"']/g, function (chr) {
			return htmlEscapes[chr];
		});
	}
	function strUnescape( string ) {
		var htmlUnescapes = {
			'&amp;'		: '&',
			'&lt;'		: '<',
			'&gt;'		: '>',
			'&quot;'	: '"',
			'&#39;'		: "'"
		};
		return ('' + string).replace(/[&<>"']/g, function (chr) {
			return htmlUnescapes[chr];
		});
	}
	function escHtml( string ) {
		return ('' + string).replace(/<script|<\/script/g, function (html) {
			return strEscape(html);
		});
	}

	$('select#icon_chooser').select2({
		width				: '100%',
		ajax				: {
			url			: ajaxurl,
			dataType	: 'json',
			delay		: 400,
			type		: 'post',
			data		: function( params ) {
				return {
					action			: 'acf/fields/font-awesome/query',
					s				: params.term,
					paged			: 1,
					nonce			: ACFFA.acf_nonce,
					field_key		: 'icon_set_builder',
					fa_version		: ACFFA.acffa_search_version,
					fa_license		: ACFFA.acffa_search_license,
					custom_icons	: ACFFA.acffa_custom_icons
				}
			}
		},
		escapeMarkup		: function( markup ) {
			if (typeof markup !== 'string') {
				return markup;
			}
			return escHtml( markup ); 
		},
		minimumInputLength	: 1,
		placeholder			: ACFFA.search_string
	});

	$('select#icon_chooser').on('select2:select', function (e) {
		var data		= e.params.data,
			iconJson	= JSON.parse( data.id );

		$('select#acffa_new_icon_set').append( '<option value="' + strEscape( data.id ) + '" data-label="' + iconJson.label + '" selected>' + iconJson.label + '</option>' );
		$('.acffa_row.custom-icon-set .selected-icons').append( '<div class="new-icon" data-icon-json="' + strEscape( data.id ) + '" data-label="' + iconJson.text + '">' + data.text + '</div>' );
		$( this ).val( null ).trigger('change');
	});

	$( '.existing-custom-icon-sets .edit-icon-set' ).on( 'click', function( e ) {
		e.preventDefault();

		$('.acffa_row.custom-icon-set .selected-icons').empty();
		$('select#acffa_new_icon_set').empty();

		var parent		= $( this ).closest('.icon-set'),
			label		= $( parent ).data('set-label'),
			$iconList	= $( 'li.icon', parent );

		$iconList.each( function( index, icon ) {
			var iconJson = $( icon ).data('icon-json');

			$('select#acffa_new_icon_set').append('<option value="' + strEscape( JSON.stringify( iconJson ) ) + '" data-label="' + iconJson.label + '" selected>' + iconJson.label + '</option>');
			$('.acffa_row.custom-icon-set .selected-icons').append( '<div class="new-icon" data-icon-json="' + strEscape( JSON.stringify( iconJson ) ) + '" data-label="' + iconJson.label + '"><i class="fa-' + iconJson.family + ' fa-' + iconJson.style + ' fa-' + iconJson.id + ' fa-fw"></i>' + iconJson.label + '</div>' );
		});

		$('#acffa_new_icon_set_label').val( label );
	});

	$( '.existing-custom-icon-sets .view-icon-list' ).on( 'click', function( e ) {
		e.preventDefault();

		var parent = $( this ).closest('.icon-set');
		$( parent ).find('.icon-list').toggle();
	});

	$( '.existing-custom-icon-sets .delete-icon-set' ).on( 'click', function( e ) {
		e.preventDefault();

		var result = confirm( ACFFA.confirm_delete );
		if ( result ) {
			var nonce = $( this ).data('nonce'),
				iconSetName = $( this ).data('icon-set-name');

			$.post(
				ajaxurl,
				{
					'action'		: 'ACFFA_delete_icon_set',
					'nonce'			: nonce,
					'icon_set_name'	: iconSetName
				},
				function( response_msg ) {
					if ( 'success' == response_msg ) {
						$('.icon-set[data-set-name="' + iconSetName + '"]').remove();
					} else {
						alert( ACFFA.delete_fail );
					}
				}
			);
		}
	});

	$( document ).on( 'click', '.selected-icons .new-icon', function() {
		if ( confirm( ACFFA.remove_icon ) ) {
			var label = $( this ).data('label');
			$('select#acffa_new_icon_set option[data-label="' + label + '"]').remove();
			$( this ).remove();
		}
	} );
})(jQuery);
