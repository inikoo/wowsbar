<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Sun, 13 Aug 2023 09:41:55 Malaysia Time, Sanur, Bali
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { capitalize } from "@/Composables/capitalize"
import QrcodeVue from 'qrcode.vue'
import Button from '@/Components/Elements/Buttons/Button.vue'
import { ref } from 'vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faSpinnerThird } from '@fad'
import { faSync } from '@fal'
import { library } from '@fortawesome/fontawesome-svg-core'
import { useTimeCountdown } from '@/Composables/useFormatTime'
library.add(faSpinnerThird, faSync)

const props = defineProps<{
    title: string,
}>()

const isQrCode = ref(false)
const isLoading = ref(false)
const isRegenerating = ref(false)
const timeCountdown = ref('')
const intervalCountdown = ref()
const randomString = ref('')

// Method: Generate new qr
const onGenerateQr = async () => {
    isLoading.value = true
    setTimeout(() => {
        isQrCode.value = true
        randomString.value = generateRandomString()
        setCountdown(300)
        isLoading.value = false
    }, 1200)
}

// Method: Regenerate the QR
const onRegenerateQr = async () => {
    isRegenerating.value = true
    setTimeout(() => {
        randomString.value = generateRandomString()
        setCountdown(300)
        isRegenerating.value = false
    }, 700)
}

// Method: random string
const generateRandomString = (length = 100) => {
    const characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'
    let result = ''
    for (let i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() * characters.length))
    }
    return result
}

// Set countdown
const setCountdown = (duration: number) => {
    let date = (new Date()).setSeconds((new Date()).getSeconds() + duration)
    clearInterval(intervalCountdown.value)
    setTimeout(() => {
        // To handle stepped 2 seconds at early
        timeCountdown.value = useTimeCountdown(date, { human: true })
    }, 50)
    
    intervalCountdown.value = setInterval(() => {
        timeCountdown.value = useTimeCountdown(date, { human: true })
    }, 1000)
}
</script>

<template layout="OrgApp">
    <Head :title="capitalize(title)" />

    <div class="max-w-7xl px-4 sm:px-6 lg:px-8 lg:py-6">
        <div v-if="!isQrCode" class="mt-10 ml-6">
            <Button label="Click to generate" :style="isLoading ? 'disabled' : 'rainbow'" :key="isLoading.toString()" @click="onGenerateQr" :loading="isLoading" size="xl" /></div>
        <div v-else>
            <template v-if="!isRegenerating">
                <div class="align-middle flex items-center gap-x-3">
                    <qrcode-vue :value="randomString" :size="200" level="L" render-as="svg" />
                    <div @click="onRegenerateQr()" v-tooltip="'Regenerate QR Code'" class="cursor-pointer p-0.5 text-gray-400 hover:text-gray-600">
                        <FontAwesomeIcon icon='fal fa-sync' :class="isRegenerating ? 'animate-spin' : ''" class='h-5' aria-hidden='true' />
                    </div>
                </div>
                <p v-if="timeCountdown" class="mt-1 text-sm text-gray-500 tabular-nums">This QR Code valid for {{ timeCountdown }}.</p>
            </template>
            <div v-else class="h-[200px] aspect-square skeleton" />
        </div>
    </div>
</template>
