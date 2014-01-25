// uglify.js minifies the js after it's been combined by concat.js
module.exports = {
  build: {
    src: 'assets/js/scripts.js',
    dest: 'assets/js/scripts.min.js',
    options: {
      // JS source map: to enable, uncomment the lines below and update sourceMappingURL based on your install
      // sourceMap: 'assets/js/scripts.min.js.map',
      // sourceMappingURL: '/app/themes/roots/assets/js/scripts.min.js.map'    	
    }
  }
}