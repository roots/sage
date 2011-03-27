/* Functionality for finding, storing, and restoring selections
 *
 * This does not provide a generic API, just the minimal functionality
 * required by the CodeMirror system.
 */

// Namespace object.
var select = {};

(function() {
  select.ie_selection = document.selection && document.selection.createRangeCollection;

  // Find the 'top-level' (defined as 'a direct child of the node
  // passed as the top argument') node that the given node is
  // contained in. Return null if the given node is not inside the top
  // node.
  function topLevelNodeAt(node, top) {
    while (node && node.parentNode != top)
      node = node.parentNode;
    return node;
  }

  // Find the top-level node that contains the node before this one.
  function topLevelNodeBefore(node, top) {
    while (!node.previousSibling && node.parentNode != top)
      node = node.parentNode;
    return topLevelNodeAt(node.previousSibling, top);
  }

  var fourSpaces = "\u00a0\u00a0\u00a0\u00a0";

  select.scrollToNode = function(node, cursor) {
    if (!node) return;
    var element = node, body = document.body,
        html = document.documentElement,
        atEnd = !element.nextSibling || !element.nextSibling.nextSibling
                || !element.nextSibling.nextSibling.nextSibling;
    // In Opera (and recent Webkit versions), BR elements *always*
    // have a offsetTop property of zero.
    var compensateHack = 0;
    while (element && !element.offsetTop) {
      compensateHack++;
      element = element.previousSibling;
    }
    // atEnd is another kludge for these browsers -- if the cursor is
    // at the end of the document, and the node doesn't have an
    // offset, just scroll to the end.
    if (compensateHack == 0) atEnd = false;

    // WebKit has a bad habit of (sometimes) happily returning bogus
    // offsets when the document has just been changed. This seems to
    // always be 5/5, so we don't use those.
    if (webkit && element && element.offsetTop == 5 && element.offsetLeft == 5)
      return;

    var y = compensateHack * (element ? element.offsetHeight : 0), x = 0,
        width = (node ? node.offsetWidth : 0), pos = element;
    while (pos && pos.offsetParent) {
      y += pos.offsetTop;
      // Don't count X offset for <br> nodes
      if (!isBR(pos))
        x += pos.offsetLeft;
      pos = pos.offsetParent;
    }

    var scroll_x = body.scrollLeft || html.scrollLeft || 0,
        scroll_y = body.scrollTop || html.scrollTop || 0,
        scroll = false, screen_width = window.innerWidth || html.clientWidth || 0;

    if (cursor || width < screen_width) {
      if (cursor) {
        var off = select.offsetInNode(node), size = nodeText(node).length;
        if (size) x += width * (off / size);
      }
      var screen_x = x - scroll_x;
      if (screen_x < 0 || screen_x > screen_width) {
        scroll_x = x;
        scroll = true;
      }
    }
    var screen_y = y - scroll_y;
    if (screen_y < 0 || atEnd || screen_y > (window.innerHeight || html.clientHeight || 0) - 50) {
      scroll_y = atEnd ? 1e6 : y;
      scroll = true;
    }
    if (scroll) window.scrollTo(scroll_x, scroll_y);
  };

  select.scrollToCursor = function(container) {
    select.scrollToNode(select.selectionTopNode(container, true) || container.firstChild, true);
  };

  // Used to prevent restoring a selection when we do not need to.
  var currentSelection = null;

  select.snapshotChanged = function() {
    if (currentSelection) currentSelection.changed = true;
  };

  // Find the 'leaf' node (BR or text) after the given one.
  function baseNodeAfter(node) {
    var next = node.nextSibling;
    if (next) {
      while (next.firstChild) next = next.firstChild;
      if (next.nodeType == 3 || isBR(next)) return next;
      else return baseNodeAfter(next);
    }
    else {
      var parent = node.parentNode;
      while (parent && !parent.nextSibling) parent = parent.parentNode;
      return parent && baseNodeAfter(parent);
    }
  }

  // This is called by the code in editor.js whenever it is replacing
  // a text node. The function sees whether the given oldNode is part
  // of the current selection, and updates this selection if it is.
  // Because nodes are often only partially replaced, the length of
  // the part that gets replaced has to be taken into account -- the
  // selection might stay in the oldNode if the newNode is smaller
  // than the selection's offset. The offset argument is needed in
  // case the selection does move to the new object, and the given
  // length is not the whole length of the new node (part of it might
  // have been used to replace another node).
  select.snapshotReplaceNode = function(from, to, length, offset) {
    if (!currentSelection) return;

    function replace(point) {
      if (from == point.node) {
        currentSelection.changed = true;
        if (length && point.offset > length) {
          point.offset -= length;
        }
        else {
          point.node = to;
          point.offset += (offset || 0);
        }
      }
      else if (select.ie_selection && point.offset == 0 && point.node == baseNodeAfter(from)) {
        currentSelection.changed = true;
      }
    }
    replace(currentSelection.start);
    replace(currentSelection.end);
  };

  select.snapshotMove = function(from, to, distance, relative, ifAtStart) {
    if (!currentSelection) return;

    function move(point) {
      if (from == point.node && (!ifAtStart || point.offset == 0)) {
        currentSelection.changed = true;
        point.node = to;
        if (relative) point.offset = Math.max(0, point.offset + distance);
        else point.offset = distance;
      }
    }
    move(currentSelection.start);
    move(currentSelection.end);
  };

  // Most functions are defined in two ways, one for the IE selection
  // model, one for the W3C one.
  if (select.ie_selection) {
    function selRange() {
      var sel = document.selection;
      if (!sel) return null;
      if (sel.createRange) return sel.createRange();
      else return sel.createTextRange();
    }

    function selectionNode(start) {
      var range = selRange();
      range.collapse(start);

      function nodeAfter(node) {
        var found = null;
        while (!found && node) {
          found = node.nextSibling;
          node = node.parentNode;
        }
        return nodeAtStartOf(found);
      }

      function nodeAtStartOf(node) {
        while (node && node.firstChild) node = node.firstChild;
        return {node: node, offset: 0};
      }

      var containing = range.parentElement();
      if (!isAncestor(document.body, containing)) return null;
      if (!containing.firstChild) return nodeAtStartOf(containing);

      var working = range.duplicate();
      working.moveToElementText(containing);
      working.collapse(true);
      for (var cur = containing.firstChild; cur; cur = cur.nextSibling) {
        if (cur.nodeType == 3) {
          var size = cur.nodeValue.length;
          working.move("character", size);
        }
        else {
          working.moveToElementText(cur);
          working.collapse(false);
        }

        var dir = range.compareEndPoints("StartToStart", working);
        if (dir == 0) return nodeAfter(cur);
        if (dir == 1) continue;
        if (cur.nodeType != 3) return nodeAtStartOf(cur);

        working.setEndPoint("StartToEnd", range);
        return {node: cur, offset: size - working.text.length};
      }
      return nodeAfter(containing);
    }

    select.markSelection = function() {
      currentSelection = null;
      var sel = document.selection;
      if (!sel) return;
      var start = selectionNode(true),
          end = selectionNode(false);
      if (!start || !end) return;
      currentSelection = {start: start, end: end, changed: false};
    };

    select.selectMarked = function() {
      if (!currentSelection || !currentSelection.changed) return;

      function makeRange(point) {
        var range = document.body.createTextRange(),
            node = point.node;
        if (!node) {
          range.moveToElementText(document.body);
          range.collapse(false);
        }
        else if (node.nodeType == 3) {
          range.moveToElementText(node.parentNode);
          var offset = point.offset;
          while (node.previousSibling) {
            node = node.previousSibling;
            offset += (node.innerText || "").length;
          }
          range.move("character", offset);
        }
        else {
          range.moveToElementText(node);
          range.collapse(true);
        }
        return range;
      }

      var start = makeRange(currentSelection.start), end = makeRange(currentSelection.end);
      start.setEndPoint("StartToEnd", end);
      start.select();
    };

    select.offsetInNode = function(node) {
      var range = selRange();
      if (!range) return 0;
      var range2 = range.duplicate();
      try {range2.moveToElementText(node);} catch(e){return 0;}
      range.setEndPoint("StartToStart", range2);
      return range.text.length;
    };

    // Get the top-level node that one end of the cursor is inside or
    // after. Note that this returns false for 'no cursor', and null
    // for 'start of document'.
    select.selectionTopNode = function(container, start) {
      var range = selRange();
      if (!range) return false;
      var range2 = range.duplicate();
      range.collapse(start);
      var around = range.parentElement();
      if (around && isAncestor(container, around)) {
        // Only use this node if the selection is not at its start.
        range2.moveToElementText(around);
        if (range.compareEndPoints("StartToStart", range2) == 1)
          return topLevelNodeAt(around, container);
      }

      // Move the start of a range to the start of a node,
      // compensating for the fact that you can't call
      // moveToElementText with text nodes.
      function moveToNodeStart(range, node) {
        if (node.nodeType == 3) {
          var count = 0, cur = node.previousSibling;
          while (cur && cur.nodeType == 3) {
            count += cur.nodeValue.length;
            cur = cur.previousSibling;
          }
          if (cur) {
            try{range.moveToElementText(cur);}
            catch(e){return false;}
            range.collapse(false);
          }
          else range.moveToElementText(node.parentNode);
          if (count) range.move("character", count);
        }
        else {
          try{range.moveToElementText(node);}
          catch(e){return false;}
        }
        return true;
      }

      // Do a binary search through the container object, comparing
      // the start of each node to the selection
      var start = 0, end = container.childNodes.length - 1;
      while (start < end) {
        var middle = Math.ceil((end + start) / 2), node = container.childNodes[middle];
        if (!node) return false; // Don't ask. IE6 manages this sometimes.
        if (!moveToNodeStart(range2, node)) return false;
        if (range.compareEndPoints("StartToStart", range2) == 1)
          start = middle;
        else
          end = middle - 1;
      }
      
      if (start == 0) {
        var test1 = selRange(), test2 = test1.duplicate();
        try {
          test2.moveToElementText(container);
        } catch(exception) {
          return null;
        }
        if (test1.compareEndPoints("StartToStart", test2) == 0)
          return null;
      }
      return container.childNodes[start] || null;
    };

    // Place the cursor after this.start. This is only useful when
    // manually moving the cursor instead of restoring it to its old
    // position.
    select.focusAfterNode = function(node, container) {
      var range = document.body.createTextRange();
      range.moveToElementText(node || container);
      range.collapse(!node);
      range.select();
    };

    select.somethingSelected = function() {
      var range = selRange();
      return range && (range.text != "");
    };

    function insertAtCursor(html) {
      var range = selRange();
      if (range) {
        range.pasteHTML(html);
        range.collapse(false);
        range.select();
      }
    }

    // Used to normalize the effect of the enter key, since browsers
    // do widely different things when pressing enter in designMode.
    select.insertNewlineAtCursor = function() {
      insertAtCursor("<br>");
    };

    select.insertTabAtCursor = function() {
      insertAtCursor(fourSpaces);
    };

    // Get the BR node at the start of the line on which the cursor
    // currently is, and the offset into the line. Returns null as
    // node if cursor is on first line.
    select.cursorPos = function(container, start) {
      var range = selRange();
      if (!range) return null;

      var topNode = select.selectionTopNode(container, start);
      while (topNode && !isBR(topNode))
        topNode = topNode.previousSibling;

      var range2 = range.duplicate();
      range.collapse(start);
      if (topNode) {
        range2.moveToElementText(topNode);
        range2.collapse(false);
      }
      else {
        // When nothing is selected, we can get all kinds of funky errors here.
        try { range2.moveToElementText(container); }
        catch (e) { return null; }
        range2.collapse(true);
      }
      range.setEndPoint("StartToStart", range2);

      return {node: topNode, offset: range.text.length};
    };

    select.setCursorPos = function(container, from, to) {
      function rangeAt(pos) {
        var range = document.body.createTextRange();
        if (!pos.node) {
          range.moveToElementText(container);
          range.collapse(true);
        }
        else {
          range.moveToElementText(pos.node);
          range.collapse(false);
        }
        range.move("character", pos.offset);
        return range;
      }

      var range = rangeAt(from);
      if (to && to != from)
        range.setEndPoint("EndToEnd", rangeAt(to));
      range.select();
    }

    // Some hacks for storing and re-storing the selection when the editor loses and regains focus.
    select.getBookmark = function (container) {
      var from = select.cursorPos(container, true), to = select.cursorPos(container, false);
      if (from && to) return {from: from, to: to};
    };

    // Restore a stored selection.
    select.setBookmark = function(container, mark) {
      if (!mark) return;
      select.setCursorPos(container, mark.from, mark.to);
    };
  }
  // W3C model
  else {
    // Find the node right at the cursor, not one of its
    // ancestors with a suitable offset. This goes down the DOM tree
    // until a 'leaf' is reached (or is it *up* the DOM tree?).
    function innerNode(node, offset) {
      while (node.nodeType != 3 && !isBR(node)) {
        var newNode = node.childNodes[offset] || node.nextSibling;
        offset = 0;
        while (!newNode && node.parentNode) {
          node = node.parentNode;
          newNode = node.nextSibling;
        }
        node = newNode;
        if (!newNode) break;
      }
      return {node: node, offset: offset};
    }

    // Store start and end nodes, and offsets within these, and refer
    // back to the selection object from those nodes, so that this
    // object can be updated when the nodes are replaced before the
    // selection is restored.
    select.markSelection = function () {
      var selection = window.getSelection();
      if (!selection || selection.rangeCount == 0)
        return (currentSelection = null);
      var range = selection.getRangeAt(0);

      currentSelection = {
        start: innerNode(range.startContainer, range.startOffset),
        end: innerNode(range.endContainer, range.endOffset),
        changed: false
      };
    };

    select.selectMarked = function () {
      var cs = currentSelection;
      // on webkit-based browsers, it is apparently possible that the
      // selection gets reset even when a node that is not one of the
      // endpoints get messed with. the most common situation where
      // this occurs is when a selection is deleted or overwitten. we
      // check for that here.
      function focusIssue() {
        if (cs.start.node == cs.end.node && cs.start.offset == cs.end.offset) {
          var selection = window.getSelection();
          if (!selection || selection.rangeCount == 0) return true;
          var range = selection.getRangeAt(0), point = innerNode(range.startContainer, range.startOffset);
          return cs.start.node != point.node || cs.start.offset != point.offset;
        }
      }
      if (!cs || !(cs.changed || (webkit && focusIssue()))) return;
      var range = document.createRange();

      function setPoint(point, which) {
        if (point.node) {
          // Some magic to generalize the setting of the start and end
          // of a range.
          if (point.offset == 0)
            range["set" + which + "Before"](point.node);
          else
            range["set" + which](point.node, point.offset);
        }
        else {
          range.setStartAfter(document.body.lastChild || document.body);
        }
      }

      setPoint(cs.end, "End");
      setPoint(cs.start, "Start");
      selectRange(range);
    };

    // Helper for selecting a range object.
    function selectRange(range) {
      var selection = window.getSelection();
      if (!selection) return;
      selection.removeAllRanges();
      selection.addRange(range);
    }
    function selectionRange() {
      var selection = window.getSelection();
      if (!selection || selection.rangeCount == 0)
        return false;
      else
        return selection.getRangeAt(0);
    }

    // Finding the top-level node at the cursor in the W3C is, as you
    // can see, quite an involved process.
    select.selectionTopNode = function(container, start) {
      var range = selectionRange();
      if (!range) return false;

      var node = start ? range.startContainer : range.endContainer;
      var offset = start ? range.startOffset : range.endOffset;
      // Work around (yet another) bug in Opera's selection model.
      if (window.opera && !start && range.endContainer == container && range.endOffset == range.startOffset + 1 &&
          container.childNodes[range.startOffset] && isBR(container.childNodes[range.startOffset]))
        offset--;

      // For text nodes, we look at the node itself if the cursor is
      // inside, or at the node before it if the cursor is at the
      // start.
      if (node.nodeType == 3){
        if (offset > 0)
          return topLevelNodeAt(node, container);
        else
          return topLevelNodeBefore(node, container);
      }
      // Occasionally, browsers will return the HTML node as
      // selection. If the offset is 0, we take the start of the frame
      // ('after null'), otherwise, we take the last node.
      else if (node.nodeName.toUpperCase() == "HTML") {
        return (offset == 1 ? null : container.lastChild);
      }
      // If the given node is our 'container', we just look up the
      // correct node by using the offset.
      else if (node == container) {
        return (offset == 0) ? null : node.childNodes[offset - 1];
      }
      // In any other case, we have a regular node. If the cursor is
      // at the end of the node, we use the node itself, if it is at
      // the start, we use the node before it, and in any other
      // case, we look up the child before the cursor and use that.
      else {
        if (offset == node.childNodes.length)
          return topLevelNodeAt(node, container);
        else if (offset == 0)
          return topLevelNodeBefore(node, container);
        else
          return topLevelNodeAt(node.childNodes[offset - 1], container);
      }
    };

    select.focusAfterNode = function(node, container) {
      var range = document.createRange();
      range.setStartBefore(container.firstChild || container);
      // In Opera, setting the end of a range at the end of a line
      // (before a BR) will cause the cursor to appear on the next
      // line, so we set the end inside of the start node when
      // possible.
      if (node && !node.firstChild)
        range.setEndAfter(node);
      else if (node)
        range.setEnd(node, node.childNodes.length);
      else
        range.setEndBefore(container.firstChild || container);
      range.collapse(false);
      selectRange(range);
    };

    select.somethingSelected = function() {
      var range = selectionRange();
      return range && !range.collapsed;
    };

    select.offsetInNode = function(node) {
      var range = selectionRange();
      if (!range) return 0;
      range = range.cloneRange();
      range.setStartBefore(node);
      return range.toString().length;
    };

    select.insertNodeAtCursor = function(node) {
      var range = selectionRange();
      if (!range) return;

      range.deleteContents();
      range.insertNode(node);
      webkitLastLineHack(document.body);

      // work around weirdness where Opera will magically insert a new
      // BR node when a BR node inside a span is moved around. makes
      // sure the BR ends up outside of spans.
      if (window.opera && isBR(node) && isSpan(node.parentNode)) {
        var next = node.nextSibling, p = node.parentNode, outer = p.parentNode;
        outer.insertBefore(node, p.nextSibling);
        var textAfter = "";
        for (; next && next.nodeType == 3; next = next.nextSibling) {
          textAfter += next.nodeValue;
          removeElement(next);
        }
        outer.insertBefore(makePartSpan(textAfter, document), node.nextSibling);
      }
      range = document.createRange();
      range.selectNode(node);
      range.collapse(false);
      selectRange(range);
    }

    select.insertNewlineAtCursor = function() {
      select.insertNodeAtCursor(document.createElement("BR"));
    };

    select.insertTabAtCursor = function() {
      select.insertNodeAtCursor(document.createTextNode(fourSpaces));
    };

    select.cursorPos = function(container, start) {
      var range = selectionRange();
      if (!range) return;

      var topNode = select.selectionTopNode(container, start);
      while (topNode && !isBR(topNode))
        topNode = topNode.previousSibling;

      range = range.cloneRange();
      range.collapse(start);
      if (topNode)
        range.setStartAfter(topNode);
      else
        range.setStartBefore(container);

      var text = range.toString();
      return {node: topNode, offset: text.length};
    };

    select.setCursorPos = function(container, from, to) {
      var range = document.createRange();

      function setPoint(node, offset, side) {
        if (offset == 0 && node && !node.nextSibling) {
          range["set" + side + "After"](node);
          return true;
        }

        if (!node)
          node = container.firstChild;
        else
          node = node.nextSibling;

        if (!node) return;

        if (offset == 0) {
          range["set" + side + "Before"](node);
          return true;
        }

        var backlog = []
        function decompose(node) {
          if (node.nodeType == 3)
            backlog.push(node);
          else
            forEach(node.childNodes, decompose);
        }
        while (true) {
          while (node && !backlog.length) {
            decompose(node);
            node = node.nextSibling;
          }
          var cur = backlog.shift();
          if (!cur) return false;

          var length = cur.nodeValue.length;
          if (length >= offset) {
            range["set" + side](cur, offset);
            return true;
          }
          offset -= length;
        }
      }

      to = to || from;
      if (setPoint(to.node, to.offset, "End") && setPoint(from.node, from.offset, "Start"))
        selectRange(range);
    };
  }
})();
