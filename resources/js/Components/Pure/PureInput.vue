<script setup lang='ts'>
import { useCopyText } from '@/Composables/useCopyText'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faCopy } from '@fal/'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faCopy)

const props = defineProps<{
    modelValue: string
    placeholder?: string
    readonly?: boolean
    inputName?: string
    counter?: boolean
    maxLength?: number
    type?: string
    copyButton?: boolean
}>()

const emits = defineEmits<{
    (e: 'update:modelValue', value: string): void
}>()

</script>

<template>
    <div class="relative">
        <input
            :value="modelValue"
            @input="(event: any) => emits('update:modelValue', event.target.value)"
            :id="inputName"
            :name="inputName"
            :readonly="readonly"
            :type="type ?? 'text'"
            :placeholder="placeholder"
            :maxlength="maxLength"
            class="py-2.5 px-3 block w-full shadow rounded-md
                ring-1 ring-gray-300
                text-gray-600 dark:bg-gray-600 dark:text-gray-400 sm:text-sm border-gray-300 dark:border-gray-500 placeholder:text-gray-400
                focus:ring-2 focus:ring-gray-500 focus:border-gray-500
                read-only:bg-gray-100 read-only:ring-0 read-only:ring-transparent read-only:focus:border-transparent read-only:focus:border-gray-300 read-only:text-gray-500
            "
        />

        <div class="flex absolute inset-y-0 right-0 gap-x-1">
            <div v-if="copyButton"
                class="group cursor-pointer px-2 flex justify-center items-center text-gray-600"
                @click="useCopyText(modelValue)">
                <FontAwesomeIcon icon="fal fa-copy"
                    class="text-lg leading-none opacity-20 group-hover:opacity-75 group-active:opacity-100"
                    aria-hidden="true" />
            </div>
            <div>
                <slot />
            </div>
        </div>
    </div>
</template>
