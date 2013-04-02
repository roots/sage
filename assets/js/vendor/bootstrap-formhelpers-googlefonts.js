/* ==========================================================
 * bootstrap-formhelpers-googlefonts.js
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

  var BFHGoogleFonts = function (element, options) {
    this.options = $.extend({}, $.fn.bfhgooglefonts.defaults, options)
    this.$element = $(element)
    this.familyList = {}
    
    if (this.options.subsets) {
      this.options.subsets = this.options.subsets.split(',')
      for (var i in BFHGoogleFontsList.items) {
        var font = BFHGoogleFontsList.items[i];
        for (var f = 0, allhave = true; f <= this.options.subsets.length; f++){
            if ($.inArray(this.options.subsets[f], font.subsets) == -1) {allhave = false;}
            if (f == this.options.subsets.length-1 && allhave == true){
              this.familyList[font.family] = {
                'font': BFHGoogleFontsList.items[i],
                'i': parseInt(i)
              };
            }
        }
      }
    } else if (this.options.families) {
      this.options.families = this.options.families.split(',')
      for (var i in BFHGoogleFontsList.items) {
        var font = BFHGoogleFontsList.items[i];
        if ($.inArray(font.family, this.options.families) >= 0) {
          this.familyList[font.family] = {
            'font': BFHGoogleFontsList.items[i],
            'i': parseInt(i)
          };
        }
      }
    } else {
      for (var i in BFHGoogleFontsList.items) {
        var font = BFHGoogleFontsList.items[i];
        this.familyList[font.family] = {
          'font': BFHGoogleFontsList.items[i],
          'i': parseInt(i)
        };
      }
    }

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

  BFHGoogleFonts.prototype = {

    constructor: BFHGoogleFonts

    , addFonts: function () {
      var value = this.options.family
      
      this.$element.html('')
      this.$element.append('<option value=""></option>')
      for (var f in this.familyList) {
        var entry = this.familyList[f];
        this.$element.append('<option value="' + entry.font.family + '">' + entry.font.family + '</option>')
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
      $options.append('<li><a tabindex="-1" href="#" data-option="" style="background-image: none;"></a></li>')
      for (var f in this.familyList) {
        var entry = this.familyList[f];
        $options.append('<li><a tabindex="-1" href="#" style="background-position: 0 -' + ((entry.i * 30) - 2) + 'px;" data-option="' + entry.font.family + '">' + entry.font.family + '</a></li>')
      }
      
      $toggle.data('option', value)
      
      if (value) {
        $toggle.html(this.familyList[value].font.family)
      }
      
      $input.val(value)
    }
    
    , displayFont: function () {
      var value = this.options.family
      
      this.$element.html(this.familyList[value].font.family)
    }

  }


 /* FONTS PLUGIN DEFINITION
  * ======================= */

  $.fn.bfhgooglefonts = function (option) {
    return this.each(function () {
      var $this = $(this)
        , data = $this.data('bfhgooglefonts')
        , options = typeof option == 'object' && option
      this.type = 'bfhgooglefonts';
      if (!data) $this.data('bfhgooglefonts', (data = new BFHGoogleFonts(this, options)))
      if (typeof option == 'string') data[option]()
    })
  }

  $.fn.bfhgooglefonts.Constructor = BFHGoogleFonts

  $.fn.bfhgooglefonts.defaults = {
    family: "",
    families: "",
    subsets: ""
  }
  

 /* FONTS DATA-API
  * ============== */

  $(window).on('load', function () {
    $('form select.bfh-googlefonts, span.bfh-googlefonts, div.bfh-googlefonts').each(function () {
      var $googlefonts = $(this)

      $googlefonts.bfhgooglefonts($googlefonts.data())
    })
  })


}(window.jQuery);
