/* Simple parser for CSS */

var CSSParser = Editor.Parser = (function() {
  var tokenizeCSS = (function() {
    function normal(source, setState) {
      var ch = source.next();
      if (ch == "@") {
        source.nextWhileMatches(/\w/);
        return "css-at";
      }
      else if (ch == "/" && source.equals("*")) {
        setState(inCComment);
        return null;
      }
      else if (ch == "<" && source.equals("!")) {
        setState(inSGMLComment);
        return null;
      }
      else if (ch == "=") {
        return "css-compare";
      }
      else if (source.equals("=") && (ch == "~" || ch == "|")) {
        source.next();
        return "css-compare";
      }
      else if (ch == "\"" || ch == "'") {
        setState(inString(ch));
        return null;
      }
      else if (ch == "#") {
        source.nextWhileMatches(/\w/);
        return "css-hash";
      }
      else if (ch == "!") {
        source.nextWhileMatches(/[ \t]/);
        source.nextWhileMatches(/\w/);
        return "css-important";
      }
      else if (/\d/.test(ch)) {
        source.nextWhileMatches(/[\w.%]/);
        return "css-unit";
      }
      else if (/[,.+>*\/]/.test(ch)) {
        return "css-select-op";
      }
      else if (/[;{}:\[\]]/.test(ch)) {
        return "css-punctuation";
      }
      else {
        source.nextWhileMatches(/[\w\\\-_]/);
        return "css-identifier";
      }
    }

    function inCComment(source, setState) {
      var maybeEnd = false;
      while (!source.endOfLine()) {
        var ch = source.next();
        if (maybeEnd && ch == "/") {
          setState(normal);
          break;
        }
        maybeEnd = (ch == "*");
      }
      return "css-comment";
    }

    function inSGMLComment(source, setState) {
      var dashes = 0;
      while (!source.endOfLine()) {
        var ch = source.next();
        if (dashes >= 2 && ch == ">") {
          setState(normal);
          break;
        }
        dashes = (ch == "-") ? dashes + 1 : 0;
      }
      return "css-comment";
    }

    function inString(quote) {
      return function(source, setState) {
        var escaped = false;
        while (!source.endOfLine()) {
          var ch = source.next();
          if (ch == quote && !escaped)
            break;
          escaped = !escaped && ch == "\\";
        }
        if (!escaped)
          setState(normal);
        return "css-string";
      };
    }

    return function(source, startState) {
      return tokenizer(source, startState || normal);
    };
  })();

  function indentCSS(inBraces, inRule, base) {
    return function(nextChars) {
      if (!inBraces || /^\}/.test(nextChars)) return base;
      else if (inRule) return base + indentUnit * 2;
      else return base + indentUnit;
    };
  }

  // This is a very simplistic parser -- since CSS does not really
  // nest, it works acceptably well, but some nicer colouroing could
  // be provided with a more complicated parser.
  function parseCSS(source, basecolumn) {
    basecolumn = basecolumn || 0;
    var tokens = tokenizeCSS(source);
    var inBraces = false, inRule = false, inDecl = false;;

    var iter = {
      next: function() {
        var token = tokens.next(), style = token.style, content = token.content;

        if (style == "css-hash")
          style = token.style =  inRule ? "css-colorcode" : "css-identifier";
        if (style == "css-identifier") {
          if (inRule) token.style = "css-value";
          else if (!inBraces && !inDecl) token.style = "css-selector";
        }

        if (content == "\n")
          token.indentation = indentCSS(inBraces, inRule, basecolumn);

        if (content == "{" && inDecl == "@media")
          inDecl = false;
        else if (content == "{")
          inBraces = true;
        else if (content == "}")
          inBraces = inRule = inDecl = false;
        else if (content == ";")
          inRule = inDecl = false;
        else if (inBraces && style != "css-comment" && style != "whitespace")
          inRule = true;
        else if (!inBraces && style == "css-at")
          inDecl = content;

        return token;
      },

      copy: function() {
        var _inBraces = inBraces, _inRule = inRule, _tokenState = tokens.state;
        return function(source) {
          tokens = tokenizeCSS(source, _tokenState);
          inBraces = _inBraces;
          inRule = _inRule;
          return iter;
        };
      }
    };
    return iter;
  }

  return {make: parseCSS, electricChars: "}"};
})();
