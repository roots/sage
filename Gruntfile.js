'use strict';
module.exports = function(grunt) {

  grunt.initConfig({
    jshint: {
      options: {
        jshintrc: '.jshintrc'
      },
      all: [
        'Gruntfile.js',
        'assets/js/*.js',
        '!assets/js/scripts.min.js'
      ]
    },
    less: {
      dist: {
        files: {
          'assets/css/main.min.css': [
            'assets/less/app.less'
          ]
        },
        options: {
          compress: true,
          // LESS source map
          // To enable, set sourceMap to true and update sourceMapRootpath based on your install
          sourceMap: true,
          sourceMapFilename: 'assets/css/main.min.css.map',
          sourceMapRootpath: '/wordpress/wp-content/themes/pages-theme-roots/'
        }
      }
    },
    uglify: {
      dist: {
        files: {
          'assets/js/scripts.min.js': [
            'assets/js/plugins/bootstrap/transition.js',
            'assets/js/plugins/bootstrap/alert.js',
            'assets/js/plugins/bootstrap/button.js',
            'assets/js/plugins/bootstrap/carousel.js',
            'assets/js/plugins/bootstrap/collapse.js',
            'assets/js/plugins/bootstrap/dropdown.js',
            'assets/js/plugins/bootstrap/modal.js',
            'assets/js/plugins/bootstrap/tooltip.js',
            'assets/js/plugins/bootstrap/popover.js',
            'assets/js/plugins/bootstrap/scrollspy.js',
            'assets/js/plugins/bootstrap/tab.js',
            'assets/js/plugins/bootstrap/affix.js',
            'assets/js/plugins/*.js',
            'assets/js/_*.js'
          ]
        },
        options: {
          // JS source map: to enable, uncomment the lines below and update sourceMappingURL based on your install
          sourceMap: 'assets/js/scripts.min.js.map',
          sourceMapRoot: '/wordpress/wp-content/themes/pages-theme-roots/',
          sourceMappingURL: '/wordpress/wp-content/themes/pages-theme-roots/assets/js/scripts.min.js.map',
          report: 'min'
        }
      }
    },
    version: {
      options: {
        file: 'lib/scripts.php',
        css: 'assets/css/main.min.css',
        cssHandle: 'roots_main',
        js: 'assets/js/scripts.min.js',
        jsHandle: 'roots_scripts'
      }
    },
    watch: {
      less: {
        files: [
          'assets/less/*.less',
          'assets/less/bootstrap/*.less'
        ],
        tasks: ['less', 'version', 'rsync:test']
      },
      js: {
        files: [
          '<%= jshint.all %>'
        ],
        tasks: ['jshint', 'uglify', 'version', 'rsync:test']
      },
      php: {
        files: [
          '*.php',
          'lib/*.php',
          'templates/*.php',
          'buddypress/*.php',
          'buddypress/**/*.php'
        ],
        tasks: ['rsync:test']
      },
      livereload: {
        // Browser live reloading
        // https://github.com/gruntjs/grunt-contrib-watch#live-reloading
        options: {
          livereload: false
        },
        files: [
          'assets/css/main.min.css',
          'assets/js/scripts.min.js',
          'templates/*.php',
          '*.php'
        ]
      }
    },
    clean: {
      dist: [
        'assets/css/main.min.css',
        'assets/js/scripts.min.js',
        'assets/css/main.min.css.map',
        'assets/js/scripts.min.js.map'
      ]
    },
    rsync: {
      test: {
        src: './',
        dest: '/var/www/wordpress/wp-content/themes/pages-theme-roots',
        host: 'tobias@test',
        ssh: true,
        privatekey: '~/.ssh/id_rsa',
        recursive: true,
        syncDest: true,
        exclude: [
          '.git*',
          'node_modules',
          'Gruntfile.js',
          'package.json',
          '.DS_Store',
          '.editorconfig',
          'README.md',
          'CHANGELOG.md',
          'CONTRIBUTING.md',
          'LICENSE.md',
          'LICENSE.txt',
          'config.rb',
          '.jshintrc',
          '*.tmproj',
          '*.sublime-project',
          'ftpsync.settings',
          '.ftppass',
          'sftp-config.json'
        ]
      }
    },
    'ftp-deploy': {
      staging: {
        auth: {
          host: 'pages-tdm-test.au.dk',
          port: 21,
          authKey: 'key1'
        },
        src: './',
        dest: '/wp-content/themes/pages-theme-roots',
        exclusions: [
        '<%= rsync.test.exclude %>'
        ]
      },
      production: {
        auth: {
          host: 'pages-tdm.au.dk',
          port: 21,
          authKey: 'key1'
        },
        src: './',
        dest: '/wp-content/themes/pages-theme-roots',
        exclusions: [
        '<%= rsync.test.exclude %>'
        ]
      }
    }
  });

  // Load tasks
  grunt.loadNpmTasks('grunt-contrib-clean');
  grunt.loadNpmTasks('grunt-contrib-jshint');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-less');
  grunt.loadNpmTasks('grunt-wp-version');
  grunt.loadNpmTasks('grunt-ftp-deploy');
  grunt.loadNpmTasks('grunt-rsync');
  
  // Register tasks
  grunt.registerTask('default', [
    'clean',
    'less',
    'uglify',
    'version'
  ]);
  grunt.registerTask('dev', [
    'default',
    'rsync:test',
    'watch'
  ]);
  grunt.registerTask('staging', [
    'default',
    'ftp-deploy:staging'
  ]);
  grunt.registerTask('dist', [
    'default',
    'rsync:dist'
  ]);

};