jQuery(document).ready(function(){
	
	jQuery('.redux-opts-checkbox-hide-all').each(function(){
		if(!jQuery(this).is(':checked')){
			jQuery(this).closest('tr').nextAll('tr').hide();
		}
	});
	
	jQuery('.redux-opts-checkbox-hide-all').click(function(){
			jQuery(this).closest('tr').nextAll('tr').fadeToggle('slow');
	});
	
});
