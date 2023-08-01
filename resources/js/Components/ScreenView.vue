<script setup lang="ts">
import { ref } from 'vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faDesktop, faMobileAndroidAlt, faTabletAndroidAlt } from '@/../private/pro-solid-svg-icons'
import {  } from '@/../private/pro-regular-svg-icons'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faDesktop, faMobileAndroidAlt, faTabletAndroidAlt)

defineEmits<{
    (e: 'screenView', id: string): void
}>()

const screenView = ref('desktop')

</script>

<template>
    <div class="flex gap-x-2 py-2">
        <div 
            class="border border-gray-300 py-1 px-2 cursor-pointer rounded" title="Mobile view"
            :class="[screenView == 'mobile' ? 'bg-gray-300/70' : 'hover:bg-gray-200/60']"
            @click="screenView = 'mobile', $emit('screenView', 'mobile')"
        >
            <FontAwesomeIcon icon='fas fa-mobile-android-alt' class='' aria-hidden='true' />
        </div>
        <div 
            class="border border-gray-300 py-1 px-2 cursor-pointer rounded" title="Tablet view"
            :class="[screenView == 'tablet' ? 'bg-gray-300/70' : 'hover:bg-gray-200/60']"
            @click="screenView = 'tablet', $emit('screenView', 'tablet')"
        >
            <FontAwesomeIcon icon='fas fa-tablet-android-alt' class='' aria-hidden='true' />
        </div>
        <div
            class="border border-gray-300 py-1 px-2 cursor-pointer rounded" title="Desktop view"
            :class="[screenView == 'desktop' ? 'bg-gray-300/70' : 'hover:bg-gray-200/60']"
            @click="screenView = 'desktop', $emit('screenView', 'desktop')"
        >
            <FontAwesomeIcon icon='fas fa-desktop' class='' aria-hidden='true' />
        </div>
    </div>
</template>

<style scoped></style>