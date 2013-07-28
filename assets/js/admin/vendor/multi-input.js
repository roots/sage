/**********************************

	This handles multi input fields
	in custom meta...
	
	It allows the user to add multiple
	items in the same meta and pipes 
	the items into one input

**********************************/

jQuery(document).ready(function(){
	set_up_form();
	setup_toolbars();
	//jQuery('.multi-input-field').hide();
	jQuery('.multi-input .field-group input').keyup(function(index, row){
		update_all_fields();
	}); 
});

function set_up_form(){

	// this function grabs any existing multi-input data
	// and creates html fields for them via process_current_fields()
	
	var $ 				= jQuery;
	var div_selector 	= $('.multi-input input');
	var fields 			= $.parseJSON(div_selector.attr('data-fields'));
	
	//console.log(div_selector.val());
	
	$.each(div_selector, function(index, row){
		var field_id = $(row).attr('id');
		
		process_current_fields({
			field_id 	: field_id,
			element		: row		
		});
		//console.log($('.multi-input',this));
		
		$(this).parent().append(
			'<a href="javascript:add_empty_fields({unique:undefined});">Add New</a>'
		);

	});
}

function add_empty_fields(obj){
	// define the unique id
	var unique 			= obj.unique;
	var clicked_from 	= obj.location ? obj.location : '';

	var $ = jQuery;
	var original_input = $('.multi-input');
	var multi_input_field = $('.multi-input-field').attr('data-fields');

	var fields = $.parseJSON(convert_to_quotes(multi_input_field));
	var inputs = '';
	var field_containers = '';
	var fields_open = '<div style="border:1px solid #ccc; padding: 5px;margin-top: 10px;" class="field-group">' + make_toolbar();
	var fields_close = '</div>';
	var html = '';
	var inputs = '';
		
	html = html + fields_open;
	
	for (var key in fields) {
 		var field_obj = fields[key];
		
		inputs = inputs + '<input type="text" id="'+field_obj.id+'_'+obj.unique+'" placeholder="'+field_obj.title+'" value="" class="'+field_obj.id+'" data-field-id="'+field_obj.id+'" size="30" /><br/>';		

	}	
	
	html = html + inputs + fields_close;
		
	original_input.append( html );
	
	// if location was specified, move the group there
	if(clicked_from){
		
		$('.field-group:last').hide().insertAfter( clicked_from ).fadeIn('300');
	}
	
	jQuery('.multi-input .field-group input').keyup(function(index, row){
		update_all_fields({ index: index });
	});

}

function add_input(obj){
	// not using this but I wanted to keep it around for a little while
	
	/*
	var $ = jQuery;
	var original_input = $(obj.element);

	var fields = obj.fields;
	var inputs = '';
	var field_containers = '';
	
	var fields_close = '</div>';
			
	var input = '<input type="text" id="'+fields.id+'" placeholder="Link Title" value="'+fields.value+'" class="'+fields.id+'" /><br/>';

	return input;
	*/
	return false;
}

function process_current_fields(obj){
	var $ = jQuery;
	/*
	var required_fields = $.parseJSON($('.multi-input-field').attr('data-fields'));
	console.log(required_fields);
	*/
	var multi_input_field = $('.multi-input-field').val();

	var fields 			= $.parseJSON(convert_to_quotes(multi_input_field));
	var fields_per_group= $.parseJSON($('.multi-input-field').attr('data-fields')).length;	
	var total_groups 	= fields ? fields.length / fields_per_group : 0;
	if(fields){
		// first add the empty inputs
		for (var i=0;i<total_groups;i++){ 
			add_empty_fields({ unique : i });
		}	
			
		// now add the data to those inputs
		var i 					= 0;
		var group_tracker		= 0;
		
		$.each(fields, function(index, row){
	
			// find the input
			var input = $('#' + row.id + '_' + group_tracker);
	
			// make sure the data is filler data (there to keep the form safe)
			var value = row.value != row.id ? row.value : '';
					
			// add the data to the input
			input.val(value);
			
			if(i === fields_per_group -1){
				// reached the last item in the group
				// so go to next group
				group_tracker++;
				i = 0;
			} else {
				i++;
			}
					
		});	
	} // end if fields
}

function update_all_fields(){

	var $ 				= jQuery;
	var inputs 			= $('.multi-input input');
	var field_groups	= $('.field-group');
	var master_field	= $('.multi-input-field');
	var string_to_save	= '[';
	
	// empty the master field
	master_field.val('');
	
	// iterate through the field groups
	$.each(field_groups, function(index, row){
	
		// iterate through the inputs in this field group
		$.each($('input',row), function(i, r){

			var field_id 	= $(r).attr('data-field-id');
			var field_value = $(r).val();
			var comma 		= index === 0 && i === 0 ? '' : ',';
			
			// add this field to the array of fields/values
			// using pipes here so we can submit "escaped" data
			// pipes in the field will later be replaces with quotes
			// so the string can be json_encoded and decoded with php
			if(field_value){
				string_to_save = string_to_save + comma + '{|id|:|'+field_id+'|,|value|:|'+field_value+'|}';
			} else { 	
				// since an empty value would break or setup (iterations have to match etc...)
				// we'll add the id as a value if one does not exist
				// then we'll hide that when we show it to the user later
				string_to_save = string_to_save + comma + '{|id|:|'+field_id+'|,|value|:|'+field_id+'|}';
			}
			
		});

		var total_results 	= inputs.length - 1;
				
	});
	string_to_save = string_to_save + ']';
	master_field.val(master_field.val() + string_to_save);
}

function make_toolbar(){
	
	var $ = jQuery;
	
	// make the toolbar that controls each group
	var html = 	'<ul class="multi-toolbar">'+
					'<li><button type="button" onClick="return false" class="move-up"> &uarr; </button></li>'+
					'<li><button type="button" onClick="return false" class="move-down"> &darr; </button></li>'+
					'<li><button type="button" onClick="return false" class="add-group">+</button></li>'+
					'<li><button type="button" onClick="return false" class="delete-group"> - </button></li>'+
				'</ul>';
	
	return html;	
}

function setup_toolbars(){
	jQuery('.move-up').live('click', move_group_up);
	jQuery('.move-down').live('click', move_group_down);
	jQuery('.add-group').live('click', add_group);
	jQuery('.delete-group').live('click', delete_group);
}

function move_group_up(){
	// moves the group up in the order
    var group = jQuery(this).closest('.field-group');
        group.insertBefore(group.prev());	
    
    // update the fields
    update_all_fields();
    
    // grab current background
    var background = group.css('background-color');
    
    // change background to give user feedback
    group.animate({ backgroundColor : 'yellow' });
    
    // now change it back
    group.animate({ backgroundColor : background });
    
}

function move_group_down(){
	// moves the group down in the order
    var group = jQuery(this).closest('.field-group');
        group.insertAfter(group.next());	
    
    // update the fields
    update_all_fields();

    // grab current background
    var background = group.css('background-color');
    
    // change background to give user feedback
    group.animate({ backgroundColor : 'yellow' });
    
    // now change it back
    group.animate({ backgroundColor : background });
}

function add_group(){
	
	// adds a new group
	add_empty_fields({
		unique		:undefined,
		location 	: jQuery(this).closest('.field-group')
	});
}

function delete_group(){
	// removes a group
	var group = jQuery(this).closest('.field-group');
	group.fadeOut(500, function(){
		group.remove();
	    // update the fields
	    update_all_fields();
	
	});
		
}

function convert_to_quotes(string){
	// quotes have to be removed since they
	// will be escaped... this adds them back in
	
	return string.replace(/(\|)/g,"\"");
}

function convert_from_quotes(string){
	// quotes have to be removed before
	// they go into wordpress... see 
	// convert_to_quotes() for the reverse
	// this should be the opposite of that

	return string.replace(/(\")/g,"\|");

}