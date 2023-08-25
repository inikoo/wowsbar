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

const props = defineProps<{
    title: string,
    banners?: any
}>()

</script>

<template layout="TenantApp">
    <Head :title="capitalize(title)" />
    <div class="max-w-7xl px-4 sm:px-6 lg:px-8 lg:py-6">
        <div class="pt-2 mt-4 lg:mt-0 lg:pt-0 text-2xl">
            {{ trans('Welcome') }}, <span class="font-bold capitalize">{{ useLayoutStore().tenant.name }}</span>!
        </div>
        <hr class="mt-3 mb-6">
        <LastEditedBanners :banners="banners"/>
    </div>
</template>
