(function($){
	
	var fa_initialized = false;

	function initialize_field( $el ) {

		if( fa_initialized ) {

			// ACF 5
			var last_row = $( '.acf-clone' ).prev( '.acf-row' );

			// ACF 4
			if ( last_row.length === 0 ) {
				last_row = $( '.row-clone' ).prev( '.row' );
			}

			$( last_row ).each( function() {
				$( 'select.fa-select2-field', this ).each( function() {
					$(this).select2({
						width : '100%'
					});
					update_preview( this, $(this).val() );
				});
			});

			// ACF 5
			var last_layout = $( '.acf-flexible-content .values' ).last();

			// ACF 4
			if ( last_layout.length === 0 ) {
				last_layout = $( '.acf_flexible_content .values' ).last();
			}

			$( '.field_type-font-awesome select.fa-select2-field', last_layout ).each( function() {
				$(this).select2({
					width : '100%'
				});
				update_preview( this, $(this).val() );
			});

		} else {

			// Initialize flexible content Font Awesome fields
			$( '.acf-flexible-content .values tbody > tr.field_type-font-awesome select.fa-select2-field' ).each( function() {
				$(this).select2({
					width : '100%'
				});
				update_preview( this, $(this).val() );
			});

			// Initialize basic (not repeater, not flexible content) Font Awesome fields
			$( '.field_type-font-awesome select.fa-select2-field' ).each( function() {
				$(this).select2({
					width : '100%'
				});
				update_preview( this, $(this).val() );
			});

		}

		// ACF 5 Flex Clones
		$( '.clones select.fa-select2-field' ).each(function() {
			$(this).select2( 'destroy' );
		});

		// ACF 5 Repeater Clones
		$( 'tr.acf-row.acf-clone select.fa-select2-field' ).each(function() {
			$(this).select2( 'destroy' );
		});

		// ACF 4 Repeater & Flex Clones
		$( 'tr.row-clone select.fa-select2-field' ).each(function() {
			$(this).select2( 'destroy' );
		});

		$( 'select.fa-select2-field' ).on( 'select2-selecting', function( object ) {
			update_preview( this, object.val );
		});

		$( 'select.fa-select2-field' ).on( 'select2-highlight', function( object ) {
			update_preview( this, object.val );
		});

		$( 'select.fa-select2-field' ).on( 'select2-close', function( object ) {
			update_preview( this, $(this).val() );
		});

		fa_initialized = true;

	}
	
	function update_preview( element, selected ) {
		var parent = $(element).parent();
		$( '.fa-live-preview', parent ).html( '<i class="fa ' + selected + '"></i>' );
	}

	if( typeof acf.add_action !== 'undefined' ) {

		acf.add_action('ready append', function( $el ){

			// search $el for fields of type 'FIELD_NAME'
			acf.get_fields({ type : 'font-awesome'}, $el).each(function(){

				initialize_field( $(this) );

			});

		});

	} else {

		$(document).live('acf/setup_fields', function(e, postbox){

			$(postbox).find('.field[data-field_type="font-awesome"], .sub_field[data-field_type="font-awesome"]').each(function(){
				initialize_field( $(this) );
			});

		});

	}

})(jQuery);