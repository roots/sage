/* ==========================================================
 * bootstrap-formhelpers-fontsizes.js
 * https://github.com/vlamanna/BootstrapFormHelpers
 * ==========================================================
 * Copyright 2012 Vincent Lamanna
 * contributed by Aaron Collegeman, Squidoo, 2012
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


 /* FONTSIZES CLASS DEFINITION
  * ====================== */

  var BFHFontSizes = function (element, options) {
    this.options = $.extend({}, $.fn.bfhfontsizes.defaults, options)
    this.$element = $(element)

    this.fontSizesList = BFHFontSizesList
    
    if (this.$element.is("select")) {
      this.addFontSizes()
    }
    
    if (this.$element.is("span")) {
      this.displayFont()
    }
    
    if (this.$element.hasClass("bfh-selectbox")) {
      this.addBootstrapFontSizes()
    }
  }

  BFHFontSizes.prototype = {

    constructor: BFHFontSizes

    , addFontSizes: function () {
      var value = this.options.size
      
      this.$element.html('')
      for (var s in this.fontSizesList) {
        this.$element.append('<option value="' + s + '">' + this.fontSizesList[s] + '</option>')
      }
      
      this.$element.val(value)
    }
    
    , addBootstrapFontSizes: function() {
      var $input
      , $toggle
      , $options
      
      var value = this.options.size
      
      $input = this.$element.find('input[type="hidden"]')
      $toggle = this.$element.find('.bfh-selectbox-option')
      $options = this.$element.find('[role=option]')
      
      $options.html('')
      for (var s in this.fontSizesList) {
        $options.append('<li><a tabindex="-1" href="#" data-option="' + s + '">' + this.fontSizesList[s] + '</a></li>')
      }
      
      $toggle.data('option', value)
      
      if (value) {
        $toggle.html(this.fontSizesList[value])
      }
      
      $input.val(value)
    }
    
    , displayFont: function () {
      var value = this.options.family
      
      this.$element.html(value)
    }

  }


 /* FONTSIZES PLUGIN DEFINITION
  * ======================= */

  $.fn.bfhfontsizes = function (option) {
    return this.each(function () {
      var $this = $(this)
        , data = $this.data('bfhfontsizes')
        , options = typeof option == 'object' && option
      this.type = 'bfhfontsizes';
      if (!data) $this.data('bfhfontsizes', (data = new BFHFontSizes(this, options)))
      if (typeof option == 'string') data[option]()
    })
  }

  $.fn.bfhfontsizes.Constructor = BFHFontSizes

  $.fn.bfhfontsizes.defaults = {
    size: '12'
  }
  

 /* FONTSIZES DATA-API
  * ============== */

  $(window).on('load', function () {
    $('form select.bfh-fontsizes, span.bfh-fontsizes, div.bfh-fontsizes').each(function () {
      var $fontsizes = $(this)

      $fontsizes.bfhfontsizes($fontsizes.data())
    })
  })


}(window.jQuery);
