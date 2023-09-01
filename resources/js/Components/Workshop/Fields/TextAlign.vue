<script setup lang="ts">
import { ref } from 'vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faAlignLeft, faAlignCenter, faAlignRight } from '@/../private/pro-light-svg-icons'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faAlignLeft, faAlignCenter, faAlignRight)

const props = defineProps<{
    fieldData: {
        options: {
            icon: string
            value: string
        }[]
    },
    fieldName: string[],
}>()

const updateData = (value: string) => {
    // add update data here
}

</script>

<template>
    <div class="py-1">
        <div class="flex gap-x-2">
            <div v-for="option in fieldData.options" @click="updateData(option.value)" class="flex items-center justify-center bg-gray-100 rounded p-2 ring-1 ring-gray-300 cursor-pointer"
                :class="[ true ? 'bg-gray-300' : 'hover:bg-gray-200']"
            >
                <FontAwesomeIcon :icon='option.icon' class='' aria-hidden='true' />
            </div>
        </div>
    </div>
</template>
