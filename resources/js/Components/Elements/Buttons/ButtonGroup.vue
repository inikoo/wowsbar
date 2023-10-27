<script setup lang='ts'>
import { Link } from '@inertiajs/vue3'
import Button from '@/Components/Elements/Buttons/Button.vue'
    
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faUpload } from '@fas/'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faUpload)

const props = defineProps<{
    dataButton: any
    dataModal: {
        isModalOpen: boolean
    }
}>()
</script>

<template>
    <div class="rounded overflow-hidden ring-1 ring-gray-400 flex">
        <template v-for="(button, index) in dataButton">
            <Button v-if="index == 0" @click="dataModal.isModalOpen = true" :style="button.style" :label="button.label" :icon="button.icon"
                class="relative capitalize items-center rounded-none text-sm border-none font-medium shadow-sm focus:ring-transparent focus:ring-offset-transparent focus:ring-0">
                <FontAwesomeIcon icon='fas fa-upload' class='' aria-hidden='true' />
                <div class="absolute inset-0 w-full flex items-center justify-center" />
            </Button>
            
            <Link v-else
                :href="`${route(button.route.name, button.route.parameters)}`" class=""
                :method="button.method ?? 'get'"
            >
                <Button :style="button.style" :label="button.label" :icon="button.icon"
                    class="capitalize inline-flex items-center h-full rounded-none text-sm border-none font-medium shadow-sm focus:ring-transparent focus:ring-offset-transparent focus:ring-0">
                </Button>
            </Link>
        </template>
    </div>
</template>