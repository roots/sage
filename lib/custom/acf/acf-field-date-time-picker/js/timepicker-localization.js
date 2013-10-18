/*
Attach a jQuery.datetimepicker() to "input[type=text].time_picker" fields. Will also attach to dynamic added fields.
*/
jQuery(function() {
	jQuery("body").on("focusin", "input[type=text].time_picker",  function(){
		self = jQuery(this);
		self.datetimepicker({
			closeText: timepicker_objectL10n.closeText,
			currentText: timepicker_objectL10n.currentText,
			prevText: timepicker_objectL10n.prevText,
			nextText: timepicker_objectL10n.nextText,
			weekHeader: timepicker_objectL10n.weekHeader,
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