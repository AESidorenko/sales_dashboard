const path = require('path');

module.exports = {
    entry:  {
        index: './assets/js/index.js',
        print: './assets/js/index.js',
    },
    output: {
        filename: '[name].bundle.js',
        path:     path.resolve(__dirname, 'public/build'),
    },
    module: {
        rules: [
            {
                test: /\.css$/i,
                use:  ['style-loader', 'css-loader'],
            },
        ],
    },
};