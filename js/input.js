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

	$(document).live('acf/setup_fields', function(e, postbox){
		
		$(postbox).find('.font-awesome').each(function(){
			
			$(".fa-select2-field").select2({
				width : 'resolve'
			});

			$('.fa-select2-field').on( 'select2-selecting', function( object ) {
				update_preview( this, object.val );
			});

			$('.fa-select2-field').each(function(index, el) {
				update_preview( this, $(this).val() );
			});

			$('.fa-select2-field').on( 'select2-highlight', function( object ) {
				update_preview( this, object.val );
			});

			$('.fa-select2-field').on( 'select2-close', function( object ) {
				update_preview( this, $(this).val() );
			});

			function update_preview( element, selected ) {
				$(element).prevAll('.fa-live-preview').html( '<i class="fa ' + selected + '"></i>' );
			}

		});
	
	});

})(jQuery);
