<script setup lang="ts">
import { ref } from 'vue'
import { Switch } from '@headlessui/vue'

const props = defineProps<{
    form: any
    fieldName: string
    options?: any
    fieldData?: {
        placeholder: string
        readonly: boolean
        copyButton: boolean
        maxLength?: number
    }
}>()

const enabled = ref(false)
</script>


<template>
    <div class="flex items-center gap-x-2 transition-all duration-1000 ease-in-out">
        <!-- <span class="" :class="!form[fieldName] ? 'text-gray-600 font-semibold' : 'text-gray-400'">Disabled</span> -->
        <Switch v-model="form[fieldName]" :class="form[fieldName] ? 'bg-slate-600' : 'bg-slate-300'"
            class="pr-1 relative inline-flex h-6 w-12 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75">
            <span class="sr-only">Use setting</span>
            <span aria-hidden="true" :class="form[fieldName] ? 'translate-x-6' : 'translate-x-0'"
                class="pointer-events-none inline-block h-full w-1/2 transform rounded-full bg-white shadow-lg ring-0 transition duration-200 ease-in-out" />
        </Switch>
        <!-- <span class="" :class="form[fieldName] ? 'text-gray-600 font-semibold' : 'text-gray-400'">Enabled</span> -->
    </div>
</template>