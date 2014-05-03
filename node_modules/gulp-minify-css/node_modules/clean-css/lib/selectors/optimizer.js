var Tokenizer = require('./tokenizer');
var PropertyOptimizer = require('../properties/optimizer');

module.exports = function Optimizer(data, context, options) {
  var specialSelectors = {
    '*': /\-(moz|ms|o|webkit)\-/,
    'ie8': /(\-moz\-|\-ms\-|\-o\-|\-webkit\-|:root|:nth|:first\-of|:last|:only|:empty|:target|:checked|::selection|:enabled|:disabled|:not)/,
    'ie7': /(\-moz\-|\-ms\-|\-o\-|\-webkit\-|:focus|:before|:after|:root|:nth|:first\-of|:last|:only|:empty|:target|:checked|::selection|:enabled|:disabled|:not)/
  };

  var minificationsMade = [];

  var propertyOptimizer = new PropertyOptimizer(options.compatibility);

  var cleanUpSelector = function(selectors) {
    if (selectors.indexOf(',') == -1)
      return selectors;

    var plain = [];
    var cursor = 0;
    var lastComma = 0;
    var noBrackets = selectors.indexOf('(') == -1;
    var withinBrackets = function(idx) {
      if (noBrackets)
        return false;

      var previousOpening = selectors.lastIndexOf('(', idx);
      var previousClosing = selectors.lastIndexOf(')', idx);

      if (previousOpening == -1)
        return false;
      if (previousClosing > 0 && previousClosing < idx)
        return false;

      return true;
    };

    while (true) {
      var nextComma = selectors.indexOf(',', cursor + 1);
      var selector;

      if (nextComma === -1) {
        nextComma = selectors.length;
      } else if (withinBrackets(nextComma)) {
        cursor = nextComma + 1;
        continue;
      }
      selector = selectors.substring(lastComma, nextComma);
      lastComma = cursor = nextComma + 1;

      if (plain.indexOf(selector) == -1)
        plain.push(selector);

      if (nextComma === selectors.length)
        break;
    }

    return plain.sort().join(',');
  };

  var isSpecial = function(selector) {
    return specialSelectors[options.selectorsMergeMode || '*'].test(selector);
  };

  var removeDuplicates = function(tokens) {
    var matched = {};
    var forRemoval = [];

    for (var i = 0, l = tokens.length; i < l; i++) {
      var token = tokens[i];
      if (typeof token == 'string' || token.block)
        continue;

      var id = token.body + '@' + token.selector;
      var alreadyMatched = matched[id];

      if (alreadyMatched) {
        forRemoval.push(alreadyMatched[0]);
        alreadyMatched.unshift(i);
      } else {
        matched[id] = [i];
      }
    }

    forRemoval = forRemoval.sort(function(a, b) {
      return a > b ? 1 : -1;
    });

    for (var j = 0, n = forRemoval.length; j < n; j++) {
      tokens.splice(forRemoval[j] - j, 1);
    }

    minificationsMade.unshift(forRemoval.length > 0);
  };

  var mergeAdjacent = function(tokens) {
    var forRemoval = [];
    var lastToken = { selector: null, body: null };

    for (var i = 0, l = tokens.length; i < l; i++) {
      var token = tokens[i];

      if (typeof token == 'string' || token.block)
        continue;

      if (token.selector == lastToken.selector) {
        var joinAt = [lastToken.body.split(';').length];
        lastToken.body = propertyOptimizer.process(lastToken.body + ';' + token.body, joinAt);
        forRemoval.push(i);
      } else if (token.body == lastToken.body && !isSpecial(token.selector) && !isSpecial(lastToken.selector)) {
        lastToken.selector = cleanUpSelector(lastToken.selector + ',' + token.selector);
        forRemoval.push(i);
      } else {
        lastToken = token;
      }
    }

    for (var j = 0, m = forRemoval.length; j < m; j++) {
      tokens.splice(forRemoval[j] - j, 1);
    }

    minificationsMade.unshift(forRemoval.length > 0);
  };

  var reduceNonAdjacent = function(tokens) {
    var matched = {};
    var matchedMoreThanOnce = [];
    var partiallyReduced = [];
    var reduced = false;
    var token, selector, selectors;

    for (var i = 0, l = tokens.length; i < l; i++) {
      token = tokens[i];
      selector = token.selector;

      if (typeof token == 'string' || token.block)
        continue;

      selectors = selector.indexOf(',') > 0 && !isSpecial(selector) ?
        selector.split(',').concat(selector) :
        [selector];

      for (var j = 0, m = selectors.length; j < m; j++) {
        var sel = selectors[j];
        var alreadyMatched = matched[sel];
        if (alreadyMatched) {
          if (alreadyMatched.length == 1)
            matchedMoreThanOnce.push(sel);
          alreadyMatched.push(i);
        } else {
          matched[sel] = [i];
        }
      }
    }

    matchedMoreThanOnce.forEach(function(selector) {
      var matchPositions = matched[selector];
      var bodies = [];
      var splitBodies = [];
      var joinsAt = [];
      var j;

      for (j = 0, m = matchPositions.length; j < m; j++) {
        var body = tokens[matchPositions[j]].body;
        var splitBody = body.split(';');

        bodies.push(body);
        splitBodies.push(splitBody);
        joinsAt.push((joinsAt[j - 1] || 0) + splitBody.length);
      }

      var optimizedBody = propertyOptimizer.process(bodies.join(';'), joinsAt);
      var optimizedTokens = optimizedBody.split(';');

      j = optimizedTokens.length - 1;
      var currentMatch = matchPositions.length - 1;

      while (currentMatch >= 0) {
        if (splitBodies[currentMatch].indexOf(optimizedTokens[j]) > -1 && j > -1) {
          j--;
          continue;
        }

        var tokenIndex = matchPositions[currentMatch];
        var token = tokens[tokenIndex];
        var newBody = optimizedTokens.splice(j + 1);
        var reducedBody = [];
        for (var k = 0, n = newBody.length; k < n; k++) {
          if (newBody[k].length > 0)
            reducedBody.push(newBody[k]);
        }

        if (token.selector == selector) {
          var joinedBody = reducedBody.join(';');
          reduced = reduced || (token.body != joinedBody);
          token.body = joinedBody;
        } else {
          token._partials = token._partials || [];
          token._partials.push(reducedBody.join(';'));

          if (partiallyReduced.indexOf(tokenIndex) == -1)
            partiallyReduced.push(tokenIndex);
        }

        currentMatch -= 1;
      }
    });

    // process those tokens which were partially reduced
    // i.e. at least one of token's selectors saw reduction
    // if all selectors were reduced to same value we can override it
    for (i = 0, l = partiallyReduced.length; i < l; i++) {
      token = tokens[partiallyReduced[i]];

      if (token.body != token._partials[0] && token._partials.length == token.selector.split(',').length) {
        var newBody = token._partials[0];
        for (var k = 1, n = token._partials.length; k < n; k++) {
          if (token._partials[k] != newBody)
            break;
        }

        if (k == n) {
          token.body = newBody;
          reduced = reduced || true;
        }
      }

      delete token._partials;
    }

    minificationsMade.unshift(reduced);
  };

  var optimize = function(tokens) {
    var noChanges = function() {
      return minificationsMade.length > 4 &&
        minificationsMade[0] === false &&
        minificationsMade[1] === false;
    };

    tokens = Array.isArray(tokens) ? tokens : [tokens];
    for (var i = 0, l = tokens.length; i < l; i++) {
      var token = tokens[i];

      if (token.selector) {
        token.selector = cleanUpSelector(token.selector);
        token.body = propertyOptimizer.process(token.body, false);
      } else if (token.block) {
        optimize(token.body);
      }
    }

    // Run until 2 last operations do not yield any changes
    minificationsMade = [];
    while (true) {
      if (noChanges())
        break;
      removeDuplicates(tokens);

      if (noChanges())
        break;
      mergeAdjacent(tokens);

      if (noChanges())
        break;
      reduceNonAdjacent(tokens);
    }
  };

  var rebuild = function(tokens) {
    var rebuilt = [];

    tokens = Array.isArray(tokens) ? tokens : [tokens];
    for (var i = 0, l = tokens.length; i < l; i++) {
      var token = tokens[i];

      if (typeof token == 'string') {
        rebuilt.push(token);
        continue;
      }

      var name = token.block || token.selector;
      var body = token.block ? rebuild(token.body) : token.body;

      if (body.length > 0)
        rebuilt.push(name + '{' + body + '}');
    }

    return rebuilt.join(options.keepBreaks ? options.lineBreak : '');
  };

  return {
    process: function() {
      var tokenized = new Tokenizer(data, context).process();
      optimize(tokenized);
      return rebuild(tokenized);
    }
  };
};
