<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Sat, 12 Aug 2023 14:01:37 Malaysia Time, Pantai Lembeng, Bali
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup>

import { usePage } from "@inertiajs/vue3"

const header = usePage().props.structure.header;
const footer = usePage().props.structure.footer;
console.log(usePage().props)
</script>

<template>
    <div class="relative">
    <!-- <pre>{{ header }}</pre> -->
    <div v-html="header[0].html"></div>
        <!-- <section class="relative isolate overflow-hidden bg-gray-100"
            :class="[structure.layout.layout !== 'center' ? 'mx-auto max-w-5xl' : '']"
        >


            <slot />



        </section> -->
        <slot />
        <div v-html="footer[0].html"></div>
    </div>

    <!-- <Cookies /> -->
</template>

