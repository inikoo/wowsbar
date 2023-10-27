<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import Button from '@/Components/Elements/Buttons/Button.vue'

interface Route {
    name: string
    parameters: any
}

const props = defineProps<{
    action: {
        icon?: string | string[]
        label?: string
        method?: string
        route: Route
        style: string
        type: string
        buttons?: {
            route: Route
            style: string
            label?: string
            icon?: string | string[]
            method?: string
        }[]
    },
    dataToSubmit?: any
}>()


</script>

<template>
    <!-- Button -->
    <Link v-if="action.type === 'button'"
        :href="`${route(action.route.name, action.route.parameters)}`"
        :method="action.method ?? 'get'"
        :data="action.method !== 'get' ? dataToSubmit : null"
    >
        <Button :style="action.style" :label="action.label" :icon="action.icon" />
    </Link>

    <!--suppress HtmlUnknownTag -->
    <!-- Button Group () -->
    <div v-if="action.type === 'buttonGroup'" class="first:rounded-l last:rounded-r overflow-hidden ring-1 ring-gray-300 flex">
        <slot v-for="(button, index) in action.buttons" :name="'button' + index">
            <Link
                :href="`${route(button.route.name, button.route.parameters)}`" class=""
                :method="button.method ?? 'get'"
            >
                <Button :style="button.style" :label="button.label" :icon="button.icon"
                        class="capitalize inline-flex items-center h-full rounded-none text-sm border-none font-medium shadow-sm focus:ring-transparent focus:ring-offset-transparent focus:ring-0">
                </Button>
            </Link>
        </slot>
    </div>

    <slot v-if="action.type === 'modal'" name="modal" :data="{...props }"/>

</template>
