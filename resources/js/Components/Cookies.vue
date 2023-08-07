<template>
    <div class="fixed bottom-4 right-4 bg-white rounded-md shadow-md py-4 px-6 flex flex-col justify-start gap-y-2 ring-1 ring-gray-200">
        <div class="flex gap-x-1">
            <FontAwesomeIcon icon='fas fa-cookie-bite' class='text-yellow-700' size="xl" aria-hidden='true' />
            <div class="text-yellow-800 text-xl font-bold">Yet another cookie disclaimer!</div>
        </div>
        <div class="text-sm text-gray-500">We use cookies to improve your experience.<br>By using Wowsbar you're agreeing to the collection<br>of data as describer in our Cookie Policy.</div>
        <div class="flex gap-x-4 mt-3">
            <div class="py-1 px-3 ring-1 ring-gray-400 rounded-full cursor-pointer">Necessary cookies only</div>
            <div class="py-1 px-3 bg-gray-500 text-gray-100 rounded-full cursor-pointer">Accept all cookies</div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faCookieBite } from '@/../private/pro-solid-svg-icons'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faCookieBite)
</script>

<style scoped>

</style>