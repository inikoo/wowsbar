<!--
  - Author: Artha <artha@aw-advantage.com>
  - Created: Wed, 07 Jun 2023 09:03:33 Central Indonesia Time, Sanur, Bali, Indonesia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import {capitalize} from "@/Composables/capitalize"
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome'
import {faFirefoxBrowser, faWindows, faChrome} from "@fortawesome/free-brands-svg-icons"
import {faDesktop, faMobile, faDesktopAlt} from '@far/'
import {library} from '@fortawesome/fontawesome-svg-core'

library.add(faDesktop, faMobile, faDesktopAlt, faFirefoxBrowser, faWindows, faChrome)

const props = defineProps<{
    data: [
        string,
        string,
        string
    ]
}>()

// To make it safe when data[0] is empty
const parseJson = (data: any) => {
    return data ? JSON.parse(data) : ''
}


</script>

<template>
    <div class="flex text-gray-600">
        <FontAwesomeIcon :icon='(parseJson(data[0])).icon' class='w-6' aria-hidden='true' :title='capitalize((parseJson(data[0])).title)'/>
        <FontAwesomeIcon :icon='(parseJson(data[1])).icon' class='w-6' aria-hidden='true' :title='capitalize((parseJson(data[1])).title)'/>
        <FontAwesomeIcon :icon='(parseJson(data[2])).icon' class='w-6' aria-hidden='true' :title='capitalize((parseJson(data[2])).title)'/>
    </div>
</template>
