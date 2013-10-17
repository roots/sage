/*
*  Repeater
*
*  @description: 
*  @since: 3.5.8
*  @created: 17/01/13
*/

(function($){
	
	
	/*
	*  Vars
	*
	*  @description: 
	*  @since: 3.6
	*  @created: 30/01/13
	*/
	
	acf.fields.repeater = {
		update_order : function(){},
		set_column_widths : function(){},
		add_sortable : function(){},
		update_classes : function(){},
		add_row : function(){},
		remove_row : function(){},
		text : {
			min : "Minimum rows reached ( {min} rows )",
			max : "Maximum rows reached ( {max} rows )"
		}
	};
	
	var _repeater = acf.fields.repeater;
	
	
	/*
	*  update_order
	*
	*  @description: 
	*  @since: 3.5.8
	*  @created: 17/01/13
	*/
	
	_repeater.update_order = function( repeater )
	{
		repeater.find('> table > tbody > tr.row').each(function(i){
			$(this).children('td.order').html( i+1 );
		});
	
	};
	
	
	/*
	*  set_column_widths
	*
	*  @description: 
	*  @since: 3.5.8
	*  @created: 17/01/13
	*/
	
	_repeater.set_column_widths = function( repeater )
	{
		// validate
		if( repeater.children('.acf-input-table').hasClass('row_layout') )
		{
			return;
		}
		

		// accomodate for order / remove
		var column_width = 100;
		if( repeater.find('> .acf-input-table > thead > tr > th.order').exists() )
		{
			column_width = 93;
		}
		
		
		// find columns that already have a width and remove these amounts from the column_width var
		repeater.find('> .acf-input-table  > thead > tr > th[width]').each(function( i ){
			
			column_width -= parseInt( $(this).attr('width') );
		});

		
		var ths = repeater.find('> .acf-input-table > thead > tr > th').not('[width]').has('span');
		if( ths.length > 1 )
		{
			column_width = column_width / ths.length;
			
			ths.each(function( i ){
				
				// dont add width to last th
				if( (i+1) == ths.length  )
				{
					return;
				}
				
				$(this).attr('width', column_width + '%');
				
			});
		}
		
	}
	
	
	/*
	*  add_sortable
	*
	*  @description: 
	*  @since 3.5.2
	*  @created: 11/11/12
	*/
	
	_repeater.add_sortable = function( repeater ){
		
		// vars
		var max_rows = parseFloat( repeater.attr('data-max_rows') );
		
		
		// validate
		if( max_rows <= 1 )
		{
			return;
		}
			
		repeater.find('> table > tbody').unbind('sortable').sortable({
			items : '> tr.row',
			handle : '> td.order',
			helper : acf.helpers.sortable,
			forceHelperSize : true,
			forcePlaceholderSize : true,
			scroll : true,
			start : function (event, ui) {
			
				$(document).trigger('acf/sortable_start', ui.item);
				$(document).trigger('acf/sortable_start_repeater', ui.item);

				// add markup to the placeholder
				var td_count = ui.item.children('td').length;
        		ui.placeholder.html('<td colspan="' + td_count + '"></td>');
        		
   			},
   			stop : function (event, ui) {
			
				$(document).trigger('acf/sortable_stop', ui.item);
				$(document).trigger('acf/sortable_stop_repeater', ui.item);
				
				// update order numbers	
				_repeater.update_order( repeater );		
				
   			}
		});
	};
	
	
	/*
	*  update_classes
	*
	*  @description: 
	*  @since 3.5.2
	*  @created: 11/11/12
	*/
	
	_repeater.update_classes = function( repeater )
	{
		// vars
		var max_rows = parseFloat( repeater.attr('data-max_rows') ),
			row_count = repeater.find('> table > tbody > tr.row').length;	

		
		// empty?
		if( row_count == 0 )
		{
			repeater.addClass('empty');
		}
		else
		{
			repeater.removeClass('empty');
		}
		
		
		// row limit reached
		if( row_count >= max_rows )
		{
			repeater.addClass('disabled');
			repeater.find('> .repeater-footer .acf-button').addClass('disabled');
		}
		else
		{
			repeater.removeClass('disabled');
			repeater.find('> .repeater-footer .acf-button').removeClass('disabled');
		}
		
	}
	
	
	/*
	*  acf/setup_fields
	*
	*  @description: 
	*  @since 3.5.2
	*  @created: 11/11/12
	*/
	
	$(document).live('acf/setup_fields', function(e, postbox){
		
		$(postbox).find('.repeater').each(function(){
			
			var repeater = $(this)
			
			
			// set column widths
			_repeater.set_column_widths( repeater );
			
			
			// update classes based on row count
			_repeater.update_classes( repeater );
			
			
			// add sortable
			_repeater.add_sortable( repeater );
						
		});
			
	});
	
	
	
	/*
	*  Add Row
	*
	*  @description: 
	*  @since: 3.5.8
	*  @created: 17/01/13
	*/
	
	_repeater.add_row = function( repeater, before )
	{
		// vars
		var max_rows = parseInt( repeater.attr('data-max_rows') ),
			row_count = repeater.find('> table > tbody > tr.row').length;	
			
			
		// validate
		if( row_count >= max_rows )
		{
			alert( _repeater.text.max.replace('{max}', max_rows) );
			return false;
		}
		
	
		// create and add the new field
		var new_id = acf.helpers.uniqid(),
			new_field_html = repeater.find('> table > tbody > tr.row-clone').html().replace(/(=["]*[\w-\[\]]*?)(acfcloneindex)/g, '$1' + new_id),
			new_field = $('<tr class="row"></tr>').append( new_field_html );
		
		
		// add row
		if( !before )
		{
			before = repeater.find('> table > tbody > .row-clone');
		}
		
		before.before( new_field );
		
		
		// trigger mouseenter on parent repeater to work out css margin on add-row button
		repeater.closest('tr').trigger('mouseenter');
		
		
		// update order
		_repeater.update_order( repeater );
		
		
		// update classes based on row count
		_repeater.update_classes( repeater );
		
		
		// setup fields
		$(document).trigger('acf/setup_fields', new_field);

		
		// validation
		repeater.closest('.field').removeClass('error');
	}
	
	
	/*
	*  Add Button
	*
	*  @description: 
	*  @since: 3.5.8
	*  @created: 17/01/13
	*/
	
	$('.repeater .repeater-footer .add-row-end').live('click', function(){
		
		var repeater = $(this).closest('.repeater');
		
		_repeater.add_row( repeater, false );
		
		return false;
	});
	
	
	/*
	*  Add Before Button
	*
	*  @description: 
	*  @since: 3.5.8
	*  @created: 17/01/13
	*/
	
	$('.repeater td.remove .add-row-before').live('click', function(){
		
		var repeater = $(this).closest('.repeater'),
			before = $(this).closest('tr');
			
		_repeater.add_row( repeater, before );
		
		return false;
	});
	
	
	/*
	*  Remove Row
	*
	*  @description: 
	*  @since: 3.5.8
	*  @created: 17/01/13
	*/
	
	_repeater.remove_row = function( tr )
	{	
		// vars
		var repeater =  tr.closest('.repeater'),
			min_rows = parseInt( repeater.attr('data-min_rows') ),
			row_count = repeater.find('> table > tbody > tr.row').length,
			column_count = tr.children('tr.row').length,
			row_height = tr.height();
			
			
		// validate
		if( row_count <= min_rows )
		{
			alert( _repeater.text.min.replace('{min}', row_count) );
			return false;
		}
		
		
		// animate out tr
		tr.addClass('acf-remove-item');
		setTimeout(function(){
			
			tr.remove();
			
			
			// trigger mouseenter on parent repeater to work out css margin on add-row button
			repeater.closest('tr').trigger('mouseenter');
		
		
			// update order
			_repeater.update_order( repeater );
			
			
			// update classes based on row count
			_repeater.update_classes( repeater );
			
		}, 400);
		
	}
	
	
	/*
	*  Remove Row
	*
	*  @description: 
	*  @since: 3.5.8
	*  @created: 17/01/13
	*/
	
	$('.repeater td.remove .acf-button-remove').live('click', function(){
	
		var tr = $(this).closest('tr');
		
		_repeater.remove_row( tr );
		
		return false;
	});
	
	
	/*
	*  hover over tr, align add-row button to top
	*
	*  @description: 
	*  @since: 3.5.8
	*  @created: 17/01/13
	*/
	
	$('.repeater tr').live('mouseenter', function(){
		
		var button = $(this).find('> td.remove > a.acf-button-add');
		var margin = ( button.parent().height() / 2 ) + 9; // 9 = padding + border
		
		button.css('margin-top', '-' + margin + 'px' );
		
	});
	

})(jQuery);
