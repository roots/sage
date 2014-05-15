'use strict';
module.exports = function(grunt) {

  grunt.initConfig({
    // Metadata.
    pkg: grunt.file.readJSON('package.json'),
	// setting folder templates
	dirs: {
		css: 'assets/css',
		fonts: 'assets/fonts',
		images: 'assets/img',
		js: 'assets/js',
		less: 'assets/less',
		vendor: 'assets/vendor'
	},
    jshint: {
      options: {
        jshintrc: '.jshintrc'
      },
      all: [
        'Gruntfile.js',
        '<%= dirs.js %>/*.js',
        '!<%= dirs.js %>/scripts.min.js'
      ]
    },
    less: {
      dist: {
        files: {
          '<%= dirs.css %>/main.min.css': [
            '<%= dirs.less %>/app.less'
          ]
        },
        options: {
	      paths: [
			'<%= dirs.less %>',
			'<%= dirs.vendor %>'
		  ],
          compress: true,
		  cleancss: false,
		  ieCompat: true,
          // LESS source map
          // To enable, set sourceMap to true and update sourceMapRootpath based on your install
          sourceMap: false,
          sourceMapFilename: '<%= dirs.css %>/main.min.css.map',
          sourceMapRootpath: '/app/themes/<%= pkg.name %>/'
        }
      }
    },
    uglify: {
      dist: {
        files: {
          '<%= dirs.js %>/scripts.min.js': [
            '<%= dirs.vendor %>/bootstrap/js/transition.js',
            '<%= dirs.vendor %>/bootstrap/js/alert.js',
            '<%= dirs.vendor %>/bootstrap/js/button.js',
            '<%= dirs.vendor %>/bootstrap/js/carousel.js',
            '<%= dirs.vendor %>/bootstrap/js/collapse.js',
            '<%= dirs.vendor %>/bootstrap/js/dropdown.js',
            '<%= dirs.vendor %>/bootstrap/js/modal.js',
            '<%= dirs.vendor %>/bootstrap/js/tooltip.js',
            '<%= dirs.vendor %>/bootstrap/js/popover.js',
            '<%= dirs.vendor %>/bootstrap/js/scrollspy.js',
            '<%= dirs.vendor %>/bootstrap/js/tab.js',
            '<%= dirs.vendor %>/bootstrap/js/affix.js',
            '<%= dirs.js %>/plugins/*.js',
            '<%= dirs.js %>/_*.js'
          ]
        },
        options: {
          sourceMap: false
        }
      }
    },
    version: {
      options: {
        file: 'lib/scripts.php',
        css: '<%= dirs.css %>/main.min.css',
        cssHandle: '<%= pkg.name %>_main',
        js: '<%= dirs.js %>/scripts.min.js',
        jsHandle: '<%= pkg.name %>_scripts'
      }
    },
    watch: {
      less: {
        files: [
          '<%= dirs.less %>/*.less',
          '<%= dirs.less %>/bootstrap/*.less'
        ],
        tasks: ['less', 'version']
      },
      js: {
        files: [
          '<%= jshint.all %>'
        ],
        tasks: ['jshint', 'uglify', 'version']
      },
      livereload: {
        // Browser live reloading
        // https://github.com/gruntjs/grunt-contrib-watch#live-reloading
        options: {
          livereload: false
        },
        files: [
          '<%= dirs.css %>/main.min.css',
          '<%= dirs.js %>/scripts.min.js',
          'templates/*.php',
          '*.php'
        ]
      }
    },
    clean: {
      dist: [
        '<%= dirs.css %>/main.min.css',
        '<%= dirs.js %>/scripts.min.js'
      ]
    }
  });

  // Load tasks
  grunt.loadNpmTasks('grunt-contrib-clean');
  grunt.loadNpmTasks('grunt-contrib-jshint');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-less');
  grunt.loadNpmTasks('grunt-wp-version');

  // Register tasks
  grunt.registerTask('default', [
    'clean',
    'less',
    'uglify',
    'version'
  ]);
  grunt.registerTask('dev', [
    'watch'
  ]);

};
