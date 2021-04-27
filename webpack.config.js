const path = require('path');
const MonacoWebpackPlugin = require('monaco-editor-webpack-plugin');

module.exports = {
    resolve: {
        alias: {
            '@': path.resolve('resources/js'),
        },
    },
    devServer: {
        host: '0.0.0.0',
        port: 8080,
    },
    plugins: [
        new MonacoWebpackPlugin({
            languages: [
                'json'
            ]
        })
    ]
};
