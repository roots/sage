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
        '!assets/js/scripts.min.js',
      ]
    },
    less: {
      theme: {
        files: {
          'assets/css/main.min.css': [
            'assets/less/app.less'
          ]
        },
        options: {
          compress: true,
          sourceMap: true,
          sourceMapFilename: 'assets/css/main.min.css.map',
          sourceMapRootpath: '/wordpress/wp-content/themes/pages-theme-roots/'
        }
      }
    },
    uglify: {
      theme: {
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
          sourceMap: 'assets/js/scripts.min.js.map',
          sourceMapRoot: '/wordpress/wp-content/themes/pages-theme-roots/',
          sourceMappingURL: '/wordpress/wp-content/themes/pages-theme-roots/assets/js/scripts.min.js.map',
          report: 'min'
        }
      },
      buddypress: {
        files: {
          'buddypress/js/buddypress.js': [
          'buddypress/js/_buddypress.js'
          ]
        },
        options: {
          sourceMap: 'buddypress/js/buddypress.js.map',
          sourceMapRoot: '/wordpress/wp-content/themes/pages-theme-roots/',
          sourceMappingURL: '/wordpress/wp-content/themes/pages-theme-roots/buddypress/js/buddypress.js.map',
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
          'assets/less/bootstrap/*.less',
          'buddypress/css/*.less'
        ],
        tasks: ['less', 'version', 'rsync:test']
      },
      js: {
        files: [
          '<%= jshint.all %>',
          'buddypress/js/_buddypress.js'
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
                '<%= watch.php.files %>'
                ]
              }
            },
            clean: {
              dist: [
                'assets/css/main.min.css',
                'assets/css/main.min.css.map',
                'assets/js/scripts.min.js',
                'assets/js/scripts.min.js.map',
                'buddypress/js/buddypress.js.map',
                'buddypress/css/buddypress.css.map',
                'buddypress/css/buddypress-rtl.css.map',
                'dist'
              ]
            },
            rsync: {
              test: {
                src: "./",
                dest: "/var/www/wordpress/wp-content/themes/pages-theme-roots",
                host: "tobias@test",
                ssh: true,
                privatekey: "~/.ssh/id_rsa",
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
              },
              dist: {
                src: "./",
                dest: "dist",
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
                  'sftp-config.json',
                  '*.css.map',
                  '*.js.map',
                  'assets/less',
                  'assets/js/plugins',
                  'assets/js/_*.js',
                  'buddypress/js/_*.js',
                  'buddypress/css/_*.css',
                  'buddypress/css/*.less'
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
    grunt.loadNpmTasks("grunt-rsync");

    // Register tasks
    grunt.registerTask('default', [
      'clean',
      'less',
      'uglify',
      'version'
      ]);
    grunt.registerTask('test', [
      'default',
      'rsync:test'
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