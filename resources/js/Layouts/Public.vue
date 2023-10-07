<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Sat, 12 Aug 2023 14:01:37 Malaysia Time, Pantai Lembeng, Bali
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup>

import { usePage } from "@inertiajs/vue3"
import { onMounted } from 'vue';
import { loadCss } from '@/Composables/loadCss';

const header = usePage().props.structure.header;
const footer = usePage().props.structure.footer;

console.log(header,footer)
let dynamicClasses = {
    header: '',
    footer: ''
};

onMounted(() => {
    const css = {
        header: header[0] ? loadCss(header[0].css) : [],
        footer: footer[0] ? loadCss(footer[0].css) : []
    };

    for (const selector in css) {
        for (const property in css[selector]) {
            let classString = '';
            for (const c in css[selector][property]) {
                classString += `${c}: ${css[selector][property][c]};`;
            }
            dynamicClasses[selector] += `${selector} { ${classString} } `;
        }
    }

    const styleElement = document.createElement('style');
    styleElement.textContent = dynamicClasses.header + dynamicClasses.footer;
    document.head.appendChild(styleElement);
});

</script>

<template>
    <div class="relative">
        <div v-html="header[0]?.html" :class="dynamicClasses.header"></div>
        <slot />
        <div v-html="footer[0]?.html" :class="dynamicClasses.footer"></div>
    </div>
</template>

