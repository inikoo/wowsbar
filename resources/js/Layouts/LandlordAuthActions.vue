<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Tue, 04 Apr 2023 08:47:34 Malaysia Time, Sanur, Bali, Indonesia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup>

import { useLayoutStore } from '@/Stores/layout'
import { usePage } from '@inertiajs/vue3'
import { loadLanguageAsync } from 'laravel-vue-i18n'
import { watchEffect } from 'vue'
import { Link } from "@inertiajs/vue3"

const layout = useLayoutStore()
if (usePage().props.language) {
    loadLanguageAsync(usePage().props.language)
}
watchEffect(() => {
    if (usePage().props.tenant) {
        layout.tenant = usePage().props.tenant ?? null
    }
})


</script>

<template>

    <div class="min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8 ">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <Link :href="route('landlord.welcome')">
                <img class="mx-auto h-16 -mb-3 w-auto" src="/art/logo-no-background.png" alt="Wowsbar" />
            </Link>
        </div>

        <div class="mt-16 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                <slot />
            </div>
        </div>
    </div>
</template>
