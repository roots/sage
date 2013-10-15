(function($){
	'use strict';

	$.redux = $.redux || {};

	$(document).ready(function(){
		$.redux.spacing();
	});

	$.redux.spacing = function(){
		$(".redux-spacing-top, .redux-spacing-bottom, .redux-spacing-left, .redux-spacing-right").numeric();

		$(".redux-spacing-units").select2({
			width: 'resolve',
			triggerChange: true,
			allowClear: true
		});

		$('.redux-spacing-input').on('change', function() {
			var units = $(this).parents('.redux-spacing-container:first').find('.redux-spacing-units option:selected').val();
			var id = $(this).attr('rel');
			$('#'+id).val($(this).val()+units);
		});

		$('.redux-spacing-units').on('change', function() {
			$(this).parents('.redux-spacing-container:first').find('.redux-spacing-input').change();
		});
	}

})(jQuery);