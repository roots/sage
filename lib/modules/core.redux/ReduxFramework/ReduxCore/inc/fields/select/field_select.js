(function($){
	"use strict";
    
    $.redux = $.redux || {};
	
    $(document).ready(function () {
         $.redux.select();
    });

    $.redux.select = function(){
    	$('.redux-select-item').each(function() {
			if ( $(this).hasClass('font-icons') ) {
				$(this).select2({
					width: 'resolve',
					triggerChange: true,
					allowClear: true,
					formatResult: addIconToSelect,
					formatSelection: addIconToSelect,
					escapeMarkup: function(m) { return m; }
				});
			} else {
				$(this).select2({
					width: 'resolve',
					triggerChange: true,
					allowClear: true
				});
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