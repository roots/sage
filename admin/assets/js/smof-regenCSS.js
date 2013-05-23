jQuery.noConflict();

/** Fire up jQuery - let's dance!
 */
jQuery(document).ready(function($){

	// Used to denote LESS changes
	$('body').append('<input type="hidden" id="lessRegen" value="" />');

	// Runs when any value denoted as LESS is changed
	$('.lessDirty').live('change', function() {
		console.log('Value changed, dirty!');
		$('#lessRegen').val('dirty');
	});

	function reGenAjax() {
		if ($('#lessRegen').val() == "dirty") {
		    $.ajax({
		        url:        regenCSSAjax.adminUrl+'admin-ajax.php',
		        type:       'post',
		        dataType:   'json',
		        cache:      false,
		        data:       { "action":"shoestrapCSSRegen", "a":"saveClicked", "nonce": regenCSSAjax.nonce, "data": "true" },
		        beforeSend: function(){
		            // Show regen gui
		        },
		        success:    function(obj){
		            if( obj.response == 'success' ) {
		                console.log('success');
		                $('#lessRegen').val('');
		            }
		            else if( obj.response == 'failed' ) {
		                // failed
		            }
		        }
		    });
	    }
	}

	// Compile the CSS in the background since wordpress doesn't have a proper hook!
    $('#save, #of_save').live('click', function() {
    	setTimeout(reGenAjax, 3000);
    });


});