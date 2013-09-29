// tablecloth.js
// copyright brian sewell
// https://github.com/bwsewell/tablecloth
//
// v1.0.0
// May 4, 2012 14:20

(function( $ ){
	$.fn.tablecloth = function(options) {
	  
    var defaults = { 
			theme: "default", // "none","default","stats"
			customClass: "",
			bordered: false,
			condensed: false,
			striped: false,
			sortable: false,
			clean: false,
			cleanElements: "*"
	  };
	  
	 	var opts = $.extend(defaults, options);

    // Before we remove any attributes, let's fix a few things up
    this.find("*").each(function() {
      if ($(this).attr("align") == "right") {
        $(this).addClass("right");
      }
      if ($(this).attr("align") == "center") {
        $(this).addClass("center");
      }
      if ($(this).attr("nowrap")) {
        $(this).css('white-space','nowrap');
      }
    });

	 	// Get rid of all inline styling and deprecated table attributes
	 	// Also get rid of any current classes being applied to the <table> element
	 	if (opts.clean) {
	 	  
	 	  this.removeAttr('class')
	 	    .removeAttr('style')
	 	    .removeAttr('cellpadding')
	 	    .removeAttr('cellspacing')
	 	    .removeAttr('bgcolor')
	 	    .removeAttr('align')
	 	    .removeAttr('width')
	 	    .removeAttr('nowrap');
 	      
	 	  this.find(opts.cleanElements).each(function() {
	 	    $(this).removeAttr('style')
  	 	    .removeAttr('cellpadding')
  	 	    .removeAttr('cellspacing')
  	 	    .removeAttr('bgcolor')
  	 	    .removeAttr('align')
  	 	    .removeAttr('width')
  	 	    .removeAttr('nowrap');
	 	  });
	 	  
	 	}
	 	
	 	// Set the table theme accordingly
	 	if (opts.theme == "default") {
	 	  this.addClass("table");
	 	}
	 	else if (opts.theme == "dark") {
	 	  this.addClass("table table-dark");
	 	}
	 	else if (opts.theme == "stats") {
	 	  this.addClass("table table-stats");
	 	}
	 	else if (opts.theme == "paper") {
	 	  this.addClass("table table-paper");
	 	}
	 	
	 	// Set the table theme accordingly
	 	if (opts.customClass != "") {
	 	  this.addClass(opts.customClass);
	 	}
	 	
	 	// Set the table options accordingly
	 	if (opts.condensed) {
	 	  this.addClass("table-condensed");
	 	}
	 	if (opts.bordered) {
	 	  this.addClass("table-bordered");
	 	}
	 	if (opts.striped) {
	 	  this.addClass("table-striped");
	 	}
    if (opts.sortable) {
	 	  this.addClass("table-sortable");
	 	  if (jQuery().tablesorter) {
	 	    this.tablesorter({cssHeader: "headerSortable"});
	 	  }
	 	  else {
	 	    console.log('Tablesorter is not loaded');
	 	  }
	 	}
	 	
  };

})( jQuery );