'use strict'; // eslint-disable-line

const fs = require('fs');
const path = require('path');
const glob = require('glob');
const utils = require('loader-utils');
const includes = require('lodash/includes');

const interpolateName = require('./util/interpolateName');
const promisify = require('./util/promisify');

const fixPath = v => v.replace(/\\/g, '/');
const errorMsg = msg => `\x1b[31m${msg}\x1b[0m`;

const GLOB_CWD_AUTO = null;

const globAsync = promisify(glob);
const statAsync = promisify(fs.stat);
const readFileAsync = promisify(fs.readFile);

class PatternUndefinedError extends Error {
  constructor() {
    super(errorMsg('[copy-globs] You must provide glob pattern.'));
  }
}

class ArgsArrayError extends TypeError {
  constructor() {
    super(errorMsg(
      '[copy-globs] pattern cannot be an array.\n' +
      'For multiple folders, use something like:\n\n' +
      '  +(images|fonts)/**/*\n\n' +
      'See also: https://github.com/isaacs/node-glob#glob-primer\n'
    ));
  }
}

/**
 * Throws an error if pattern is an array or undefined
 *
 * @param pattern
 */
const testPattern = (pattern) => {
  if (pattern === undefined) {
    throw new PatternUndefinedError();
  }
  if (Array.isArray(pattern)) {
    throw new ArgsArrayError();
  }
};

const normalizeArguments = (input) => {
  testPattern(input);
  const options = {};
  if (typeof input === 'string') {
    options.pattern = input;
  } else {
    testPattern(input.pattern);
    return input;
  }
  return options;
};

module.exports = class {
  constructor(o) {
    const options = normalizeArguments(o);
    this.pattern = options.pattern;
    this.disable = options.disable;
    this.output = options.output || '[path][name].[ext]';
    this.globOptions = Object.assign(options.globOptions || {}, { cwd: GLOB_CWD_AUTO });
    this.globOptions.nodir = true;
    this.manifest = options.manifest || {};
    this.files = [];
    this.started = false;
  }
  apply(compiler) {
    if (this.disable) {
      return;
    }
    this.compiler = compiler;
    this.resolveWorkingDirectory();
    if (!this.started) {
      compiler.plugin('emit', this.emitHandler.bind(this));
      compiler.plugin('after-emit', this.afterEmitHandler.bind(this));
      compiler.plugin('after-emit', this.afterEmitHandler.bind(this));
      this.started = true;
    }
  }
  emitHandler(compilation, callback) {
    this.compilation = compilation;
    globAsync(this.pattern, this.globOptions)
      .then(
        paths => Promise.all(paths.map(this.processAsset.bind(this))),
        err => compilation.errors.push(err)
      )
      .then(() => {
        Object.keys(this.files).forEach((absoluteFrom) => {
          const file = this.files[absoluteFrom];
          this.manifest[file.relativeFrom] = file.webpackTo;
          this.compilation.assets[file.webpackTo] = {
            size: () => file.stat.size,
            source: () => file.content,
          };
        });
      })
      .then(callback);
  }
  afterEmitHandler(compilation, callback) {
    Object.keys(this.files)
      .filter(absoluteFrom => !includes(compilation.fileDependencies, absoluteFrom))
      .forEach(absoluteFrom => compilation.fileDependencies.push(absoluteFrom));
    callback();
  }
  resolveWorkingDirectory() {
    if (this.globOptions.cwd === GLOB_CWD_AUTO) {
      this.globOptions.cwd = this.compiler.options.context;
    }
    this.context = this.globOptions.cwd || this.compiler.options.context;
  }
  processAsset(relativeFrom) {
    if (this.compilation.assets[relativeFrom]) {
      return Promise.resolve();
    }
    const absoluteFrom = path.resolve(this.context, relativeFrom);
    return statAsync(absoluteFrom)
      .then(stat => this.buildFileObject(relativeFrom, absoluteFrom, stat))
      .then(this.addAsset.bind(this));
  }
  buildFileObject(relativeFrom, absoluteFrom, stat) {
    return readFileAsync(absoluteFrom)
      .then((content) => {
        const hash = utils.getHashDigest(content);
        const webpackTo = fixPath(interpolateName(this.output, relativeFrom, content));
        return { relativeFrom, absoluteFrom, stat, content, hash, webpackTo };
      });
  }
  addAsset(file) {
    const asset = this.getAsset(file.absoluteFrom);
    if (asset && asset.hash === file.hash) {
      return null;
    }
    this.files[file.absoluteFrom] = file;
    return file;
  }
  getAsset(absoluteFrom) {
    return this.files[absoluteFrom];
  }
};
