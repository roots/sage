/* ==========================================================
 * bootstrap-formhelpers-languages.js
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


 /* LANGUAGES CLASS DEFINITION
  * ====================== */

  var BFHLanguages = function (element, options) {
    this.options = $.extend({}, $.fn.bfhlanguages.defaults, options)
    this.$element = $(element)
    
    if (this.$element.is("select")) {
      this.addLanguages()
    }
    
    if (this.$element.is("span")) {
      this.displayLanguage()
    }
    
    if (this.$element.hasClass("bfh-selectbox")) {
      this.addBootstrapLanguages()
    }
  }

  BFHLanguages.prototype = {

    constructor: BFHLanguages

    , addLanguages: function () {
      var value = this.options.language
      var available = this.options.available
      
      this.$element.html('')
      this.$element.append('<option value=""></option>')
      if (available.length > 0) {
        var languages = available.split(",")
        for (var i=0; i < languages.length; i++) {
          var language = languages[i]
          if (language.indexOf("_") != -1) {
            var languageArr = language.split("_")
            this.$element.append('<option value="' + language + '">' + BFHLanguagesList[languageArr[0]].toProperCase() + ' (' + BFHCountriesList[languageArr[1]] + ')</option>')
          } else {
            this.$element.append('<option value="' + language + '">' + BFHLanguagesList[language].toProperCase() + '</option>')
          }
        }
      } else {
		for (var language in BFHLanguagesList) {
		  this.$element.append('<option value="' + language + '">' + BFHLanguagesList[language].toProperCase() + '</option>')
		}
	  }
      
      this.$element.val(value)
    }
    
    , addBootstrapLanguages: function() {
      var $input
      , $toggle
      , $options
      
      var value = this.options.language
      var available = this.options.available
      
      $input = this.$element.find('input[type="hidden"]')
      $toggle = this.$element.find('.bfh-selectbox-option')
      $options = this.$element.find('[role=option]')
      
      $options.html('')
      $options.append('<li><a tabindex="-1" href="#" data-option=""></a></li>')
      if (available.length > 0) {
        var languages = available.split(",")
        for (var i=0; i < languages.length; i++) {
          var language = languages[i]
          if (language.indexOf("_") != -1) {
            var languageArr = language.split("_")
            if (this.options.flags == true) {
              $options.append('<li><a tabindex="-1" href="#" data-option="' + language + '"><i class="icon-flag-' + languageArr[1] + '"></i>' + BFHLanguagesList[languageArr[0]].toProperCase() + '</a></li>')
            } else {
              $options.append('<li><a tabindex="-1" href="#" data-option="' + language + '">' + BFHLanguagesList[languageArr[0]].toProperCase() + ' (' + BFHCountriesList[languageArr[1]] + ')</a></li>')
            }
          } else {
            this.$element.append('<option value="' + language + '">' + BFHLanguagesList[language].toProperCase() + '</option>')
          }
        }
      } else {
		for (var language in BFHLanguagesList) {
		  $options.append('<li><a tabindex="-1" href="#" data-option="' + language + '">' + BFHLanguagesList[language].toProperCase() + '</a></li>')
		}
	  }
      
      $toggle.data('option', value)
      if (value.indexOf("_") != -1) {
        var languageArr = value.split("_")
        if (this.options.flags == true) {
          $toggle.html('<i class="icon-flag-' + languageArr[1] + '"></i> ' + BFHLanguagesList[languageArr[0]])
        } else {
          $toggle.html(BFHLanguagesList[languageArr[0]] + ' (' + BFHCountriesList[languageArr[1]] + ')')
        }
      } else {
        $toggle.html(BFHLanguagesList[value])
      }
      
      $input.val(value)
    }
    
    , displayLanguage: function () {
      var value = this.options.language
      
      if (value.indexOf("_") != -1) {
        var languageArr = value.split("_")
        if (this.options.flags == true) {
          this.$element.html('<i class="icon-flag-' + languageArr[1] + '"></i> ' + BFHLanguagesList[languageArr[0]])
        } else {
          this.$element.html(BFHLanguagesList[languageArr[0]] + ' (' + BFHCountriesList[languageArr[1]] + ')')
        }
      } else {
        this.$element.html(BFHLanguagesList[value])
      }
    }

  }


 /* LANGUAGES PLUGIN DEFINITION
  * ======================= */

  $.fn.bfhlanguages = function (option) {
    return this.each(function () {
      var $this = $(this)
        , data = $this.data('bfhlanguages')
        , options = typeof option == 'object' && option
        
      if (!data) $this.data('bfhlanguages', (data = new BFHLanguages(this, options)))
      if (typeof option == 'string') data[option]()
    })
  }

  $.fn.bfhlanguages.Constructor = BFHLanguages

  $.fn.bfhlanguages.defaults = {
    language: "",
    available: "",
    flags: false
  }
  

 /* LANGUAGES DATA-API
  * ============== */

  $(window).on('load', function () {
    $('form select.bfh-languages, span.bfh-languages, div.bfh-languages').each(function () {
      var $languages = $(this)

      $languages.bfhlanguages($languages.data())
    })
  })
  
  String.prototype.toProperCase = function () {
    return this.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
  };


}(window.jQuery);