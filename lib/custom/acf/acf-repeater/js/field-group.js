(function($){

	/*
	*  Repeater CHange layout display (Row | Table)
	*
	*  @description: 
	*  @since 3.5.2
	*  @created: 18/11/12
	*/
	
	$('#acf_fields .field_option_repeater_layout input[type="radio"]').live('click', function(){
		
		// vars
		var radio = $(this);
		
		
		// Set class
		radio.closest('.field_option_repeater').siblings('.field_option_repeater_fields').find('.repeater:first').removeClass('layout-row').removeClass('layout-table').addClass( 'layout-' + radio.val() );
		
	});
	
	$(document).live('acf/field_form-open', function(e, field){
		
		$(field).find('.field_option_repeater_layout input[type="radio"]:checked').each(function(){
			$(this).trigger('click');
		});
		
	});

})(jQuery);
