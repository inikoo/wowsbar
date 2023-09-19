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

/*
const props = defineProps([
    'layout'
])

const footerLogo = usePage().props.art.footer_logo

const getHeaderComponent = computed(() => {
    const componentList = {
        'HeaderThemeOne': HeaderThemeOne
    }

    return componentList[props.layout.header.component]
})

const getMenuComponent = computed(() => {
    const componentList = {
        'MenuOne': MenuOne,
        'MenuTwo': MenuTwo
    }

    return componentList[props.layout?.menu?.component ?? 'MenuTwo']
})

const getFooterComponent = computed(() => {
    const componentList = {
        'FooterThemeOne': FooterThemeOne,
        'FooterThemeTwo': FooterThemeTwo,
        'FooterThemeThree': FooterThemeThree,
    }

    return componentList[props.layout?.footer?.component ?? 'FooterThemeOne']
})

*/


</script>

<template>
    <div class="relative ">
        <section class="relative isolate overflow-hidden bg-gray-100">
            <!--
           <component :is="getHeaderComponent" :data="publicHeader"></component>

           <component :is="getMenuComponent" :data="publicData.menu"></component>

           -->

            <!-- Background: Square line -->
            <svg class="absolute inset-0 -z-10 h-full w-full stroke-gray-200 [mask-image:radial-gradient(100%_100%_at_top_right,white,transparent)]"
                aria-hidden="true">
                <defs>
                    <pattern id="0787a7c5-978c-4f66-83c7-11c213f99cb7" width="200" height="200" x="50%" y="-1"
                        patternUnits="userSpaceOnUse">
                        <path d="M.5 200V.5H200" fill="none" />
                    </pattern>
                </defs>
                <rect width="100%" height="100%" stroke-width="0" fill="url(#0787a7c5-978c-4f66-83c7-11c213f99cb7)" />
            </svg>

            <!-- Main content of page -->
            <slot />

            <!-- <component :is="getFooterComponent" :data="publicData.footer.data" />-->
       </section>
   </div>

   <!-- <Cookies /> -->
</template>

