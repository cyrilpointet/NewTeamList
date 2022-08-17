import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import eslintPlugin from 'vite-plugin-eslint';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    server: {
        //https: true,
        host: '0.0.0.0'
    },
    plugins: [
        laravel({
            input: ['resources/ts/app.tsx'],
            refresh: true,
        }),
        eslintPlugin(),
        vue({
            template: {
                transformAssetUrls: {
                    // The Vue plugin will re-write asset URLs, when referenced
                    // in Single File Components, to point to the Laravel web
                    // server. Setting this to `null` allows the Laravel plugin
                    // to instead re-write asset URLs to point to the Vite
                    // server instead.
                    base: null,

                    // The Vue plugin will parse absolute URLs and treat them
                    // as absolute paths to files on disk. Setting this to
                    // `false` will leave absolute URLs un-touched so they can
                    // reference assets in the public directly as expected.
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            '@': '/resources/ts',
            '@common': '/resources/components/common'
        },
    },
});
