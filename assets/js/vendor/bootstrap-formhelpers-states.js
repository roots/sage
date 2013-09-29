/* ==========================================================
 * bootstrap-formhelpers-states.js
 * https://github.com/vlamanna/BootstrapFormHelpers
 * ==========================================================
 * Copyright 2012 Vincent Lamanna
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ========================================================== */
 
 !function ($) {

  "use strict"; // jshint ;_;


 /* STATES CLASS DEFINITION
  * ====================== */

  var BFHStates = function (element, options) {
    this.options = $.extend({}, $.fn.bfhstates.defaults, options)
    this.$element = $(element)
    
    if (this.$element.is("select")) {
      this.addStates()
    }
    
    if (this.$element.is("span")) {
      this.displayState()
    }
    
    if (this.$element.hasClass("bfh-selectbox")) {
      this.addBootstrapStates()
    }
  }

  BFHStates.prototype = {

    constructor: BFHStates

    , addStates: function () {
      var country = this.options.country
      
      if (country != "") {
		var formObject = this.$element.closest('form')
		var countryObject = formObject.find('#' + country)
		
		if (countryObject.length != 0) {
		  country = countryObject.val()
		  countryObject.on('change.bfhcountries.data-api', {stateObject: this}, this.changeCountry)
		}
	  }
      
      this.loadStates(country)
    }
    
    , loadStates: function (country) {
      var value = this.options.state
      
      this.$element.html('')
      this.$element.append('<option value=""></option>')
      for (var state in BFHStatesList[country]) {
        this.$element.append('<option value="' + BFHStatesList[country][state]['code'] + '">' + BFHStatesList[country][state]['name'] + '</option>')
      }
      
      this.$element.val(value)
    }
    
    , changeCountry: function (e) {
        var $this = $(this)
        var stateObject = e.data.stateObject
        var country = $this.val()
        
        stateObject.loadStates(country)
    }
    
    , addBootstrapStates: function() {
      var country = this.options.country
      
      if (country != "") {
        var formObject = this.$element.closest('form')
        var countryObject = formObject.find('#' + country)
        
        if (countryObject.length != 0) {
          country = countryObject.find('input[type="hidden"]').val()
          countryObject.find('input[type="hidden"]').on('change.bfhcountries.data-api', {stateObject: this}, this.changeBootstrapCountry)
        }
      }
      
      this.loadBootstrapStates(country)
    }
    
    , loadBootstrapStates: function(country) {
      var $input
      , $toggle
      , $options
      
      var value = this.options.state
      
      $input = this.$element.find('input[type="hidden"]')
      $toggle = this.$element.find('.bfh-selectbox-option')
      $options = this.$element.find('[role=option]')
      
      $options.html('')
      $options.append('<li><a tabindex="-1" href="#" data-option=""></a></li>')
      for (var state in BFHStatesList[country]) {
        $options.append('<li><a tabindex="-1" href="#" data-option="' + BFHStatesList[country][state]['code'] + '">' + BFHStatesList[country][state]['name'] + '</a></li>')
      }
      
      $toggle.data('option', value)
      if (typeof BFHStatesList[country][value] == "undefined") {
        $toggle.html('')
      } else {
        $toggle.html(BFHStatesList[country][value])
      }
      
      $input.val(value)
    }
    
    , changeBootstrapCountry: function (e) {
        var $this = $(this)
        var stateObject = e.data.stateObject
        var country = $this.val()
        
        stateObject.loadBootstrapStates(country)
    }
    
    , displayState: function () {
      var country = this.options.country
      var value = this.options.state
      
      this.$element.html(BFHStatesList[country][value])
    }

  }


 /* STATES PLUGIN DEFINITION
  * ======================= */

  $.fn.bfhstates = function (option) {
    return this.each(function () {
      var $this = $(this)
        , data = $this.data('bfhstates')
        , options = typeof option == 'object' && option
        
      if (!data) $this.data('bfhstates', (data = new BFHStates(this, options)))
      if (typeof option == 'string') data[option]()
    })
  }

  $.fn.bfhstates.Constructor = BFHStates

  $.fn.bfhstates.defaults = {
    country: "",
    state: ""
  }
  

 /* STATES DATA-API
  * ============== */

  $(window).on('load', function () {
    $('form select.bfh-states, span.bfh-states, div.bfh-states').each(function () {
      var $states = $(this)

      $states.bfhstates($states.data())
    })
  })


}(window.jQuery);