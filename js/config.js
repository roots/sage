var roots={};roots.check={};jQuery(function($){
  // Add a function that checks if brand_hover_glow is enabled, and takes the appropriate action based on this (either hiding the advanced options below it or showing them)
  window.roots.check.brand_hover_glow=function(){if ($('#brand_hover_glow').val()=='y' & $('#brand_hover_glow_options').is(':hidden'))$('#brand_hover_glow_options').slideDown('slow');else if($('#brand_hover_glow').val()=='n' & $('#brand_hover_glow_options').is(':visible'))$('#brand_hover_glow_options').slideUp('slow');};
  // Bind the above function to the change event of the brand_hover_glow (so that when the user makes a choice we run the check defined in the above line)
  $('#brand_hover_glow').bind('change',roots.check.brand_hover_glow);
  // Run this check once the page loads
  roots.check.brand_hover_glow();
  // Indent advanced options
  $('.advanced').each(function(){$(this).find('label').each(function(){$(this).html('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+$(this).html())})})
}(jQuery))