/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 12 Sep 2023 20:46:11 Malaysia Time, Sanur, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import i18n from 'laravel-vue-i18n/vite';
import inertia from './resources/scripts/vite/inertia-layout';

export default defineConfig(
    {
        plugins: [
            inertia(),
            laravel({
                        hotFile: 'public/public.hot',
                        buildDirectory: 'public',
                        input  : 'resources/js/app-public.js',
                        ssr    : 'resources/js/ssr-public.js',
                        refresh: true,
                    }),
            vue({
                    template: {
                        transformAssetUrls: {
                            base           : null,
                            includeAbsolute: false,
                        },
                    },
                }),
            i18n(),
        ],
        build  : {
            rollupOptions: {
                output: {
                    manualChunks(id) {
                        if (id.includes('node_modules')) {
                            return id.toString().
                                split('node_modules/')[1].split(
                                '/')[0].toString();
                        }
                    },
                },
            },
        },
    });