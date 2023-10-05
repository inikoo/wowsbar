<script setup>
import { trans } from 'laravel-vue-i18n'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faFilter, faTimesCircle } from "@/../private/pro-regular-svg-icons"
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faFilter, faTimesCircle)

const emit = defineEmits(['resetSearch'])

defineProps({
    label: {
        type: String,
        default: "Search on table...",
        required: false,
    },

    value: {
        type: String,
        default: "",
        required: false,
    },

    onChange: {
        type: Function,
        required: true,
    },
});

const clear =()=>{
    console.log('rtrtrtrttrtrggfgfdsfsjfhgds')
}
</script>

<template>
    <div class="group relative" :title="trans('Search on table')">
        <input
            class="dark:bg-gray-600 block pl-9 pr-0.5 w-0 group-focus-within:w-full group-focus-within:pr-9 text-sm rounded-md shadow-sm focus:ring-gray-500 focus:border-gray-500 dark:focus:border-gray-400 border-gray-300 dark:border-gray-500 transition-all duration-150 ease-in-out"
            :placeholder="trans(label)" :value="value" type="text" name="global" @input="onChange($event.target.value)">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <FontAwesomeIcon icon="far fa-filter" class="h-4 w-4 text-gray-400" aria-hidden="true" />
        </div>
        <div v-if="value" tabindex="0" class="hidden group-focus-within:flex absolute inset-y-0 right-2  items-center pointer-events-auto cursor-pointer" @click="emit('resetSearch', true)">
            <FontAwesomeIcon icon="far fa-times-circle" class="h-4 w-4 text-gray-400" aria-hidden="true" />
        </div>
    </div>
</template>