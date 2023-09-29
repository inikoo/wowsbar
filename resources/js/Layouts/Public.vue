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
import FooterThemeOne from '@/Components/Footer/Public/FooterThemeOne.vue'
import FooterThemeTwo from '@/Components/Footer/Public/FooterThemeTwo.vue'
import FooterThemeThree from '@/Components/Footer/Public/FooterThemeThree.vue'

const isTabActive = ref(false)

console.log(usePage().props.structure.header)
const props = defineProps([
    'layout',
    'structure'
])

// const footerLogo = usePage().props.art.footer_logo

const getHeaderComponent = computed(() => {
    const componentList = {
        'simpleSticky': HeaderThemeOne
    }

    return componentList[props.structure.header.type]
})

const getMenuComponent = computed(() => {
    const componentList = {
        'simple': MenuOne,
        'MenuTwo': MenuTwo
    }

    return componentList[props.layout?.menu?.component ?? 'MenuTwo']
})

// const getFooterComponent = computed(() => {
//     const componentList = {
//         'FooterThemeOne': FooterThemeOne,
//         'FooterThemeTwo': FooterThemeTwo,
//         'FooterThemeThree': FooterThemeThree,
//     }

//     return componentList[props.layout?.footer?.component ?? 'FooterThemeOne']
// })


</script>

<template>
    <div class="relative ">
        <section class="relative isolate overflow-hidden bg-gray-100"
            :class="[structure.layout.layout !== 'center' ? 'mx-auto max-w-5xl' : '']"
        >
            <!-- Header -->
            <!-- <pre>{{ structure.layout }}</pre> -->
            <component :is="getHeaderComponent" :data="structure.header"></component>

            <!-- Menu -->
            <!-- <component :is="getMenuComponent" :data="structure.header.menu"></component> -->




            <!-- Main content of page -->
            <slot />

            <!-- <component :is="getFooterComponent" :data="publicData.footer.data" />-->
        </section>
    </div>

    <!-- <Cookies /> -->
</template>

