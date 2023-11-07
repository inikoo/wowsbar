<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import Button from '@/Components/Elements/Buttons/Button.vue'
import { Action } from '@/types/Action'

interface Route {
    name: string
    parameters: any
}

const props = defineProps<{
    actions: Action
    dataToSubmit?: any
}>()

// console.log(props.action)

</script>

<template>
    <!-- <pre>{{ actions }}</pre> -->
    <!--suppress HtmlUnknownTag -->
    <!-- Button Group () -->
    <div v-if="actions.type === 'buttonGroup'" class="first:rounded-l last:rounded-r overflow-hidden ring-1 ring-gray-300 flex">
        <slot v-for="(button, index) in actions.buttonGroup" :name="'button' + index">
            <Link
                :href="`${button.route?.name ? route(button.route?.name, button.route?.parameters) : '#'}`" class=""
                :method="button.route?.method ?? 'get'"
            >
                <Button :style="button.style" :label="button.label" :icon="button.icon" :iconRight="button.iconRight"
                    class="capitalize inline-flex items-center h-full rounded-none text-sm border-none font-medium shadow-sm focus:ring-transparent focus:ring-offset-transparent focus:ring-0">
                </Button>
            </Link>
        </slot>
    </div>

    <!-- Button -->
    <Link v-else
        :href="`${actions.route ? route(actions.route?.name, actions.route?.parameters) : '#'}`"
        :method="actions.route?.method ?? 'get'"
        :data="actions.route?.method !== 'get' ? dataToSubmit : null"
    >
    <!-- {{ actions }} -->
        <Button :style="actions.style" :label="actions.label" :icon="actions.icon" :iconRight="actions.iconRight" />
    </Link>

    <!-- <slot v-if="button.type === 'modal'" name="modal" :data="{...props }"/> -->

</template>
