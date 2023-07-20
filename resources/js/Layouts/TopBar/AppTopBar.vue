<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 03 Jul 2023 15:46:34 Malaysia Time, Pantai Lembeng, Bali, Id
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import { useLayoutStore } from "@/Stores/layout";
import AppTopBarNavs from "@/Layouts/TopBar/AppTopBarNavs.vue";
import { useAppearanceStore } from "@/Stores/appearance";
const layout = useLayoutStore();

</script>

<template>
    <div class="flex flex-1 items-center justify-between lg:justify-start">
        <Link :href="route('dashboard.show')"
            class="md:pl-3 flex items-center h-full xl:overflow-hidden space-x-2 mr-6 xl:w-56 xl:pr-2 xl:border-r-2 xl:mr-0"
            :class="[useAppearanceStore().darkMode ? 'xl:border-light4' : 'xl:border-light3']"
        >
            <img class="h-7 md:h-5 shadow" :src="`/media/${layout.tenant.logo_id}`" :alt="layout.tenant.code" />
            <span class="hidden leading-none md:inline font-bold  xl:truncate"
                :class="[useAppearanceStore().darkMode ? 'text-light3' : 'text-dark3']"
            >
                {{ layout.tenant.name }}
            </span>
        </Link>
        <AppTopBarNavs />

    </div>
</template>

