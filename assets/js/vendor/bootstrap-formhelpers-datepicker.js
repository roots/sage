/* ==========================================================
 * bootstrap-formhelpers-datepicker.js
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


 /* BFHDATEPICKER CLASS DEFINITION
  * ========================= */

  var toggle = '[data-toggle=bfh-datepicker]'
    , BFHDatePicker = function (element, options) {
        this.options = $.extend({}, $.fn.bfhdatepicker.defaults, options)
    	this.$element = $(element)
        this.initCalendar()
      }

  BFHDatePicker.prototype = {

    constructor: BFHDatePicker

  , daysInMonth: function(month, year) {
    return new Date(year, month, 0).getDate()
  }
  
  , dayOfWeek: function(month, year, day) {
  	return new Date(year, month, day).getDay()
  }
  
  , formatDate: function(month, year, day) {
    var date = this.options.format
    month += 1
    month = new String(month)
    day = new String(day)
    
    if (month.length == 1) {
      month = "0" + month
    }
    if (day.length == 1) {
      day = "0" + day
    }
    date = date.replace("m", month)
    date = date.replace("y", year)
    date = date.replace("d", day)
    
    return date
  }
  
  , parse: function(element, date) {
    var format = this.options.format
    
    var monthPos = format.indexOf("m")
    var yearPos = format.indexOf("y")
    var dayPos = format.indexOf("d")
    
    var indexes = [
      {"type": "m", "pos": monthPos},
      {"type": "y", "pos": yearPos},
      {"type": "d", "pos": dayPos}
    ]
    
    indexes.sort(function(a, b) {return a.pos - b.pos})
    
    var parts = date.match(/(\d+)/g)
    
    for (var i=0; i < indexes.length; i++) {
      if (indexes[i]['type'] == element) {
        return new Number(parts[i]).toString()
      }
    }
  }
  
  , initCalendar: function() {
    var date = this.options.date
    
    if (date == "") {
      var today = new Date()
    
      this.$element.data('month', today.getMonth())
      this.$element.data('year', today.getFullYear())
    } else {
      this.$element.find('input[type=text]').val(date)
      this.$element.data('month', this.parse("m", date) - 1)
      this.$element.data('year', this.parse("y", date))
    }
    
    this.updateCalendar()
  }
  
  , updateCalendar: function () {
    var $calendar
      , today
      , month
      , year
      , $daysHeader
      , $days
    
    var today = new Date()
    month = this.$element.data('month')
    year = this.$element.data('year')
    
    $calendar = this.$element.find('.bfh-datepicker-calendar')
    
    $calendar.find('table > thead > tr > th.month > span').text(BFHMonthsList[month])
    $calendar.find('table > thead > tr > th.year > span').text(year)
    $daysHeader = $calendar.find('table > thead > tr.days-header')
    $daysHeader.html('')
    for (var i=0; i < BFHDaysList.length; i++) {
      $daysHeader.append('<th>' + BFHDaysList[i] + '</th>')
    }
    $days = $calendar.find('table > tbody')
    $days.html('')
    var numDaysPrevious = this.daysInMonth(month, year)
    var numDays = this.daysInMonth(month + 1, year)
    var firstDay = this.dayOfWeek(month, year, 1)
    var lastDay = this.dayOfWeek(month, year, numDays)
    var row = ''
    for (var i=0; i < firstDay; i++) {
      if (i == 0) {
        row += '<tr>'
      }
      row += '<td class="off">' + (numDays - firstDay + i) + '</td>'
      if (i == 6) {
        row += '</tr>'
        $days.append(row)
        row = ''
      }
    }
    for (var i=1; i <= numDays; i++) {
      var day = this.dayOfWeek(month, year, i)
      if (day == 0) {
        row += '<tr>'
      }
      if (i == today.getDate() && month == today.getMonth() && year == today.getFullYear()) {
        row += '<td data-day="' + i + '" class="today">' + i + '</td>'
      } else {
        row += '<td data-day="' + i + '">' + i + '</td>'
      }
      if (day == 6) {
        row += '</tr>'
        $days.append(row)
        row = ''
      }
    }
    for (var i=lastDay+1, j=1; i <= 6; i++, j++) {
      if (i == 0) {
        row += '<tr>'
      }
      row += '<td class="off">' + j + '</td>'
      if (i == 6) {
        row += '</tr>'
        $days.append(row)
        row = ''
      }
    }
  }
  
  , previousMonth: function (e) {
    var $this = $(this)
      , $parent
      , $datePicker
      
    $parent = $this.closest('.bfh-datepicker')
    
    if ($parent.data('month') == 0) {
    	$parent.data('month', 11)
    	$parent.data('year', new Number($parent.data('year')) - 1)
    } else {
    	$parent.data('month', new Number($parent.data('month')) - 1)
    }
    
    $datePicker = $parent.data('bfhdatepicker')
    $datePicker.updateCalendar()
    
    return false;
  }
  
  , nextMonth: function (e) {
    var $this = $(this)
      , $parent
      , $datePicker
      
    $parent = $this.closest('.bfh-datepicker')
    
    if ($parent.data('month') == 11) {
    	$parent.data('month', 0)
    	$parent.data('year', new Number($parent.data('year')) + 1)
    } else {
    	$parent.data('month', new Number($parent.data('month')) + 1)
    }
    
    $datePicker = $parent.data('bfhdatepicker')
    $datePicker.updateCalendar()
    
    return false;
  }
  
  , previousYear: function (e) {
    var $this = $(this)
      , $parent
      , $datePicker
      
    $parent = $this.closest('.bfh-datepicker')
    
    $parent.data('year', new Number($parent.data('year')) - 1)
    
    $datePicker = $parent.data('bfhdatepicker')
    $datePicker.updateCalendar()
    
    return false;
  }
  
  , nextYear: function (e) {
    var $this = $(this)
      , $parent
      , $datePicker
      
    $parent = $this.closest('.bfh-datepicker')
    
    $parent.data('year', new Number($parent.data('year')) + 1)
    
    $datePicker = $parent.data('bfhdatepicker')
    $datePicker.updateCalendar()
    
    return false;
  }
  
  , select: function (e) {
    var $this = $(this)
    , $parent
    , $datePicker
    
    $parent = $this.closest('.bfh-datepicker')
    
    var month = $parent.data('month')
    var year = $parent.data('year')
    var day = $this.data('day')
    
    $datePicker = $parent.data('bfhdatepicker')
    
    $parent.find('input[type=text]').val($datePicker.formatDate(month, year, day)).trigger('change')
    
    return false;
  }
  
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
      }

      return false
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


  /* DATEPICKER PLUGIN DEFINITION
   * ========================== */

  $.fn.bfhdatepicker = function (option) {
    return this.each(function () {
      var $this = $(this)
        , data = $this.data('bfhdatepicker')
        , options = typeof option == 'object' && option
        
      if (!data) $this.data('bfhdatepicker', (data = new BFHDatePicker(this, options)))
      if (typeof option == 'string') data[option]()
    })
  }

  $.fn.bfhdatepicker.Constructor = BFHDatePicker

  $.fn.bfhdatepicker.defaults = {
    format: "m/d/y",
    date: ""
  }
  
  /* APPLY TO STANDARD DATEPICKER ELEMENTS
   * =================================== */

  $(window).on('load', function () {
    $('div.bfh-datepicker').each(function () {
      var $datepicker = $(this)

      $datepicker.bfhdatepicker($datepicker.data())
    })
  })
  
  $(function () {
    $('html')
      .on('click.bfhdatepicker.data-api', clearMenus)
    $('body')
      .on('click.bfhdatepicker.data-api touchstart.bfhdatepicker.data-api', toggle, BFHDatePicker.prototype.toggle)
      .on('click.bfhdatepicker.data-api touchstart.bfhdatepicker.data-api', '.bfh-datepicker-calendar > table.calendar .month > .previous', BFHDatePicker.prototype.previousMonth)
      .on('click.bfhdatepicker.data-api touchstart.bfhdatepicker.data-api', '.bfh-datepicker-calendar > table.calendar .month > .next', BFHDatePicker.prototype.nextMonth)
      .on('click.bfhdatepicker.data-api touchstart.bfhdatepicker.data-api', '.bfh-datepicker-calendar > table.calendar .year > .previous', BFHDatePicker.prototype.previousYear)
      .on('click.bfhdatepicker.data-api touchstart.bfhdatepicker.data-api', '.bfh-datepicker-calendar > table.calendar .year > .next', BFHDatePicker.prototype.nextYear)
      .on('click.bfhdatepicker.data-api touchstart.bfhdatepicker.data-api', '.bfh-datepicker-calendar > table.calendar td:not(.off)', BFHDatePicker.prototype.select)
      .on('click.bfhdatepicker.data-api touchstart.bfhdatepicker.data-api', '.bfh-datepicker-calendar > table.calendar', function() { return false })
  })

}(window.jQuery);