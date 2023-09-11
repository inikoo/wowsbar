<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 14 Aug 2023 08:45:47 Malaysia Time, Sanur, Bali
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { capitalize } from "@/Composables/capitalize"
import { trans } from 'laravel-vue-i18n'
import { useLayoutStore } from '@/Stores/layout'
import LastEditedBanners from '@/Components/LastEditedBanners.vue'

const currentHour = new Date().getHours();

const greetingMessage =
    currentHour >= 4 && currentHour < 12 ? // after 4:00AM and before 12:00PM
        'Good morning' :
    currentHour >= 12 && currentHour <= 17 ? // after 12:00PM and before 6:00pm
        'Good afternoon' :
    currentHour > 17 || currentHour < 4 ? // after 5:59pm or before 4:00AM (to accommodate night owls)
        'Good evening' :
    'Welcome' // if for some reason the calculation didn't work

const layout = useLayoutStore()

const props = defineProps<{
    title: string,
    banners?: any,
    userName: string
}>()

</script>

<template layout="TenantApp">
    <Head :title="capitalize(title)" />
    <!-- <pre>{{ layout.user }}</pre> -->
    <div class="max-w-7xl px-4 sm:px-6 lg:px-8 lg:py-6">
        <div class="pt-2 mt-4 lg:mt-0 lg:pt-0 text-2xl">
            {{ trans(greetingMessage) }}, <span class="font-bold capitalize">{{ userName}}</span>!
        </div>
        <hr class="mt-3 mb-6">
        <LastEditedBanners :banners="banners"/>
    </div>
</template>
