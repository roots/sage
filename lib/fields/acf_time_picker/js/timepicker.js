/*
Attach a jQuery.datetimepicker() to "input[type=text].time_picker" fields. Will also attach to dynamic added fields.
*/
jQuery(function() {
	jQuery("body").on("focusin", "input[type=text].time_picker",  function(){
		self = jQuery(this);
		self.datetimepicker({
			timeOnly: (self.attr('data-date_format') == undefined),
			timeFormat: self.attr('data-time_format'),
			dateFormat: (self.attr('data-date_format') != undefined) ? self.attr('data-date_format') : 'mm/dd/yy',
			showWeek: (self.attr('data-show_week_number') != "true") ? 0 : 1,
			ampm: (self.attr('data-time_format').search(/t/i) != -1),
			timeOnlyTitle: self.attr('title'),
			closeText: timepicker_objectL10n.closeText,
			currentText: timepicker_objectL10n.currentText,
			prevText: timepicker_objectL10n.prevText,
			nextText: timepicker_objectL10n.nextText,
			monthNames: timepicker_objectL10n.monthNames,
			monthNamesShort: timepicker_objectL10n.monthNamesShort,
			dayNames: timepicker_objectL10n.dayNames,
			dayNamesShort: timepicker_objectL10n.dayNamesShort,
			dayNamesMin: timepicker_objectL10n.dayNamesMin,
			weekHeader: timepicker_objectL10n.weekHeader,
			firstDay: timepicker_objectL10n.firstDay,
			isRTL: timepicker_objectL10n.isRTL,			
			timeText:   timepicker_objectL10n.timeText,
			hourText:   timepicker_objectL10n.hourText,
			minuteText: timepicker_objectL10n.minuteText,
			secondText: timepicker_objectL10n.secondText,
			millisecText: timepicker_objectL10n.millisecText,
			timezoneText: timepicker_objectL10n.timezoneText
		});
	});
});

