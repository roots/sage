(function() {
  var Binary, autoprefixer, fs, path;

  autoprefixer = require('./autoprefixer');

  path = require('path');

  fs = require('fs-extra');

  Binary = (function() {
    function Binary(process) {
      this["arguments"] = process.argv.slice(2);
      this.stdin = process.stdin;
      this.stderr = process.stderr;
      this.stdout = process.stdout;
      this.status = 0;
      this.command = 'compile';
      this.inputFiles = [];
      this.processOptions = {};
      this.processorOptions = {};
      this.parseArguments();
    }

    Binary.prototype.help = function() {
      return 'Usage: autoprefixer [OPTION...] FILES\n\nParse CSS files and add prefixed properties and values.\n\nOptions:\n  -b, --browsers BROWSERS  add prefixes for selected browsers\n  -o, --output FILE        set output file\n  -d, --dir DIR            set output dir\n  -m, --map                generate source map\n      --no-map             skip source map even if previous map exists\n  -I, --inline-map         inline map by data:uri to annotation comment\n      --no-map-annotation  skip source map annotation comment is CSS\n  -c, --cascade            create nice visual cascade of prefixes\n  -i, --info               show selected browsers and properties\n  -h, --help               show help text\n  -v, --version            print program version';
    };

    Binary.prototype.desc = function() {
      return 'Files:\n  If you didn\'t set input files, autoprefixer will read from stdin stream.\n\n  By default, prefixed CSS will rewrite original files.\n\n  You can specify output file or directory by `-o` argument.\n  For several input files you can specify only output directory by `-d`.\n\n  Output CSS will be written to stdout stream on `-o -\' argument or stdin input.\n\nSource maps:\n  On `-m` argument Autoprefixer will generate source map for changes near\n  output CSS (for out/main.css it generates out/main.css.map).\n\n  If previous source map will be near input files (for example, in/main.css\n  and in/main.css.map) Autoprefixer will apply previous map to output\n  source map.\n\nBrowsers:\n  Separate browsers by comma. For example, `-b "> 1%, opera 12"\'.\n  You can set browsers by global usage statictics: `-b \"> 1%\"\'.\n  or last version: `-b "last 2 versions"\'.';
    };

    Binary.prototype.print = function(str) {
      str = str.replace(/\n$/, '');
      return this.stdout.write(str + "\n");
    };

    Binary.prototype.error = function(str) {
      this.status = 1;
      return this.stderr.write(str + "\n");
    };

    Binary.prototype.version = function() {
      return require('../package.json').version;
    };

    Binary.prototype.parseArguments = function() {
      var arg, args;
      args = this["arguments"].slice();
      while (args.length > 0) {
        arg = args.shift();
        switch (arg) {
          case '-h':
          case '--help':
            this.command = 'showHelp';
            break;
          case '-v':
          case '--version':
            this.command = 'showVersion';
            break;
          case '-i':
          case '--info':
            this.command = 'info';
            break;
          case '-u':
          case '--update':
            this.command = 'update';
            break;
          case '-m':
          case '--map':
            this.processOptions.map = true;
            break;
          case '--no-map':
            this.processOptions.map = false;
            break;
          case '-I':
          case '--inline-map':
            this.processOptions.inlineMap = true;
            break;
          case '--no-map-annotation':
            this.processOptions.mapAnnotation = false;
            break;
          case '-c':
          case '--cascade':
            this.processorOptions.cascade = true;
            break;
          case '-b':
          case '--browsers':
            this.requirements = args.shift().split(',').map(function(i) {
              return i.trim();
            });
            break;
          case '-o':
          case '--output':
            this.outputFile = args.shift();
            break;
          case '-d':
          case '--dir':
            this.outputDir = args.shift();
            break;
          default:
            if (arg.match(/^-\w$/) || arg.match(/^--\w[\w-]+$/)) {
              this.command = void 0;
              this.error("autoprefixer: Unknown argument " + arg);
              this.error('');
              this.error(this.help());
            } else {
              this.inputFiles.push(arg);
            }
        }
      }
    };

    Binary.prototype.showHelp = function(done) {
      this.print(this.help());
      this.print('');
      this.print(this.desc());
      return done();
    };

    Binary.prototype.showVersion = function(done) {
      this.print("autoprefixer " + (this.version()));
      return done();
    };

    Binary.prototype.info = function(done) {
      this.print(this.compiler().info());
      return done();
    };

    Binary.prototype.update = function(done) {
      var coffee, updater;
      try {
        coffee = require('coffee-script');
      } catch (_error) {
        this.error("Install coffee-script npm package");
        return done();
      }
      updater = require('./updater');
      updater.request((function(_this) {
        return function() {
          return _this.stdout.write('.');
        };
      })(this));
      updater.done((function(_this) {
        return function() {
          _this.print('');
          if (updater.changed.length === 0) {
            _this.print('Everything up-to-date');
          } else {
            _this.print("Update " + (updater.changed.join(' and ')) + " data");
          }
          return done();
        };
      })(this));
      return updater.run();
    };

    Binary.prototype.startWork = function() {
      return this.waiting += 1;
    };

    Binary.prototype.endWork = function() {
      this.waiting -= 1;
      if (this.waiting <= 0) {
        return this.doneCallback();
      }
    };

    Binary.prototype.workError = function(str) {
      this.error(str);
      return this.endWork();
    };

    Binary.prototype.compiler = function() {
      return this.compilerCache || (this.compilerCache = autoprefixer(this.requirements, this.processorOptions));
    };

    Binary.prototype.compileCSS = function(css, output, input) {
      var error, name, opts, result, value, _ref;
      opts = {};
      _ref = this.processOptions;
      for (name in _ref) {
        value = _ref[name];
        opts[name] = value;
      }
      if (input) {
        opts.from = input;
      }
      if (output !== '-') {
        opts.to = output;
      }
      if (opts.map && input && fs.existsSync(input + '.map')) {
        opts.map = fs.readFileSync(input + '.map').toString();
      }
      try {
        result = this.compiler().process(css, opts);
      } catch (_error) {
        error = _error;
        if (error.autoprefixer || error.message.match(/^Can't parse CSS/)) {
          this.error("autoprefixer: " + error.message);
        } else {
          this.error('autoprefixer: Internal error');
        }
        if (error.css || !error.autoprefixer) {
          if (error.stack != null) {
            this.error('');
            this.error(error.stack);
          }
        }
      }
      if (result == null) {
        return this.endWork();
      }
      if (output === '-') {
        this.print(result.css);
        return this.endWork();
      } else {
        return fs.outputFile(output, result.css, (function(_this) {
          return function(error) {
            if (error) {
              _this.error("autoprefixer: " + error);
            }
            if (result.map) {
              return fs.writeFile(output + '.map', result.map, function(error) {
                if (error) {
                  _this.error("autoprefixer: " + error);
                }
                return _this.endWork();
              });
            } else {
              return _this.endWork();
            }
          };
        })(this));
      }
    };

    Binary.prototype.files = function() {
      var file, _i, _j, _k, _len, _len1, _len2, _ref, _ref1, _ref2, _results, _results1, _results2;
      if (this.inputFiles.length === 0) {
        this.outputFile || (this.outputFile = '-');
      }
      if (this.outputDir) {
        if (this.inputFiles.length === 0) {
          this.error("autoprefixer: For STDIN input you need to specify output file (by `-o FILE`),\nnot output dir");
          return;
        }
        if (fs.existsSync(this.outputDir) && !fs.statSync(this.outputDir).isDirectory()) {
          this.error("autoprefixer: Path " + this.outputDir + " is a file, not directory");
          return;
        }
        _ref = this.inputFiles;
        _results = [];
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
          file = _ref[_i];
          _results.push([file, path.join(this.outputDir, path.basename(file))]);
        }
        return _results;
      } else if (this.outputFile) {
        if (this.inputFiles.length > 1) {
          this.error("autoprefixer: For several files you can specify only output dir (by `-d DIR`),\nnot one output file");
          return;
        }
        _ref1 = this.inputFiles;
        _results1 = [];
        for (_j = 0, _len1 = _ref1.length; _j < _len1; _j++) {
          file = _ref1[_j];
          _results1.push([file, this.outputFile]);
        }
        return _results1;
      } else {
        _ref2 = this.inputFiles;
        _results2 = [];
        for (_k = 0, _len2 = _ref2.length; _k < _len2; _k++) {
          file = _ref2[_k];
          _results2.push([file, file]);
        }
        return _results2;
      }
    };

    Binary.prototype.compile = function(done) {
      var css, file, files, input, output, _fn, _i, _j, _len, _len1, _ref;
      this.waiting = 0;
      this.doneCallback = done;
      files = this.files();
      if (!files) {
        return done();
      }
      if (files.length === 0) {
        this.startWork();
        css = '';
        this.stdin.resume();
        this.stdin.on('data', function(chunk) {
          return css += chunk;
        });
        return this.stdin.on('end', (function(_this) {
          return function() {
            return _this.compileCSS(css, _this.outputFile);
          };
        })(this));
      } else {
        for (_i = 0, _len = files.length; _i < _len; _i++) {
          file = files[_i];
          this.startWork();
        }
        _fn = (function(_this) {
          return function(input, output) {
            return fs.readFile(input, function(error, css) {
              if (error) {
                return _this.workError("autoprefixer: " + error.message);
              } else {
                return _this.compileCSS(css, output, input);
              }
            });
          };
        })(this);
        for (_j = 0, _len1 = files.length; _j < _len1; _j++) {
          _ref = files[_j], input = _ref[0], output = _ref[1];
          if (!fs.existsSync(input)) {
            this.workError("autoprefixer: File " + input + " doesn't exists");
            continue;
          }
          _fn(input, output);
        }
        return false;
      }
    };

    Binary.prototype.run = function(done) {
      if (this.command) {
        return this[this.command](done);
      } else {
        return done();
      }
    };

    return Binary;

  })();

  module.exports = Binary;

}).call(this);
