(function($){
	
	function update_preview( value, parent ) {
		$( '.acf-field-setting-fa_live_preview .acf-input', parent ).html( '<i class="fa ' + value + '" aria-hidden="true"></i>' );
		$( '.icon_preview', parent ).html( '<i class="fa ' + value + '" aria-hidden="true"></i>' );
	}

	function select2_init_args( element, parent ) {
		return {
			key			: $( parent ).data('key'),
			allow_null	: $( element ).data('allow_null'),
			ajax		: 1,
			ajax_action	: 'acf/fields/font-awesome/query'
		}
	}

	function select2_init( fa_field ) {
		var $select = $( fa_field );
		var parent = $( $select ).closest('.acf-field-font-awesome');

		update_preview( $select.val(), parent );

		acf.select2.init( $select, select2_init_args( fa_field, parent ), $( fa_field ) );
	}

	acf.add_action( 'select2_init', function( $input, args, settings, $field ) {
		if ( $field instanceof jQuery && $field.hasClass('fontawesome-edit') ) {
			$field.addClass('select2_initalized');
		}
	});

	// Add our classes to FontAwesome select2 fields
	acf.add_filter( 'select2_args', function( args, $select, settings, $field ) {

		if ( $select.hasClass('select2-fontawesome') ) {
			args.dropdownCssClass = 'fa-select2-drop';
			args.containerCssClass = 'fa-select2';
		}

		return args;
	});

	// Update FontAwesome field previews in field create area
	acf.add_action( 'open_field/type=font-awesome', function( $el ) {
		var $field_objects = $('.acf-field-object[data-type="font-awesome"]');

		$field_objects.each( function( index, field_object ) {
			update_preview( $( 'select.fontawesome-create', field_object ).val(), field_object );
		});
	});

	// Handle new menu items with FontAwesome fields assigned to them
	$( document ).on( 'menu-item-added', function( event, $menuMarkup ) {
		var $fa_fields = $( 'select.fontawesome-edit:not(.select2_initalized)', $menuMarkup );

		if ( $fa_fields.length ) {
			$fa_fields.each( function( index, fa_field ) {
				select2_init( fa_field );
			});
		}
	});

	// Update FontAwesome field previews and init select2 in field edit area
	acf.add_action( 'ready_field/type=font-awesome append_field/type=font-awesome show_field/type=font-awesome', function( $el ) {
		var $fa_fields = $( 'select.fontawesome-edit:not(.select2_initalized)', $el );

		if ( $fa_fields.length ) {
			$fa_fields.each( function( index, fa_field ) {
				select2_init( fa_field );
			});
		}
	});

	// Update FontAwesome field previews when value changes
	acf.add_action( 'change', function( $input ) {

		if ( $input.hasClass('fontawesome-create') ) {
			update_preview( $input.val(), $input.closest('.acf-field-object') );
		}

		if ( $input.hasClass('fontawesome-edit') ) {
			update_preview( $input.val(), $input.closest('.acf-field-font-awesome') );
		}
	});

})(jQuery);
