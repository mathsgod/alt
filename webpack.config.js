//var path = require("path");
var webpack = require("webpack");
//const CopyWebpackPlugin = require('copy-webpack-plugin');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");

module.exports = {
    module: {
        rules: [
            {
                test: /\.less$/,
                use: [
                    MiniCssExtractPlugin.loader,
                    "css-loader",
                    "less-loader"
                ]
            },
            {
                test: /\.css$/,
                use: ['style-loader',
                    'css-loader'
                ]
            },
            {
                test: /\.woff($|\?)|\.woff2($|\?)|\.ttf($|\?)|\.eot($|\?)|\.svg($|\?)/,
                use: "url-loader"
            }]
    }, plugins: [
        new webpack.ProvidePlugin({
            $: "jquery",
            jQuery: "jquery"
        }),
        new MiniCssExtractPlugin({
            filename: "./css/[name].css"
        })
        /*,
        new CopyWebpackPlugin([{
            from: "node_modules/bootstrap/dist",
            to: "bootstrap"
        }])*/
    ],
    externals: {
        jqueryui: true,
        vue: true
    }


};