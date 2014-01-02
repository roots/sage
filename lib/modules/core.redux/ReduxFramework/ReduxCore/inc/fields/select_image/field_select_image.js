(function($){

	$('.redux-select-image-item').on('change', function() {
		var preview = $(this).parents('.redux-field:first').find('.redux-preview-image');
		if ($(this).val() === "") {
			preview.fadeOut('medium', function() {
				preview.attr('src', '');
			});
		} else {
			preview.attr('src', $(this).val());
			preview.fadeIn().css('visibility', 'visible');
		}
	});

})(jQuery);


