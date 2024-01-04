<script setup lang='ts'>
import { useCopyText } from '@/Composables/useCopyText'

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faCopy, faCheck } from '@fal'
import { library } from '@fortawesome/fontawesome-svg-core'
import { ref } from 'vue'
library.add(faCopy, faCheck)

const props = defineProps<{
    text: string | number
}>()

const waitAfterClick = ref(false)

const onClickCopy = () => {
    waitAfterClick.value = true
    useCopyText(props.text)
    setTimeout(() => {
        waitAfterClick.value = false
    }, 1000)
}

</script>

<template>
    <div class="inline-flex leading-none">
        <!-- After click copy -->
        <div v-if="waitAfterClick" class="py-0.5 px-1">
            <FontAwesomeIcon icon='fal fa-check' class='text-lime-500' aria-hidden='true' />
        </div>

        <!-- Copy button -->
        <div v-else @click="onClickCopy" class="py-0.5 px-1 text-gray-300 hover:text-gray-400 active:text-gray-600 cursor-pointer">
            <FontAwesomeIcon icon='fal fa-copy' class='' aria-hidden='true' />
        </div>
    </div>
</template>