/**
 *  Date and Time Picker
 */
(function($){
	$(document).live('acf/setup_fields', function(e, postbox){
		$(postbox).find('input.ps_timepicker').each(function(){
			var input = $(this)
				, is_timeonly = (input.attr('data-date_format') == undefined)
				, date_format = (input.attr('data-date_format') != undefined) ? input.attr('data-date_format') : 'mm/dd/yy'
				, time_format = input.attr('data-time_format')
				, has_ampm = (input.attr('data-time_format').search(/t/i) != -1);

			if( acf.helpers.is_clone_field(input) )
			{
				return;
			}


			input.addClass('active').attr("placeholder", (is_timeonly) ? time_format : date_format + ' ' + time_format).datetimepicker({
				changeYear: true
				, yearRange: "-100:+100"
				, changeMonth: true				
				, timeOnly: is_timeonly
				, timeFormat: time_format
				, dateFormat: date_format
				, showWeek: (input.attr('data-show_week_number') != "true") ? 0 : 1
				, ampm: has_ampm
				, controlType: input.attr('data-picker')
				, timeOnlyTitle: input.attr('title')
				, monthNames: timepicker_objectL10n.monthNames
				, monthNamesShort: timepicker_objectL10n.monthNamesShort
				, dayNames: timepicker_objectL10n.dayNames
				, dayNamesShort: timepicker_objectL10n.dayNamesShort
				, dayNamesMin: timepicker_objectL10n.dayNamesMin
				, firstDay: timepicker_objectL10n.firstDay
			});

			
			if($('body > #ui-datepicker-div').length > 0)
			{
				$('#ui-datepicker-div').wrap('<div class="ui-acf" />');
			}

			// allow null
			input.blur(function(){
				
				if( !input.val() )
				{
					input.val('');
				}
			});

		});
	});
})(jQuery);