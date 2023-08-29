<script setup>
import { trans } from 'laravel-vue-i18n'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faSearch, faTimesCircle } from "@/../private/pro-regular-svg-icons"
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faSearch,faTimesCircle)

const emit = defineEmits(['resetSearch'])

defineProps({
    label: {
        type: String,
        default: "Search...",
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
   <div class="relative">
        <input
            class="dark:bg-gray-600 block w-full pl-9 pr-9 text-sm rounded-md shadow-sm focus:ring-gray-500 focus:border-gray-500 dark:focus:border-gray-400 border-gray-300 dark:border-gray-500"
            :placeholder="trans(label)" :value="value" type="text" name="global" @input="onChange($event.target.value)">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <FontAwesomeIcon icon="far fa-search" class="h-4 w-4 text-gray-400" aria-hidden="true" />
        </div>
        <div v-if="value" class="absolute inset-y-0 right-2 flex items-center pointer-events-auto cursor-pointer" @click="emit('resetSearch', true)">
            <font-awesome-icon :icon="['far', 'times-circle']" class="h-4 w-4 text-gray-400" aria-hidden="true" />
        </div>
    </div>
</template>