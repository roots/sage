/* global redux_change */
(function($){
    "use strict";
    
    $.redux.multi_text = $.group || {};
	
    $(document).ready(function () {
        //multi text functionality
        $.redux.multi_text();
    });

    $.redux.multi_text = function(){
		$('.redux-multi-text-remove').live('click', function() {
			redux_change($(this));
			$(this).prev('input[type="text"]').val('');
			$(this).parent().slideUp('medium', function(){
				$(this).remove();
			});
		});
		
		$('.redux-multi-text-add').click(function(){
			var number = parseInt($(this).attr('data-add_number'));
			var id = $(this).attr('data-id');
			var name = $(this).attr('data-name');
			for (var i = 0; i < number; i++) {
				var new_input = $('#'+id+' li:last-child').clone();
				$('#'+id).append(new_input);
				$('#'+id+' li:last-child').removeAttr('style');
				$('#'+id+' li:last-child input[type="text"]').val('');
				$('#'+id+' li:last-child input[type="text"]').attr('name' , name);
			}
		});
    };
})(jQuery);