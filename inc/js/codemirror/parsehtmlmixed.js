var HTMLMixedParser = Editor.Parser = (function() {

  // tags that trigger seperate parsers
  var triggers = {
    "script": "JSParser",
    "style":  "CSSParser"
  };

  function checkDependencies() {
    var parsers = ['XMLParser'];
    for (var p in triggers) parsers.push(triggers[p]);
    for (var i in parsers) {
      if (!window[parsers[i]]) throw new Error(parsers[i] + " parser must be loaded for HTML mixed mode to work.");
    }
    XMLParser.configure({useHTMLKludges: true});
  }

  function parseMixed(stream) {
    checkDependencies();
    var htmlParser = XMLParser.make(stream), localParser = null, inTag = false;
    var iter = {next: top, copy: copy};

    function top() {
      var token = htmlParser.next();
      if (token.content == "<")
        inTag = true;
      else if (token.style == "xml-tagname" && inTag === true)
        inTag = token.content.toLowerCase();
      else if (token.content == ">") {
        if (triggers[inTag]) {
          var parser = window[triggers[inTag]];
          iter.next = local(parser, "</" + inTag);
        }
        inTag = false;
      }
      return token;
    }
    function local(parser, tag) {
      var baseIndent = htmlParser.indentation();
      localParser = parser.make(stream, baseIndent + indentUnit);
      return function() {
        if (stream.lookAhead(tag, false, false, true)) {
          localParser = null;
          iter.next = top;
          return top();
        }

        var token = localParser.next();
        var lt = token.value.lastIndexOf("<"), sz = Math.min(token.value.length - lt, tag.length);
        if (lt != -1 && token.value.slice(lt, lt + sz).toLowerCase() == tag.slice(0, sz) &&
            stream.lookAhead(tag.slice(sz), false, false, true)) {
          stream.push(token.value.slice(lt));
          token.value = token.value.slice(0, lt);
        }

        if (token.indentation) {
          var oldIndent = token.indentation;
          token.indentation = function(chars) {
            if (chars == "</")
              return baseIndent;
            else
              return oldIndent(chars);
          };
        }

        return token;
      };
    }

    function copy() {
      var _html = htmlParser.copy(), _local = localParser && localParser.copy(),
          _next = iter.next, _inTag = inTag;
      return function(_stream) {
        stream = _stream;
        htmlParser = _html(_stream);
        localParser = _local && _local(_stream);
        iter.next = _next;
        inTag = _inTag;
        return iter;
      };
    }
    return iter;
  }

  return {
    make: parseMixed,
    electricChars: "{}/:",
    configure: function(obj) {
      if (obj.triggers) triggers = obj.triggers;
    }
  };

})();
