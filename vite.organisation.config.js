/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 04 Sep 2023 12:16:07 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import i18n from 'laravel-vue-i18n/vite';
import inertia from './resources/scripts/vite/inertia-layout';
import {fileURLToPath, URL} from 'node:url';

export default defineConfig(
    {
      plugins: [
        inertia(),
        laravel({
                  hotFile       : 'public/organisation.hot',
                  buildDirectory: 'organisation',
                  input         : 'resources/js/app-organisation.js',
                  ssr           : 'resources/js/ssr-organisation.js',
                  refresh       : true,
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

      resolve: {
        alias: {
          '@fad': fileURLToPath(
              new URL('./private/fa/pro-duotone-svg-icons', import.meta.url)),
          '@fal': fileURLToPath(
              new URL('./private/fa/pro-light-svg-icons', import.meta.url)),
          '@far': fileURLToPath(
              new URL('./private/fa/pro-regular-svg-icons', import.meta.url)),
          '@fas': fileURLToPath(
              new URL('./private/fa/pro-solid-svg-icons', import.meta.url)),
        },
      },
    });
