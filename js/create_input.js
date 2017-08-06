(function($){

	var version_num = ACFFA.version;

	$(document).on( 'change', '.field_type-select select, .field_type select, .acf-field-select select', function() {

		if ( $(this).val() == 'font-awesome' ) {

			var font_awesome_form;

			if ( 5 == version_num ) {
				// ACF 5
				font_awesome_form = $(this).closest( '.acf-field-object-font-awesome' );
			} else {
				// ACF 4
				font_awesome_form = $(this).closest( '.field_form' );
			}

			setTimeout(function() {
				var font_awesome_select = $( 'select.fontawesome', font_awesome_form );

				update_preview( font_awesome_select, $( font_awesome_select ).val(), version_num );

				if ( $('.select2-container', font_awesome_form).length === 0 ) {
					font_awesome_select.select2({
						width : '100%',
						dropdownCssClass : 'fa-select2-drop'
					});
				} else {
					$('.select2-container', font_awesome_form).remove();
					font_awesome_select.select2({
						width : '100%',
						dropdownCssClass : 'fa-select2-drop'
					});
				}
			}, 1000);
		}

	});

	if ( 5 == version_num ) {
		acf.add_filter('select2_args', function( args, $select, settings, $field ){
			
			if ( $field.data('setting') == 'font-awesome' ) {
				args.dropdownCssClass = 'fa-select2-drop';
			}

			// return
			return args;
					
		});
	}

	$(document).ready(function($) {
	
		elements = $( 'select.fontawesome' );

		if ( 4 == version_num ) {
			$( elements ).select2({
				width : '100%',
				dropdownCssClass : 'fa-select2-drop'
			});
		}

		$.each( elements , function( index, el ) {
			update_preview( el, $(el).val(), version_num );
		});

	});

	$(document).on( 'select2-selecting select2:selecting', 'select.fontawesome', function( object ) {
		update_preview( this, object.val, version_num );
	});

	$(document).on( 'select2-highlight select2:highlight', 'select.fontawesome', function( object ) {
		update_preview( this, object.val, version_num );
	});

	$(document).on( 'select2-close select2:close', 'select.fontawesome', function( object ) {
		update_preview( this, $(this).val(), version_num );
	});

	function update_preview( element, selected, version ) {
		if ( version == 4 ) {
			$( '.fa-live-preview', $(element).parent() ).html( '<i class="fa ' + selected + '"></i>' );
		} else {
			var sibling = $(element).closest('tr').siblings('tr[data-name="fa_live_preview"]');
			$( 'td.acf-input', sibling ).html( '<i class="fa ' + selected + '"></i>' );
		}
	}

})(jQuery);