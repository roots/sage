/* ==========================================================
 * bootstrap-formhelpers-selectbox.js
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


 /* SELECTBOX CLASS DEFINITION
  * ========================= */

  var toggle = '[data-toggle=bfh-selectbox]'
    , BFHSelectBox = function (element) {
      }

  BFHSelectBox.prototype = {

    constructor: BFHSelectBox

  , toggle: function (e) {
      var $this = $(this)
        , $parent
        , isActive

      if ($this.is('.disabled, :disabled')) return

      $parent = getParent($this)

      isActive = $parent.hasClass('open')

      clearMenus()

      if (!isActive) {
        $parent.toggleClass('open')
        
        $parent.find('[role=option] > li > [data-option="' + $this.find('.bfh-selectbox-option').data('option') + '"]').focus()
      }

      return false
    }

  , filter: function(e) {
    var $this
      , $parent
      , $items
      
    $this = $(this)
    
    $parent = $this.closest('.bfh-selectbox')
    
    $items = $('[role=option] li a', $parent)
    
    $items.hide()
    
    $items.filter(function() { return ($(this).text().toUpperCase().indexOf($this.val().toUpperCase()) != -1) }).show()
  }
  
  , keydown: function (e) {
      var $this
        , $items
        , $active
        , $parent
        , isActive
        , index

      if (!/(38|40|27)/.test(e.keyCode) && !/[A-z]/.test(String.fromCharCode(e.which))) return

      $this = $(this)

      e.preventDefault()
      e.stopPropagation()

      if ($this.is('.disabled, :disabled')) return

      $parent = $this.closest('.bfh-selectbox')

      isActive = $parent.hasClass('open')

      if (!isActive || (isActive && e.keyCode == 27)) return $this.click()

      $items = $('[role=option] li a', $parent).filter(':visible')

      if (!$items.length) return

      $('body').off('mouseenter.bfh-selectbox.data-api', '[role=option] > li > a', BFHSelectBox.prototype.mouseenter)
      
      index = $items.index($items.filter(':focus'))

      if (e.keyCode == 38 && index > 0) index--                                        // up
      if (e.keyCode == 40 && index < $items.length - 1) index++                        // down
      if (/[A-z]/.test(String.fromCharCode(e.which))) {
      	var $subItems = $items.filter(function() { return ($(this).text().charAt(0).toUpperCase() == String.fromCharCode(e.which)) })
        var selectedIndex = $subItems.index($subItems.filter(':focus'))
        if (!~selectedIndex) index = $items.index($subItems)
        else if (selectedIndex >= $subItems.length - 1) index = $items.index($subItems)
        else index++
      }
      if (!~index) index = 0
      
      $items
        .eq(index)
        .focus()
        
      $('body').on('mouseenter.bfh-selectbox.data-api', '[role=option] > li > a', BFHSelectBox.prototype.mouseenter)
    }
    
    , mouseenter: function (e) {
	  var $this
	  
	  $this = $(this)
	  
	  if ($this.is('.disabled, :disabled')) return
	  
	  $this.focus()
    }
    
    , select: function (e) {
      var $this
        , $parent
        , $toggle
        , $input
      
      $this = $(this)
      
      e.preventDefault()
      e.stopPropagation()
      
      if ($this.is('.disabled, :disabled')) return
      
      $parent = $this.closest('.bfh-selectbox')
      $toggle = $parent.find('.bfh-selectbox-option')
      $input = $parent.find('input[type="hidden"]')
      
      $toggle.data('option', $this.data('option'))
      $toggle.html($this.html())
      
      $input.removeData()
      $input.val($this.data('option'))
      $.each($this.data(), function(i,e) {
        $input.data(i,e);  
      });
      $input.change()
      
      clearMenus()
    }

  }

  function clearMenus() {
    getParent($(toggle))
      .removeClass('open')
  }

  function getParent($this) {
    var selector = $this.attr('data-target')
      , $parent

    if (!selector) {
      selector = $this.attr('href')
      selector = selector && /#/.test(selector) && selector.replace(/.*(?=#[^\s]*$)/, '') //strip for ie7
    }

    $parent = $(selector)
    $parent.length || ($parent = $this.parent())

    return $parent
  }


  /* SELECTBOX PLUGIN DEFINITION
   * ========================== */

  $.fn.bfhselectbox = function (option) {
    return this.each(function () {
      var $this = $(this)
        , data = $this.data('bfhselectbox')
      this.type = 'bfhselectbox';
      if (!data) $this.data('bfhselectbox', (data = new BFHSelectBox(this)))
      if (typeof option == 'string') data[option].call($this)
    })
  }

  $.fn.bfhselectbox.Constructor = BFHSelectBox

  var origHook
  // There might already be valhooks for the "text" type
  if ($.valHooks.div){
    // Preserve the original valhook function
    origHook = $.valHooks.div
  }
  $.valHooks.div = {
    get: function(el) {
      if($(el).hasClass("bfh-selectbox")){
        return $(el).find('input[type="hidden"]').val()
      }else if (origHook){
        return origHook.get(el)
      }
    },
    set: function(el, val) {
      if($(el).hasClass("bfh-selectbox")){
        var $el = $(el)
          , text = $el.find("li a[data-option='"+val+"']").text()
        $el.find('input[type="hidden"]').val(val)

        $el.find('.bfh-selectbox-option').text(text)
      }else if (origHook){
        return origHook.set(el,val)
      }
    }
  }

  /* APPLY TO STANDARD SELECTBOX ELEMENTS
   * =================================== */

  $(function () {
    $('html')
      .on('click.bfhselectbox.data-api', clearMenus)
    $('body')
      .on('click.bfhselectbox.data-api touchstart.bfhselectbox.data-api'  , toggle, BFHSelectBox.prototype.toggle)
      .on('keydown.bfhselectbox.data-api', toggle + ', [role=option]' , BFHSelectBox.prototype.keydown)
      .on('mouseenter.bfhselectbox.data-api', '[role=option] > li > a', BFHSelectBox.prototype.mouseenter)
      .on('click.bfhselectbox.data-api', '[role=option] > li > a', BFHSelectBox.prototype.select)  
      .on('click.bfhselectbox.data-api', '.bfh-selectbox-filter', function (e) { return false })
      .on('propertychange.bfhselectbox.data-api change.bfhselectbox.data-api input.bfhselectbox.data-api paste.bfhselectbox.data-api', '.bfh-selectbox-filter', BFHSelectBox.prototype.filter)
  })

}(window.jQuery);
