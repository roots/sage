/* ========================================================================
 * Bootstrap: scrollspy.js v3.0.0
 * http://twbs.github.com/bootstrap/javascript.html#scrollspy
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
 * ======================================================================== */+function(e){"use strict";function t(n,r){var i,s=e.proxy(this.process,this);this.$element=e(n).is("body")?e(window):e(n);this.$body=e("body");this.$scrollElement=this.$element.on("scroll.bs.scroll-spy.data-api",s);this.options=e.extend({},t.DEFAULTS,r);this.selector=(this.options.target||(i=e(n).attr("href"))&&i.replace(/.*(?=#[^\s]+$)/,"")||"")+" .nav li > a";this.offsets=e([]);this.targets=e([]);this.activeTarget=null;this.refresh();this.process()}t.DEFAULTS={offset:10};t.prototype.refresh=function(){var t=this.$element[0]==window?"offset":"position";this.offsets=e([]);this.targets=e([]);var n=this,r=this.$body.find(this.selector).map(function(){var r=e(this),i=r.data("target")||r.attr("href"),s=/^#\w/.test(i)&&e(i);return s&&s.length&&[[s[t]().top+(!e.isWindow(n.$scrollElement.get(0))&&n.$scrollElement.scrollTop()),i]]||null}).sort(function(e,t){return e[0]-t[0]}).each(function(){n.offsets.push(this[0]);n.targets.push(this[1])})};t.prototype.process=function(){var e=this.$scrollElement.scrollTop()+this.options.offset,t=this.$scrollElement[0].scrollHeight||this.$body[0].scrollHeight,n=t-this.$scrollElement.height(),r=this.offsets,i=this.targets,s=this.activeTarget,o;if(e>=n)return s!=(o=i.last()[0])&&this.activate(o);for(o=r.length;o--;)s!=i[o]&&e>=r[o]&&(!r[o+1]||e<=r[o+1])&&this.activate(i[o])};t.prototype.activate=function(t){this.activeTarget=t;e(this.selector).parents(".active").removeClass("active");var n=this.selector+'[data-target="'+t+'"],'+this.selector+'[href="'+t+'"]',r=e(n).parents("li").addClass("active");r.parent(".dropdown-menu").length&&(r=r.closest("li.dropdown").addClass("active"));r.trigger("activate")};var n=e.fn.scrollspy;e.fn.scrollspy=function(n){return this.each(function(){var r=e(this),i=r.data("bs.scrollspy"),s=typeof n=="object"&&n;i||r.data("bs.scrollspy",i=new t(this,s));typeof n=="string"&&i[n]()})};e.fn.scrollspy.Constructor=t;e.fn.scrollspy.noConflict=function(){e.fn.scrollspy=n;return this};e(window).on("load",function(){e('[data-spy="scroll"]').each(function(){var t=e(this);t.scrollspy(t.data())})})}(window.jQuery);