var webpack = require("webpack");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const CopyWebpackPlugin = require('copy-webpack-plugin');
const CleanWebpackPlugin = require('clean-webpack-plugin');

module.exports = {
    mode: "production",
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
        //new CleanWebpackPlugin(['dist']),
        new webpack.ProvidePlugin({
            $: "jquery",
            jQuery: "jquery"
        }),
        new MiniCssExtractPlugin({
            filename: "./css/[name].css"
        }),
        new CopyWebpackPlugin([
            {
                from: "node_modules/datatables-all/media",
                to: "datatables",
                toType: "dir"
            }, {
                from: "node_modules/bootstrap/dist",
                to: "bootstrap",
                toType: "dir"
            }, {
                from: "node_modules/fullcalendar/dist",
                to: "fullcalendar",
                toType: "dir"
            }, {
                from: "node_modules/select2/dist",
                to: "select2",
                toType: "dir"
            }, {
                from: "node_modules/bootstrap-select/dist",
                to: "bootstrap-select",
                toType: "dir"
            }, {
                from: "node_modules/ionicons/dist",
                to: "ionicons",
                toType: "dir"
            }, {
                from: "node_modules/pnotify/dist",
                to: "pnotify",
                toType: "dir"
            }, {
                from: "node_modules/x-editable/dist/bootstrap3-editable",
                to: "bootstrap3-editable",
                toType: "dir"
            }, {
                from: "node_modules/x-html/dist",
                to: "x-html",
                toType: "dir"
            }, {
                from: "plugins/alt-component/dist",
                to: "alt-component",
                toType: "dir"
            }, {
                from: "node_modules/@fancyapps/fancybox/dist",
                to: "fancybox",
                toType: "dir"
            }, {
                from: "node_modules/vue-bs/dist",
                to: "vue-bs",
                toType: "dir"
            }, {
                from: "node_modules/bootstrap-multiselect/dist",
                to: "bootstrap-multiselect",
                toType: "dir"
            }, {
                from: "node_modules/bootstrap-datepicker/dist",
                to: "bootstrap-datepicker",
                toType: "dir"
            }

        ])
    ]


};