(function($){

	$(document).on( 'change', '.field_type select', function() {

		if( $(this).val() == 'font-awesome' ) {
			var font_awesome_form = $(this).closest( '.field_form' );
			var font_awesome_select = $( 'select.fontawesome', font_awesome_form );
			setTimeout(function() {
				$( 'select.fontawesome', font_awesome_form ).select2({
					width : '100%'
				});
				update_preview( font_awesome_select, $( font_awesome_select ).val() );
			}, 1000);
		}

	});

	$(document).on( 'acf/field_form-open', function( e, field ) {

		element = $( 'select.fontawesome', field );

		$( element ).select2({
			width : '100%'
		});
		update_preview( element, $(element).val() );
	});

	$(document).on( 'select2-selecting', 'select.fontawesome', function( object ) {
		update_preview( this, object.val );
	});

	$(document).on( 'select2-highlight', 'select.fontawesome', function( object ) {
		update_preview( this, object.val );
	});

	$(document).on( 'select2-close', 'select.fontawesome', function( object ) {
		update_preview( this, $(this).val() );
	});

	function update_preview( element, selected ) {
		var parent = $(element).parent();
		$( '.fa-live-preview', parent ).html( '<i class="fa ' + selected + '"></i>' );
	}

})(jQuery);