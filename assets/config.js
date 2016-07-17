var path = require('path');
var url = require('url');
var cheerio = require('cheerio');
var request = require('sync-request');

var CONFIG = {
  context: "assets",
  theme: path.basename(path.dirname(__dirname)),
  entry: {
    main: [
      "./scripts/main.js",
      "./styles/main.scss"
    ],
    customizer: [
      "./scripts/customizer.js"
    ]
  },
  output: {
    path: "dist",
    publicPath: null,
  },
  devUrl: "http://example.dev",
  devPort: 3000
};

var getPublicPath = function () {
  var publicPath;
  $ = cheerio.load(request('GET', CONFIG.devUrl).getBody());
  $('script[src], link[href][type="text/css"]').each(function () {
    var $this = $(this);
    var href = $this.attr('src') ? $this.attr('src') : $this.attr('href');
    var path = url.parse(href, false, true).pathname;
    if (path.indexOf(CONFIG.theme) !== -1) {
      publicPath = path.replace(/\/(scripts|styles)\/.*/, '/');
    }
  });
  if (!publicPath) {
    publicPath = '/app/themes/' + CONFIG.theme + '/dist/';
    console.error("Could not determine publicPath. Now we're just guessing!");
  }
  return publicPath;
};

module.exports = (function (config) {
  if (!config.output.publicPath) {
    config.output.publicPath = getPublicPath();
  }
  return config;
})(CONFIG);
