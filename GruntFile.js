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
                integration_center_admin: {
                    options: {
                        sourceMap: false,
                        mangle: {
                            reserved: ['__', '_x']
                        },
                        sourceMapName: 'sourceMap.map'
                    },
                    src: [
                        'src/admin/scripts/hold.js',
                        'src/admin/scripts/components/add-field-modal.js',
                        'src/admin/scripts/components/copy-to-clipboard.js',
                        'src/admin/scripts/components/cp-configuration-modal.js',
                        'src/admin/scripts/components/delete-settings.js',
                        'src/admin/scripts/components/select2.js',
                    ],
                    dest: 'assets/js/admin.min.js'
                },
                integration_center_front: {
                    options: {
                        sourceMap: false,
                        mangle: {
                            reserved: ['__', '_x']
                        },
                        sourceMapName: 'sourceMap.map'
                    },
                    src: [
                        'src/front/scripts/components/intro.js',
                        'src/front/scripts/components/tooltips.js'
                    ],
                    dest: 'assets/js/front.min.js'
                }
            }
        });

    // Load the plugin that provides the "uglify" task.
    grunt.loadNpmTasks('grunt-contrib-uglify');

    grunt.registerTask('default', 'uglify');
};
