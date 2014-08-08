(function($){
	
	
	function initialize_field( $el ) {
		
		fa_initialized = false;		

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

		// ACF 5 Flex Clones
		$( '.clones select.fa-select2-field' ).each(function() {
			$(this).select2( 'destroy' );
		});

		// ACF 5 Repeater Clones
		$( 'tr.acf-row.clone select.fa-select2-field' ).each(function() {
			$(this).select2( 'destroy' );
		});

		// ACF 4 Repeater Clones
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
	
		/*
		*  ready append (ACF5)
		*
		*  These are 2 events which are fired during the page load
		*  ready = on page load similar to $(document).ready()
		*  append = on new DOM elements appended via repeater field
		*
		*  @type	event
		*  @date	20/07/13
		*
		*  @param	$el (jQuery selection) the jQuery element which contains the ACF fields
		*  @return	n/a
		*/
		
		acf.add_action('ready append', function( $el ){
			
			// search $el for fields of type 'FIELD_NAME'
			acf.get_fields({ type : 'font-awesome'}, $el).each(function(){
				
				initialize_field( $(this) );
				
			});
			
		});
		
		
	} else {
		
		
		/*
		*  acf/setup_fields (ACF4)
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
		*  @return	n/a
		*/
		
		$(document).live('acf/setup_fields', function(e, postbox){

			$(postbox).find('.field[data-field_type="font-awesome"], .sub_field[data-field_type="font-awesome"]').each(function(){
				initialize_field( $(this) );
			});
		
		});
	
	
	}


})(jQuery);