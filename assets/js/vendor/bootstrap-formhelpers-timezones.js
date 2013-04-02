/* ==========================================================
 * bootstrap-formhelpers-timezones.js
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


 /* TIMEZONES CLASS DEFINITION
  * ====================== */

  var BFHTimezones = function (element, options) {
    this.options = $.extend({}, $.fn.bfhtimezones.defaults, options)
    this.$element = $(element)
    
    if (this.$element.is("select")) {
      this.addTimezones()
    }
    
    if (this.$element.hasClass("bfh-selectbox")) {
      this.addBootstrapTimezones()
    }
  }

  BFHTimezones.prototype = {

    constructor: BFHTimezones

    , addTimezones: function () {
      var country = this.options.country
      
      if (country != "") {
		var formObject = this.$element.closest('form')
		var countryObject = formObject.find('#' + country)
		
		if (countryObject.length != 0) {
		  country = countryObject.val()
		  countryObject.on('change.bfhcountries.data-api', {timezoneObject: this}, this.changeCountry)
		}
	  }
      
      this.loadTimezones(country)
    }
    
    , loadTimezones: function (country) {
      var value = this.options.timezone
      
      this.$element.html('')
      this.$element.append('<option value=""></option>')
      for (var timezone in BFHTimezonesList[country]) {
        this.$element.append('<option value="' + timezone + '">' + BFHTimezonesList[country][timezone] + '</option>')
      }
      
      this.$element.val(value)
    }
    
    , changeCountry: function (e) {
        var $this = $(this)
        var timezoneObject = e.data.timezoneObject
        var country = $this.val()
        
        timezoneObject.loadTimezones(country)
    }
    
    , addBootstrapTimezones: function() {
      var country = this.options.country
      
      if (country != "") {
        var formObject = this.$element.closest('form')
        var countryObject = formObject.find('#' + country)
        
        if (countryObject.length != 0) {
          country = countryObject.find('input[type="hidden"]').val()
          countryObject.find('input[type="hidden"]').on('change.bfhcountries.data-api', {timezoneObject: this}, this.changeBootstrapCountry)
        }
      }
      
      this.loadBootstrapTimezones(country)
    }
    
    , loadBootstrapTimezones: function(country) {
      var $input
      , $toggle
      , $options
      
      var value = this.options.timezone
      
      $input = this.$element.find('input[type="hidden"]')
      $toggle = this.$element.find('.bfh-selectbox-option')
      $options = this.$element.find('[role=option]')
      
      $options.html('')
      $options.append('<li><a tabindex="-1" href="#" data-option=""></a></li>')
      for (var timezone in BFHTimezonesList[country]) {
        $options.append('<li><a tabindex="-1" href="#" data-option="' + timezone + '">' + BFHTimezonesList[country][timezone] + '</a></li>')
      }
      
      $toggle.data('option', value)
      if (typeof BFHTimezonesList[country][value] == "undefined") {
        $toggle.html('')
      } else {
        $toggle.html(value)
      }
      
      $input.val(value)
    }
    
    , changeBootstrapCountry: function (e) {
        var $this = $(this)
        var timezoneObject = e.data.timezoneObject
        var country = $this.val()
        
        timezoneObject.loadBootstrapTimezones(country)
    }

  }


 /* TIMEZONES PLUGIN DEFINITION
  * ======================= */

  $.fn.bfhtimezones = function (option) {
    return this.each(function () {
      var $this = $(this)
        , data = $this.data('bfhtimezones')
        , options = typeof option == 'object' && option
        
      if (!data) $this.data('bfhtimezones', (data = new BFHTimezones(this, options)))
      if (typeof option == 'string') data[option]()
    })
  }

  $.fn.bfhtimezones.Constructor = BFHTimezones

  $.fn.bfhtimezones.defaults = {
    country: "",
    timezone: ""
  }
  

 /* TIMEZONES DATA-API
  * ============== */

  $(window).on('load', function () {
    $('form select.bfh-timezones, div.bfh-timezones').each(function () {
      var $timezones = $(this)

      $timezones.bfhtimezones($timezones.data())
    })
  })


}(window.jQuery);