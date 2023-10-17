<script setup>
import { trans } from 'laravel-vue-i18n'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faFilter, faTimesCircle } from '@far/'
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

</script>

<template>
    <div class="group relative" :title="trans('Search on table')">
        <input
            :placeholder="trans(label)" :value="value" type="text" name="global" @input="onChange($event.target.value)"
            class="block pl-9 pr-0.5 w-0 group-focus-within:w-full group-focus-within:pr-9 text-sm rounded-md shadow-sm focus:ring-gray-500 focus:border-gray-500 border-gray-300 transition-all duration-150 ease-in-out"
            :class="[value ? 'bg-gray-600 text-white' : 'bg-gray-50']"
        >
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <FontAwesomeIcon icon="far fa-filter" class="h-4 w-4 text-gray-400" aria-hidden="true"
                :class="[value ? 'text-lime-400' : '']" 
            />
        </div>
        <div v-if="value" tabindex="0" class="hidden group-focus-within:flex absolute inset-y-0 right-2  items-center pointer-events-auto cursor-pointer" @click="emit('resetSearch', true)">
            <FontAwesomeIcon icon="far fa-times-circle" class="h-4 w-4 text-gray-400" aria-hidden="true" />
        </div>
    </div>
</template>
