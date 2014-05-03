module.exports = function (opt) {
    // options
    opt = opt || {};
    var ignore = opt.ignore || opt.excludeList || [".js", ".css", ".svg", ".ico", ".woff", ".png", ".jpg", ".jpeg"];
    var html = opt.html || _html;
    var rules = opt.rules || [];

    // helper functions
    var regex = (function() {
        var matches = rules.map(function(item) {
            return item.match.source;
        }).join("|");
        return new RegExp(matches);
    })();

    function _html(str) {
        if (!str) {
            return false;
        }
        return /<[:_-\w\s\!\/\=\"\']+>/i.test(str);
    }

    function exists(body) {
        if (!body) {
            return false;
        }
        return regex.test(body);
    }

    function snip(body) {
        if (!body) {
            return false;
        }
    }

    function snap(body) {

        var _body = body;
        rules.forEach(function(rule) {
            if (rule.match.test(body)) {
                _body = _body.replace(rule.match, function(w) {
                    return rule.fn(w);
                });
                return true;
            }
            return false;
        });
        return _body;
    }

    function accept(req) {
        var ha = req.headers["accept"];
        if (!ha) {
            return false;
        }
        return (~ha.indexOf("html"));
    }

    function leave(req) {
        var url = req.url;
        var ignored = false;
        if (!url) {
            return true;
        }
        ignore.forEach(function(item) {
            if (~url.indexOf(item)) {
                ignored = true;
            }
        });
        return ignored;
    }

    // middleware
    return function (req, res, next) {
        if (res._livereload) {
            return next();
        }
        res._livereload = true;

        var writeHead = res.writeHead;
        var write = res.write;
        var end = res.end;

        if (!accept(req) || leave(req)) {
            return next();
        }

        function restore() {
            res.writeHead = writeHead;
            res.write = write;
            res.end = end;
        }

        res.push = function(chunk) {
            res.data = (res.data || "") + chunk;
        };

        res.inject = res.write = function(string, encoding) {
            if (string !== undefined) {
                var body = string instanceof Buffer ? string.toString(encoding) : string;
                if (exists(body) && !snip(res.data)) {
                    var newString = snap(body);
                    res.push(newString);
                    return true;
                } else if (html(body) || html(res.data)) {
                    res.push(body);
                    return true;
                } else {
                    restore();
                    return write.call(res, string, encoding);
                }
            }
            return true;
        };

        res.writeHead = function() {
            var headers = arguments[arguments.length - 1];
            if (headers && typeof headers === "object") {
                for (var name in headers) {
                    if (/content-length/i.test(name)) {
                        delete headers[name];
                    }
                }
            }

            var header = res.getHeader( "content-length" );
            if ( header ) {
                res.removeHeader( "content-length" );
            }

            writeHead.apply(res, arguments);
        };

        res.end = function(string, encoding) {

            restore();

            var result = res.inject(string, encoding);

            if (!result) {
                return end.call(res, string, encoding);
            }

            if (res.data !== undefined && !res._header) {
                res.setHeader("content-length", Buffer.byteLength(res.data, encoding));
            }

            res.end(res.data, encoding);
        };
        next();
    };
};
