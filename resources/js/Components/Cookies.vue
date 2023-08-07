<script setup lang="ts">
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faCookieBite } from '@/../private/pro-solid-svg-icons'
import { faTimes } from '@/../private/pro-light-svg-icons'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faCookieBite, faTimes)
</script>

<template>
    <div class="fixed bottom-4 right-4 bg-white rounded-lg shadow-md pt-8 pb-6 px-6 flex flex-col justify-start gap-y-2 ring-1 ring-gray-200">
        <!-- <div @click="" class="absolute top-2 right-2 text-gray-400 w-8 h-8 grid place-items-center rounded-full cursor-pointer hover:text-gray-600">
            <FontAwesomeIcon icon='fal fa-times' class='' aria-hidden='true' />
        </div> -->
        <div class="flex gap-x-1">
            <FontAwesomeIcon icon='fas fa-cookie-bite' class='text-yellow-700' size="xl" aria-hidden='true' />
            <div class="text-yellow-800 text-xl font-bold">Yet another cookie disclaimer!</div>
        </div>
        <div class="text-sm text-gray-600">We use cookies to improve your experience.<br>By using Wowsbar you're agreeing to the collection<br>of data as describer in our Cookie Policy.</div>
        <div class="flex gap-x-4 mt-3">
            <div class="py-1 px-4 ring-1 ring-gray-400 rounded-full cursor-pointer bg-white hover:bg-gray-100 active:bg-gray-200 select-none">Necessary cookies only</div>
            <div class="py-1 px-4 text-gray-100 rounded-full cursor-pointer button-wowsbar">Accept all cookies</div>
        </div>
    </div>
</template>

<style scoped>

</style>