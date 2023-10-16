<script setup lang='ts'>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faPencil, faTrashAlt } from '@/../private/pro-light-svg-icons'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faPencil, faTrashAlt)
import { Link } from '@inertiajs/vue3';
import Button from './Button.vue';
    
const props = defineProps<{
    iconAction: any
}>()

</script>

<template>
        <Link :href="route(iconAction.href.name,iconAction.href.parameters)" :title="iconAction.tooltip"
            class=""
        >
            <div class="h-8 flex justify-center items-center aspect-square rounded-md border border-gray-200 bg-white text-gray-600"
                :class="iconAction.style == 'negative' ? 'text-red-700 border-red-100' : ''">
                <FontAwesomeIcon :icon='iconAction.icon' class='' aria-hidden='true' />
            </div>
        </Link>
</template>