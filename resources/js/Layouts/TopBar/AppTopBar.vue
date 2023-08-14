<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 03 Jul 2023 15:46:34 Malaysia Time, Pantai Lembeng, Bali, Indonesia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Link } from "@inertiajs/vue3"
import { useLayoutStore } from "@/Stores/layout"
import AppTopBarNavs from "@/Layouts/TopBar/AppTopBarNavs.vue"
const layout = useLayoutStore()

const props = defineProps<{
    dashboardRoute: string
}>()

</script>

<template>
    <div class="flex flex-1 items-center justify-between lg:justify-start">
        <Link :href="route(dashboardRoute)"
            class="md:pl-3 flex items-center h-full xl:overflow-hidden space-x-2 mr-6 xl:w-56 xl:pr-2 xl:border-r-2 xl:mr-0 xl:border-gray-200 dark:xl:border-gray-500"
        >
            <slot></slot>

        </Link>
        <AppTopBarNavs />
    </div>
</template>

