<script setup lang="ts">
import { router } from '@inertiajs/vue3'
import Button from '@/Components/Elements/Buttons/Button.vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faRocketLaunch } from '@/../private/pro-light-svg-icons'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faRocketLaunch)

const props = defineProps<{
    fieldData: {
        label: string,
        icon?: string
        button: {
            style?: string
            method: any
            data: any
            route: {
                name: string
                parameters?: string
            }
        }
    }
}>()

const handleClick = () => {
    router.visit(route(props.fieldData.button.route.name, props.fieldData.button.route.parameters), {
        method: props.fieldData.button.method ?? 'get',
        data: props.fieldData.button.data,
    })
}

</script>

<template>
    <Button :style="props.fieldData.button.style" @click="handleClick">
        {{ fieldData.label }}
        <FontAwesomeIcon v-if="fieldData.icon" :icon='fieldData.icon' class='ml-1' aria-hidden='true' />
    </Button>
</template>