/**
 *
 *
 *
 * @author: Luca Cannarozzo
 * Date: 24/07/2018
 */

module.exports = function(grunt)
{
    require('load-grunt-tasks')(grunt);
    grunt.initConfig(
        {
            // read the package.json file so we know what packages we have
            pkg: grunt.file.readJSON('package.json'),
            
            // config options used in the uglify task to minify scripts
            uglify: {
                integration_center_csv: {
                    options: {
                        sourceMap: false,
                        mangle: {
                            reserved: ['__', '_x']
                        },
                        sourceMapName: 'sourceMap.map'
                    },
                    src: [
                        'admin/assets/scripts/admin.js',
                        'admin/assets/scripts/methods.js'
                    ],
                    dest: 'admin/assets/scripts/dist/appq-integration-center-csv-addon.min.js'
                }
            }
        });

    // Load the plugin that provides the "uglify" task.
    grunt.loadNpmTasks('grunt-contrib-uglify');

    grunt.registerTask('default', 'uglify');
};
