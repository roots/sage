var sass = require('../sass'),
    chalk = require('chalk'),
    fs = require('fs');

function render(options, emitter) {

  sass.render({
    file: options.inFile,
    includePaths: options.includePaths,
    imagePath: options.imagePath,
    outputStyle: options.outputStyle,
    sourceComments: options.sourceComments,
    sourceMap: options.sourceMap,
    success: function(css, sourceMap) {

      var todo = 1;
      var done = function() {
        if (--todo <= 0) {
          emitter.emit('done');
        }
      };

      emitter.emit('warn', chalk.green('Rendering Complete, saving .css file...'));

      fs.writeFile(options.outFile, css, function(err) {
        if (err) { return emitter.emit('error', chalk.red('Error: ' + err)); }
        emitter.emit('warn', chalk.green('Wrote CSS to ' + options.outFile));
        emitter.emit('write', err, options.outFile, css);
        done();
      });

      if (options.sourceMap) {
        todo++;
        fs.writeFile(options.sourceMap, sourceMap, function(err) {
          if (err) {return emitter.emit('error', chalk.red('Error' + err)); }
          emitter.emit('warn', chalk.green('Wrote Source Map to ' + options.sourceMap));
          emitter.emit('write-source-map', err, options.sourceMap, sourceMap);
          done();
        });
      }

      if (options.stdout) {
        emitter.emit('log', css);
      }

      emitter.emit('render', css);
    },
    error: function(error) {
      emitter.emit('error', chalk.red(error));
    }
  });
}

module.exports = render;
