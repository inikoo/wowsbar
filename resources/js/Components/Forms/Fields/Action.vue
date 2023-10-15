<script setup lang="ts">
import {Link, router} from '@inertiajs/vue3'
import Button from '@/Components/Elements/Buttons/Button.vue'


const props = defineProps<{
    action: any,
    dataToSubmit?: any
}>()

const handleClick = () => {
    router.visit(route(props.fieldData.button.route.name, props.fieldData.button.route.parameters), {
        method: props.fieldData.button.method ?? 'get',
        data: props.fieldData.button.data,
    })
}

</script>

<template>
    <!-- Button -->
    <Link v-if="action.type === 'button'" as="button"
          :href="`${route(action['route']['name'], action['route']['parameters'])}`"
          :method="action.method ?? 'get'"
          :data="action.method !== 'get' ? dataToSubmit : null"
    >
        <Button :style="action.style" :label="action.label" :icon="action.icon"
                class="capitalize inline-flex items-center rounded-md text-sm font-medium shadow-sm gap-x-2"
        />
    </Link>

    <!--suppress HtmlUnknownTag -->
    <!-- Button Group () -->
    <div v-if="action.type === 'buttonGroup'" class="first:rounded-l last:rounded-r overflow-hidden ring-1 ring-gray-300 flex">
        <slot v-for="(button, index) in action.buttons" :name="'button' + index">
            <Link
                :href="`${route(button['route']['name'], button['route']['parameters'])}`" class="">
                <Button :style="button.style" :label="button.label" :icon="button.icon"
                        class="capitalize inline-flex items-center rounded-none text-sm border-none font-medium shadow-sm focus:ring-transparent focus:ring-offset-transparent focus:ring-0">

                </Button>
            </Link>
        </slot>
    </div>

    <slot v-if="action.type === 'modal'" name="modal" :data="{...props }"/>


</template>
