(function($){
	"use strict";
    
    $.redux = $.redux || {};
	
    $(document).ready(function () {
         $.redux.select();
    });

    $.redux.select = function(){
    	$('.redux-select-item').each(function() {

		    var default_params = {
			    width: 'resolve',
			    triggerChange: true,
			    allowClear: true
		    };

		    if ( $(this).siblings('.select2_params').size() > 0 ) {
			    var select2_params = $(this).siblings('.select2_params').val();
			    select2_params = JSON.parse( select2_params );
			    default_params = $.extend({}, default_params, select2_params);
		    }

			if ( $(this).hasClass('font-icons') ) {
				default_params = $.extend({}, {formatResult: addIconToSelect, formatSelection: addIconToSelect, escapeMarkup: function(m) { return m; } }, default_params);
				$(this).select2(default_params);
			} else {
				$(this).select2(default_params);
	            $(this).on("change", function(e) { 
	                redux_change($($(this).attr('id')));
	            });
			}
		});
    }	

	function addIconToSelect(icon) {
		if ( icon.hasOwnProperty( 'id' ) ) {
			return "<span class='elusive'><i class='" + icon.id + "'></i>" + "&nbsp;&nbsp;" + icon.id.toUpperCase() + "</span>";
		}
    }
})(jQuery);