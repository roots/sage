var shell = require('shelljs');

module.exports = function(grunt) {

  // Project configuration.
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    po2mo: {
      files: {
        src: 'ReduxCore/languages/*.po',
        expand: true,
      },
    },
    concat: {
		options: {
        	separator: ';'
      	},
    	core: {  	
        	src: [ 
				    'ReduxCore/assets/js/vendor/cookie.js',
				    'ReduxCore/assets/js/vendor/jquery.tipsy.js',
				    'ReduxCore/assets/js/vendor/jquery.typewatch.js',
				    'ReduxCore/assets/js/vendor/spinner_custom.js',
				    'ReduxCore/assets/js/vendor/jquery.alphanum.js',
            'ReduxCore/assets/js/vendor/select2.sortable.js',
            'ReduxCore/assets/js/vendor/minicolors/jquery.minicolors.js',
				    'ReduxCore/inc/fields/**/*.js',
            'ReduxCore/extensions/**/*.js',
				    'ReduxCore/assets/js/redux.js', 
        	],
        	dest: 'ReduxCore/assets/js/redux.min.js'
    	},
    	vendor: {
        	src: [ 
    				'ReduxCore/assets/js/vendor/cookie.js',
    				'ReduxCore/assets/js/vendor/jquery.tipsy.js',
    				'ReduxCore/assets/js/vendor/jquery.typewatch.js',
    				'ReduxCore/assets/js/vendor/spinner_custom.js',
    				'ReduxCore/assets/js/vendor/jquery.alphanum.js',
            'ReduxCore/assets/js/vendor/select2.sortable.js',
        	],
        	dest: 'ReduxCore/assets/js/vendor.min.js'
    	}
    },
    'gh-pages': {
      options: {
        base: 'docs',
        message: 'Update docs and files to distribute'
      },
      dev: {
        src: ['docs/**/*', 'bin/CNAME']
      },
      travis: {
        options: {
          repo: 'https://' + process.env.GH_TOKEN + '@github.com/ReduxFramework/docs.reduxframework.com.git',
          user: {
            name: 'Travis',
            email: 'travis@travis-ci.org'
          },
          silent: false
        },
        src: ['**/*']
      }
    },       
    uglify: {
      	core: {
  			  options: {
  				  banner: '/*! <%= pkg.name %> - v<%= pkg.version %> - ' +
  				  '<%= grunt.template.today("yyyy-mm-dd") %> */\n',
  			  },
  			  files: {
  		  		'ReduxCore/assets/js/redux.min.js': ['ReduxCore/assets/js/redux.min.js']
  			  }      	
      	},
	  	  extensions: {  
  			files: [{
  				expand: true,
  				cwd: 'ReduxCore/extensions',
  				src: '**/*.js',
  				ext: '.min.js',
  				dest: 'ReduxCore/extensions'
  			}]					   	
      },     
	  	vendor: {  
    			files: {
    		  		'ReduxCore/assets/js/vendor.min.js': ['ReduxCore/assets/js/vendor.min.js']
    			}  				   	
      	},      	 
    },    
    qunit: {
      files: ['test/qunit/**/*.html']
    },
    jshint: {
      files: [ 
      /* // for testing individually
        'ReduxCore/inc/fields/ace_editor/*.js',
        'ReduxCore/inc/fields/border/*.js',
        'ReduxCore/inc/fields/button_set/*.js',
        'ReduxCore/inc/fields/checkbox/*.js',
        'ReduxCore/inc/fields/color/*.js',
        'ReduxCore/inc/fields/color_gradient/*.js',
        'ReduxCore/inc/fields/date/*.js',
        'ReduxCore/inc/fields/dimensions/*.js',
        'ReduxCore/inc/fields/divide/*.js',
        'ReduxCore/inc/fields/editor/*.js',
        'ReduxCore/inc/fields/gallery/*.js',
        'ReduxCore/inc/fields/group/*.js',
        'ReduxCore/inc/fields/image_select/*.js',
        'ReduxCore/inc/fields/info/*.js',
        'ReduxCore/inc/fields/link_color/*.js',
        'ReduxCore/inc/fields/media/*.js',
        'ReduxCore/inc/fields/multi_text/*.js',
        'ReduxCore/inc/fields/password/*.js',
        'ReduxCore/inc/fields/radio/*.js',
        'ReduxCore/inc/fields/raw/*.js',
        'ReduxCore/inc/fields/raw_align/*.js',
        'ReduxCore/inc/fields/select/*.js',
        'ReduxCore/inc/fields/slider/*.js',
        'ReduxCore/inc/fields/slides/*.js',
        'ReduxCore/inc/fields/sortable/*.js',
        'ReduxCore/inc/fields/sorter/*.js',
        'ReduxCore/inc/fields/spacing/*.js',
        'ReduxCore/inc/fields/spinner/*.js',
        'ReduxCore/inc/fields/switch/*.js',
        'ReduxCore/inc/fields/text/*.js',
        'ReduxCore/inc/fields/textarea/*.js',
        'ReduxCore/inc/fields/typography/*.js',
      */
        'ReduxCore/inc/fields/**/*.js',
        'ReduxCore/extensions/**/*.js',
        'ReduxCore/assets/js/redux.js'
      ],
      options: {
        expr: true,
        // options here to override JSHint defaults
        globals: {
          jQuery: true,
          console: true,
          redux_change: true,
          module: true,
          document: true,
        }
      }
    },
    watch: {
      ui: {
        files: ['<%= jshint.files %>'],
        tasks: ['jshint']  
      },
      php: {
        files: ['ReduxCore/**/*.php'],
        tasks: ['phplint:core']  
      },
      css: {
        files: ['ReduxCore/**/*.less'],
        tasks: ['less:development']
      }
    },
    phpdocumentor: {
      options : {
        directory : 'ReduxCore/',
        target : 'docs/'
      }, 
      generate : {}
    },
    phplint: {
        options: {
            swapPath: "./"
        },

        core: ["ReduxCore/**/*.php"],
        plugin: ["class-redux-plugin.php", "index.php", "redux-framework.php"],
    },
    less: {
        development: {
			   options: {
            	paths: 'ReduxCore/',
    		},        	
            files: [{
                expand: true,        // Enable dynamic expansion.
                cwd: 'ReduxCore/inc/fields',  // Src matches are relative to this path.
                src: ['**/*.less'],     // Actual pattern(s) to match.
                dest: 'ReduxCore/inc/fields',  // Destination path prefix.
                ext: '.css',         // Dest filepaths will have this extension.
            }]
        },
        extensions: {
            files: [{
                expand: true,        // Enable dynamic expansion.
                cwd: 'ReduxCore/extensions/',  // Src matches are relative to this path.
                src: ['**/*.less'],     // Actual pattern(s) to match.
                dest: 'ReduxCore/extensions/',  // Destination path prefix.
                ext: '.css',         // Dest filepaths will have this extension.
            }]
        },        
        production: {
        	options: {
      			compress : true,
            	cleancss : true,
            	ieCompat : true,
            	relativeUrls : true,
            	paths: 'ReduxCore/',
    		  },
  		    files: {
  		      "ReduxCore/assets/css/redux.css": ["ReduxCore/inc/fields/**/*.less", "ReduxCore/extensions/**/*.less", "ReduxCore/assets/css/admin.less"],
  		      "ReduxCore/assets/css/admin.css": ["ReduxCore/assets/css/admin.less"],

  		    }
        },
        dist: {
          options: {
            compress : true,
              cleancss : true,
              ieCompat : true,
              relativeUrls : true,
              report: 'gzip',
              paths: 'ReduxCore/',
          },
          files: {
            "ReduxCore/assets/css/redux.css": ["ReduxCore/inc/fields/**/*.less", "ReduxCore/extensions/**/*.less", "ReduxCore/assets/css/admin.less"],
            "ReduxCore/assets/css/admin.css": ["ReduxCore/assets/css/admin.less"],

          }
        }               
    },
  });

  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-jshint');
  grunt.loadNpmTasks('grunt-contrib-qunit');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-phpdocumentor');
  grunt.loadNpmTasks('grunt-gh-pages');
  grunt.loadNpmTasks("grunt-phplint");
  grunt.loadNpmTasks('grunt-contrib-less');
  grunt.loadNpmTasks('grunt-po2mo');

  grunt.registerTask('langUpdate', "Update languages", function() {
    shell.exec('tx pull -a');
    shell.exec('grunt po2mo');
    shell.exec('php bin/makepot/gen.php');
  });

  // Default task(s).
  grunt.registerTask('default', ['jshint', 'concat:core', 'uglify:core', 'concat:vendor', 'uglify:vendor', "less:production", "less:development", "less:extensions"]);
  grunt.registerTask('travis', ['jshint', 'lintPHP']);

  // this would be run by typing "grunt test" on the command line
  grunt.registerTask('testJS', ['jshint', 'concat:core', 'concat:vendor']);  

  grunt.registerTask('watchUI', ['watch:ui']);
  grunt.registerTask('watchPHP', ['watch:php', 'phplint:core', 'phplint:plugin']);

  grunt.registerTask("lintPHP", ["phplint:plugin", "phplint:core"]);
  grunt.registerTask("compileCSS", ["less:production", "less:development", "less:extensions"]);
  grunt.registerTask('compileJS', ['jshint', 'concat:core', 'uglify:core', 'concat:vendor', 'uglify:vendor']);
  grunt.registerTask('compileTestJS', ['jshint', 'concat:core', 'concat:vendor']);

};
