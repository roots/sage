/*
*  Flexible Content
*
*  @description: 
*  @since: 3.5.8
*  @created: 17/01/13
*/

(function($){
	
	// vars
	acf.text.flexible_content_no_fields = 'Flexible Content requires at least 1 layout';
	

	/*----------------------------------------------------------------------
	*
	*	Add Layout Option
	*
	*---------------------------------------------------------------------*/
	
	$('#acf_fields .acf_fc_add').live('click', function(){
		
		// vars
		var tr = $(this).closest('tr.field_option_flexible_content'),
			new_tr = tr.clone(false),
			id = new_tr.attr('data-id'),
			new_id = acf.helpers.uniqid();
		
		
		// remove sub fields
		new_tr.find('.field:not(.field_key-field_clone)').remove();
		
		// show add new message
		new_tr.find('.no_fields_message').show();
		
		// reset layout meta values
		new_tr.find('.acf_cf_meta input[type="text"]').val('');
		
		
		// update id / names
		new_tr.find('[name]').each(function(){
		
			var name = $(this).attr('name').replace('[layouts]['+id+']','[layouts]['+new_id+']');
			$(this).attr('name', name);
			$(this).attr('id', name);
			
		});
		
		// update data-id
		new_tr.attr('data-id', new_id);
		
		// add new tr
		tr.after(new_tr);
		
		// display
		new_tr.find('.acf_cf_meta select').val('row').trigger('change');
		
		
		return false;
	});
	
	
	/*----------------------------------------------------------------------
	*
	*	Duplicate Layout
	*
	*---------------------------------------------------------------------*/
	
	$('#acf_fields .acf_fc_duplicate').live('click', function(){
		
		// vars
		var tr = $(this).closest('tr.field_option_flexible_content'),
			new_tr = null,
			id = tr.attr('data-id'),
			new_id = acf.helpers.uniqid();
		
			
		// save select values
		tr.find('select').each(function(){
			$(this).attr( 'data-val', $(this).val() );
		});
		
		
		// clone tr
		new_tr = tr.clone(false);
		
		
		// update id / names
		new_tr.find('[name]').each(function(){
		
			var name = $(this).attr('name').replace('[layouts]['+id+']','[layouts]['+new_id+']');
			$(this).attr('name', name);
			$(this).attr('id', name);
			
		});
		
		
		// update data-id
		new_tr.attr('data-id', new_id);
		
		
		// update field names
		new_tr.find('.field:not(.field_key-field_clone)').each(function(){
			$(this).update_names();
		});
		
		
		// add new tr
		tr.after(new_tr);
		
		
		// set select values
		new_tr.find('select').each(function(){
			$(this).val( $(this).attr('data-val') ).trigger('change');
		});
		
		
		// update new_field label / name
		var label = new_tr.find('.acf_fc_label input[type="text"]'),
			name = new_tr.find('.acf_fc_name input[type="text"]');
		
		
		name.val('');
		label.val( label.val() + ' (' + acf.text.copy + ')' );
		label.trigger('blur');
		
		
		return false;
	});
	
	
	/*----------------------------------------------------------------------
	*
	*	Delete Layout Option
	*
	*---------------------------------------------------------------------*/
	
	$('#acf_fields .acf_fc_delete').live('click', function(){

		var tr = $(this).closest('tr.field_option_flexible_content'),
			tr_count = tr.siblings('tr.field_option.field_option_flexible_content').length;

		if( tr_count <= 1 )
		{
			alert( acf.text.flexible_content_no_fields );
			return false;
		}
		
		tr.animate({'left' : '50px', 'opacity' : 0}, 250, function(){
			tr.remove();
		});
		
	});
	
	
	/*----------------------------------------------------------------------
	*
	*	Sortable Layout Option
	*
	*---------------------------------------------------------------------*/
	
	$('#acf_fields .acf_fc_reorder').live('mouseover', function(){
		
		var table = $(this).closest('table.acf_field_form_table');
		
		if(table.hasClass('sortable')) return false;
		
		table.addClass('sortable').children('tbody').sortable({
			items: ".field_option_flexible_content",
			handle: 'a.acf_fc_reorder',
			helper: acf.sortable_helper,
			forceHelperSize : true,
			forcePlaceholderSize : true,
			scroll : true,
			start : function (event, ui) {

				// add markup to the placeholder
				var td_count = ui.item.children('td').length;
        		ui.placeholder.html('<td colspan="' + td_count + '"></td>');
        		
   			}
		});
		
	});
	
	
	/*----------------------------------------------------------------------
	*
	*	Label update name
	*
	*---------------------------------------------------------------------*/
	
	$('#acf_fields .acf_fc_label input[type="text"]').live('blur', function(){
		
		var label = $(this);
		var name = $(this).parents('td').siblings('td.acf_fc_name').find('input[type="text"]');

		if(name.val() == '')
		{
			var val = label.val().toLowerCase().split(' ').join('_').split('\'').join('');
			name.val(val);
			name.trigger('keyup');
		}

	});
	
	
	/*
	*  Flexible Content CHange layout display (Row | Table)
	*
	*  @description: 
	*  @since 3.5.2
	*  @created: 18/11/12
	*/
	
	$('#acf_fields .acf_fc_display select').live('change', function(){
		
		// vars
		var select = $(this);
		
		
		// Set class
		select.closest('.repeater').removeClass('layout-row').removeClass('layout-table').addClass( 'layout-' + select.val() );
		
	});
	
	$(document).live('acf/field_form-open', function(e, field){
		
		$(field).find('.acf_fc_display select').each(function(){
			$(this).trigger('change');
		});
		
	});
	

})(jQuery);
