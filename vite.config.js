import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue2'
import webpack from "webpack";

export default defineConfig({
    plugins: [
        new webpack.ProvidePlugin({
            $: "jquery",
            jQuery: "jquery"
        }),
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            vue: 'vue/dist/vue.esm-bundler.js',
            // this is required for the SCSS modules
            find: /^~(.*)$/,
            replacement: '$1',
        },
    },
    test: /\.s(c|a)ss$/,
    use: [
        'vue-style-loader',
        'css-loader',
        {
            loader: 'sass-loader',
            options: {
                implementation: require('sass'),
                sassOptions: {
                    indentedSyntax: true // optional
                },
            },
        },
    ],
});
