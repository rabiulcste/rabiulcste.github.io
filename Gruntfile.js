module.exports = function (grunt) {

    grunt.initConfig({
        cssmin: {
            target: {
                files: [{
                    expand: true,
                    cwd: 'css',
                    src: ['*.css', '!*.min.css'],
                    dest: 'css',
                    ext: '.min.css'
                }]
            }
        },
        uglify: {
            site: {
                src: 'js/site.js',
                dest: 'js/site.min.js'
            }
        },
        watch: {
            css: {
                files: ['css/**/*.css', '!css/**/*.min.css'],
                tasks: ['cssmin']
            },
            js: {
                files: ['js/site.js'],
                tasks: ['uglify']
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-watch');

    grunt.registerTask('default', ['cssmin', 'uglify']);

};
