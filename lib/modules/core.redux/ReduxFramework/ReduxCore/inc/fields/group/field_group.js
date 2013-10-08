/* global redux_change */
(function($){
    "use strict";
    
    $.group = $.group || {};
	
    $(document).ready(function () {
        //Group functionality
        $.group();
    });
    
    $.group = function(){
        $("#redux-groups-accordion")
        .accordion({
            header: "> div > h3",
            collapsible: true,
            active: false,
            heightStyle: "content",
            icons: {
                "header": "ui-icon-plus", 
                "activeHeader": "ui-icon-minus"
            }
        })
        .sortable({
            axis: "y",
            handle: "h3",
            stop: function (event, ui) {
                // IE doesn't register the blur when sorting
                // so trigger focusout handlers to remove .ui-state-focus
                ui.item.children("h3").triggerHandler("focusout");
                var inputs = $('input.slide-sort');
                inputs.each(function(idx) {
                    $(this).val(idx);
                });
            }
        });
        
        $('.slide-title').live('keyup',function(event) {
            $(this).parent().parent().parent().find('.redux-groups-header').text(event.target.value);
        });
        
        $('.redux-groups-remove').live('click', function () {
            redux_change($(this));
            $(this).parent().find('input[type="text"]').val('');
            $(this).parent().find('input[type="hidden"]').val('');
            $(this).parent().parent().slideUp('medium', function () {
                $(this).remove();
            });
        });

        $('.redux-groups-add').click(function () {

            var newSlide = $(this).prev().find('.redux-groups-accordion-group:last').clone(true);
            var slideCount = $(newSlide).find('input[type="text"]').attr("name").match(/\d+(?!.*\d+)/);
            var slideCount1 = slideCount*1 + 1;

            $(newSlide).find('input[type="text"], input[type="hidden"], textarea').each(function(){
                var attr_name = $(this).attr('name');
                var attr_id = $(this).attr('id');
            
                // For some browsers, `attr` is undefined; for others,
                // `attr` is false.  Check for both.
                if (typeof attr_id !== 'undefined' && attr_id !== false) 
                    $(this).attr("id", $(this).attr("id").replace(/\d+(?!.*\d+)/, slideCount1) );
                if (typeof attr_name !== 'undefined' && attr_name !== false) 
                    $(this).attr("name", $(this).attr("name").replace(/\d+(?!.*\d+)/, slideCount1) );

            
                //$(this).attr("name", $(this).attr("name").replace(/\d+/, slideCount1) ).attr("id", $(this).attr("id").replace(/\d+/, slideCount1) );
                $(this).val('');
                if ($(this).hasClass('slide-sort')){
                    $(this).val(slideCount1);
                }
            });

            $(newSlide).find('h3').text('').append('<span class="redux-groups-header">New Group</span><span class="ui-accordion-header-icon ui-icon ui-icon-plus"></span>');
            $(this).prev().append(newSlide);
        });
    }
})(jQuery);