/* ========================================================================
 * Bootstrap: popover.js v3.0.0
 * http://twbs.github.com/bootstrap/javascript.html#popovers
 * ========================================================================
 * Copyright 2012 Twitter, Inc.
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
 * ======================================================================== */+function(e){"use strict";var t=function(e,t){this.init("popover",e,t)};if(!e.fn.tooltip)throw new Error("Popover requires tooltip.js");t.DEFAULTS=e.extend({},e.fn.tooltip.Constructor.DEFAULTS,{placement:"right",trigger:"click",content:"",template:'<div class="popover"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>'});t.prototype=e.extend({},e.fn.tooltip.Constructor.prototype);t.prototype.constructor=t;t.prototype.getDefaults=function(){return t.DEFAULTS};t.prototype.setContent=function(){var e=this.tip(),t=this.getTitle(),n=this.getContent();e.find(".popover-title")[this.options.html?"html":"text"](t);e.find(".popover-content")[this.options.html?"html":"text"](n);e.removeClass("fade top bottom left right in");e.find(".popover-title").html()||e.find(".popover-title").hide()};t.prototype.hasContent=function(){return this.getTitle()||this.getContent()};t.prototype.getContent=function(){var e=this.$element,t=this.options;return e.attr("data-content")||(typeof t.content=="function"?t.content.call(e[0]):t.content)};t.prototype.arrow=function(){return this.$arrow=this.$arrow||this.tip().find(".arrow")};t.prototype.tip=function(){this.$tip||(this.$tip=e(this.options.template));return this.$tip};var n=e.fn.popover;e.fn.popover=function(n){return this.each(function(){var r=e(this),i=r.data("bs.popover"),s=typeof n=="object"&&n;i||r.data("bs.popover",i=new t(this,s));typeof n=="string"&&i[n]()})};e.fn.popover.Constructor=t;e.fn.popover.noConflict=function(){e.fn.popover=n;return this}}(window.jQuery);