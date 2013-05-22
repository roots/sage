jQuery.noConflict();

/** Fire up jQuery - let's dance!
 */
jQuery(document).ready(function($){
	function reGenAjax() {
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
	                // success
	            }
	            else if( obj.response == 'failed' ) {
	                // failed
	            }
	        }
	    });
	}

	$('body').append('<input type="hidden" id="lessRegen" value="0" />');
	// Compile the CSS in the background since wordpress doesn't have a proper hook!
    $('#save, #of_save').live('click', function() {
    	setTimeout(reGenAjax, 1000);
    });
});