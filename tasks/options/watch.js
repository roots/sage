// watch.js watches files to run tasks and run livereload
module.exports = {
  options: {
    // Browser live reloading
    // https://github.com/gruntjs/grunt-contrib-watch#live-reloading
    livereload: true,
  },
  scripts: {
    files: [
      'Gruntfile.js',
      'assets/js/*.js',
      '!assets/js/scripts.min.js'
    ],
    tasks: ['jshint', 'concat', 'uglify', 'version'],
    options: {
      spawn: false,
    }
  },
  css: {
    files: ['assets/css/*.css'],
    tasks: [],
    options: {
      nospawn: false
    }
  },
  less: {
    options: {
      nospawn: false,
      livereload: false
    },
    files: [
      'assets/less/*.less',
      'assets/less/bootstrap/*.less'
      ],
    tasks: ['less', 'cssmin', 'version']
  },
  php:{
    files: [
      'templates/*.php',
      'lib/*.php',
      '*.php'
    ],
    tasks: [],
    options: {
      spawn: false
    }
  }
}