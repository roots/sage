module.exports = function(grunt) {
  grunt.initConfig({
    pkg: '<json:package.json>',
    meta: {
      banner: '/*! Roots <%= pkg.version %> - <%= pkg.homepage %> */'
    },
    lint: {
      files: ['grunt.js', 'assets/js/_*.js']
    },
    recess: {
      dist: {
        src: [
          'assets/css/less/bootstrap/bootstrap.less',
          'assets/css/less/bootstrap/responsive.less',
          'assets/css/less/app.less'
        ],
        dest: 'assets/css/main.css',
        options: {
          compile: true
        }
      }
    },
    concat: {
      js: {
        src: [
          'assets/js/plugins/bootstrap/bootstrap-transition.js',
          'assets/js/plugins/bootstrap/bootstrap-alert.js',
          'assets/js/plugins/bootstrap/bootstrap-button.js',
          'assets/js/plugins/bootstrap/bootstrap-carousel.js',
          'assets/js/plugins/bootstrap/bootstrap-collapse.js',
          'assets/js/plugins/bootstrap/bootstrap-dropdown.js',
          'assets/js/plugins/bootstrap/bootstrap-modal.js',
          'assets/js/plugins/bootstrap/bootstrap-tooltip.js',
          'assets/js/plugins/bootstrap/bootstrap-popover.js',
          'assets/js/plugins/bootstrap/bootstrap-scrollspy.js',
          'assets/js/plugins/bootstrap/bootstrap-tab.js',
          'assets/js/plugins/bootstrap/bootstrap-typeahead.js',
          'assets/js/plugins/*.js',
          'assets/js/_*.js'
        ],
        dest: 'assets/js/scripts.js'
      }
    },
    min: {
      dist: {
        src: ['<banner>', 'assets/js/scripts.js'],
        dest: 'assets/js/scripts.min.js'
      }
    },
    mincss: {
      compress: {
        files: {
          'assets/css/main.min.css': ['assets/css/main.css']
        }
      }
    },
    watch: {
      js: {
        files: ['<config:lint.files>'],
        tasks: 'js'
      },
      css: {
        files: ['assets/css/less/*.less', 'assets/css/less/bootstrap/*.less'],
        tasks: 'css'
      }
    }
  });

  grunt.loadTasks('build/tasks');
  grunt.registerTask('default', 'lint recess concat min mincss enqueue_ver');
  grunt.registerTask('js', 'lint concat min enqueue_ver');
  grunt.registerTask('css', 'recess mincss enqueue_ver');
  grunt.loadNpmTasks('grunt-contrib');
  grunt.loadNpmTasks('grunt-recess');
};