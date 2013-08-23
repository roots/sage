/**
 * Task: version
 * Set the versions in scripts.php for CSS/JS.
 */

'use strict';

var fs = require('fs'),
    path = require('path'),
    crypto = require('crypto');

module.exports = function(grunt) {
  grunt.registerTask('version', 'Set the versions in scripts.php for CSS/JS', function() {
    var scriptsPhp = 'lib/scripts.php';

    // Hash the CSS
    var hashCss = md5('assets/css/main.min.css');

    // Hash the JS
    var hashJs = md5('assets/js/scripts.min.js');

    // Update scripts.php to reference the new versions
    var regexCss = /(wp_enqueue_style\('roots_main',(\s*[^,]+,){2})\s*[^\)]+\);/;
    var regexJs = /(wp_register_script\('roots_scripts',(\s*[^,]+,){2})\s*[^,]+,\s*([^\)]+)\);/;

    var content = grunt.file.read(scriptsPhp);
    content = content.replace(regexCss, "\$1 '" + hashCss + "');");
    content = content.replace(regexJs, "\$1 '" + hashJs + "', " + "\$3);");
    grunt.file.write(scriptsPhp, content);
    grunt.log.writeln('"' + scriptsPhp + '" updated with new CSS/JS versions.');
  });

  /**
   * 'md5' is a basic wrapper around crypto.createHash
   */
  var md5 = function(filepath) {
    var hash = crypto.createHash('md5');
    hash.update(fs.readFileSync(filepath));
    grunt.log.write('Versioning ' + filepath + '...').ok();
    return hash.digest('hex');
  };
};
