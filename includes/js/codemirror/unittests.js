/**
 * Test Harness for CodeMirror
 * JS-unit compatible tests here.  The two available assertions are
 * assertEquals (strict equality) and assertEquivalent (looser equivalency).
 *
 * 'editor' is a global object for the CodeMirror editor shared between all
 * tests.  After manipulating it in each test, try to restore it to
 * approximately its original state.
 */

function testSetGet() {
  var code = 'It was the best of times.\nIt was the worst of times.';
  editor.setCode(code);
  assertEquals(code, editor.getCode());
  editor.setCode('');
  assertEquals('', editor.getCode());
}

function testSetStylesheet() {
  function cssStatus() {
    // Returns a list of tuples, for each CSS link return the filename and
    // whether it is enabled.
    links = editor.win.document.getElementsByTagName('link');
    css = [];
    for (var x = 0, link; link = links[x]; x++) {
      if (link.rel.indexOf("stylesheet") !== -1) {
        css.push([link.href.substring(link.href.lastIndexOf('/') + 1),
                 !link.disabled])
      }
    }
    return css;
  }
  assertEquivalent([], cssStatus());
  editor.setStylesheet('css/jscolors.css');
  assertEquivalent([['jscolors.css', true]], cssStatus());
  editor.setStylesheet(['css/csscolors.css', 'css/xmlcolors.css']);
  assertEquivalent([['jscolors.css', false], ['csscolors.css', true], ['xmlcolors.css', true]], cssStatus());
  editor.setStylesheet([]);
  assertEquivalent([['jscolors.css', false], ['csscolors.css', false], ['xmlcolors.css', false]], cssStatus());
}

// Update this list of tests as new ones are added.
var tests = ['testSetGet', 'testSetStylesheet'];

