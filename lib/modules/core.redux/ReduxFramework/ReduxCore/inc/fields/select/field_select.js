jQuery(document).ready(function(){

	function addIconToSelect(icon) {
		if ( icon.hasOwnProperty( 'id' ) ) {
			return "<span class='elusive'><i class='" + icon.id + "'></i>" + "&nbsp;&nbsp;" + icon.id.toUpperCase() + "</span>";
		}

    }

	jQuery('.redux-select-item').each(function() {
		if ( jQuery(this).hasClass('elusive-icons') ) {
			jQuery(this).select2({
					width: 'resolve',
					triggerChange: true,
					allowClear: true,
					formatResult: addIconToSelect,
					formatSelection: addIconToSelect,
					escapeMarkup: function(m) { return m; }
			});
		} else {
			jQuery(this).select2({
					width: 'resolve',
					triggerChange: true,
					allowClear: true
			});
                        jQuery(this).on("change", function(e) { 
                            redux_change(jQuery(jQuery(this).attr('id')));
                        });
		}

	});

});