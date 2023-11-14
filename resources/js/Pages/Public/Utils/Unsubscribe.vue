<script setup lang='ts'>
import { Head } from '@inertiajs/vue3'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faFrown } from '@fal/'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faFrown)

const props = defineProps<{
    message: string
}>()
</script>

<template layout="Public">
    <Head title="Unsubscribe Emails"/>
    <div class="py-16 sm:py-20">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 text-gray-600 ">
            <div class="bg-[radial-gradient(ellipse_at_bottom,_var(--tw-gradient-stops))] from-violet-50 to-gray-50 relative isolate overflow-hidden px-6 py-24 shadow-xl border-2 border-violet-100 sm:rounded-2xl sm:px-24 xl:py-32">
                <div class="w-full text-center">
                    <FontAwesomeIcon icon='fal fa-frown' class='h-10' aria-hidden='true' />
                </div>
                <h2 class="mx-auto max-w-2xl text-center text-3xl font-semibold tracking-tight sm:text-4xl">
                    Unsubscribe successfully.
                </h2>
                <p class="mx-auto mt-2 max-w-2xl text-center text-lg leading-8 text-gray-500">
                    {{ message }}
                </p>
            </div>
        </div>
    </div>
</template>