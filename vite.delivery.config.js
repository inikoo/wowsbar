/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 24 Aug 2023 09:33:07 Malaysia Time, Kuala Lumpur, Malaysia
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
                        hotFile: 'public/delivery.hot',
                        buildDirectory: 'delivery',
                        input  : 'resources/js/app-delivery.js',
                        ssr    : 'resources/js/ssr-delivery.js',
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
