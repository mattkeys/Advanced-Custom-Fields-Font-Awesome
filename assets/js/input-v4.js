(function($){
	
	function update_preview( $select_element, parent ) {
		var value = $select_element.val();

		if ( ! parent ) {
			var parent = $select_element.closest('.field');
		}

		$( '.icon_preview', parent ).html( '<i class="fa ' + value + '" aria-hidden="true"></i>' );

		$( '.field_option_font-awesome .fa_live_preview', parent ).html( '<i class="fa ' + value + '" aria-hidden="true"></i>' );
	}

	function initialize_chosen( $select, allow_deselect ) {
		$select.addClass('chosen_initialized').chosen({
			width					: '100%',
			no_results_text			: ACFFA.no_results,
			rtl						: ACFFA.is_rtl,
			allow_single_deselect	: allow_deselect
		});
	}

	$( document ).on( 'acf/setup_fields', function( e, postbox ) {
		$( postbox ).find('.field[data-field_type="font-awesome"]').each( function() {

			var $select	= $( '.chosen-fontawesome:not(.chosen_initialized):visible', this );

			if ( $select.length ) {
				update_preview( $select, false );

				if ( ACFFA.chosen ) {
					initialize_chosen( $select, $select.data('allow_null') );
				}
			}
		});
	
	});

	$( document ).on( 'click', '.acf-tab-button', function( e ) {
		e.preventDefault();
		
		var $wrap	= $( this ).closest('.acf-tab-wrap').parent();
		var key		= $( this ).attr('data-key');

		$wrap.children('.field_type-tab').each( function() {
			var $tab = $( this );

			if ( key == $tab.attr('data-field_key') ) {
				$( this ).nextUntil('.field_type-tab').each( function() {
					var $select	= $( '.chosen-fontawesome:not(.chosen_initialized):visible', this );
					if ( $select.length ) {
						$.each( $select, function( index, select_element ) {
							update_preview( $( select_element ), false );

							if ( ACFFA.chosen ) {
								initialize_chosen( $( select_element ), $( select_element ).data('allow_null') );
							}
						});
					}
				});
			}
		});
	});

	$( document ).on( 'change', '.field_type select', function() {
		if ( 'font-awesome' == $( this ).val() ) {
			if ( ACFFA.chosen ) {
				var font_awesome_form = $( this ).closest( '.field_form' );
				var ajaxLoadWait = setInterval( function() {
					if ( $( '.chosen-fontawesome', font_awesome_form ).length ) {
						clearInterval( ajaxLoadWait );

						var $select	= $( '.chosen-fontawesome:not(.chosen_initialized):visible', font_awesome_form );

						if ( $select.length ) {
							initialize_chosen( $select, true );
						}
					}
				}, 100 );
			}
		}
	});

	$( document ).on( 'acf/field_form-open', function( event, field ) {
		var $select	= $( '.chosen-fontawesome:not(.chosen_initialized)', field );

		if ( $select.length ) {
			$.each( $select, function( index, select_element ) {
				var parent = $( select_element ).closest('.field');
				update_preview( $( select_element ), parent );

				if ( ACFFA.chosen ) {
					initialize_chosen( $( select_element ), true );
				}
			});
		}
	});

	$( document ).on( 'acf/field_form-close', function( event, field ) {
		if ( ACFFA.chosen ) {
			var $select	= $( '.chosen-fontawesome.chosen_initialized', field );

			if ( $select.length ) {
				$select.removeClass('chosen_initialized').chosen('destroy');
			}

			$duplicate_field = $( field ).next('.field');

			if ( $duplicate_field.length && $duplicate_field.hasClass('form_open') ) {
				if ( $duplicate_field.hasClass('field_type-font-awesome') || $duplicate_field.hasClass('field_type-repeater') || $duplicate_field.hasClass('field_type-flexible_content') ) {
					var $duplicate_selects = $( '.chosen-fontawesome.chosen_initialized', $duplicate_field );

					if ( $duplicate_selects.length ) {
						$.each( $duplicate_selects, function( index, select_element ) {
							$( select_element ).removeClass('chosen_initialized').css('display','block').chosen('destroy').next('.chosen-container').remove();
						});
					}

				}
			}
		}
	});

	$( document ).on( 'change', '.chosen-fontawesome', function( evt, params ) {
		update_preview( $( this ), false );
	});

})(jQuery);
