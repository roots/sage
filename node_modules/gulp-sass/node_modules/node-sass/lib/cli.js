var watch = require('node-watch'),
    render = require('./render'),
    path = require('path'),
    Emitter = require('events').EventEmitter,
    cwd = process.cwd();

var optimist = require('optimist')
  .usage('Compile .scss files with node-sass.\nUsage: $0 [options] <input.scss> [<output.css>]')
  .options('output-style', {
    describe: 'CSS output style (nested|expanded|compact|compressed)',
    'default': 'nested'
  })
  .options('source-comments', {
    describe: 'Include debug info in output (none|normal|map)',
    'default': 'none'
  })
  .options('source-map', {
    describe: 'Emit source map'
  })
  .options('include-path', {
    describe: 'Path to look for @import-ed files',
    'default': cwd
  })
  .options('image-path', {
    describe: 'Path to prepend when using the image-url(…) helper',
    'default': ''
  })
  .options('watch', {
    describe: 'Watch a directory or file',
    alias: 'w'
  })
  .options('output', {
    describe: 'Output css file',
    alias: 'o'
  })
  .options('stdout', {
    describe: 'Print the resulting CSS to stdout'
  })
  .options('help', {
    describe: 'Print usage info',
    type: 'string',
    alias: 'help'
  })
  .check(function(argv){
    if (argv.help) { return true; }
    if (argv._.length < 1) { return false; }
  });

// throttle function, used so when multiple files change at the same time
// (e.g. git pull) the files are only compiled once.
function throttle(fn) {
  var timer;
  var args = Array.prototype.slice.call(arguments, 1);
  return function() {
    var self = this;
    clearTimeout(timer);
    timer = setTimeout(function() {
      fn.apply(self, args);
    }, 20);
  };
}

function isSassFile(file) {
  return file.match(/\.(sass|scss)/);
}

exports = module.exports = function(args) {
  var argv = optimist.parse(args);

  if (argv.help) {
    optimist.showHelp();
    process.exit(0);
    return;
  }

  var emitter = new Emitter();

  emitter.on('error', function(err){
    console.error(err);
    process.exit(1);
  });

  var options = {
    stdout: argv.stdout
  };

  var inFile = options.inFile = argv._[0];
  var outFile = options.outFile = argv.o || argv._[1];

  if (!outFile) {
    var suffix = '.css';
    if (/\.css$/.test(inFile)) {
      suffix = '';
    }
    outFile = options.outFile = path.join(cwd, path.basename(inFile, '.scss') + suffix);
  }

  // make sure it's an array.
  options.includePaths = argv['include-path'];
  if (!Array.isArray(options.includePaths)) {
    options.includePaths = [options.includePaths];
  }

  // include the image path.
  options.imagePath = argv['image-path'];

  // if it's an array, make it a string
  options.outputStyle = argv['output-style'];
  if (Array.isArray(options.outputStyle)) {
    options.outputStyle = options.outputStyle[0];
  }

  // if it's an array, make it a string
  options.sourceComments = argv['source-comments'];
  if (Array.isArray(options.sourceComments)) {
    options.sourceComments = options.sourceComments[0];
  }

  // Set the sourceMap path if the sourceComment was 'map', but set source-map was missing
  if (options.sourceComments === 'map' && !argv['source-map']) {
    argv['source-map'] = true;
  }

  // set source map file and set sourceComments to 'map'
  if (argv['source-map']) {
    options.sourceComments = 'map';
    if (argv['source-map'] === true) {
      options.sourceMap = outFile + '.map';
    } else {
      options.sourceMap = path.resolve(cwd, argv['source-map']);
    }
  }

  if (argv.w) {

    var watchDir = argv.w;

    if (watchDir === true) {
      watchDir = [];
    } else if (!Array.isArray(watchDir)) {
      watchDir = [watchDir];
    }
    watchDir.push(inFile);

    var throttledRender = throttle(render, options, emitter);

    watch(watchDir, function(file){
      emitter.emit('warn', '=> changed: '.grey + file.blue);
      if (isSassFile(file)) {
        throttledRender();
      }
    });

    throttledRender();

  } else {
    render(options, emitter);
  }

  return emitter;
};

exports.optimist = optimist;
