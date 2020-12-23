const path         = require('path');
const webpack      = require('webpack');
const TerserPlugin = require('terser-webpack-plugin');

module.exports = {
    entry:        {
        index:    './assets/js/index.js',
        platform: './assets/js/platform.js',
    },
    plugins:      [
        new webpack.ProvidePlugin({
            $:      'jquery',
            jQuery: 'jquery',
        }),
    ],
    output:       {
        filename: '[name].bundle.js',
        path:     path.resolve(__dirname, 'public/build'),
    },
    optimization: {
        minimize:  true,
        minimizer: [
            new TerserPlugin({
                test:    /\.js(\?.*)?$/i,
                exclude: /node_modules/,
            }),
        ],
    },
    module:       {
        rules: [
            {
                test:    /\.(js)$/,
                exclude: /node_modules/,
                use:     ['babel-loader'],
            },
            {
                test: /\.css$/i,
                use:  ['style-loader', 'css-loader'],
            },
            {
                test: /\.(scss)$/,
                use:  [
                    {
                        loader: 'style-loader',
                    }, {
                        loader: 'css-loader',
                    }, {
                        loader:  'postcss-loader',
                        options: {
                            plugins: function() {
                                return [
                                    require('precss'),
                                    require('autoprefixer'),
                                ];
                            },
                        },
                    }, {
                        loader: 'sass-loader',
                    },
                ],
            },
        ],
    },
    resolve: {
        extensions: ['*', '.js'],
    },
};