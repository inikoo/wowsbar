<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Sat, 12 Aug 2023 14:01:37 Malaysia Time, Pantai Lembeng, Bali
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup>
import { usePage } from '@inertiajs/vue3'
import { ref, computed } from 'vue'



import HeaderThemeOne from '@/Components/Header/Public/HeaderThemeOne.vue'
import MenuOne from '@/Components/Menu/Public/MenuOne.vue'
import MenuTwo from '@/Components/Menu/Public/MenuTwo.vue'
import FooterOne from '@/Components/Public/Footer/FooterOne.vue'
import FooterThemeTwo from '@/Components/Footer/Public/FooterThemeTwo.vue'

const isTabActive = ref(false)

const props = defineProps([
    'layout',
    'structure'
])
console.log(props.structure.footer)

// const footerLogo = usePage().props.art.footer_logo

const getHeaderComponent = computed(() => {
    const componentList = {
        'simpleSticky': HeaderThemeOne
    }

    return componentList[props.structure.header.type]
})

// const getMenuComponent = computed(() => {
//     const componentList = {
//         'simple': MenuOne,
//         'MenuTwo': MenuTwo
//     }

//     return componentList[props.layout?.menu?.component ?? 'MenuTwo']
// })

const getFooterComponent = computed(() => {
    const componentList = {
        'simple': FooterOne,
        'FooterThemeTwo': FooterThemeTwo,
    }

    return componentList[props.structure?.footer?.type ?? 'simple']
})


</script>

<template>
    <div class="relative ">
        <section class="relative isolate overflow-hidden bg-gray-100"
            :class="[structure.layout.layout !== 'center' ? 'mx-auto max-w-5xl' : '']"
        >
            <!-- Header -->
            <!-- <pre>{{ structure }}</pre> -->
            <component :is="getHeaderComponent" :data="structure.header"></component>

            <!-- Menu -->
            <!-- <component :is="getMenuComponent" :data="structure.header.menu"></component> -->

            <slot />

            <!-- Footer -->
            <component :is="getFooterComponent" :dataFooter="structure.footer" :dataLayout="structure.layout" />
        </section>
    </div>

    <!-- <Cookies /> -->
</template>

