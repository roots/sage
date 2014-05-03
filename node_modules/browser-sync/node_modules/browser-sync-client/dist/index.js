(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);throw new Error("Cannot find module '"+o+"'")}var f=n[o]={exports:{}};t[o][0].call(f.exports,function(e){var n=t[o][1][e];return s(n?n:e)},f,f.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
"use strict";

/**
 * @returns {window}
 */
exports.getWindow = function () {
    return window;
};

/**
 *
 * @returns {HTMLDocument}
 */
exports.getDocument = function () {
    return document;
};

/**
 * @type {{getScrollPosition: getScrollPosition, getScrollSpace: getScrollSpace}}
 */
exports.utils = {
    /**
     * Cross-browser scroll position
     * @returns {{x: number, y: number}}
     */
    getBrowserScrollPosition: function () {

        var $window   = exports.getWindow();
        var $document = exports.getDocument();
        var scrollX;
        var scrollY;
        var dElement = $document.documentElement;
        var dBody    = $document.body;

        if ($window.pageYOffset !== undefined) {
            scrollX = $window.pageXOffset;
            scrollY = $window.pageYOffset;
        } else {
            scrollX = dElement.scrollLeft || dBody.scrollLeft || 0;
            scrollY = dElement.scrollTop || dBody.scrollTop || 0;
        }

        return {
            x: scrollX,
            y: scrollY
        };
    },
    /**
     * @returns {{x: number, y: number}}
     */
    getScrollSpace: function () {
        var $document = exports.getDocument();
        var dElement = $document.documentElement;
        var dBody    = $document.body;
        return {
            x: dBody.scrollHeight - dElement.clientWidth,
            y: dBody.scrollHeight - dElement.clientHeight
        };
    },
    /**
     * @param tagName
     * @param elem
     * @returns {*|number}
     */
    getElementIndex: function (tagName, elem) {
        var allElems = document.getElementsByTagName(tagName);
        return Array.prototype.indexOf.call(allElems, elem);
    },
    /**
     * Force Change event on radio & checkboxes (IE)
     */
    forceChange: function (elem) {
        elem.blur();
        elem.focus();
    },
    /**
     * @param elem
     * @returns {{tagName: (elem.tagName|*), index: *}}
     */
    getElementData: function (elem) {
        var tagName = elem.tagName;
        var index   = exports.utils.getElementIndex(tagName, elem);
        return {
            tagName: tagName,
            index: index
        };
    },
    /**
     * @param {string} tagName
     * @param {number} index
     */
    getSingleElement: function (tagName, index) {
        var elems = document.getElementsByTagName(tagName);
        return elems[index];
    },
    /**
     *
     */
    getBody: function () {
        return document.getElementsByTagName("body")[0];
    }
};
},{}],2:[function(require,module,exports){
if (!("indexOf" in Array.prototype)) {

    Array.prototype.indexOf= function(find, i) {
        if (i === undefined) {
            i = 0;
        }
        if (i < 0) {
            i += this.length;
        }
        if (i < 0) {
            i= 0;
        }
        for (var n = this.length; i < n; i += 1) {
            if (i in this && this[i]===find) {
                return i;
            }
        }
        return -1;
    };
}
},{}],3:[function(require,module,exports){
"use strict";

var options = {

    tagNames: {
        "css":  "link",
        "jpg":  "img",
        "jpeg": "img",
        "png":  "img",
        "svg":  "img",
        "gif":  "img",
        "js":   "script"
    },
    attrs: {
        "link":   "href",
        "img":    "src",
        "script": "src"
    }
};

/**
 * @param {BrowserSync} bs
 */
exports.init = function (bs) {
    bs.socket.on("file:reload", exports.reload(bs));
    bs.socket.on("browser:reload", function () {
        exports.reloadBrowser(true);
    });
};

/**
 * @param elem
 * @param attr
 * @param opts
 * @returns {{elem: HTMLElement, timeStamp: number}}
 */
exports.swapFile = function (elem, attr, opts) {

    var currentValue = elem[attr];
    var timeStamp = new Date().getTime();
    var suffix = "?rel=" + timeStamp;

    var justUrl = /^[^\?]+(?=\?)/.exec(currentValue);

    if (justUrl) {
        currentValue = justUrl[0];
    }

    if (opts) {
        if (!opts.timestamps) {
            suffix = "";
        }
    }

    elem[attr] = currentValue + suffix;

    return {
        elem: elem,
        timeStamp: timeStamp
    };
};

/**
 * @param {BrowserSync} bs
 * @returns {*}
 */
exports.reload = function (bs) {

    /**
     * @param data - from socket
     */
    return function (data) {

        var transformedElem;
        var opts    = bs.opts;
        var emitter = bs.emitter;

        if (data.url || !opts.injectChanges) {
            exports.reloadBrowser(true);
        }

        if (data.assetFileName && data.fileExtension) {

            var domData = exports.getElems(data.fileExtension);
            var elems   = exports.getMatches(domData.elems, data.assetFileName, domData.attr);

            if (elems.length && opts.notify) {
                emitter.emit("notify", {message: "Injected: " + data.assetFileName});
            }

            for (var i = 0, n = elems.length; i < n; i += 1) {
                transformedElem = exports.swapFile(elems[i], domData.attr, opts);
            }
        }

        return transformedElem;
    };
};

/**
 * @param fileExtension
 * @returns {*}
 */
exports.getTagName = function (fileExtension) {
    return options.tagNames[fileExtension];
};

/**
 * @param tagName
 * @returns {*}
 */
exports.getAttr = function (tagName) {
    return options.attrs[tagName];
};

/**
 * @param elems
 * @param url
 * @param attr
 * @returns {Array}
 */
exports.getMatches = function (elems, url, attr) {

    var matches = [];

    for (var i = 0, len = elems.length; i < len; i += 1) {
        if (elems[i][attr].indexOf(url) !== -1) {
            matches.push(elems[i]);
        }
    }

    return matches;
};

/**
 * @param fileExtension
 * @returns {{elems: NodeList, attr: *}}
 */
exports.getElems = function(fileExtension) {

    var tagName = exports.getTagName(fileExtension);
    var attr    = exports.getAttr(tagName);

    return {
        elems: document.getElementsByTagName(tagName),
        attr: attr
    };
};

/**
 * @returns {window}
 */
exports.getWindow = function () {
    return window;
};

/**
 * @param confirm
 */
exports.reloadBrowser = function (confirm) {
    var $window = exports.getWindow();
    if (confirm) {
        $window.location.reload(true);
    }
};
},{}],4:[function(require,module,exports){
"use strict";

exports.events = {};

/**
 * @param name
 * @param data
 */
exports.emit = function (name, data) {
    var event = exports.events[name];
    var listeners;
    if (event && event.listeners) {
        listeners = event.listeners;
        for (var i = 0, n = listeners.length; i < n; i += 1) {
            listeners[i](data);
        }
    }
};

/**
 * @param name
 * @param func
 */
exports.on = function (name, func) {
    var events = exports.events;
    if (!events[name]) {
        events[name] = {
            listeners: [func]
        };
    } else {
        events[name].listeners.push(func);
    }
};
},{}],5:[function(require,module,exports){
exports._ElementCache = function () {

    var cache = {},
        guidCounter = 1,
        expando = "data" + (new Date).getTime();

    this.getData = function (elem) {
        var guid = elem[expando];
        if (!guid) {
            guid = elem[expando] = guidCounter++;
            cache[guid] = {};
        }
        return cache[guid];
    };

    this.removeData = function (elem) {
        var guid = elem[expando];
        if (!guid) return;
        delete cache[guid];
        try {
            delete elem[expando];
        }
        catch (e) {
            if (elem.removeAttribute) {
                elem.removeAttribute(expando);
            }
        }
    };
};

/**
 * Fix an event
 * @param event
 * @returns {*}
 */
exports._fixEvent = function (event) {

    function returnTrue() {
        return true;
    }

    function returnFalse() {
        return false;
    }

    if (!event || !event.stopPropagation) {
        var old = event || window.event;

        // Clone the old object so that we can modify the values
        event = {};

        for (var prop in old) {
            event[prop] = old[prop];
        }

        // The event occurred on this element
        if (!event.target) {
            event.target = event.srcElement || document;
        }

        // Handle which other element the event is related to
        event.relatedTarget = event.fromElement === event.target ?
            event.toElement :
            event.fromElement;

        // Stop the default browser action
        event.preventDefault = function () {
            event.returnValue = false;
            event.isDefaultPrevented = returnTrue;
        };

        event.isDefaultPrevented = returnFalse;

        // Stop the event from bubbling
        event.stopPropagation = function () {
            event.cancelBubble = true;
            event.isPropagationStopped = returnTrue;
        };

        event.isPropagationStopped = returnFalse;

        // Stop the event from bubbling and executing other handlers
        event.stopImmediatePropagation = function () {
            this.isImmediatePropagationStopped = returnTrue;
            this.stopPropagation();
        };

        event.isImmediatePropagationStopped = returnFalse;

        // Handle mouse position
        if (event.clientX != null) {
            var doc = document.documentElement, body = document.body;

            event.pageX = event.clientX +
            (doc && doc.scrollLeft || body && body.scrollLeft || 0) -
            (doc && doc.clientLeft || body && body.clientLeft || 0);
            event.pageY = event.clientY +
            (doc && doc.scrollTop || body && body.scrollTop || 0) -
            (doc && doc.clientTop || body && body.clientTop || 0);
        }

        // Handle key presses
        event.which = event.charCode || event.keyCode;

        // Fix button for mouse clicks:
        // 0 == left; 1 == middle; 2 == right
        if (event.button != null) {
            event.button = (event.button & 1 ? 0 :
                (event.button & 4 ? 1 :
                    (event.button & 2 ? 2 : 0)));
        }
    }

    return event;
};

/**
 * @constructor
 */
exports._EventManager = function (cache) {

    var nextGuid = 1;

    this.addEvent = function (elem, type, fn) {

        var data = cache.getData(elem);

        if (!data.handlers) data.handlers = {};

        if (!data.handlers[type])
            data.handlers[type] = [];

        if (!fn.guid) fn.guid = nextGuid++;

        data.handlers[type].push(fn);

        if (!data.dispatcher) {
            data.disabled = false;
            data.dispatcher = function (event) {

                if (data.disabled) return;
                event = exports._fixEvent(event);

                var handlers = data.handlers[event.type];
                if (handlers) {
                    for (var n = 0; n < handlers.length; n++) {
                        handlers[n].call(elem, event);
                    }
                }
            };
        }

        if (data.handlers[type].length == 1) {
            if (document.addEventListener) {
                elem.addEventListener(type, data.dispatcher, false);
            }
            else if (document.attachEvent) {
                elem.attachEvent("on" + type, data.dispatcher);
            }
        }

    };

    function tidyUp(elem, type) {

        function isEmpty(object) {
            for (var prop in object) {
                return false;
            }
            return true;
        }

        var data = cache.getData(elem);

        if (data.handlers[type].length === 0) {

            delete data.handlers[type];

            if (document.removeEventListener) {
                elem.removeEventListener(type, data.dispatcher, false);
            }
            else if (document.detachEvent) {
                elem.detachEvent("on" + type, data.dispatcher);
            }
        }

        if (isEmpty(data.handlers)) {
            delete data.handlers;
            delete data.dispatcher;
        }

        if (isEmpty(data)) {
            cache.removeData(elem);
        }
    }

    this.removeEvent = function (elem, type, fn) {

        var data = cache.getData(elem);

        if (!data.handlers) return;

        var removeType = function (t) {
            data.handlers[t] = [];
            tidyUp(elem, t);
        };

        if (!type) {
            for (var t in data.handlers) removeType(t);
            return;
        }

        var handlers = data.handlers[type];
        if (!handlers) return;

        if (!fn) {
            removeType(type);
            return;
        }

        if (fn.guid) {
            for (var n = 0; n < handlers.length; n++) {
                if (handlers[n].guid === fn.guid) {
                    handlers.splice(n--, 1);
                }
            }
        }
        tidyUp(elem, type);

    };

    this.proxy = function (context, fn) {
        if (!fn.guid) {
            fn.guid = nextGuid++;
        }
        var ret = function () {
            return fn.apply(context, arguments);
        };
        ret.guid = fn.guid;
        return ret;
    };
};



/**
 * Trigger a click on an element
 * @param elem
 */
exports.triggerClick = function (elem) {

    var evObj;

    if (document.createEvent) {

        evObj = document.createEvent("MouseEvents");
        evObj.initEvent("click", true, true);
        elem.dispatchEvent(evObj);

    } else {

        if (document.createEventObject) {
            evObj = document.createEventObject();
            evObj.cancelBubble = true;
            elem.fireEvent("on" + "click", evObj);
        }
    }
};

var cache = new exports._ElementCache();
var eventManager = new exports._EventManager(cache);

eventManager.triggerClick = exports.triggerClick;

exports.manager = eventManager;




},{}],6:[function(require,module,exports){
"use strict";

var socket    = require("./socket");
var shims     = require("./client-shims");
var notify    = require("./notify");
var codeSync  = require("./code-sync");
var ghostMode = require("./ghostmode");
var emitter   = require("./emitter");
var utils     = require("./browser.utils");

/**
 * @constructor
 */
var BrowserSync = function () {
    this.socket  = socket;
    this.emitter = emitter;
    this.utils   = utils.utils;
};

/**
 * Helper to check if syncing is allowed
 * @param data
 * @returns {boolean}
 */
BrowserSync.prototype.canSync = function (data) {
    return data.url === window.location.pathname;
};

var bs;

/**
 * @param opts
 */
exports.init = function (opts) {

    bs      = new BrowserSync();
    bs.opts = opts;

    if (opts.notify) {
        notify.init(bs);
        notify.flash("Connected to BrowserSync :)");
    }

    if (opts.ghostMode) {
        ghostMode.init(bs);
    }

    if (opts.codeSync) {
        codeSync.init(bs);
    }
};

socket.on("connection", exports.init);

/**debug:start**/
if (window.__karma__) {
    window.__bs_scroll__     = require("./ghostmode.scroll");
    window.__bs_clicks__     = require("./ghostmode.clicks");
    window.__bs_location__   = require("./ghostmode.location");
    window.__bs_inputs__     = require("./ghostmode.forms.input");
    window.__bs_toggles__    = require("./ghostmode.forms.toggles");
    window.__bs_submit__     = require("./ghostmode.forms.submit");
    window.__bs_forms__      = require("./ghostmode.forms");
    window.__bs_utils__      = require("./browser.utils");
    window.__bs_emitter__    = emitter;
    window.__bs_notify__     = notify;
    window.__bs_code_sync__  = codeSync;
    window.__bs_ghost_mode__ = ghostMode;
    window.__bs_socket__     = socket;
    window.__bs_index__      = exports;
}
/**debug:end**/
},{"./browser.utils":1,"./client-shims":2,"./code-sync":3,"./emitter":4,"./ghostmode":12,"./ghostmode.clicks":7,"./ghostmode.forms":9,"./ghostmode.forms.input":8,"./ghostmode.forms.submit":10,"./ghostmode.forms.toggles":11,"./ghostmode.location":13,"./ghostmode.scroll":14,"./notify":15,"./socket":16}],7:[function(require,module,exports){
"use strict";

/**
 * This is the plugin for syncing clicks between browsers
 * @type {string}
 */
var EVENT_NAME  = "click";
exports.canEmitEvents = true;

/**
 * @param {BrowserSync} bs
 * @param eventManager
 */
exports.init = function (bs, eventManager) {
    eventManager.addEvent(document.body, EVENT_NAME, exports.browserEvent(bs));
    bs.socket.on(EVENT_NAME, exports.socketEvent(bs, eventManager));
};

/**
 * Uses event delegation to determine the clicked element
 * @param {BrowserSync} bs
 * @returns {Function}
 */
exports.browserEvent = function (bs) {

    return function (event) {

        if (exports.canEmitEvents) {

            var elem = event.target || event.srcElement;

            if (elem.type === "checkbox" || elem.type === "radio") {
                bs.utils.forceChange(elem);
                return;
            }

            bs.socket.emit(EVENT_NAME, bs.utils.getElementData(elem));

        } else {
            exports.canEmitEvents = true;
        }
    };
};

/**
 * @param {BrowserSync} bs
 * @param {manager} eventManager
 * @returns {Function}
 */
exports.socketEvent = function (bs, eventManager) {

    return function (data) {

        if (bs.canSync(data)) {

            var elem = bs.utils.getSingleElement(data.tagName, data.index);

            if (elem) {
                exports.canEmitEvents = false;
                eventManager.triggerClick(elem);
            }
        }
    };
};
},{}],8:[function(require,module,exports){
"use strict";

/**
 * This is the plugin for syncing clicks between browsers
 * @type {string}
 */
var EVENT_NAME  = "input:text";
exports.canEmitEvents = true;

/**
 * @param {BrowserSync} bs
 * @param eventManager
 */
exports.init = function (bs, eventManager) {
    eventManager.addEvent(document.body, "keyup", exports.browserEvent(bs));
    bs.socket.on(EVENT_NAME, exports.socketEvent(bs, eventManager));
};

/**
 * @param {BrowserSync} bs
 * @returns {Function}
 */
exports.browserEvent = function (bs) {

    return function (event) {

        var elem = event.target || event.srcElement;
        var data;

        if (exports.canEmitEvents) {

            if (elem.tagName === "INPUT" || elem.tagName === "TEXTAREA") {

                data = bs.utils.getElementData(elem);
                data.value = elem.value;

                bs.socket.emit(EVENT_NAME, data);
            }

        } else {
            exports.canEmitEvents = true;
        }
    };
};

/**
 * @param {BrowserSync} bs
 * @returns {Function}
 */
exports.socketEvent = function (bs) {

    return function (data) {

        if (bs.canSync(data)) {

            var elem = bs.utils.getSingleElement(data.tagName, data.index);

            if (elem) {
                elem.value = data.value;
                return elem;
            }
        }

        return false;
    };
};
},{}],9:[function(require,module,exports){
"use strict";

exports.plugins = {
    "inputs":  require("./ghostmode.forms.input"),
    "toggles": require("./ghostmode.forms.toggles"),
    "submit":  require("./ghostmode.forms.submit")
};

/**
 * Load plugins for enabled options
 * @param bs
 */
exports.init = function (bs, eventManager) {

    var checkOpt = true;
    var opts = bs.opts.ghostMode.forms;

    if (opts === true) {
        checkOpt = false;
    }

    function init(name) {
        exports.plugins[name].init(bs, eventManager);
    }

    for (var name in exports.plugins) {
        if (!checkOpt) {
            init(name);
        } else {
            if (opts[name]) {
                init(name);
            }
        }
    }
};
},{"./ghostmode.forms.input":8,"./ghostmode.forms.submit":10,"./ghostmode.forms.toggles":11}],10:[function(require,module,exports){
"use strict";

/**
 * This is the plugin for syncing clicks between browsers
 * @type {string}
 */
var EVENT_NAME  = "form:submit";
exports.canEmitEvents = true;

/**
 * @param {BrowserSync} bs
 * @param eventManager
 */
exports.init = function (bs, eventManager) {
    var browserEvent = exports.browserEvent(bs);
    eventManager.addEvent(document.body, "submit", browserEvent);
    eventManager.addEvent(document.body, "reset", browserEvent);
    bs.socket.on(EVENT_NAME, exports.socketEvent(bs, eventManager));
};

/**
 * @param {BrowserSync} bs
 * @returns {Function}
 */
exports.browserEvent = function (bs) {

    return function (event) {
        if (exports.canEmitEvents) {
            var elem = event.target || event.srcElement;
            var data = bs.utils.getElementData(elem);
            data.type = event.type;
            bs.socket.emit(EVENT_NAME, data);
        } else {
            exports.canEmitEvents = true;
        }
    };
};

/**
 * @param {BrowserSync} bs
 * @returns {Function}
 */
exports.socketEvent = function (bs) {

    return function (data) {
        if (bs.canSync(data)) {
            var elem = bs.utils.getSingleElement(data.tagName, data.index);
            exports.canEmitEvents = false;
            if (elem && data.type === "submit") {
                elem.submit();
            }
            if (elem && data.type === "reset") {
                elem.reset();
            }
            return false;
        }
        return false;
    };
};
},{}],11:[function(require,module,exports){
"use strict";

/**
 * This is the plugin for syncing clicks between browsers
 * @type {string}
 */
var EVENT_NAME  = "input:toggles";
exports.canEmitEvents = true;

/**
 * @param {BrowserSync} bs
 * @param eventManager
 */
exports.init = function (bs, eventManager) {
    var browserEvent = exports.browserEvent(bs);
    exports.addEvents(eventManager, browserEvent);
    bs.socket.on(EVENT_NAME, exports.socketEvent(bs, eventManager));
};

/**
 * @param eventManager
 * @param event
 */
exports.addEvents = function (eventManager, event) {

    var elems   = document.getElementsByTagName("select");
    var inputs  = document.getElementsByTagName("input");

    addEvents(elems);
    addEvents(inputs);

    function addEvents(domElems) {
        for (var i = 0, n = domElems.length; i < n; i += 1) {
            eventManager.addEvent(domElems[i], "change", event);
        }
    }
};

/**
 * @param {BrowserSync} bs
 * @returns {Function}
 */
exports.browserEvent = function (bs) {

    return function (event) {

        if (exports.canEmitEvents) {
            var elem = event.target || event.srcElement;
            var data;
            if (elem.type === "radio" || elem.type === "checkbox" || elem.tagName === "SELECT") {
                data = bs.utils.getElementData(elem);
                data.type    = elem.type;
                data.value   = elem.value;
                data.checked = elem.checked;
                bs.socket.emit(EVENT_NAME, data);
            }
        } else {
            exports.canEmitEvents = true;
        }

    };
};

/**
 * @param {BrowserSync} bs
 * @returns {Function}
 */
exports.socketEvent = function (bs) {

    return function (data) {

        if (bs.canSync(data)) {

            exports.canEmitEvents = false;

            var elem = bs.utils.getSingleElement(data.tagName, data.index);

            if (elem) {
                if (data.type === "radio") {
                    elem.checked = true;
                }
                if (data.type === "checkbox") {
                    elem.checked = data.checked;
                }
                if (data.tagName === "SELECT") {
                    elem.value = data.value;
                }
                return elem;
            }
            return false;
        }

        return false;
    };
};
},{}],12:[function(require,module,exports){
"use strict";

var eventManager = require("./events").manager;

exports.plugins = {
    "scroll":   require("./ghostmode.scroll"),
    "clicks":   require("./ghostmode.clicks"),
    "forms":    require("./ghostmode.forms"),
    "location": require("./ghostmode.location")
};

/**
 * Load plugins for enabled options
 * @param bs
 */
exports.init = function (bs) {

    var ghostMode = bs.opts.ghostMode;

    function init(name) {
        exports.plugins[name].init(bs, eventManager);
    }

    for (var name in exports.plugins) {
        if (ghostMode[name]) {
            init(name);
        }
    }
};
},{"./events":5,"./ghostmode.clicks":7,"./ghostmode.forms":9,"./ghostmode.location":13,"./ghostmode.scroll":14}],13:[function(require,module,exports){
"use strict";

/**
 * This is the plugin for syncing location
 * @type {string}
 */
var EVENT_NAME = "location";
exports.canEmitEvents = true;

/**
 * @param {BrowserSync} bs
 */
exports.init = function (bs) {
    bs.socket.on(EVENT_NAME, exports.socketEvent());
};

/**
 * Respond to socket event
 */
exports.socketEvent = function () {
    return function (data) {
        window.location = data.url;
    };
};
},{}],14:[function(require,module,exports){
"use strict";

/**
 * This is the plugin for syncing scroll between devices
 * @type {string}
 */
var EVENT_NAME = "scroll";
var utils;

exports.canEmitEvents = true;

/**
 * @param {BrowserSync} bs
 * @param eventManager
 */
exports.init = function (bs, eventManager) {
    utils = bs.utils;
    eventManager.addEvent(window, EVENT_NAME, exports.browserEvent(bs));
    bs.socket.on(EVENT_NAME, exports.socketEvent(bs));
};

/**
 * @param {BrowserSync} bs
 */
exports.socketEvent = function (bs) {

    return function (data) {

        var scrollSpace = utils.getScrollSpace();

        exports.canEmitEvents = false;

        if (!bs.canSync(data)) {
            return false;
        }

        if (bs.opts && bs.opts.scrollProportionally) {
            return window.scrollTo(0, scrollSpace.y * data.position.proportional); // % of y axis of scroll to px
        } else {
            return window.scrollTo(0, data.position.raw);
        }
    };
};

/**
 * @param socket
 */
exports.browserEvent = function (bs) {

    return function () {

        var canSync = exports.canEmitEvents;

        if (canSync) {
            bs.socket.emit(EVENT_NAME, {
                position: exports.getScrollPosition()
            });
        }

        exports.canEmitEvents = true;
    };
};


/**
 * @returns {{raw: number, proportional: number}}
 */
exports.getScrollPosition = function () {
    var pos = utils.getBrowserScrollPosition();
    return {
        raw: pos, // Get px of y axis of scroll
        proportional: exports.getScrollTopPercentage(pos) // Get % of y axis of scroll
    };
};

/**
 * @param {{x: number, y: number}} scrollSpace
 * @param scrollPosition
 * @returns {{x: number, y: number}}
 */
exports.getScrollPercentage = function (scrollSpace, scrollPosition) {

    var x = scrollPosition.x / scrollSpace.x;
    var y = scrollPosition.y / scrollSpace.y;

    return {
        x: x || 0,
        y: y
    };
};

/**
 * Get just the percentage of Y axis of scroll
 * @returns {number}
 */
exports.getScrollTopPercentage = function (pos) {
    var scrollSpace = utils.getScrollSpace();
    var percentage  = exports.getScrollPercentage(scrollSpace, pos);
    return percentage.y;
};
},{}],15:[function(require,module,exports){
"use strict";

var scroll = require("./ghostmode.scroll");

var styles = [
    "background-color: black",
    "color: white",
    "padding: 10px",
    "display: none",
    "font-family: sans-serif",
    "position: absolute",
    "z-index: 9999",
    "right: 0px",
    "border-bottom-left-radius: 5px"
];

var browserSync;
var elem;
var options;

/**
 * @param {BrowserSync} bs
 * @returns {*}
 */
exports.init = function (bs) {

    browserSync = bs;
    options     = bs.opts;

    var cssStyles = styles;

    if (options.notify.styles) {
        cssStyles = options.notify.styles;
    }

    elem = document.createElement("DIV");
    elem.id = "__bs_notify__";
    elem.style.cssText = cssStyles.join(";");
    document.getElementsByTagName("body")[0].appendChild(elem);

    var flashFn = exports.watchEvent();

    browserSync.emitter.on("notify", flashFn);
    browserSync.socket.on("browser:notify", flashFn);

    return elem;
};

/**
 * @returns {Function}
 */
exports.watchEvent = function() {
    return function (data) {
        exports.flash(data.message);
    };
};

/**
 *
 */
exports.getElem = function () {
    return elem;
};

/**
 * @returns {number|*}
 */
exports.getScrollTop = function () {
    return browserSync.utils.getBrowserScrollPosition().y;
};

/**
 * @param message
 * @param [timeout]
 * @returns {*}
 */
exports.flash = function (message, timeout) {

    var elem = exports.getElem();

    // return if notify was never initialised
    if (!elem) {
        return false;
    }

    var html = document.getElementsByTagName("HTML")[0];
    html.style.position = "relative";

    elem.innerHTML = message;
    elem.style.top = exports.getScrollTop() + "px";
    elem.style.display = "block";

    window.setTimeout(function () {
        elem.style.display = "none";
    }, timeout || 2000);

    return elem;
};
},{"./ghostmode.scroll":14}],16:[function(require,module,exports){
"use strict";

/**
 * @type {{emit: emit, on: on}}
 */
exports.socket = window.___socket___ || {
        emit: function(){},
        on: function(){}
    };

/**
 * @returns {string}
 */
exports.getPath = function () {
    return window.location.pathname;
};
/**
 * Alias for socket.emit
 * @param name
 * @param data
 */
exports.emit = function (name, data) {
    var socket = exports.socket;
    if (socket && socket.emit) {
        // send relative path of where the event is sent
        data.url = exports.getPath();
        socket.emit(name, data);
    }
};

/**
 * Alias for socket.on
 * @param name
 * @param func
 */
exports.on = function (name, func) {
    exports.socket.on(name, func);
};
},{}]},{},[6])