/* jshint node:true */

'use strict';

var Transform = require('stream').Transform,
    BufferStreams = require('bufferstreams'),
    SVGOptim = require('svgo'),
    gutil = require('gulp-util');

var PLUGIN_NAME = 'gulp-svgmin';

// File level transform function
function minifySVGTransform(svgo) {

    if (!(svgo instanceof SVGOptim)) {
        svgo = new SVGOptim(svgo);
    }

    // Return a callback function handling the buffered content
    return function(err, buf, cb) {

        // Handle any error
        if (err) {
            cb(new gutil.PluginError(PLUGIN_NAME, err));
        }

        // Use the buffered content
        svgo.optimize(String(buf), function(result) {
            if (result.error) {
                cb(new gutil.PluginError(PLUGIN_NAME, result.error));
            }

            // Bring it back to streams
            cb(null, new Buffer(result.data));
        });
    };
}

// Plugin function
function minifySVGGulp(plugins) {
    var stream = new Transform({objectMode: true});
    var svgo = new SVGOptim({ plugins: plugins });

    stream._transform = function(file, unused, done) {
        // When null just pass through
        if(file.isNull()) {
            stream.push(file); done();
            return;
        }

        if (file.isStream()) {
            file.contents = file.contents.pipe(
                new BufferStreams(minifySVGTransform(svgo)));
            stream.push(file);
            done();
        } else {
            svgo.optimize(String(file.contents), function(result) {
                if (result.error) {
                    stream.emit('error', new gutil.PluginError(PLUGIN_NAME, result.error));
                }
                file.contents = new Buffer(result.data);
                stream.push(file);
                done();
            });
        }
    };

    return stream;
}

// Export the file level transform function for other plugins usage
minifySVGGulp.fileTransform = minifySVGTransform;

// Export the plugin main function
module.exports = minifySVGGulp;

