'use strict';
module.exports = function(grunt) {

  grunt.initConfig({
    jshint: {
      options: {
        jshintrc: '.jshintrc'
      },
      all: [
        'Gruntfile.js',
        'assets/js/**/*.js',
        'assets/js/plugins/**/*.js',
        '!assets/build/app.min.js'
      ]
    },
    sass: {
      dist: {
        options: {
          style: 'compressed',
          compass: false,
          sourcemap: false
        },
        files: {
          'assets/build/app.min.css': [
              'assets/sass/app.scss'
          ],
          'common/css/global.css': [
            'common/css/global.scss'
        ]
        ,
          'admin/css/repeating-fields.css': [
            'admin/css/repeating-fields.scss'
        ]
        ,
          'admin/css/widgets.css': [
            'admin/css/widgets.scss'
        ]
        }
      }
    },
    uglify: {
      dist: {
        files: {
          'assets/build/app.min.js': ['!assets/js/plugins/alpha-color-picker/*.js','assets/js/plugins/slick/*.js','assets/js/plugins/magnific/*.js','assets/js/plugins/mmenu/jquery.mmenu.oncanvas.js','assets/js/plugins/mmenu/jquery.mmenu.offcanvas.js','assets/js/plugins/mmenu/jquery.mmenu.autoheight.js','assets/js/app.js']
          
        },
        options: {
          sourceMap: 'assets/build/app.min.js.map',
          sourceMappingURL: '/assets/build/app.min.js.map'
        }
      }
    },
    watch: {
      options: {
        livereload: true
      },
      sass: {
        files: [
          'assets/sass/**/*.scss', 'admin/css/**/*.scss', 'common/css/**/*.scss'
        ],
        tasks: ['sass']
      },
      js: {
        files: [
          'assets/js/plugins/**/*.js',
          'assets/js/**/*.js'
        ],
        tasks: ['uglify']
      },
      html: {
        files: [
          '**/*.html'
        ]
      }
    },
    clean: {
      dist: [
        'assets/build/app.min.css',
        'assets/build/app.min.js'
      ]
    }
  });

  // Load tasks
  grunt.loadNpmTasks('grunt-contrib-clean');
  grunt.loadNpmTasks('grunt-contrib-jshint');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-sass');

  // Register tasks
  grunt.registerTask('default', [
    'clean',
    'sass',
    'uglify'
  ]);
  grunt.registerTask('dev', [
    'watch'
  ]);

};