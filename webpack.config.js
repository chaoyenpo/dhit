const path = require('path');
const MonacoWebpackPlugin = require('monaco-editor-webpack-plugin');

module.exports = {
    resolve: {
        alias: {
            '@': path.resolve('resources/js'),
        },
    },
    plugins: [
        new MonacoWebpackPlugin({
            languages: [
                'json'
            ]
        })
    ]
};
