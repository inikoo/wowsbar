<script setup lang="ts">
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { library } from '@fortawesome/fontawesome-svg-core'
import { faSearch } from '@far/'
library.add(faSearch)

</script>

<template>
    <div class="relative">
        <input
            class="block w-full pl-9 pr-9 text-sm rounded-md shadow-sm focus:ring-gray-500 focus:border-gray-500 border-gray-300"
            placeholder="search" type="text" name="global" :readonly="true">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <FontAwesomeIcon icon="far fa-search" class="h-4 w-4 text-gray-400" aria-hidden="true" />
        </div>

    </div>
</template>

