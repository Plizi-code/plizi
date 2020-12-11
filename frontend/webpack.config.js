const path = require('path');
const VueLoaderPlugin = require('vue-loader/lib/plugin');

const plzPublicDir = './public/js/';
const plzPublicPath = path.resolve(__dirname, plzPublicDir);
const contentPath = path.resolve(__dirname, 'public');

module.exports = {

    devServer: {
        contentBase: contentPath,
        port: 8080,
        historyApiFallback: true
    },

    context: __dirname,
    entry: './src/main.js',
    output: {
        path: plzPublicPath,
        publicPath: 'public/js/',
        filename: 'app.js'
    },
    module: {
        rules: [
            {
                test: /\.vue$/,
                loader: 'vue-loader'
            },

            // TODO: @TGA подключить Babel
            // @link https://webpack.js.org/loaders/babel-loader/
            {
                test: /\.css$/,
                use: [
                    'vue-style-loader',
                    'css-loader',
                ]
            },
            {
                test: /\.scss$/,
                use: [
                    'vue-style-loader',
                    'css-loader',
                    'sass-loader',
                ]
            },
            {
                // TODO: @TGA разобраться, почему Vue "падал" на файлах с такими расширениями
                test: /\.(png|svg|jpg|jpeg)$/,
                use: 'vue-loader'
            }
        ]
    },
    mode: 'development',
    devtool: 'source-map',
    stats: {
        colors: true
    },
    plugins: [
        new VueLoaderPlugin()
    ]
};
