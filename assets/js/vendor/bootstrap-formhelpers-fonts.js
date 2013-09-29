/* ==========================================================
 * bootstrap-formhelpers-fonts.js
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


 /* FONTS CLASS DEFINITION
  * ====================== */

  var BFHFonts = function (element, options) {
    this.options = $.extend({}, $.fn.bfhfonts.defaults, options)
    this.$element = $(element)

    this.familyList = BFHFontsList
    
    if (this.$element.is("select")) {
      this.addFonts()
    }
    
    if (this.$element.is("span")) {
      this.displayFont()
    }
    
    if (this.$element.hasClass("bfh-selectbox")) {
      this.addBootstrapFonts()
    }
  }

  BFHFonts.prototype = {

    constructor: BFHFonts

    , addFonts: function () {
      var value = this.options.family
      
      this.$element.html('')
      for (var f in this.familyList) {
        this.$element.append('<option value="' + f + '">' + f + '</option>')
      }
      
      this.$element.val(value)
    }
    
    , addBootstrapFonts: function() {
      var $input
      , $toggle
      , $options
      
      var value = this.options.family
      
      $input = this.$element.find('input[type="hidden"]')
      $toggle = this.$element.find('.bfh-selectbox-option')
      $options = this.$element.find('[role=option]')
      
      $options.html('')
      for (var f in this.familyList) {
        $options.append('<li><a tabindex="-1" href="#" style=\'font-family: ' + this.familyList[f] + '\' data-option="' + f + '">' + f + '</a></li>')
      }
      
      $toggle.data('option', value)
      
      if (value) {
        $toggle.html(value)
      }
      
      $input.val(value)
    }
    
    , displayFont: function () {
      var value = this.options.family
      
      this.$element.html(value)
    }

  }


 /* FONTS PLUGIN DEFINITION
  * ======================= */

  $.fn.bfhfonts = function (option) {
    return this.each(function () {
      var $this = $(this)
        , data = $this.data('bfhfonts')
        , options = typeof option == 'object' && option
      this.type = 'bfhfonts';
      if (!data) $this.data('bfhfonts', (data = new BFHFonts(this, options)))
      if (typeof option == 'string') data[option]()
    })
  }

  $.fn.bfhfonts.Constructor = BFHFonts

  $.fn.bfhfonts.defaults = {
    family: "Arial"
  }
  

 /* FONTS DATA-API
  * ============== */

  $(window).on('load', function () {
    $('form select.bfh-fonts, span.bfh-fonts, div.bfh-fonts').each(function () {
      var $fonts = $(this)

      $fonts.bfhfonts($fonts.data())
    })
  })


}(window.jQuery);
