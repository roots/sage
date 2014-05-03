/*jslint node: true */

'use strict';

var through = require('through');
var map = require('map-stream');
var Combine = require('ordered-read-streams');
var unique = require('unique-stream');

var glob = require('glob');
var minimatch = require('minimatch');
var glob2base = require('glob2base');
var path = require('path');

var isMatch = function(file, pattern) {
  if (typeof pattern === 'string') return minimatch(file.path, pattern);
  if (pattern instanceof RegExp) return pattern.test(file.path);
  return true; // unknown glob type?
};

var isNegative = function(pattern) {
  if (typeof pattern !== 'string') return true;
  if (pattern[0] === '!') return true;
  return false;
};

var isPositive = function(pattern) {
  return !isNegative(pattern);
};

var unrelative = function(cwd, glob) {
  var mod = '';
  if (glob[0] === '!') {
    mod = glob[0];
    glob = glob.slice(1);
  }
  return mod+path.resolve(cwd, glob);
};

var gs = {
  // creates a stream for a single glob or filter
  createStream: function(ourGlob, negatives, opt) {
    if (!negatives) negatives = [];
    if (!opt) opt = {};
    if (typeof opt.cwd !== 'string') opt.cwd = process.cwd();
    if (typeof opt.silent !== 'boolean') opt.silent = true;
    if (typeof opt.nonull !== 'boolean') opt.nonull = false;
    if (typeof opt.cwdbase !== 'boolean') opt.cwdbase = false;
    if (opt.cwdbase) opt.base = opt.cwd;
    
    // remove path relativity to make globs make sense
    ourGlob = unrelative(opt.cwd, ourGlob);
    negatives = negatives.map(unrelative.bind(null, opt.cwd));

    // create globbing stuff
    var globber = new glob.Glob(ourGlob, opt);

    // extract base path from glob
    var basePath = opt.base ? opt.base : glob2base(globber);

    // create stream and map events from globber to it
    var stream = through();

    globber.on('error', stream.emit.bind(stream, 'error'));
    globber.on('end', function(){
      stream.end();
    });
    globber.on('match', function(filename) {
      stream.write({
        cwd: opt.cwd,
        base: basePath,
        path: path.resolve(opt.cwd, filename)
      });
    });

    if (negatives.length === 0) return stream; // no filtering needed

    // stream to check against negatives
    var filterStream = map(function(filename, cb) {
      var matcha = isMatch.bind(null, filename);
      if (negatives.every(matcha)) return cb(null, filename); // pass
      cb(); // ignore
    });

    return stream.pipe(filterStream);
  },

  // creates a stream for multiple globs or filters
  create: function(globs, opt) {
    if (!opt) opt = {};

    // only one glob no need to aggregate
    if (!Array.isArray(globs)) return gs.createStream(globs, null, opt);

    var positives = globs.filter(isPositive);
    var negatives = globs.filter(isNegative);

    if (positives.length === 0) throw new Error("Missing positive glob");

    // only one positive glob no need to aggregate
    if (positives.length === 1) return gs.createStream(positives[0], negatives, opt);

    // create all individual streams
    var streams = positives.map(function(glob){
      return gs.createStream(glob, negatives, opt);
    });

    // then just pipe them to a single unique stream and return it
    var aggregate = new Combine(streams);
    var uniqueStream = unique('path');

    // TODO: set up streaming queue so items come in order

    return aggregate.pipe(uniqueStream);
  }
};

module.exports = gs;
