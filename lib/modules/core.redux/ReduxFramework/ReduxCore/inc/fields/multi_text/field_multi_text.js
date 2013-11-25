/* global redux_change */
(function($){
    "use strict";
    
    $.redux.multi_text = $.group || {};
	
    $(document).ready(function () {
        //multi text functionality
        $.redux.multi_text();
    });

    $.redux.multi_text = function(){
    	$('.redux-multi-text-remove').live('click', function(){
			redux_change($(this));
			$(this).prev('input[type="text"]').val('');
			$(this).parent().slideUp('medium', function(){$(this).remove();});
		});
		
		$('.redux-multi-text-add').click(function(){
			var new_input = $('#'+$(this).attr('rel-id')+' li:last-child').clone();
			$('#'+$(this).attr('rel-id')).append(new_input);
			$('#'+$(this).attr('rel-id')+' li:last-child').removeAttr('style');
			$('#'+$(this).attr('rel-id')+' li:last-child input[type="text"]').val('');
			$('#'+$(this).attr('rel-id')+' li:last-child input[type="text"]').attr('name' , $(this).attr('rel-name'));
		});
    }

})(jQuery);    
