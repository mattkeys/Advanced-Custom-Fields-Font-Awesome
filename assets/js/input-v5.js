(function($){
	
	function update_preview( value, parent ) {
		$( '.acf-field-setting-fa_live_preview .acf-input', parent ).html( '<i class="fa ' + value + '" aria-hidden="true"></i>' );
		$( '.icon_preview', parent ).html( '<i class="fa ' + value + '" aria-hidden="true"></i>' );
	}
	
	function select2_init_args( element ) {
		return {
			key			: $( element ).data('key'),
			allow_null	: $( 'select', element ).data('allow_null'),
			ajax		: 1,
			ajax_action	: 'acf/fields/font-awesome/query'
		}
	}

	acf.add_action( 'ready', function( $el ) {

		// Create Field Groups
		var $field_objects = $('.acf-field-object[data-type="font-awesome"]');

		$field_objects.each( function( index, field_object ) {
			update_preview( $( 'select.fontawesome-create', field_object ).val(), field_object );
		});

		// Edit Field Groups
		var $fa_fields = $('.acf-field-font-awesome:visible');

		$fa_fields.each( function( index, fa_field ) {
			update_preview( $( 'select', fa_field ).val(), fa_field );

			acf.select2.init( $( 'select', fa_field ), select2_init_args( fa_field ), $( fa_field ) );
		});
	});

	acf.add_action( 'append', function( $el ) {

		if ( $( 'select.fontawesome-edit', $el ).length ) {
			var $fa_fields = $el;

			$fa_fields.each( function( index, fa_field ) {
				update_preview( $( 'select.fontawesome-edit', fa_field ).val(), fa_field );

				acf.select2.init( $( 'select.fontawesome-edit', fa_field ), select2_init_args( fa_field ), $( fa_field ) );
			});
		}
	});

	acf.add_filter( 'select2_args', function( args, $select, settings, $field ) {

		if ( $select.hasClass('select2-fontawesome') ) {
			args.dropdownCssClass = 'fa-select2-drop';
			args.containerCssClass = 'fa-select2';
		}

		return args;
	});

	acf.add_action( 'change', function( $input ) {

		if ( $input.hasClass('fontawesome-create') ) {
			update_preview( $input.val(), $input.closest('.acf-field-object') );
		}

		if ( $input.hasClass('fontawesome-edit') ) {
			update_preview( $input.val(), $input.closest('.acf-field-font-awesome') );
		}
	});

})(jQuery);
