/**
 * Task: enqueue_ver
 * Description: Set the versions in scripts.php for CSS/JS
 */

module.exports = function(grunt) {
  'use strict';

  var fs = require('fs');
  var path = require('path');
  var crypto = require('crypto');

  grunt.registerTask('enqueue_ver', 'Set the versions in scripts.php for CSS/JS', function() {
    var scriptsPhp = 'lib/scripts.php';

    // Hash the CSS
    var hashCss = grunt.helper('md5', 'assets/css/main.min.css');

    // Hash the JS
    var hashJs = grunt.helper('md5', 'assets/js/scripts.min.js');

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
   * The 'md5' helper is a basic wrapper around crypto.createHash
   */
  grunt.registerHelper('md5', function(filepath) {
    var hash = crypto.createHash('md5');
    hash.update(fs.readFileSync(filepath));
    grunt.log.write('Versioning ' + filepath + '...').ok();
    return hash.digest('hex');
  });
};