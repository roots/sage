// less.js runs LESS for you
module.exports = {
  dist: {
    options: {
      // cssmin will minify later
      compress: false,
      // LESS source map
      // To enable, set sourceMap to true and update sourceMapRootpath based on your install
      sourceMap: false,
      sourceMapFilename: 'assets/css/main.min.css.map',
      sourceMapRootpath: '/app/themes/roots/'
    },
    files: {
      'assets/css/main.css': 'assets/less/app.less'
    },
  }
}