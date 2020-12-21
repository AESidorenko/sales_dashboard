const path    = require('path');
const webpack = require('webpack');

module.exports = {
    entry:   {
        index: './assets/js/index.js',
    },
    plugins: [
        new webpack.ProvidePlugin({
            $:      'jquery',
            jQuery: 'jquery',
        }),
    ],
    output:  {
        filename: '[name].bundle.js',
        path:     path.resolve(__dirname, 'public/build'),
    },
    module:  {
        rules: [
            {
                test: /\.css$/i,
                use:  ['style-loader', 'css-loader'],
            },
        ],
    },
};