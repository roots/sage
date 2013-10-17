/* ========================================================================
 * Bootstrap: button.js v3.0.0
 * http://twbs.github.com/bootstrap/javascript.html#buttons
 * ========================================================================
 * Copyright 2013 Twitter, Inc.
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
 * ======================================================================== */+function(e){"use strict";var t=function(n,r){this.$element=e(n);this.options=e.extend({},t.DEFAULTS,r)};t.DEFAULTS={loadingText:"loading..."};t.prototype.setState=function(e){var t="disabled",n=this.$element,r=n.is("input")?"val":"html",i=n.data();e+="Text";i.resetText||n.data("resetText",n[r]());n[r](i[e]||this.options[e]);setTimeout(function(){e=="loadingText"?n.addClass(t).attr(t,t):n.removeClass(t).removeAttr(t)},0)};t.prototype.toggle=function(){var e=this.$element.closest('[data-toggle="buttons"]');if(e.length){var t=this.$element.find("input").prop("checked",!this.$element.hasClass("active")).trigger("change");t.prop("type")==="radio"&&e.find(".active").removeClass("active")}this.$element.toggleClass("active")};var n=e.fn.button;e.fn.button=function(n){return this.each(function(){var r=e(this),i=r.data("bs.button"),s=typeof n=="object"&&n;i||r.data("bs.button",i=new t(this,s));n=="toggle"?i.toggle():n&&i.setState(n)})};e.fn.button.Constructor=t;e.fn.button.noConflict=function(){e.fn.button=n;return this};e(document).on("click.bs.button.data-api","[data-toggle^=button]",function(t){var n=e(t.target);n.hasClass("btn")||(n=n.closest(".btn"));n.button("toggle");t.preventDefault()})}(window.jQuery);