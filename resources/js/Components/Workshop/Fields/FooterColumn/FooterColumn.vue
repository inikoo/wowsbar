<script setup lang="ts">
import { ref } from 'vue'
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue'

import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome"
import { faAngleDown, faAngleUp } from '@far';
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faAngleDown, faAngleUp )

const props = defineProps<{
    modelValue: any,
}>();

const emits = defineEmits<{
    (e: 'update:modelValue', value: {}): void
}>();

console.log('column',props)

</script>

<template>
     <Disclosure v-slot="{ open }" v-for="(field, index) of modelValue">
        <DisclosureButton
            class="flex w-full justify-between bg-gray-200 px-4 py-1 text-left text-sm 
            font-medium text-gray-900 hover:bg-gray-300 focus:outline-none focus-visible:ring focus-visible:ring-gray-500/75">
            <span class="text-lg font-bold text-gray-600">{{ field.name }}</span>
            <FontAwesomeIcon :icon="open ? faAngleDown : faAngleUp" class="h-5 w-5 text-gray-500" />
        </DisclosureButton>
        <DisclosurePanel class="px-4 bg-gray-100 pb-2 pt-4 text-sm text-gray-500">
            
        </DisclosurePanel>
    </Disclosure>
</template>

<style scoped></style>
