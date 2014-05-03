
/*!
 * Connect - directory
 * Copyright(c) 2011 Sencha Inc.
 * Copyright(c) 2011 TJ Holowaychuk
 * MIT Licensed
 */

// TODO: arrow key navigation
// TODO: make icons extensible

/**
 * Module dependencies.
 */

var fs = require('fs')
  , parse = require('url').parse
  , utils = require('../utils')
  , path = require('path')
  , normalize = path.normalize
  , sep = path.sep
  , extname = path.extname
  , join = path.join;
var Batch = require('batch');
var Negotiator = require('negotiator');

/*!
 * Icon cache.
 */

var cache = {};

/*!
 * Default template.
 */

var defaultTemplate = join(__dirname, '..', 'public', 'directory.html');

/**
 * Media types and the map for content negotiation.
 */

var mediaTypes = [
  'text/html',
  'text/plain',
  'application/json'
];

var mediaType = {
  'text/html': 'html',
  'text/plain': 'plain',
  'application/json': 'json'
};

/**
 * Directory:
 *
 * Serve directory listings with the given `root` path.
 *
 * Options:
 *
 *  - `hidden` display hidden (dot) files. Defaults to false.
 *  - `icons`  display icons. Defaults to false.
 *  - `filter` Apply this filter function to files. Defaults to false.
 *  - `template` Optional path to html template. Defaults to a built-in template.
 *    The following tokens are replaced:
 *      - `{directory}` with the name of the directory.
 *      - `{files}` with the HTML of an unordered list of file links.
 *      - `{linked-path}` with the HTML of a link to the directory.
 *      - `{style}` with the built-in CSS and embedded images.
 *
 * @param {String} root
 * @param {Object} options
 * @return {Function}
 * @api public
 */

exports = module.exports = function directory(root, options){
  options = options || {};

  // root required
  if (!root) throw new Error('directory() root path required');
  var hidden = options.hidden
    , icons = options.icons
    , view = options.view || 'tiles'
    , filter = options.filter
    , root = normalize(root + sep)
    , template = options.template || defaultTemplate;

  return function directory(req, res, next) {
    if ('GET' != req.method && 'HEAD' != req.method) return next();

    var url = parse(req.url)
      , dir = decodeURIComponent(url.pathname)
      , path = normalize(join(root, dir))
      , originalUrl = parse(req.originalUrl)
      , originalDir = decodeURIComponent(originalUrl.pathname)
      , showUp = path != root;

    // null byte(s), bad request
    if (~path.indexOf('\0')) return next(utils.error(400));

    // malicious path, forbidden
    if (0 != path.indexOf(root)) return next(utils.error(403));

    // check if we have a directory
    fs.stat(path, function(err, stat){
      if (err) return 'ENOENT' == err.code
        ? next()
        : next(err);

      if (!stat.isDirectory()) return next();

      // fetch files
      fs.readdir(path, function(err, files){
        if (err) return next(err);
        if (!hidden) files = removeHidden(files);
        if (filter) files = files.filter(filter);
        files.sort();

        // content-negotiation
        var type = new Negotiator(req).preferredMediaType(mediaTypes);

        // not acceptable
        if (!type) return next(utils.error(406));
        exports[mediaType[type]](req, res, files, next, originalDir, showUp, icons, path, view, template);
      });
    });
  };
};

/**
 * Respond with text/html.
 */

exports.html = function(req, res, files, next, dir, showUp, icons, path, view, template){
  fs.readFile(template, 'utf8', function(err, str){
    if (err) return next(err);
    fs.readFile(__dirname + '/../public/style.css', 'utf8', function(err, style){
      if (err) return next(err);
      stat(path, files, function(err, stats){
        if (err) return next(err);
        files = files.map(function(file, i){ return { name: file, stat: stats[i] }; });
        files.sort(fileSort);
        if (showUp) files.unshift({ name: '..' });
        str = str
          .replace('{style}', style.concat(iconStyle(files, icons)))
          .replace('{files}', html(files, dir, icons, view))
          .replace('{directory}', dir)
          .replace('{linked-path}', htmlPath(dir));
        res.setHeader('Content-Type', 'text/html');
        res.setHeader('Content-Length', str.length);
        res.end(str);
      });
    });
  });
};

/**
 * Respond with application/json.
 */

exports.json = function(req, res, files){
  files = JSON.stringify(files);
  res.setHeader('Content-Type', 'application/json');
  res.setHeader('Content-Length', files.length);
  res.end(files);
};

/**
 * Respond with text/plain.
 */

exports.plain = function(req, res, files){
  files = files.join('\n') + '\n';
  res.setHeader('Content-Type', 'text/plain');
  res.setHeader('Content-Length', files.length);
  res.end(files);
};

/**
 * Sort function for with directories first.
 */

function fileSort(a, b) {
  return Number(b.stat && b.stat.isDirectory()) - Number(a.stat && a.stat.isDirectory()) ||
    String(a.name).toLocaleLowerCase().localeCompare(String(b.name).toLocaleLowerCase());
}

/**
 * Map html `dir`, returning a linked path.
 */

function htmlPath(dir) {
  var curr = [];
  return dir.split('/').map(function(part){
    curr.push(encodeURIComponent(part));
    return part ? '<a href="' + curr.join('/') + '">' + part + '</a>' : '';
  }).join(' / ');
}

/**
 * Load icon images, return css string.
 */

function iconStyle (files, useIcons) {
  if (!useIcons) return '';
  var data = {};
  var views = { tiles: [], details: [], mobile: [] };

  for (var i=0; i < files.length; i++) {
    var file = files[i];
    if (file.name == '..') continue;

    var isDir = '..' == file.name || (file.stat && file.stat.isDirectory());
    var icon = isDir ? icons.folder : icons[extname(file.name)] || icons.default;

    var ext = extname(file.name);
    ext = isDir ? '.directory' : (icons[ext] ? ext : '.default');

    if (data[icon]) continue;
    data[icon] = ext + ' .name{background-image: url(data:image/png;base64,' + load(icon)+');}';
    views.tiles.push('.view-tiles ' + data[icon]);
  	views.details.push('.view-details ' + data[icon]);
  	views.mobile.push('#files ' + data[icon]);
  }

  var style = views.tiles.join('\n')
    + '\n'+views.details.join('\n')
    + '\n@media (max-width: 768px) {\n\t'
    + views.mobile.join('\n\t')
    + '\n}';
  return style;
}

/**
 * Map html `files`, returning an html unordered list.
 */

function html(files, dir, useIcons, view) {
  	return '<ul id="files" class="view-'+view+'">'
    + (view == 'details' ? (
      '<li class="header">'
      + '<span class="name">Name</span>'
      + '<span class="size">Size</span>'
      + '<span class="date">Modified</span>'
      + '</li>') : '')
    + files.map(function(file){
    var isDir
      , classes = []
      , path = dir.split('/').map(function (c) { return encodeURIComponent(c); });

    if (useIcons) {
      var ext = extname(file.name);
      isDir = '..' == file.name || (file.stat && file.stat.isDirectory());
      ext = isDir ? '.directory' : (icons[ext] ? ext : '.default');
      classes.push('icon');
      classes.push(ext.replace('.',''));
    }

    path.push(encodeURIComponent(file.name));

    var date = file.name == '..' ? ''
      : file.stat.mtime.toDateString()+' '+file.stat.mtime.toLocaleTimeString();
    var size = file.name == '..' ? '' : file.stat.size;

    return '<li><a href="'
      + utils.normalizeSlashes(normalize(path.join('/')))
      + '" class="'
      + classes.join(' ') + '"'
      + ' title="' + file.name + '">'
      + '<span class="name">'+file.name+'</span>'
      + '<span class="size">'+size+'</span>'
      + '<span class="date">'+date+'</span>'
      + '</a></li>';

  }).join('\n') + '</ul>';
}

/**
 * Load and cache the given `icon`.
 *
 * @param {String} icon
 * @return {String}
 * @api private
 */

function load(icon) {
  if (cache[icon]) return cache[icon];
  return cache[icon] = fs.readFileSync(__dirname + '/../public/icons/' + icon, 'base64');
}

/**
 * Filter "hidden" `files`, aka files
 * beginning with a `.`.
 *
 * @param {Array} files
 * @return {Array}
 * @api private
 */

function removeHidden(files) {
  return files.filter(function(file){
    return '.' != file[0];
  });
}

/**
 * Stat all files and return array of stat
 * in same order.
 */

function stat(dir, files, cb) {
  var batch = new Batch();

  batch.concurrency(10);

  files.forEach(function(file, i){
    batch.push(function(done){
      fs.stat(join(dir, file), done);
    });
  });

  batch.end(cb);
}

/**
 * Icon map.
 */

var icons = {
    '.js': 'page_white_code_red.png'
  , '.c': 'page_white_c.png'
  , '.h': 'page_white_h.png'
  , '.cc': 'page_white_cplusplus.png'
  , '.php': 'page_white_php.png'
  , '.rb': 'page_white_ruby.png'
  , '.cpp': 'page_white_cplusplus.png'
  , '.swf': 'page_white_flash.png'
  , '.pdf': 'page_white_acrobat.png'
  , 'folder': 'folder.png'
  , 'default': 'page_white.png'
};
