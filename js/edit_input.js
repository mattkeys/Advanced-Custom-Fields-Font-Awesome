(function($){

	/*
	*  acf/setup_fields
	*
	*  This event is triggered when ACF adds any new elements to the DOM. 
	*
	*  @type	function
	*  @since	1.0.0
	*  @date	01/01/12
	*
	*  @param	event		e: an event object. This can be ignored
	*  @param	Element		postbox: An element which contains the new HTML
	*
	*  @return	N/A
	*/

	fa_initialized = false;

	$(document).on( 'acf/setup_fields', function( e, postbox ) {

		if( $( '.fa-field-wrapper', postbox ).length > 0 ) {

			if( fa_initialized ) {

				var last_row = $( '.row-clone' ).prev( '.row' );

				$( last_row ).each( function() {
					$( 'select.fa-select2-field', this ).each( function() {
						$(this).select2({
							width : '100%'
						});
						update_preview( this, $(this).val() );
					});
				});

				var last_layout = $( '.acf-flexible-content .values' ).last();

				$( 'tbody > tr.field_type-font-awesome select.fa-select2-field', last_layout ).each( function() {
					$(this).select2({
						width : '100%'
					});
					update_preview( this, $(this).val() );
				});

			} else {

				$( '.row' ).each( function() {
					$( 'select.fa-select2-field', this ).each( function() {
						$(this).select2({
							width : '100%'
						});
						update_preview( this, $(this).val() );
					});
				});

				$( '.acf-flexible-content .values tbody > tr.field_type-font-awesome select.fa-select2-field' ).each( function() {
					$(this).select2({
						width : '100%'
					});
					update_preview( this, $(this).val() );
				});

				$( '.field_type-font-awesome select.fa-select2-field' ).each( function() {
					$(this).select2({
						width : '100%'
					});
					update_preview( this, $(this).val() );
				});

			}

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

	});

	function update_preview( element, selected ) {
		var parent = $(element).parent();
		$( '.fa-live-preview', parent ).html( '<i class="fa ' + selected + '"></i>' );
	}

})(jQuery);